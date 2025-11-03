<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Autor;
use App\Models\Assunto;
use App\Models\Livro;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $autores = Autor::factory(50)->create();
        $assuntos = Assunto::factory(50)->create();

        Livro::factory(50)->create()->each(function ($livro) use ($autores, $assuntos) {
            $livro->autores()->attach(
                $autores->random(rand(1, 3))->pluck('CodAu')->toArray()
            );
            $livro->assuntos()->attach(
                $assuntos->random(rand(1, 3))->pluck('codAs')->toArray()
            );
        });
    }
}
