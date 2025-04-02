<div class="bg-white p-6 shadow-sm sm:rounded-lg mb-6">
    <form action="{{ route('quotes.index') }}" method="GET" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Filtro por Período -->
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">Data Inicial</label>
                <input type="date" name="start_date" id="start_date" 
                    value="{{ request('start_date') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700">Data Final</label>
                <input type="date" name="end_date" id="end_date" 
                    value="{{ request('end_date') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            <!-- Filtro por Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Todos</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendente</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Aprovado</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejeitado</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Filtro por Cliente -->
            <div>
                <label for="client_id" class="block text-sm font-medium text-gray-700">Cliente</label>
                <select name="client_id" id="client_id" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Todos os Clientes</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                            {{ $client->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filtro por Categoria -->
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700">Categoria</label>
                <select name="category_id" id="category_id" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Todas as Categorias</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Busca por Número do Orçamento -->
            <div>
                <label for="quote_number" class="block text-sm font-medium text-gray-700">Número do Orçamento</label>
                <input type="text" name="quote_number" id="quote_number" 
                    value="{{ request('quote_number') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="Digite o número do orçamento">
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('quotes.index') }}" 
                class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Limpar Filtros
            </a>
            <button type="submit" 
                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Filtrar
            </button>
        </div>
    </form>
</div>