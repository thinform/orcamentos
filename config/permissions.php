<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configurações das Permissões
    |--------------------------------------------------------------------------
    |
    | Aqui você pode configurar as permissões do sistema.
    |
    */

    'roles' => [
        'admin' => [
            'name' => 'Administrador',
            'permissions' => [
                'users.*',
                'clients.*',
                'categories.*',
                'products.*',
                'quotes.*',
            ],
        ],
        'manager' => [
            'name' => 'Gerente',
            'permissions' => [
                'clients.*',
                'categories.*',
                'products.*',
                'quotes.*',
            ],
        ],
        'user' => [
            'name' => 'Usuário',
            'permissions' => [
                'clients.index',
                'clients.show',
                'categories.index',
                'categories.show',
                'products.index',
                'products.show',
                'quotes.index',
                'quotes.show',
                'quotes.create',
                'quotes.store',
            ],
        ],
    ],

    'permissions' => [
        'users' => [
            'index' => 'Listar Usuários',
            'show' => 'Visualizar Usuário',
            'create' => 'Criar Usuário',
            'store' => 'Salvar Usuário',
            'edit' => 'Editar Usuário',
            'update' => 'Atualizar Usuário',
            'destroy' => 'Excluir Usuário',
        ],
        'clients' => [
            'index' => 'Listar Clientes',
            'show' => 'Visualizar Cliente',
            'create' => 'Criar Cliente',
            'store' => 'Salvar Cliente',
            'edit' => 'Editar Cliente',
            'update' => 'Atualizar Cliente',
            'destroy' => 'Excluir Cliente',
        ],
        'categories' => [
            'index' => 'Listar Categorias',
            'show' => 'Visualizar Categoria',
            'create' => 'Criar Categoria',
            'store' => 'Salvar Categoria',
            'edit' => 'Editar Categoria',
            'update' => 'Atualizar Categoria',
            'destroy' => 'Excluir Categoria',
        ],
        'products' => [
            'index' => 'Listar Produtos',
            'show' => 'Visualizar Produto',
            'create' => 'Criar Produto',
            'store' => 'Salvar Produto',
            'edit' => 'Editar Produto',
            'update' => 'Atualizar Produto',
            'destroy' => 'Excluir Produto',
        ],
        'quotes' => [
            'index' => 'Listar Orçamentos',
            'show' => 'Visualizar Orçamento',
            'create' => 'Criar Orçamento',
            'store' => 'Salvar Orçamento',
            'edit' => 'Editar Orçamento',
            'update' => 'Atualizar Orçamento',
            'destroy' => 'Excluir Orçamento',
            'approve' => 'Aprovar Orçamento',
            'reject' => 'Rejeitar Orçamento',
        ],
    ],
]; 