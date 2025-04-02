<?php
$host = 'thinforma.mysql.dbaas.com.br';
$db = 'thinforma';
$user = 'thinforma';
$pass = 'Lordac01#';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Erro: " . $conn->connect_error);
}

$id = intval($_GET['id'] ?? 0);

// Buscar dados do orçamento
$sql_orc = "SELECT * FROM orcamentos WHERE id = $id";
$res_orc = $conn->query($sql_orc);
$orcamento = $res_orc->fetch_assoc();

// Buscar itens do orçamento
$sql_itens = "SELECT * FROM itens_orcamento WHERE id_orcamento = $id";
$res_itens = $conn->query($sql_itens);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Visualizar Orçamento</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 30px; background: #f4f4f4; }
    h2 { margin-bottom: 10px; }
    .info { margin-bottom: 20px; }
    .label { font-weight: bold; margin-right: 5px; }
    table { width: 100%; border-collapse: collapse; background: #fff; margin-top: 10px; }
    th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
    th { background-color: #eee; }
    nav a, .btn { margin-right: 10px; text-decoration: none; color: #fff; background: #007bff; padding: 10px 15px; border-radius: 5px; }
    .btn-pdf { background: #28a745; }
    .btn-dup { background: #ffc107; color: #000; }
  </style>
</head>
<body>

<nav style="margin-bottom: 20px;">
    <a href="index.html" style="margin-right: 10px; text-decoration: none; color: #fff; background: #007bff; padding: 8px 12px; border-radius: 5px;">Cadastrar Produto</a>
    <a href="listar_produtos.php" style="margin-right: 10px; text-decoration: none; color: #fff; background: #007bff; padding: 8px 12px; border-radius: 5px;">Listar Produtos</a>
    <a href="novo_orcamento.php" style="margin-right: 10px; text-decoration: none; color: #fff; background: #28a745; padding: 8px 12px; border-radius: 5px;">Novo Orçamento</a>
    <a href="listar_orcamentos.php" style="margin-right: 10px; text-decoration: none; color: #fff; background: #6c757d; padding: 8px 12px; border-radius: 5px;">Orçamentos</a>
  </nav>

  <h2>Orçamento #<?= $orcamento['numero'] ?></h2>

  <div class="info">
    <p><span class="label">Cliente:</span> <?= $orcamento['cliente'] ?></p>
    <p><span class="label">Data de Emissão:</span> <?= date('d/m/Y', strtotime($orcamento['data_emissao'])) ?></p>
    <p><span class="label">Validade:</span> <?= $orcamento['validade'] ?> dias</p>
  </div>

  <h3>Produtos</h3>
  <table>
    <tr>
      <th>Produto</th>
      <th>Qtd</th>
      <th>Valor Unitário</th>
      <th>Total</th>
    </tr>
    <?php while ($item = $res_itens->fetch_assoc()): ?>
    <tr>
      <td><?= $item['produto'] ?></td>
      <td><?= $item['quantidade'] ?></td>
      <td>R$ <?= number_format($item['valor_unitario'], 2, ',', '.') ?></td>
      <td>R$ <?= number_format($item['valor_total'], 2, ',', '.') ?></td>
    </tr>
    <?php endwhile; ?>
  </table>

  <h3>Totais</h3>
  <p><span class="label">Frete:</span> R$ <?= number_format($orcamento['frete'], 2, ',', '.') ?></p>
  <p><span class="label">Desconto:</span> R$ <?= number_format($orcamento['desconto_aplicado'], 2, ',', '.') ?></p>
  <p><span class="label">Valor Final:</span> <strong>R$ <?= number_format($orcamento['valor_total'], 2, ',', '.') ?></strong></p>

  <h3>Observações</h3>
  <p><?= nl2br($orcamento['observacoes']) ?: 'Nenhuma' ?></p>

</body>
</html>

<?php $conn->close(); ?>
