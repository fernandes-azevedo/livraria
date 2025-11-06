<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assunto;
use App\Http\Resources\AssuntoResource;
use App\Http\Requests\StoreAssuntoRequest;
use App\Http\Requests\UpdateAssuntoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\QueryException;

class AssuntoController extends Controller
{
    public function index(Request $request)
    {
        $busca = $request->input('busca');
        if ($busca) {
            $assuntos = Assunto::search($busca)->paginate(15);
        } else {
            $assuntos = Assunto::orderBy('Descricao')->paginate(15);
        }
        return AssuntoResource::collection($assuntos);
    }

    public function store(StoreAssuntoRequest $request)
    {
        try {
            $assunto = Assunto::create($request->validated());
            Cache::forget('assuntos_list');

            // Padrão de Resposta: 201 com wrapper
            return response()->json([
                'message' => 'Assunto cadastrado com sucesso!',
                'data' => new AssuntoResource($assunto)
            ], 201);
            
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062 || str_contains($e->getMessage(), 'UNIQUE constraint failed')) {
                return response()->json([
                    'error' => 'Esta descrição de assunto já está cadastrada.'
                ], 422);
            }
            return response()->json(['error' => 'Erro de banco de dados.'], 500);
        }
    }

    public function show(Assunto $assunto)
    {
        return new AssuntoResource($assunto);
    }

    public function update(UpdateAssuntoRequest $request, Assunto $assunto)
    {
        try {
            $assunto->update($request->validated());
            Cache::forget('assuntos_list');
            
            // Padrão de Resposta: 200 com wrapper
            return response()->json([
                'message' => 'Assunto atualizado com sucesso!',
                'data' => new AssuntoResource($assunto)
            ], 200);

        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062 || str_contains($e->getMessage(), 'UNIQUE constraint failed')) {
                return response()->json([
                    'error' => 'Esta descrição de assunto já está cadastrada.'
                ], 422);
            }
            return response()->json(['error' => 'Erro de banco de dados.'], 500);
        }
    }

    public function destroy(Assunto $assunto)
    {
        if ($assunto->livros()->count() > 0) {
            return response()->json([
                'error' => 'Este assunto não pode ser excluído, pois está associado a livros.'
            ], 409);
        }
        $assunto->delete();
        Cache::forget('assuntos_list');
        
        // Padrão de Resposta: 200 com mensagem
        return response()->json(['message' => 'Assunto removido com sucesso.'], 200);
    }
}