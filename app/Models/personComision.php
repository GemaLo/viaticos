<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class personComision extends Model
{
    use HasFactory;

    protected $connection = 'oracle_primary';
    protected $table = 'personComision';
    protected $primaryKey = 'idpersoncomision';
    protected $fillable = ['cargo', 'nivel', 'rfc', 'curp', 'clabe', 'cuenta', 'banco', 'adscripcion', 'contacto', 'estado_cuenta_file', 'direccion','id_user'];

public function user() {
    return $this->belongsTo(User::class, 'id_user', 'id_user');
}
}
