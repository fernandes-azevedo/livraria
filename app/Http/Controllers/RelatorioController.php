<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class RelatorioController extends Controller
{
    public function livrosPorAutor()
    {
        // "Consulta a VIEW criada"
        $dados = DB::table('view_relatorio_livros_autores')->get();

        // "Agrupa os dados pelo nome do autor para o PDF"
        $relatorio = $dados->groupBy('autor_nome');

        $pdf = Pdf::loadView('relatorios.livros_por_autor_pdf', compact('relatorio'));
        return $pdf->stream('relatorio_livros_por_autor.pdf');
    }
}