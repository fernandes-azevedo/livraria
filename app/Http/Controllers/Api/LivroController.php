<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Livro;
use App\Http\Resources\LivroResource;
use App\Http\Requests\StoreLivroRequest;
use App\Http\Requests\UpdateLivroRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Exception;

class LivroController extends Controller
{
    public function index(Request $request)
    {
        // "O endpoint principal de listagem usa Eager Loading ('with')
        // e Scout para busca, garantindo performance e flexibilidade."
        $busca = $request->input('busca');
        
        // Eager Loading é crucial para os 'whenLoaded' no Resource
        $query = Livro::with('autores', 'assuntos');

        if ($busca) {
            $livros = $query->search($busca)->paginate(15);
        } else {
            $livros = $query->orderBy('Titulo')->paginate(15);
        }

        return LivroResource::collection($livros);
    }

    public function store(StoreLivroRequest $request)
    {
        // Reutilizei a 'DB::Transaction' garantindo a atomicidade da operação também na API.
        try {
            $livro = DB::transaction(function () use ($request) {
                $validatedData = $request->validated();
                $livro = Livro::create($validatedData);
                $livro->autores()->sync($request->input('autores', []));
                $livro->assuntos()->sync($request->input('assuntos', []));
                return $livro;
            });

            $livro->load('autores', 'assuntos');
            
            // Padrão de Resposta: 201 com wrapper
            return response()->json([
                'message' => 'Livro cadastrado com sucesso!',
                'data' => new LivroResource($livro)
            ], 201);

        } catch (QueryException $e) {
            // Código 1452: Foreign Key constraint violation (MySQL)
            // (ex: enviar um autor_id que não existe)
            if ($e->errorInfo[1] == 1452) {
                return response()->json([
                    'error' => 'Falha ao criar o livro. Um dos autores ou assuntos enviados é inválido.'
                ], 422); // 422 Unprocessable Entity
            }
            return response()->json([
                'error' => 'Falha ao criar o livro. Erro de banco de dados.'
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Falha ao criar o livro. ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Livro $livro)
    {
        // Carrega os relacionamentos para o Resource formatar
        $livro->load('autores', 'assuntos');
        return new LivroResource($livro);
    }

    public function update(UpdateLivroRequest $request, Livro $livro)
    {
        try {
            $livro = DB::transaction(function () use ($request, $livro) {
                $validatedData = $request->validated();
                $livro->update($validatedData);
                $livro->autores()->sync($request->input('autores', []));
                $livro->assuntos()->sync($request->input('assuntos', []));
                return $livro;
            });

            $livro->load('autores', 'assuntos');
            
            // Padrão de Resposta: 200 com wrapper
            return response()->json([
                'message' => 'Livro atualizado com sucesso!',
                'data' => new LivroResource($livro)
            ], 200);

        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1452) {
                return response()->json([
                    'error' => 'Falha ao atualizar o livro. Um dos autores ou assuntos enviados é inválido.'
                ], 422);
            }
            return response()->json([
                'error' => 'Falha ao atualizar o livro. Erro de banco de dados.'
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Falha ao atualizar o livro. ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Livro $livro)
    {
        $livro->delete();
        
        return response()->json(['message' => 'Livro removido com sucesso.'], 200);
    }
}