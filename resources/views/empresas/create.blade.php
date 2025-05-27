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
    <h1>Nova Empresa</h1>

    <form action="{{ route('empresas.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nome" class="form-label">Nome da Empresa</label>
            <input type="text" name="nome" id="nome" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="divisao" class="form-label">Divisão</label>
            <select name="divisao" id="divisao" class="form-control" required>
                <option value="">Selecione</option>
                <option value="Logística">Divisão Logística</option>
                <option value="Comércio">Divisão Comércio</option>
                <option value="Passageiros">Divisão Passageiros</option>
                <option value="Holding">Divisão Holding</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="{{ route('empresas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
