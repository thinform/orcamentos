<!DOCTYPE html>
<html lang="pt-BR">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>THINFORMA - Sistema de Orçamentos</title>
            @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
<body class="bg-gradient-to-br from-[#DD1E21] to-[#4143C1] min-h-screen flex items-center justify-center">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="text-center">
            <img src="{{ asset('images/logo.png') }}" alt="THINFORMA" class="h-24 mx-auto mb-8">
            <h1 class="text-4xl font-bold text-white mb-4">Sistema de Orçamentos</h1>
            <p class="text-xl text-white/80 mb-8">Gerencie seus orçamentos de forma eficiente e profissional</p>
            
            <div class="space-x-4">
            @if (Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-[#DD1E21] bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#DD1E21] transition-colors">
                            Acessar Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-[#DD1E21] bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#DD1E21] transition-colors">
                            Entrar
                        </a>
                    @endauth
            @endif
                </div>

            <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="bg-white/10 backdrop-blur-lg rounded-lg p-6 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="text-xl font-semibold mb-2">Orçamentos Profissionais</h3>
                    <p class="text-white/80">Crie orçamentos detalhados e personalizados para seus clientes</p>
                </div>

                <!-- Card 2 -->
                <div class="bg-white/10 backdrop-blur-lg rounded-lg p-6 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <h3 class="text-xl font-semibold mb-2">Gestão de Produtos</h3>
                    <p class="text-white/80">Organize seu catálogo de produtos e serviços</p>
                </div>

                <!-- Card 3 -->
                <div class="bg-white/10 backdrop-blur-lg rounded-lg p-6 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="text-xl font-semibold mb-2">Relatórios e Análises</h3>
                    <p class="text-white/80">Acompanhe o desempenho do seu negócio com relatórios detalhados</p>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>
