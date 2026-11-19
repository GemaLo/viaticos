<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonComision extends Model
{
    use HasFactory;
    protected $connection = 'oracle_primary';
    protected $table = 'personcomision';
    protected $primaryKey = 'idpersoncomision';
    protected $fillable = ['cargo', 'nivel', 'rfc', 'curp', 'clabe', 'cuenta', 'banco', 'estado_cuenta_file', 'adscripcion', 'direccion', 'id_user'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
    
    public function comisiones()
    {
        return $this->hasMany(Comision::class, 'idpersoncomision', 'idpersoncomision');
    }
}
