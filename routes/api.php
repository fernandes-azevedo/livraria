<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AutorController;
use App\Http\Controllers\Api\AssuntoController;
use App\Http\Controllers\Api\LivroController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Para a API, usei 'Route::apiResource'. 
// Ele é o padrão RESTful e cria automaticamente os 5 endpoints principais
// (index, store, show, update, destroy) para cada entidade, já otimizado para JSON."
Route::apiResource('autores', AutorController::class)->parameters(['autores' => 'autor'])->names('api.');
Route::apiResource('assuntos', AssuntoController::class)->parameters(['assuntos' => 'assunto'])->names('api.');
Route::apiResource('livros', LivroController::class)->parameters(['livros' => 'livro'])->names('api.');
