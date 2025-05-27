<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Empresa;

class Filial extends Model
{
    use HasFactory;

    protected $table = 'filiais';
    protected $fillable = ['nome', 'empresa_id'];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
