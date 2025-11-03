@extends('layout.app')

@section('content')
    <div class="container mt-5">
        <div class="p-5 mb-4 bg-light rounded-3 text-center">
            <div class="container-fluid py-5">
                <h1 class="display-5 fw-bold">Olá, eu sou o Desenvolvedor!</h1>
                <p class="fs-4 col-md-8 mx-auto">
                    Um desenvolvedor apaixonado por criar soluções elegantes e eficientes com Laravel.
                </p>
                <p class="text-muted">
                    Este é um projeto de demonstração para um sistema de gerenciamento de livraria.
                </p>
            </div>
        </div>

        <div class="row align-items-md-stretch">
            <div class="col-md-12">
                <div class="h-100 p-5 bg-light border rounded-3">
                    <h2>Sobre este Projeto</h2>
                    <p>
                        Este sistema foi construído para demonstrar minhas habilidades com o framework Laravel,
                        seguindo as melhores práticas de desenvolvimento, como o padrão RESTful,
                        validação de formulários (Form Requests), Eloquent ORM com relacionamentos,
                        e uma estrutura de views organizada com Blade.
                    </p>
                    <p>
                        Explore os módulos abaixo para ver o sistema em ação:
                    </p>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-start mt-4">
                        <a href="{{ route('livros.index') }}" class="btn btn-primary btn-lg px-4 me-md-2">Ver Livros</a>
                        <a href="{{ route('autores.index') }}" class="btn btn-outline-secondary btn-lg px-4">Gerenciar Autores</a>
                        <a href="{{ route('assuntos.index') }}" class="btn btn-outline-secondary btn-lg px-4">Gerenciar Assuntos</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection