<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Desafio Cadastro de Livros</title>

    @vite(['resources/sass/app.scss'])
</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">Livraria</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('autores.index') }}">Autores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('assuntos.index') }}">Assuntos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('livros.index') }}">Livros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('relatorio.livros') }}" target="_blank">Relatório</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </main>

    @vite(['resources/js/app.js'])
</body>
</html>