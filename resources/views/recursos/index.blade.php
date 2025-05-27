@extends('layouts.app')

@section('content')
<div class="content">
    <div class="container-fluid">
    <h1>Recursos Cadastrados</h1>

    <a href="{{ route('recursos.create') }}" class="btn btn-primary mb-3">Novo Recurso</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('recursos.index') }}" class="d-flex flex-wrap mb-3 style="gap: 10px;">
    <div style="min-width: 200px; flex: 1;">
        <input type="text" name="hostname" class="form-control" placeholder="Hostname" value="{{ request('hostname') }}">
    </div>
    <div style="min-width: 200px; flex: 1;">
        <input type="text" name="colaborador" class="form-control" placeholder="Colaborador" value="{{ request('colaborador') }}">
    </div>
    <div style="min-width: 200px; flex: 1;">
        <select name="empresa_id" id="filtro_empresa" class="form-control">
            <option value="">Empresa</option>
            @foreach($empresas as $empresa)
                <option value="{{ $empresa->id }}" {{ request('empresa_id') == $empresa->id ? 'selected' : '' }}>
                    {{ $empresa->nome }}
                </option>
            @endforeach
        </select>
    </div>
    <div style="min-width: 200px; flex: 1;">
	<select name="filial_id" id="filtro_filial" class="form-control" data-selected="{{ is_numeric(request('filial_id')) ? request('filial_id') : '' }}">
	    <option value="">Filial</option>
	    @foreach($filiais as $filial)
	        <option value="{{ $filial->id }}" {{ request('filial_id') == $filial->id ? 'selected' : '' }}>
	            {{ $filial->nome }}
	        </option>
	    @endforeach
	</select>
    </div>
    <div style="min-width: 200px; flex: 1;">
	<select name="setor_id" id="filtro_setor" class="form-control" data-selected="{{ is_numeric(request('setor_id')) ? request('setor_id') : '' }}">
	    <option value="">Setor</option>
	    @foreach($setores as $setor)
	        <option value="{{ $setor->id }}" {{ request('setor_id') == $setor->id ? 'selected' : '' }}>
	            {{ $setor->nome }}
	        </option>
	    @endforeach
	</select>
    </div>
    <div style="min-width: 200px; flex: 1;">
        <input type="text" name="licenca" class="form-control" placeholder="Código da Licença" value="{{ request('licenca') }}">
    </div>

    <div class="col-md-12 mt-2 text-end">
        <button type="submit" class="btn btn-primary">Filtrar</button>
        <a href="{{ route('recursos.index') }}" class="btn btn-secondary">Limpar</a>

	@php
	    $filters = request()->only(['hostname', 'colaborador', 'empresa_id', 'filial_id', 'setor_id', 'licenca']);
	    // Remover valores inválidos (como "Setor")
	    $filters = array_filter($filters, function($value) {
	        return $value !== null && $value !== '' && !in_array($value, ['Setor', 'Filial', 'Empresa']);
	    });
	@endphp
	<a href="{{ route('recursos.export.excel', $filters) }}" class="btn btn-success">Exportar Excel</a>
	<a href="{{ route('recursos.export.pdf', $filters) }}" class="btn btn-danger">Exportar PDF</a>
    </div>
</form>


    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Hostname</th>
                <th>Colaborador</th>
                <th>Empresa</th>
		<th>Filial</th>
                <th>Setor</th>
                <th>Licença</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recursos as $recurso)
                <tr>
                    <td>{{ $recurso->hostname }}</td>
                    <td>{{ $recurso->colaborador }}</td>
                    <td>{{ $recurso->empresa->nome }}</td>
		    <td>{{ $recurso->filial->nome ?? '-' }}</td>
                    <td>{{ $recurso->setor->nome }}</td>
                    <td>{{ $recurso->licenca?->codigo ?? '-' }}</td>
                    <td>
                        <a href="{{ route('recursos.edit', $recurso) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('recursos.destroy', $recurso) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Confirma a exclusão?')">Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const empresaSelect = document.getElementById('filtro_empresa');
    const filialSelect = document.getElementById('filtro_filial');
    const setorSelect = document.getElementById('filtro_setor');

    function carregarFiliais(empresaId, selected = null) {
        filialSelect.innerHTML = '<option>Carregando...</option>';
        setorSelect.innerHTML = '<option>Setor</option>';

        if (!empresaId) {
            filialSelect.innerHTML = '<option value="">Filial</option>';
            return;
        }

        fetch(`/api/filiais?empresa_id=${empresaId}`)
            .then(res => res.json())
            .then(data => {
                filialSelect.innerHTML = '<option value="">Filial</option>';
                data.forEach(filial => {
                    const option = document.createElement('option');
                    option.value = filial.id;
                    option.text = filial.nome;
                    if (selected && selected == filial.id) option.selected = true;
                    filialSelect.appendChild(option);
                });

                if (selected) {
                    carregarSetores(empresaId, selected);
                }
            });
    }

    function carregarSetores(empresaId, filialId, selected = null) {
        setorSelect.innerHTML = '<option>Carregando...</option>';

        if (!empresaId || !filialId) {
            setorSelect.innerHTML = '<option value="">Setor</option>';
            return;
        }

        fetch(`/api/setores?empresa_id=${empresaId}&filial_id=${filialId}`)
            .then(res => res.json())
            .then(data => {
                setorSelect.innerHTML = '<option value="">Setor</option>';
                data.forEach(setor => {
                    const option = document.createElement('option');
                    option.value = setor.id;
                    option.text = setor.nome;
                    if (selected && selected == setor.id) option.selected = true;
                    setorSelect.appendChild(option);
                });
            });
    }

    empresaSelect.addEventListener('change', function () {
        carregarFiliais(this.value);
    });

    filialSelect.addEventListener('change', function () {
        carregarSetores(empresaSelect.value, this.value);
    });

    // Carrega filtros já selecionados no load da página
    const empresaIdSelecionada = empresaSelect.value || null;
    const filialIdSelecionada = filialSelect.getAttribute('data-selected') || null;
    const setorIdSelecionado = setorSelect.getAttribute('data-selected') || null;

    if (empresaIdSelecionada) {
        carregarFiliais(empresaIdSelecionada, filialIdSelecionada);
        if (filialIdSelecionada) {
            carregarSetores(empresaIdSelecionada, filialIdSelecionada, setorIdSelecionado);
        }
    }
});

</script>

</div>
@endsection
</div> {{-- fecha container-fluid --}}
</div> {{-- fecha content --}}
