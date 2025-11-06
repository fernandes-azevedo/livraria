<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutorFeatureTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_autor()
    {
        // "O teste segue o mesmo, mas agora testa os campos do ERD.
        // O payload envia 'Nome' (como no ERD) e espera
        // que ele seja salvo na tabela 'Autor'."
        $response = $this->post('/autores', ['Nome' => 'Ariano Suassuna']);

        $response->assertStatus(302);
        $response->assertRedirect('/autores');
        // "Verificando na tabela 'Autor' pelo campo 'Nome'"
        $this->assertDatabaseHas('Autor', ['Nome' => 'Ariano Suassuna']);
    }

    /** @test */
    public function it_validates_a_required_name_for_autor()
    {
        // "Testando a validação do campo 'Nome'"
        $response = $this->post('/autores', ['Nome' => '']);
        $response->assertSessionHasErrors('Nome');
    }
}
