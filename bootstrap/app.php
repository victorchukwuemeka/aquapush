<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\GitHubAuthenticated;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\TrackVisits;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function(){
            Route::middleware(['web', 'auth', 'admin'])

            ->prefix('admin')

            ->name('admin.')

            ->group(base_path('routes/admin.php'));
        }
    )
   

    ->withMiddleware(function (Middleware $middleware) {
        //$middleware->append(GitHubAuthenticated::class);
        $middleware->append(TrackVisits::class);
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
