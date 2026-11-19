<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Sesion extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'sesion_id'; // Indica que la clave primaria es user_id
    public $incrementing = true; // Define si la clave es incremental
    protected $keyType = 'int'; // Especifica el tipo de la clave primaria


    use Notifiable;

    /**
     * La conexión de base de datos que este modelo usará.
     *
     * @var string
     */
    protected $connection = 'oracle_primary'; // Conexión específica

    /**
     * La tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'sesiones'; // Nombre de la tabla

    // Otros atributos del modelo (si los necesitas)

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'hora_entrada', 
        'hora_salida', 
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
    ];
}
