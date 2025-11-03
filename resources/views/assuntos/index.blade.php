@extends('layout.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Lista de Assuntos</h1>
        <a href="{{ route('assuntos.create') }}" class="btn btn-primary">Novo Assunto</a>
    </div>

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
        </div>
    </div>
@endsection