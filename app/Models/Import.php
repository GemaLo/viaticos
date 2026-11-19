<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    use HasFactory;
    protected $connection = 'oracle_primary';
    protected $table = 'imports';
    protected $primaryKey = 'id_import';
    protected $fillable = ['completeImport', 'mediumImport', 'personNivel'];
}
