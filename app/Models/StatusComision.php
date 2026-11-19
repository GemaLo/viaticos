<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusComision extends Model
{
    use HasFactory;

    protected $primaryKey = 'idStatusComision';
    protected $fillable = ['statuscomision', 'idModalidad', 'idType'];

    protected $connection = 'oracle_primary';
    protected $table = 'statuscomision';
}
