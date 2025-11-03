@extends('layout.app')

@section('content')
    <h1>Novo Assunto</h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('assuntos.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="Descricao" class="form-label">Descrição</label>
                    <input type="text"
                           class="form-control @error('Descricao') is-invalid @enderror"
                           id="Descricao"
                           name="Descricao"
                           value="{{ old('Descricao') }}">
                    
                    @error('Descricao')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="{{ route('assuntos.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@endsection