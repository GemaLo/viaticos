<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modalidad extends Model
{
    use HasFactory;

    protected $primaryKey = 'idModalidad';
    protected $fillable = ['modalidad', 'acronym'];

    protected $connection = 'oracle_primary';
    protected $table = 'modalidades';
}
