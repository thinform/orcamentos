<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configurações Gerais
    |--------------------------------------------------------------------------
    |
    | Aqui você pode configurar as configurações gerais do sistema.
    |
    */

    'pagination' => [
        'per_page' => 10,
    ],

    'date_format' => [
        'php' => 'd/m/Y',
        'js' => 'DD/MM/YYYY',
        'moment' => 'DD/MM/YYYY',
    ],

    'time_format' => [
        'php' => 'H:i',
        'js' => 'HH:mm',
        'moment' => 'HH:mm',
    ],

    'datetime_format' => [
        'php' => 'd/m/Y H:i',
        'js' => 'DD/MM/YYYY HH:mm',
        'moment' => 'DD/MM/YYYY HH:mm',
    ],

    'currency' => [
        'code' => 'BRL',
        'symbol' => 'R$',
        'decimal_separator' => ',',
        'thousand_separator' => '.',
        'decimals' => 2,
    ],

    'quote' => [
        'validity' => 30, // dias
        'payment_terms' => [
            'À vista',
            '30 dias',
            '60 dias',
            '90 dias',
        ],
        'status' => [
            'pending' => 'Pendente',
            'approved' => 'Aprovado',
            'rejected' => 'Rejeitado',
        ],
    ],
]; 