<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reasignacion extends Model
{
    use HasFactory;

    protected $connection = 'oracle_primary';
    protected $table = 'reasignaciones';
    protected $primaryKey = 'idreasignacion';

    protected $fillable = [
        'datereasignacioninicial',
        'datereasignacionfinal',
        'datereturn',
        'motivo',
        'motivoampliacion',
        'newimport',
        'returnimport',
        'idcomision', 
    ];

    public function setMotivoAttribute($value)
    {
        $this->attributes['MOTIVO'] = strtoupper($value);
    }

    public function comision()
    {
        return $this->belongsTo(Comision::class, 'idcomision', 'idcomision');
    }
}
