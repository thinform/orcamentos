<?php
require_once 'conexao.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: listar_orcamentos.php");
    exit();
}

$id = $_GET['id'];

// Buscar dados do orçamento
$sql = "SELECT o.*, c.nome as cliente_nome 
        FROM orcamentos o 
        LEFT JOIN clientes c ON o.cliente_id = c.id 
        WHERE o.id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$orcamento = $stmt->fetch();

if (!$orcamento) {
    header("Location: listar_orcamentos.php");
    exit();
}

// Buscar itens do orçamento
$sql = "SELECT i.*, p.descricao as produto_nome, p.codigo as produto_codigo 
        FROM itens_orcamento i 
        JOIN produtos p ON i.produto_id = p.id 
        WHERE i.orcamento_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$itens = $stmt->fetchAll();

// Buscar todos os clientes para o select
$sql = "SELECT id, nome FROM clientes ORDER BY nome";
$stmt = $conn->prepare($sql);
$stmt->execute();
$clientes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Orçamento - THINFORMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
</head>
<body class="bg-light">

<?php include 'menu.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Editar Orçamento</h2>
    </div>

    <form action="atualizar_orcamento.php" method="POST" id="formOrcamento">
        <input type="hidden" name="orcamento_id" value="<?php echo $orcamento['id']; ?>">
        
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Número do Orçamento</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($orcamento['numero_orcamento']); ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Data de Emissão</label>
                            <input type="text" class="form-control" value="<?php echo $orcamento['data_emissao'] ? date('d/m/Y', strtotime($orcamento['data_emissao'])) : ''; ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Data de Validade</label>
                            <input type="text" class="form-control" value="<?php echo $orcamento['data_validade'] ? date('d/m/Y', strtotime($orcamento['data_validade'])) : ''; ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Cliente</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($orcamento['cliente_nome']); ?>" readonly>
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
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($itens as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['produto_codigo']); ?></td>
                                    <td>
                                        <?php echo htmlspecialchars($item['produto_nome']); ?>
                                        <input type="hidden" name="produto_id[]" value="<?php echo $item['produto_id']; ?>">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control quantidade" name="quantidade[]" 
                                               value="<?php echo $item['quantidade']; ?>" min="1" step="1">
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-text">R$</span>
                                            <input type="text" class="form-control valor-unitario" name="valor_unitario[]" 
                                                   value="<?php echo number_format($item['valor_unitario'], 2, ',', '.'); ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-text">R$</span>
                                            <input type="text" class="form-control valor-total" readonly 
                                                   value="<?php echo number_format($item['valor_total'], 2, ',', '.'); ?>">
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                                <td>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="text" class="form-control" id="subtotal" readonly>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Frete:</strong></td>
                                <td>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="text" class="form-control" id="valor_frete" name="valor_frete" 
                                               value="<?php echo number_format($orcamento['valor_frete'], 2, ',', '.'); ?>">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Desconto:</strong></td>
                                <td>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="text" class="form-control" id="desconto_aplicado" name="desconto_aplicado" 
                                               value="<?php echo number_format($orcamento['desconto_aplicado'], 2, ',', '.'); ?>">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                <td>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="text" class="form-control" id="total" readonly 
                                               value="<?php echo number_format($orcamento['valor_total'], 2, ',', '.'); ?>">
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <div class="form-group">
                    <label for="observacoes">Observações</label>
                    <textarea class="form-control" id="observacoes" name="observacoes" rows="3"><?php echo htmlspecialchars($orcamento['observacoes']); ?></textarea>
                </div>
            </div>
        </div>

        <div class="text-end">
            <a href="listar_orcamentos.php" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Atualizar Orçamento</button>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    // Máscaras para campos monetários
    $('#valor_frete, #desconto_aplicado').mask('#.##0,00', {reverse: true});
    $('.valor-unitario').mask('#.##0,00', {reverse: true});

    // Função para calcular totais
    function calcularTotais() {
        let subtotal = 0;

        // Calcula o total de cada item e o subtotal
        $('.valor-unitario').each(function(index) {
            let quantidade = parseFloat($('.quantidade').eq(index).val()) || 0;
            let valorUnitario = parseFloat($(this).val().replace('.', '').replace(',', '.')) || 0;
            let total = quantidade * valorUnitario;
            
            $('.valor-total').eq(index).val(total.toFixed(2).replace('.', ','));
            subtotal += total;
        });

        // Atualiza o subtotal
        $('#subtotal').val(subtotal.toFixed(2).replace('.', ','));

        // Calcula o total final
        let frete = parseFloat($('#valor_frete').val().replace('.', '').replace(',', '.')) || 0;
        let desconto = parseFloat($('#desconto_aplicado').val().replace('.', '').replace(',', '.')) || 0;
        let total = subtotal + frete - desconto;

        $('#total').val(total.toFixed(2).replace('.', ','));
    }

    // Eventos para recalcular os totais
    $('.quantidade, .valor-unitario, #valor_frete, #desconto_aplicado').on('input', calcularTotais);

    // Calcula os totais iniciais
    calcularTotais();

    // Validação do formulário
    $('#formOrcamento').on('submit', function(e) {
        let total = parseFloat($('#total').val().replace('.', '').replace(',', '.'));
        if (total < 0) {
            e.preventDefault();
            alert('O valor total do orçamento não pode ser negativo.');
            return false;
        }
        return true;
    });
});
</script>

</body>
</html> 