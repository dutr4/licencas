<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\Request;
use App\Helpers\LogHelper;

class EmpresaController extends Controller
{
    public function index()
    {
        $empresas = Empresa::all();
        return view('empresas.index', compact('empresas'));
    }

    public function getFiliais($empresaId)
    {
        $filiais = \App\Models\Filial::where('empresa_id', $empresaId)->get(['id', 'nome']);
        return response()->json($filiais);
    }

    public function create()
    {
        return view('empresas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|unique:empresas,nome',
            'divisao' => 'required|in:Logística,Comércio,Passageiros,Holding',
        ]);
	$empresa = Empresa::create($request->only('nome', 'divisao'));
	LogHelper::registrar('created', 'Empresa', 'Empresa criada: ' . $empresa->nome);

        return redirect()->route('empresas.index')->with('success', 'Empresa criada com sucesso!');
    }

    public function edit(Empresa $empresa)
    {
        return view('empresas.edit', compact('empresa'));
    }

    public function update(Request $request, Empresa $empresa)
    {
        $request->validate([
            'nome' => 'required|unique:empresas,nome,' . $empresa->id,
            'divisao' => 'required|in:Logística,Comércio,Passageiros,Holding',
        ]);
        $empresa->update($request->only('nome', 'divisao'));
        LogHelper::registrar('updated', 'Empresa', 'Empresa atualizada: ' . $empresa->nome);
        return redirect()->route('empresas.index')->with('success', 'Empresa atualizada com sucesso!');
    }

    public function destroy(Empresa $empresa)
    {
        $empresa->delete();
	LogHelper::registrar('deleted', 'Empresa', 'Empresa excluída: ' . $empresa->nome);
        return redirect()->route('empresas.index')->with('success', 'Empresa excluída com sucesso!');
    }
}
