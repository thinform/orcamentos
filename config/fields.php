<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Campos do Sistema
    |--------------------------------------------------------------------------
    |
    | Aqui você pode configurar os campos do sistema.
    |
    */

    'users' => [
        'name' => [
            'type' => 'text',
            'label' => 'Nome',
            'placeholder' => 'Digite o nome',
            'required' => true,
        ],
        'email' => [
            'type' => 'email',
            'label' => 'Email',
            'placeholder' => 'Digite o email',
            'required' => true,
        ],
        'password' => [
            'type' => 'password',
            'label' => 'Senha',
            'placeholder' => 'Digite a senha',
            'required' => true,
        ],
        'password_confirmation' => [
            'type' => 'password',
            'label' => 'Confirmar Senha',
            'placeholder' => 'Digite a senha novamente',
            'required' => true,
        ],
    ],

    'clients' => [
        'name' => [
            'type' => 'text',
            'label' => 'Nome',
            'placeholder' => 'Digite o nome',
            'required' => true,
        ],
        'email' => [
            'type' => 'email',
            'label' => 'Email',
            'placeholder' => 'Digite o email',
            'required' => true,
        ],
        'phone' => [
            'type' => 'text',
            'label' => 'Telefone',
            'placeholder' => 'Digite o telefone',
            'required' => true,
        ],
        'document' => [
            'type' => 'text',
            'label' => 'CPF/CNPJ',
            'placeholder' => 'Digite o CPF/CNPJ',
            'required' => true,
        ],
        'address' => [
            'type' => 'text',
            'label' => 'Endereço',
            'placeholder' => 'Digite o endereço',
            'required' => true,
        ],
        'city' => [
            'type' => 'text',
            'label' => 'Cidade',
            'placeholder' => 'Digite a cidade',
            'required' => true,
        ],
        'state' => [
            'type' => 'text',
            'label' => 'Estado',
            'placeholder' => 'Digite o estado',
            'required' => true,
        ],
        'zip_code' => [
            'type' => 'text',
            'label' => 'CEP',
            'placeholder' => 'Digite o CEP',
            'required' => true,
        ],
    ],

    'categories' => [
        'name' => [
            'type' => 'text',
            'label' => 'Nome',
            'placeholder' => 'Digite o nome',
            'required' => true,
        ],
        'description' => [
            'type' => 'textarea',
            'label' => 'Descrição',
            'placeholder' => 'Digite a descrição',
            'required' => false,
        ],
    ],

    'products' => [
        'name' => [
            'type' => 'text',
            'label' => 'Nome',
            'placeholder' => 'Digite o nome',
            'required' => true,
        ],
        'description' => [
            'type' => 'textarea',
            'label' => 'Descrição',
            'placeholder' => 'Digite a descrição',
            'required' => false,
        ],
        'internal_code' => [
            'type' => 'text',
            'label' => 'Código Interno',
            'placeholder' => 'Digite o código interno',
            'required' => true,
        ],
        'cost_price' => [
            'type' => 'number',
            'label' => 'Preço de Custo',
            'placeholder' => 'Digite o preço de custo',
            'required' => true,
        ],
        'profit_margin' => [
            'type' => 'number',
            'label' => 'Margem de Lucro',
            'placeholder' => 'Digite a margem de lucro',
            'required' => true,
        ],
        'sale_price' => [
            'type' => 'number',
            'label' => 'Preço de Venda',
            'placeholder' => 'Digite o preço de venda',
            'required' => true,
        ],
        'unit' => [
            'type' => 'text',
            'label' => 'Unidade',
            'placeholder' => 'Digite a unidade',
            'required' => true,
        ],
        'image' => [
            'type' => 'file',
            'label' => 'Imagem',
            'placeholder' => 'Selecione a imagem',
            'required' => false,
        ],
        'category_id' => [
            'type' => 'select',
            'label' => 'Categoria',
            'placeholder' => 'Selecione a categoria',
            'required' => true,
            'options' => [],
        ],
    ],

    'quotes' => [
        'client_id' => [
            'type' => 'select',
            'label' => 'Cliente',
            'placeholder' => 'Selecione o cliente',
            'required' => true,
            'options' => [],
        ],
        'category_id' => [
            'type' => 'select',
            'label' => 'Categoria',
            'placeholder' => 'Selecione a categoria',
            'required' => true,
            'options' => [],
        ],
        'product_id' => [
            'type' => 'select',
            'label' => 'Produto',
            'placeholder' => 'Selecione o produto',
            'required' => true,
            'options' => [],
        ],
        'quantity' => [
            'type' => 'number',
            'label' => 'Quantidade',
            'placeholder' => 'Digite a quantidade',
            'required' => true,
        ],
        'unit_price' => [
            'type' => 'number',
            'label' => 'Preço Unitário',
            'placeholder' => 'Digite o preço unitário',
            'required' => true,
        ],
        'discount' => [
            'type' => 'number',
            'label' => 'Desconto',
            'placeholder' => 'Digite o desconto',
            'required' => false,
        ],
        'total' => [
            'type' => 'number',
            'label' => 'Total',
            'placeholder' => 'Digite o total',
            'required' => true,
        ],
        'validity' => [
            'type' => 'number',
            'label' => 'Validade',
            'placeholder' => 'Digite a validade',
            'required' => true,
        ],
        'payment_terms' => [
            'type' => 'select',
            'label' => 'Condições de Pagamento',
            'placeholder' => 'Selecione as condições de pagamento',
            'required' => true,
            'options' => [
                'À vista',
                '30 dias',
                '60 dias',
                '90 dias',
            ],
        ],
        'notes' => [
            'type' => 'textarea',
            'label' => 'Observações',
            'placeholder' => 'Digite as observações',
            'required' => false,
        ],
    ],
]; 