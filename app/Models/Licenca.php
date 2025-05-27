<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Licenca extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
	'filial_id',
        'setor_id',
        'nota_fiscal_item_id',
        'chave',
        'codigo',
        'status',
    ];

    public function empresa()
    {
        return $this->belongsTo(\App\Models\Empresa::class);
    }

    public function setor()
    {
        return $this->belongsTo(\App\Models\Setor::class);
    }

    public function notaFiscalItem()
    {
        return $this->belongsTo(\App\Models\NotaFiscalItem::class);
    }
    public function recurso()
    {
        return $this->hasOne(Recurso::class);
    }
    public function filial()
    {
        return $this->belongsTo(Filial::class);
    }
}
