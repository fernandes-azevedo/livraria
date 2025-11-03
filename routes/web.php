<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutorController;
use App\Http\Controllers\AssuntoController;
use App\Http\Controllers\LivroController;
use App\Http\Controllers\RelatorioController;

Route::get('/', function () {
    return view('home');
});

// Usei o padrão RESTful (/autores, /assuntos, /livros)
Route::resource('autores', AutorController::class)->parameters(['autores' => 'autor']);
Route::resource('assuntos', AssuntoController::class)->parameters(['assuntos' => 'assunto']);
Route::resource('livros', LivroController::class)->parameters(['livros' => 'livro']);
Route::get('/relatorio/livros-por-autor', [RelatorioController::class, 'livrosPorAutor'])->name('relatorio.livros');
