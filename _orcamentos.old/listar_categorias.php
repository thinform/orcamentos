<?php
// Carrega as configurações
require_once 'config.php';

// Inicializa a sessão se ainda não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define o título da página
$titulo = "Listar Categorias";

// Busca todas as categorias
try {
    $sql = "SELECT c.*, COUNT(p.id) as total_produtos 
            FROM categorias c 
            LEFT JOIN produtos p ON c.id = p.categoria_id 
            GROUP BY c.id 
            ORDER BY c.nome";
    $stmt = $pdo->query($sql);
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro ao buscar categorias: " . $e->getMessage();
    $categorias = [];
}

// Inclui o header
include 'header.php';
// Inclui o menu
include 'menu.php';
?>

<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Categorias</h4>
                    <a href="cadastrar_categoria.php" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Nova Categoria
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
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Descrição</th>
                                    <th>Total de Produtos</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($categorias as $categoria): ?>
                                    <tr>
                                        <td><?php echo $categoria['id']; ?></td>
                                        <td><?php echo htmlspecialchars($categoria['nome']); ?></td>
                                        <td><?php echo htmlspecialchars($categoria['descricao']); ?></td>
                                        <td><?php echo $categoria['total_produtos']; ?></td>
                                        <td>
                                            <a href="editar_categoria.php?id=<?php echo $categoria['id']; ?>" 
                                               class="btn btn-sm btn-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="excluir_categoria.php?id=<?php echo $categoria['id']; ?>" 
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Tem certeza que deseja excluir esta categoria?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
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

<?php include 'footer.php'; ?> 