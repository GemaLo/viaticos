<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimientos extends Model
{
    use HasFactory;

    protected $primaryKey = 'idMovimiento';
    protected $fillable = ['movimiento', 'fecha_movimiento', 'id_user'];
}
