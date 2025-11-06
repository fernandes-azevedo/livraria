<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Trigger usando DB::unprepared.
     * Esta Trigger é um 'grampo' direto no MySQL. A regra é:
     * 'AFTER INSERT ON Livro' (Após uma inserção na tabela Livro),
     * 'FOR EACH ROW' (para cada linha inserida),
     * execute o bloco que insere os detalhes na tabela 'audit_log'.
     * Isso é mais garantido do que fazê-lo na aplicação (via Eventos),
     * pois acontece no nível do banco, mesmo se o livro for inserido
     * por outro sistema.
     */
    public function up(): void
    {
        // "Para que a migração funcione com o 'RefreshDatabase'
        // dos testes, ela precisa ser 'idempotente' (re-executável).
        // Adicionei o 'DROP TRIGGER IF EXISTS' antes de criá-la.
        DB::unprepared('DROP TRIGGER IF EXISTS trg_livro_after_insert');

        DB::unprepared('
            CREATE TRIGGER trg_livro_after_insert
            AFTER INSERT ON Livro
            FOR EACH ROW
            BEGIN
                INSERT INTO audit_log (acao, detalhes, criado_em)
                VALUES (
                    "NOVO_LIVRO", 
                    CONCAT("Livro criado: ", NEW.Titulo, " (ID: ", NEW.CodI, ")"), 
                    NOW()
                );
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_livro_after_insert');
    }
};
