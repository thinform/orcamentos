<?php
require_once 'conexao.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: listar_fornecedores.php");
    exit();
}

$id = $_GET['id'];

try {
    $conn->beginTransaction();

    // Verificar se existem produtos vinculados ao fornecedor
    $sql = "SELECT COUNT(*) as total FROM produtos WHERE fornecedor_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    $result = $stmt->fetch();

    if ($result['total'] > 0) {
        throw new Exception("Não é possível excluir este fornecedor pois existem produtos vinculados a ele.");
    }

    // Excluir o fornecedor
    $sql = "DELETE FROM fornecedores WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    if ($stmt->rowCount() === 0) {
        throw new Exception("Fornecedor não encontrado.");
    }

    $conn->commit();
    header("Location: listar_fornecedores.php?sucesso=1");
    exit();
} catch (Exception $e) {
    $conn->rollBack();
    die("Erro ao excluir fornecedor: " . $e->getMessage());
} 