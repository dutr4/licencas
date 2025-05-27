<?php

namespace App\Http\Controllers;

use App\Models\Recurso;
use App\Models\Empresa;
use App\Models\Filial;
use App\Models\Setor;
use App\Models\Licenca;
use App\Helpers\LogHelper;
use Illuminate\Http\Request;
use App\Exports\RecursosExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class RecursoController extends Controller
{
    public function index(Request $request)
    {
	$filialId = $request->filial_id;
	$setorId = $request->setor_id;

	// Ajuste para tratar valores inválidos
	if (empty($filialId) || !is_numeric($filialId)) {
	    $filialId = null;
	}
	if (empty($setorId) || !is_numeric($setorId)) {
	    $setorId = null;
	}

        $query = Recurso::with(['empresa', 'filial', 'setor', 'licenca']);

        if ($request->filled('hostname')) {
            $query->where('hostname', 'like', '%' . $request->hostname . '%');
        }

        if ($request->filled('colaborador')) {
            $query->where('colaborador', 'like', '%' . $request->colaborador . '%');
        }

        if ($request->filled('empresa_id')) {
            $query->where('empresa_id', $request->empresa_id);
        }

	if ($filialId) {
	    $query->where('filial_id', $filialId);
	}

	if ($setorId) {
	    $query->where('setor_id', $setorId);
	}

	if ($request->filled('licenca')) {
	    $query->whereHas('licenca', function ($q) use ($request) {
	        $q->where('codigo', 'like', '%' . $request->licenca . '%');
	    });
	}
        $recursos = $query->get();
        $empresas = Empresa::all();

	if ($request->filled('empresa_id')) {
	    $filiais = Filial::where('empresa_id', $request->empresa_id)->get();
	} else {
	    $filiais = collect();
	}

	if ($request->filled('empresa_id') && $filialId) {
	    $setores = Setor::where('empresa_id', $request->empresa_id)
	                    ->where('filial_id', $filialId)
	                    ->get();
	} else {
	    $setores = collect();
	}

        return view('recursos.index', compact('recursos', 'empresas', 'filiais', 'setores'));
    }

    public function create()
    {
        $empresas = Empresa::all();
	$filiais = Filial::all();
        $setores = Setor::all();
        $licencas = Licenca::whereDoesntHave('recurso')->get();
        return view('recursos.create', compact('empresas', 'filiais', 'setores', 'licencas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'hostname' => 'required|string',
            'colaborador' => 'required|string',
            'empresa_id' => 'required|exists:empresas,id',
	    'filial_id' => 'required|exists:filiais,id',
            'setor_id' => 'required|exists:setores,id',
            'licenca_id' => 'nullable|exists:licencas,id',
        ]);

        $recurso = Recurso::create($data);

        if ($request->filled('licenca_id')) {
            Licenca::where('id', $request->licenca_id)->update(['status' => 'vinculada']);
        }

        LogHelper::registrar('created', 'Recurso', 'Recurso cadastrado: ' . $recurso->hostname);

        return redirect()->route('recursos.index')->with('success', 'Recurso cadastrado com sucesso!');
    }

    public function show(Recurso $recurso)
    {
        return view('recursos.show', compact('recurso'));
    }

    public function edit(Recurso $recurso)
    {
        $empresas = Empresa::all();
	$filiais = Filial::all();
        $setores = Setor::all();
        $licencas = Licenca::whereDoesntHave('recurso')->orWhere('id', $recurso->licenca_id)->get();

        return view('recursos.edit', compact('recurso', 'empresas', 'filiais', 'setores', 'licencas'));
    }

    public function update(Request $request, Recurso $recurso)
    {
        $data = $request->validate([
            'hostname' => 'required|string',
            'colaborador' => 'required|string',
            'empresa_id' => 'required|exists:empresas,id',
	    'filial_id' => 'required|exists:filiais,id',
            'setor_id' => 'required|exists:setores,id',
            'licenca_id' => 'nullable|exists:licencas,id',
        ]);

        $recurso->update($data);
        LogHelper::registrar('updated', 'Recurso', 'Recurso atualizado: ' . $recurso->hostname);

        return redirect()->route('recursos.index')->with('success', 'Recurso atualizado com sucesso!');
    }

    public function destroy(Recurso $recurso)
    {
	if ($recurso->licenca_id) {
	    \App\Models\Licenca::where('id', $recurso->licenca_id)->update(['status' => 'disponivel']);
	}

        $recurso->delete();
        LogHelper::registrar('deleted', 'Recurso', 'Recurso excluído: ' . $recurso->hostname);

        return redirect()->route('recursos.index')->with('success', 'Recurso removido com sucesso!');
    }
    public function exportExcel(Request $request)
{
    $filters = $request->only(['hostname', 'colaborador', 'empresa_id', 'filial_id', 'setor_id', 'licenca']);
    // Remove valores inválidos
    foreach ($filters as $key => $value) {
        if (in_array($value, ['', 'Setor', 'Filial', 'Empresa'])) {
            unset($filters[$key]);
        }
    }
    return Excel::download(new RecursosExport($filters), 'recursos.xlsx');
}
    public function exportarPDF(Request $request)
    {
        $query = Recurso::with(['empresa', 'filial', 'setor', 'licenca']);

        if ($request->filled('hostname')) {
            $query->where('hostname', 'like', '%' . $request->hostname . '%');
        }

        if ($request->filled('colaborador')) {
            $query->where('colaborador', 'like', '%' . $request->colaborador . '%');
        }

        if ($request->filled('empresa_id')) {
            $query->where('empresa_id', $request->empresa_id);
        }

        if ($request->filled('filial_id')) {
            $query->where('filial_id', $request->filial_id);
        }

        if ($request->filled('setor_id')) {
            $query->where('setor_id', $request->setor_id);
        }

        if ($request->filled('licenca')) {
            $query->whereHas('licenca', function ($q) use ($request) {
                $q->where('codigo', 'like', '%' . $request->licenca . '%');
            });
        }

        $recursos = $query->get();

        $pdf = Pdf::loadView('recursos.pdf', compact('recursos'))->setPaper('a4', 'landscape');
        return $pdf->download('recursos.pdf');
    }
}
