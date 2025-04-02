<?php
require_once 'config.php';

$produtos = [];
$erro = null;

try {
    // Buscar todos os produtos com suas categorias e fornecedores
    $sql = "SELECT p.*, c.nome as categoria_nome, f.nome as fornecedor_nome 
            FROM produtos p
            LEFT JOIN categorias c ON p.categoria_id = c.id 
            LEFT JOIN fornecedores f ON p.fornecedor_id = f.id 
            ORDER BY p.nome";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $erro = "Erro ao carregar produtos: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Produtos - THINFORMA</title>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<?php include 'menu.php'; ?>

<div class="container-fluid py-4">
    <?php if ($erro): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $erro; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">Lista de Produtos</h4>
            <a href="cadastrar_produto.php" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Novo Produto
            </a>
        </div>
        <div class="card-body">
            <?php if(isset($_SESSION['sucesso'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php 
                    echo $_SESSION['sucesso'];
                    unset($_SESSION['sucesso']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['erro'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php 
                    echo $_SESSION['erro'];
                    unset($_SESSION['erro']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table id="tabelaProdutos" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nome</th>
                            <th>Categoria</th>
                            <th>Fornecedor</th>
                            <th>Preço Custo</th>
                            <th>Margem</th>
                            <th>Preço Venda</th>
                            <th>Estoque</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($produtos)): ?>
                            <?php foreach ($produtos as $produto): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($produto['codigo']); ?></td>
                                    <td>
                                        <?php if ($produto['imagem']): ?>
                                            <img src="uploads/miniaturas/<?php echo basename($produto['imagem']); ?>" 
                                                 alt="<?php echo htmlspecialchars($produto['nome']); ?>"
                                                 class="img-thumbnail me-2" style="max-width: 50px;">
                                        <?php endif; ?>
                                        <?php echo htmlspecialchars($produto['nome']); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($produto['categoria_nome']); ?></td>
                                    <td><?php echo htmlspecialchars($produto['fornecedor_nome']); ?></td>
                                    <td>R$ <?php echo number_format($produto['preco_custo'], 2, ',', '.'); ?></td>
                                    <td><?php echo number_format($produto['margem_lucro'], 2, ',', '.'); ?>%</td>
                                    <td>R$ <?php echo number_format($produto['preco_venda'], 2, ',', '.'); ?></td>
                                    <td>
                                        <?php 
                                        $estoque_class = $produto['estoque_atual'] <= $produto['estoque_minimo'] ? 'text-danger' : 'text-success';
                                        echo "<span class='$estoque_class'>";
                                        echo number_format($produto['estoque_atual'], 2, ',', '.') . ' ' . $produto['unidade'];
                                        echo "</span>";
                                        ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="visualizar_produto.php?id=<?php echo $produto['id']; ?>" 
                                               class="btn btn-sm btn-info" title="Visualizar">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="editar_produto.php?id=<?php echo $produto['id']; ?>" 
                                               class="btn btn-sm btn-warning" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" title="Excluir"
                                                    onclick="confirmarExclusao(<?php echo $produto['id']; ?>)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div class="modal fade" id="modalExclusao" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                Tem certeza que deseja excluir este produto?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="formExclusao" action="excluir_produto.php" method="POST" style="display: inline;">
                    <input type="hidden" id="produto_id" name="id">
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // Inicializa DataTables
    $('#tabelaProdutos').DataTable({
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json'
        },
        order: [[1, 'asc']], // Ordena pela coluna nome (índice 1)
        columnDefs: [
            {
                targets: -1, // Última coluna (ações)
                orderable: false
            }
        ]
    });
});

function confirmarExclusao(id) {
    document.getElementById('produto_id').value = id;
    var modal = new bootstrap.Modal(document.getElementById('modalExclusao'));
    modal.show();
}
</script>

</body>
</html>
