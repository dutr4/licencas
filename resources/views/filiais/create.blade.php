@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Nova Filial</h1>

    <form action="{{ route('filiais.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="empresa_id" class="form-label">Empresa</label>
            <select name="empresa_id" id="empresa_id" class="form-control" required>
                <option value="">Selecione</option>
                @foreach($empresas as $empresa)
                    <option value="{{ $empresa->id }}">{{ $empresa->nome }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="nome" class="form-label">Nome da Filial</label>
            <input type="text" name="nome" id="nome" class="form-control" required>
        </div>

        <button class="btn btn-success">Salvar</button>
        <a href="{{ route('filiais.index') }}" class="btn btn-secondary">Voltar</a>
    </form>
</div>
@endsection
