<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Notificações do Sistema
    |--------------------------------------------------------------------------
    |
    | Aqui você pode configurar as notificações do sistema.
    |
    */

    'channels' => [
        'database' => [
            'enabled' => true,
        ],
        'mail' => [
            'enabled' => true,
        ],
    ],

    'events' => [
        'quote_created' => [
            'title' => 'Novo Orçamento Criado',
            'description' => 'Um novo orçamento foi criado no sistema.',
            'channels' => ['database', 'mail'],
            'roles' => ['admin', 'manager'],
            'mail' => [
                'subject' => 'Novo Orçamento Criado - #:id',
                'view' => 'emails.quotes.created',
            ],
        ],
        'quote_updated' => [
            'title' => 'Orçamento Atualizado',
            'description' => 'Um orçamento foi atualizado no sistema.',
            'channels' => ['database'],
            'roles' => ['admin', 'manager'],
            'mail' => [
                'subject' => 'Orçamento Atualizado - #:id',
                'view' => 'emails.quotes.updated',
            ],
        ],
        'quote_approved' => [
            'title' => 'Orçamento Aprovado',
            'description' => 'Um orçamento foi aprovado no sistema.',
            'channels' => ['database', 'mail'],
            'roles' => ['admin', 'manager'],
            'mail' => [
                'subject' => 'Orçamento Aprovado - #:id',
                'view' => 'emails.quotes.approved',
            ],
        ],
        'quote_rejected' => [
            'title' => 'Orçamento Rejeitado',
            'description' => 'Um orçamento foi rejeitado no sistema.',
            'channels' => ['database', 'mail'],
            'roles' => ['admin', 'manager'],
            'mail' => [
                'subject' => 'Orçamento Rejeitado - #:id',
                'view' => 'emails.quotes.rejected',
            ],
        ],
        'product_low_stock' => [
            'title' => 'Produto com Estoque Baixo',
            'description' => 'Um produto está com estoque baixo.',
            'channels' => ['database', 'mail'],
            'roles' => ['admin', 'manager'],
            'mail' => [
                'subject' => 'Produto com Estoque Baixo - :name',
                'view' => 'emails.products.low_stock',
            ],
        ],
        'user_created' => [
            'title' => 'Novo Usuário Criado',
            'description' => 'Um novo usuário foi criado no sistema.',
            'channels' => ['database'],
            'roles' => ['admin'],
            'mail' => [
                'subject' => 'Novo Usuário Criado - :name',
                'view' => 'emails.users.created',
            ],
        ],
        'client_created' => [
            'title' => 'Novo Cliente Criado',
            'description' => 'Um novo cliente foi criado no sistema.',
            'channels' => ['database'],
            'roles' => ['admin', 'manager'],
            'mail' => [
                'subject' => 'Novo Cliente Criado - :name',
                'view' => 'emails.clients.created',
            ],
        ],
    ],

    'templates' => [
        'database' => [
            'icon' => 'fas fa-bell',
            'color' => 'primary',
            'read_color' => 'secondary',
            'date_format' => 'd/m/Y H:i:s',
        ],
        'mail' => [
            'from' => [
                'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
                'name' => env('MAIL_FROM_NAME', 'Example'),
            ],
            'footer_text' => 'Se você tiver alguma dúvida, entre em contato conosco.',
            'logo' => '/images/logo.png',
        ],
    ],
]; 