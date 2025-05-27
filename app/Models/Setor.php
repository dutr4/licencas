<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Empresa;
use App\Models\Filial;

class Setor extends Model
{
    use HasFactory;

    protected $table = 'setores';
    protected $fillable = ['nome', 'empresa_id', 'filial_id'];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
    public function filial()
    {
        return $this->belongsTo(Filial::class);
    }

}
