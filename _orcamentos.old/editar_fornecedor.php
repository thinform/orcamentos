<?php
require_once 'conexao.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mensagem = '';
$fornecedor = null;

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: listar_fornecedores.php");
    exit();
}

$id = $_GET['id'];

// Buscar dados do fornecedor
$sql = "SELECT * FROM fornecedores WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$fornecedor = $stmt->fetch();

if (!$fornecedor) {
    header("Location: listar_fornecedores.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $nome = $_POST['nome'];
        $cnpj = $_POST['cnpj'];
        $endereco = $_POST['endereco'];
        $telefone = $_POST['telefone'];
        $email = $_POST['email'];
        $contato = $_POST['contato'];

        $sql = "UPDATE fornecedores SET 
                nome = ?, cnpj = ?, endereco = ?, 
                telefone = ?, email = ?, contato = ? 
                WHERE id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([$nome, $cnpj, $endereco, $telefone, $email, $contato, $id]);

        header("Location: listar_fornecedores.php?sucesso=1");
        exit();
    } catch (PDOException $e) {
        $mensagem = "Erro ao atualizar fornecedor: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Fornecedor - THINFORMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Editar Fornecedor - THINFORMA</h2>
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
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nome" class="form-label">Nome*</label>
                            <input type="text" class="form-control" id="nome" name="nome" 
                                   value="<?php echo htmlspecialchars($fornecedor['nome']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cnpj" class="form-label">CNPJ*</label>
                            <input type="text" class="form-control" id="cnpj" name="cnpj" 
                                   value="<?php echo htmlspecialchars($fornecedor['cnpj']); ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="endereco" class="form-label">Endereço</label>
                        <input type="text" class="form-control" id="endereco" name="endereco" 
                               value="<?php echo htmlspecialchars($fornecedor['endereco']); ?>">
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="telefone" class="form-label">Telefone*</label>
                            <input type="text" class="form-control" id="telefone" name="telefone" 
                                   value="<?php echo htmlspecialchars($fornecedor['telefone']); ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($fornecedor['email']); ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="contato" class="form-label">Pessoa de Contato</label>
                            <input type="text" class="form-control" id="contato" name="contato" 
                                   value="<?php echo htmlspecialchars($fornecedor['contato']); ?>">
                        </div>
                    </div>

                    <div class="text-end">
                        <a href="listar_fornecedores.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Atualizar Fornecedor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#cnpj').mask('00.000.000/0000-00');
            $('#telefone').mask('(00) 00000-0000');
        });
    </script>
</body>
</html> 