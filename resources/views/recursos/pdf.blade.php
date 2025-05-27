<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório de Recursos</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 4px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Relatório de Recursos</h2>

    <table>
        <thead>
            <tr>
                <th>Hostname</th>
                <th>Colaborador</th>
                <th>Empresa</th>
                <th>Filial</th>
                <th>Setor</th>
                <th>Código da Licença</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recursos as $recurso)
                <tr>
                    <td>{{ $recurso->hostname }}</td>
                    <td>{{ $recurso->colaborador }}</td>
                    <td>{{ $recurso->empresa->nome }}</td>
                    <td>{{ $recurso->filial->nome }}</td>
                    <td>{{ $recurso->setor->nome }}</td>
                    <td>{{ $recurso->licenca?->codigo ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
