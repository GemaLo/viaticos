<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiario extends Model
{
    use HasFactory;
    protected $connection = 'oracle_primary';
    protected $table = 'beneficiarios';

    protected $primaryKey = 'idBeneficiario';
    protected $fillable = ['rfc','razon_social', 'activo', 'fecha_alta', 'id_ramo','codigo_beneficiario'];
}
