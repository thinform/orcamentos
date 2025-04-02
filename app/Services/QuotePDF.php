<?php

namespace App\Services;

use TCPDF;
use App\Models\Quote;

class QuotePDF extends TCPDF
{
    private $quote;
    private $background_image;

    public function __construct(Quote $quote)
    {
        parent::__construct(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        $this->quote = $quote;
        $this->background_image = public_path('storage/papel_timbrado.png');
        
        // Configurações do documento
        $this->SetCreator('THINFORMA');
        $this->SetAuthor('THINFORMA');
        $this->SetTitle('Orçamento #' . $quote->quote_number);
        
        // Configurações de margem ajustadas
        $this->SetMargins(15, 70, 15); // Reduzindo margens laterais e aumentando margem superior
        $this->SetHeaderMargin(15);
        $this->SetFooterMargin(15);
        
        // Configurações de fonte
        $this->SetFont('helvetica', '', 9);
        
        // Configurações de imagem
        $this->setImageScale(PDF_IMAGE_SCALE_RATIO);
        
        // Configurações de quebra de página
        $this->SetAutoPageBreak(TRUE, 25);
    }

    public function Header()
    {
        // Adiciona a imagem de fundo
        if (file_exists($this->background_image)) {
            $this->SetAutoPageBreak(false, 0);
            $this->Image(
                $this->background_image,
                0,
                0,
                $this->getPageWidth(),
                $this->getPageHeight(),
                'PNG',
                '',
                '',
                false,
                300
            );
            $this->SetAutoPageBreak(true, 25);
        }
        
        // Posiciona o cursor para o cabeçalho
        $this->SetY(15);
        
        // Adiciona o cabeçalho
        $this->SetFont('helvetica', 'B', 11);
        $this->Cell(150, 6, 'THINFORMA - CNPJ: 54.178.539/0001-64', 0, 0, 'L');
        
        // Número do orçamento em uma caixa
        $this->SetFillColor(240, 240, 240);
        $this->Cell(30, 6, 'Nº. ' . $this->quote->quote_number, 1, 1, 'C', true);
        
        $this->SetFont('helvetica', '', 8);
        $this->Cell(0, 4, 'Rua Tenerife, Nº 407 - JARDIM ATLÂNTICO', 0, 1, 'L');
        $this->Cell(0, 4, '31.550-220, BELO HORIZONTE, MG - WHATSAPP: (31) 99243-1019 - SITE: http://www.thinforma.com.br', 0, 1, 'L');
        
        // Espaço antes das informações do cliente
        $this->Ln(10);
        
        // Salva a posição Y atual
        $currentY = $this->GetY();
        
        // Informações do cliente em uma caixa com fundo cinza claro
        $this->SetFillColor(245, 245, 245); // Mesma cor do cabeçalho da tabela
        $boxHeight = 20; // Altura ajustada para 4 linhas de texto + padding
        $this->RoundedRect(15, $currentY, 180, $boxHeight, 2, '1111', 'DF');
        
        // Ajusta a posição Y para o texto dentro da caixa
        $this->SetY($currentY + 2); // Pequeno padding superior
        
        $this->SetFont('helvetica', '', 8);
        $this->Cell(0, 4, 'THINFORMA - (31) 99243-1019 - thinform@gmail.com', 0, 1, 'L');
        $this->Cell(0, 4, 'Rua Tenerife, 407 -', 0, 1, 'L');
        $this->Cell(0, 4, '31550-220 - Belo Horizonte, MG', 0, 1, 'L');
        $this->Cell(0, 4, 'CPF/CNPJ: 54.178.539/0001-64', 0, 1, 'L');
        
        // Ajusta a posição Y após a caixa
        $this->SetY($currentY + $boxHeight + 5);
        
        // Título do detalhamento
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(0, 6, 'DETALHAMENTO DO PEDIDO', 0, 1, 'L');
    }

    public function Footer()
    {
        $this->SetY(-20);
        $this->SetFont('helvetica', 'I', 7);
        $this->Cell(0, 5, 'Página ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 0, 'C');
    }

    public function generate()
    {
        $this->AddPage();
        
        // Tabela de produtos
        // Cabeçalho da tabela
        $this->SetFillColor(245, 245, 245);
        $this->SetFont('helvetica', 'B', 8);
        
        // Ajuste nas larguras das colunas
        $w = array(20, 100, 15, 15, 30); // Larguras ajustadas
        $this->Cell($w[0], 6, 'COD', 1, 0, 'C', true);
        $this->Cell($w[1], 6, 'DESCRIÇÃO DO PRODUTO', 1, 0, 'C', true);
        $this->Cell($w[2], 6, 'QTD', 1, 0, 'C', true);
        $this->Cell($w[3], 6, 'UND', 1, 0, 'C', true);
        $this->Cell($w[4], 6, 'VL. TOTAL', 1, 1, 'C', true);
        
        // Dados dos produtos
        $this->SetFont('helvetica', '', 8);
        foreach ($this->quote->products as $product) {
            $this->Cell($w[0], 5, $product->code, 1, 0, 'C');
            $this->Cell($w[1], 5, $product->name, 1, 0, 'L');
            $this->Cell($w[2], 5, $product->pivot->quantity, 1, 0, 'C');
            $this->Cell($w[3], 5, 'PÇ', 1, 0, 'C');
            $this->Cell($w[4], 5, 'R$ ' . number_format($product->pivot->quantity * $product->pivot->unit_price, 2, ',', '.'), 1, 1, 'R');
        }
        
        // Linhas em branco
        for ($i = count($this->quote->products); $i < 20; $i++) {
            $this->Cell($w[0], 5, '', 1, 0, 'C');
            $this->Cell($w[1], 5, '', 1, 0, 'L');
            $this->Cell($w[2], 5, '', 1, 0, 'C');
            $this->Cell($w[3], 5, '', 1, 0, 'C');
            $this->Cell($w[4], 5, '', 1, 1, 'R');
        }
        
        $this->Ln(5);
        
        // Totais (ajustados para alinhar com a tabela)
        $leftWidth = array_sum(array_slice($w, 0, 4)); // Soma das larguras das 4 primeiras colunas
        
        $this->SetFont('helvetica', '', 8);
        $this->Cell($leftWidth, 5, 'PAGAMENTO CARTÃO:', 1, 0, 'L');
        $this->Cell($w[4], 5, '10x de R$ ' . number_format($this->quote->monthly_installment, 2, ',', '.'), 1, 1, 'R');
        
        $this->SetTextColor(255, 0, 0);
        $this->Cell($leftWidth, 5, 'DESCONTO:', 1, 0, 'L');
        $this->Cell($w[4], 5, 'R$ ' . number_format($this->quote->total * 0.18, 2, ',', '.'), 1, 1, 'R');
        
        $this->SetTextColor(0, 0, 0);
        $this->SetFillColor(255, 245, 245);
        $this->Cell($leftWidth, 5, 'TOTAL PARCIAL (SEM FRETE):', 1, 0, 'L', true);
        $this->Cell($w[4], 5, 'R$ ' . number_format($this->quote->total, 2, ',', '.'), 1, 1, 'R', true);
        
        $this->SetFillColor(240, 248, 255);
        $this->Cell($leftWidth, 5, 'CUSTOS COM TRANSPORTE (FRETE) - FAVOR SOLICITAR COTAÇÃO:', 1, 0, 'L', true);
        $this->Cell($w[4], 5, 'R$ ' . number_format($this->quote->shipping_cost, 2, ',', '.'), 1, 1, 'R', true);
        
        $this->SetFillColor(0, 102, 204);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('helvetica', 'B', 8);
        $this->Cell($leftWidth, 5, 'PAGAMENTO C/ DESCONTO À VISTA:', 1, 0, 'L', true);
        $this->Cell($w[4], 5, 'R$ ' . number_format($this->quote->total - ($this->quote->total * 0.18), 2, ',', '.'), 1, 1, 'R', true);
        
        $this->Ln(5);
        
        // Observações
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('helvetica', '', 8);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(array_sum($w), 5, 'Observações:', 1, 1, 'L', true);
        $this->Cell(array_sum($w), 5, '* O Orçamento tem validade 48 horas;', 1, 1, 'L', true);
        $this->Cell(array_sum($w), 5, '* Máquinas Personalizadas não têm direito a Arrependimento;', 1, 1, 'L', true);
        $this->Cell(array_sum($w), 5, '* Somos Empresa com CNPJ devidamente regularizada;', 1, 1, 'L', true);
        $this->Cell(array_sum($w), 5, '* Todas as peças tem Garantia Oficial e Procedência;', 1, 1, 'L', true);
        
        $this->Ln(5);
        
        // Data e assinatura
        $this->Cell(0, 5, 'Belo Horizonte, ' . now()->format('d \d\e F \d\e Y') . '.', 0, 1, 'L');
        $this->Cell(60, 1, '', 'B', 1, 'L');
        $this->SetFont('helvetica', 'B', 8);
        $this->Cell(0, 4, 'Tonny Heringer', 0, 1, 'L');
        $this->SetFont('helvetica', '', 8);
        $this->Cell(0, 4, 'thinform@gmail.com', 0, 1, 'L');
        
        return $this->Output('S');
    }
} 