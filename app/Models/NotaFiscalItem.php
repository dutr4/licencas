<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaFiscalItem extends Model
{
    use HasFactory;
    protected $table = 'nota_fiscal_itens';

    protected $fillable = [
        'nota_fiscal_id',
        'descricao',
        'quantidade',
    ];

    public function notaFiscal()
    {
        return $this->belongsTo(\App\Models\NotaFiscal::class);
    }
    public function licencas()
    {
        return $this->hasMany(Licenca::class, 'nota_fiscal_item_id');
    }

}
