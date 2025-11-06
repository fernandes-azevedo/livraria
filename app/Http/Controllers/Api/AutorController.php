<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Autor;
use App\Http\Resources\AutorResource;

use App\Http\Requests\StoreAutorRequest;
use App\Http\Requests\UpdateAutorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AutorController extends Controller
{
    public function index(Request $request)
    {
        // Reutilizando a lógica de busca do Scout
        $busca = $request->input('busca');
        if ($busca) {
            $autores = Autor::search($busca)->paginate(15);
        } else {
            $autores = Autor::orderBy('Nome')->paginate(15);
        }
        
        // Retorna uma coleção formatada pelo Resource
        return AutorResource::collection($autores);
    }

    public function store(StoreAutorRequest $request)
    {
        $autor = Autor::create($request->validated());
        Cache::forget('autores_list'); // Limpa o cache
        
        // Retorna o novo objeto e um status 201 (Created)
        return (new AutorResource($autor))->response()->setStatusCode(201);
    }

    public function show(Autor $autor)
    {
        return new AutorResource($autor);
    }

    public function update(UpdateAutorRequest $request, Autor $autor)
    {
        $autor->update($request->validated());
        Cache::forget('autores_list'); // Limpa o cache

        return new AutorResource($autor);
    }

    public function destroy(Autor $autor)
    {
        if ($autor->livros()->count() > 0) {
            return response()->json([
                'error' => 'Este autor não pode ser excluído, pois está associado a livros.'
            ], 409); // 409 Conflict
        }

        $autor->delete();
        Cache::forget('autores_list'); // Limpa o cache

        // Retorna uma resposta vazia com status 204 (No Content)
        return response()->noContent();
    }
}