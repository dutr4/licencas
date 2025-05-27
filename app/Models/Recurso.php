<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recurso extends Model
{
    use HasFactory;

    protected $fillable = [
        'hostname',
        'colaborador',
        'empresa_id',
	'filial_id',
        'setor_id',
        'licenca_id',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function filial()
    {
        return $this->belongsTo(Filial::class);
    }

    public function setor()
    {
        return $this->belongsTo(Setor::class);
    }

    public function licenca()
    {
        return $this->belongsTo(Licenca::class);
    }
}
