<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagadorUsuario extends Model
{
    use HasFactory;

    protected $table = 'pagador_usuarios';

    protected $fillable = [
        'user_id',
        'clave_ur',
        'pagador_habilitado',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
