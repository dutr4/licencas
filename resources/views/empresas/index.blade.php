@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Empresas</h2>

    <a href="{{ route('empresas.create') }}" class="btn btn-primary mb-3">Nova Empresa</a>
    <x-buscador />

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
		<th>Nome</th>
		<th>Divisão</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($empresas as $empresa)
            <tr>
                <td>{{ $empresa->id }}</td>
		<td>{{ $empresa->nome }}</td>
		<td>{{ $empresa->divisao }}</td>
                <td>
                    <a href="{{ route('empresas.edit', $empresa) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('empresas.destroy', $empresa) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Excluir</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
