<?php

namespace App\Exports;

use App\Models\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;

class LogsExport implements FromQuery, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = Log::with('user')->select('logs.*');

        if ($this->request->filled('user_id')) {
            $query->where('user_id', $this->request->user_id);
        }

        if ($this->request->filled('acao')) {
            $query->where('acao', $this->request->acao);
        }

        if ($this->request->filled('modulo')) {
            $query->where('modulo', $this->request->modulo);
        }

        if ($this->request->filled('data_inicio')) {
            $query->whereDate('created_at', '>=', $this->request->data_inicio);
        }

        if ($this->request->filled('data_fim')) {
            $query->whereDate('created_at', '<=', $this->request->data_fim);
        }

        if ($this->request->filled('detalhes')) {
            $query->where('detalhes', 'like', '%' . $this->request->detalhes . '%');
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Usuário',
            'Ação',
            'Módulo',
            'Detalhes',
            'Criado em',
            'Atualizado em',
        ];
    }
}
