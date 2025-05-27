<?php

namespace App\Exports;

use App\Models\Recurso;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RecursosExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Recurso::with(['empresa', 'filial', 'setor', 'licenca']);

        if (!empty($this->filters['hostname'])) {
            $query->where('hostname', 'like', '%' . $this->filters['hostname'] . '%');
        }
        if (!empty($this->filters['colaborador'])) {
            $query->where('colaborador', 'like', '%' . $this->filters['colaborador'] . '%');
        }
        if (!empty($this->filters['empresa_id'])) {
            $query->where('empresa_id', $this->filters['empresa_id']);
        }
        if (!empty($this->filters['filial_id'])) {
            $query->where('filial_id', $this->filters['filial_id']);
        }
        if (!empty($this->filters['setor_id'])) {
            $query->where('setor_id', $this->filters['setor_id']);
        }
        if (!empty($this->filters['licenca'])) {
            $query->whereHas('licenca', function ($q) {
                $q->where('codigo', 'like', '%' . $this->filters['licenca'] . '%');
            });
        }

        return $query->get()->map(function ($recurso) {
            return [
                'Hostname' => $recurso->hostname,
                'Colaborador' => $recurso->colaborador,
                'Empresa' => $recurso->empresa->nome ?? '-',
                'Filial' => $recurso->filial->nome ?? '-',
                'Setor' => $recurso->setor->nome ?? '-',
                'Licença' => $recurso->licenca->codigo ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return ['Hostname', 'Colaborador', 'Empresa', 'Filial', 'Setor', 'Licença'];
    }
}
