@extends('adminlte::page')

@section('title', 'Notas Fiscais')

@section('content_header')
    <h1>Notas Fiscais</h1>
@endsection

@section('content')
    <a href="{{ route('notas.create') }}" class="btn btn-primary mb-3">Nova Nota Fiscal</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('notas.index') }}" class="mb-3 d-flex flex-wrap gap-2 align-items-end">

    <div style="margin-right: 10px;">
        <label for="filtro_filial" class="form-label">Filial</label>
        <select name="filtro_filial" id="filtro_filial" class="form-control">
            <option value="">Todas</option>
            @foreach ($filiais as $filial)
                <option value="{{ $filial->id }}" {{ request('filtro_filial') == $filial->id ? 'selected' : '' }}>
                    {{ $filial->nome }}
                </option>
            @endforeach
        </select>
    </div>

    <div style="margin-right: 10px;">
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

    <div style="margin-right: 10px;">
        <label for="filtro_numero" class="form-label">Número</label>
        <input type="text" name="filtro_numero" id="filtro_numero" class="form-control" value="{{ request('filtro_numero') }}">
    </div>

    <div style="margin-right: 10px;">
        <label for="filtro_data_inicio" class="form-label">Data Início</label>
        <input type="date" name="filtro_data_inicio" id="filtro_data_inicio" class="form-control" value="{{ request('filtro_data_inicio') }}">
    </div>

    <div style="margin-right: 10px;">
        <label for="filtro_data_fim" class="form-label">Data Fim</label>
        <input type="date" name="filtro_data_fim" id="filtro_data_fim" class="form-control" value="{{ request('filtro_data_fim') }}">
    </div>

    <div>
        <button type="submit" class="btn btn-primary">Filtrar</button>
        <a href="{{ route('notas.index') }}" class="btn btn-secondary ms-2">Limpar</a>
    </div>

</form>

    <div class="mb-3">
	<a href="{{ route('notas.export.excel', request()->only(['filtro_filial', 'filtro_empresa', 'filtro_numero', 'filtro_data_inicio', 'filtro_data_fim'])) }}" class="btn btn-success">Exportar Excel</a>
	<a href="{{ route('notas.export.pdf', request()->only(['filtro_filial', 'filtro_empresa', 'filtro_numero', 'filtro_data_inicio', 'filtro_data_fim'])) }}" class="btn btn-danger">Exportar PDF</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Número</th>
                <th>Empresa</th>
		<th>Filial</th>
                <th>Data de Emissão</th>
                <th>Arquivo</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($notas as $nota)
                <tr>
                    <td>{{ $nota->numero }}</td>
                    <td>{{ $nota->empresa->nome }}</td>
		    <td>{{ $nota->filial->nome ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($nota->data_emissao)->format('d/m/Y') }}</td>
                    <td>
                        @if ($nota->arquivo)
                            <a href="{{ asset('storage/notas/' . $nota->arquivo) }}" target="_blank">Ver PDF</a>
                        @else
                            —
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('notas.show', $nota) }}" class="btn btn-info btn-sm">Visualizar</a>
                        <a href="{{ route('notas.edit', $nota) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('notas.destroy', $nota) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Tem certeza que deseja excluir?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
