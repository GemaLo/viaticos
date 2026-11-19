<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoReintegro extends Model
{
    use HasFactory;
    protected $primaryKey = 'idTipoReintegro';
    protected $fillable = ['tipoReintegro', 'acronymReintegro'];
    protected $connection = 'oracle_primary';
    protected $table = 'tipoReintegro';
}
