@extends('adminlte::page')

@section('title', 'Nova Nota Fiscal')

@section('content_header')
    <h1>Nova Nota Fiscal</h1>
@endsection

@stack('js')

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

    <form action="{{ route('notas.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="numero" class="form-label">Número da Nota</label>
            <input type="text" name="numero" id="numero" class="form-control" required>
        </div>

	<div class="row">
	    <div class="col-md-6">
	        <div class="form-group">
	            <label for="empresa_id">Empresa</label>
	            <select name="empresa_id" id="empresa_id" class="form-control" required>
	                <option value="">Selecione a empresa</option>
	                @foreach ($empresas as $empresa)
	                    <option value="{{ $empresa->id }}">{{ $empresa->nome }}</option>
	                @endforeach
	            </select>
	        </div>
	    </div>

	    <div class="col-md-6">
	        <div class="form-group">
	            <label for="filial_id">Filial</label>
	            <select name="filial_id" id="filial_id" class="form-control" required>
	                <option value="">Selecione a filial</option>
			@foreach ($filiais as $filial)
		            <option value="{{ $filial->id }}" {{ old('filial_id') == $filial->id ? 'selected' : '' }}>
                		{{ $filial->nome }}
		            </option>
		        @endforeach
	            </select>
	        </div>
	    </div>
	</div>

        <div class="mb-3">
            <label for="data_emissao" class="form-label">Data de Emissão</label>
            <input type="date" name="data_emissao" id="data_emissao" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="arquivo" class="form-label">Arquivo (PDF)</label>
            <input type="file" name="arquivo" id="arquivo" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="{{ route('notas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@endsection
@push('js')
	<script>
	document.getElementById('empresa_id').addEventListener('change', function () {
	    const empresaId = this.value;
	    const filialSelect = document.getElementById('filial_id');

	    filialSelect.innerHTML = '<option value="">Carregando...</option>';

	    fetch(`/empresas/${empresaId}/filiais`)
	        .then(response => response.json())
	        .then(data => {
	            filialSelect.innerHTML = '<option value="">Selecione a filial</option>';
	            data.forEach(filial => {
	                const option = document.createElement('option');
	                option.value = filial.id;
	                option.text = filial.nome;
	                filialSelect.appendChild(option);
	            });
	        });
	});
	</script>
@endpush
