<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // "Usei 'CREATE OR REPLACE VIEW' para tornar esta migração idempotente.
        // Isso garante que ela possa ser executada com segurança, mesmo que
        // a view já exista no banco, tornando o deploy mais robusto."
        DB::unprepared("
            CREATE OR REPLACE VIEW view_relatorio_livros_autores AS
            SELECT
                a.Nome AS autor_nome,
                l.Titulo AS livro_titulo,
                l.Editora AS livro_editora,
                l.AnoPublicacao AS livro_ano,
                l.Valor AS livro_valor,

                (SELECT GROUP_CONCAT(ass.Descricao SEPARATOR ', ') 
                 FROM Assunto ass
                 JOIN Livro_Assunto las ON ass.codAs = las.Assunto_codAs
                 WHERE las.Livro_CodI = l.CodI) AS assuntos

            FROM Autor a
            JOIN Livro_Autor la ON a.CodAu = la.Autor_CodAu
            JOIN Livro l ON la.Livro_CodI = l.CodI
            GROUP BY a.Nome, l.CodI, l.Titulo, l.Editora, l.AnoPublicacao, l.Valor
            ORDER BY a.Nome;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP VIEW IF EXISTS view_relatorio_livros_autores");
    }
};