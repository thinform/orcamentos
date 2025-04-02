<?php
date_default_timezone_set('America/Sao_Paulo');
$novo_numero = date('YmdHis');

$host = 'thinforma.mysql.dbaas.com.br';
$db = 'thinforma';
$user = 'thinforma';
$pass = 'Lordac01#';
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Erro: " . $conn->connect_error);
}

$id_original = intval($_GET['id'] ?? 0);

// Buscar orçamento original
$sql = "SELECT * FROM orcamentos WHERE id = $id_original";
$res = $conn->query($sql);
$original = $res->fetch_assoc();

if (!$original) {
    die("Orçamento não encontrado.");
}

// Criar novo orçamento com os mesmos dados, exceto o número e data
$stmt = $conn->prepare("INSERT INTO orcamentos (numero, cliente, data_emissao, validade, frete, desconto_aplicado, valor_total, observacoes) VALUES (?, ?, CURDATE(), ?, ?, ?, ?, ?)");
$stmt->bind_param("ssiddds", $novo_numero, $original['cliente'], $original['validade'], $original['frete'], $original['desconto_aplicado'], $original['valor_total'], $original['observacoes']);
$stmt->execute();
$id_novo = $stmt->insert_id;
$stmt->close();

// Duplicar itens
$res_itens = $conn->query("SELECT * FROM itens_orcamento WHERE id_orcamento = $id_original");
$stmt_item = $conn->prepare("INSERT INTO itens_orcamento (id_orcamento, produto, quantidade, valor_unitario, valor_total) VALUES (?, ?, ?, ?, ?)");

while ($item = $res_itens->fetch_assoc()) {
    $stmt_item->bind_param("isddd", $id_novo, $item['produto'], $item['quantidade'], $item['valor_unitario'], $item['valor_total']);
    $stmt_item->execute();
}
$stmt_item->close();
$conn->close();

header("Location: ver_orcamento.php?id=$id_novo");
exit;
?>
