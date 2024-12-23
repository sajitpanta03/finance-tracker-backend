<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function ($exceptions) {
        $exceptions->render(function (Exception $exception) {
            $status = 500;
            $errorMessage = 'Something went wrong';

            if ($exception instanceof ModelNotFoundException) {
                $status = 404;
                $errorMessage = 'Resource not found';
            } elseif ($exception instanceof HttpException) {
                $status = $exception->getStatusCode();
                $errorMessage = 'HTTP Error';
            } elseif ($exception instanceof ValidationException) {
                $status = $exception->getStatusCode();
                $errorMessage = 'Validation Exception';
            } elseif ($exception instanceof AuthorizationException) {
                $status = 403;
                $errorMessage = 'Unauthorized';
            }

            $message = $exception->getMessage() ?: $errorMessage;

            return response()->json([
                'error' => $errorMessage,
                'message' => $message,
            ], $status);
        });
    })->create();
