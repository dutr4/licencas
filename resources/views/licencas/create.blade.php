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
    <h1>Cadastrar Licença</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('licencas.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="empresa_id" class="form-label">Empresa</label>
            <select name="empresa_id" id="empresa_id" class="form-control" required>
                <option value="">Selecione</option>
                @foreach ($empresas as $empresa)
                    <option value="{{ $empresa->id }}">{{ $empresa->nome }}</option>
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
                @foreach ($setores as $setor)
                    <option value="{{ $setor->id }}">{{ $setor->nome }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="nota_fiscal_item_id" class="form-label">Item da Nota Fiscal</label>
            <select name="nota_fiscal_item_id" id="nota_fiscal_item_id" class="form-control" required>
                <!-- Esse campo será preenchido via JavaScript após seleção da empresa -->
            </select>
        </div>

        <div class="mb-3">
            <label for="chave" class="form-label">Chave / Serial</label>
            <input type="text" name="chave" id="chave" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
        </form>

    <script>
    document.getElementById('empresa_id').addEventListener('change', function () {
    const empresaId = this.value;
    const filialSelect = document.getElementById('filial_id');
    const setorSelect = document.getElementById('setor_id');
    const itemSelect = document.getElementById('nota_fiscal_item_id');

    filialSelect.innerHTML = '<option>Carregando...</option>';
    setorSelect.innerHTML = '<option>Selecione</option>';
    itemSelect.innerHTML = '<option>Selecione</option>';

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
    const itemSelect = document.getElementById('nota_fiscal_item_id');

    setorSelect.innerHTML = '<option>Carregando...</option>';
    itemSelect.innerHTML = '<option>Carregando...</option>';

    if (!empresaId || !filialId) {
        setorSelect.innerHTML = '<option>Selecione</option>';
        itemSelect.innerHTML = '<option>Selecione</option>';
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

    fetch(`/api/nota-fiscal-itens?empresa_id=${empresaId}&filial_id=${filialId}`)
        .then(res => res.json())
        .then(data => {
            itemSelect.innerHTML = '<option value="">Selecione</option>';
            data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.text = `${item.nome} - NF ${item.nf} (Disponível: ${item.quantidade_disponivel})`;
                itemSelect.appendChild(option);
            });
        })
        .catch(() => {
            itemSelect.innerHTML = '<option value="">Erro ao carregar</option>';
        });
});

    </script>
</div>
@endsection

