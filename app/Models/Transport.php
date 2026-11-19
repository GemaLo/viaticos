<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transport extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_transport';
    protected $fillable = ['transport', 'idtypetransport'];
    protected $connection = 'oracle_primary';
    protected $table = 'transports';
}
