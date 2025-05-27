@extends('layouts.app')

@section('content')
<div class="container">

@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

<ul class="nav nav-tabs mb-3" id="adminTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="gerais-tab" data-toggle="tab" href="#gerais" role="tab">Configurações Gerais</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="seguranca-tab" data-toggle="tab" href="#seguranca" role="tab">Segurança</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="sistema-tab" data-toggle="tab" href="#sistema" role="tab">Sistema</a>
    </li>
</ul>

<div class="tab-content" id="adminTabContent">

    <div class="tab-pane fade show active" id="gerais" role="tabpanel">
        <h3>Logo Atual</h3>
        @if (Storage::exists('public/config/logo.png'))
            <img src="{{ asset('storage/config/logo.png') }}" alt="Logo" style="max-height: 100px;">
        @else
            <p>Nenhuma logo configurada.</p>
        @endif

        <h3>Alterar Logo</h3>
        <form action="{{ route('admin.logo') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="logo" class="form-control mb-2">
            <button type="submit" class="btn btn-primary">Salvar Logo</button>
        </form>

        <h3>Configuração de Fuso Horário</h3>
        <form action="{{ route('admin.timezone') }}" method="POST">
            @csrf
            <select name="timezone" class="form-control mb-2">
                @foreach(timezone_identifiers_list() as $tz)
                    <option value="{{ $tz }}" {{ (Storage::exists('public/config/timezone.txt') && Storage::get('public/config/timezone.txt') == $tz) ? 'selected' : '' }}>
                        {{ $tz }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">Salvar Fuso Horário</button>
        </form>

        <h3>Configuração de Formato de Data e Hora</h3>
        <form action="{{ route('admin.datetime.format') }}" method="POST">
            @csrf
            <input type="text" name="datetime_format" class="form-control mb-2"
                   value="{{ Storage::exists('public/config/datetime_format.txt') ? Storage::get('public/config/datetime_format.txt') : 'Y-m-d H:i:s' }}">
            <button type="submit" class="btn btn-primary">Salvar Formato</button>
        </form>
        <small>Exemplo: Y-m-d H:i:s, d/m/Y H:i, etc.</small>
    </div>

    <div class="tab-pane fade" id="seguranca" role="tabpanel">
        <h3>Configurações de Segurança</h3>
        <form action="{{ route('admin.security') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Tempo de sessão (minutos):</label>
                <input type="number" name="session_timeout" class="form-control"
                       value="{{ Storage::exists('public/config/session_timeout.txt') ? Storage::get('public/config/session_timeout.txt') : 30 }}">
            </div>

            <div class="mb-3">
                <label>Bloquear IP:</label>
                <input type="text" name="blocked_ips" class="form-control"
                       value="{{ Storage::exists('public/config/blocked_ips.txt') ? Storage::get('public/config/blocked_ips.txt') : '' }}">
                <small>Separar múltiplos IPs por vírgula</small>
            </div>

            <div class="mb-3">
                <label>Notificações por E-mail:</label>
                <select name="email_notifications" class="form-control">
                    <option value="1" {{ Storage::exists('public/config/email_notifications.txt') && Storage::get('public/config/email_notifications.txt') == '1' ? 'selected' : '' }}>Ativar</option>
                    <option value="0" {{ Storage::exists('public/config/email_notifications.txt') && Storage::get('public/config/email_notifications.txt') == '0' ? 'selected' : '' }}>Desativar</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Número máximo de tentativas de login:</label>
                <input type="number" name="max_login_attempts" class="form-control"
                       value="{{ Storage::exists('public/config/max_login_attempts.txt') ? Storage::get('public/config/max_login_attempts.txt') : 5 }}">
            </div>

            <div class="mb-3">
                <label>Exportações:</label>
                <select name="exports_enabled" class="form-control">
                    <option value="1" {{ Storage::exists('public/config/exports_enabled.txt') && Storage::get('public/config/exports_enabled.txt') == '1' ? 'selected' : '' }}>Ativar</option>
                    <option value="0" {{ Storage::exists('public/config/exports_enabled.txt') && Storage::get('public/config/exports_enabled.txt') == '0' ? 'selected' : '' }}>Desativar</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Salvar Configurações de Segurança</button>
        </form>
    </div>

    <div class="tab-pane fade" id="sistema" role="tabpanel">
        <h3>Informações do Sistema</h3>
        <p>Versão: 1.0.0</p>
        <p>Última atualização: {{ \Carbon\Carbon::parse(\Illuminate\Support\Facades\File::lastModified(base_path('composer.json')))->format('d/m/Y H:i') }}</p>

        <h3>Logs do Sistema</h3>
        @if (Storage::exists('logs/laravel.log'))
            <a href="{{ route('admin.log.download') }}" class="btn btn-sm btn-success">Baixar Log</a>
            <form action="{{ route('admin.log.delete') }}" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir o log?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">Excluir Log</button>
            </form>
            <pre style="max-height: 300px; overflow-y: auto; background: #f8f9fa; padding: 10px;">
                {{ \Illuminate\Support\Facades\Storage::get('logs/laravel.log') }}
            </pre>
        @else
            <p>Nenhum log disponível.</p>
        @endif

        <h3>Backups Disponíveis</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Arquivo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($backups as $backup)
                    <tr>
                        <td>{{ $backup }}</td>
                        <td>
                            <a href="{{ route('admin.download', ['file' => $backup]) }}" class="btn btn-sm btn-success">Download</a>
                            <form action="{{ route('admin.restore') }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="backup_file" value="{{ $backup }}">
                                <button type="submit" class="btn btn-sm btn-warning">Restaurar</button>
                            </form>
                            <form action="{{ route('admin.delete', ['file' => $backup]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir este backup?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ route('admin.backup') }}" class="btn btn-primary">Gerar Backup</a>

        <h3>Restaurar Backup</h3>
        <form action="{{ route('admin.restore') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="backup_file">Selecionar backup existente:</label>
                <select name="backup_file" class="form-control">
                    <option value="">-- Nenhum --</option>
                    @foreach ($backups as $backup)
                        <option value="{{ $backup }}">{{ $backup }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="upload_file">Ou enviar arquivo:</label>
                <input type="file" name="upload_file" class="form-control">
            </div>
            <button type="submit" class="btn btn-warning">Restaurar</button>
        </form>
    </div>

</div>
</div>
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-qN6/6KiMi1OPhnJoBrzJD/xEzKXawBbmSOvO6X+4vAB9+avFiMF0KzWmiv5X8BGD" crossorigin="anonymous"></script>
@endsection
