@extends('adminlte::page')

@section('title', 'Editar Usuário')

@section('content_header')
    <h1>Editar Usuário</h1>
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

    <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name">Nome</label>
            <input type="text" name="name" class="form-control" value="{{ $usuario->name }}" required>
        </div>

        <div class="mb-3">
            <label for="email">E-mail</label>
            <input type="email" name="email" class="form-control" value="{{ $usuario->email }}" required>
        </div>

        <div class="mb-3">
            <label for="perfil">Perfil</label>
	    <select name="perfil" id="perfil" class="form-control" required>
	        <option value="admin" {{ $usuario->perfil === 'admin' ? 'selected' : '' }}>Administrador</option>
	        <option value="operador" {{ $usuario->perfil === 'operador' ? 'selected' : '' }}>Operador</option>
	        <option value="visualizacao" {{ $usuario->perfil === 'visualizacao' ? 'selected' : '' }}>Visualização</option>
	    </select>
        </div>

	<div id="divisoes-container" class="mb-3" style="display: none;">
	    <label for="divisoes" class="form-label">Divisões de Negócio</label>
	    <select name="divisoes[]" id="divisoes" class="form-control" multiple>
	        <option value="Comércio" {{ in_array('Comércio', $divisoes ?? []) ? 'selected' : '' }}>Comércio</option>
	        <option value="Logística" {{ in_array('Logística', $divisoes ?? []) ? 'selected' : '' }}>Logística</option>
	        <option value="Passageiros" {{ in_array('Passageiros', $divisoes ?? []) ? 'selected' : '' }}>Passageiros</option>
	        <option value="Holding" {{ in_array('Holding', $divisoes ?? []) ? 'selected' : '' }}>Holding</option>
	    </select>
	</div>

        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Voltar</a>
    </form>
@endsection

@section('js')
<script>
    function toggleDivisoes() {
        const perfil = document.getElementById('perfil').value;
        const container = document.getElementById('divisoes-container');
        container.style.display = perfil === 'visualizacao' ? 'block' : 'none';
    }

    document.getElementById('perfil').addEventListener('change', toggleDivisoes);
    window.addEventListener('DOMContentLoaded', toggleDivisoes);
</script>
@endsection
