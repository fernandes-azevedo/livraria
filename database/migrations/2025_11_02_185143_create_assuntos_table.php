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
        Schema::create('Assunto', function (Blueprint $table) {
            // "Chave primária 'codAs: INTEGER (PK)'"
            $table->increments('codAs');

            // "'Descricao: VARCHAR(20)'"
            $table->string('Descricao', 20)->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Assunto');
    }
};
