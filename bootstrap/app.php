<?php

use App\Http\Middleware\CheckAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->alias([
            'check-admin' => CheckAdmin::class
        ]);
        $middleware->redirectTo(
            // agar user alrdy login hai aur access kar raha hau login anf regi page ko so redirect it tousers
            guests:'account/login',
            users:'account/profile'
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
