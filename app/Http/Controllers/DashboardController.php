<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\Product;
use App\Models\Category;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Período padrão: últimos 30 dias
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        // Estatísticas gerais
        $totalQuotes = Quote::whereBetween('created_at', [$startDate, $endDate])->count();
        $approvedQuotes = Quote::whereBetween('created_at', [$startDate, $endDate])->where('status', 'approved')->count();
        $rejectedQuotes = Quote::whereBetween('created_at', [$startDate, $endDate])->where('status', 'rejected')->count();
        $pendingQuotes = Quote::whereBetween('created_at', [$startDate, $endDate])->where('status', 'pending')->count();
        
        // Valor total dos orçamentos
        $totalValue = Quote::whereBetween('created_at', [$startDate, $endDate])->sum(DB::raw('
            COALESCE((
                SELECT SUM(unit_price * quantity)
                FROM quote_product
                WHERE quote_product.quote_id = quotes.id
            ), 0) + COALESCE(shipping_cost, 0) + COALESCE(additional_cost, 0)
        '));

        // Taxa de aprovação
        $approvalRate = $totalQuotes > 0 
            ? round(($approvedQuotes / $totalQuotes) * 100, 2)
            : 0;

        // Dados para o gráfico de status
        $statusLabels = ['Aprovados', 'Rejeitados', 'Pendentes'];
        $statusData = [$approvedQuotes, $rejectedQuotes, $pendingQuotes];

        // Dados para o gráfico de evolução
        $quotesPerDay = Quote::whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $evolutionLabels = $quotesPerDay->pluck('date')->toArray();
        $evolutionData = $quotesPerDay->pluck('total')->toArray();

        // Produtos mais orçados
        $topProducts = DB::table('quote_product')
            ->join('products', 'quote_product.product_id', '=', 'products.id')
            ->join('quotes', 'quote_product.quote_id', '=', 'quotes.id')
            ->whereBetween('quotes.created_at', [$startDate, $endDate])
            ->select(
                'products.name',
                DB::raw('COUNT(*) as quote_count'),
                DB::raw('SUM(quote_product.quantity) as total_quantity'),
                DB::raw('SUM(quote_product.quantity * quote_product.unit_price) as total_value')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('quote_count')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact(
            'totalQuotes',
            'totalValue',
            'approvalRate',
            'statusLabels',
            'statusData',
            'evolutionLabels',
            'evolutionData',
            'topProducts',
            'startDate',
            'endDate'
        ));
    }
}
