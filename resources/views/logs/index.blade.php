@extends('adminlte::page')

@section('title', 'Logs do Sistema')

@section('content_header')
    <h1>Logs do Sistema</h1>
@endsection

@section('content')
    <form method="GET" class="mb-4">
    <div class="row">
        <div class="col-md-2">
            <label>Usuário</label>
            <select name="user_id" class="form-control">
                <option value="">Todos</option>
                @foreach ($usuarios as $id => $nome)
                    <option value="{{ $id }}" {{ request('user_id') == $id ? 'selected' : '' }}>{{ $nome }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label>Ação</label>
            <select name="acao" class="form-control">
                <option value="">Todas</option>
                <option value="created" {{ request('acao') == 'created' ? 'selected' : '' }}>Criado</option>
                <option value="updated" {{ request('acao') == 'updated' ? 'selected' : '' }}>Editado</option>
                <option value="deleted" {{ request('acao') == 'deleted' ? 'selected' : '' }}>Excluído</option>
            </select>
        </div>
        <div class="col-md-2">
            <label>Módulo</label>
            <input type="text" name="modulo" class="form-control" value="{{ request('modulo') }}">
        </div>
        <div class="col-md-2">
            <label>De</label>
            <input type="date" name="data_inicio" class="form-control" value="{{ request('data_inicio') }}">
        </div>
        <div class="col-md-2">
            <label>Até</label>
            <input type="date" name="data_fim" class="form-control" value="{{ request('data_fim') }}">
        </div>
        <div class="col-md-2">
            <label>Detalhes</label>
            <input type="text" name="detalhes" class="form-control" value="{{ request('detalhes') }}">
        </div>
    </div>
	<div class="row align-items-end mb-3">
	    <div class="col-md-6 d-flex gap-2 pt-2 mt-2">
	        <button type="submit" class="btn btn-primary">Filtrar</button>
	        <a href="{{ route('logs.index') }}" class="btn btn-secondary">Limpar</a>
	    </div>
	    <div class="col-md-6 d-flex justify-content-end gap-2 pt-2 mt-2">
	        <a href="{{ route('logs.export.excel', request()->all()) }}" class="btn btn-success">🧾 Exportar Excel</a>
	        <a href="{{ route('logs.exportar.pdf',request()->all()) }}" class="btn btn-danger">📄 Exportar PDF</a>
	    </div>
	</div>
</form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Data/Hora</th>
                <th>Usuário</th>
                <th>Ação</th>
                <th>Módulo</th>
                <th>Detalhes</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($logs as $log)
                <tr>
                    <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $log->user->name ?? 'Sistema' }}</td>
                    <td>{{ ucfirst($log->acao) }}</td>
                    <td>{{ $log->modulo }}</td>
                    <td>{{ $log->detalhes }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Nenhum log encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        {{ $logs->links() }}
    </div>
@endsection
