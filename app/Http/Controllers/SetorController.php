<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Setor;
use App\Models\Empresa;
use App\Models\Filial;
use Illuminate\Http\Request;
use App\Helpers\LogHelper;

class SetorController extends Controller
{
    public function index(Request $request)
    {
        $empresas = Empresa::all();

        $filiais = collect(); // inicia vazio
        if ($request->filled('empresa_id')) {
            $filiais = Filial::where('empresa_id', $request->empresa_id)->get();
        }

        $setores = Setor::with(['empresa', 'filial'])
            ->when($request->empresa_id, fn($q) => $q->where('empresa_id', $request->empresa_id))
            ->when($request->filial_id, fn($q) => $q->where('filial_id', $request->filial_id))
            ->get();

        return view('setores.index', compact('setores', 'empresas', 'filiais'));
    }

    public function create()
    {
        $empresas = Empresa::all();
        return view('setores.create', compact('empresas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'empresa_id' => 'required|exists:empresas,id',
        ]);

	$setor = Setor::create($request->only('nome', 'empresa_id', 'filial_id'));
        LogHelper::registrar('created', 'Setor', 'Setor criado: ' . $setor->nome);
        return redirect()->route('setores.index')->with('success', 'Setor criado com sucesso!');
    }

    public function edit(Setor $setor)
    {
        $empresas = Empresa::all();
        $filiais = Filial::where('empresa_id', $setor->empresa_id)->get();

        return view('setores.edit', compact('setor', 'empresas', 'filiais'));
    }

    public function update(Request $request, Setor $setor)
    {
        $request->validate([
            'nome' => 'required',
            'empresa_id' => 'required|exists:empresas,id',
	    'filial_id' => 'required|exists:filiais,id',
        ]);

        $setor->update($request->only('nome', 'empresa_id', 'filial_id'));
        LogHelper::registrar('updated', 'Setor', 'Setor atualizado: ' . $setor->nome);
        return redirect()->route('setores.index')->with('success', 'Setor atualizado com sucesso!');
    }

    public function destroy(Setor $setor)
    {
        $setor->delete();
        LogHelper::registrar('deleted', 'Setor', 'Setor excluído: ' . $setor->nome);
        return redirect()->route('setores.index')->with('success', 'Setor excluído com sucesso!');
    }
}
