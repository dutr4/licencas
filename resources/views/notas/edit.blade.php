@extends('adminlte::page')

@section('title', 'Editar Nota Fiscal')

@section('content_header')
    <h1>Editar Nota Fiscal</h1>
@endsection

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

    <form action="{{ route('notas.update', $nota) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="numero" class="form-label">Número da Nota</label>
            <input type="text" name="numero" id="numero" value="{{ $nota->numero }}" class="form-control" required>
        </div>

	<div class="row">
	    <div class="col-md-6">
	        <div class="form-group">
	            <label for="empresa_id">Empresa</label>
	            <select name="empresa_id" id="empresa_id" class="form-control" required>
	                <option value="">Selecione a empresa</option>
	                @foreach ($empresas as $empresa)
	                    <option value="{{ $empresa->id }}" {{ $nota->empresa_id == $empresa->id ? 'selected' : '' }}>
	                        {{ $empresa->nome }}
	                    </option>
	                @endforeach
	            </select>
	        </div>
	    </div>

	    <div class="col-md-6">
	        <div class="form-group">
	            <label for="filial_id">Filial</label>
	            <select name="filial_id" id="filial_id" class="form-control" required>
	                @foreach ($filiais as $filial)
	                    <option value="{{ $filial->id }}" {{ $nota->filial_id == $filial->id ? 'selected' : '' }}>
	                        {{ $filial->nome }}
	                    </option>
	                @endforeach
	            </select>
	        </div>
	    </div>
	</div>

        <div class="mb-3">
            <label for="data_emissao" class="form-label">Data de Emissão</label>
            <input type="date" name="data_emissao" id="data_emissao" value="{{ $nota->data_emissao }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="arquivo" class="form-label">Arquivo (PDF)</label>
            <input type="file" name="arquivo" id="arquivo" class="form-control">
            @if ($nota->arquivo)
                <small>Arquivo atual: <a href="{{ asset('storage/' . $nota->arquivo) }}" target="_blank">Visualizar</a></small>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="{{ route('notas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
	<script>
	document.addEventListener('DOMContentLoaded', function () {
	    const empresaSelect = document.getElementById('empresa_id');
	    const filialSelect = document.getElementById('filial_id');
	    const filialSelecionada = '{{ $nota->filial_id }}';

    empresaSelect.addEventListener('change', function () {
	        const empresaId = this.value;
	        filialSelect.innerHTML = '<option value="">Carregando...</option>';

	        fetch(`/empresas/${empresaId}/filiais`)
	            .then(response => response.json())
	            .then(data => {
	                filialSelect.innerHTML = '<option value="">Selecione a filial</option>';
	                data.forEach(filial => {
	                    const option = document.createElement('option');
	                    option.value = filial.id;
	                    option.text = filial.nome;
	                    if (filial.id == filialSelecionada) {
	                        option.selected = true;
	                    }
	                    filialSelect.appendChild(option);
	                });
	            });
	    });

	    // dispara o carregamento das filiais da empresa atual ao carregar a página
	    empresaSelect.dispatchEvent(new Event('change'));
	});
	</script>

@endsection
