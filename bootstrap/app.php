<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
        
        $middleware->alias([
            'auth.token' => \App\Http\Middleware\AuthToken::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->report(function (\Throwable $e) {
            error_log('[EXCEPTION] ' . get_class($e) . ': ' . $e->getMessage());
            error_log('[TRACE] ' . $e->getTraceAsString());
            
            // Extra logging for database errors
            if ($e instanceof QueryException || $e instanceof \PDOException) {
                error_log('[DATABASE ERROR] SQL State: ' . ($e->getCode() ?? 'N/A'));
                error_log('[DATABASE ERROR] Message: ' . $e->getMessage());
            }
        });
    })->create();
    
