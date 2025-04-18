<?php
require_once 'config.php';

$categorias = [];
$fornecedores = [];
$erro = null;

try {
    // Buscar categorias para o select
    $sql = "SELECT id, nome FROM categorias ORDER BY nome";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Buscar fornecedores para o select
    $sql = "SELECT id, nome FROM fornecedores ORDER BY nome";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $erro = "Erro ao carregar dados: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Produto - THINFORMA</title>
    
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
            <?php if ($erro): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $erro; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Cadastrar Produto</h4>
                </div>
                <div class="card-body">
                    <form action="salvar_produto.php" method="POST" enctype="multipart/form-data" id="formProduto">
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="nome" class="form-label">Nome do Produto*</label>
                                <input type="text" class="form-control" id="nome" name="nome" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="codigo" class="form-label">Código*</label>
                                <input type="text" class="form-control" id="codigo" name="codigo" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="categoria" class="form-label">Categoria*</label>
                                <select class="form-select select2" id="categoria" name="categoria_id" required>
                                    <option value="">Selecione uma categoria</option>
                                    <?php foreach ($categorias as $categoria): ?>
                                        <option value="<?php echo $categoria['id']; ?>">
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
                                        <option value="<?php echo $fornecedor['id']; ?>">
                                            <?php echo htmlspecialchars($fornecedor['nome']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="descricao" class="form-label">Descrição</label>
                                <textarea class="form-control" id="descricao" name="descricao" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="preco_custo" class="form-label">Preço de Custo (R$)*</label>
                                <input type="text" class="form-control money" id="preco_custo" name="preco_custo" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="margem_lucro" class="form-label">Margem de Lucro (%)*</label>
                                <input type="number" class="form-control" id="margem_lucro" name="margem_lucro" 
                                       value="30" min="0" max="1000" step="0.01" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="preco_venda" class="form-label">Preço de Venda (R$)*</label>
                                <input type="text" class="form-control money" id="preco_venda" name="preco_venda" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="unidade" class="form-label">Unidade*</label>
                                <select class="form-select" id="unidade" name="unidade" required>
                                    <option value="UN">Unidade (UN)</option>
                                    <option value="KG">Quilograma (KG)</option>
                                    <option value="M">Metro (M)</option>
                                    <option value="M2">Metro Quadrado (M²)</option>
                                    <option value="M3">Metro Cúbico (M³)</option>
                                    <option value="L">Litro (L)</option>
                                    <option value="CX">Caixa (CX)</option>
                                    <option value="PCT">Pacote (PCT)</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="estoque_atual" class="form-label">Estoque Atual*</label>
                                <input type="number" class="form-control" id="estoque_atual" name="estoque_atual" 
                                       value="0" min="0" step="0.01" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="estoque_minimo" class="form-label">Estoque Mínimo*</label>
                                <input type="number" class="form-control" id="estoque_minimo" name="estoque_minimo" 
                                       value="5" min="0" step="0.01" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="imagem" class="form-label">Imagem do Produto</label>
                            <input type="file" class="form-control" id="imagem" name="imagem" 
                                   accept=".jpg,.jpeg,.png,.gif">
                            <div class="form-text">
                                Formatos aceitos: JPG, JPEG, PNG e GIF. Tamanho máximo: 2MB
                            </div>
                        </div>

                        <div class="text-end">
                            <a href="listar_produtos.php" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Cadastrar Produto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>

<script>
$(document).ready(function() {
    // Inicializa Select2
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%' // Força largura total
    });

    // Inicializa MaskMoney
    $('.money').maskMoney({
        prefix: 'R$ ',
        allowNegative: false,
        thousands: '.',
        decimal: ',',
        affixesStay: true
    });

    // Calcula preço de venda quando preço de custo ou margem mudar
    function calcularPrecoVenda() {
        var precoCusto = parseFloat($('#preco_custo').maskMoney('unmasked')[0] || 0);
        var margem = parseFloat($('#margem_lucro').val() || 0);
        var precoVenda = precoCusto * (1 + (margem / 100));
        
        $('#preco_venda').maskMoney('mask', precoVenda);
    }

    $('#preco_custo, #margem_lucro').on('input', calcularPrecoVenda);

    // Força o cálculo inicial
    calcularPrecoVenda();

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
