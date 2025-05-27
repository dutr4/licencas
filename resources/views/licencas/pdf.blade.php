<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Licenças</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; font-size: 12px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Licenças</h2>
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Empresa</th>
                <th>Filial</th>
                <th>Setor</th>
                <th>Nota Fiscal</th>
                <th>Item</th>
                <th>Chave</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($licencas as $licenca)
                <tr>
                    <td>{{ $licenca->codigo }}</td>
                    <td>{{ $licenca->empresa->nome ?? '-' }}</td>
                    <td>{{ $licenca->filial->nome ?? '-' }}</td>
                    <td>{{ $licenca->setor->nome ?? '-' }}</td>
                    <td>{{ $licenca->notaFiscalItem->notaFiscal->numero ?? '-' }}</td>
                    <td>{{ $licenca->notaFiscalItem->descricao ?? '-' }}</td>
                    <td>{{ $licenca->chave }}</td>
                    <td>{{ $licenca->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
