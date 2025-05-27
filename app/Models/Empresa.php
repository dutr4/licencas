<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Setor;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'divisao'];

    public function setores()
    {
        return $this->hasMany(Setor::class);
    }
    public function filiais()
    {
        return $this->hasMany(Filial::class);
    }

}
