<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Licenca;
use App\Models\Empresa;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
{
    $user = \Auth::user();

    if ($user->perfil === 'visualizacao') {
        $divisoes = \DB::table('divisao_user')
            ->where('user_id', $user->id)
            ->pluck('divisao');
        $bloquearDivisao = true;
    } else {
        $divisoes = \App\Models\Empresa::select('divisao')->distinct()->pluck('divisao');
        $bloquearDivisao = false;
    }

    if ($user->perfil === 'visualizacao') {
        $empresas = \App\Models\Empresa::whereIn('divisao', $divisoes)->get();
    } else {
        $empresas = \App\Models\Empresa::all();
    }
    $filiais = \App\Models\Filial::all();
    $setores = \App\Models\Setor::all();

    return view('dashboard', compact(
        'divisoes',
        'empresas',
        'filiais',
        'setores',
        'bloquearDivisao'
    ));
}

	public function licencasStatus(Request $request)
	{
	    $query = \App\Models\Licenca::query();
	    $user = \Auth::user();

	    if ($user->perfil === 'visualizacao') {
	        $divisoesPermitidas = \DB::table('divisao_user')
	            ->where('user_id', $user->id)
	            ->pluck('divisao');

	        $empresaIds = \App\Models\Empresa::whereIn('divisao', $divisoesPermitidas)->pluck('id');
	        $query->whereIn('empresa_id', $empresaIds);
	    } elseif ($request->filled('divisao')) {
	        $empresaIds = \App\Models\Empresa::where('divisao', $request->divisao)->pluck('id');
	        $query->whereIn('empresa_id', $empresaIds);
	    }

	    if ($request->filled('empresa')) {
	        $query->where('empresa_id', $request->empresa);
	    }

	    if ($request->filled('filial')) {
	        $query->where('filial_id', $request->filial);
	    }

	    if ($request->filled('setor')) {
	        $query->where('setor_id', $request->setor);
	    }

	    $dados = $query->select('status')
	                   ->selectRaw('COUNT(*) as total')
	                   ->groupBy('status')
	                   ->get();

	    return response()->json($dados);
	}

	public function licencasEmUsoPorVersao(Request $request)
{
    $query = \DB::table('licencas as l')
                ->join('nota_fiscal_itens as nfi', 'l.nota_fiscal_item_id', '=', 'nfi.id')
                ->where('l.status', 'vinculada');

    $user = \Auth::user();

    if ($user->perfil === 'visualizacao') {
        $divisoesPermitidas = \DB::table('divisao_user')
            ->where('user_id', $user->id)
            ->pluck('divisao');

        $empresaIds = \App\Models\Empresa::whereIn('divisao', $divisoesPermitidas)->pluck('id');
        $query->whereIn('l.empresa_id', $empresaIds);
    } elseif ($request->filled('divisao')) {
        $empresaIds = \App\Models\Empresa::where('divisao', $request->divisao)->pluck('id');
        $query->whereIn('l.empresa_id', $empresaIds);
    }

    if ($request->filled('empresa')) {
        $query->where('l.empresa_id', $request->empresa);
    }

    if ($request->filled('filial')) {
        $query->where('l.filial_id', $request->filial);
    }

    if ($request->filled('setor')) {
        $query->where('l.setor_id', $request->setor);
    }

    $dados = $query->select('nfi.descricao as versao', \DB::raw('COUNT(*) as total'))
                   ->groupBy('nfi.descricao')
                   ->get();

    return response()->json($dados);
}

	public function licencasLivresPorVersao(Request $request)
{
    $query = \DB::table('licencas as l')
                ->join('nota_fiscal_itens as nfi', 'l.nota_fiscal_item_id', '=', 'nfi.id')
                ->where('l.status', 'disponivel');

    $user = \Auth::user();

    if ($user->perfil === 'visualizacao') {
        $divisoesPermitidas = \DB::table('divisao_user')
            ->where('user_id', $user->id)
            ->pluck('divisao');

        $empresaIds = \App\Models\Empresa::whereIn('divisao', $divisoesPermitidas)->pluck('id');
        $query->whereIn('l.empresa_id', $empresaIds);
    } elseif ($request->filled('divisao')) {
        $empresaIds = \App\Models\Empresa::where('divisao', $request->divisao)->pluck('id');
        $query->whereIn('l.empresa_id', $empresaIds);
    }

    if ($request->filled('empresa')) {
        $query->where('l.empresa_id', $request->empresa);
    }

    if ($request->filled('filial')) {
        $query->where('l.filial_id', $request->filial);
    }

    if ($request->filled('setor')) {
        $query->where('l.setor_id', $request->setor);
    }

    $dados = $query->select('nfi.descricao as versao', \DB::raw('COUNT(*) as total'))
                   ->groupBy('nfi.descricao')
                   ->get();

    return response()->json($dados);
}

}
