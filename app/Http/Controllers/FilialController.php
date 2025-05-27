<?php

namespace App\Http\Controllers;

use App\Models\Filial;
use App\Models\Empresa;
use Illuminate\Http\Request;

class FilialController extends Controller
{
    public function index(Request $request)
    {
        $query = Filial::with('empresa');

        if ($request->filled('filtro_empresa')) {
            $query->where('empresa_id', $request->filtro_empresa);
        }

        if ($request->filled('filtro_nome')) {
            $query->where('nome', 'like', '%' . $request->filtro_nome . '%');
        }

        $filiais = $query->get();
        $empresas = \App\Models\Empresa::all();

        return view('filiais.index', compact('filiais', 'empresas'));
    }

    public function create()
    {
        $empresas = Empresa::all();
        return view('filiais.create', compact('empresas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'empresa_id' => 'required|exists:empresas,id',
        ]);

        Filial::create($request->all());

        return redirect()->route('filiais.index')->with('success', 'Filial criada com sucesso.');
    }

    public function edit($id)
    {
        $filial = \App\Models\Filial::findOrFail($id);
        $empresas = \App\Models\Empresa::all();
        return view('filiais.edit', compact('filial', 'empresas'));
    }

public function update(Request $request, Filial $filial)
{
    $filial->nome = $request->nome;
    $filial->empresa_id = $request->empresa_id;
    $filial->save();

    return redirect()->route('filiais.index')->with('success', 'Filial atualizada com sucesso.');
}
}
