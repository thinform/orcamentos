@props(['products' => [], 'editable' => true])

<div class="space-y-4">
    <div id="product-list" class="space-y-4">
        @foreach($products as $product)
            <div class="product-item bg-white p-4 rounded-lg shadow-sm border border-gray-200 cursor-move" 
                 data-product-id="{{ $product->id }}"
                 draggable="true">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                            </svg>
                            <h4 class="text-lg font-medium text-gray-900">{{ $product->name }}</h4>
                        </div>
                        <div class="mt-2 grid grid-cols-2 gap-4 text-sm text-gray-600">
                            <div>
                                <span class="font-medium">Preço:</span>
                                <span>R$ {{ number_format($product->sale_price, 2, ',', '.') }}</span>
                            </div>
                            <div>
                                <span class="font-medium">Estoque:</span>
                                <span>{{ $product->stock }}</span>
                            </div>
                            <div>
                                <span class="font-medium">Categoria:</span>
                                <span>{{ $product->category->name }}</span>
                            </div>
                            <div>
                                <span class="font-medium">Código:</span>
                                <span>{{ $product->code }}</span>
                            </div>
                        </div>
                    </div>
                    @if($editable)
                        <div class="ml-4 flex items-center space-x-2">
                            <button type="button" 
                                    class="edit-product text-indigo-600 hover:text-indigo-900"
                                    data-product-id="{{ $product->id }}">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button type="button" 
                                    class="remove-product text-red-600 hover:text-red-900"
                                    data-product-id="{{ $product->id }}">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const productList = document.getElementById('product-list');
    
    if (productList) {
        new Sortable(productList, {
            animation: 150,
            handle: '.product-item',
            onEnd: function(evt) {
                const productIds = Array.from(productList.children).map(item => item.dataset.productId);
                // Emitir evento para atualizar a ordem
                window.dispatchEvent(new CustomEvent('products-reordered', {
                    detail: { productIds }
                }));
            }
        });
    }
});
</script>
@endpush 