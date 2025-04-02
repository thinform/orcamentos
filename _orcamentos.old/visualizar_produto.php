<?php
require_once 'config.php';

try {
    // Verifica se o ID foi fornecido
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        throw new Exception("ID do produto não fornecido");
    }

    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if (!$id) {
        throw new Exception("ID do produto inválido");
    }

    // Busca informações do produto
    $sql = "SELECT p.*, c.nome as categoria_nome, f.nome as fornecedor_nome 
            FROM produtos p 
            LEFT JOIN categorias c ON p.categoria_id = c.id 
            LEFT JOIN fornecedores f ON p.fornecedor_id = f.id 
            WHERE p.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $produto = $stmt->fetch();

    if (!$produto) {
        throw new Exception("Produto não encontrado");
    }

    // Busca histórico de preços
    $sql = "SELECT * FROM historico_precos 
            WHERE produto_id = ? 
            ORDER BY data_alteracao DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $historico = $stmt->fetchAll();

} catch (Exception $e) {
    $_SESSION['erro'] = "Erro ao carregar produto: " . $e->getMessage();
    header("Location: listar_produtos.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Produto - THINFORMA</title>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<?php include 'menu.php'; ?>

<div class="container py-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Detalhes do Produto</h4>
                    <div>
                        <a href="editar_produto.php?id=<?php echo $produto['id']; ?>" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        <a href="listar_produtos.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <?php if ($produto['imagem']): ?>
                                <img src="<?php echo $produto['imagem']; ?>" 
                                     alt="<?php echo htmlspecialchars($produto['nome']); ?>"
                                     class="img-fluid rounded">
                            <?php else: ?>
                                <div class="border rounded p-3">
                                    <i class="bi bi-image text-muted" style="font-size: 5rem;"></i>
                                    <p class="text-muted mb-0">Sem imagem</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-8">
                            <h5 class="border-bottom pb-2"><?php echo htmlspecialchars($produto['nome']); ?></h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Código:</strong> <?php echo htmlspecialchars($produto['codigo']); ?></p>
                                    <p><strong>Categoria:</strong> <?php echo htmlspecialchars($produto['categoria_nome']); ?></p>
                                    <p><strong>Fornecedor:</strong> <?php echo htmlspecialchars($produto['fornecedor_nome']); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Preço de Custo:</strong> R$ <?php echo number_format($produto['preco_custo'], 2, ',', '.'); ?></p>
                                    <p><strong>Margem de Lucro:</strong> <?php echo number_format($produto['margem_lucro'], 2, ',', '.'); ?>%</p>
                                    <p><strong>Preço de Venda:</strong> R$ <?php echo number_format($produto['preco_venda'], 2, ',', '.'); ?></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <p><strong>Descrição:</strong></p>
                                    <p class="text-muted"><?php echo nl2br(htmlspecialchars($produto['descricao'] ?: 'Nenhuma descrição disponível')); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Estoque Atual</h6>
                                    <p class="h4 mb-0 <?php echo $produto['estoque_atual'] <= $produto['estoque_minimo'] ? 'text-danger' : 'text-success'; ?>">
                                        <?php echo number_format($produto['estoque_atual'], 2, ',', '.'); ?>
                                        <small class="text-muted"><?php echo $produto['unidade']; ?></small>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Estoque Mínimo</h6>
                                    <p class="h4 mb-0">
                                        <?php echo number_format($produto['estoque_minimo'], 2, ',', '.'); ?>
                                        <small class="text-muted"><?php echo $produto['unidade']; ?></small>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Data de Cadastro</h6>
                                    <p class="h4 mb-0">
                                        <?php echo date('d/m/Y', strtotime($produto['data_cadastro'])); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Histórico de Preços</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Custo</th>
                                    <th>Margem</th>
                                    <th>Venda</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($historico as $registro): ?>
                                    <tr>
                                        <td><?php echo date('d/m/Y', strtotime($registro['data_alteracao'])); ?></td>
                                        <td>R$ <?php echo number_format($registro['preco_custo'], 2, ',', '.'); ?></td>
                                        <td><?php echo number_format($registro['margem_lucro'], 2, ',', '.'); ?>%</td>
                                        <td>R$ <?php echo number_format($registro['preco_venda'], 2, ',', '.'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html> 