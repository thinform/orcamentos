<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Arquivo de log para debug
error_log("Iniciando busca de dados");

$host = 'thinforma.mysql.dbaas.com.br';
$db = 'thinforma';
$user = 'thinforma';
$pass = 'Lordac01#';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    error_log("Erro de conexão: " . $conn->connect_error);
    die(json_encode(['erro' => 'Erro de conexão: ' . $conn->connect_error]));
}

$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
$termo = isset($_GET['termo']) ? $conn->real_escape_string($_GET['termo']) : '';

error_log("Tipo de busca: " . $tipo);
error_log("Termo de busca: " . $termo);

if ($tipo == 'produtos') {
    $sql = "SELECT id, codigo, descricao, preco_venda FROM produtos WHERE codigo LIKE ? OR descricao LIKE ? LIMIT 10";
    $stmt = $conn->prepare($sql);
    $termo = "%$termo%";
    $stmt->bind_param("ss", $termo, $termo);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $produtos = [];
    while ($row = $result->fetch_assoc()) {
        $produtos[] = [
            'id' => $row['id'],
            'value' => $row['codigo'] . ' - ' . $row['descricao'],
            'codigo' => $row['codigo'],
            'descricao' => $row['descricao'],
            'preco_venda' => $row['preco_venda']
        ];
    }
    echo json_encode($produtos);

} elseif ($tipo == 'clientes') {
    // Primeiro vamos fazer um SELECT simples para ver se existem clientes
    $result = $conn->query("SELECT COUNT(*) as total FROM clientes");
    $count = $result->fetch_assoc()['total'];
    error_log("Total de clientes na base: " . $count);

    // Agora vamos fazer a busca simplificada
    $sql = "SELECT * FROM clientes WHERE nome LIKE ? LIMIT 10";
    error_log("SQL Query: " . $sql);
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Erro no prepare: " . $conn->error);
        die(json_encode(['erro' => 'Erro no prepare: ' . $conn->error]));
    }

    $termo = "%$termo%";
    $stmt->bind_param("s", $termo);
    
    if (!$stmt->execute()) {
        error_log("Erro no execute: " . $stmt->error);
        die(json_encode(['erro' => 'Erro no execute: ' . $stmt->error]));
    }
    
    $result = $stmt->get_result();
    error_log("Número de resultados encontrados: " . $result->num_rows);
    
    $clientes = [];
    while ($row = $result->fetch_assoc()) {
        error_log("Cliente encontrado: " . json_encode($row));
        $clientes[] = [
            'id' => $row['id'],
            'value' => $row['nome'],
            'nome' => $row['nome']
        ];
    }
    echo json_encode($clientes);
}

$conn->close();
?> 