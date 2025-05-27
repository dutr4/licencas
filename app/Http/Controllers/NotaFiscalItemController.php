<?php

namespace App\Http\Controllers;

use App\Models\NotaFiscal;
use App\Models\NotaFiscalItem;
use Illuminate\Http\Request;
use App\Helpers\LogHelper;

class NotaFiscalItemController extends Controller
{
    public function create(NotaFiscal $nota)
    {
        return view('notas.itens.create', compact('nota'));
    }

    public function store(Request $request, NotaFiscal $nota)
    {
        $request->validate([
            'descricao' => 'required|string',
            'quantidade' => 'required|integer|min:1',
        ]);

        $item = $nota->itens()->create($request->only(['descricao', 'quantidade']));
        LogHelper::registrar('created', 'Item da Nota', 'Item criado: ' . $item->descricao);

        return redirect()->route('notas.show', $nota)->with('success', 'Item adicionado com sucesso.');
    }

    public function destroy(NotaFiscalItem $item)
    {
        $nota = $item->notaFiscal;
        $item->delete();
        LogHelper::registrar('deleted', 'Item da Nota', 'Item excluído: ' . $item->descricao);

        return redirect()->route('notas.show', $nota)->with('success', 'Item removido com sucesso.');
    }
}
