@extends('adminlte::page')

@section('title', 'Usuários')

@section('content_header')
    <h1>Usuários</h1>
@endsection

@section('content')
    <x-buscador />

    <a href="{{ route('usuarios.create') }}" class="btn btn-success mb-3">Novo Usuário</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Perfil</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->name }}</td>
                    <td>{{ $usuario->email }}</td>
                    <td>{{ ucfirst($usuario->perfil) }}</td>
                    <td>
			<a href="{{ url('usuarios/'.$usuario->id.'/edit') }}" class="btn btn-primary btn-sm">Editar</a>

                        <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Deseja excluir este usuário?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $usuarios->links() }}
@endsection
