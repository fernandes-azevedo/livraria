@extends('layout.app')

@section('content')
    <h1>Editar Autor</h1>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('autores.update', $autor) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="Nome" class="form-label">Nome do Autor</label>
                    <input type="text"
                           class="form-control @error('Nome') is-invalid @enderror"
                           id="Nome"
                           name="Nome"
                           value="{{ old('Nome', $autor->Nome) }}">
                           
                    @error('Nome')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Atualizar</button>
                <a href="{{ route('autores.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@endsection