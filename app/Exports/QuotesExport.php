<?php

namespace App\Exports;

use App\Models\Quote;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class QuotesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $quotes;

    public function __construct($quotes)
    {
        $this->quotes = $quotes;
    }

    public function collection()
    {
        return $this->quotes;
    }

    public function headings(): array
    {
        return [
            'Número',
            'Cliente',
            'Categoria',
            'Status',
            'Total',
            'Data de Criação',
            'Validade (dias)',
            'Frete',
            'Custos Adicionais',
            'Observações'
        ];
    }

    public function map($quote): array
    {
        return [
            $quote->quote_number,
            $quote->client->name,
            $quote->category->name,
            __("quotes.status.{$quote->status}"),
            number_format($quote->total, 2, ',', '.'),
            $quote->created_at->format('d/m/Y H:i'),
            $quote->validity,
            number_format($quote->shipping_cost, 2, ',', '.'),
            number_format($quote->additional_cost, 2, ',', '.'),
            $quote->notes
        ];
    }
} 