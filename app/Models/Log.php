<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'acao',
        'modulo',
        'detalhes',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
