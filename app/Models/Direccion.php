<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    use HasFactory;
    protected $primaryKey = 'iddireccion';
    protected $fillable = ['no_direccion', 'nombre_ur'];
}
