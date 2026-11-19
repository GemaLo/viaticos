<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objetive extends Model
{
    use HasFactory;
    protected $primaryKey = 'idObjetive';
    protected $fillable = ['objetive', 'ur'];

    protected $casts = [
    'ur' => 'array'
    ];
}
