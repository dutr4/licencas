<table>
    <thead>
        <tr>
            <th>Hostname</th>
            <th>Colaborador</th>
            <th>Empresa</th>
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
                <td>{{ $recurso->setor->nome }}</td>
                <td>{{ $recurso->licenca?->codigo ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
