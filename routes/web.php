<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutorController;
use App\Http\Controllers\AssuntoController;
use App\Http\Controllers\LivroController;

Route::get('/', function () {
    return view('welcome');
});

// Usei o padrão RESTful (/autores, /assuntos, /livros)
Route::resource('autores', AutorController::class)->parameters(['autores' => 'autor']);
Route::resource('assuntos', AssuntoController::class)->parameters(['assuntos' => 'assunto']);
Route::resource('livros', LivroController::class)->parameters(['livros' => 'livro']);
