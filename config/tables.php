<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Tabelas do Sistema
    |--------------------------------------------------------------------------
    |
    | Aqui você pode configurar as tabelas do sistema.
    |
    */

    'users' => [
        'columns' => [
            'id' => [
                'label' => '#',
                'sortable' => true,
                'searchable' => false,
            ],
            'name' => [
                'label' => 'Nome',
                'sortable' => true,
                'searchable' => true,
            ],
            'email' => [
                'label' => 'Email',
                'sortable' => true,
                'searchable' => true,
            ],
            'created_at' => [
                'label' => 'Data de Criação',
                'sortable' => true,
                'searchable' => false,
            ],
            'actions' => [
                'label' => 'Ações',
                'sortable' => false,
                'searchable' => false,
            ],
        ],
    ],

    'clients' => [
        'columns' => [
            'id' => [
                'label' => '#',
                'sortable' => true,
                'searchable' => false,
            ],
            'name' => [
                'label' => 'Nome',
                'sortable' => true,
                'searchable' => true,
            ],
            'email' => [
                'label' => 'Email',
                'sortable' => true,
                'searchable' => true,
            ],
            'phone' => [
                'label' => 'Telefone',
                'sortable' => true,
                'searchable' => true,
            ],
            'document' => [
                'label' => 'CPF/CNPJ',
                'sortable' => true,
                'searchable' => true,
            ],
            'created_at' => [
                'label' => 'Data de Criação',
                'sortable' => true,
                'searchable' => false,
            ],
            'actions' => [
                'label' => 'Ações',
                'sortable' => false,
                'searchable' => false,
            ],
        ],
    ],

    'categories' => [
        'columns' => [
            'id' => [
                'label' => '#',
                'sortable' => true,
                'searchable' => false,
            ],
            'name' => [
                'label' => 'Nome',
                'sortable' => true,
                'searchable' => true,
            ],
            'description' => [
                'label' => 'Descrição',
                'sortable' => true,
                'searchable' => true,
            ],
            'created_at' => [
                'label' => 'Data de Criação',
                'sortable' => true,
                'searchable' => false,
            ],
            'actions' => [
                'label' => 'Ações',
                'sortable' => false,
                'searchable' => false,
            ],
        ],
    ],

    'products' => [
        'columns' => [
            'id' => [
                'label' => '#',
                'sortable' => true,
                'searchable' => false,
            ],
            'name' => [
                'label' => 'Nome',
                'sortable' => true,
                'searchable' => true,
            ],
            'internal_code' => [
                'label' => 'Código Interno',
                'sortable' => true,
                'searchable' => true,
            ],
            'cost_price' => [
                'label' => 'Preço de Custo',
                'sortable' => true,
                'searchable' => false,
            ],
            'profit_margin' => [
                'label' => 'Margem de Lucro',
                'sortable' => true,
                'searchable' => false,
            ],
            'sale_price' => [
                'label' => 'Preço de Venda',
                'sortable' => true,
                'searchable' => false,
            ],
            'unit' => [
                'label' => 'Unidade',
                'sortable' => true,
                'searchable' => true,
            ],
            'category' => [
                'label' => 'Categoria',
                'sortable' => true,
                'searchable' => true,
            ],
            'created_at' => [
                'label' => 'Data de Criação',
                'sortable' => true,
                'searchable' => false,
            ],
            'actions' => [
                'label' => 'Ações',
                'sortable' => false,
                'searchable' => false,
            ],
        ],
    ],

    'quotes' => [
        'columns' => [
            'id' => [
                'label' => '#',
                'sortable' => true,
                'searchable' => false,
            ],
            'client' => [
                'label' => 'Cliente',
                'sortable' => true,
                'searchable' => true,
            ],
            'total' => [
                'label' => 'Total',
                'sortable' => true,
                'searchable' => false,
            ],
            'validity' => [
                'label' => 'Validade',
                'sortable' => true,
                'searchable' => false,
            ],
            'payment_terms' => [
                'label' => 'Condições de Pagamento',
                'sortable' => true,
                'searchable' => true,
            ],
            'status' => [
                'label' => 'Status',
                'sortable' => true,
                'searchable' => true,
            ],
            'created_at' => [
                'label' => 'Data de Criação',
                'sortable' => true,
                'searchable' => false,
            ],
            'actions' => [
                'label' => 'Ações',
                'sortable' => false,
                'searchable' => false,
            ],
        ],
    ],
]; 