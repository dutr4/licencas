@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Licenças Cadastradas</h1>

    <a href="{{ route('licencas.create') }}" class="btn btn-primary mb-3">Nova Licença</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('licencas.index') }}" class="d-flex flex-wrap gap-3 align-items-end">

    <div style="min-width: 140px; flex-grow: 1;">
        <label for="filtro_codigo" class="form-label">Código</label>
        <input type="text" name="filtro_codigo" id="filtro_codigo" class="form-control" value="{{ request('filtro_codigo') }}">
    </div>

    <div style="min-width: 140px; flex-grow: 1;">
        <label for="filtro_empresa" class="form-label">Empresa</label>
        <select name="filtro_empresa" id="filtro_empresa" class="form-control">
            <option value="">Todas</option>
            @foreach ($empresas as $empresa)
                <option value="{{ $empresa->id }}" {{ request('filtro_empresa') == $empresa->id ? 'selected' : '' }}>
                    {{ $empresa->nome }}
                </option>
            @endforeach
        </select>
    </div>

    <div style="min-width: 140px; flex-grow: 1;">
        <label for="filtro_filial" class="form-label">Filial</label>
        <select name="filtro_filial" id="filtro_filial" class="form-control">
            <option value="">Todas</option>
            @foreach ($filiais as $filial)
                <option value="{{ $filial->id }}" {{ request('filtro_filial') == $filial->id ? 'selected' : '' }}>
                    {{ $filial->nome }}
                </option>
            @endforeach
        </select>
    </div>
    <div style="min-width: 140px; flex-grow: 1;">
        <label for="filtro_setor" class="form-label">Setor</label>
        <select name="filtro_setor" id="filtro_setor" class="form-control">
            <option value="">Todos</option>
            @foreach ($setores as $setor)
                <option value="{{ $setor->id }}" {{ request('filtro_setor') == $setor->id ? 'selected' : '' }}>
                    {{ $setor->nome }}
                </option>
            @endforeach
        </select>
    </div>

    <div style="min-width: 140px; flex-grow: 1;">
        <label for="filtro_nota_fiscal" class="form-label">Nota Fiscal</label>
        <input type="text" name="filtro_nota_fiscal" id="filtro_nota_fiscal" class="form-control" value="{{ request('filtro_nota_fiscal') }}">
    </div>

    <div style="min-width: 140px; flex-grow: 1;">
        <label for="filtro_item" class="form-label">Item</label>
        <input type="text" name="filtro_item" id="filtro_item" class="form-control" value="{{ request('filtro_item') }}">
    </div>

    <div style="min-width: 140px; flex-grow: 1;">
        <label for="filtro_chave" class="form-label">Chave</label>
        <input type="text" name="filtro_chave" id="filtro_chave" class="form-control" value="{{ request('filtro_chave') }}">
    </div>

    <div style="min-width: 140px; flex-grow: 1;">
        <label for="filtro_status" class="form-label">Status</label>
        <select name="filtro_status" id="filtro_status" class="form-control">
            <option value="">Todos</option>
            <option value="disponivel" {{ request('filtro_status') == 'disponivel' ? 'selected' : '' }}>Disponível</option>
            <option value="vinculada" {{ request('filtro_status') == 'vinculada' ? 'selected' : '' }}>Vinculada</option>
            <option value="expirada" {{ request('filtro_status') == 'expirada' ? 'selected' : '' }}>Expirada</option>
        </select>
    </div>

    <div class="d-flex gap-2 align-items-end">
        <button type="submit" class="btn btn-primary">Filtrar</button>
        <a href="{{ route('licencas.index') }}" class="btn btn-secondary ms-2">Limpar</a>
	<a href="{{ route('licencas.export.excel', request()->all()) }}" class="btn btn-success me-2">Exportar Excel</a>
	<a href="{{ route('licencas.export.pdf', request()->all()) }}" class="btn btn-danger">Exportar PDF</a>
    </div>

</form>

    <table class="table table-bordered">
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
        <th>Ações</th>
    </tr>
</thead>
<tbody>
    @foreach($licencas as $licenca)
        <tr>
            <td>{{ $licenca->codigo }}</td>
            <td>{{ $licenca->empresa->nome }}</td>
 	    <td>{{ $licenca->filial->nome ?? '-' }}</td>
            <td>{{ $licenca->setor->nome }}</td>
            <td>{{ $licenca->notaFiscalItem->notaFiscal->numero }}</td>
            <td>{{ $licenca->notaFiscalItem->descricao }}</td>
            <td>{{ $licenca->chave }}</td>
            <td>{{ $licenca->status }}</td>
            <td>
                <a href="{{ route('licencas.edit', $licenca) }}" class="btn btn-sm btn-warning">Editar</a>

                <form action="{{ route('licencas.destroy', $licenca) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Confirmar exclusão?')">Excluir</button>
                </form>
            </td>
        </tr>
    @endforeach
</tbody>
    </table>
</div>
<script>
document.getElementById('filtro_empresa').addEventListener('change', function() {
    const empresaId = this.value;

    if (!empresaId) {
        document.getElementById('filtro_filial').innerHTML = '<option value="">Todas</option>';
        document.getElementById('filtro_setor').innerHTML = '<option value="">Todos</option>';
        return;
    }

    fetch('/filtros/filiais?empresa_id=' + empresaId)
        .then(response => response.json())
        .then(data => {
            const filialSelect = document.getElementById('filtro_filial');
            filialSelect.innerHTML = '<option value="">Todas</option>';
            data.forEach(filial => {
                const selected = (filial.id == "{{ request('filtro_filial') }}") ? 'selected' : '';
                filialSelect.innerHTML += `<option value="${filial.id}" ${selected}>${filial.nome}</option>`;
            });

            // Reset Setor
            document.getElementById('filtro_setor').innerHTML = '<option value="">Todos</option>';
        });
});

document.getElementById('filtro_filial').addEventListener('change', function() {
    const filialId = this.value;

    if (!filialId) {
        document.getElementById('filtro_setor').innerHTML = '<option value="">Todos</option>';
        return;
    }

    fetch('/filtros/setores?filial_id=' + filialId)
        .then(response => response.json())
        .then(data => {
            const setorSelect = document.getElementById('filtro_setor');
            setorSelect.innerHTML = '<option value="">Todos</option>';
            data.forEach(setor => {
                const selected = (setor.id == "{{ request('filtro_setor') }}") ? 'selected' : '';
                setorSelect.innerHTML += `<option value="${setor.id}" ${selected}>${setor.nome}</option>`;
            });
        });
});
</script>

@endsection
