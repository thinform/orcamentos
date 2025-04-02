<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="bi bi-receipt"></i> Sistema de Orçamentos
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <!-- Orçamentos -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-file-earmark-text"></i> Orçamentos
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="novo_orcamento.php">
                            <i class="bi bi-plus-circle"></i> Novo Orçamento
                        </a></li>
                        <li><a class="dropdown-item" href="listar_orcamentos.php">
                            <i class="bi bi-list"></i> Listar Orçamentos
                        </a></li>
                    </ul>
                </li>
                
                <!-- Produtos -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-box"></i> Produtos
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="cadastrar_produto.php">
                            <i class="bi bi-plus-circle"></i> Novo Produto
                        </a></li>
                        <li><a class="dropdown-item" href="listar_produtos.php">
                            <i class="bi bi-list"></i> Listar Produtos
                        </a></li>
                    </ul>
                </li>
                
                <!-- Categorias -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-tags"></i> Categorias
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="cadastrar_categoria.php">
                            <i class="bi bi-plus-circle"></i> Nova Categoria
                        </a></li>
                        <li><a class="dropdown-item" href="listar_categorias.php">
                            <i class="bi bi-list"></i> Listar Categorias
                        </a></li>
                    </ul>
                </li>
                
                <!-- Fornecedores -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-truck"></i> Fornecedores
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="cadastrar_fornecedor.php">
                            <i class="bi bi-plus-circle"></i> Novo Fornecedor
                        </a></li>
                        <li><a class="dropdown-item" href="listar_fornecedores.php">
                            <i class="bi bi-list"></i> Listar Fornecedores
                        </a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<?php if(isset($_SESSION['mensagem'])): ?>
    <div class="container mt-3">
        <div class="alert alert-<?php echo $_SESSION['mensagem_tipo']; ?> alert-dismissible fade show" role="alert">
            <?php 
            echo $_SESSION['mensagem'];
            unset($_SESSION['mensagem']);
            unset($_SESSION['mensagem_tipo']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
<?php endif; ?>