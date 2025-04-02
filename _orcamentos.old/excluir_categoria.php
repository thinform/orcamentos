<?php
require_once 'conexao.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: listar_categorias.php");
    exit();
}

$id = $_GET['id'];

try {
    $conn->beginTransaction();

    // Verificar se existem produtos vinculados à categoria
    $sql = "SELECT COUNT(*) as total FROM produtos WHERE categoria_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    $result = $stmt->fetch();

    if ($result['total'] > 0) {
        throw new Exception("Não é possível excluir esta categoria pois existem produtos vinculados a ela.");
    }

    // Excluir a categoria
    $sql = "DELETE FROM categorias WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    if ($stmt->rowCount() === 0) {
        throw new Exception("Categoria não encontrada.");
    }

    $conn->commit();
    header("Location: listar_categorias.php?sucesso=1");
    exit();
} catch (Exception $e) {
    $conn->rollBack();
    die("Erro ao excluir categoria: " . $e->getMessage());
} 