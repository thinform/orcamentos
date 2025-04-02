<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('products.form', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:products',
            'name' => 'required',
            'description' => 'nullable',
            'cost_price' => 'required',
            'profit_margin' => 'required|numeric|min:0',
            'installment_fee' => 'required|numeric|min:0',
            'brand' => 'nullable',
            'supplier' => 'nullable',
            'unit' => 'nullable',
            'height' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'depth' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'supplier_link' => 'nullable|url',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'active' => 'boolean'
        ]);

        // Formata o preço de custo
        $validated['cost_price'] = $this->formatCurrency($request->cost_price);

        // Calcula o preço de venda (arredonda para o próximo centavo)
        $validated['sale_price'] = ceil($validated['cost_price'] / (1 - ($validated['profit_margin'] / 100)) * 100) / 100;

        // Calcula o preço parcelado (arredonda para o próximo centavo)
        $validated['installment_price'] = ceil($validated['sale_price'] / (1 - ($validated['installment_fee'] / 100)) * 100) / 100;

        // Upload da imagem
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Produto cadastrado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('products.form', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'code' => 'required|unique:products,code,' . $product->id,
            'name' => 'required',
            'description' => 'nullable',
            'cost_price' => 'required',
            'profit_margin' => 'required|numeric|min:0',
            'installment_fee' => 'required|numeric|min:0',
            'brand' => 'nullable',
            'supplier' => 'nullable',
            'unit' => 'nullable',
            'height' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'depth' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'supplier_link' => 'nullable|url',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'active' => 'boolean'
        ]);

        // Formata o preço de custo
        $validated['cost_price'] = $this->formatCurrency($request->cost_price);

        // Calcula o preço de venda (arredonda para o próximo centavo)
        $validated['sale_price'] = ceil($validated['cost_price'] / (1 - ($validated['profit_margin'] / 100)) * 100) / 100;

        // Calcula o preço parcelado (arredonda para o próximo centavo)
        $validated['installment_price'] = ceil($validated['sale_price'] / (1 - ($validated['installment_fee'] / 100)) * 100) / 100;

        // Upload da imagem
        if ($request->hasFile('image')) {
            // Remove a imagem antiga se existir
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Produto atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Verifica se o produto está em algum orçamento
        if ($product->quotes()->exists()) {
            return redirect()->route('products.index')
                ->with('error', 'Não é possível excluir um produto que está em orçamentos!');
        }

        // Remove a imagem
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produto excluído com sucesso.');
    }

    private function formatCurrency($value)
    {
        // Remove qualquer caractere que não seja número ou ponto
        $value = preg_replace('/[^0-9.]/', '', $value);
        return (float) $value;
    }
}
