<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'id_user';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $connection = 'oracle_primary';
    protected $table = 'users';

    protected $fillable = ['numEmpleado', 'nomUsuario', 'appUsuario', 'apmUsuario', 'email', 'password', 'must_change_password','active', 'cargo',
                            'contacto', 'unidad', 'idtype', 'idsubcargo', 'created_by', 'direcciones', 'procedencia', 'importcomplete', 'restringido'];
    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'idArea' => 'string',
        'numEmpleado' => 'string',
        'must_change_password' => 'boolean',
        'direcciones' => 'array',
        'ur' => 'array'
    ];

    public function userType()
    {
        return $this->belongsTo(TypeUser::class, 'idtype', 'idtype');
    }

public function personComision()
{
    return $this->belongsTo(PersonComision::class, 'idpersoncomision', 'idpersoncomision');
}

    public function setNomUsuarioAttribute($value)
    {
        $this->attributes['nomUsuario'] = strtoupper($value);
    }
    public function setAppUsuarioAttribute($value)
    {
        $this->attributes['appUsuario'] = strtoupper($value);
    }
    public function setApmUsuarioAttribute($value)
    {
        $this->attributes['apmUsuario'] = strtoupper($value);
    }
    public function setCargoAttribute($value)
    {
        $this->attributes['cargo'] = strtoupper($value);
    }
    public function setProcedenciaAttribute($value)
    {
        $this->attributes['procedencia'] = strtoupper($value);
    }
    public function getCargoUsuarioAttribute()
{
    return $this->attributes['cargo'] ?? null;
}
}
