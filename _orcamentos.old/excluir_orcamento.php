<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = 'thinforma.mysql.dbaas.com.br';
$db = 'thinforma';
$user = 'thinforma';
$pass = 'Lordac01#';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    die("ID do orçamento inválido");
}

// Inicia a transação
$conn->begin_transaction();

try {
    // Primeiro exclui os itens do orçamento
    $sql = "DELETE FROM itens_orcamento WHERE orcamento_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Depois exclui o orçamento
    $sql = "DELETE FROM orcamentos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Se chegou até aqui, confirma as alterações
    $conn->commit();

    // Redireciona de volta para a lista com mensagem de sucesso
    header("Location: listar_orcamentos.php?msg=excluido");
    exit;

} catch (Exception $e) {
    // Se houver erro, desfaz as alterações
    $conn->rollback();
    die("Erro ao excluir orçamento: " . $e->getMessage());
}

$conn->close();
?> 