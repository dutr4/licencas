<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;
use App\Exports\LogsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class LogController extends Controller
{
    public function index(Request $request)
{
    $query = \App\Models\Log::with('usuario')->latest();

    if ($request->filled('user_id')) {
        $query->where('user_id', $request->user_id);
    }

    if ($request->filled('acao')) {
        $query->where('acao', $request->acao);
    }

    if ($request->filled('modulo')) {
        $query->where('modulo', $request->modulo);
    }

    if ($request->filled('data_inicio')) {
        $query->whereDate('created_at', '>=', $request->data_inicio);
    }

    if ($request->filled('data_fim')) {
        $query->whereDate('created_at', '<=', $request->data_fim);
    }

    if ($request->filled('detalhes')) {
        $query->where('detalhes', 'like', '%' . $request->detalhes . '%');
    }

    $logs = $query->paginate(20);

    $usuarios = \App\Models\User::pluck('name', 'id');

    return view('logs.index', compact('logs', 'usuarios'));
}
    public function exportExcel(Request $request)
    {
    return Excel::download(new LogsExport($request), 'logs.xlsx');
    }
    public function exportarPDF(Request $request)
{
    $query = Log::with('usuario');

    if ($request->filled('user_id')) {
        $query->where('usuario_id', $request->user_id);
    }

    if ($request->filled('acao')) {
        $query->where('acao', $request->acao);
    }

    if ($request->filled('modulo')) {
        $query->where('modulo', $request->modulo);
    }

    if ($request->filled('data_inicio')) {
        $query->whereDate('created_at', '>=', $request->data_inicio);
    }

    if ($request->filled('data_fim')) {
        $query->whereDate('created_at', '<=', $request->data_fim);
    }

    if ($request->filled('detalhes')) {
        $query->where('detalhes', 'like', '%' . $request->detalhes . '%');
    }

    $logs = $query->latest()->get();

    $pdf = Pdf::loadView('logs.pdf', compact('logs'));
    return $pdf->download('logs_sistema.pdf');
}
}
