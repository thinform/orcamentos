<?php
require_once 'config.php';

// Gerar número do orçamento (AAAAMMDDHHMMSS)
$numero_orcamento = date('YmdHis');

// Data de emissão (hoje)
$data_emissao = date('Y-m-d');

// Data de validade (hoje + 30 dias)
$data_validade = date('Y-m-d', strtotime('+30 days'));

try {
    // Buscar produtos padrão da categoria Hardware
    $sql = "SELECT p.*, c.nome as categoria_nome 
            FROM produtos p 
            LEFT JOIN categorias c ON p.categoria_id = c.id 
            WHERE c.nome LIKE 'Hardware%' 
            ORDER BY p.descricao";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $produtos_hardware = $stmt->fetchAll();

    // Buscar todos os clientes para o select
    $sql = "SELECT id, nome FROM clientes ORDER BY nome";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $clientes = $stmt->fetchAll();
} catch(PDOException $e) {
    die("Erro ao carregar dados: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Orçamento - THINFORMA</title>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<?php include 'menu.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Novo Orçamento</h2>
    </div>

    <form id="formOrcamento" action="salvar_orcamento.php" method="POST">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Dados do Orçamento</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label class="form-label">Número do Orçamento</label>
                            <input type="text" class="form-control" name="numero_orcamento" 
                                   value="<?php echo $numero_orcamento; ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label class="form-label">Data de Emissão</label>
                            <input type="date" class="form-control" name="data_emissao" 
                                   value="<?php echo $data_emissao; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label class="form-label">Data de Validade</label>
                            <input type="date" class="form-control" name="data_validade" 
                                   value="<?php echo $data_validade; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label class="form-label">Cliente</label>
                            <select class="form-control select2" name="cliente_id" required>
                                <option value="">Selecione um cliente</option>
                                <?php foreach ($clientes as $cliente): ?>
                                    <option value="<?php echo $cliente['id']; ?>">
                                        <?php echo htmlspecialchars($cliente['nome']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Produtos</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="tabelaProdutos">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Produto</th>
                                <th>Quantidade</th>
                                <th>Valor Unitário</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($produtos_hardware as $produto): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($produto['codigo']); ?></td>
                                <td>
                                    <?php echo htmlspecialchars($produto['descricao']); ?>
                                    <input type="hidden" name="produto_id[]" value="<?php echo $produto['id']; ?>">
                                </td>
                                <td>
                                    <input type="number" class="form-control quantidade" name="quantidade[]" 
                                           value="1" min="1" step="1">
                                </td>
                                <td>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="text" class="form-control valor-unitario" name="valor_unitario[]" 
                                               value="<?php echo number_format($produto['preco_venda'], 2, ',', '.'); ?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="text" class="form-control valor-total" readonly 
                                               value="<?php echo number_format($produto['preco_venda'], 2, ',', '.'); ?>">
                                    </div>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm remover-produto">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6">
                                    <button type="button" class="btn btn-success btn-sm" id="adicionarProduto">
                                        <i class="bi bi-plus"></i> Adicionar Produto
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                                <td>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="text" class="form-control" id="subtotal" name="subtotal" readonly>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Frete:</strong></td>
                                <td>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="text" class="form-control money" id="valor_frete" name="valor_frete" value="0,00">
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Desconto:</strong></td>
                                <td>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="text" class="form-control money" id="desconto_aplicado" name="desconto_aplicado" value="0,00">
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                <td>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="text" class="form-control" id="total" name="total" readonly>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <div class="form-group">
                    <label for="observacoes" class="form-label">Observações</label>
                    <textarea class="form-control" id="observacoes" name="observacoes" rows="3"></textarea>
                </div>
            </div>
        </div>

        <div class="text-end">
            <a href="listar_orcamentos.php" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Salvar Orçamento</button>
        </div>
    </form>
</div>

<!-- Modal para adicionar produto -->
<div class="modal fade" id="modalProduto" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar Produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label class="form-label">Produto</label>
                    <select class="form-control select2" id="selectProduto"></select>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Quantidade</label>
                    <input type="number" class="form-control" id="modalQuantidade" value="1" min="1" step="1">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Valor Unitário</label>
                    <div class="input-group">
                        <span class="input-group-text">R$</span>
                        <input type="text" class="form-control money" id="modalValorUnitario">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnAdicionarProduto">Adicionar</button>
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
        prefix: '',
        allowNegative: false,
        thousands: '.',
        decimal: ',',
        affixesStay: false
    });

    // Função para buscar produtos
    function carregarProdutos() {
        $.get('buscar_produtos.php', function(data) {
            $('#selectProduto').html(data);
        });
    }

    // Função para calcular total da linha
    function calcularTotalLinha($row) {
        var quantidade = parseFloat($row.find('.quantidade').val()) || 0;
        var valorUnitario = parseFloat($row.find('.valor-unitario').val().replace('.', '').replace(',', '.')) || 0;
        var total = quantidade * valorUnitario;
        $row.find('.valor-total').val(total.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        calcularTotais();
    }

    // Função para calcular totais
    function calcularTotais() {
        var subtotal = 0;
        $('.valor-total').each(function() {
            subtotal += parseFloat($(this).val().replace('.', '').replace(',', '.')) || 0;
        });

        var frete = parseFloat($('#valor_frete').val().replace('.', '').replace(',', '.')) || 0;
        var desconto = parseFloat($('#desconto_aplicado').val().replace('.', '').replace(',', '.')) || 0;
        var total = subtotal + frete - desconto;

        $('#subtotal').val(subtotal.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        $('#total').val(total.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    }

    // Evento para adicionar produto
    $('#adicionarProduto').click(function() {
        carregarProdutos();
        $('#modalProduto').modal('show');
    });

    // Evento ao selecionar um produto no modal
    $('#selectProduto').change(function() {
        var preco = $(this).find(':selected').data('preco');
        if(preco) {
            $('#modalValorUnitario').val(preco.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        }
    });

    // Evento para adicionar produto da modal à tabela
    $('#btnAdicionarProduto').click(function() {
        var produto = $('#selectProduto option:selected');
        if(produto.val()) {
            var html = `
                <tr>
                    <td>${produto.data('codigo')}</td>
                    <td>
                        ${produto.text()}
                        <input type="hidden" name="produto_id[]" value="${produto.val()}">
                    </td>
                    <td>
                        <input type="number" class="form-control quantidade" name="quantidade[]" 
                               value="${$('#modalQuantidade').val()}" min="1" step="1">
                    </td>
                    <td>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="text" class="form-control valor-unitario" name="valor_unitario[]" 
                                   value="${$('#modalValorUnitario').val()}">
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="text" class="form-control valor-total" readonly>
                        </div>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remover-produto">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#tabelaProdutos tbody').append(html);
            
            // Inicializa MaskMoney para o novo campo
            $('#tabelaProdutos tbody tr:last .valor-unitario').maskMoney({
                prefix: '',
                allowNegative: false,
                thousands: '.',
                decimal: ',',
                affixesStay: false
            });
            
            calcularTotalLinha($('#tabelaProdutos tbody tr:last'));
            $('#modalProduto').modal('hide');
        }
    });

    // Eventos para cálculos automáticos
    $(document).on('input', '.quantidade, .valor-unitario', function() {
        calcularTotalLinha($(this).closest('tr'));
    });

    $(document).on('input', '#valor_frete, #desconto_aplicado', function() {
        calcularTotais();
    });

    // Evento para remover produto
    $(document).on('click', '.remover-produto', function() {
        $(this).closest('tr').remove();
        calcularTotais();
    });

    // Calcula totais iniciais
    calcularTotais();
});
</script>

</body>
</html>
