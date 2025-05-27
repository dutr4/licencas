@extends('adminlte::page')

@section('title', 'Novo Usuário')

@section('content_header')
    <h1>Novo Usuário</h1>
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

    <form action="{{ route('usuarios.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name">Nome</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="email">E-mail</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="perfil">Perfil</label>
	    <select name="perfil" id="perfil" class="form-control" required>
	        <option value="admin">Administrador</option>
	        <option value="operador">Operador</option>
	        <option value="visualizacao">Visualização</option>
	    </select>
        </div>

        <div class="mb-3">
            <label for="password">Senha</label>
            <input type="password" name="password" class="form-control" required>
        </div>

	<div id="divisoes-container" class="mb-3" style="display: none;">
	    <label for="divisoes" class="form-label">Divisões de Negócio</label>
	    <select name="divisoes[]" id="divisoes" class="form-control" multiple>
	        <option value="Comércio">Comércio</option>
	        <option value="Logística">Logística</option>
	        <option value="Passageiros">Passageiros</option>
	        <option value="Holding">Holding</option>
	    </select>
	</div>

        <button type="submit" class="btn btn-success">Cadastrar</button>
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
