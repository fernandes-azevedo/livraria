<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ponto de apresentação:
        // "Tivemos que adaptar o SQL da VIEW para ser compatível com o SQLite.
        // A função 'GROUP_CONCAT' do SQLite não usa a palavra-chave 'SEPARATOR';
        // o separador é passado como o segundo argumento da função."
        DB::unprepared("
            CREATE VIEW view_relatorio_livros_autores AS
            SELECT
                a.Nome AS autor_nome,
                l.Titulo AS livro_titulo,
                l.Editora AS livro_editora,
                l.AnoPublicacao AS livro_ano,
                l.Valor AS livro_valor,
                
                -- AQUI ESTÁ A CORREÇÃO:
                (SELECT GROUP_CONCAT(ass.Descricao, ', ') 
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

    public function down(): void
    {
        DB::unprepared("DROP VIEW IF EXISTS view_relatorio_livros_autores");
    }
};