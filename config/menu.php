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
            'route' => 'dashboard',
            'icon' => 'fas fa-home',
            'permission' => null,
        ],
        [
            'title' => 'Clientes',
            'route' => 'clients.index',
            'icon' => 'fas fa-users',
            'permission' => 'clients.index',
        ],
        [
            'title' => 'Categorias',
            'route' => 'categories.index',
            'icon' => 'fas fa-tags',
            'permission' => 'categories.index',
        ],
        [
            'title' => 'Produtos',
            'route' => 'products.index',
            'icon' => 'fas fa-box',
            'permission' => 'products.index',
        ],
        [
            'title' => 'Orçamentos',
            'route' => 'quotes.index',
            'icon' => 'fas fa-file-invoice-dollar',
            'permission' => 'quotes.index',
        ],
        [
            'title' => 'Usuários',
            'route' => 'users.index',
            'icon' => 'fas fa-user',
            'permission' => 'users.index',
        ],
    ],

    'navbar' => [
        [
            'title' => 'Perfil',
            'route' => 'profile.edit',
            'icon' => 'fas fa-user-circle',
            'permission' => null,
        ],
        [
            'title' => 'Sair',
            'route' => 'logout',
            'icon' => 'fas fa-sign-out-alt',
            'permission' => null,
        ],
    ],
]; 