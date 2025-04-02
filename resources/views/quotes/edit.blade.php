<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Orçamento
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('quotes.update', $quote->id) }}">
                        @method('PUT')
                        @include('quotes.form')
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Produtos do Orçamento</h3>
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <x-product-list :products="$quote->products" :editable="true" />
            
            <div class="mt-6">
                <button type="button" 
                        class="add-product inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Adicionar Produto
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Listener para reordenação de produtos
        window.addEventListener('products-reordered', function(e) {
            const productIds = e.detail.productIds;
            // Atualizar os campos hidden com a nova ordem
            const productsInput = document.querySelector('input[name="products[]"]');
            if (productsInput) {
                productsInput.value = productIds.join(',');
            }
        });

        // Listener para adicionar produto
        document.querySelector('.add-product').addEventListener('click', function() {
            // Implementar lógica de adicionar produto
            // Pode ser um modal ou um formulário inline
        });

        // Listeners para editar e remover produtos
        document.querySelectorAll('.edit-product').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.dataset.productId;
                // Implementar lógica de edição
            });
        });

        document.querySelectorAll('.remove-product').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.dataset.productId;
                // Implementar lógica de remoção
            });
        });
    });
    </script>
    @endpush
</x-app-layout> 