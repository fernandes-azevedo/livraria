<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assunto;
use App\Http\Resources\AssuntoResource;
use App\Http\Requests\StoreAssuntoRequest;
use App\Http\Requests\UpdateAssuntoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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
        $assunto = Assunto::create($request->validated());
        Cache::forget('assuntos_list');
        return (new AssuntoResource($assunto))->response()->setStatusCode(201);
    }

    public function show(Assunto $assunto)
    {
        return new AssuntoResource($assunto);
    }

    public function update(UpdateAssuntoRequest $request, Assunto $assunto)
    {
        $assunto->update($request->validated());
        Cache::forget('assuntos_list');
        
        return new AssuntoResource($assunto);
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
        return response()->noContent();
    }
}