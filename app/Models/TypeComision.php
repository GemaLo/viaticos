<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeComision extends Model
{
    use HasFactory;

    protected $primaryKey = 'idTypeComision';
    protected $fillable = ['typeComision', 'acronym'];

    protected $connection = 'oracle_primary';
    protected $table = 'typeComision';
}
