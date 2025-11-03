@extends('layout.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Lista de Livros</h1>
        <a href="{{ route('livros.create') }}" class="btn btn-primary">Novo Livro</a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Autor(es)</th>
                        <th>Assunto(s)</th>
                        <th>Valor (R$)</th>
                        <th style="width: 200px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($livros as $livro)
                        <tr>
                            <td>
                                {{ $livro->Titulo }}
                                <small class="d-block text-muted">
                                    {{ $livro->Editora }}, {{ $livro->AnoPublicacao }}
                                </small>
                            </td>
                            <td>
                                @forelse($livro->autores as $autor)
                                    <span class="badge bg-secondary">{{ $autor->Nome }}</span>
                                @empty
                                    <small class="text-muted">N/A</small>
                                @endforelse
                            </td>
                            <td>
                                @forelse($livro->assuntos as $assunto)
                                    <span class="badge bg-info text-dark">{{ $assunto->Descricao }}</span>
                                @empty
                                    <small class="text-muted">N/A</small>
                                @endforelse
                            </td>
                            <td>
                                R$ {{ number_format($livro->Valor, 2, ',', '.') }}
                            </td>
                            <td>
                                <a href="{{ route('livros.edit', $livro->CodI) }}" class="btn btn-sm btn-warning">
                                    Editar
                                </a>
                                <form action="{{ route('livros.destroy', $livro->CodI) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Nenhum livro cadastrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection