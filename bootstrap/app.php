<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckSubscription;
use App\Http\Middleware\CheckFormLimit;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'check.subscription' => CheckSubscription::class,
            'check.form.limit' => CheckFormLimit::class,
            'check.permission' => CheckPermission::class,
            // Autres middlewares personnalisés

        ]);

        $middleware->web(append: [
            // Middleware à ajouter au groupe web
        ]);

        $middleware->api(append: [
            // Middleware à ajouter au groupe api
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
