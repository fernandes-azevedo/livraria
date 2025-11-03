@extends('layout.app')

@section('content')

<div class="container col-xxl-8 px-4 py-5">
    <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
        <div class="col-10 col-sm-8 col-lg-6">
            
        </div>
        <div class="col-lg-6">
            <h1 class="display-5 fw-bold lh-1 mb-3">Lucas Fernandes</h1>
            <p class="display-6 fs-4">Desenvolvedor SR Especialista em PHP</p>
            <p class="lead">
                Este é um projeto-desafio para demonstrar a implementação de um sistema
                de livraria focado em arquitetura limpa, TDD e boas práticas
                de desenvolvimento com Laravel.
            </p>
            <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                <a href="#features" class="btn btn-primary btn-lg px-4 me-md-2">Ver Features</a>
                <a href="{{ route('livros.index') }}" class="btn btn-outline-secondary btn-lg px-4">Testar o CRUD</a>
            </div>
        </div>
    </div>
</div>

<div class="container px-4 py-5" id="features">
    <h2 class="pb-2 border-bottom text-center">Boas Práticas e Features Implementadas</h2>

    <div class="row row-cols-1 row-cols-md-2 align-items-md-center g-5 py-5">
        <div class="col d-flex flex-column align-items-start gap-2">
            <h3 class="fw-bold">Pontos de Destaque do Backend</h3>
            <p class="text-muted">
                O foco não foi apenas "fazer funcionar", mas "fazer da forma correta",
                pensando em manutenibilidade e performance.
            </p>
            <a href="https://github.com/fernandes-azevedo/livraria/blob/main/README.md" class="btn btn-primary" target="_blank">
                <i class="bi bi-github me-1"></i> Ver README no GitHub
            </a>
        </div>

        <div class="col">
            <div class="row row-cols-1 row-cols-sm-2 g-4">
                
                <div class="col d-flex flex-column gap-2">
                    <div class="feature-icon-small d-inline-flex align-items-center justify-content-center text-bg-primary bg-gradient fs-4 rounded-3">
                        <i class="bi bi-check-circle-fill" style="padding: .5rem;"></i>
                    </div>
                    <h4 class="fw-semibold mb-0">TDD (Diferencial)</h4>
                    <p class="text-muted">
                        O CRUD de Autores foi guiado por testes (TDD) usando PHPUnit.
                    </p>
                </div>

                <div class="col d-flex flex-column gap-2">
                    <div class="feature-icon-small d-inline-flex align-items-center justify-content-center text-bg-primary bg-gradient fs-4 rounded-3">
                        <i class="bi bi-database-gear" style="padding: .5rem;"></i>
                    </div>
                    <h4 class="fw-semibold mb-0">Schema Legado</h4>
                    <p class="text-muted">
                        Domínio do Eloquent para mapear um ERD existente (`CodAu`, `Livro_Autor`).
                    </p>
                </div>

                <div class="col d-flex flex-column gap-2">
                    <div class="feature-icon-small d-inline-flex align-items-center justify-content-center text-bg-primary bg-gradient fs-4 rounded-3">
                        <i class="bi bi-braces-asterisk" style="padding: .5rem;"></i>
                    </div>
                    <h4 class="fw-semibold mb-0">Separação de Responsabilidades</h4>
                    <p class="text-muted">
                        Uso de Form Requests para isolar a validação, mantendo os Controllers enxutos e aderentes ao SOLID (SRP).
                    </p>
                </div>

                <div class="col d-flex flex-column gap-2">
                    <div class="feature-icon-small d-inline-flex align-items-center justify-content-center text-bg-primary bg-gradient fs-4 rounded-3">
                        <i class="bi bi-bug-fill" style="padding: .5rem;"></i>
                    </div>
                    <h4 class="fw-semibold mb-0">Error Handling</h4>
                    <p class="text-muted">
                        Tratamento de `QueryException` (erros `UNIQUE`) e Transações de BD.
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>


<div class="container px-4 py-5 bg-light rounded-3">
    <h2 class="text-center mb-4">Explore o Sistema (CRUDs)</h2>
    <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
        
        <div class="col">
            <div class="card h-100 shadow-sm text-center">
                <div class="card-body d-flex flex-column">
                    <i class="bi bi-book-half display-3 text-primary"></i>
                    <h3 class="card-title mt-3">Livros</h3>
                    <p class="card-text flex-grow-1">
                        CRUD completo com relacionamentos N:N (Autores e Assuntos),
                        Eager Loading e Transações de Banco de Dados.
                    </p>
                    <a href="{{ route('livros.index') }}" class="btn btn-primary mt-auto">Gerenciar Livros</a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card h-100 shadow-sm text-center">
                <div class="card-body d-flex flex-column">
                    <i class="bi bi-people-fill display-3 text-primary"></i>
                    <h3 class="card-title mt-3">Autores</h3>
                    <p class="card-text flex-grow-1">
                        CRUD simples desenvolvido com TDD e tratamento
                        específico para erros de nome duplicado.
                    </p>
                    <a href="{{ route('autores.index') }}" class="btn btn-outline-secondary mt-auto">Gerenciar Autores</a>
                </div>
            </div>
        </div>

        <div class="col">
             <div class="card h-100 shadow-sm text-center">
                <div class="card-body d-flex flex-column">
                    <i class="bi bi-tags-fill display-3 text-primary"></i>
                    <h3 class="card-title mt-3">Assuntos</h3>
                    <p class="card-text flex-grow-1">
                        CRUD simples com proteção de integridade (impede
                        exclusão de itens em uso por um livro).
                    </p>
                    <a href="{{ route('assuntos.index') }}" class="btn btn-outline-secondary mt-auto">Gerenciar Assuntos</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection