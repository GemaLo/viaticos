<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registros extends Model
{
    use HasFactory;

    protected $primaryKey = 'idmovimiento';
    protected $fillable = ['movimiento', 'fecha_movimiento', 'id_user', 'idcomision', 'idstatus'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function estatus()
    {
        return $this->belongsTo(StatusComision::class, 'idstatus', 'idstatuscomision');
    }

    public function getNombreCompletoAttribute()
    {
        if (!$this->usuario) {
            return 'Sistema';
        }

        return trim(sprintf(
            '%s %s %s',
            $this->usuario->nomusuario,
            $this->usuario->appusuario,
            $this->usuario->apmusuario ?? ''
        ));
    }
}
