<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\NotaFiscalItem;
use App\Models\Filial;
use App\Models\Setor;
use App\Models\Licenca;
use App\Http\Controllers\Api\FilialController;
use App\Http\Controllers\Api\SetorController;
use App\Http\Controllers\Api\NotaFiscalItemController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/itens-nota/{empresa}', function (Request $request, $empresaId) {
    $filialId = $request->query('filial_id');

    $query = NotaFiscalItem::with('notaFiscal')
        ->whereHas('notaFiscal', function ($q) use ($empresaId, $filialId) {
            $q->where('empresa_id', $empresaId);
            if ($filialId) {
                $q->where('filial_id', $filialId);
            }
        })
        ->whereDoesntHave('licencas') // pega só itens sem licença vinculada
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'nome' => $item->descricao,
                'nf' => $item->notaFiscal->numero,
            ];
        });

    return response()->json($query);
});
Route::get('/filiais', function (Request $request) {
    $empresaId = $request->query('empresa_id');
    if (!$empresaId) {
        return response()->json([]);
    }
    $filiais = Filial::where('empresa_id', $empresaId)->get();
    return response()->json($filiais);
});

Route::get('/setores', function (Request $request) {
    $empresaId = $request->query('empresa_id');
    $filialId = $request->query('filial_id');

    if (!$empresaId || !$filialId) {
        return response()->json([]);
    }

    $setores = Setor::where('empresa_id', $empresaId)->where('filial_id', $filialId)->get();
    return response()->json($setores);
});

Route::get('/nota-fiscal-itens', function (Request $request) {
    $empresaId = $request->query('empresa_id');
    $filialId = $request->query('filial_id');

    $itens = NotaFiscalItem::with('notaFiscal')
        ->whereHas('notaFiscal', function ($q) use ($empresaId, $filialId) {
            $q->where('empresa_id', $empresaId)
              ->where('filial_id', $filialId);
        })
        ->leftJoin('licencas', 'licencas.nota_fiscal_item_id', '=', 'nota_fiscal_itens.id')
        ->select('nota_fiscal_itens.*')
        ->selectRaw('COUNT(licencas.id) AS licencas_vinculadas')
        ->groupBy('nota_fiscal_itens.id')
        ->havingRaw('licencas_vinculadas < nota_fiscal_itens.quantidade')
        ->get()
        ->map(function ($item) {
            $item->quantidade_disponivel = $item->quantidade - $item->licencas_vinculadas;
            return [
                'id' => $item->id,
                'nome' => $item->descricao,
                'nf' => $item->notaFiscal->numero,
                'quantidade_disponivel' => $item->quantidade_disponivel,
            ];
        });

    return $itens;
});

Route::get('/licencas-disponiveis', function (Request $request) {
    $empresaId = $request->query('empresa_id');
    $filialId = $request->query('filial_id');

    if (!$empresaId || !$filialId) {
        return response()->json([]);
    }

    $licencas = Licenca::where('empresa_id', $empresaId)
        ->where('filial_id', $filialId)
        ->where('status', 'disponivel')
        ->with('setor')
        ->get()
        ->map(function ($licenca) {
            return [
                'id' => $licenca->id,
                'codigo' => $licenca->codigo,
                'setor_nome' => $licenca->setor->nome ?? '-',
            ];
        });

    return response()->json($licencas);
});
