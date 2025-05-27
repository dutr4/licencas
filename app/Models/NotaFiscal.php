<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaFiscal extends Model
{
    use HasFactory;

    protected $table = 'notas_fiscais';

    protected $fillable = [
        'numero',
        'empresa_id',
	'filial_id',
        'data_emissao',
        'arquivo',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function itens()
    {
        return $this->hasMany(NotaFiscalItem::class);
    }
    public function filial()
    {
        return $this->belongsTo(Filial::class);
    }

}
