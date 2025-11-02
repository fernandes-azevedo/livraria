<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Autor extends Model
{
    use HasFactory;

    // Como não seguir a convenção do Laravel, precisei instruir o Eloquent
    // sobre como este Model funciona.

    // 1. O nome da tabela é 'Autor', e não 'autors'.
    protected $table = 'Autor';

    // 2. A chave primária é 'CodAu', e não 'id'.
    protected $primaryKey = 'CodAu';

    // 3. A chave primária não é auto-incrementável (embora seja, 
    //    definir como 'integer' ajuda em alguns casos, mas 'increments' 
    //    na migration cuida disso. O mais importante é...)
    // public $incrementing = true; // (Isso já é o padrão)

    // 4. Este model NÃO usa os campos 'created_at' e 'updated_at'.
    public $timestamps = false;

    // 5. Proteção de Mass Assignment para o campo 'Nome'.
    protected $fillable = ['Nome'];

    // Para o relacionamento N:N, precisei especificar todos os nomes customizados:
    // 1º: Model relacionado (Livro::class)
    // 2º: Nome da tabela pivot ('Livro_Autor')
    // 3º: Chave estrangeira DESTE model na pivot ('Autor_CodAu')
    // 4º: Chave estrangeira DO OUTRO model na pivot ('Livro_CodI')
    public function livros()
    {
        return $this->belongsToMany(
            Livro::class,
            'Livro_Autor',
            'Autor_CodAu',
            'Livro_CodI'
        );
    }
}
