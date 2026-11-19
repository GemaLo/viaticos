<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GastosSinComprobante extends Model
{
    use HasFactory;

    protected $connection = 'oracle_primary';
    protected $table = 'sincomprobantes';
    protected $primaryKey = 'idSinComprobante';

    protected $fillable = [
        'datepay',
        'conceptopago',
        'monto',
        'idcomision',
        'proof_file',
        'activo',
        'observaciones'
    ];

    public function comision()
    {
        return $this->belongsTo(Comision::class, 'idcomision');
    }

    public function setObservacionesAttribute($value)
    {
        $this->attributes['observaciones'] = strtoupper($value);
    }

}
