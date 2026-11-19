<!DOCTYPE html>
<html>
<head>
    <title>Estatus de Comisiones</title>
</head>
<body style="font-family: Arial, sans-serif;">
    @if ($actionType === 'aprobacion')
        <h1 style="color: #28a745;">Comisión Aprobada</h1>
        <p>Se le informa que la comisión fue **aprobada por la UR**, y se encuentra en revisión por parte de la DGPPR.
</p>
    @elseif ($actionType === 'cancelacion')
        <h1 style="color: #dc3545;">Comisión Cancelada</h1>
        <p>Le informamos que su comisión ha sido cancelada, para cualquier duda e información puede recurrir a su
            Autorizador(Director de Unidad Responsable)</p>
    @endif
    <div class="content">
        @if ($actionType === 'cancelacion' && isset($justificacion))
            <p style="background-color: #000000; border-left: 4px solid #F87171; padding: 10px; margin: 15px 0;">
                Motivo de la Cancelación: {{ $justificacion }}
            </p>
        @endif
    <p>Puedes revisar el detalle completo en el sistema ubicado en el siguiente enlace: <a
            href="https://sspcsistemaviaticos.sspc.gob.mx/login">https://sspcsistemaviaticos.sspc.gob.mx/login</a></p>
</div>
<div style="margin-top: 20px; font-size: 0.8em; color: #777;">
    <p>Este es un correo automático, por favor no responder.</p>
</div>
