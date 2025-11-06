<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AutorResource extends JsonResource
{
    /**
     * "O Resource mapeia o schema legado (CodAu, Nome) para
     * um JSON limpo e moderno (id, nome), desacoplando o
     * frontend da estrutura interna do banco."
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->CodAu,
            'nome' => $this->Nome,
        ];
    }
}
