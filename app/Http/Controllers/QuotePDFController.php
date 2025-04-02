<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Services\QuotePDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QuotePDFController extends Controller
{
    public function generate(Quote $quote)
    {
        try {
            Log::info('Iniciando geração de PDF para visualização', [
                'quote_id' => $quote->id,
                'quote_number' => $quote->quote_number
            ]);
            
            $quote->load(['client', 'products', 'category']);
            
            // Log dos dados carregados
            Log::info('Dados do orçamento carregados', [
                'client' => $quote->client->name,
                'products_count' => $quote->products->count(),
                'category' => $quote->category->name
            ]);

            $pdf = new QuotePDF($quote);
            $pdfContent = $pdf->generate();
            
            Log::info('PDF gerado com sucesso');
            
            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="orcamento-' . $quote->quote_number . '.pdf"');
            
        } catch (\Exception $e) {
            Log::error('Erro ao gerar PDF', [
                'quote_id' => $quote->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()
                ->back()
                ->with('error', 'Erro ao gerar PDF: ' . $e->getMessage());
        }
    }

    public function download(Quote $quote)
    {
        try {
            Log::info('Iniciando download de PDF', [
                'quote_id' => $quote->id,
                'quote_number' => $quote->quote_number
            ]);
            
            $quote->load(['client', 'products', 'category']);
            
            // Log dos dados carregados
            Log::info('Dados do orçamento carregados', [
                'client' => $quote->client->name,
                'products_count' => $quote->products->count(),
                'category' => $quote->category->name
            ]);

            $pdf = new QuotePDF($quote);
            $pdfContent = $pdf->generate();
            
            Log::info('PDF gerado com sucesso');
            
            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="orcamento-' . $quote->quote_number . '.pdf"');
            
        } catch (\Exception $e) {
            Log::error('Erro ao gerar PDF', [
                'quote_id' => $quote->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()
                ->back()
                ->with('error', 'Erro ao gerar PDF: ' . $e->getMessage());
        }
    }

    public function image(Quote $quote)
    {
        $quote->load(['client', 'products', 'category']);
        
        $pdf = Pdf::loadView('pdf.quote', compact('quote'));
        
        // Salva o PDF temporariamente
        $pdfPath = storage_path('app/temp/') . "orcamento-{$quote->quote_number}.pdf";
        Storage::makeDirectory('temp');
        $pdf->save($pdfPath);

        // Converte PDF para imagem usando Imagick
        $imagick = new \Imagick();
        $imagick->readImage($pdfPath);
        $imagick->setImageFormat('png');
        $imagick->setResolution(300, 300);
        $imagick->setCompressionQuality(95);
        
        // Remove o arquivo temporário
        unlink($pdfPath);
        
        return response($imagick->getImageBlob())
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', "attachment; filename=orcamento-{$quote->quote_number}.png");
    }
} 