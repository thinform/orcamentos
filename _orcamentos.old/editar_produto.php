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
    $sql = "SELECT * FROM produtos WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $produto = $stmt->fetch();

    if (!$produto) {
        throw new Exception("Produto não encontrado");
    }

    // Buscar categorias para o select
    $sql = "SELECT id, nome FROM categorias ORDER BY nome";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $categorias = $stmt->fetchAll();

    // Buscar fornecedores para o select
    $sql = "SELECT id, nome FROM fornecedores ORDER BY nome";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $fornecedores = $stmt->fetchAll();

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
    <title>Editar Produto - THINFORMA</title>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<?php include 'menu.php'; ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Editar Produto</h4>
                    <a href="listar_produtos.php" class="btn btn-secondary">
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

                    <form action="atualizar_produto.php" method="POST" enctype="multipart/form-data" id="formProduto">
                        <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">
                        
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="nome" class="form-label">Nome do Produto*</label>
                                <input type="text" class="form-control" id="nome" name="nome" 
                                       value="<?php echo htmlspecialchars($produto['nome']); ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="codigo" class="form-label">Código*</label>
                                <input type="text" class="form-control" id="codigo" name="codigo" 
                                       value="<?php echo htmlspecialchars($produto['codigo']); ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="categoria" class="form-label">Categoria*</label>
                                <select class="form-select select2" id="categoria" name="categoria_id" required>
                                    <option value="">Selecione uma categoria</option>
                                    <?php foreach ($categorias as $categoria): ?>
                                        <option value="<?php echo $categoria['id']; ?>" 
                                                <?php echo $categoria['id'] == $produto['categoria_id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($categoria['nome']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="fornecedor" class="form-label">Fornecedor*</label>
                                <select class="form-select select2" id="fornecedor" name="fornecedor_id" required>
                                    <option value="">Selecione um fornecedor</option>
                                    <?php foreach ($fornecedores as $fornecedor): ?>
                                        <option value="<?php echo $fornecedor['id']; ?>"
                                                <?php echo $fornecedor['id'] == $produto['fornecedor_id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($fornecedor['nome']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="descricao" class="form-label">Descrição</label>
                                <textarea class="form-control" id="descricao" name="descricao" rows="3"><?php echo htmlspecialchars($produto['descricao']); ?></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="preco_custo" class="form-label">Preço de Custo (R$)*</label>
                                <input type="text" class="form-control money" id="preco_custo" name="preco_custo" 
                                       value="<?php echo number_format($produto['preco_custo'], 2, ',', '.'); ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="margem" class="form-label">Margem de Lucro (%)*</label>
                                <input type="number" class="form-control" id="margem" name="margem" 
                                       value="<?php echo $produto['margem_lucro']; ?>" 
                                       min="0" max="1000" step="0.01" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="preco_venda" class="form-label">Preço de Venda (R$)*</label>
                                <input type="text" class="form-control money" id="preco_venda" name="preco_venda" 
                                       value="<?php echo number_format($produto['preco_venda'], 2, ',', '.'); ?>" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="unidade" class="form-label">Unidade*</label>
                                <select class="form-select" id="unidade" name="unidade" required>
                                    <option value="UN" <?php echo $produto['unidade'] == 'UN' ? 'selected' : ''; ?>>Unidade (UN)</option>
                                    <option value="KG" <?php echo $produto['unidade'] == 'KG' ? 'selected' : ''; ?>>Quilograma (KG)</option>
                                    <option value="M" <?php echo $produto['unidade'] == 'M' ? 'selected' : ''; ?>>Metro (M)</option>
                                    <option value="M2" <?php echo $produto['unidade'] == 'M2' ? 'selected' : ''; ?>>Metro Quadrado (M²)</option>
                                    <option value="M3" <?php echo $produto['unidade'] == 'M3' ? 'selected' : ''; ?>>Metro Cúbico (M³)</option>
                                    <option value="L" <?php echo $produto['unidade'] == 'L' ? 'selected' : ''; ?>>Litro (L)</option>
                                    <option value="CX" <?php echo $produto['unidade'] == 'CX' ? 'selected' : ''; ?>>Caixa (CX)</option>
                                    <option value="PCT" <?php echo $produto['unidade'] == 'PCT' ? 'selected' : ''; ?>>Pacote (PCT)</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="estoque_atual" class="form-label">Estoque Atual*</label>
                                <input type="number" class="form-control" id="estoque_atual" name="estoque_atual" 
                                       value="<?php echo $produto['estoque_atual']; ?>" 
                                       min="0" step="0.01" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="estoque_minimo" class="form-label">Estoque Mínimo*</label>
                                <input type="number" class="form-control" id="estoque_minimo" name="estoque_minimo" 
                                       value="<?php echo $produto['estoque_minimo']; ?>" 
                                       min="0" step="0.01" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="imagem" class="form-label">Imagem do Produto</label>
                            <?php if ($produto['imagem']): ?>
                                <div class="mb-2">
                                    <img src="<?php echo $produto['imagem']; ?>" 
                                         alt="Imagem atual" class="img-thumbnail" style="max-height: 100px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="remover_imagem" name="remover_imagem">
                                        <label class="form-check-label" for="remover_imagem">
                                            Remover imagem atual
                                        </label>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="imagem" name="imagem" 
                                   accept=".jpg,.jpeg,.png,.gif">
                            <div class="form-text">
                                Formatos aceitos: JPG, JPEG, PNG e GIF. Tamanho máximo: 2MB
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>

<script>
$(document).ready(function() {
    // Inicializa Select2
    $('.select2').select2({
        theme: 'bootstrap-5'
    });

    // Inicializa MaskMoney
    $('.money').maskMoney({
        prefix: 'R$ ',
        allowNegative: false,
        thousands: '.',
        decimal: ',',
        affixesStay: false
    });

    // Calcula preço de venda quando preço de custo ou margem mudar
    function calcularPrecoVenda() {
        var precoCusto = parseFloat($('#preco_custo').val().replace('R$ ', '').replace('.', '').replace(',', '.')) || 0;
        var margem = parseFloat($('#margem').val()) || 0;
        var precoVenda = precoCusto * (1 + (margem / 100));
        
        $('#preco_venda').val(precoVenda.toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }));
    }

    $('#preco_custo, #margem').on('input', calcularPrecoVenda);

    // Validação do formulário
    $('#formProduto').on('submit', function(e) {
        var imagem = $('#imagem')[0].files[0];
        if (imagem) {
            var tamanhoMaximo = 2 * 1024 * 1024; // 2MB
            if (imagem.size > tamanhoMaximo) {
                e.preventDefault();
                alert('A imagem deve ter no máximo 2MB');
                return false;
            }

            var extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
            var extensao = imagem.name.split('.').pop().toLowerCase();
            if (!extensoesPermitidas.includes(extensao)) {
                e.preventDefault();
                alert('Formato de imagem não permitido. Use: ' + extensoesPermitidas.join(', '));
                return false;
            }
        }
        return true;
    });
});
</script>

</body>
</html>
