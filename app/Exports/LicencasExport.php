<?php

namespace App\Exports;

use App\Models\Licenca;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class LicencasExport implements FromCollection, WithHeadings
{
    protected $licencas;

    public function __construct(Collection $licencas)
    {
        $this->licencas = $licencas;
    }

    public function collection()
    {
        return $this->licencas->map(function ($licenca) {
            return [
                'Código' => $licenca->codigo,
                'Empresa' => $licenca->empresa->nome ?? '-',
                'Filial' => $licenca->filial->nome ?? '-',
                'Setor' => $licenca->setor->nome ?? '-',
                'Nota Fiscal' => $licenca->notaFiscalItem->notaFiscal->numero ?? '-',
                'Item' => $licenca->notaFiscalItem->descricao ?? '-',
                'Chave' => $licenca->chave,
                'Status' => $licenca->status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Código',
            'Empresa',
            'Filial',
            'Setor',
            'Nota Fiscal',
            'Item',
            'Chave',
            'Status'
        ];
    }
}

