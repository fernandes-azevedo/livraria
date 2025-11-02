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
        Schema::create('Autor', function (Blueprint $table) {
            // "Seguindo o ERD, a chave primária é 'CodAu' do tipo INTEGER.
            // Usei 'increments()' que cria um 'unsigned integer' auto-incrementável
            // e já o define como chave primária. É o equivalente a 'CodAu: INTEGER (PK)'."
            $table->increments('CodAu');

            // "O ERD pedia 'Nome: VARCHAR(40)'.
            // Adicionei 'unique' como uma melhoria de performance e integridade,
            // permitido pelo desafio, para evitar nomes duplicados."
            $table->string('Nome', 40)->unique();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Autor');
    }
};
