@csrf

@if ($errors->any())
    <div class="mb-4 bg-red-50 p-4 rounded-md">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Ocorreram erros ao salvar o orçamento:</h3>
                <div class="mt-2 text-sm text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label for="client_search" class="block text-sm font-medium text-gray-700">Cliente *</label>
        <div class="relative">
            <input type="text" id="client_search" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('client_id') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                placeholder="Digite para buscar cliente..."
                autocomplete="off"
                value="{{ old('client_name', isset($quote) ? $quote->client->name : '') }}">
            <input type="hidden" name="client_id" id="client_id" required value="{{ old('client_id', isset($quote) ? $quote->client_id : '') }}">
            <div id="client_suggestions" class="absolute z-50 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm hidden"></div>
            @error('client_id')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="category_id" class="block text-sm font-medium text-gray-700">Tipo de Orçamento *</label>
        <select name="category_id" id="category_id" required 
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('category_id') border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500 @enderror">
            <option value="">Selecione um tipo</option>
            <option value="1" {{ (old('category_id', isset($quote) ? $quote->category_id : '') == 1) ? 'selected' : '' }}>COMPUTADOR</option>
            <option value="2" {{ (old('category_id', isset($quote) ? $quote->category_id : '') == 2) ? 'selected' : '' }}>UPGRADE</option>
            <option value="3" {{ (old('category_id', isset($quote) ? $quote->category_id : '') == 3) ? 'selected' : '' }}>PEÇAS AVULSAS</option>
        </select>
        @error('category_id')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="mt-6">
    <h3 class="text-lg font-medium text-gray-900">Produtos</h3>
    <div class="mt-2 space-y-4" id="products-container">
        <!-- Template para novos produtos -->
        <template id="product-template">
            <div class="product-item grid grid-cols-1 md:grid-cols-6 gap-4 p-4 bg-gray-50 rounded-lg">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Produto *</label>
                    <div class="relative">
                        <input type="text" class="product-search mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                            placeholder="Digite para buscar produto..."
                            autocomplete="off">
                        <input type="hidden" name="products[]" class="product-id" required>
                        <div class="product-suggestions absolute z-50 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm hidden"></div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Quantidade *</label>
                    <input type="number" name="quantities[]" class="quantity mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                        value="1" min="1" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Preço Unitário</label>
                    <input type="text" class="unit-price mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" readonly>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Desconto (%)</label>
                    <input type="number" name="discounts[]" class="discount mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                        value="0" min="0" max="100">
                </div>
                <div class="flex items-end">
                    <button type="button" class="remove-product text-red-600 hover:text-red-800">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </template>

        <!-- Container para produtos existentes -->
        <div id="existing-products">
            @if(isset($quote))
                @foreach($quote->products as $product)
                    <div class="product-item grid grid-cols-1 md:grid-cols-6 gap-4 p-4 bg-gray-50 rounded-lg">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Produto *</label>
                            <div class="relative">
                                <input type="text" class="product-search mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                                    value="{{ $product->name }}"
                                    placeholder="Digite para buscar produto..."
                                    autocomplete="off">
                                <input type="hidden" name="products[]" class="product-id" value="{{ $product->id }}" required>
                                <div class="product-suggestions absolute z-50 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm hidden"></div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Quantidade *</label>
                            <input type="number" name="quantities[]" class="quantity mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                                value="{{ $product->pivot->quantity }}" min="1" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Preço Unitário</label>
                            <input type="text" class="unit-price mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                                value="{{ number_format($product->pivot->unit_price, 2, ',', '.') }}" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Desconto (%)</label>
                            <input type="number" name="discounts[]" class="discount mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                                value="{{ $product->pivot->discount }}" min="0" max="100">
                        </div>
                        <div class="flex items-end">
                            <button type="button" class="remove-product text-red-600 hover:text-red-800">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <button type="button" id="add-product" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Adicionar Produto
    </button>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
    <div>
        <label for="validity" class="block text-sm font-medium text-gray-700">Validade (dias) *</label>
        <input type="number" name="validity" id="validity" required 
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            value="{{ isset($quote) ? $quote->validity : '2' }}" min="1">
    </div>

    <div>
        <label for="shipping_cost" class="block text-sm font-medium text-gray-700">Frete</label>
        <div class="mt-1 relative rounded-md shadow-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="text-gray-500 sm:text-sm">R$</span>
            </div>
            <input type="text" name="shipping_cost" id="shipping_cost" 
                class="pl-8 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm money"
                value="{{ isset($quote) ? number_format($quote->shipping_cost, 2, ',', '.') : '0,00' }}"
                placeholder="0,00">
        </div>
    </div>

    <div>
        <label for="additional_cost" class="block text-sm font-medium text-gray-700">Custos Adicionais</label>
        <div class="mt-1 relative rounded-md shadow-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="text-gray-500 sm:text-sm">R$</span>
            </div>
            <input type="text" name="additional_cost" id="additional_cost" 
                class="pl-8 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm money"
                value="{{ isset($quote) ? number_format($quote->additional_cost, 2, ',', '.') : '0,00' }}"
                placeholder="0,00">
        </div>
    </div>

    <div>
        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
        <select name="status" id="status" 
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            <option value="pending" {{ isset($quote) && $quote->status == 'pending' ? 'selected' : '' }}>Pendente</option>
            <option value="approved" {{ isset($quote) && $quote->status == 'approved' ? 'selected' : '' }}>Aprovado</option>
            <option value="rejected" {{ isset($quote) && $quote->status == 'rejected' ? 'selected' : '' }}>Rejeitado</option>
        </select>
    </div>
