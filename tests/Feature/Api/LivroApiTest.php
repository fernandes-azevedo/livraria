<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\Livro;
use App\Models\Autor;
use App\Models\Assunto;

class LivroApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test] // 2. Usar o atributo (remove o warning)
    public function it_can_list_books_via_api()
    {

        // "Testando o endpoint GET /api/livros. Crio 3 livros,
        // chamo a API e verifico se o status é 200 e se
        // a 'data' (da paginação) contém 3 itens."
        Livro::factory(3)->create();

        $response = $this->getJson('/api/livros');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }

    #[Test]
    public function it_returns_validation_error_in_portuguese_via_api()
    {

        // "Testando o requisito de idioma. Eu envio um POST
        // inválido (Titulo vazio) e verifico se o status é 422
        // e se a mensagem de erro está em português."
        $response = $this->postJson('/api/livros', ['Titulo' => '']);

        $response->assertStatus(422) // Erro de validação
                 ->assertJsonFragment(['O campo Título é obrigatório.']);
    }

    #[Test]
    public function it_can_create_a_book_with_relations()
    {

        // "Testando o endpoint POST /api/livros.
        // Crio um Autor e Assunto, envio os IDs deles
        // na criação do Livro, e verifico o status 201."
        $autor = Autor::factory()->create();
        $assunto = Assunto::factory()->create();

        $livroData = [
            'Titulo' => 'Um Livro de Teste',
            'Editora' => 'Editora Teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2024',
            'Valor' => 123.45,
            'autores' => [$autor->CodAu], // Usando a PK correta
            'assuntos' => [$assunto->codAs] // Usando a PK correta
        ];
        
        $response = $this->postJson('/api/livros', $livroData);

        // "Verifico se o retorno JSON está correto e se
        // as tabelas (Livro e a pivot Livro_Autor)
        // foram preenchidas corretamente no banco."
        $response->assertStatus(201)
                 ->assertJsonPath('data.titulo', 'Um Livro de Teste')
                 ->assertJsonPath('data.autores.0.id', $autor->CodAu);
        
        $this->assertDatabaseHas('Livro', ['Titulo' => 'Um Livro de Teste']);
        $this->assertDatabaseHas('Livro_Autor', [
            'Livro_CodI' => $response->json('data.id'),
            'Autor_CodAu' => $autor->CodAu
        ]);
    }

    #[Test]
    public function it_can_show_a_book()
    {
        $livro = Livro::factory()->create();

        $response = $this->getJson("/api/livros/{$livro->CodI}");

        $response->assertStatus(200)
                 ->assertJsonPath('data.id', $livro->CodI)
                 ->assertJsonPath('data.titulo', $livro->Titulo);
    }

    #[Test]
    public function it_can_update_a_book()
    {
        $livro = Livro::factory()->create();
        
        $updateData = [
            'Titulo' => 'Titulo Atualizado',
            'Editora' => $livro->Editora,
            'Edicao' => $livro->Edicao,
            'AnoPublicacao' => $livro->AnoPublicacao,
            'Valor' => 200.50,
        ];

        $response = $this->putJson("/api/livros/{$livro->CodI}", $updateData);

        $response->assertStatus(200)
                 ->assertJsonPath('data.titulo', 'Titulo Atualizado')
                 ->assertJsonPath('data.valor', 200.50);

        $this->assertDatabaseHas('Livro', ['CodI' => $livro->CodI, 'Titulo' => 'Titulo Atualizado']);
    }

    #[Test]
    public function it_can_delete_a_book()
    {
        $livro = Livro::factory()->create();

        $response = $this->deleteJson("/api/livros/{$livro->CodI}");

        // "Verificando se o DELETE retorna a mensagem de sucesso
        // e o status 200 (como definimos no controller)."
        $response->assertStatus(200)
                 ->assertJson(['message' => 'Livro removido com sucesso.']);
        
        $this->assertDatabaseMissing('Livro', ['CodI' => $livro->CodI]);
    }

    #[Test]
    public function it_returns_404_for_missing_book()
    {

        // "Testando o requisito de erro 404. Verifico se ao
        // buscar um ID inválido, a API retorna o JSON de erro
        // padronizado que configuramos no bootstrap/app.php."
        $response = $this->getJson('/api/livros/999');

        $response->assertStatus(404)
                 ->assertJson([
                     'error' => 'Livro não encontrado.'
                 ]);
    }
}