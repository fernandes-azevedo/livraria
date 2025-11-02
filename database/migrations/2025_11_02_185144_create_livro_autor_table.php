<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('Livro_Autor', function (Blueprint $table) {
            // "Aqui definimos as chaves estrangeiras exatamente como no ERD.
            // 'Livro_CodI' é um 'unsigned integer' que referencia 'CodI' na tabela 'Livro'."
            $table->integer('Livro_CodI')->unsigned();
            $table->foreign('Livro_CodI')
                ->references('CodI')->on('Livro')
                ->onDelete('cascade');

            // "'Autor_CodAu' é um 'unsigned integer' que referencia 'CodAu' na tabela 'Autor'."
            $table->integer('Autor_CodAu')->unsigned();
            $table->foreign('Autor_CodAu')
                ->references('CodAu')->on('Autor')
                ->onDelete('cascade');

            // "Definindo a chave primária composta (Rel_01, Rel_02) para performance e integridade."
            $table->primary(['Livro_CodI', 'Autor_CodAu']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Livro_Autor');
    }
};
