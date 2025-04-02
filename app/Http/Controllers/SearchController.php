<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function clients(Request $request)
    {
        $search = $request->get('q', '');

        if (strlen($search) < 2) {
            return response()->json([]);
        }

        $clients = DB::table('clients')
            ->select('id', 'name', 'cpf_cnpj')
            ->where('name', 'like', "%{$search}%")
            ->orWhere('cpf_cnpj', 'like', "%{$search}%")
            ->orderBy('name')
            ->limit(10)
            ->get();

        return response()->json($clients);
    }

    public function products(Request $request)
    {
        $search = $request->get('q', '');

        if (strlen($search) < 2) {
            return response()->json([]);
        }

        $products = DB::table('products')
            ->select('id', 'name', 'code', 'sale_price')
            ->where('name', 'like', "%{$search}%")
            ->orWhere('code', 'like', "%{$search}%")
            ->orderBy('name')
            ->limit(10)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'code' => $product->code,
                    'sale_price' => (float) $product->sale_price
                ];
            });

        return response()->json($products);
    }
} 