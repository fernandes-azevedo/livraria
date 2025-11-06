<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LivroResource extends JsonResource
{
    /**
     * "O Resource do Livro é mais complexo. Ele não apenas mapeia
     * os campos, mas também lida com os relacionamentos.
     * Usei 'whenLoaded' para incluir autores e assuntos
     * somente se eles foram pré-carregados (via 'with()') no
     * controller, evitando o problema de N+1 queries na API."
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->CodI,
            'titulo' => $this->Titulo,
            'editora' => $this->Editora,
            'edicao' => $this->Edicao,
            'ano_publicacao' => $this->AnoPublicacao,
            'valor' => (float) $this->Valor, // Garante que seja um número, não string

            // Relacionamentos (só aparecem se carregados)
            'autores' => AutorResource::collection($this->whenLoaded('autores')),
            'assuntos' => AssuntoResource::collection($this->whenLoaded('assuntos')),
        ];
    }
}
