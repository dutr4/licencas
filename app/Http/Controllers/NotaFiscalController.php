<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\NotaFiscal;
use App\Models\Filial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Helpers\LogHelper;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NotasExport;
use Barryvdh\DomPDF\Facade\Pdf;

class NotaFiscalController extends Controller
{
    public function index(Request $request)
    {
        $query = NotaFiscal::with(['empresa', 'filial']);

        if ($request->filled('filtro_filial')) {
            $query->where('filial_id', $request->filtro_filial);
        }
	if ($request->filled('filtro_empresa')) {
	    $query->where('empresa_id', $request->filtro_empresa);
	}
	if ($request->filled('filtro_numero')) {
	    $query->where('numero', 'like', '%' . $request->filtro_numero . '%');
	}
	if ($request->filled('filtro_data_inicio')) {
	    $query->whereDate('data_emissao', '>=', $request->filtro_data_inicio);
	}

	if ($request->filled('filtro_data_fim')) {
	    $query->whereDate('data_emissao', '<=', $request->filtro_data_fim);
	}

        $notas = $query->latest()->get();
        $filiais = \App\Models\Filial::all();
	$empresas = \App\Models\Empresa::all();


	return view('notas.index', compact('notas', 'filiais', 'empresas'));
    }

    public function create()
    {
        $empresas = Empresa::all();
	$filiais = Filial::all();
	return view('notas.create', compact('empresas', 'filiais'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero' => 'required',
            'empresa_id' => 'required|exists:empresas,id',
	    'filial_id' => 'required|exists:filiais,id',
            'data_emissao' => 'required|date',
            'arquivo' => 'nullable|file|mimes:pdf',
        ]);

        $data = $request->only(['numero', 'empresa_id', 'filial_id', 'data_emissao']);

        if ($request->hasFile('arquivo')) {
	     $nomeArquivo = $request->file('arquivo')->getClientOriginalName();
        $request->file('arquivo')->storeAs('notas', $nomeArquivo, 'public');
        $data['arquivo'] = $nomeArquivo; // Salva só o nome
        }

        $nota = NotaFiscal::create($data);
	LogHelper::registrar('created', 'Nota Fiscal', 'Nota criada: ' . $nota->numero);

        return redirect()->route('notas.index')->with('success', 'Nota fiscal cadastrada com sucesso.');
    }

    public function show(NotaFiscal $nota)
    {
        $nota->load('itens');
        return view('notas.show', compact('nota'));
    }

    public function edit(NotaFiscal $nota)
    {
        $empresas = Empresa::all();
	$filiais = Filial::where('empresa_id', $nota->empresa_id)->get();
        return view('notas.edit', compact('nota', 'empresas', 'filiais'));
    }

    public function update(Request $request, NotaFiscal $nota)
    {
        $request->validate([
            'numero' => 'required',
            'empresa_id' => 'required|exists:empresas,id',
            'filial_id' => 'required|exists:filiais,id',
            'data_emissao' => 'required|date',
            'arquivo' => 'nullable|file|mimes:pdf',
        ]);

	$data = $request->only(['numero', 'empresa_id', 'filial_id', 'data_emissao']);

        if ($request->hasFile('arquivo')) {
	        $nomeArquivo = $request->file('arquivo')->getClientOriginalName();
	        $request->file('arquivo')->storeAs('notas', $nomeArquivo, 'public');
	        $data['arquivo'] = $nomeArquivo; // Salva só o nome
        }

        $nota->update($data);
        LogHelper::registrar('updated', 'Nota Fiscal', 'Nota alterada: ' . $nota->numero);
        return redirect()->route('notas.index')->with('success', 'Nota fiscal atualizada com sucesso.');
    }

    public function destroy(NotaFiscal $nota)
    {
	    // Exclui o arquivo se existir
	if ($nota->arquivo && Storage::disk('public')->exists('notas/'.$nota->arquivo)) {
    	Storage::disk('public')->delete('notas/'.$nota->arquivo);
        }

        $nota->delete();
        LogHelper::registrar('deleted', 'Nota Fiscal', 'Nota excluída: ' . $notaFiscal->numero);
        return redirect()->route('notas.index')->with('success', 'Nota fiscal removida com sucesso.');
    }

    private function filtrarNotas($request)
    {
        $query = NotaFiscal::with(['empresa', 'filial']);

        if ($request->filled('filtro_filial')) {
            $query->where('filial_id', $request->filtro_filial);
        }

        if ($request->filled('filtro_empresa')) {
            $query->where('empresa_id', $request->filtro_empresa);
        }

        if ($request->filled('filtro_numero')) {
            $query->where('numero', 'like', '%' . $request->filtro_numero . '%');
        }

        return $query;
    }

    public function exportExcel(Request $request)
    {
	$query = NotaFiscal::with(['empresa', 'filial', 'itens']);

        if ($request->filled('filtro_filial')) {
            $query->where('filial_id', $request->filtro_filial);
        }

        if ($request->filled('filtro_empresa')) {
            $query->where('empresa_id', $request->filtro_empresa);
        }

        if ($request->filled('filtro_numero')) {
            $query->where('numero', 'like', '%' . $request->filtro_numero . '%');
        }
	if ($request->filled('filtro_data_inicio')) {
	    $query->whereDate('data_emissao', '>=', $request->filtro_data_inicio);
	}

	if ($request->filled('filtro_data_fim')) {
	    $query->whereDate('data_emissao', '<=', $request->filtro_data_fim);
	}
        $notas = $query->get();

        return Excel::download(new \App\Exports\NotasExport($notas), 'notas.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $query = NotaFiscal::with(['empresa', 'filial', 'itens']);

        if ($request->filled('filtro_filial')) {
            $query->where('filial_id', $request->filtro_filial);
        }

        if ($request->filled('filtro_empresa')) {
            $query->where('empresa_id', $request->filtro_empresa);
        }

        if ($request->filled('filtro_numero')) {
            $query->where('numero', 'like', '%' . $request->filtro_numero . '%');
        }
	if ($request->filled('filtro_data_inicio')) {
	    $query->whereDate('data_emissao', '>=', $request->filtro_data_inicio);
	}

	if ($request->filled('filtro_data_fim')) {
    	$query->whereDate('data_emissao', '<=', $request->filtro_data_fim);
	}
        $notas = $query->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('notas.pdf', compact('notas'));

        return $pdf->download('notas.pdf');
    }
}
