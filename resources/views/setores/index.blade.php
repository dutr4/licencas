@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Setores</h1>
    @stack('js')
    <a href="{{ route('setores.create') }}" class="btn btn-primary mb-3">Novo Setor</a>

    <form method="GET" action="{{ route('setores.index') }}" class="row mb-3">
        <div class="col-md-4">
            <label>Empresa</label>
            <select name="empresa_id" id="empresa_id" class="form-control">
                <option value="">Todas</option>
                @foreach ($empresas as $empresa)
                    <option value="{{ $empresa->id }}" {{ request('empresa_id') == $empresa->id ? 'selected' : '' }}>
                        {{ $empresa->nome }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label>Filial</label>
            <select name="filial_id" id="filial_id" class="form-control">
                <option value="">Todas</option>
                @foreach ($filiais as $filial)
                    <option value="{{ $filial->id }}" {{ request('filial_id') == $filial->id ? 'selected' : '' }}>
                        {{ $filial->nome }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2">Filtrar</button>
            <a href="{{ route('setores.index') }}" class="btn btn-secondary">Limpar</a>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Empresa</th>
                <th>Filial</th>
                <th>Setor</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($setores as $setor)
                <tr>
                    <td>{{ $setor->empresa->nome }}</td>
		    <td>{{ $setor->filial->nome ?? '-' }}</td>
                    <td>{{ $setor->nome }}</td>
                    <td>
                        <a href="{{ route('setores.edit', $setor) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('setores.destroy', $setor) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('js')
<script>
document.getElementById('empresa_id').addEventListener('change', function () {
    let empresaId = this.value;
    let filialSelect = document.getElementById('filial_id');

    filialSelect.innerHTML = '<option value="">Carregando...</option>';

    fetch(`/empresas/${empresaId}/filiais`)
        .then(response => response.json())
        .then(data => {
            filialSelect.innerHTML = '<option value="">Todas</option>';
            data.forEach(f => {
                let opt = document.createElement('option');
                opt.value = f.id;
                opt.textContent = f.nome;
                filialSelect.appendChild(opt);
            });
        });
});
</script>
@endpush
