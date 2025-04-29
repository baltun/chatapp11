<?php

use App\Exceptions\AppLogicException;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AppLogicException $e, $request) {
            if ($request->wantsJson()) {
                $responseData = response()->json([
                    'errors' => [
                        [
                            'status' => (string) $e->getCode(),
                            'code' => $e->getCode(),
                            'title' => $e->getMessage(),
                        ],
                    ],
                ], $e->getCode());
            } else {
                $responseData = null;
            }

            return $responseData;
        });
    })->withSchedule(function (Schedule $schedule) {
        $schedule->call('telescope:prune')->daily();
    })->create();
