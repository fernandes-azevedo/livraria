<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Para o requisito da Procedure, criei a 'sp_GetDashboardStats'.
     * O objetivo é centralizar consultas de agregação (como as
     * estatísticas de um dashboard) no próprio banco.
     * Isso otimiza a performance, pois a aplicação só precisa fazer
     * uma chamada (CALL sp_GetDashboardStats()) em vez de 3 queries
     * separadas, reduzindo o 'network round-trip' e movendo a lógica
     * para o SGBD, que é otimizado para isso."
     */
    public function up(): void
    {
        // Adicionei o 'DROP'
        // no início do método 'up()' para garantir que os
        // testes (que rodam 'migrate:fresh' repetidamente)
        // nunca falhem.
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_GetDashboardStats');

        DB::unprepared('
            CREATE PROCEDURE sp_GetDashboardStats()
            BEGIN
                SELECT 
                    (SELECT COUNT(*) FROM Livro) AS total_livros,
                    (SELECT COUNT(*) FROM Autor) AS total_autores,
                    (SELECT COUNT(*) FROM Assunto) AS total_assuntos,
                    (SELECT AVG(Valor) FROM Livro) AS preco_medio_livros;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_GetDashboardStats');
    }
};
