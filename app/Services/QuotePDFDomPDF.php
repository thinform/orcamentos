<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Quote;
use Illuminate\Support\Facades\Log;

class QuotePDFDomPDF
{
    public function generate(Quote $quote)
    {
        $imagePath = public_path('storage/papel_timbrado.png');
        
        // Verifica se o arquivo existe
        if (!file_exists($imagePath)) {
            Log::error('Arquivo de imagem não encontrado: ' . $imagePath);
            $imagePath = ''; // Define como vazio se não encontrar
        }

        $data = [
            'quote' => $quote,
            'background_image' => $imagePath
        ];

        try {
            $pdf = PDF::loadView('pdf.quote', $data);
            
            // Configurações do PDF
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isPhpEnabled', true);
            
            return $pdf->download('orcamento_' . $quote->quote_number . '.pdf');
        } catch (\Exception $e) {
            Log::error('Erro ao gerar PDF: ' . $e->getMessage());
            throw $e;
        }
    }
} 