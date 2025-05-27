<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $backupPath = storage_path('app/backups');
        $backups = [];

        if (is_dir($backupPath)) {
	    $backups = [];

	    foreach (glob($backupPath . '/*.sql') as $file) {
	        $backups[] = basename($file);
	    }
        }
file_put_contents(storage_path('logs/debug_backups.log'), json_encode($backups));

        return view('admin.index')->with(['backups' => $backups]);
    }

    public function backup()
    {
        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $backupPath = storage_path('app/backups');

        if (!file_exists($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        $filename = 'backup_' . now()->format('Ymd_His') . '.sql';
        $command = "mysqldump -u{$username} -p{$password} {$database} > {$backupPath}/{$filename}";

        $result = null;
        $output = null;
        exec($command, $output, $result);

        if ($result === 0) {
            return back()->with('status', 'Backup gerado com sucesso.');
        } else {
            return back()->with('status', 'Erro ao gerar backup.');
        }
    }

    public function restore(Request $request)
    {
    $db = config('database.connections.mysql');
    $restorePath = null;

    if ($request->hasFile('upload_file')) {
        $file = $request->file('upload_file');
        $restorePath = $file->storeAs('backups', $file->getClientOriginalName());
        $restorePath = storage_path('app/' . $restorePath);
    } elseif ($request->backup_file) {
        $restorePath = storage_path('app/backups/' . $request->backup_file);
    }

    if (!$restorePath || !file_exists($restorePath)) {
        return back()->with('status', 'Nenhum arquivo de backup selecionado ou arquivo não encontrado.');
    }

    $command = sprintf(
        'mysql -u%s -p%s %s < %s',
        $db['username'],
        $db['password'],
        $db['database'],
        $restorePath
    );

    $result = null;
    $output = null;
    exec($command, $output, $result);

    if ($result === 0) {
        return back()->with('status', 'Backup restaurado com sucesso!');
    } else {
        return back()->with('status', 'Erro ao restaurar backup.');
    }
}

    public function download($file)
    {
        $path = storage_path('app/backups/' . $file);

        if (file_exists($path)) {
            return response()->download($path);
        } else {
            return back()->with('status', 'Arquivo não encontrado.');
        }
}
    public function delete($file)
    {
        $path = storage_path('app/backups/' . $file);

        if (file_exists($path)) {
            unlink($path);
            return back()->with('status', 'Backup excluído com sucesso!');
        } else {
            return back()->with('status', 'Arquivo de backup não encontrado.');
        }

}
    public function uploadLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

	$path = $request->file('logo')->storeAs('config', 'logo.png', 'public');

        return back()->with('status', 'Logo atualizada com sucesso!');
    }

    public function updateTimezone(Request $request)
    {
        $request->validate([
            'timezone' => 'required|timezone',
        ]);

        file_put_contents(storage_path('app/public/config/timezone.txt'), $request->timezone);

        return back()->with('status', 'Fuso horário atualizado para: ' . $request->timezone);
    }

    public function updateDateTimeFormat(Request $request)
    {
        $request->validate([
            'datetime_format' => 'required|string',
        ]);

        file_put_contents(storage_path('app/public/config/datetime_format.txt'), $request->datetime_format);

        return back()->with('status', 'Formato de data e hora atualizado para: ' . $request->datetime_format);
    }

    public function updateSecurity(Request $request)
    {
        file_put_contents(storage_path('app/public/config/session_timeout.txt'), $request->session_timeout);
        file_put_contents(storage_path('app/public/config/blocked_ips.txt'), $request->blocked_ips);
        file_put_contents(storage_path('app/public/config/email_notifications.txt'), $request->email_notifications);
        file_put_contents(storage_path('app/public/config/max_login_attempts.txt'), $request->max_login_attempts);
        file_put_contents(storage_path('app/public/config/exports_enabled.txt'), $request->exports_enabled);

        return back()->with('status', 'Configurações de segurança atualizadas com sucesso!');
    }

    public function downloadLog()
{
    $path = storage_path('logs/laravel.log');

    if (file_exists($path)) {
        return response()->download($path);
    } else {
        return back()->with('status', 'Log não encontrado.');
    }
}

public function deleteLog()
{
    $path = storage_path('logs/laravel.log');

    if (file_exists($path)) {
        unlink($path);
        return back()->with('status', 'Log excluído com sucesso!');
    } else {
        return back()->with('status', 'Log não encontrado.');
    }
}

}

