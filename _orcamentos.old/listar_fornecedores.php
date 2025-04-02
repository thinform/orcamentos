<?php
require_once 'conexao.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Buscar todos os fornecedores
$sql = "SELECT * FROM fornecedores ORDER BY nome";
$stmt = $conn->prepare($sql);
$stmt->execute();
$fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fornecedores - THINFORMA</title>
</head>
<body class="bg-light">

<?php include 'menu.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Fornecedores</h2>
    </div>

    <?php if (isset($_GET['sucesso'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Operação realizada com sucesso!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="fornecedoresTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>CNPJ</th>
                            <th>Telefone</th>
                            <th>E-mail</th>
                            <th>Contato</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($fornecedores as $fornecedor): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($fornecedor['nome']); ?></td>
                                <td><?php echo htmlspecialchars($fornecedor['cnpj']); ?></td>
                                <td><?php echo htmlspecialchars($fornecedor['telefone']); ?></td>
                                <td><?php echo htmlspecialchars($fornecedor['email']); ?></td>
                                <td><?php echo htmlspecialchars($fornecedor['contato']); ?></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="editar_fornecedor.php?id=<?php echo $fornecedor['id']; ?>" 
                                           class="btn btn-sm btn-primary">
                                            Editar
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger"
                                                onclick="confirmarExclusao(<?php echo $fornecedor['id']; ?>)">
                                            Excluir
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#fornecedoresTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json'
            }
        });
    });

    function confirmarExclusao(id) {
        if (confirm('Tem certeza que deseja excluir este fornecedor?')) {
            window.location.href = 'excluir_fornecedor.php?id=' + id;
        }
    }
</script>
</body>
</html> 