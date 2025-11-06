<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Para atender ao requisito de Triggers. 
     * O objetivo é rastrear eventos importantes do sistema, começando pela criação de livros.
     * Isso é uma prática para garantir a rastreabilidade.
     */
    public function up(): void
    {
        Schema::create('audit_log', function (Blueprint $table) {
            $table->id();
            $table->string('acao', 255);
            $table->text('detalhes')->nullable();
            $table->string('ip_origem', 45)->nullable(); // IP de quem fez a ação
            $table->foreignId('user_id')->nullable(); // Futura referência ao usuário logado
            $table->timestamp('criado_em')->useCurrent(); // Data e hora do evento
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_log');
    }
};