<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .header { background-color: #28a745; color: white; padding: 10px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { padding: 15px; border: 1px solid #f0f0f0; border-radius: 4px; margin-top: 15px; }
        .footer { margin-top: 20px; font-size: 0.8em; color: #777; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="color: #ffffff; margin: 0;">Comisión Validada por parte de DGPPR</h1>
        </div>

        <div class="content">
            <p>Estimado(a) {{ $comisionado->nomusuario ?? 'Comisionado' }} (Folio: {{ $comision->comision ?? 'N/A' }}),</p>
            <p>Se le informa que la comisión con folio {{ $comision->modalidad->acronym}}_{{ $comision->comision }}_{{ $comision->yearcomision }} fue validada por la DGGPR,
                una vez aprobada por la UR se podrá descargar el <b>FORMATO DE COMPROBACIÓN</b> y continuar con los trámites correspondientes.</p>

            <p>Puedes revisar el detalle completo en el sistema ubicado en el siguiente enlace: <a
                href="https://sspcsistemaviaticos.sspc.gob.mx/login">https://sspcsistemaviaticos.sspc.gob.mx/login</a>.</p>
        </div>

        <div class="footer">
            <p>Este es un correo automático, por favor no responder.</p>
        </div>
    </div>
</body>
</html>
