<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // Para garantir que qualquer erro 404 (seja uma rota
        // inválida ou um 'Model Not Found') retorne um JSON
        // limpo e padronizado, eu interceptei a exceção
        // 'NotFoundHttpException' globalmente.
        $exceptions->renderable(function (NotFoundHttpException $e, Request $request) {

            // "Se a requisição for para a API..."
            if ($request->is('api/*')) {

                // Ponto de apresentação:
                // "Eu verifico a 'causa raiz' da exceção. Se foi
                // um ModelNotFound, eu retorno uma mensagem específica.
                // Se não, retorno um 'Endpoint não encontrado' genérico."
                $previousException = $e->getPrevious();

                if ($previousException instanceof ModelNotFoundException) {
                    $modelName = class_basename($previousException->getModel());
                    return response()->json([
                        'error' => "$modelName não encontrado."
                    ], 404);
                }

                // Se não foi um ModelNotFound, foi uma rota 404 comum.
                return response()->json([
                    'error' => 'Endpoint não encontrado.'
                ], 404);
            }
        });
    })->create();
