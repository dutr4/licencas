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
    <h1>Editar Licença</h1>

    <a href="{{ route('licencas.index') }}" class="btn btn-secondary mb-3">← Voltar</a>

    <form action="{{ route('licencas.update', $licenca) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="empresa_id" class="form-label">Empresa</label>
            <select name="empresa_id" id="empresa_id" class="form-control" required>
                @foreach ($empresas as $empresa)
                    <option value="{{ $empresa->id }}" {{ $empresa->id == $licenca->empresa_id ? 'selected' : '' }}>
                        {{ $empresa->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="filial_id" class="form-label">Filial</label>
            <select name="filial_id" id="filial_id" class="form-control" required>
                <option value="">Selecione</option>
                {{-- Opções serão carregadas via JS --}}
            </select>
        </div>

        <div class="mb-3">
            <label for="setor_id" class="form-label">Setor</label>
            <select name="setor_id" id="setor_id" class="form-control" required>
                <option value="">Selecione</option>
                {{-- Opções serão carregadas via JS --}}
            </select>
        </div>

	<div class="mb-3">
	    <label for="nota_fiscal_item_id" class="form-label">Item da Nota Fiscal</label>
	    <select name="nota_fiscal_item_id" id="nota_fiscal_item_id" class="form-control" required>
	        <option value="">Selecione</option>
	       	{{-- Opções serão carregadas via JS --}}
	    </select>
	</div>

        <div class="mb-3">
            <label for="chave" class="form-label">Chave / Serial</label>
            <input type="text" name="chave" id="chave" value="{{ $licenca->chave }}" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>

    <script>
        function carregarFiliais(empresaId, selecionado = null) {
            const filialSelect = document.getElementById('filial_id');
            filialSelect.innerHTML = '<option>Carregando...</option>';

            fetch(`/api/filiais?empresa_id=${empresaId}`)
                .then(res => res.json())
                .then(data => {
                    filialSelect.innerHTML = '<option value="">Selecione</option>';
                    data.forEach(filial => {
                        const option = document.createElement('option');
                        option.value = filial.id;
                        option.text = filial.nome;
                        if (selecionado && filial.id == selecionado) option.selected = true;
                        filialSelect.appendChild(option);
                    });
                });
        }

        function carregarSetores(empresaId, filialId, selecionado = null) {
            const setorSelect = document.getElementById('setor_id');
            setorSelect.innerHTML = '<option>Carregando...</option>';

            fetch(`/api/setores?empresa_id=${empresaId}&filial_id=${filialId}`)
                .then(res => res.json())
                .then(data => {
                    setorSelect.innerHTML = '<option value="">Selecione</option>';
                    data.forEach(setor => {
                        const option = document.createElement('option');
                        option.value = setor.id;
                        option.text = setor.nome;
                        if (selecionado && setor.id == selecionado) option.selected = true;
                        setorSelect.appendChild(option);
                    });
                });
        }

        document.getElementById('empresa_id').addEventListener('change', function () {
            carregarFiliais(this.value);
            document.getElementById('setor_id').innerHTML = '<option value="">Selecione</option>';
        });

        document.getElementById('filial_id').addEventListener('change', function () {
            const empresaId = document.getElementById('empresa_id').value;
            carregarSetores(empresaId, this.value);
        });

        // Carregar filiais e setores no carregamento da página com valores atuais
        window.addEventListener('DOMContentLoaded', function () {
            const empresaId = document.getElementById('empresa_id').value;
            const filialId = "{{ $licenca->filial_id }}";
            const setorId = "{{ $licenca->setor_id }}";

            if (empresaId) {
                carregarFiliais(empresaId, filialId);
            }
            if (empresaId && filialId) {
                carregarSetores(empresaId, filialId, setorId);
            }
        });

	function carregarItensNotaFiscal(empresaId, filialId, selecionado = null) {
	    const itemSelect = document.getElementById('nota_fiscal_item_id');
	    itemSelect.innerHTML = '<option>Carregando...</option>';

	    fetch(`/api/nota-fiscal-itens?empresa_id=${empresaId}&filial_id=${filialId}`)
	        .then(res => res.json())
	        .then(data => {
	            itemSelect.innerHTML = '<option value="">Selecione</option>';
	            data.forEach(item => {
	                const option = document.createElement('option');
	                option.value = item.id;
	                option.text = `${item.nome} - NF ${item.nf} (Disponível: ${item.quantidade_disponivel})`;
	                if (selecionado && item.id == selecionado) option.selected = true;
	                itemSelect.appendChild(option);
	            });
	        });
	}

	document.getElementById('filial_id').addEventListener('change', function () {
	    const empresaId = document.getElementById('empresa_id').value;
	    const filialId = this.value;
	    carregarSetores(empresaId, filialId);
	    carregarItensNotaFiscal(empresaId, filialId);
	});

	// No carregamento da página, atualize também os itens
	window.addEventListener('DOMContentLoaded', function () {
	    const empresaId = document.getElementById('empresa_id').value;
	    const filialId = "{{ $licenca->filial_id }}";
	    const setorId = "{{ $licenca->setor_id }}";
	    const notaFiscalItemId = "{{ $licenca->nota_fiscal_item_id }}";

	    if (empresaId) {
	        carregarFiliais(empresaId, filialId);
	    }
	    if (empresaId && filialId) {
	        carregarSetores(empresaId, filialId, setorId);
	        carregarItensNotaFiscal(empresaId, filialId, notaFiscalItemId);
	    }
	});

    </script>
</div>
@endsection
