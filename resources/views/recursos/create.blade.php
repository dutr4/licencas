@extends('layouts.app')

@section('content')
@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $erro)
            <li>{{ $erro }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="container">
    <h1>{{ isset($recurso) ? 'Editar Recurso' : 'Cadastrar Recurso' }}</h1>

    <form action="{{ isset($recurso) ? route('recursos.update', $recurso) : route('recursos.store') }}" method="POST">
        @csrf
        @if(isset($recurso))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label for="empresa_id" class="form-label">Empresa</label>
            <select name="empresa_id" id="empresa_id" class="form-control" required>
                <option value="">Selecione</option>
                @foreach ($empresas as $empresa)
                    <option value="{{ $empresa->id }}" {{ (old('empresa_id') ?? ($recurso->empresa_id ?? '')) == $empresa->id ? 'selected' : '' }}>
                        {{ $empresa->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="filial_id" class="form-label">Filial</label>
            <select name="filial_id" id="filial_id" class="form-control" required>
                <option value="">Selecione</option>
                {{-- Será preenchido pelo JS --}}
            </select>
        </div>

        <div class="mb-3">
            <label for="setor_id" class="form-label">Setor</label>
            <select name="setor_id" id="setor_id" class="form-control" required>
                <option value="">Selecione</option>
                {{-- Será preenchido pelo JS --}}
            </select>
        </div>

        <div class="mb-3">
            <label for="hostname" class="form-label">Hostname</label>
            <input type="text" name="hostname" id="hostname" class="form-control" value="{{ old('hostname') ?? ($recurso->hostname ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="colaborador" class="form-label">Colaborador</label>
            <input type="text" name="colaborador" id="colaborador" class="form-control" value="{{ old('colaborador') ?? ($recurso->colaborador ?? '') }}" required>
        </div>

	<div class="mb-3">
	    <label for="licenca_id" class="form-label">Licença (disponível)</label>
	    <select name="licenca_id" id="licenca_id" class="form-control">
	        <option value="">Nenhuma</option>
	        {{-- Opções serão carregadas via JS --}}
	    </select>
	</div>


        <button type="submit" class="btn btn-primary">{{ isset($recurso) ? 'Salvar' : 'Cadastrar' }}</button>
    </form>
</div>

<script>
document.getElementById('empresa_id').addEventListener('change', function () {
    const empresaId = this.value;
    const filialSelect = document.getElementById('filial_id');
    const setorSelect = document.getElementById('setor_id');

    filialSelect.innerHTML = '<option>Carregando...</option>';
    setorSelect.innerHTML = '<option value="">Selecione</option>';

    if (!empresaId) {
        filialSelect.innerHTML = '<option value="">Selecione</option>';
        return;
    }

    fetch(`/api/filiais?empresa_id=${empresaId}`)
        .then(res => res.json())
        .then(data => {
            filialSelect.innerHTML = '<option value="">Selecione</option>';
            data.forEach(filial => {
                const option = document.createElement('option');
                option.value = filial.id;
                option.text = filial.nome;
                filialSelect.appendChild(option);
            });
        });
});

document.getElementById('filial_id').addEventListener('change', function () {
    const empresaId = document.getElementById('empresa_id').value;
    const filialId = this.value;
    const setorSelect = document.getElementById('setor_id');

    setorSelect.innerHTML = '<option>Carregando...</option>';

    if (!empresaId || !filialId) {
        setorSelect.innerHTML = '<option value="">Selecione</option>';
        return;
    }

    fetch(`/api/setores?empresa_id=${empresaId}&filial_id=${filialId}`)
        .then(res => res.json())
        .then(data => {
            setorSelect.innerHTML = '<option value="">Selecione</option>';
            data.forEach(setor => {
                const option = document.createElement('option');
                option.value = setor.id;
                option.text = setor.nome;
                setorSelect.appendChild(option);
            });
        });
});

window.addEventListener('DOMContentLoaded', () => {
    const empresaId = document.getElementById('empresa_id').value;
    const filialId = "{{ $recurso->filial_id ?? '' }}";
    const setorId = "{{ $recurso->setor_id ?? '' }}";

    if (empresaId) {
        fetch(`/api/filiais?empresa_id=${empresaId}`)
            .then(res => res.json())
            .then(data => {
                const filialSelect = document.getElementById('filial_id');
                filialSelect.innerHTML = '<option value="">Selecione</option>';
                data.forEach(filial => {
                    const option = document.createElement('option');
                    option.value = filial.id;
                    option.text = filial.nome;
                    if(filial.id == filialId) option.selected = true;
                    filialSelect.appendChild(option);
                });

                if(filialId) {
                    fetch(`/api/setores?empresa_id=${empresaId}&filial_id=${filialId}`)
                        .then(res => res.json())
                        .then(data => {
                            const setorSelect = document.getElementById('setor_id');
                            setorSelect.innerHTML = '<option value="">Selecione</option>';
                            data.forEach(setor => {
                                const option = document.createElement('option');
                                option.value = setor.id;
                                option.text = setor.nome;
                                if(setor.id == setorId) option.selected = true;
                                setorSelect.appendChild(option);
                            });
                        });
                }
            });
    }
});
function carregarLicencasDisponiveis(empresaId, filialId, selecionado = null) {
    const licencaSelect = document.getElementById('licenca_id');
    licencaSelect.innerHTML = '<option>Carregando...</option>';

    if (!empresaId || !filialId) {
        licencaSelect.innerHTML = '<option value="">Nenhuma</option>';
        return;
    }

    fetch(`/api/licencas-disponiveis?empresa_id=${empresaId}&filial_id=${filialId}`)
        .then(res => res.json())
        .then(data => {
            licencaSelect.innerHTML = '<option value="">Nenhuma</option>';
            data.forEach(licenca => {
                const option = document.createElement('option');
                option.value = licenca.id;
                option.text = `${licenca.codigo} - ${licenca.setor_nome}`;
                if (selecionado && licenca.id == selecionado) option.selected = true;
                licencaSelect.appendChild(option);
            });
        });
}

document.getElementById('filial_id').addEventListener('change', function () {
    const empresaId = document.getElementById('empresa_id').value;
    const filialId = this.value;

    carregarLicencasDisponiveis(empresaId, filialId);
});

// No carregamento da página (editar)
window.addEventListener('DOMContentLoaded', () => {
    const empresaId = document.getElementById('empresa_id').value;
    const filialId = document.getElementById('filial_id').value;
    const licencaId = "{{ $recurso->licenca_id ?? '' }}";

    if (empresaId && filialId) {
        carregarLicencasDisponiveis(empresaId, filialId, licencaId);
    }
});

</script>

@endsection
