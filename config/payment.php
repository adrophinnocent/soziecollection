<?php

return [
    'currency' => 'TZS',
    'currency_symbol' => 'TZS',
    'delivery_fee' => 5000,
    'free_delivery_threshold' => 100000,

    'whatsapp' => [
        'enabled' => env('PAYMENT_WHATSAPP_ENABLED', true),
        'phone_number' => env('PAYMENT_WHATSAPP_NUMBER', '255700000000'),
        'business_name' => env('PAYMENT_WHATSAPP_BUSINESS', 'Sozie Collection Atelier'),
    ],

    'mobile_money' => [
        'enabled' => env('PAYMENT_MOBILE_MONEY_ENABLED', true),
        'mpesa' => [
            'name' => 'Vodacom M-Pesa',
            'till_number' => env('PAYMENT_MPESA_TILL', '5544332'),
            'account_name' => 'SOZIE COLLECTION',
        ],
        'tigopesa' => [
            'name' => 'Tigo Pesa',
            'till_number' => env('PAYMENT_TIGOPESA_TILL', '8877661'),
            'account_name' => 'SOZIE COLLECTION',
        ],
        'airtel' => [
            'name' => 'Airtel Money',
            'till_number' => env('PAYMENT_AIRTEL_TILL', '9988776'),
            'account_name' => 'SOZIE COLLECTION',
        ],
    ],

    'bank_transfer' => [
        'enabled' => env('PAYMENT_BANK_TRANSFER_ENABLED', true),
        'crdb' => [
            'bank' => 'CRDB Bank',
            'account_number' => env('PAYMENT_CRDB_ACCOUNT', '0150234567800'),
            'account_name' => 'SOZIE COLLECTION LTD',
        ],
        'nmb' => [
            'bank' => 'NMB Bank',
            'account_number' => env('PAYMENT_NMB_ACCOUNT', '20110034567'),
            'account_name' => 'SOZIE COLLECTION LTD',
        ],
    ],

    'cash_on_delivery' => [
        'enabled' => env('PAYMENT_COD_ENABLED', true),
        'available_cities' => ['Dar es Salaam', 'Arusha', 'Dodoma', 'Mwanza', 'Zanzibar'],
    ],
];
