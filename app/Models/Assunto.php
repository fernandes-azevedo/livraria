<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Assunto extends Model
{
    use HasFactory, Searchable;

    protected $table = 'Assunto';

    protected $primaryKey = 'codAs';
    
    // Sem timestamps seguindo o ERD
    public $timestamps = false;

    protected $fillable = ['Descricao'];

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        return [
            'Descricao' => $this->Descricao,
        ];
    }

    // Mapeamento N:N similar ao Autor, especificando todos os nomes
    // da tabela pivot 'Livro_Assunto' e suas chaves.
    public function livros()
    {
        return $this->belongsToMany(
            Livro::class,
            'Livro_Assunto',
            'Assunto_codAs',
            'Livro_CodI'
        );
    }
}
