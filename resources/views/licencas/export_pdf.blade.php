<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Licenças</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #666;
            padding: 6px 8px;
            text-align: left;
            vertical-align: middle;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #fafafa;
        }
    </style>
</head>
<body>

<h1>Relatório de Licenças</h1>

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
        @foreach($licencas as $licenca)
            <tr>
                <td>{{ $licenca->codigo }}</td>
                <td>{{ $licenca->empresa->nome ?? '-' }}</td>
                <td>{{ $licenca->filial->nome ?? '-' }}</td>
                <td>{{ $licenca->setor->nome ?? '-' }}</td>
                <td>{{ $licenca->notaFiscalItem->notaFiscal->numero ?? '-' }}</td>
                <td>{{ $licenca->notaFiscalItem->descricao ?? '-' }}</td>
                <td>{{ $licenca->chave }}</td>
                <td>{{ ucfirst($licenca->status) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
