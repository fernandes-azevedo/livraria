@extends('layout.app')

@section('content')
    <h1>Editar Livro: {{ $livro->Titulo }}</h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('livros.update', $livro) }}" method="POST" id="edit-form">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="Titulo" class="form-label"><b>Título *</b></label>
                            <input type="text" class="form-control @error('Titulo') is-invalid @enderror" id="Titulo" name="Titulo" value="{{ old('Titulo', $livro->Titulo) }}">
                            @error('Titulo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="Editora" class="form-label"><b>Editora *</b></label>
                            <input type="text" class="form-control @error('Editora') is-invalid @enderror" id="Editora" name="Editora" value="{{ old('Editora', $livro->Editora) }}">
                            @error('Editora')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="Edicao" class="form-label"><b>Edição *</b></label>
                                    <input type="number" class="form-control @error('Edicao') is-invalid @enderror" id="Edicao" name="Edicao" value="{{ old('Edicao', $livro->Edicao) }}">
                                    @error('Edicao')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="AnoPublicacao" class="form-label"><b>Ano (AAAA) *</b></label>
                                    <input type="text" class="form-control @error('AnoPublicacao') is-invalid @enderror" id="AnoPublicacao" name="AnoPublicacao" value="{{ old('AnoPublicacao', $livro->AnoPublicacao) }}">
                                    @error('AnoPublicacao')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="Valor" class="form-label"><b>Valor (R$) *</b></label>
                                    <input type="text" class="form-control @error('Valor') is-invalid @enderror" id="Valor" name="Valor" value="{{ old('Valor', $livro->Valor) }}">
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
                                        @if(is_array(old('autores')) ? in_array($autor->CodAu, old('autores')) : in_array($autor->CodAu, $autoresSelecionados)) selected @endif>
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
                                        @if(is_array(old('assuntos')) ? in_array($assunto->codAs, old('assuntos')) : in_array($assunto->codAs, $assuntosSelecionados)) selected @endif>
                                        {{ $assunto->Descricao }}
                                    </option>
                                @endforeach
                            </select>
                             @error('assuntos')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary">Atualizar</button>
                <a href="{{ route('livros.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>

    <script>
        const valorInput = document.getElementById('Valor');
        const form = document.getElementById('edit-form');

        valorInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value) {
                value = (parseInt(value, 10) / 100).toLocaleString('pt-BR', {
                    style: 'currency',
                    currency: 'BRL'
                });
            }
            e.target.value = value;
        });

        form.addEventListener('submit', function(e) {
            let value = valorInput.value.replace(/\D/g, '');
            valorInput.value = value / 100;
        });

        // Trigger input event to format the initial value
        valorInput.dispatchEvent(new Event('input'));
    </script>
@endsection