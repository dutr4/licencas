<?php

namespace App\Helpers;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class LogHelper
{
    public static function registrar(string $acao, string $modulo, string $detalhes = null): void
    {
        if (!Auth::check()) return;

        Log::create([
            'user_id' => Auth::id(),
            'acao'    => $acao,
            'modulo'  => $modulo,
            'detalhes'=> $detalhes,
        ]);
    }
}
