<?php
require_once 'config.php';

try {
    // Buscar orçamentos
    $sql = "SELECT o.*, c.nome as cliente_nome 
            FROM orcamentos o 
            LEFT JOIN clientes c ON o.cliente_id = c.id 
            ORDER BY o.data_emissao DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $orcamentos = $stmt->fetchAll();
} catch(PDOException $e) {
    die("Erro ao carregar orçamentos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orçamentos - THINFORMA</title>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/datatables.net-responsive-bs5@2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<?php include 'menu.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Orçamentos</h2>
        <a href="novo_orcamento.php" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Novo Orçamento
        </a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php 
            $message = match($_GET['success']) {
                'create' => 'Orçamento criado com sucesso!',
                'update' => 'Orçamento atualizado com sucesso!',
                'delete' => 'Orçamento excluído com sucesso!',
                default => 'Operação realizada com sucesso!'
            };
            echo $message;
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_GET['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="tabelaOrcamentos">
                    <thead>
                        <tr>
                            <th>Número</th>
                            <th>Cliente</th>
                            <th>Data Emissão</th>
                            <th>Validade</th>
                            <th>Subtotal</th>
                            <th>Frete</th>
                            <th>Desconto</th>
                            <th>Total</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orcamentos as $orcamento): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($orcamento['numero_orcamento']); ?></td>
                                <td><?php echo htmlspecialchars($orcamento['cliente_nome']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($orcamento['data_emissao'])); ?></td>
                                <td><?php echo $orcamento['data_validade'] ? date('d/m/Y', strtotime($orcamento['data_validade'])) : '-'; ?></td>
                                <td>R$ <?php echo number_format($orcamento['subtotal'] ?? 0, 2, ',', '.'); ?></td>
                                <td>R$ <?php echo number_format($orcamento['valor_frete'] ?? 0, 2, ',', '.'); ?></td>
                                <td>R$ <?php echo number_format($orcamento['desconto_aplicado'] ?? 0, 2, ',', '.'); ?></td>
                                <td>R$ <?php echo number_format($orcamento['valor_total'] ?? 0, 2, ',', '.'); ?></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="visualizar_orcamento.php?id=<?php echo $orcamento['id']; ?>" 
                                           class="btn btn-sm btn-info" title="Visualizar">
                                           <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="editar_orcamento.php?id=<?php echo $orcamento['id']; ?>" 
                                           class="btn btn-sm btn-primary" title="Editar">
                                           <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" title="Excluir"
                                                onclick="confirmarExclusao(<?php echo $orcamento['id']; ?>)">
                                            <i class="bi bi-trash"></i>
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

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
function confirmarExclusao(id) {
    if (confirm('Tem certeza que deseja excluir este orçamento?')) {
        window.location.href = 'excluir_orcamento.php?id=' + id;
    }
}

$(document).ready(function() {
    $('#tabelaOrcamentos').DataTable({
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json'
        },
        order: [[2, 'desc']], // Ordena por data de emissão decrescente
        columnDefs: [
            {
                targets: -1, // Última coluna (ações)
                orderable: false
            }
        ]
    });
});
</script>

</body>
</html> 