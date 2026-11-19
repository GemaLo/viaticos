<h2 style="color: #333;">Nueva Comisión Para Validación</h2>

<div style="padding: 20px; border: 1px solid #000000">
    <p>Se ha registrado una nueva solicitud de comisión, conforme a lo siguiente, se le agradecerá llevar a cabo la revisión de los siguientes datos:</p>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
        <thead>
            <tr style="background-color: #007bff; color: black;">
                <th style="padding: 10px; text-align: left;">Dato</th>
                <th style="padding: 10px; text-align: left;">Información</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="padding: 8px; border: 1px solid #000000 font-weight: bold;">Comisión:</td>
                <td style="padding: 8px; border: 1px solid #000000"> {{ $comision->comision }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #000000 font-weight: bold;">Modalidad:</td>
                <td style="padding: 8px; border: 1px solid #000000">{{ $comision->modalidad?->modalidad ?? 'No especificada' }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #000000 font-weight: bold;">Comisionado:</td>
                <td style="padding: 8px; border: 1px solid #000000">{{ $infoUser->appusuario }} {{ $infoUser->apmusuario }} {{ $infoUser->nomusuario }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #000000 font-weight: bold;">Origen/Destino:</td>
                <td style="padding: 8px; border: 1px solid #000000">
                    {{ strtoupper("$comision->origen" . ' a ' . "$comision->destino") }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #000000 font-weight: bold;">Período:</td>
                <td style="padding: 8px; border: 1px solid #000000">
                    Del {{ $comision->initialdate }} al {{ $comision->finaldate }}
                    ({{ $comision->dayscompletes }} días)
                </td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #000000 font-weight: bold;">Importe Total:</td>
                <td style="padding: 8px; border: 1px solid #000000">${{ number_format($comision->importe, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <p>Puedes revisar el detalle completo en el sistema ubicado en el siguiente enlace: <a href="https://sspcsistemaviaticos.sspc.gob.mx/login">https://sspcsistemaviaticos.sspc.gob.mx/login</a></p>

</div>

<div style="margin-top: 20px; font-size: 0.8em; color: #777;">
    <p>Este es un correo automático, por favor no responder.</p>
</div>
