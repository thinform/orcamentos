<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\Client;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\QuotePDF;
use App\Notifications\QuoteCreated;
use App\Notifications\QuoteStatusChanged;
use App\Exports\QuotesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Notifications\QuoteEmail;

class QuoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Quote::with(['client', 'products', 'category']);

        // Filtro por período
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filtro por status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtro por cliente
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        // Filtro por categoria
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Busca por número do orçamento
        if ($request->filled('quote_number')) {
            $query->where('quote_number', 'like', '%' . $request->quote_number . '%');
        }

        $quotes = $query->latest()->paginate(10)->withQueryString();

        return view('quotes.index', compact('quotes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('quotes.create', compact('clients', 'categories', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \Log::info('Iniciando QuoteController@store');
        \Log::info('Dados do request:', $request->all());

        try {
            $validated = $request->validate([
                'client_id' => 'required|exists:clients,id',
                'category_id' => 'required|exists:categories,id',
                'products' => 'required|array|min:1',
                'products.*' => 'required|exists:products,id',
                'quantities' => 'required|array|min:1',
                'quantities.*' => 'required|integer|min:1',
                'discounts' => 'required|array|min:1',
                'discounts.*' => 'required|numeric|min:0|max:100',
                'validity' => 'required|integer|min:1',
                'shipping_cost' => 'required|string',
                'additional_cost' => 'required|string',
                'notes' => 'nullable|string',
            ]);

            \Log::info('Dados validados com sucesso:', $validated);

            DB::beginTransaction();
            \Log::info('Iniciando transação');

            // Gerar número do orçamento no formato AAAAMMDDHHMMSS
            $quoteNumber = now()->format('YmdHis');
            \Log::info('Número do orçamento gerado:', ['quote_number' => $quoteNumber]);

            // Criar o orçamento
            $quote = Quote::create([
                'quote_number' => $quoteNumber,
                'client_id' => $validated['client_id'],
                'category_id' => $validated['category_id'],
                'validity' => $validated['validity'],
                'shipping_cost' => $this->formatCurrency($validated['shipping_cost']),
                'additional_cost' => $this->formatCurrency($validated['additional_cost']),
                'notes' => $validated['notes'],
                'status' => 'pending',
                'user_id' => auth()->id()
            ]);

            \Log::info('Orçamento base criado:', $quote->toArray());

            // Preparar dados dos produtos
            $products = [];
            foreach ($validated['products'] as $index => $productId) {
                \Log::info('Processando produto:', ['index' => $index, 'product_id' => $productId]);
                
                if (!empty($productId)) {
                    $product = Product::find($productId);
                    if ($product) {
                        $quantity = $validated['quantities'][$index];
                        $discount = $validated['discounts'][$index];
                        $unitPrice = $product->sale_price * (1 - ($discount / 100));
                        
                        $products[$productId] = [
                            'quantity' => $quantity,
                            'unit_price' => $unitPrice,
                            'discount' => $discount
                        ];
                        
                        \Log::info('Produto processado:', [
                            'product_id' => $productId,
                            'quantity' => $quantity,
                            'unit_price' => $unitPrice,
                            'discount' => $discount
                        ]);
                    } else {
                        throw new \Exception("Produto com ID {$productId} não encontrado");
                    }
                }
            }

            \Log::info('Produtos preparados para attach:', $products);

            if (!empty($products)) {
                $quote->products()->attach($products);
                \Log::info('Produtos anexados ao orçamento');
            } else {
                throw new \Exception('Nenhum produto válido foi fornecido');
            }

            // Envia notificação de criação apenas se houver um usuário associado
            if ($quote->user) {
                $quote->user->notify(new QuoteCreated($quote));
            }

            DB::commit();
            \Log::info('Transação commitada com sucesso');

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Orçamento criado com sucesso',
                    'redirect' => route('quotes.index')
                ]);
            }

            return redirect()->route('quotes.index')
                ->with('success', 'Orçamento criado com sucesso.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            \Log::error('Erro de validação:', ['errors' => $e->errors()]);
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro de validação',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return back()
                ->withErrors($e->errors())
                ->withInput();
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erro ao criar orçamento:', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao criar orçamento: ' . $e->getMessage()
                ], 500);
            }
            
            return back()
                ->with('error', 'Erro ao criar orçamento: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Quote $quote)
    {
        $quote->load(['client', 'products', 'category', 'history.user']);
        return view('quotes.show', compact('quote'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quote $quote)
    {
        $clients = Client::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('quotes.edit', compact('quote', 'clients', 'categories', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quote $quote)
    {
        try {
            \Log::info('Iniciando atualização do orçamento:', ['quote_id' => $quote->id, 'data' => $request->all()]);

            $validated = $request->validate([
                'client_id' => 'required|exists:clients,id',
                'category_id' => 'required|exists:categories,id',
                'products' => 'required|array|min:1',
                'products.*' => 'required|exists:products,id',
                'quantities' => 'required|array|min:1',
                'quantities.*' => 'required|integer|min:1',
                'discounts' => 'required|array|min:1',
                'discounts.*' => 'required|numeric|min:0|max:100',
                'validity' => 'required|integer|min:1',
                'shipping_cost' => 'required|string',
                'additional_cost' => 'required|string',
                'notes' => 'nullable|string',
                'status' => 'required|in:pending,approved,rejected'
            ]);

            DB::beginTransaction();

            // Atualizar o orçamento
            $oldStatus = $quote->status;
            
            \Log::info('Valores monetários antes da formatação:', [
                'shipping_cost' => $validated['shipping_cost'],
                'additional_cost' => $validated['additional_cost']
            ]);

            $quote->update([
                'client_id' => $validated['client_id'],
                'category_id' => $validated['category_id'],
                'validity' => $validated['validity'],
                'shipping_cost' => $this->formatCurrency($validated['shipping_cost']),
                'additional_cost' => $this->formatCurrency($validated['additional_cost']),
                'notes' => $validated['notes'],
                'status' => $validated['status'],
                'user_id' => auth()->id()
            ]);

            \Log::info('Orçamento atualizado:', $quote->toArray());

            // Atualizar produtos
            $products = [];
            foreach ($validated['products'] as $index => $productId) {
                if (!empty($productId)) {
                    $product = Product::find($productId);
                    if ($product) {
                        $quantity = $validated['quantities'][$index];
                        $discount = $validated['discounts'][$index];
                        $unitPrice = $product->sale_price;
                        
                        $products[$productId] = [
                            'quantity' => $quantity,
                            'unit_price' => $unitPrice,
                            'discount' => $discount
                        ];
                    }
                }
            }

            if (!empty($products)) {
                $quote->products()->sync($products);
                \Log::info('Produtos atualizados:', ['products' => $products]);
            }

            // Se o status mudou, envia notificação
            if ($oldStatus !== $quote->status && $quote->user) {
                $quote->user->notify(new QuoteStatusChanged($quote, $oldStatus));
                \Log::info('Notificação de mudança de status enviada');
            }

            DB::commit();
            \Log::info('Transação concluída com sucesso');

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Orçamento atualizado com sucesso',
                    'redirect' => route('quotes.index')
                ]);
            }

            return redirect()->route('quotes.index')
                ->with('success', 'Orçamento atualizado com sucesso.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            \Log::error('Erro de validação:', ['errors' => $e->errors()]);
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro de validação',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return back()
                ->withErrors($e->errors())
                ->withInput();
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erro ao atualizar orçamento:', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao atualizar orçamento: ' . $e->getMessage()
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'Erro ao atualizar orçamento: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quote $quote)
    {
        try {
            $quote->delete();
            return redirect()->route('quotes.index')
                ->with('success', 'Orçamento excluído com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir orçamento. Por favor, tente novamente.');
        }
    }

    public function downloadPDF(Quote $quote)
    {
        $pdf = new \App\Services\QuotePDFDomPDF();
        return $pdf->generate($quote);
    }

    public function duplicate(Quote $quote)
    {
        try {
            DB::beginTransaction();

            // Criar novo número de orçamento
            $newQuoteNumber = now()->format('YmdHis');

            // Duplicar o orçamento
            $newQuote = $quote->replicate();
            $newQuote->quote_number = $newQuoteNumber;
            $newQuote->status = 'pending';
            $newQuote->created_at = now();
            $newQuote->updated_at = now();
            $newQuote->save();

            // Duplicar os produtos associados
            foreach ($quote->products as $product) {
                $newQuote->products()->attach($product->id, [
                    'quantity' => $product->pivot->quantity,
                    'unit_price' => $product->pivot->unit_price,
                    'discount' => $product->pivot->discount
                ]);
            }

            // Enviar notificação de criação
            $newQuote->user->notify(new QuoteCreated($newQuote));

            DB::commit();

            return redirect()->route('quotes.edit', $newQuote)
                ->with('success', 'Orçamento duplicado com sucesso! Você pode fazer alterações necessárias agora.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erro ao duplicar orçamento:', [
                'message' => $e->getMessage(),
                'quote_id' => $quote->id
            ]);

            return back()->with('error', 'Erro ao duplicar orçamento: ' . $e->getMessage());
        }
    }

    private function formatCurrency($value)
    {
        try {
            \Log::info('Formatando valor monetário:', ['valor_original' => $value]);
            
            // Se já for numérico, retorna como float
            if (is_numeric($value)) {
                return (float) $value;
            }
            
            // Remove tudo exceto números, vírgulas e pontos
            $value = preg_replace('/[^0-9,.]/', '', $value);
            
            // Substitui vírgula por ponto
            $value = str_replace(',', '.', $value);
            
            // Converte para float
            $result = (float) $value;
            
            \Log::info('Valor formatado:', ['resultado' => $result]);
            
            return $result;
        } catch (\Exception $e) {
            \Log::error('Erro ao formatar valor monetário:', [
                'valor' => $value,
                'erro' => $e->getMessage()
            ]);
            return 0.0;
        }
    }

    public function exportExcel(Request $request)
    {
        $query = Quote::with(['client', 'category']);

        // Aplicar os mesmos filtros do index
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('quote_number')) {
            $query->where('quote_number', 'like', '%' . $request->quote_number . '%');
        }

        $quotes = $query->get();

        return Excel::download(new QuotesExport($quotes), 'orcamentos.xlsx');
    }

    public function previewPDF(Quote $quote)
    {
        $pdf = new QuotePDF();
        $pdfContent = $pdf->generate($quote);
        
        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="orcamento.pdf"');
    }

    public function sendWhatsApp(Quote $quote)
    {
        try {
            $phone = preg_replace('/[^0-9]/', '', $quote->client->phone);
            $message = "Olá {$quote->client->name}! Segue o link do seu orçamento: " . route('quotes.show', $quote);
            
            $whatsappUrl = "https://wa.me/55{$phone}?text=" . urlencode($message);
            
            return redirect()->away($whatsappUrl);
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao abrir WhatsApp: ' . $e->getMessage());
        }
    }

    public function sendEmail(Quote $quote)
    {
        try {
            $pdf = new QuotePDF();
            $pdfContent = $pdf->generate($quote);
            
            $tempPath = storage_path('app/temp/orcamento.pdf');
            Storage::put('temp/orcamento.pdf', $pdfContent);
            
            Mail::to($quote->client->email)
                ->send(new QuoteEmail($quote, $tempPath));
            
            Storage::delete('temp/orcamento.pdf');
            
            return back()->with('success', 'Orçamento enviado por e-mail com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao enviar e-mail: ' . $e->getMessage());
        }
    }
}
