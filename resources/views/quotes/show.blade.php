<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Visualizar Orçamento') }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('quotes.pdf', $quote) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" target="_blank">
                    Visualizar PDF
                </a>
                <a href="{{ route('quotes.pdf', $quote) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Download PDF
                </a>
                <a href="{{ route('quotes.edit', $quote) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    Editar
                </a>
                <a href="{{ route('quotes.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
            
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informações do Cliente</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600"><span class="font-medium">Nome:</span> {{ $quote->client->name }}</p>
                                <p class="text-sm text-gray-600"><span class="font-medium">CPF/CNPJ:</span> {{ $quote->client->formatted_cpf_cnpj }}</p>
                                <p class="text-sm text-gray-600"><span class="font-medium">Email:</span> {{ $quote->client->email }}</p>
                                <p class="text-sm text-gray-600"><span class="font-medium">Telefone:</span> {{ $quote->client->formatted_phone }}</p>
                                <p class="text-sm text-gray-600"><span class="font-medium">Endereço:</span> {{ $quote->client->full_address }}</p>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informações dos Produtos</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <x-product-list :products="$quote->products" :editable="false" />
                                
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <p class="text-sm text-gray-600"><span class="font-medium">Categoria:</span> {{ $quote->category->name }}</p>
                                    <p class="text-sm text-gray-600"><span class="font-medium">Frete:</span> R$ {{ number_format($quote->shipping_cost, 2, ',', '.') }}</p>
                                    <p class="text-sm text-gray-600"><span class="font-medium">Custos Adicionais:</span> R$ {{ number_format($quote->additional_cost, 2, ',', '.') }}</p>
                                    <p class="text-sm text-gray-600"><span class="font-medium">Subtotal:</span> R$ {{ number_format($quote->subtotal, 2, ',', '.') }}</p>
                                    <p class="text-sm text-gray-600"><span class="font-medium">Total:</span> R$ {{ number_format($quote->total, 2, ',', '.') }}</p>
                                    <p class="text-sm text-gray-600"><span class="font-medium">Total Parcelado:</span> R$ {{ number_format($quote->installment_total, 2, ',', '.') }}</p>
                                    <p class="text-sm text-gray-600"><span class="font-medium">Valor da Parcela (10x):</span> R$ {{ number_format($quote->monthly_installment, 2, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informações do Orçamento</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600"><span class="font-medium">Status:</span> 
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $quote->status_color }}-100 text-{{ $quote->status_color }}-800">
                                        {{ $quote->status_text }}
                                    </span>
                                </p>
                                <p class="text-sm text-gray-600"><span class="font-medium">Data de Criação:</span> {{ $quote->created_at->format('d/m/Y H:i') }}</p>
                                <p class="text-sm text-gray-600"><span class="font-medium">Validade:</span> {{ $quote->validity }} dias</p>
                                <p class="text-sm text-gray-600"><span class="font-medium">Condições de Pagamento:</span> {{ $quote->payment_terms }}</p>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Observações</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">{{ $quote->notes ?: 'Nenhuma observação registrada.' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <x-quote-history :history="$quote->history" />
            </div>

            <div class="flex space-x-4">
                <a href="{{ route('quotes.preview', $quote) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Preview PDF
                </a>

                <a href="{{ route('quotes.whatsapp', $quote) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564c.173.087.287.129.332.202.045.073.045.419-.1.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.029 18.88c-1.161 0-2.305-.292-3.318-.844l-3.677.964.984-3.595c-.607-1.052-.927-2.246-.926-3.468.001-3.825 3.113-6.937 6.937-6.937 1.856.001 3.598.723 4.907 2.034 1.31 1.311 2.031 3.054 2.03 4.908-.001 3.825-3.113 6.938-6.937 6.938z"/>
                    </svg>
                    Enviar WhatsApp
                </a>

                <a href="{{ route('quotes.email', $quote) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Enviar E-mail
                </a>

                <form action="{{ route('quotes.duplicate', $quote) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                        </svg>
                        Duplicar Orçamento
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout> 