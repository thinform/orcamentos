<?php
// Habilita exibição de erros para debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Carrega as configurações
require_once 'config.php';

// Inicializa a sessão se ainda não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define o título da página
$titulo = "Cadastrar Categoria";

// Verifica a conexão com o banco de dados
try {
    $pdo->query("SELECT 1");
} catch (PDOException $e) {
    die("Erro de conexão com o banco de dados: " . $e->getMessage());
}

// Verifica se a tabela existe
try {
    $result = $pdo->query("SHOW TABLES LIKE 'categorias'");
    if (!$result->fetch()) {
        // Cria a tabela se não existir
        $sql = "CREATE TABLE categorias (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL UNIQUE,
            descricao TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        $pdo->exec($sql);
    }
} catch (PDOException $e) {
    die("Erro ao verificar/criar tabela: " . $e->getMessage());
}

// Inclui o header
include 'header.php';
// Inclui o menu
include 'menu.php';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Nova Categoria</h4>
                    <a href="listar_categorias.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                </div>
                <div class="card-body">
                    <?php if(isset($_SESSION['erro'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php 
                            echo $_SESSION['erro'];
                            unset($_SESSION['erro']);
                            ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                        </div>
                    <?php endif; ?>

                    <form action="salvar_categoria.php" method="POST">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome da Categoria*</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>

                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="3"></textarea>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Cadastrar Categoria</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html> 