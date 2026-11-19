<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    protected $connection = 'oracle_primary';
    protected $table = 'facturas';

    protected $primaryKey = 'idfactura';
    protected $fillable = [
        'cfdi',
        'importe',
        'idcomision',
        'rfcEmisor',
        'rfcReceptor',
        'dateFactura',
        'subtotal',
        'propina',
        'iva',
        'ish',
        'ieps',
        'retenIva',
        'retenIsh',
        'regimenReceptor',
        'regimenEmisor',
        'formaPago',
        'cp',
        'descripcion',
        'status',
        'id_user',
        'pdf_path',
        'id_factura_reemplazo',
        'cancelacion',
        'idtypefactura',
        'evidenciarequerida',
        'observaciones',
        'isr',
        'impuestos_locales',
        'otros',
        'tasa'
    ];

    protected $casts = [
        'date' => 'date',
        'dateFactura' => 'date',
        'importe' => 'float',
        'subtotal' => 'float',
        'propina' => 'float',
        'iva' => 'float',
        'ish' => 'float',
        'ieps' => 'float',
        'retenIva' => 'float',
        'retenIsh' => 'float',
    ];

    public function comision()
    {
        return $this->belongsTo(Comision::class, 'idcomision', 'idcomision');
    }

    public function setCfdiAttribute($value)
    {
        $this->attributes['cfdi'] = strtoupper($value);
    }
    public function getImporteAttribute($value)
    {
        return (float) $value;
    }
}
