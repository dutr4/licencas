<?php

namespace App\Http\Controllers;

use App\Models\Licenca;
use App\Models\Empresa;
use App\Models\Filial;
use App\Models\Setor;
use App\Models\NotaFiscal;
use App\Models\NotaFiscalItem;
use Illuminate\Http\Request;
use App\Helpers\LogHelper;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\LicencasExport;

class LicencaController extends Controller
{
    public function index(Request $request)
{
    $query = Licenca::query();

    if ($request->filled('filtro_codigo')) {
        $query->where('codigo', 'like', '%' . $request->filtro_codigo . '%');
    }

    if ($request->filled('filtro_empresa')) {
        $query->where('empresa_id', $request->filtro_empresa);
    }

    if ($request->filled('filtro_filial')) {
        $query->where('filial_id', $request->filtro_filial);
    }

    if ($request->filled('filtro_setor')) {
        $query->where('setor_id', $request->filtro_setor);
    }

    if ($request->filled('filtro_nota_fiscal')) {
        $query->whereHas('notaFiscalItem.notaFiscal', function($q) use ($request) {
            $q->where('numero', 'like', '%' . $request->filtro_nota_fiscal . '%');
        });
    }

    if ($request->filled('filtro_item')) {
        $query->whereHas('notaFiscalItem', function($q) use ($request) {
            $q->where('descricao', 'like', '%' . $request->filtro_item . '%');
        });
    }

    if ($request->filled('filtro_chave')) {
        $query->where('chave', 'like', '%' . $request->filtro_chave . '%');
    }

    if ($request->filled('filtro_status')) {
        $query->where('status', $request->filtro_status);
    }

    $licencas = $query->paginate(10)->withQueryString();

    $empresas = Empresa::all();

    // ✅ Filiais só da empresa selecionada
    $filiais = collect();
    if ($request->filled('filtro_empresa')) {
        $filiais = Filial::where('empresa_id', $request->filtro_empresa)->get();
    }

    // ✅ Setores só da filial selecionada
    $setores = collect();
    if ($request->filled('filtro_filial')) {
        $setores = Setor::where('filial_id', $request->filtro_filial)->get();
    }

    return view('licencas.index', compact(
        'licencas',
        'empresas',
        'filiais',
        'setores'
    ));
}

    public function create()
    {
        $empresas = Empresa::all();
        $filiais = \App\Models\Filial::all();
        $setores = Setor::all();
        return view('licencas.create', compact('empresas', 'filiais', 'setores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'filial_id' => 'required|exists:filiais,id',
            'setor_id' => 'required|exists:setores,id',
            'nota_fiscal_item_id' => 'required|exists:nota_fiscal_itens,id',
            'chave' => 'nullable|string|max:255',
        ]);

        $ultimo = Licenca::max('id') ?? 0;
        $codigo = str_pad($ultimo + 1, 6, '0', STR_PAD_LEFT);

        $licenca = Licenca::create([
            'empresa_id' => $request->empresa_id,
	    'filial_id' => $request->filial_id,
            'setor_id' => $request->setor_id,
            'nota_fiscal_item_id' => $request->nota_fiscal_item_id,
            'chave' => $request->chave,
            'codigo' => $codigo,
        ]);
	LogHelper::registrar('created', 'Licença', 'Licença criada: Código ' . $licenca->codigo);

        return redirect()->route('licencas.index')->with('success', 'Licença cadastrada com sucesso.');
    }

    public function show(Licenca $licenca)
    {
        return view('licencas.show', compact('licenca'));
    }

    public function edit(Licenca $licenca)
    {
        $empresas = Empresa::all();
        $setores = Setor::all();
        return view('licencas.edit', compact('licenca', 'empresas', 'setores'));
    }

    public function update(Request $request, Licenca $licenca)
    {
        $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
	    'filial_id' => 'required|exists:filiais,id',
            'setor_id' => 'required|exists:setores,id',
            'nota_fiscal_item_id' => 'required|exists:nota_fiscal_itens,id',
            'chave' => 'nullable|string|max:255',
        ]);

	$licenca->update([
	    'empresa_id' => $request->empresa_id,
	    'filial_id' => $request->filial_id,
	    'setor_id' => $request->setor_id,
	    'nota_fiscal_item_id' => $request->nota_fiscal_item_id,
	    'chave' => $request->chave,
	]);
        LogHelper::registrar('updated', 'Licença', 'Licença atualizada: Código ' . $licenca->codigo);
        return redirect()->route('licencas.index')->with('success', 'Licença atualizada com sucesso.');
    }

    public function destroy(Licenca $licenca)
    {
        $licenca->delete();
        LogHelper::registrar('deleted', 'Licença', 'Licença excluída: Código ' . $licenca->codigo);
        return redirect()->route('licencas.index')->with('success', 'Licença excluída com sucesso.');
    }

    private function getFilteredLicencas(Request $request)
    {
        $query = Licenca::query();

        if ($request->filled('filtro_codigo')) {
            $query->where('codigo', 'like', '%' . $request->filtro_codigo . '%');
        }

        if ($request->filled('filtro_empresa')) {
            $query->where('empresa_id', $request->filtro_empresa);
        }

        if ($request->filled('filtro_filial')) {
            $query->where('filial_id', $request->filtro_filial);
        }

        if ($request->filled('filtro_setor')) {
            $query->where('setor_id', $request->filtro_setor);
        }

        if ($request->filled('filtro_nota_fiscal')) {
            $query->whereHas('notaFiscalItem.notaFiscal', function($q) use ($request) {
                $q->where('numero', 'like', '%' . $request->filtro_nota_fiscal . '%');
            });
        }

        if ($request->filled('filtro_item')) {
            $query->whereHas('notaFiscalItem', function($q) use ($request) {
                $q->where('descricao', 'like', '%' . $request->filtro_item . '%');
            });
        }

        if ($request->filled('filtro_chave')) {
            $query->where('chave', 'like', '%' . $request->filtro_chave . '%');
        }

        if ($request->filled('filtro_status')) {
            $query->where('status', $request->filtro_status);
        }

        return $query->get();
    }

    public function exportPdf(Request $request)
    {
        $licencas = $this->getFilteredLicencas($request);
        // Gera PDF com $licencas
        $pdf = Pdf::loadView('licencas.export_pdf', compact('licencas'));

        return $pdf->download('licencas.pdf');
    }

    public function exportExcel(Request $request)
    {
        $licencas = $this->getFilteredLicencas($request);
        return Excel::download(new LicencasExport($licencas), 'licencas.xlsx');
    }

}
