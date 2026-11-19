<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comision extends Model
{
    use HasFactory;
    protected $connection = 'oracle_primary';
    protected $table = 'comisiones';
    protected $primaryKey = 'idcomision';

    protected $fillable = [
        'comision',
        'folio',
        'yearcomision',
        'oficio',
        'idpersoncomision',
        'idtypecomision',
        'idstatuscomision',
        'placas',
        'origen',
        'destino',
        'partidapasaje',
        'partidaviatico',
        'lugar',
        'initialdate',
        'finaldate',
        'idobjetive',
        'idmodalidad',
        'id_transport',
        'dayscompletes',
        'hours',
        'importe',
        'autorizacion',
        'dateEndComision',
        'validador',
        'clc',
        'justificacion',
        'documentacionfisica',
        'deposito',
        'nota',
        'clasificado',
        'nivelComision',
        'puestoComision',
        'dateComprobation',
        'status',
        'dateAutorized',
        // Nuevos campos para el control de revisiones
        'rev1_estatus',
        'rev1_fecha',
        'rev2_estatus',
        'rev2_fecha',
    ];

    protected $casts = [
        'importe' => 'float',
        'rev1_estatus' => 'boolean',
        'rev1_fecha' => 'datetime',
        'rev2_estatus' => 'boolean',
        'rev2_fecha' => 'datetime',
    ];

    public function setOficioAttribute($value)
    {
        $this->attributes['oficio'] = strtoupper($value);
    }

    public function setDestinoAttribute($value)
    {
        $this->attributes['destino'] = strtoupper($value);
    }

    public function setObjetiveAttribute($value)
    {
        $this->attributes['objetive'] = strtoupper($value);
    }

    public function setJustificacionAttribute($value)
    {
        $this->attributes['justificacion'] = strtoupper($value);
    }

    public function setNotaAttribute($value)
    {
        $this->attributes['nota'] = strtoupper($value);
    }

    public function personComision()
    {
        return $this->belongsTo(PersonComision::class, 'idpersoncomision', 'idpersoncomision');
    }

    public function transport()
    {
        return $this->belongsTo(Transport::class, 'id_transport', 'id_transport');
    }

    public function statusComision()
    {
        return $this->belongsTo(StatusComision::class, 'idstatuscomision', 'idstatuscomision');
    }

    public function reasignacion()
    {
        return $this->hasOne(Reasignacion::class, 'idcomision', 'idcomision')
            ->latest('idreasignacion');
    }

    public function modalidad()
    {
        return $this->belongsTo(Modalidad::class, 'idmodalidad', 'idmodalidad');
    }

    public function tipo()
    {
        return $this->belongsTo(TypeComision::class, 'idtypecomision', 'idtypecomision');
    }

    public function reasignaciones()
    {
        return $this->hasMany(Reasignacion::class, 'idcomision', 'idcomision');
    }

    public function facturas()
    {
        return $this->hasMany(Factura::class, 'idcomision', 'idcomision');
    }

    public function objetives()
    {
        return $this->belongsTo(Objetive::class, 'idobjetive', 'idobjetive');
    }

    public function reintegros()
    {
        return $this->hasOne(Reintegro::class, 'idcomision', 'idcomision');
    }

    public function partidaViaticoRel()
    {
        return $this->belongsTo(Partida::class, 'partidaviatico', 'idpartida');
    }

    public function partidaPasajeRel()
    {
        return $this->belongsTo(Partida::class, 'partidapasaje', 'idpartida');
    }

    public function sinComprobantes()
    {
        return $this->hasMany(GastosSinComprobante::class, 'idcomision', 'idcomision');
    }

    public function usuarioValidador()
    {
        return $this->belongsTo(\App\Models\User::class, 'validador', 'numEmpleado');
    }

    public function test()
    {
        return $this->belongsTo(\App\Models\Direccion::class, 'no_direccion', 'oficio');
    }

    public function getFlujoAtributo()
    {
        $nombres = [
            1 => 'Solicitud',
            2 => 'Autorizada UR',
            4 => 'Revisión DGPPRI',
            5 => 'Revisada Pago',
            7 => 'En Dispersión',
            8 => 'Pagada',
            9 => 'En Curso',
            10 => 'En Comprobación',
            11 => 'Ampliación',
            12 => 'Revisión Comp.',
            13 => 'Validada',
            26 => 'Cierre',
            15 => 'Finalizada',
            16 => 'Solicitud Dev.',
            17 => 'Autorizada UR',
            19 => 'En Curso',
            20 => 'En Comprobación' // ...etc
        ];

        $idsFlujo = (int)$this->idmodalidad === 1
            ? [1, 2, 4, 5, 7, 8, 9, 10, 11, 12, 13, 26, 15]
            : [16, 17, 19, 20, 21, 22, 25, 23, 24];

        return collect($idsFlujo)->map(function ($id) use ($nombres) {
            return [
                'id' => $id,
                'nombre' => $nombres[$id] ?? 'Estatus ' . $id
            ];
        });
    }
    public function validadorUser()
{
    return $this->belongsTo(User::class, 'validador', 'numEmpleado');
}
}