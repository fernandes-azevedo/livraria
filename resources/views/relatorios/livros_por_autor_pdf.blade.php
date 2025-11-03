<body>
    <h1>Relatório de Livros por Autor</h1>
    @foreach($relatorio as $autorNome => $livros)
        <h2>{{ $autorNome }}</h2>
        <table>
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Editora</th>
                    <th>Ano</th>
                    <th>Assuntos</th>
                    <th>Valor (R$)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($livros as $livro)
                    <tr>
                        <td>{{ $livro->livro_titulo }}</td>
                        <td>{{ $livro->livro_editora }}</td>
                        <td>{{ $livro->livro_ano }}</td>
                        <td>{{ $livro->assuntos ?? 'N/A' }}</td>
                        <td style="text-align: right;">
                            {{ number_format($livro->livro_valor, 2, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</body>