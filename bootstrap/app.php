<?php

use App\Exceptions\AppLogicException;
use App\Http\Middleware\PermissionsMiddleware;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'permissions' => PermissionsMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AppLogicException $e, $request) {
            $statusCode = ($e->getCode() != 0) ? $e->getCode() : Response::HTTP_BAD_REQUEST;
            $message = $e->getMessage();
            if ($request->wantsJson()) {
                $responseData = response()->json([
                    'errors' => [
                        [
                            'status' => $statusCode,
                            'code' => $statusCode,
                            'title' => $message,
                        ],
                    ],
                ], $statusCode);
            } else {
                $responseData = null;
            }

            return $responseData;
        });
    })->withSchedule(function (Schedule $schedule) {
        $schedule->call('telescope:prune')->daily();
    })->create();
