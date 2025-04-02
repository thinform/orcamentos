<?php
require_once('tcpdf/tcpdf.php');

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
$sql = "SELECT o.*, c.nome as cliente_nome, c.endereco, c.email, c.cnpj 
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

// Extend TCPDF with custom functions
class MYPDF extends TCPDF {
    public function Header() {
        // Logo à direita
        $logo = 'logo.png'; // Certifique-se de ter o arquivo da logo
        if (file_exists($logo)) {
            $this->Image($logo, 180, 10, 20);
        }

        // Título e informações da empresa
        $this->SetFont('helvetica', 'B', 14);
        $this->Cell(0, 10, 'THINFORMA - CNPJ: 49.347.548/0001-00', 0, true, 'L');
        
        $this->SetFont('helvetica', '', 9);
        $this->Cell(0, 5, 'Rua Tenerife, Nº 407 - JARDIM ATLÂNTICO', 0, true, 'L');
        $this->Cell(0, 5, '31.550-220, BELO HORIZONTE, MG - WHATSAPP: (31) 99243-1019 - SITE: http://www.thinforma.com.br', 0, true, 'L');
        
        // Linha preta grossa
        $this->SetLineWidth(0.5);
        $this->Line(10, $this->GetY(), $this->getPageWidth()-10, $this->GetY());
        $this->SetLineWidth(0.2);
        
        $this->Ln(5);
    }
    
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Página '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C');
    }
}

// Criar novo documento PDF
$pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Configurar documento
$pdf->SetCreator('THINFORMA');
$pdf->SetAuthor('THINFORMA');
$pdf->SetTitle('Orçamento #' . $orcamento['numero_orcamento']);

// Configurar margens
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(TRUE, 15);

// Cores personalizadas
$pdf->SetFillColor(255, 242, 242); // Rosa claro para as linhas da tabela
$pdf->SetTextColor(0, 0, 0);

// Adicionar página
$pdf->AddPage();

// Dados do Cliente
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 8, 'DADOS DO CLIENTE', 0, true, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 6, $orcamento['cliente_nome'] . ' - ' . $orcamento['email'], 0, true, 'L');
$pdf->Cell(0, 6, 'RUA ' . $orcamento['endereco'], 0, true, 'L');
$pdf->Cell(0, 6, 'CNPJ: ' . $orcamento['cnpj'], 0, true, 'L');

// Número do Orçamento (box rosa)
$pdf->SetXY(150, $pdf->GetY() - 18);
$pdf->SetFillColor(255, 242, 242);
$pdf->Cell(50, 6, 'ORÇAMENTO Nº.', 0, true, 'L');
$pdf->SetXY(150, $pdf->GetY());
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(50, 8, $orcamento['numero_orcamento'], 1, true, 'C', true);

// Detalhamento do Pedido
$pdf->Ln(5);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 8, 'DETALHAMENTO DO PEDIDO', 0, true, 'L');

// Cabeçalho da tabela
$pdf->SetFillColor(255, 242, 242);
$pdf->SetFont('helvetica', 'B', 9);

// Larguras das colunas
$w = array(25, 100, 15, 15, 30);

// Cabeçalhos
$header = array('COD', 'DESCRIÇÃO DO PRODUTO', 'QTD', 'UND', 'VL. TOTAL');
foreach($header as $i => $txt) {
    $pdf->Cell($w[$i], 7, $txt, 1, 0, 'C', true);
}
$pdf->Ln();

// Dados da tabela
$pdf->SetFont('helvetica', '', 9);
$fill = true;
while ($item = $itens->fetch_assoc()) {
    $pdf->Cell($w[0], 6, $item['codigo'], 1, 0, 'L', $fill);
    $pdf->Cell($w[1], 6, $item['descricao'], 1, 0, 'L', $fill);
    $pdf->Cell($w[2], 6, $item['quantidade'], 1, 0, 'C', $fill);
    $pdf->Cell($w[3], 6, 'PÇ', 1, 0, 'C', $fill);
    $pdf->Cell($w[4], 6, 'R$ ' . number_format($item['valor_total'], 2, ',', '.'), 1, 0, 'R', $fill);
    $pdf->Ln();
    $fill = !$fill;
}

// Calcular valores
$subtotal = $orcamento['valor_total'] + $orcamento['desconto_aplicado'];
$valor_parcela = ($subtotal) / 12;

// Área de totais
$pdf->Ln(5);
$pdf->SetFillColor(200, 255, 200); // Verde claro
$pdf->SetFont('helvetica', 'B', 9);
$pdf->Cell(155, 6, 'PAGAMENTO CARTÃO', 1, 0, 'L', true);
$pdf->Cell(35, 6, '12x de R$' . number_format($valor_parcela, 2, ',', '.'), 1, 1, 'R', true);

$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(255, 0, 0); // Vermelho para desconto
$pdf->Cell(155, 6, 'DESCONTO', 1, 0, 'L');
$pdf->Cell(35, 6, 'R$ ' . number_format($orcamento['desconto_aplicado'], 2, ',', '.'), 1, 1, 'R');

$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(155, 6, 'TOTAL PARCIAL (SEM FRETE)', 1, 0, 'L');
$pdf->Cell(35, 6, 'R$ ' . number_format($subtotal, 2, ',', '.'), 1, 1, 'R');

$pdf->Cell(155, 6, 'CUSTOS COM TRANSPORTE (FRETE) - FAVOR SOLICITAR COTAÇÃO', 1, 0, 'L');
$pdf->Cell(35, 6, 'R$ ' . number_format($orcamento['frete'], 2, ',', '.'), 1, 1, 'R');

$pdf->SetFillColor(200, 220, 255); // Azul claro
$pdf->Cell(155, 6, 'PAGAMENTO C/ DESCONTO À VISTA', 1, 0, 'L', true);
$pdf->Cell(35, 6, 'R$ ' . number_format($orcamento['valor_total'], 2, ',', '.'), 1, 1, 'R', true);

// Observações
$pdf->Ln(5);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 8, 'OBSERVAÇÕES', 0, true, 'L');
$pdf->SetFont('helvetica', '', 9);

$observacoes = array(
    '* O Orçamento tem validade 24 horas úteis.',
    '* Não recebemos nada a mais pelo parcelamento.',
    '* Caso queira fazer em outra instituição financeira, tudo bem!',
    '* Somos Empresa com CNPJ.',
    '* Todas as peças tem garantia mínima de 1 Ano.'
);

foreach($observacoes as $obs) {
    $pdf->Cell(0, 5, $obs, 0, true, 'L');
}

// Data e Assinatura
$pdf->Ln(10);
$pdf->Cell(0, 5, 'Belo Horizonte, ' . date('d/m/Y'), 0, true, 'L');
$pdf->Ln(15);
$pdf->Cell(60, 0, '', 'B', 1, 'L');
$pdf->Cell(60, 6, 'Tonny Heringer', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(60, 4, 'thinform@gmail.com', 0, 1, 'L');

// Gerar o PDF
$pdf->Output('Orcamento_' . $orcamento['numero_orcamento'] . '.pdf', 'I');

$conn->close();
?>
