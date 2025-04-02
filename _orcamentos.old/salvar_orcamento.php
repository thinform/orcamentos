<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Arquivo de log para debug
error_log("Iniciando processamento do orçamento");

$host = 'thinforma.mysql.dbaas.com.br';
$db = 'thinforma';
$user = 'thinforma';
$pass = 'Lordac01#'; 
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Erro: " . $conn->connect_error);
}

// Debug do valor recebido
error_log("Número do orçamento recebido: " . (isset($_POST['numero_orcamento']) ? $_POST['numero_orcamento'] : 'não definido'));

$numero = isset($_POST['numero_orcamento']) ? trim($_POST['numero_orcamento']) : '';

// Função para verificar se o número já existe
function numeroExiste($conn, $numero) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM orcamentos WHERE numero_orcamento = ?");
    $stmt->bind_param("s", $numero);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_row()[0];
    $stmt->close();
    return $count > 0;
}

// Se número estiver vazio ou já existir, gerar um novo
$maxTentativas = 10;
$tentativa = 0;

while (empty($numero) || numeroExiste($conn, $numero)) {
    $tentativa++;
    if ($tentativa > $maxTentativas) {
        die("Erro: Não foi possível gerar um número único de orçamento após $maxTentativas tentativas");
    }
    $numero = date('YmdHis') . rand(100, 999);
    error_log("Tentativa $tentativa: Gerando novo número: $numero");
    // Pequena pausa para garantir timestamp único
    usleep(100000); // 100ms
}

error_log("Número final do orçamento: " . $numero);

$data = $_POST['data_emissao'];
$validade = intval($_POST['validade']);
$cliente_id = intval($_POST['cliente_id']);
$frete = floatval($_POST['frete']);
$desconto = floatval($_POST['desconto_aplicado']);
$obs = $_POST['observacoes'];

$produtos = $_POST['produto_nome'];
$quantidades = $_POST['quantidade'];
$valores = $_POST['valor_unitario'];

$total = 0;
for ($i = 0; $i < count($produtos); $i++) {
    $qtd = floatval($quantidades[$i]);
    $val = floatval($valores[$i]);
    $total += $qtd * $val;
}
$valor_total = $total + $frete - $desconto;

$stmt = $conn->prepare("INSERT INTO orcamentos (numero_orcamento, cliente_id, data_emissao, validade, frete, desconto_aplicado, valor_total, observacoes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    die("Erro prepare: " . $conn->error);
}
$stmt->bind_param("sissddds", $numero, $cliente_id, $data, $validade, $frete, $desconto, $valor_total, $obs);
if (!$stmt->execute()) {
    die("Erro execute: " . $stmt->error);
}
$id_orcamento = $stmt->insert_id;
$stmt->close();

// Depois insere os itens do orçamento
$stmt = $conn->prepare("INSERT INTO itens_orcamento (orcamento_id, produto_id, quantidade, valor_unitario, valor_total) VALUES (?, ?, ?, ?, ?)");
if (!$stmt) {
    die("Erro prepare (itens): " . $conn->error);
}

for ($i = 0; $i < count($produtos); $i++) {
    $produto_id = intval($produtos[$i]); // Convertendo para inteiro pois é o ID do produto
    $q = floatval($quantidades[$i]);
    $vu = floatval($valores[$i]);
    $vt = $q * $vu;
    $stmt->bind_param("iiddd", $id_orcamento, $produto_id, $q, $vu, $vt);
    if (!$stmt->execute()) {
        die("Erro execute (itens): " . $stmt->error);
    }
}

$stmt->close();
$conn->close();

header("Location: listar_orcamentos.php?sucesso=1");
exit;
?>
