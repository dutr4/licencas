<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NotasExport implements FromCollection, WithHeadings
{
    protected $notas;

    public function __construct($notas)
    {
        $this->notas = $notas;
    }

    public function collection()
    {
        $rows = collect();

        foreach ($this->notas as $nota) {
            if ($nota->itens->isEmpty()) {
                $rows->push([
                    'Número' => $nota->numero,
                    'Empresa' => $nota->empresa->nome ?? '-',
                    'Filial' => $nota->filial->nome ?? '-',
                    'Data de Emissão' => $nota->data_emissao,
                    'Item' => '-',
                    'Quantidade' => '-',
                    'Descrição do Item' => '-',
                ]);
            } else {
                foreach ($nota->itens as $item) {
                    $rows->push([
                        'Número' => $nota->numero,
                        'Empresa' => $nota->empresa->nome ?? '-',
                        'Filial' => $nota->filial->nome ?? '-',
                        'Data de Emissão' => $nota->data_emissao,
                        'Item' => $item->id, // ou outro campo identificador do item
                        'Quantidade' => $item->quantidade ?? '-',
                        'Descrição do Item' => $item->descricao ?? '-',
                    ]);
                }
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Número',
            'Empresa',
            'Filial',
            'Data de Emissão',
            'Item',
            'Quantidade',
            'Descrição do Item',
        ];
    }
}
