<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($product) ? 'Editar Produto' : 'Novo Produto' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ isset($product) ? route('products.update', $product->id) : route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(isset($product))
                            @method('PUT')
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="code" class="block text-sm font-medium text-gray-700">Código *</label>
                                <input type="text" name="code" id="code" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('code') border-red-500 @enderror" value="{{ old('code', $product->code ?? '') }}" required>
                                @error('code')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nome *</label>
                                <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('name') border-red-500 @enderror" value="{{ old('name', $product->name ?? '') }}" required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Descrição</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('description') border-red-500 @enderror">{{ old('description', $product->description ?? '') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label for="cost_price" class="block text-sm font-medium text-gray-700">Preço de Custo *</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">R$</span>
                                    </div>
                                    <input type="text" name="cost_price" id="cost_price" class="pl-8 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm money @error('cost_price') border-red-500 @enderror" value="{{ old('cost_price', isset($product) ? number_format($product->cost_price, 2, ',', '.') : '0,00') }}" required>
                                </div>
                                @error('cost_price')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="profit_margin" class="block text-sm font-medium text-gray-700">Margem de Lucro (%) *</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" name="profit_margin" id="profit_margin" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('profit_margin') border-red-500 @enderror" value="{{ old('profit_margin', $product->profit_margin ?? 15) }}" required step="0.01" min="0">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">%</span>
                                    </div>
                                </div>
                                @error('profit_margin')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="sale_price" class="block text-sm font-medium text-gray-700">Preço de Venda</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">R$</span>
                                    </div>
                                    <input type="text" id="sale_price" class="pl-8 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm money" value="{{ old('sale_price', isset($product) ? number_format($product->sale_price, 2, ',', '.') : '0,00') }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label for="installment_fee" class="block text-sm font-medium text-gray-700">Taxa de Parcelamento (%) *</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" name="installment_fee" id="installment_fee" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('installment_fee') border-red-500 @enderror" value="{{ old('installment_fee', $product->installment_fee ?? 18) }}" required step="0.01" min="0">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">%</span>
                                    </div>
                                </div>
                                @error('installment_fee')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="installment_price" class="block text-sm font-medium text-gray-700">Preço Parcelado</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">R$</span>
                                    </div>
                                    <input type="text" id="installment_price" class="pl-8 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm money" value="{{ old('installment_price', isset($product) ? number_format($product->installment_price, 2, ',', '.') : '0,00') }}" readonly>
                                </div>
                            </div>

                            <div>
                                <label for="monthly_installment" class="block text-sm font-medium text-gray-700">Parcela (10x)</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">R$</span>
                                    </div>
                                    <input type="text" id="monthly_installment" class="pl-8 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm money" value="{{ old('monthly_installment', isset($product) ? number_format($product->installment_price / 10, 2, ',', '.') : '0,00') }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label for="brand" class="block text-sm font-medium text-gray-700">Marca</label>
                                <input type="text" name="brand" id="brand" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('brand') border-red-500 @enderror" value="{{ old('brand', $product->brand ?? 'THINFORMA') }}">
                                @error('brand')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="supplier" class="block text-sm font-medium text-gray-700">Fornecedor</label>
                                <input type="text" name="supplier" id="supplier" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('supplier') border-red-500 @enderror" value="{{ old('supplier', $product->supplier ?? 'Google') }}">
                                @error('supplier')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="unit" class="block text-sm font-medium text-gray-700">Unidade</label>
                                <input type="text" name="unit" id="unit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('unit') border-red-500 @enderror" value="{{ old('unit', $product->unit ?? 'UN') }}">
                                @error('unit')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                            <div>
                                <label for="height" class="block text-sm font-medium text-gray-700">Altura (cm)</label>
                                <input type="number" name="height" id="height" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('height') border-red-500 @enderror" value="{{ old('height', $product->height ?? 0) }}" step="0.01" min="0">
                                @error('height')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="width" class="block text-sm font-medium text-gray-700">Largura (cm)</label>
                                <input type="number" name="width" id="width" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('width') border-red-500 @enderror" value="{{ old('width', $product->width ?? 0) }}" step="0.01" min="0">
                                @error('width')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="depth" class="block text-sm font-medium text-gray-700">Profundidade (cm)</label>
                                <input type="number" name="depth" id="depth" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('depth') border-red-500 @enderror" value="{{ old('depth', $product->depth ?? 0) }}" step="0.01" min="0">
                                @error('depth')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="weight" class="block text-sm font-medium text-gray-700">Peso (kg)</label>
                                <input type="number" name="weight" id="weight" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('weight') border-red-500 @enderror" value="{{ old('weight', $product->weight ?? 0) }}" step="0.01" min="0">
                                @error('weight')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="supplier_link" class="block text-sm font-medium text-gray-700">Link do Fornecedor</label>
                            <input type="url" name="supplier_link" id="supplier_link" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('supplier_link') border-red-500 @enderror" value="{{ old('supplier_link', $product->supplier_link ?? '') }}">
                            @error('supplier_link')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700">Categoria *</label>
                                <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('category_id') border-red-500 @enderror" required>
                                    <option value="">Selecione uma categoria</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-1">
                                <label for="image" class="block text-sm font-medium text-gray-700">Imagem</label>
                                <input type="file" name="image" id="image" accept="image/*" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    onchange="previewImage(this);">
                                
                                @if(isset($product) && $product->image)
                                    <div class="mt-2">
                                        <img src="{{ Storage::url($product->image) }}" alt="Miniatura do produto" class="h-32 w-32 object-cover rounded-md" id="imagePreview">
                                    </div>
                                @else
                                    <div class="mt-2 hidden" id="imagePreviewContainer">
                                        <img src="" alt="Miniatura do produto" class="h-32 w-32 object-cover rounded-md" id="imagePreview">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="active" id="active" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="1" {{ old('active', $product->active ?? true) ? 'checked' : '' }}>
                                <label for="active" class="ml-2 block text-sm text-gray-900">Ativo</label>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('products.index') }}" class="inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Cancelar
                            </a>
                            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                {{ isset($product) ? 'Atualizar' : 'Cadastrar' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const container = document.getElementById('imagePreviewContainer');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.classList.remove('hidden');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Função para calcular preços
    function calculatePrices() {
        const costPrice = parseFloat(document.getElementById('cost_price').value.replace(/[^0-9.]/g, '')) || 0;
        const profitMargin = parseFloat(document.getElementById('profit_margin').value) || 0;
        const installmentFee = parseFloat(document.getElementById('installment_fee').value) || 0;

        // Calcula preço de venda
        const salePrice = Math.ceil((costPrice / (1 - (profitMargin / 100))) * 100) / 100;
        document.getElementById('sale_price').value = salePrice.toFixed(2);

        // Calcula preço parcelado
        const installmentPrice = Math.ceil((salePrice / (1 - (installmentFee / 100))) * 100) / 100;
        document.getElementById('installment_price').value = installmentPrice.toFixed(2);

        // Calcula valor da parcela
        const installmentValue = (installmentPrice / 10).toFixed(2);
        document.getElementById('installment_value').value = installmentValue;
    }

    // Adiciona listeners para os campos que afetam os cálculos
    document.getElementById('cost_price').addEventListener('input', calculatePrices);
    document.getElementById('profit_margin').addEventListener('input', calculatePrices);
    document.getElementById('installment_fee').addEventListener('input', calculatePrices);

    // Calcula os preços iniciais
    calculatePrices();
    </script>
    @endpush
</x-app-layout> 