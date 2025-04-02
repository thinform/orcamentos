<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Produtos
            </h2>
            <a href="{{ route('products.create') }}" class="btn-primary inline-flex items-center px-4 py-2 rounded-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Novo Produto
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="alert-success mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert-error mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="table-header">Código</th>
                                    <th scope="col" class="table-header">Nome</th>
                                    <th scope="col" class="table-header">Categoria</th>
                                    <th scope="col" class="table-header">Preço de Custo</th>
                                    <th scope="col" class="table-header">Margem</th>
                                    <th scope="col" class="table-header">Preço de Venda</th>
                                    <th scope="col" class="table-header">Preço Parcelado</th>
                                    <th scope="col" class="table-header">10x de</th>
                                    <th scope="col" class="table-header">Status</th>
                                    <th scope="col" class="table-header">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($products as $product)
                                    <tr>
                                        <td class="table-cell">
                                            <div class="text-sm">
                                                @if($product->code)
                                                    <span class="text-gray-500 text-xs">{{ $product->code }}</span>
                                                @endif
                                                <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                                @if($product->description)
                                                    <span class="text-gray-500 text-xs">
                                                        {{ Str::limit($product->description, 50) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="table-cell">
                                            <div class="text-sm">
                                                <div class="text-gray-900">{{ $product->category->name }}</div>
                                                @if($product->brand)
                                                    <span class="text-gray-500 text-xs">{{ $product->brand }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="table-cell">
                                            <div class="text-sm">
                                                <div class="text-gray-500 text-xs">Custo: R$ {{ number_format($product->cost_price / 100, 2, ',', '.') }}</div>
                                                <div class="font-medium text-gray-900">À vista: R$ {{ number_format($product->sale_price / 100, 2, ',', '.') }}</div>
                                                <div class="text-gray-500 text-xs">10x R$ {{ number_format($product->installment_value, 2, ',', '.') }}</div>
                                            </div>
                                        </td>
                                        <td class="table-cell">
                                            <div class="text-sm">
                                                <div class="text-gray-500 text-xs">Margem: {{ number_format($product->profit_margin, 2, ',', '.') }}%</div>
                                            </div>
                                        </td>
                                        <td class="table-cell">
                                            <div class="text-sm">
                                                <div class="font-medium text-gray-900">{{ $product->formatted_sale_price }}</div>
                                            </div>
                                        </td>
                                        <td class="table-cell">
                                            <div class="text-sm">
                                                <div class="font-medium text-gray-900">{{ $product->formatted_installment_price }}</div>
                                            </div>
                                        </td>
                                        <td class="table-cell">
                                            <div class="text-sm">
                                                <div class="text-gray-900">{{ $product->monthly_installment }}</div>
                                            </div>
                                        </td>
                                        <td class="table-cell">
                                            @if($product->active)
                                                <span class="text-gray-500 text-xs">Ativo</span>
                                            @else
                                                <span class="text-gray-500 text-xs">Inativo</span>
                                            @endif
                                        </td>
                                        <td class="table-cell">
                                            <a href="{{ route('products.edit', $product) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</a>
                                            <button onclick="showDeleteModal({{ $product->id }})" class="text-red-600 hover:text-red-900">Excluir</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="px-6 py-4 text-center text-sm font-medium text-gray-500">
                                            Nenhum produto encontrado
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('products._delete-modal')
</x-app-layout> 