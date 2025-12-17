<?php

require __DIR__.'/ensure_sqlite.php';

use App\Console\Commands\ServeWithSqliteCommand;
use App\Http\Middleware\AdminAuthenticated;
use App\Http\Middleware\CustomerAuthenticated;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withCommands([
        App\Console\Commands\ServeWithSqliteCommand::class,
    ])
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'customer.auth' => CustomerAuthenticated::class,
            'admin.auth' => AdminAuthenticated::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
