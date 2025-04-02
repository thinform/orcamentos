<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Produto: {{ $product->name }}
            </h2>
            <a href="{{ route('products.index') }}" class="btn-secondary inline-flex items-center px-4 py-2 rounded-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                </svg>
                Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="code" class="form-label">Código Interno</label>
                                <input type="text" name="code" id="code" class="form-input" value="{{ old('code', $product->code) }}">
                                @error('code')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="name" class="form-label">Nome</label>
                                <input type="text" name="name" id="name" class="form-input" value="{{ old('name', $product->name) }}" required>
                                @error('name')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="category_id" class="form-label">Categoria</label>
                                <select name="category_id" id="category_id" class="form-input" required>
                                    <option value="">Selecione uma categoria</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="brand" class="form-label">Marca</label>
                                <input type="text" name="brand" id="brand" class="form-input" value="{{ old('brand', $product->brand) }}">
                                @error('brand')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="cost_price" class="form-label">Preço de Custo</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                                        R$
                                    </span>
                                    <input type="number" name="cost_price" id="cost_price" class="form-input pl-10" value="{{ old('cost_price', $product->cost_price / 100) }}" step="0.01" min="0" required>
                                </div>
                                @error('cost_price')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="profit_margin" class="form-label">Margem de Lucro (%)</label>
                                <input type="number" name="profit_margin" id="profit_margin" class="form-input" value="{{ old('profit_margin', $product->profit_margin) }}" step="0.01" min="0" max="100">
                                @error('profit_margin')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="unit" class="form-label">Unidade</label>
                                <select name="unit" id="unit" class="form-input" required>
                                    <option value="">Selecione uma unidade</option>
                                    <option value="UN" {{ old('unit', $product->unit) == 'UN' ? 'selected' : '' }}>Unidade (UN)</option>
                                    <option value="KG" {{ old('unit', $product->unit) == 'KG' ? 'selected' : '' }}>Quilograma (KG)</option>
                                    <option value="M" {{ old('unit', $product->unit) == 'M' ? 'selected' : '' }}>Metro (M)</option>
                                    <option value="M2" {{ old('unit', $product->unit) == 'M2' ? 'selected' : '' }}>Metro Quadrado (M²)</option>
                                    <option value="M3" {{ old('unit', $product->unit) == 'M3' ? 'selected' : '' }}>Metro Cúbico (M³)</option>
                                    <option value="L" {{ old('unit', $product->unit) == 'L' ? 'selected' : '' }}>Litro (L)</option>
                                    <option value="CX" {{ old('unit', $product->unit) == 'CX' ? 'selected' : '' }}>Caixa (CX)</option>
                                    <option value="PCT" {{ old('unit', $product->unit) == 'PCT' ? 'selected' : '' }}>Pacote (PCT)</option>
                                </select>
                                @error('unit')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="supplier" class="form-label">Fornecedor</label>
                                <input type="text" name="supplier" id="supplier" class="form-input" value="{{ old('supplier', $product->supplier) }}">
                                @error('supplier')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                            <div>
                                <label for="height" class="form-label">Altura</label>
                                <input type="number" name="height" id="height" class="form-input" value="{{ old('height', $product->height) }}" step="0.01" min="0">
                                @error('height')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="width" class="form-label">Largura</label>
                                <input type="number" name="width" id="width" class="form-input" value="{{ old('width', $product->width) }}" step="0.01" min="0">
                                @error('width')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="depth" class="form-label">Profundidade</label>
                                <input type="number" name="depth" id="depth" class="form-input" value="{{ old('depth', $product->depth) }}" step="0.01" min="0">
                                @error('depth')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="weight" class="form-label">Peso (kg)</label>
                                <input type="number" name="weight" id="weight" class="form-input" value="{{ old('weight', $product->weight) }}" step="0.01" min="0">
                                @error('weight')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="supplier_link" class="form-label">Link do Fornecedor</label>
                            <input type="url" name="supplier_link" id="supplier_link" class="form-input" value="{{ old('supplier_link', $product->supplier_link) }}">
                            @error('supplier_link')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">Descrição</label>
                            <textarea name="description" id="description" rows="3" class="form-input">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="image" class="form-label">Imagem</label>
                            @if($product->image)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="h-32 w-32 object-cover rounded">
                                </div>
                            @endif
                            <input type="file" name="image" id="image" class="form-input" accept="image/*">
                            <p class="text-sm text-gray-500 mt-1">Deixe em branco para manter a imagem atual</p>
                            @error('image')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="btn-primary px-4 py-2 rounded-md">
                                Atualizar Produto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 