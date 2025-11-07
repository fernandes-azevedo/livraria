@extends('layout.app')

@section('content')

<div class="container col-xxl-8 px-4 py-5">
    <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
        <div class="col-10 col-sm-8 col-lg-6">
            <img src="https://placehold.co/700x500/0d6efd/ffffff.png?text=Livraria+Docker+API" 
                 class="d-block mx-lg-auto img-fluid rounded shadow-sm" 
                 alt="Diagrama do Projeto" width="700" height="500" loading="lazy">
        </div>
        <div class="col-lg-6">
            <h1 class="display-5 fw-bold lh-1 mb-3">Lucas Fernandes</h1>
            <p class="display-6 fs-4">Desenvolvedor SR Especialista em PHP</p>
            <p class="lead">
                Este é um projeto-desafio para demonstrar um ecossistema de aplicação completo,
                focado em Docker, APIs RESTful, Banco de Dados Avançado
                e boas práticas de arquitetura.
            </p>
            <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                <a href="#features" class="btn btn-primary btn-lg px-4 me-md-2">Ver Features</a>
                <a href="{{ route('livros.index') }}" class="btn btn-outline-secondary btn-lg px-4">Testar App Web</a>
            </div>
        </div>
    </div>
</div>

<div class="container px-4 py-5" id="features">
    <h2 class="pb-2 border-bottom text-center">Arquitetura e Boas Práticas</h2>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 py-5">
        
        <div class="col d-flex align-items-start">
            <div class="feature-icon-small d-inline-flex align-items-center justify-content-center text-bg-primary bg-gradient fs-4 rounded-3 me-3">
                <i class="bi bi-docker" style="padding: .5rem;"></i>
            </div>
            <div>
                <h4 class="fw-semibold mb-0">Ambiente Docker</h4>
                <p class="text-muted">
                    Projeto 100% containerizado (Nginx, PHP, MySQL, Redis)
                    com `start.sh` para setup automatizado.
                </p>
            </div>
        </div>

        <div class="col d-flex align-items-start">
            <div class="feature-icon-small d-inline-flex align-items-center justify-content-center text-bg-primary bg-gradient fs-4 rounded-3 me-3">
                <i class="bi bi-braces" style="padding: .5rem;"></i>
            </div>
            <div>
                <h4 class="fw-semibold mb-0">API RESTful</h4>
                <p class="text-muted">
                    API completa (`ApiResources`, `Form Requests` reutilizados)
                    com documentação e retornos padronizados.
                </p>
            </div>
        </div>

        <div class="col d-flex align-items-start">
            <div class="feature-icon-small d-inline-flex align-items-center justify-content-center text-bg-primary bg-gradient fs-4 rounded-3 me-3">
                <i class="bi bi-database-gear" style="padding: .5rem;"></i>
            </div>
            <div>
                <h4 class="fw-semibold mb-0">BD Avançado</h4>
                <p class="text-muted">
                    Uso de `Views` (relatório), `Triggers` (auditoria) e
                    `Procedures` (estatísticas) via Migrations.
                </p>
            </div>
        </div>

        <div class="col d-flex align-items-start">
            <div class="feature-icon-small d-inline-flex align-items-center justify-content-center text-bg-primary bg-gradient fs-4 rounded-3 me-3">
                <i class="bi bi-braces-asterisk" style="padding: .5rem;"></i>
            </div>
            <div>
                <h4 class="fw-semibold mb-0">SOLID (SRP)</h4>
                <p class="text-muted">
                    Validação isolada em `Form Requests`, mantendo
                    Controllers limpos e focados em orquestração.
                </p>
            </div>
        </div>

        <div class="col d-flex align-items-start">
            <div class="feature-icon-small d-inline-flex align-items-center justify-content-center text-bg-primary bg-gradient fs-4 rounded-3 me-3">
                <i class="bi bi-speedometer2" style="padding: .5rem;"></i>
            </div>
            <div>
                <h4 class="fw-semibold mb-0">Otimização</h4>
                <p class="text-muted">
                    Uso de `Redis` para Cache e Sessões, e `Scout`
                    para busca otimizada (em vez de `LIKE`).
                </p>
            </div>
        </div>

        <div class="col d-flex align-items-start">
            <div class="feature-icon-small d-inline-flex align-items-center justify-content-center text-bg-primary bg-gradient fs-4 rounded-3 me-3">
                <i class="bi bi-check-circle-fill" style="padding: .5rem;"></i>
            </div>
            <div>
                <h4 class="fw-semibold mb-0">Testes (TDD)</h4>
                <p class="text-muted">
                    Testes de Feature (PHPUnit) para a API,
                    garantindo a integridade dos endpoints.
                </p>
            </div>
        </div>
    </div>
</div>


<div class="container px-4 py-5 bg-light rounded-3">
    <h2 class="text-center mb-4">Explore os Módulos do Projeto</h2>
    <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
        
        <div class="col">
            <div class="card h-100 shadow-sm text-center">
                <div class="card-body d-flex flex-column">
                    <i class="bi bi-window-desktop display-3 text-primary"></i>
                    <h3 class="card-title mt-3">Aplicação Web (CRUDs)</h3>
                    <p class="card-text flex-grow-1">
                        Interface de gerenciamento (Blade) para Livros, Autores e Assuntos.
                        Inclui o Relatório em PDF.
                    </p>
                    <a href="{{ route('livros.index') }}" class="btn btn-primary mt-auto">Testar Aplicação Web</a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card h-100 shadow-sm text-center">
                <div class="card-body d-flex flex-column">
                    <i class="bi bi-terminal-fill display-3 text-primary"></i>
                    <h3 class="card-title mt-3">API RESTful</h3>
                    <p class="card-text flex-grow-1">
                        API completa com endpoints padronizados (JSON),
                        validação em PT-BR e tratamento de erros 404/422.
                    </p>
                    <!-- Link para o arquivo no GitHub (assumindo que o repo é público) -->
                    <a href="https://github.com/fernandes-azevedo/livraria/blob/main/API_DOCS.md" class="btn btn-outline-secondary mt-auto" target="_blank">Ver Docs da API</a>
                </div>
            </div>
        </div>

        <div class="col">
             <div class="card h-100 shadow-sm text-center">
                <div class="card-body d-flex flex-column">
                    <i class="bi bi-file-earmark-binary-fill display-3 text-primary"></i>
                    <h3 class="card-title mt-3">Relatório de Banco</h3>
                    <p class="card-text flex-grow-1">
                        Documentação técnica detalhando o mapeamento do ERD legado
                        e o uso de Views, Triggers e Procedures.
                    </p>
                    <!-- Link para o arquivo no GitHub -->
                    <a href="https://github.com/fernandes-azevedo/livraria/blob/main/DB_REPORT.md" class="btn btn-outline-secondary mt-auto" target="_blank">Ler Relatório do BD</a>
                </div>
            </div>
        </div>
        
    </div>
</div>

@endsection