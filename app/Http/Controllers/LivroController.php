<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use App\Models\Autor;
use App\Models\Assunto;
use App\Http\Requests\StoreLivroRequest;
use App\Http\Requests\UpdateLivroRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class LivroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $busca = $request->input('busca');

        // Lógica de busca e paginação implementada.
        // Se um termo de busca for fornecido, usa o Scout para a pesquisa.
        // Caso contrário, exibe a lista paginada padrão.
        if ($busca) {
            // O Eager Loading com 'with()' continua funcionando com o Scout.
            $livros = Livro::search($busca)->query(function ($query) {
                // Para usar Eager Loading com Scout, o método 'with()'
                // deve ser aplicado dentro de uma closure no método 'query()'.
                $query->with('autores', 'assuntos');
            })->paginate(15);
        } else {
            $livros = Livro::with('autores', 'assuntos')->orderBy('Titulo')->paginate(15);
        }

        return view('livros.index', compact('livros', 'busca'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Carrega autores e assuntos para preencher os <select> do formulário
        $autores = Cache::remember('autores_list', now()->addHour(), function () {
            return Autor::orderBy('Nome')->get();
        });
        $assuntos = Cache::remember('assuntos_list', now()->addHour(), function () {
            return Assunto::orderBy('Descricao')->get();
        });
        return view('livros.create', compact('autores', 'assuntos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLivroRequest $request)
    {
        // O cadastro de livro é uma operação crítica que mexe em 3 tabelas
        // (Livro, Livro_Autor, Livro_Assunto). Para garantir a integridade,
        // envolvi toda a operação em uma 'DB Transaction'. Ou tudo
        // funciona, ou nada é salvo.
        DB::beginTransaction();
        try {
            $validatedData = $request->validated();

            // 1. Criar o Livro
            $livro = Livro::create($validatedData);

            // 2. Sincronizar os relacionamentos N:N
            // O 'sync()' cuida de adicionar os IDs na tabela pivot.
            $livro->autores()->sync($request->input('autores', []));
            $livro->assuntos()->sync($request->input('assuntos', []));

            // Se tudo deu certo, "comita" a transação
            DB::commit();

            return redirect()->route('livros.index')
                ->with('success', 'Livro cadastrado com sucesso!');
        } catch (\Exception $e) {
            // Se algo deu errado, "reverte" a transação
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Erro ao cadastrar o livro: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Livro $livro)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Livro $livro)
    {
        // Carrega o livro (já injetado), e também todos os autores
        // e assuntos para popular os <select>.
        $autores = Cache::remember('autores_list', now()->addHour(), function () {
            return Autor::orderBy('Nome')->get();
        });
        $assuntos = Cache::remember('assuntos_list', now()->addHour(), function () {
            return Assunto::orderBy('Descricao')->get();
        });

        // Para a view de edição, preciso pré-selecionar os autores
        // e assuntos que já estão ligados ao livro.
        // O método 'pluck()' do Eloquent é perfeito para isso:
        // ele extrai apenas a coluna 'CodAu' da coleção de autores
        // do livro, gerando um array de IDs [1, 5, 10].
        $autoresSelecionados = $livro->autores->pluck('CodAu')->toArray();
        $assuntosSelecionados = $livro->assuntos->pluck('codAs')->toArray();

        return view('livros.edit', compact(
            'livro',
            'autores',
            'assuntos',
            'autoresSelecionados',
            'assuntosSelecionados'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLivroRequest $request, Livro $livro)
    {
        // "Usei a mesma lógica de Transação do 'store'
        // para garantir a integridade na atualização."
        DB::beginTransaction();
        try {
            $validatedData = $request->validated();

            // 1. Atualizar o Livro
            $livro->update($validatedData);

            // 2. Sincronizar os relacionamentos N:N
            // "O 'sync()' é poderoso: ele remove os IDs que não
            // vieram no request e adiciona os novos."
            $livro->autores()->sync($request->input('autores', []));
            $livro->assuntos()->sync($request->input('assuntos', []));

            DB::commit();

            return redirect()->route('livros.index')
                ->with('success', 'Livro atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Erro ao atualizar o livro: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Livro $livro)
    {
        // "Como usei 'onDelete('cascade')' nas migrations das
        // tabelas pivot (Livro_Autor, Livro_Assunto), ao excluir
        // um Livro, o Laravel/Banco de Dados automaticamente
        // exclui as referências nessas tabelas pivot.
        // O controller fica limpo."
        $livro->delete();
        
        return redirect()->route('livros.index')
                         ->with('success', 'Livro excluído com sucesso!');
    }
}
