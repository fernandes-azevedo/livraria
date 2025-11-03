@extends('layout.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Lista de Autores</h1>
        <a href="{{ route('autores.create') }}" class="btn btn-primary">Novo Autor</a>
    </div>

    <form method="GET" action="{{ route('autores.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="busca" class="form-control" placeholder="Buscar por nome..." value="{{ $busca ?? '' }}">
            <button class="btn btn-outline-secondary" type="submit">Buscar</button>
             @if(isset($busca))
                <a href="{{ route('autores.index') }}" class="btn btn-outline-danger">Limpar</a>
            @endif
        </div>
    </form>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th style="width: 100px;">ID (CodAu)</th>
                        <th>Nome</th>
                        <th style="width: 200px;">Ações</th> </tr>
                </thead>
                <tbody>
                    @forelse($autores as $autor)
                        <tr>
                            <td>{{ $autor->CodAu }}</td>
                            <td>{{ $autor->Nome }}</td>
                            <td>
                                <a href="{{ route('autores.edit', $autor) }}" class="btn btn-sm btn-warning">
                                    Editar
                                </a>

                                <form action="{{ route('autores.destroy', $autor) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Nenhum autor cadastrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-center">
                {{-- "Renderiza os links de paginação, mantendo o termo de busca na URL" --}}
                {{ $autores->appends(['busca' => $busca ?? ''])->links() }}
            </div>
        </div>
    </div>
@endsection