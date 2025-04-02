<?php
require_once 'config.php';

try {
    // Verifica se o ID foi fornecido
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        throw new Exception("ID do produto não fornecido");
    }

    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    if (!$id) {
        throw new Exception("ID do produto inválido");
    }

    // Inicia a transação
    $pdo->beginTransaction();

    // Busca informações do produto antes de excluir
    $sql = "SELECT imagem FROM produtos WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $produto = $stmt->fetch();

    if (!$produto) {
        throw new Exception("Produto não encontrado");
    }

    // Exclui registros relacionados
    $sql = "DELETE FROM historico_precos WHERE produto_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    // Exclui o produto
    $sql = "DELETE FROM produtos WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    // Se tiver imagem, remove os arquivos
    if ($produto['imagem']) {
        $imagem_path = $produto['imagem'];
        $miniatura_path = 'uploads/miniaturas/' . basename($produto['imagem']);
        
        if (file_exists($imagem_path)) {
            unlink($imagem_path);
        }
        if (file_exists($miniatura_path)) {
            unlink($miniatura_path);
        }
    }

    // Commit da transação
    $pdo->commit();

    $_SESSION['sucesso'] = "Produto excluído com sucesso!";

} catch (Exception $e) {
    // Rollback em caso de erro
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $_SESSION['erro'] = "Erro ao excluir produto: " . $e->getMessage();
}

// Redireciona de volta para a lista
header("Location: listar_produtos.php");
exit(); 