</div>

<div class="mt-6">
    <label for="notes" class="block text-sm font-medium text-gray-700">Observações</label>
    <textarea name="notes" id="notes" rows="3" 
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ isset($quote) ? $quote->notes : '' }}</textarea>
</div>

<!-- Seção de Totais -->
<div class="mt-6 bg-gray-50 p-4 rounded-lg">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Resumo do Orçamento</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Subtotal</label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 sm:text-sm">R$</span>
                </div>
                <input type="text" id="subtotal" readonly
                    class="pl-8 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm sm:text-sm"
                    value="0,00">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Total à Vista</label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 sm:text-sm">R$</span>
                </div>
                <input type="text" id="total_cash" readonly
                    class="pl-8 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm sm:text-sm"
                    value="0,00">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Total Parcelado</label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 sm:text-sm">R$</span>
                </div>
                <input type="text" id="total_installments" readonly
                    class="pl-8 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm sm:text-sm"
                    value="0,00">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Valor da Parcela (10x)</label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 sm:text-sm">R$</span>
                </div>
                <input type="text" id="installment_value" readonly
                    class="pl-8 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm sm:text-sm"
                    value="0,00">
            </div>
        </div>
    </div>
</div>

<!-- Botões de Ação -->
<div class="mt-6 flex justify-end space-x-3">
    <a href="{{ route('quotes.index') }}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
        Cancelar
    </a>
    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        {{ isset($quote) ? 'Atualizar' : 'Salvar' }} Orçamento
    </button>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Função para formatar valores monetários
    function formatMoney(value) {
        return value.toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    // Função para converter string monetária em número
    function parseMoney(value) {
        if (typeof value === 'number') return value;
        return parseFloat(value.replace(/\./g, '').replace(',', '.')) || 0;
    }

    // Inicializar máscaras para campos monetários
    $('.money').mask('#.##0,00', {
        reverse: true,
        placeholder: '0,00'
    });

    // Função para calcular totais
    function calculateTotals() {
        let subtotal = 0;
        let totalCash = 0;
        let totalInstallments = 0;

        // Calcula o subtotal dos produtos
        $('.product-item').each(function() {
            const quantity = parseInt($(this).find('.quantity').val()) || 0;
            const unitPrice = parseMoney($(this).find('.unit-price').val());
            const discount = parseFloat($(this).find('.discount').val()) || 0;

            const itemTotal = quantity * unitPrice * (1 - discount/100);
            subtotal += itemTotal;
        });

        // Adiciona frete e custos adicionais
        const shippingCost = parseMoney($('#shipping_cost').val());
        const additionalCost = parseMoney($('#additional_cost').val());

        totalCash = subtotal + shippingCost + additionalCost;
        totalInstallments = totalCash * 1.18; // 18% de acréscimo para parcelamento
        const monthlyInstallment = totalInstallments / 10; // 10x

        // Atualiza os campos
        $('#subtotal').val(formatMoney(subtotal));
        $('#total_cash').val(formatMoney(totalCash));
        $('#total_installments').val(formatMoney(totalInstallments));
        $('#installment_value').val(formatMoney(monthlyInstallment));
    }

    // Adiciona listeners para recalcular os totais
    $(document).on('change keyup', '.quantity, .unit-price, .discount, #shipping_cost, #additional_cost', calculateTotals);

    // Calcula os totais inicialmente
    calculateTotals();

    // Configurar o envio do formulário
    $('form').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const formData = new FormData(form[0]);
        
        // Garantir que todos os campos estão incluídos
        formData.append('_method', form.find('input[name="_method"]').val() || form.attr('method'));
        
        // Adicionar campos monetários formatados
        formData.set('shipping_cost', parseMoney($('#shipping_cost').val()).toString());
        formData.set('additional_cost', parseMoney($('#additional_cost').val()).toString());
        
        // Adicionar produtos
        const products = [];
        const quantities = [];
        const discounts = [];
        
        $('.product-item').each(function() {
            const productId = $(this).find('.product-id').val();
            const quantity = $(this).find('.quantity').val();
            const discount = $(this).find('.discount').val();
            
            if (productId) {
                products.push(productId);
                quantities.push(quantity);
                discounts.push(discount);
            }
        });
        
        // Remover arrays antigos e adicionar os novos
        formData.delete('products[]');
        formData.delete('quantities[]');
        formData.delete('discounts[]');
        
        products.forEach((id, index) => {
            formData.append('products[]', id);
            formData.append('quantities[]', quantities[index]);
            formData.append('discounts[]', discounts[index]);
        });
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST', // Sempre POST, o _method já está definido para PUT/PATCH
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            success: function(response) {
                if (response.success) {
                    window.location.href = response.redirect || '{{ route('quotes.index') }}';
                } else {
                    alert('Erro ao salvar orçamento: ' + (response.message || 'Erro desconhecido'));
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let errorMessage = 'Erros de validação:\n';
                    for (let field in errors) {
                        errorMessage += `${errors[field].join('\n')}\n`;
                    }
                    alert(errorMessage);
                } else {
                    alert('Erro ao salvar orçamento. Por favor, tente novamente.');
                }
            }
        });
    });
});
</script>
@endpush 