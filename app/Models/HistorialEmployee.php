<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialEmployee extends Model
{
    use HasFactory;

    protected $connection = 'oracle_primary';
    protected $table = 'historialEmployee';
    protected $primaryKey = 'idHistorialEmployee';

    protected $fillable = [
        'id_user',
        'origin',            
        'numero_anterior',    
        'numero_nuevo',       
        'active',             
    ];
}