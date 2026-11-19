<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    use HasFactory;
    protected $connection = 'oracle_primary';
    protected $table = 'files';
    protected $primaryKey = 'idFile';
    protected $fillable = ['name', 'idFactura'];
}
