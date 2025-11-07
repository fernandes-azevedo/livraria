<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use App\Http\Requests\StoreAutorRequest;
use App\Http\Requests\UpdateAutorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\QueryException;

class AutorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $busca = $request->input('busca');

        if ($busca) {
            $autores = Autor::search($busca)->paginate(15);
        } else {
            // Troquei o get() por paginate() para paginar os resultados.
            $autores = Autor::orderBy('Nome')->paginate(15);
        }

        return view('autores.index', compact('autores', 'busca'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('autores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAutorRequest $request)
    {

        try {
            // Toda a complexidade do mapeamento (CodAu, Nome, tabela Autor) foi absorvida pelo Model. 
            Autor::create($request->validated());

            // "Invalida o cache de autores para que a lista seja atualizada no próximo acesso."
            Cache::forget('autores_list');

            return redirect()->route('autores.index')
                ->with('success', 'Autor cadastrado com sucesso!');
                
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062 || str_contains($e->getMessage(), 'UNIQUE constraint failed')) {
                return back()->withInput()->with('error', 'Erro: Este autor (Nome) já está cadastrado.');
            }
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Autor $autor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Autor $autor)
    {
        return view('autores.edit', compact('autor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAutorRequest $request, Autor $autor)
    {
        $autor->update($request->validated());

        // "Invalida o cache de autores."
        Cache::forget('autores_list');

        return redirect()->route('autores.index')
            ->with('success', 'Autor atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Autor $autor)
    {
        if ($autor->livros()->count() > 0) {
            return redirect()->route('autores.index')
                ->with('error', 'Este autor não pode ser excluído, pois está associado a livros.');
        }

        $autor->delete();

        // "Invalida o cache de autores."
        Cache::forget('autores_list');
        return redirect()->route('autores.index')
            ->with('success', 'Autor excluído com sucesso!');
    }
}
