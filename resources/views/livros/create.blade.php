@extends('layout.app')

@section('content')
    <h1>Novo Livro</h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('livros.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="Titulo" class="form-label">Título</label>
                            <input type="text" class="form-control @error('Titulo') is-invalid @enderror" id="Titulo" name="Titulo" value="{{ old('Titulo') }}">
                            @error('Titulo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="Editora" class="form-label">Editora</label>
                            <input type="text" class="form-control @error('Editora') is-invalid @enderror" id="Editora" name="Editora" value="{{ old('Editora') }}">
                            @error('Editora')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="Edicao" class="form-label">Edição</label>
                                    <input type="number" class="form-control @error('Edicao') is-invalid @enderror" id="Edicao" name="Edicao" value="{{ old('Edicao') }}">
                                    @error('Edicao')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="AnoPublicacao" class="form-label">Ano (AAAA)</label>
                                    <input type="text" class="form-control @error('AnoPublicacao') is-invalid @enderror" id="AnoPublicacao" name="AnoPublicacao" value="{{ old('AnoPublicacao') }}">
                                    @error('AnoPublicacao')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="Valor" class="form-label">Valor (R$)</label>
                                    <input type="text" class="form-control @error('Valor') is-invalid @enderror" id="Valor" name="Valor" value="{{ old('Valor') }}">
                                    @error('Valor')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="autores" class="form-label">Autores (Segure Ctrl/Cmd para selecionar vários)</label>
                            <select multiple class="form-select @error('autores') is-invalid @enderror" id="autores" name="autores[]" size="8">
                                @foreach($autores as $autor)
                                    <option value="{{ $autor->CodAu }}" 
                                        {{ (is_array(old('autores')) && in_array($autor->CodAu, old('autores'))) ? 'selected' : '' }}>
                                        {{ $autor->Nome }}
                                    </option>
                                @endforeach
                            </select>
                            @error('autores')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="assuntos" class="form-label">Assuntos (Segure Ctrl/Cmd para selecionar vários)</label>
                            <select multiple class="form-select @error('assuntos') is-invalid @enderror" id="assuntos" name="assuntos[]" size="8">
                                @foreach($assuntos as $assunto)
                                    <option value="{{ $assunto->codAs }}"
                                        {{ (is_array(old('assuntos')) && in_array($assunto->codAs, old('assuntos'))) ? 'selected' : '' }}>
                                        {{ $assunto->Descricao }}
                                    </option>
                                @endforeach
                            </select>
                             @error('assuntos')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="{{ route('livros.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@endsection