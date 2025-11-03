<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssuntoRequest;
use App\Http\Requests\UpdateAssuntoRequest;
use App\Models\Assunto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AssuntoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $busca = $request->input('busca');

        if ($busca) {
            $assuntos = Assunto::search($busca)->paginate(15);
        } else {
            $assuntos = Assunto::orderBy('Descricao')->paginate(15);
        }

        return view('assuntos.index', compact('assuntos', 'busca'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('assuntos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAssuntoRequest $request)
    {
        Assunto::create($request->validated());

        // "Invalida o cache de assuntos."
        Cache::forget('assuntos.all');

        return redirect()->route('assuntos.index')
            ->with('success', 'Assunto cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Assunto $assunto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Assunto $assunto)
    {
        return view('assuntos.edit', compact('assunto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAssuntoRequest $request, Assunto $assunto)
    {
        $assunto->update($request->validated());

        // "Invalida o cache de assuntos."
        Cache::forget('assuntos.all');

        return redirect()->route('assuntos.index')
            ->with('success', 'Assunto atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assunto $assunto)
    {
     
        // "Mesma lógica de proteção do Autor: não excluir Assuntos em uso."
        if ($assunto->livros()->count() > 0) {
            return redirect()->route('assuntos.index')
                ->with('error', 'Este assunto não pode ser excluído, pois está associado a livros.');
        }

        $assunto->delete();

        // "Invalida o cache de assuntos."
        Cache::forget('assuntos.all');

        return redirect()->route('assuntos.index')
            ->with('success', 'Assunto excluído com sucesso!');
    }
}
