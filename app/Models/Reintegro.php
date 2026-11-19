<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reintegro extends Model
{
    use HasFactory;

    protected $primaryKey = 'idReintegro';
    protected $fillable = ['idComision', 'idTipoReintegro', 'monto', 'date', 'comprobante', 'idDeposito'];
    protected $connection = 'oracle_primary';
    protected $table = 'reintegros';
}
