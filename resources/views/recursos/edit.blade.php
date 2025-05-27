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
    <h1>Editar Recurso</h1>

    <form action="{{ route('recursos.update', $recurso) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="hostname" class="form-label">Hostname</label>
            <input type="text" name="hostname" id="hostname" class="form-control" value="{{ $recurso->hostname }}" required>
        </div>

        <div class="mb-3">
            <label for="colaborador" class="form-label">Colaborador</label>
            <input type="text" name="colaborador" id="colaborador" class="form-control" value="{{ $recurso->colaborador }}" required>
        </div>

        <div class="mb-3">
            <label for="empresa_id" class="form-label">Empresa</label>
            <select name="empresa_id" id="empresa_id" class="form-control" required>
                <option value="">Selecione</option>
                @foreach($empresas as $empresa)
                    <option value="{{ $empresa->id }}" {{ $empresa->id == $recurso->empresa_id ? 'selected' : '' }}>
                        {{ $empresa->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="setor_id" class="form-label">Setor</label>
            <select name="setor_id" id="setor_id" class="form-control" required>
                <option value="">Selecione</option>
                @foreach($setores as $setor)
                    <option value="{{ $setor->id }}" {{ $setor->id == $recurso->setor_id ? 'selected' : '' }}>
                        {{ $setor->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="licenca_id" class="form-label">Licença (opcional)</label>
            <select name="licenca_id" id="licenca_id" class="form-control">
                <option value="">Nenhuma</option>
                @foreach($licencas as $licenca)
                    <option value="{{ $licenca->id }}" {{ $licenca->id == $recurso->licenca_id ? 'selected' : '' }}>
                        {{ $licenca->codigo }} - {{ $licenca->setor->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-success">Atualizar</button>
        <a href="{{ route('recursos.index') }}" class="btn btn-secondary">Voltar</a>
    </form>
</div>
@endsection
