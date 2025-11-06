<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Autor;
use App\Http\Resources\AutorResource;
use App\Http\Requests\StoreAutorRequest;
use App\Http\Requests\UpdateAutorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\QueryException; // Importar

class AutorController extends Controller
{
    public function index(Request $request)
    {
        $busca = $request->input('busca');
        if ($busca) {
            $autores = Autor::search($busca)->paginate(15);
        } else {
            $autores = Autor::orderBy('Nome')->paginate(15);
        }

        return AutorResource::collection($autores);
    }

    public function store(StoreAutorRequest $request)
    {

        // "O store() agora trata QueryExceptions, como a violação
        // de 'UNIQUE' (Nome duplicado), que o Form Request pode
        // não pegar em uma 'race condition'."
        try {
            $autor = Autor::create($request->validated());
            Cache::forget('autores_list');

            // Padrão de Resposta: 201 com wrapper
            return response()->json([
                'message' => 'Autor cadastrado com sucesso!',
                'data' => new AutorResource($autor)
            ], 201);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            // 1062 (MySQL) ou 19 (SQLite) para UNIQUE constraint
            if ($errorCode == 1062 || str_contains($e->getMessage(), 'UNIQUE constraint failed')) {
                return response()->json([
                    'error' => 'Este autor (Nome) já está cadastrado.'
                ], 422); // 422 Unprocessable Entity
            }
            // Retorno de erro genérico
            return response()->json(['error' => 'Erro de banco de dados.'], 500);
        }
    }

    public function show(Autor $autor)
    {
        return new AutorResource($autor);
    }

    public function update(UpdateAutorRequest $request, Autor $autor)
    {
        try {
            $autor->update($request->validated());
            Cache::forget('autores_list');

            // Padrão de Resposta: 200 com wrapper
            return response()->json([
                'message' => 'Autor atualizado com sucesso!',
                'data' => new AutorResource($autor)
            ], 200);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062 || str_contains($e->getMessage(), 'UNIQUE constraint failed')) {
                return response()->json([
                    'error' => 'Este autor (Nome) já está cadastrado.'
                ], 422);
            }
            return response()->json(['error' => 'Erro de banco de dados.'], 500);
        }
    }

    public function destroy(Autor $autor)
    {
        // 409 Conflict (implícito) - está perfeito
        if ($autor->livros()->count() > 0) {
            return response()->json([
                'error' => 'Este autor não pode ser excluído, pois está associado a livros.'
            ], 409);
        }

        $autor->delete();
        Cache::forget('autores_list');

        // Padrão de Resposta: 200 com mensagem (em vez de 204)
        return response()->json(['message' => 'Autor removido com sucesso.'], 200);
    }
}
