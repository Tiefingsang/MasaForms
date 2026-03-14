<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuration de l'API PayDunya
    |--------------------------------------------------------------------------
    |
    | Obtenez vos clés sur https://paydunya.com/dashboard/settings/api
    |
    */

    'master_key' => env('PAYDUNYA_MASTER_KEY'),
    'public_key' => env('PAYDUNYA_PUBLIC_KEY'),
    'private_key' => env('PAYDUNYA_PRIVATE_KEY'),
    'token' => env('PAYDUNYA_TOKEN'),
    'mode' => env('PAYDUNYA_MODE', 'test'), // 'test' ou 'live'

    /*
    |--------------------------------------------------------------------------
    | Configuration de votre boutique
    |--------------------------------------------------------------------------
    |
    | Ces informations apparaîtront sur la page de paiement
    |
    */

    'store' => [
        'name' => env('APP_NAME', 'MasaForm'),
        'tagline' => env('PAYDUNYA_STORE_TAGLINE', 'Formulaires en ligne simples et puissants'),
        'phone' => env('PAYDUNYA_STORE_PHONE'),
        'logo_url' => env('PAYDUNYA_LOGO_URL'),
        // IMPORTANT: On utilise une variable d'environnement ici, PAS route()
        'callback_url' => env('PAYDUNYA_CALLBACK_URL'),
    ],
];
