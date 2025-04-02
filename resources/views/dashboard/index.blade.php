<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Cards de Estatísticas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900">Total de Orçamentos</h3>
                        <p class="text-3xl font-bold text-indigo-600">{{ $totalQuotes }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900">Valor Total</h3>
                        <p class="text-3xl font-bold text-green-600">{{ format_currency($totalValue) }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900">Taxa de Aprovação</h3>
                        <p class="text-3xl font-bold text-blue-600">{{ number_format($approvalRate, 1) }}%</p>
                    </div>
                </div>
            </div>

            <!-- Gráficos -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Gráfico de Status -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Status dos Orçamentos</h3>
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>

                <!-- Gráfico de Evolução -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Evolução Mensal</h3>
                        <canvas id="evolutionChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Produtos Mais Orçados -->
            <div class="mt-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Produtos Mais Orçados</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produto</th>
                                        <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Quantidade</th>
                                        <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($topProducts as $product)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ $product->total_quantity }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ format_currency($product->total_value) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Gráfico de Status
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'pie',
            data: {
                labels: {!! json_encode($statusLabels) !!},
                datasets: [{
                    data: {!! json_encode($statusData) !!},
                    backgroundColor: ['#FCD34D', '#34D399', '#F87171'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });

        // Gráfico de Evolução
        const evolutionCtx = document.getElementById('evolutionChart').getContext('2d');
        new Chart(evolutionCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($evolutionLabels) !!},
                datasets: [{
                    label: 'Total de Orçamentos',
                    data: {!! json_encode($evolutionData) !!},
                    borderColor: '#6366F1',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout> 