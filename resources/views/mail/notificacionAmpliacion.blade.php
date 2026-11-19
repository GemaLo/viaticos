<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .header { background-color: #007bff; color: white; padding: 15px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { padding: 20px; border: 1px solid #f0f0f0; border-radius: 0 0 8px 8px; margin-top: 0; background-color: #f9f9f9; }
        .details { margin-top: 15px; border-left: 5px solid #007bff; padding-left: 15px; background-color: #e9f0ff; padding: 10px; border-radius: 4px; }
        .footer { margin-top: 20px; font-size: 0.8em; color: #777; text-align: center; }
        strong { font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="color: #ffffff; margin: 0; font-size: 1.5em;">✅ Ampliación de Comisión Aprobada</h1>
        </div>

        <div class="content">
            <p>Estimado(a) <b>{{ $comisionado->nomusuario ?? 'Comisionado' }}</b>, (Folio: {{ $comision->comision ?? 'N/A' }})</p>

            <p>Se le informa que la ampliación solicitada a su comisión con folio {{ $comision->modalidad->acronym}}_{{ $comision->comision }}_{{ $comision->yearcomision }} fue **aprobada y registrada** en sistema; lo anterior, para los trámites correspondientes.</p>

            <div class="details">
                <p><strong>Detalles de la Ampliación:</strong></p>
                <ul>
                    <li><strong>Motivo de la Ampliación:</strong> {{ $motivoAmpliacion }}</li>
                    <li><strong>Fecha Final Anterior:</strong> {{ \Carbon\Carbon::parse($comision->finaldate)->format('d/m/Y') }}</li>
                    <li><strong>Nueva Fecha Final:</strong> {{ \Carbon\Carbon::parse($fechaFinalNueva)->format('d/m/Y') }}</li>
                    <li><strong>Importe Adicional Calculado:</strong> ${{ number_format($importeCalculadoAmpliacion, 2) }}</li>
                    <li><strong>Importe Total Final:</strong> ${{ number_format($comision->importe, 2) }}</li>
                </ul>
            </div>

            <p>Por favor, ingrese al sistema para revisar los detalles completos en el siguiente enlace: <a href="https://sspcsistemaviaticos.sspc.gob.mx/login">https://sspcsistemaviaticos.sspc.gob.mx/login</a></p>

        </div>

        <div class="footer">
            <p>Este es un correo automático, por favor no responder.</p>
        </div>
    </div>
</body>
</html>
