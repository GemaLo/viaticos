<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .header { background-color: #28a745; color: white; padding: 10px; text-align: center; border-radius: 5px 5px 0 0; }
        .details-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .details-table th, .details-table td { border: 1px solid #eee; padding: 8px; text-align: left; }
        .details-table th { background-color: #f2f2f2; }
        .canceled-invoices { margin-top: 20px; border-top: 2px solid #dc3545; padding-top: 15px; }
        .invoice-item { border: 1px solid #dc3545; padding: 10px; margin-bottom: 10px; border-radius: 4px; background-color: #f8d7da; color: #721c24; }
        .footer { margin-top: 20px; font-size: 0.8em; color: #777; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Comisión Revisada</h1>
        </div>

        <p>Estimado(a) {{ $comision->personComision->user->nomusuario ?? 'Comisionado' }},</p>
        <p>Le informamos que su comisión ya se revisó completamente y se ha finalizado su comprobación. Enseguida se muestra un resumen de los detalles:</p>

        <h2>Detalle de la Comisión</h2>
        <table class="details-table">
            <tr><th>Folio</th><td>{{ $comision->comision }}</td></tr>
            <tr><th>Origen</th><td>{{ $comision->origen }}</td></tr>
            <tr><th>Destino</th><td>{{ $comision->destino }}</td></tr>
            <tr><th>Período</th><td>{{ \Carbon\Carbon::parse($comision->initialdate)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($comision->finaldate)->format('d/m/Y') }}</td></tr>
            <tr><th>Importe (Anticipo)</th><td>$ {{ number_format($comision->importe, 2) }}</td></tr>
            <tr><th>Objetivo</th><td>{{ $comision->objetives->objetive }}</td></tr>
        </table>

        @php
            $facturasCanceladas = $comision->facturas->filter(function ($factura) {
                return !empty($factura->evidenciarequerida) || !empty($factura->observaciones);
            });
        @endphp

        @if ($facturasCanceladas->isNotEmpty())
            <div class="canceled-invoices">
                <h2>Estimado(a)  **Comisionado**</h2>
                <p>Se le informa que la comisión con folio {{ $comision->modalidad->acronym}}_{{ $comision->comision }}_{{ $comision->yearcomision }} fue revisada por la DGGPR,
                    y se encontraron las siguientes inconsistencias, por favor dar seguimiento:</p>

                @foreach ($facturasCanceladas as $factura)
                    <div class="invoice-item">
                        <strong>CFDI:</strong> {{ $factura->cfdi ?? 'N/A' }}<br>
                        <strong>Importe:</strong> $ {{ number_format($factura->importe, 2) }}<br>
                        <strong>Descripción:</strong> {{ $factura->descripcion ?? 'Sin descripción' }}<br>
                        <br>
                        <strong>Motivo General (Código Cancelación):</strong> {{ $factura->cancelacion ?? 'N/A' }}<br>

                        @if (!empty($factura->observaciones))
                            <strong>Observaciones del Revisor:</strong> **{{ $factura->observaciones }}**<br>
                        @endif

                        @if (!empty($factura->evidenciarequerida))
                            <strong>Evidencia Requerida:</strong> **{{ $factura->evidenciarequerida }}**<br>
                        @endif

                        <br>
                        *Por favor, revise el sistema para adjuntar o aclarar la evidencia.*
                    </div>
                @endforeach
            </div>
        @else
            <p style="color: #28a745; font-weight: bold;">✅ No se encontraron facturas canceladas que requieran aclaración.</p>
        @endif


        <div class="content">
            <p>Puedes revisar el detalle completo en el sistema ubicado en el siguiente enlace: <a
                href="https://sspcsistemaviaticos.sspc.gob.mx/login">https://sspcsistemaviaticos.sspc.gob.mx/login</a></p>
        </div>

        <div class="footer">
            <p>Este es un correo automático, por favor no responder.</p>
        </div>
    </div>
</body>
</html>
