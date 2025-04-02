<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = 'thinforma.mysql.dbaas.com.br';
$db = 'thinforma';
$user = 'thinforma';
$pass = 'Lordac01#';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Busca os dados do orçamento
$sql = "SELECT o.*, c.nome as cliente_nome, c.endereco 
        FROM orcamentos o 
        LEFT JOIN clientes c ON o.cliente_id = c.id 
        WHERE o.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$orcamento = $result->fetch_assoc();

if (!$orcamento) {
    die("Orçamento não encontrado");
}

// Busca os itens do orçamento
$sql = "SELECT i.*, p.descricao, p.codigo 
        FROM itens_orcamento i 
        LEFT JOIN produtos p ON i.produto_id = p.id 
        WHERE i.orcamento_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$itens = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Visualizar Orçamento</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .header {
            background-color: #f8f9fa;
            padding: 20px 0;
            margin-bottom: 20px;
        }
        .company-info {
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Visualizar Orçamento</h2>
        <div>
            <a href="cadastrar_produto.php" class="btn btn-primary btn-sm">Cadastrar Produto</a>
            <a href="listar_produtos.php" class="btn btn-primary btn-sm">Listar Produtos</a>
            <a href="novo_orcamento.php" class="btn btn-success btn-sm">Novo Orçamento</a>
            <a href="listar_orcamentos.php" class="btn btn-dark btn-sm">Orçamentos</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="company-info">
                <h3>THINFORMA</h3>
                <p>CNPJ: 49.347.548/0001-00</p>
                <p>Rua Tenerife, Nº 407 - JARDIM ATLÂNTICO</p>
                <p>31.550-220, BELO HORIZONTE, MG</p>
                <p>WHATSAPP: (31) 99243-1019 - SITE: http://www.thinforma.com.br</p>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <h4>Dados do Cliente</h4>
                    <p><strong>Nome:</strong> <?= htmlspecialchars($orcamento['cliente_nome']) ?></p>
                    <p><strong>Endereço:</strong> <?= htmlspecialchars($orcamento['endereco']) ?></p>
                </div>
                <div class="col-md-6 text-end">
                    <h4>Dados do Orçamento</h4>
                    <p><strong>Número:</strong> <?= htmlspecialchars($orcamento['numero_orcamento']) ?></p>
                    <p><strong>Data:</strong> <?= date('d/m/Y', strtotime($orcamento['data_emissao'])) ?></p>
                    <p><strong>Validade:</strong> <?= $orcamento['validade'] ?> dias</p>
                </div>
            </div>

            <div class="table-responsive mb-4">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Descrição</th>
                            <th class="text-center">Qtd</th>
                            <th class="text-end">Valor Unit.</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($item = $itens->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['codigo']) ?></td>
                            <td><?= htmlspecialchars($item['descricao']) ?></td>
                            <td class="text-center"><?= $item['quantidade'] ?></td>
                            <td class="text-end">R$ <?= number_format($item['valor_unitario'], 2, ',', '.') ?></td>
                            <td class="text-end">R$ <?= number_format($item['valor_total'], 2, ',', '.') ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <div class="row justify-content-end">
                <div class="col-md-6">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Subtotal:</strong></td>
                            <td class="text-end">R$ <?= number_format($orcamento['valor_total'] + $orcamento['desconto_aplicado'] - $orcamento['frete'], 2, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <td><strong>Frete:</strong></td>
                            <td class="text-end">R$ <?= number_format($orcamento['frete'], 2, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <td><strong>Desconto:</strong></td>
                            <td class="text-end">R$ <?= number_format($orcamento['desconto_aplicado'], 2, ',', '.') ?></td>
                        </tr>
                        <tr class="table-primary">
                            <td><strong>Total:</strong></td>
                            <td class="text-end"><strong>R$ <?= number_format($orcamento['valor_total'], 2, ',', '.') ?></strong></td>
                        </tr>
                    </table>
                </div>
            </div>

            <?php if (!empty($orcamento['observacoes'])): ?>
            <div class="mt-4">
                <h4>Observações</h4>
                <p><?= nl2br(htmlspecialchars($orcamento['observacoes'])) ?></p>
            </div>
            <?php endif; ?>

            <div class="mt-4">
                <h4>Condições Gerais</h4>
                <ul>
                    <li>O Orçamento tem validade 24 horas úteis.</li>
                    <li>Não recebemos nada a mais pelo parcelamento.</li>
                    <li>Caso queira fazer em outra instituição financeira, tudo bem!</li>
                    <li>Somos Empresa com CNPJ.</li>
                    <li>Todas as peças tem garantia mínima de 1 Ano.</li>
                </ul>
            </div>

            <div class="mt-4 text-center">
                <a href="gerar_pdf.php?id=<?= $id ?>" class="btn btn-primary">Gerar PDF</a>
                <a href="editar_orcamento.php?id=<?= $id ?>" class="btn btn-warning">Editar</a>
                <a href="listar_orcamentos.php" class="btn btn-secondary">Voltar</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>

<?php
$conn->close();
?> 