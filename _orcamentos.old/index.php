<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Orçamentos - THINFORMA</title>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        .total-card {
            padding: 20px;
            border-radius: 10px;
            color: white;
        }
        .total-card h2 {
            font-size: 36px;
            margin: 0;
        }
        .btn-novo {
            border-radius: 20px;
            padding: 8px 20px;
            font-weight: 500;
        }
        .btn-primary { background-color: #0d6efd; }
        .btn-success { background-color: #198754; }
        .btn-warning { background-color: #ffc107; color: #000; }
        .btn-info { background-color: #0dcaf0; }
    </style>
</head>
<body class="bg-light">

<?php include 'menu.php'; ?>

<div class="container py-4">
    <div class="row g-4">
        <!-- Card de Orçamentos -->
        <div class="col-md-6 col-lg-3">
            <div class="card bg-white p-4">
                <div class="text-center">
                    <i class="bi bi-file-earmark-text card-icon text-primary"></i>
                    <h5 class="mb-3">Orçamentos</h5>
                    <p class="text-muted mb-4">Gerencie seus orçamentos de forma rápida e eficiente.</p>
                    <a href="novo_orcamento.php" class="btn btn-novo btn-primary">
                        <i class="bi bi-plus-circle"></i> Novo Orçamento
                    </a>
                </div>
            </div>
        </div>

        <!-- Card de Produtos -->
        <div class="col-md-6 col-lg-3">
            <div class="card bg-white p-4">
                <div class="text-center">
                    <i class="bi bi-box card-icon text-success"></i>
                    <h5 class="mb-3">Produtos</h5>
                    <p class="text-muted mb-4">Cadastre e gerencie seu catálogo de produtos.</p>
                    <a href="cadastrar_produto.php" class="btn btn-novo btn-success">
                        <i class="bi bi-plus-circle"></i> Novo Produto
                    </a>
                </div>
            </div>
        </div>

        <!-- Card de Categorias -->
        <div class="col-md-6 col-lg-3">
            <div class="card bg-white p-4">
                <div class="text-center">
                    <i class="bi bi-tags card-icon text-warning"></i>
                    <h5 class="mb-3">Categorias</h5>
                    <p class="text-muted mb-4">Organize seus produtos em categorias.</p>
                    <a href="cadastrar_categoria.php" class="btn btn-novo btn-warning">
                        <i class="bi bi-plus-circle"></i> Nova Categoria
                    </a>
                </div>
            </div>
        </div>

        <!-- Card de Fornecedores -->
        <div class="col-md-6 col-lg-3">
            <div class="card bg-white p-4">
                <div class="text-center">
                    <i class="bi bi-truck card-icon text-info"></i>
                    <h5 class="mb-3">Fornecedores</h5>
                    <p class="text-muted mb-4">Gerencie seus fornecedores e contatos.</p>
                    <a href="cadastrar_fornecedor.php" class="btn btn-novo btn-info">
                        <i class="bi bi-plus-circle"></i> Novo Fornecedor
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Totais -->
    <div class="row mt-4 g-4">
        <div class="col-md-3">
            <div class="total-card bg-primary">
                <h6 class="mb-2">Total de Orçamentos</h6>
                <h2><?php echo getTotalOrcamentos(); ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="total-card bg-success">
                <h6 class="mb-2">Total de Produtos</h6>
                <h2><?php echo getTotalProdutos(); ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="total-card bg-warning">
                <h6 class="mb-2">Total de Categorias</h6>
                <h2><?php echo getTotalCategorias(); ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="total-card bg-info">
                <h6 class="mb-2">Total de Fornecedores</h6>
                <h2><?php echo getTotalFornecedores(); ?></h2>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</body>
</html> 