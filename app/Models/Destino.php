<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destino extends Model
{
    use HasFactory;
    protected $connection = 'oracle_primary';
    protected $table = 'destinos';
    protected $primaryKey = 'idDestino';
    protected $fillable = ['destino'];
}
