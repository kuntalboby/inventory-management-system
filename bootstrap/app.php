<?php

use App\Http\Middleware\BacktoDashboard;
use App\Http\Middleware\CustomerMiddleware;
use App\Http\Middleware\PreventBrowerBack;
use App\Http\Middleware\validAdmin;
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
            'precvenBack' => PreventBrowerBack::class,
            'validAdmin' => validAdmin::class,
            'ifLoggedIn'=>BacktoDashboard::class,
            'customer'=>CustomerMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
