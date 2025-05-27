@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Filiais</h1>

    <a href="{{ route('filiais.create') }}" class="btn btn-primary mb-3">Nova Filial</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('filiais.index') }}" class="mb-3 d-flex align-items-end gap-2">
        <div style="margin-right: 10px;>
            <label for="filtro_empresa" class="form-label">Empresa</label>
            <select name="filtro_empresa" id="filtro_empresa" class="form-control">
                <option value="">Todas</option>
                @foreach ($empresas as $empresa)
                    <option value="{{ $empresa->id }}" {{ request('filtro_empresa') == $empresa->id ? 'selected' : '' }}>
                        {{ $empresa->nome }}
                    </option>
                @endforeach
            </select>
        </div>
        <div style="margin-right: 10px;>
            <label for="filtro_nome" class="form-label">Nome da Filial</label>
            <input type="text" name="filtro_nome" id="filtro_nome" class="form-control" value="{{ request('filtro_nome') }}">
        </div>
        <div>
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <a href="{{ route('filiais.index') }}" class="btn btn-secondary">Limpar</a>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Empresa</th>
                <th>Filial</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($filiais as $filial)
                <tr>
                    <td>{{ $filial->empresa->nome }}</td>
                    <td>{{ $filial->nome }}</td>
                    <td>
                        <a href="{{ route('filiais.edit', $filial) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('filiais.destroy', $filial) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
