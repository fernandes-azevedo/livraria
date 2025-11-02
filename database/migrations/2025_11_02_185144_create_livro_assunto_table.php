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
        Schema::create('Livro_Assunto', function (Blueprint $table) {
            // "'Livro_CodI' referenciando 'Livro(CodI)'"
            $table->integer('Livro_CodI')->unsigned();
            $table->foreign('Livro_CodI')
                ->references('CodI')->on('Livro')
                ->onDelete('cascade');

            // "'Assunto_codAs' referenciando 'Assunto(codAs)'"
            $table->integer('Assunto_codAs')->unsigned();
            $table->foreign('Assunto_codAs')
                ->references('codAs')->on('Assunto')
                ->onDelete('cascade');

            // "Chave primária composta (Rel_03, Rel_04)"
            $table->primary(['Livro_CodI', 'Assunto_codAs']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Livro_Assunto');
    }
};
