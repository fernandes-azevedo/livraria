@extends('layout.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Lista de Assuntos</h1>
        <a href="{{ route('assuntos.create') }}" class="btn btn-primary">Novo Assunto</a>
    </div>

    <form method="GET" action="{{ route('assuntos.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="busca" class="form-control" placeholder="Buscar por descrição..." value="{{ $busca ?? '' }}">
            <button class="btn btn-outline-secondary" type="submit">Buscar</button>
            @if(isset($busca))
                <a href="{{ route('assuntos.index') }}" class="btn btn-outline-danger">Limpar</a>
            @endif
        </div>
    </form>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th style="width: 100px;">ID (codAs)</th>
                        <th>Descrição</th>
                        <th style="width: 200px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assuntos as $assunto)
                        <tr>
                            <td>{{ $assunto->codAs }}</td>
                            <td>{{ $assunto->Descricao }}</td>
                            <td>
                                <a href="{{ route('assuntos.edit', $assunto) }}" class="btn btn-sm btn-warning">
                                    Editar
                                </a>
                                <form action="{{ route('assuntos.destroy', $assunto) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Nenhum assunto cadastrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-center">
                {{-- "Renderiza os links de paginação, mantendo o termo de busca na URL" --}}
                {{ $assuntos->appends(['busca' => $busca ?? ''])->links() }}
            </div>
        </div>
    </div>
@endsection