<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Relatórios do Sistema
    |--------------------------------------------------------------------------
    |
    | Aqui você pode configurar os relatórios do sistema.
    |
    */

    'quotes' => [
        'daily' => [
            'title' => 'Orçamentos Diários',
            'description' => 'Relatório de orçamentos por dia',
            'filters' => [
                'date' => [
                    'type' => 'date',
                    'label' => 'Data',
                    'placeholder' => 'Digite a data',
                    'required' => true,
                ],
                'status' => [
                    'type' => 'select',
                    'label' => 'Status',
                    'placeholder' => 'Selecione o status',
                    'required' => false,
                    'options' => [
                        'pending' => 'Pendente',
                        'approved' => 'Aprovado',
                        'rejected' => 'Rejeitado',
                    ],
                ],
            ],
            'columns' => [
                'id' => [
                    'label' => '#',
                    'sortable' => true,
                ],
                'client' => [
                    'label' => 'Cliente',
                    'sortable' => true,
                ],
                'total' => [
                    'label' => 'Total',
                    'sortable' => true,
                ],
                'status' => [
                    'label' => 'Status',
                    'sortable' => true,
                ],
                'created_at' => [
                    'label' => 'Data de Criação',
                    'sortable' => true,
                ],
            ],
        ],
        'monthly' => [
            'title' => 'Orçamentos Mensais',
            'description' => 'Relatório de orçamentos por mês',
            'filters' => [
                'month' => [
                    'type' => 'month',
                    'label' => 'Mês',
                    'placeholder' => 'Digite o mês',
                    'required' => true,
                ],
                'status' => [
                    'type' => 'select',
                    'label' => 'Status',
                    'placeholder' => 'Selecione o status',
                    'required' => false,
                    'options' => [
                        'pending' => 'Pendente',
                        'approved' => 'Aprovado',
                        'rejected' => 'Rejeitado',
                    ],
                ],
            ],
            'columns' => [
                'id' => [
                    'label' => '#',
                    'sortable' => true,
                ],
                'client' => [
                    'label' => 'Cliente',
                    'sortable' => true,
                ],
                'total' => [
                    'label' => 'Total',
                    'sortable' => true,
                ],
                'status' => [
                    'label' => 'Status',
                    'sortable' => true,
                ],
                'created_at' => [
                    'label' => 'Data de Criação',
                    'sortable' => true,
                ],
            ],
        ],
        'yearly' => [
            'title' => 'Orçamentos Anuais',
            'description' => 'Relatório de orçamentos por ano',
            'filters' => [
                'year' => [
                    'type' => 'year',
                    'label' => 'Ano',
                    'placeholder' => 'Digite o ano',
                    'required' => true,
                ],
                'status' => [
                    'type' => 'select',
                    'label' => 'Status',
                    'placeholder' => 'Selecione o status',
                    'required' => false,
                    'options' => [
                        'pending' => 'Pendente',
                        'approved' => 'Aprovado',
                        'rejected' => 'Rejeitado',
                    ],
                ],
            ],
            'columns' => [
                'id' => [
                    'label' => '#',
                    'sortable' => true,
                ],
                'client' => [
                    'label' => 'Cliente',
                    'sortable' => true,
                ],
                'total' => [
                    'label' => 'Total',
                    'sortable' => true,
                ],
                'status' => [
                    'label' => 'Status',
                    'sortable' => true,
                ],
                'created_at' => [
                    'label' => 'Data de Criação',
                    'sortable' => true,
                ],
            ],
        ],
    ],

    'products' => [
        'most_quoted' => [
            'title' => 'Produtos Mais Orçados',
            'description' => 'Relatório de produtos mais orçados',
            'filters' => [
                'start_date' => [
                    'type' => 'date',
                    'label' => 'Data Inicial',
                    'placeholder' => 'Digite a data inicial',
                    'required' => true,
                ],
                'end_date' => [
                    'type' => 'date',
                    'label' => 'Data Final',
                    'placeholder' => 'Digite a data final',
                    'required' => true,
                ],
                'category_id' => [
                    'type' => 'select',
                    'label' => 'Categoria',
                    'placeholder' => 'Selecione a categoria',
                    'required' => false,
                    'options' => [],
                ],
            ],
            'columns' => [
                'id' => [
                    'label' => '#',
                    'sortable' => true,
                ],
                'name' => [
                    'label' => 'Nome',
                    'sortable' => true,
                ],
                'internal_code' => [
                    'label' => 'Código Interno',
                    'sortable' => true,
                ],
                'category' => [
                    'label' => 'Categoria',
                    'sortable' => true,
                ],
                'quotes_count' => [
                    'label' => 'Quantidade de Orçamentos',
                    'sortable' => true,
                ],
            ],
        ],
    ],

    'clients' => [
        'most_quoted' => [
            'title' => 'Clientes Mais Orçados',
            'description' => 'Relatório de clientes mais orçados',
            'filters' => [
                'start_date' => [
                    'type' => 'date',
                    'label' => 'Data Inicial',
                    'placeholder' => 'Digite a data inicial',
                    'required' => true,
                ],
                'end_date' => [
                    'type' => 'date',
                    'label' => 'Data Final',
                    'placeholder' => 'Digite a data final',
                    'required' => true,
                ],
            ],
            'columns' => [
                'id' => [
                    'label' => '#',
                    'sortable' => true,
                ],
                'name' => [
                    'label' => 'Nome',
                    'sortable' => true,
                ],
                'email' => [
                    'label' => 'Email',
                    'sortable' => true,
                ],
                'phone' => [
                    'label' => 'Telefone',
                    'sortable' => true,
                ],
                'quotes_count' => [
                    'label' => 'Quantidade de Orçamentos',
                    'sortable' => true,
                ],
            ],
        ],
    ],
]; 