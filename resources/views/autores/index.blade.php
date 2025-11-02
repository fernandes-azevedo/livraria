@extends('layout.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Lista de Autores</h1>
        
        <a href="{{ route('autores.create') }}" class="btn btn-primary">Novo Autor</a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th style="width: 100px;">ID (CodAu)</th>
                        <th>Nome</th>
                        <th style="width: 150px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($autores as $autor)
                        <tr>
                            <td>{{ $autor->CodAu }}</td>
                            <td>{{ $autor->Nome }}</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-warning">Editar</a>
                                <a href="#" class="btn btn-sm btn-danger">Excluir</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Nenhum autor cadastrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection