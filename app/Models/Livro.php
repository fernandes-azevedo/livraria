<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Livro extends Model
{
    use HasFactory;

    protected $table = 'Livro';

    protected $primaryKey = 'CodI';
    // Sem timestamps
    public $timestamps = false;

    // Incluindo o campo 'Valor' (R$)
    protected $fillable = [
        'Titulo',
        'Editora',
        'Edicao',
        'AnoPublicacao',
        'Valor',
    ];

    // Relacionamento N:N com Autor (mapeamento inverso)
    public function autores()
    {
        return $this->belongsToMany(
            Autor::class,
            'Livro_Autor',
            'Livro_CodI',
            'Autor_CodAu'
        );
    }

    // Relacionamento N:N com Assunto (mapeamento inverso)
    public function assuntos()
    {
        return $this->belongsToMany(
            Assunto::class,
            'Livro_Assunto',
            'Livro_CodI',
            'Assunto_codAs'
        );
    }
}
