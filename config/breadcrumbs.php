<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Breadcrumbs do Sistema
    |--------------------------------------------------------------------------
    |
    | Aqui você pode configurar os breadcrumbs do sistema.
    |
    */

    'dashboard' => [
        [
            'title' => 'Dashboard',
            'route' => 'dashboard',
            'icon' => 'fas fa-tachometer-alt',
        ],
    ],

    'quotes' => [
        'index' => [
            [
                'title' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'fas fa-tachometer-alt',
            ],
            [
                'title' => 'Orçamentos',
                'route' => 'quotes.index',
                'icon' => 'fas fa-file-invoice-dollar',
            ],
        ],
        'create' => [
            [
                'title' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'fas fa-tachometer-alt',
            ],
            [
                'title' => 'Orçamentos',
                'route' => 'quotes.index',
                'icon' => 'fas fa-file-invoice-dollar',
            ],
            [
                'title' => 'Criar Novo',
                'route' => 'quotes.create',
                'icon' => 'fas fa-plus',
            ],
        ],
        'edit' => [
            [
                'title' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'fas fa-tachometer-alt',
            ],
            [
                'title' => 'Orçamentos',
                'route' => 'quotes.index',
                'icon' => 'fas fa-file-invoice-dollar',
            ],
            [
                'title' => 'Editar',
                'route' => 'quotes.edit',
                'icon' => 'fas fa-edit',
            ],
        ],
        'show' => [
            [
                'title' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'fas fa-tachometer-alt',
            ],
            [
                'title' => 'Orçamentos',
                'route' => 'quotes.index',
                'icon' => 'fas fa-file-invoice-dollar',
            ],
            [
                'title' => 'Visualizar',
                'route' => 'quotes.show',
                'icon' => 'fas fa-eye',
            ],
        ],
    ],

    'products' => [
        'index' => [
            [
                'title' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'fas fa-tachometer-alt',
            ],
            [
                'title' => 'Produtos',
                'route' => 'products.index',
                'icon' => 'fas fa-box',
            ],
        ],
        'create' => [
            [
                'title' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'fas fa-tachometer-alt',
            ],
            [
                'title' => 'Produtos',
                'route' => 'products.index',
                'icon' => 'fas fa-box',
            ],
            [
                'title' => 'Criar Novo',
                'route' => 'products.create',
                'icon' => 'fas fa-plus',
            ],
        ],
        'edit' => [
            [
                'title' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'fas fa-tachometer-alt',
            ],
            [
                'title' => 'Produtos',
                'route' => 'products.index',
                'icon' => 'fas fa-box',
            ],
            [
                'title' => 'Editar',
                'route' => 'products.edit',
                'icon' => 'fas fa-edit',
            ],
        ],
        'show' => [
            [
                'title' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'fas fa-tachometer-alt',
            ],
            [
                'title' => 'Produtos',
                'route' => 'products.index',
                'icon' => 'fas fa-box',
            ],
            [
                'title' => 'Visualizar',
                'route' => 'products.show',
                'icon' => 'fas fa-eye',
            ],
        ],
    ],

    'clients' => [
        'index' => [
            [
                'title' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'fas fa-tachometer-alt',
            ],
            [
                'title' => 'Clientes',
                'route' => 'clients.index',
                'icon' => 'fas fa-users',
            ],
        ],
        'create' => [
            [
                'title' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'fas fa-tachometer-alt',
            ],
            [
                'title' => 'Clientes',
                'route' => 'clients.index',
                'icon' => 'fas fa-users',
            ],
            [
                'title' => 'Criar Novo',
                'route' => 'clients.create',
                'icon' => 'fas fa-plus',
            ],
        ],
        'edit' => [
            [
                'title' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'fas fa-tachometer-alt',
            ],
            [
                'title' => 'Clientes',
                'route' => 'clients.index',
                'icon' => 'fas fa-users',
            ],
            [
                'title' => 'Editar',
                'route' => 'clients.edit',
                'icon' => 'fas fa-edit',
            ],
        ],
        'show' => [
            [
                'title' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'fas fa-tachometer-alt',
            ],
            [
                'title' => 'Clientes',
                'route' => 'clients.index',
                'icon' => 'fas fa-users',
            ],
            [
                'title' => 'Visualizar',
                'route' => 'clients.show',
                'icon' => 'fas fa-eye',
            ],
        ],
    ],

    'categories' => [
        'index' => [
            [
                'title' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'fas fa-tachometer-alt',
            ],
            [
                'title' => 'Categorias',
                'route' => 'categories.index',
                'icon' => 'fas fa-tags',
            ],
        ],
        'create' => [
            [
                'title' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'fas fa-tachometer-alt',
            ],
            [
                'title' => 'Categorias',
                'route' => 'categories.index',
                'icon' => 'fas fa-tags',
            ],
            [
                'title' => 'Criar Nova',
                'route' => 'categories.create',
                'icon' => 'fas fa-plus',
            ],
        ],
        'edit' => [
            [
                'title' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'fas fa-tachometer-alt',
            ],
            [
                'title' => 'Categorias',
                'route' => 'categories.index',
                'icon' => 'fas fa-tags',
            ],
            [
                'title' => 'Editar',
                'route' => 'categories.edit',
                'icon' => 'fas fa-edit',
            ],
        ],
        'show' => [
            'title' => 'Visualizar Categoria',
            'route' => 'categories.show',
            'icon' => 'fas fa-eye',
        ],
    ],

    'users' => [
        'index' => [
            [
                'title' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'fas fa-tachometer-alt',
            ],
            [
                'title' => 'Usuários',
                'route' => 'users.index',
                'icon' => 'fas fa-users',
            ],
        ],
        'create' => [
            [
                'title' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'fas fa-tachometer-alt',
            ],
            [
                'title' => 'Usuários',
                'route' => 'users.index',
                'icon' => 'fas fa-users',
            ],
            [
                'title' => 'Criar Novo',
                'route' => 'users.create',
                'icon' => 'fas fa-plus',
            ],
        ],
        'edit' => [
            [
                'title' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'fas fa-tachometer-alt',
            ],
            [
                'title' => 'Usuários',
                'route' => 'users.index',
                'icon' => 'fas fa-users',
            ],
            [
                'title' => 'Editar',
                'route' => 'users.edit',
                'icon' => 'fas fa-edit',
            ],
        ],
        'show' => [
            [
                'title' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'fas fa-tachometer-alt',
            ],
            [
                'title' => 'Usuários',
                'route' => 'users.index',
                'icon' => 'fas fa-users',
            ],
            [
                'title' => 'Visualizar',
                'route' => 'users.show',
                'icon' => 'fas fa-eye',
            ],
        ],
    ],

    'reports' => [
        'index' => [
            [
                'title' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'fas fa-tachometer-alt',
            ],
            [
                'title' => 'Relatórios',
                'route' => 'reports.index',
                'icon' => 'fas fa-chart-bar',
            ],
        ],
        'quotes' => [
            'daily' => [
                [
                    'title' => 'Dashboard',
                    'route' => 'dashboard',
                    'icon' => 'fas fa-tachometer-alt',
                ],
                [
                    'title' => 'Relatórios',
                    'route' => 'reports.index',
                    'icon' => 'fas fa-chart-bar',
                ],
                [
                    'title' => 'Orçamentos Diários',
                    'route' => 'reports.quotes.daily',
                    'icon' => 'fas fa-file-invoice-dollar',
                ],
            ],
            'monthly' => [
                [
                    'title' => 'Dashboard',
                    'route' => 'dashboard',
                    'icon' => 'fas fa-tachometer-alt',
                ],
                [
                    'title' => 'Relatórios',
                    'route' => 'reports.index',
                    'icon' => 'fas fa-chart-bar',
                ],
                [
                    'title' => 'Orçamentos Mensais',
                    'route' => 'reports.quotes.monthly',
                    'icon' => 'fas fa-file-invoice-dollar',
                ],
            ],
            'yearly' => [
                [
                    'title' => 'Dashboard',
                    'route' => 'dashboard',
                    'icon' => 'fas fa-tachometer-alt',
                ],
                [
                    'title' => 'Relatórios',
                    'route' => 'reports.index',
                    'icon' => 'fas fa-chart-bar',
                ],
                [
                    'title' => 'Orçamentos Anuais',
                    'route' => 'reports.quotes.yearly',
                    'icon' => 'fas fa-file-invoice-dollar',
                ],
            ],
        ],
        'products' => [
            'most_quoted' => [
                [
                    'title' => 'Dashboard',
                    'route' => 'dashboard',
                    'icon' => 'fas fa-tachometer-alt',
                ],
                [
                    'title' => 'Relatórios',
                    'route' => 'reports.index',
                    'icon' => 'fas fa-chart-bar',
                ],
                [
                    'title' => 'Produtos Mais Orçados',
                    'route' => 'reports.products.most_quoted',
                    'icon' => 'fas fa-box',
                ],
            ],
        ],
        'clients' => [
            'most_quoted' => [
                [
                    'title' => 'Dashboard',
                    'route' => 'dashboard',
                    'icon' => 'fas fa-tachometer-alt',
                ],
                [
                    'title' => 'Relatórios',
                    'route' => 'reports.index',
                    'icon' => 'fas fa-chart-bar',
                ],
                [
                    'title' => 'Clientes Mais Orçados',
                    'route' => 'reports.clients.most_quoted',
                    'icon' => 'fas fa-users',
                ],
            ],
        ],
    ],

    'settings' => [
        'index' => [
            [
                'title' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'fas fa-tachometer-alt',
            ],
            [
                'title' => 'Configurações',
                'route' => 'settings.index',
                'icon' => 'fas fa-cog',
            ],
        ],
        'general' => [
            [
                'title' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'fas fa-tachometer-alt',
            ],
            [
                'title' => 'Configurações',
                'route' => 'settings.index',
                'icon' => 'fas fa-cog',
            ],
            [
                'title' => 'Gerais',
                'route' => 'settings.general',
                'icon' => 'fas fa-cogs',
            ],
        ],
        'notifications' => [
            [
                'title' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'fas fa-tachometer-alt',
            ],
            [
                'title' => 'Configurações',
                'route' => 'settings.index',
                'icon' => 'fas fa-cog',
            ],
            [
                'title' => 'Notificações',
                'route' => 'settings.notifications',
                'icon' => 'fas fa-bell',
            ],
        ],
    ],

    'profile' => [
        'edit' => [
            [
                'title' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'fas fa-tachometer-alt',
            ],
            [
                'title' => 'Meu Perfil',
                'route' => 'profile.edit',
                'icon' => 'fas fa-user',
            ],
        ],
    ],

    'notifications' => [
        'index' => [
            [
                'title' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'fas fa-tachometer-alt',
            ],
            [
                'title' => 'Notificações',
                'route' => 'notifications.index',
                'icon' => 'fas fa-bell',
            ],
        ],
    ],
]; 