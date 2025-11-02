<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use App\Http\Requests\StoreAutorRequest; 

class AutorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // "Buscando os dados. O Eloquent já sabe a tabela e a ordenação."
        $autores = Autor::orderBy('Nome')->get();
        return view('autores.index', ['autores' => $autores]);
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

        // Toda a complexidade do mapeamento (CodAu, Nome, tabela Autor) foi absorvida pelo Model. 
        Autor::create($request->validated());

        return redirect()->route('autores.index')
            ->with('success', 'Autor cadastrado com sucesso!');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Autor $autor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Autor $autor)
    {
        //
    }
}
