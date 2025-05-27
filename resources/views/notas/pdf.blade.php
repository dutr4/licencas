<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Notas Fiscais</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        h2 { margin-top: 40px; }
    </style>
</head>
<body>
    <h1>Notas Fiscais</h1>

    @foreach($notas as $nota)
        <h2>Nota: {{ $nota->numero }}</h2>
        <p><strong>Empresa:</strong> {{ $nota->empresa->nome ?? '-' }}</p>
        <p><strong>Filial:</strong> {{ $nota->filial->nome ?? '-' }}</p>
        <p><strong>Data de Emissão:</strong> {{ $nota->data_emissao }}</p>

        @if($nota->itens->isEmpty())
            <p>Sem itens cadastrados.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Quantidade</th>
                        <th>Descrição</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($nota->itens as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->quantidade ?? '-' }}</td>
                            <td>{{ $item->descricao ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endforeach

</body>
</html>
