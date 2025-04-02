<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Filtros do Sistema
    |--------------------------------------------------------------------------
    |
    | Aqui você pode configurar os filtros do sistema.
    |
    */

    'users' => [
        'name' => [
            'type' => 'text',
            'label' => 'Nome',
            'placeholder' => 'Digite o nome',
        ],
        'email' => [
            'type' => 'text',
            'label' => 'Email',
            'placeholder' => 'Digite o email',
        ],
    ],

    'clients' => [
        'name' => [
            'type' => 'text',
            'label' => 'Nome',
            'placeholder' => 'Digite o nome',
        ],
        'email' => [
            'type' => 'text',
            'label' => 'Email',
            'placeholder' => 'Digite o email',
        ],
        'phone' => [
            'type' => 'text',
            'label' => 'Telefone',
            'placeholder' => 'Digite o telefone',
        ],
        'document' => [
            'type' => 'text',
            'label' => 'CPF/CNPJ',
            'placeholder' => 'Digite o CPF/CNPJ',
        ],
    ],

    'categories' => [
        'name' => [
            'type' => 'text',
            'label' => 'Nome',
            'placeholder' => 'Digite o nome',
        ],
    ],

    'products' => [
        'name' => [
            'type' => 'text',
            'label' => 'Nome',
            'placeholder' => 'Digite o nome',
        ],
        'internal_code' => [
            'type' => 'text',
            'label' => 'Código Interno',
            'placeholder' => 'Digite o código interno',
        ],
        'category_id' => [
            'type' => 'select',
            'label' => 'Categoria',
            'placeholder' => 'Selecione a categoria',
            'options' => [],
        ],
    ],

    'quotes' => [
        'client_id' => [
            'type' => 'select',
            'label' => 'Cliente',
            'placeholder' => 'Selecione o cliente',
            'options' => [],
        ],
        'status' => [
            'type' => 'select',
            'label' => 'Status',
            'placeholder' => 'Selecione o status',
            'options' => [
                'pending' => 'Pendente',
                'approved' => 'Aprovado',
                'rejected' => 'Rejeitado',
            ],
        ],
        'created_at' => [
            'type' => 'date',
            'label' => 'Data',
            'placeholder' => 'Digite a data',
        ],
    ],
]; 