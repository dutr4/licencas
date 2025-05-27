@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Novo Setor</h1>

    <form action="{{ route('setores.store') }}" method="POST">
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
                <option value="">Selecione a empresa primeiro</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="nome" class="form-label">Nome do Setor</label>
            <input type="text" name="nome" id="nome" class="form-control" required>
        </div>

        <button class="btn btn-success">Salvar</button>
        <a href="{{ route('setores.index') }}" class="btn btn-secondary">Voltar</a>
    </form>
</div>
@endsection

@section('js')
<script>
document.getElementById('empresa_id').addEventListener('change', function () {
    let empresaId = this.value;
    let filialSelect = document.getElementById('filial_id');
    filialSelect.innerHTML = '<option>Carregando...</option>';

    fetch(`/empresas/${empresaId}/filiais`)
        .then(response => response.json())
        .then(data => {
            filialSelect.innerHTML = '<option value="">Selecione</option>';
            data.forEach(f => {
                let opt = document.createElement('option');
                opt.value = f.id;
                opt.textContent = f.nome;
                filialSelect.appendChild(opt);
            });
        });
});
</script>
@endsection
