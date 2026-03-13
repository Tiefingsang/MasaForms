<?php
// app/Http/Middleware/VerifyCsrfToken.php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Les URIs qui doivent être exclus de la vérification CSRF.
     *
     * @var array
     */
    protected $except = [
        'webhooks/*', // Exclure les webhooks de la vérification CSRF
        'api/*',
    ];
}
