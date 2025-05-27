<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório de Logs</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Relatório de Logs do Sistema</h2>
    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Usuário</th>
                <th>Ação</th>
                <th>Módulo</th>
                <th>Detalhes</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $log)
                <tr>
                    <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $log->usuario->name ?? 'Desconhecido' }}</td>
                    <td>{{ ucfirst($log->acao) }}</td>
                    <td>{{ $log->modulo }}</td>
                    <td>{{ $log->detalhes }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
