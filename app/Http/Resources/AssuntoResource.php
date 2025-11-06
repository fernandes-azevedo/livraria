<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssuntoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->codAs,
            'descricao' => $this->Descricao,
        ];
    }
}
