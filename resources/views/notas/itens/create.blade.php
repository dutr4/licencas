@extends('adminlte::page')

@section('title', 'Adicionar Item')

@section('content_header')
    <h1>Adicionar Item à Nota {{ $nota->numero }}</h1>
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

    <form action="{{ route('notas.itens.store', $nota) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição do Item</label>
            <input type="text" name="descricao" id="descricao" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="quantidade" class="form-label">Quantidade</label>
            <input type="number" name="quantidade" id="quantidade" class="form-control" value="1" min="1" required>
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('notas.show', $nota) }}" class="btn btn-secondary">Cancelar</a>
    </form>
@endsection
