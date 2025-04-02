<?php
require_once 'conexao.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mensagem = '';
$categoria = null;

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: listar_categorias.php");
    exit();
}

$id = $_GET['id'];

// Buscar dados da categoria
$sql = "SELECT * FROM categorias WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$categoria = $stmt->fetch();

if (!$categoria) {
    header("Location: listar_categorias.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $nome = $_POST['nome'];
        $descricao = $_POST['descricao'];

        $sql = "UPDATE categorias SET nome = ?, descricao = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$nome, $descricao, $id]);

        header("Location: listar_categorias.php?sucesso=1");
        exit();
    } catch (PDOException $e) {
        $mensagem = "Erro ao atualizar categoria: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Categoria - THINFORMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Editar Categoria - THINFORMA</h2>
            <div>
                <div class="btn-group">
                    <a href="cadastrar_produto.php" class="btn btn-primary btn-sm">Cadastrar Produto</a>
                    <a href="listar_produtos.php" class="btn btn-primary btn-sm">Listar Produtos</a>
                </div>
                <div class="btn-group ms-2">
                    <a href="cadastrar_fornecedor.php" class="btn btn-info btn-sm">Cadastrar Fornecedor</a>
                    <a href="listar_fornecedores.php" class="btn btn-info btn-sm">Listar Fornecedores</a>
                </div>
                <div class="btn-group ms-2">
                    <a href="cadastrar_categoria.php" class="btn btn-warning btn-sm">Cadastrar Categoria</a>
                    <a href="listar_categorias.php" class="btn btn-warning btn-sm">Listar Categorias</a>
                </div>
                <div class="btn-group ms-2">
                    <a href="novo_orcamento.php" class="btn btn-success btn-sm">Novo Orçamento</a>
                    <a href="listar_orcamentos.php" class="btn btn-dark btn-sm">Orçamentos</a>
                </div>
            </div>
        </div>

        <?php if ($mensagem): ?>
            <div class="alert alert-danger"><?php echo $mensagem; ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome da Categoria*</label>
                        <input type="text" class="form-control" id="nome" name="nome" 
                               value="<?php echo htmlspecialchars($categoria['nome']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control" id="descricao" name="descricao" 
                                  rows="3"><?php echo htmlspecialchars($categoria['descricao']); ?></textarea>
                    </div>

                    <div class="text-end">
                        <a href="listar_categorias.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Atualizar Categoria</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 