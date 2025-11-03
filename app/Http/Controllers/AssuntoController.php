<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssuntoRequest;
use App\Http\Requests\UpdateAssuntoRequest;
use App\Models\Assunto;

class AssuntoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assuntos = Assunto::orderBy('Descricao')->get();
        return view('assuntos.index', compact('assuntos'));
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
        return redirect()->route('assuntos.index')
            ->with('success', 'Assunto excluído com sucesso!');
    }
}
