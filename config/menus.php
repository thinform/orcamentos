<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Menus do Sistema
    |--------------------------------------------------------------------------
    |
    | Aqui você pode configurar os menus do sistema.
    |
    */

    'sidebar' => [
        [
            'title' => 'Dashboard',
            'icon' => 'fas fa-tachometer-alt',
            'route' => 'dashboard',
            'roles' => ['admin', 'manager', 'user'],
        ],
        [
            'title' => 'Orçamentos',
            'icon' => 'fas fa-file-invoice-dollar',
            'route' => 'quotes.index',
            'roles' => ['admin', 'manager', 'user'],
            'badge' => [
                'type' => 'pending_quotes_count',
                'class' => 'badge-warning',
            ],
            'submenu' => [
                [
                    'title' => 'Listar Todos',
                    'route' => 'quotes.index',
                    'roles' => ['admin', 'manager', 'user'],
                ],
                [
                    'title' => 'Criar Novo',
                    'route' => 'quotes.create',
                    'roles' => ['admin', 'manager', 'user'],
                ],
            ],
        ],
        [
            'title' => 'Produtos',
            'icon' => 'fas fa-box',
            'route' => 'products.index',
            'roles' => ['admin', 'manager'],
            'badge' => [
                'type' => 'low_stock_count',
                'class' => 'badge-danger',
            ],
            'submenu' => [
                [
                    'title' => 'Listar Todos',
                    'route' => 'products.index',
                    'roles' => ['admin', 'manager'],
                ],
                [
                    'title' => 'Criar Novo',
                    'route' => 'products.create',
                    'roles' => ['admin', 'manager'],
                ],
                [
                    'title' => 'Categorias',
                    'route' => 'categories.index',
                    'roles' => ['admin', 'manager'],
                ],
            ],
        ],
        [
            'title' => 'Clientes',
            'icon' => 'fas fa-users',
            'route' => 'clients.index',
            'roles' => ['admin', 'manager', 'user'],
            'submenu' => [
                [
                    'title' => 'Listar Todos',
                    'route' => 'clients.index',
                    'roles' => ['admin', 'manager', 'user'],
                ],
                [
                    'title' => 'Criar Novo',
                    'route' => 'clients.create',
                    'roles' => ['admin', 'manager', 'user'],
                ],
            ],
        ],
        [
            'title' => 'Relatórios',
            'icon' => 'fas fa-chart-bar',
            'route' => 'reports.index',
            'roles' => ['admin', 'manager'],
            'submenu' => [
                [
                    'title' => 'Orçamentos Diários',
                    'route' => 'reports.quotes.daily',
                    'roles' => ['admin', 'manager'],
                ],
                [
                    'title' => 'Orçamentos Mensais',
                    'route' => 'reports.quotes.monthly',
                    'roles' => ['admin', 'manager'],
                ],
                [
                    'title' => 'Orçamentos Anuais',
                    'route' => 'reports.quotes.yearly',
                    'roles' => ['admin', 'manager'],
                ],
                [
                    'title' => 'Produtos Mais Orçados',
                    'route' => 'reports.products.most_quoted',
                    'roles' => ['admin', 'manager'],
                ],
                [
                    'title' => 'Clientes Mais Orçados',
                    'route' => 'reports.clients.most_quoted',
                    'roles' => ['admin', 'manager'],
                ],
            ],
        ],
        [
            'title' => 'Usuários',
            'icon' => 'fas fa-user-shield',
            'route' => 'users.index',
            'roles' => ['admin'],
            'submenu' => [
                [
                    'title' => 'Listar Todos',
                    'route' => 'users.index',
                    'roles' => ['admin'],
                ],
                [
                    'title' => 'Criar Novo',
                    'route' => 'users.create',
                    'roles' => ['admin'],
                ],
            ],
        ],
        [
            'title' => 'Configurações',
            'icon' => 'fas fa-cog',
            'route' => 'settings.index',
            'roles' => ['admin'],
            'submenu' => [
                [
                    'title' => 'Gerais',
                    'route' => 'settings.general',
                    'roles' => ['admin'],
                ],
                [
                    'title' => 'Notificações',
                    'route' => 'settings.notifications',
                    'roles' => ['admin'],
                ],
            ],
        ],
    ],

    'user_dropdown' => [
        [
            'title' => 'Meu Perfil',
            'icon' => 'fas fa-user',
            'route' => 'profile.edit',
            'roles' => ['admin', 'manager', 'user'],
        ],
        [
            'title' => 'Notificações',
            'icon' => 'fas fa-bell',
            'route' => 'notifications.index',
            'roles' => ['admin', 'manager', 'user'],
            'badge' => [
                'type' => 'unread_notifications_count',
                'class' => 'badge-info',
            ],
        ],
        [
            'title' => 'Sair',
            'icon' => 'fas fa-sign-out-alt',
            'route' => 'logout',
            'roles' => ['admin', 'manager', 'user'],
        ],
    ],
]; 