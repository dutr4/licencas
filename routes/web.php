<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\SetorController;
use App\Http\Controllers\NotaFiscalController;
use App\Http\Controllers\NotaFiscalItemController;
use App\Http\Controllers\RecursoController;
use App\Http\Controllers\LicencaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\FilialController;
use App\Models\NotaFiscalItem;

    Route::get('/', function () {
        return redirect()->route('dashboard');
    })->middleware(['auth']);

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['auth', 'verified'])
        ->name('dashboard');

    Route::get('/redirecionar', function () {
        return redirect('/dashboard');
    })->middleware(['auth', 'redireciona.por.perfil']);

    Route::middleware(['auth'])->group(function () {
    // Perfil do usuário
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Acesso comum
    Route::resource('licencas', LicencaController::class);
    Route::resource('recursos', RecursoController::class);
    Route::resource('notas', NotaFiscalController::class);

    //  Logs
    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');

    // Exportar para Excel
    Route::get('/logs/exportar/excel', [LogController::class, 'exportExcel'])->name('logs.export.excel');
    Route::get('/recursos/exportar/excel', [App\Http\Controllers\RecursoController::class, 'exportExcel'])->name('recursos.export.excel');
    Route::get('notas/export/excel', [NotaFiscalController::class, 'exportExcel'])->name('notas.export.excel');
    Route::get('/licencas/exportar/excel', [LicencaController::class, 'exportExcel'])->name('licencas.export.excel');

    // Exportar para PDF
    Route::get('/logs/exportar-pdf', [LogController::class, 'exportarPDF'])->name('logs.exportar.pdf');
    Route::get('/recursos/exportar/pdf', [App\Http\Controllers\RecursoController::class, 'exportarPDF'])->name('recursos.export.pdf');
    Route::get('notas/export/pdf', [NotaFiscalController::class, 'exportPdf'])->name('notas.export.pdf');
    Route::get('/licencas/exportar/pdf', [LicencaController::class, 'exportPdf'])->name('licencas.export.pdf');

    // Notas
    Route::get('notas/{nota}/itens/create', [NotaFiscalItemController::class, 'create'])->name('notas.itens.create');
    Route::post('notas/{nota}/itens', [NotaFiscalItemController::class, 'store'])->name('notas.itens.store');
    Route::delete('notas/itens/{item}', [NotaFiscalItemController::class, 'destroy'])->name('notas.itens.destroy');

    // Rotas para gráficos
    Route::get('/dashboard/licencas-status', [DashboardController::class, 'licencasStatus'])->name('dashboard.licencasStatus');
    Route::get('/dashboard/licencas-em-uso-por-versao', [DashboardController::class, 'licencasEmUsoPorVersao'])->name('dashboard.licencasEmUsoPorVersao');
    Route::get('/dashboard/licencas-livres-por-versao', [DashboardController::class, 'licencasLivresPorVersao'])->name('dashboard.licencasLivresPorVersao');
    Route::get('/filtros/empresas', function (\Illuminate\Http\Request $request) {
        $empresas = \App\Models\Empresa::where('divisao', $request->divisao)->get();
        return response()->json($empresas);
    });
    Route::get('/filtros/filiais', function (\Illuminate\Http\Request $request) {
        $filiais = \App\Models\Filial::where('empresa_id', $request->empresa_id)->get();
        return response()->json($filiais);
    });
    Route::get('/filtros/setores', function (\Illuminate\Http\Request $request) {
        $setores = \App\Models\Setor::where('filial_id', $request->filial_id)->get();
        return response()->json($setores);
    });


    // Somente para Administrador
    Route::middleware('can:admin-only')->group(function () {
        Route::resource('admin', AdminController::class)->except(['show']);
        Route::resource('empresas', EmpresaController::class);
	Route::resource('filiais', FilialController::class)->parameters(['filiais' => 'filial']);
        Route::resource('setores', SetorController::class)->parameters(['setores' => 'setor']);
	Route::resource('usuarios', \App\Http\Controllers\UsuarioController::class);
	Route::get('/empresas/{empresa}/filiais', function ($empresaId) {
        return \App\Models\Filial::where('empresa_id', $empresaId)->get();
    });
        // Admin Controller
        Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index');
        Route::get('/admin/backup', [App\Http\Controllers\AdminController::class, 'backup'])->name('admin.backup');
        Route::post('/admin/restore', [App\Http\Controllers\AdminController::class, 'restore'])->name('admin.restore');
        Route::get('/admin/download/{file}', [App\Http\Controllers\AdminController::class, 'download'])->name('admin.download');
        Route::delete('/admin/backup/{file}', [App\Http\Controllers\AdminController::class, 'delete'])->name('admin.delete');

        // Logo
        Route::post('/admin/logo', [App\Http\Controllers\AdminController::class, 'uploadLogo'])->name('admin.logo');

        // Fuso horário
        Route::post('/admin/timezone', [App\Http\Controllers\AdminController::class, 'updateTimezone'])->name('admin.timezone');
        Route::post('/admin/datetime-format', [App\Http\Controllers\AdminController::class, 'updateDateTimeFormat'])->name('admin.datetime.format');
        Route::post('/admin/security', [App\Http\Controllers\AdminController::class, 'updateSecurity'])->name('admin.security');
        Route::get('/admin/log/download', [App\Http\Controllers\AdminController::class, 'downloadLog'])->name('admin.log.download');
        Route::delete('/admin/log/delete', [App\Http\Controllers\AdminController::class, 'deleteLog'])->name('admin.log.delete');
    });

});

require __DIR__.'/auth.php';
