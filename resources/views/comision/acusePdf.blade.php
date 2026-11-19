@php
    $cancelacion = [3, 14];
    $inicial = [1, 16];
@endphp
<header>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 14px;
        }

        .fecha_lib {
            width: 70%;
            border: 0.0px solid white;
        }

        .dgpyp {
            text-align: right;
            font-size: 13px;
            font-weight: bold;
            color: #333;
        }

        .title {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 10px;
            color: #000;
        }

        .text,
        .subtitle {
            text-align: center;
            font-size: 12px;
        }

        .content {
            border-collapse: collapse;
            border: 0.5px solid black;
            width: 100%;
            margin-bottom: 15px;
            /* REGLA CLAVE PARA EVITAR LA DIVISIÓN */
            page-break-inside: avoid;
        }

        .content th,
        .content td {
            font-size: 12px;
            border: 0.5px solid black;
            vertical-align: middle;
            padding: 8px;
            text-align: left;
        }

        .content th {
            font-weight: bold;
            background-color: #f9f9f9;
        }

        .content tr:first-child th {
            background-color: #e6e6e6;
            font-size: 12px;
            text-align: center;
            text-transform: uppercase;
        }

        .normal-text {
            font-weight: normal;
        }

        .normal-text-final {
            font-weight: normal;
            font-size: 8px;
        }

        .content .text-center-th {
            text-align: center;
        }

        .textTitle {
            border-collapse: collapse;
            border: 0.5px solid black;
            vertical-align: middle;
            font-size: 12px;
            text-align: center;
            font-weight: bold;
        }

        .table,
        td,
        tr {
            border-collapse: collapse;
            border: 0.5px solid black;
            vertical-align: middle;
            text-align: center;
        }

        .logo {
            border: 0px;
            vertical-align: middle;
            margin-bottom: 10px;
        }

        .no-break-section {
            page-break-inside: avoid;
        }
    </style>
</header>
<img src="{{ public_path('images/SSPC.png') }}" class="logo" width="150px">

<p class="dgpyp">{{ strtoupper($query->nombre_ur) }}</p>
<p class="title">OFICIO DE COMISIÓN</p>
<hr style="border: 3px solid red; margin-top: 10px; margin-bottom: 20px;">

<table class="content">
    <tr>
        <th colspan="3" class="text-center-th"><span class="normal-text">{{ $query->adscripcion }}</span></th>
    </tr>
    <tr>
        <th colspan="3" class="text-center-th">OFICIO DE COMISION</th>
    </tr>
    <tr>
    <tr>
        <th colspan="2">FECHA DE AUTORIZACIÓN: <span class="normal-text">{{ $query->dateautorized ? strtoupper(\Carbon\Carbon::parse($query->dateautorized)->locale('es')->isoFormat('D [de] MMMM [de] YYYY')) : $query->lugar }}</span></th>
        <th>UNIDAD QUE COMISIONA ESTE FOLIO:
            <span class="normal-text">{{ $query->oficio ?? 'N/A' }}</span>
        </th>
    </tr>
    </tr>
</table>

<table class="content">
    <tr>
        <th colspan="3">DATOS DEL COMISIONADO</th>
    </tr>
    <tr>
        <th colspan="3">NOMBRE COMPLETO: <span class="normal-text">{{ $query->nombre_completo }}</span></th>
    </tr>
    <tr>
        <th colspan="2"> CARGO: <span class="normal-text">{{ !empty($query->puestocomision) ? $query->puestocomision : $query->cargo }}</span></th>
        <th> CARGO: <span class="normal-text">{{ !empty($query->nivelcomision) ? $query->nivelcomision : $query->nivel }}</span></th>
    </tr>
    <tr>
        <th>CURP: <span class="normal-text">{{ $query->curp }}</span></th>
        <th>RFC: <span class="normal-text">{{ $query->rfc }}</span></th>
        <th>NÚMERO DE EMPLEADO: <span class="normal-text">{{ $query->numempleado }}</span></th>
    </tr>
    <tr>
        <th>CUENTA: <span class="normal-text">{{ $query->cuenta }}</span></th>
        <th>BANCO: <span class="normal-text">{{ $query->banco }}</span></th>
        <th>CLABE: <span class="normal-text">{{ $query->clabe }}</span></th>
    </tr>
    <tr>
        <th colspan="">ADSCRIPCIÓN: <span class="normal-text">{{ $query->unidad }} -
                {{ $query->adscripcion }}</span></th>
        <th colspan="2">Número de comisión: <span
                class="normal-text">{{ $query->acronym }}_{{ $query->comision }}_{{ $query->yearcomision }}</span>
        </th>
    </tr>
</table>

<table class="content">
    <tr>
        <th colspan="3">DATOS DE LA COMISIÓN OFICIAL</th>
    </tr>
    <tr>
        <th>TIPO DE COMISIÓN: <span class="normal-text">{{ $query->typecomision }}</span></th>
        <th>PARTIDA DEL PASAJE: <span class="normal-text">{{ $query->partidapasaje }}</span></th>
        <th>PARTIDA DE VIATICO: <span class="normal-text">{{ $query->partidaviatico }}</span></th>
    </tr>
    <tr>
        <th>TRANSPORTE: <span class="normal-text">{{ $query->transport }}</span></th>
        <th>ORIGEN: <span class="normal-text"> {{ $query->origen }} </span></th>
        <th>DESTINO: <span class="normal-text">{{ $query->destino }}</span></th>
    </tr>
    <tr>
        @php
            $importe = $query->importe;
            $formattedImporte = '$' . number_format($importe, 2, '.', ',');
            $completeImport = $query->completeimport;
            $formattedCompleteImport = '$' . number_format($completeImport, 2, '.', ',');
            $mediumImport = $query->mediumimport;
            $formattedMediumImport = '$' . number_format($mediumImport, 2, '.', ',');
        @endphp
        <th>
            Importe Completo:
            <span class="normal-text">$ {{ number_format($importes_ley->completo, 2) }}</span>
        </th>
        <th>
            Importe por medio día:
            <span class="normal-text">$ {{ number_format($importes_ley->medio, 2) }}</span>
        </th>

        <th>IMPORTE: <span class="normal-text">{{ $formattedImporte }}</span></th>
    </tr>
    <tr>
        <th>FECHA DE INICIO:
            <span
                class="normal-text">{{ strtoupper(\Carbon\Carbon::parse($query->initialdate)->translatedFormat('d-F-Y')) }}</span>
        </th>
        <th colspan="2">FECHA FINAL:
            <span
                class="normal-text">{{ strtoupper(\Carbon\Carbon::parse($query->finaldate)->translatedFormat('d-F-Y')) }}</span>
        </th>
    </tr>
    <tr>
        <th>DIAS COMPLETOS: <span class="normal-text">{{ $query->dayscompletes }}</span></th>
        <th>MEDIOS DÍAS: <span class="normal-text">{{ $query->hours }}</span></th>
        <th>CONTACTO DE JEFE INMEDIATO: <br><span class="normal-text">{{ $validador->contacto ?? 'No cuenta con contacto registrado'}}</span></th>
    <tr>
        <th colspan="3">OBJETIVO: <span class="normal-text">{{ $query->objetive }}</span></th>

    </tr>

</table>

@if ($reasignaciones->isNotEmpty())
    @foreach ($reasignaciones as $reasignacion)
        <table class="content">
            <tr>
                <th colspan="{{ ($reasignacion->newimport > ($comision->importe ?? 0)) ? '2' : '3' }}" style="background-color: #ffcc00; text-align: center;">
                    DETALLES DE MOVIMIENTO
                </th>

                @if ($reasignacion->newimport > ($comision->importe ?? 0))
                    <th>NUEVO IMPORTE ASIGNADO:
                        @php
                            $newImporte = max(0, $reasignacion->newimport ?? 0);
                            $formattedNewImporte = '$' . number_format($newImporte, 2, '.', ',');
                        @endphp
                        <span class="normal-text">{{ $formattedNewImporte }}</span>
                    </th>
                @endif
            </tr>

            @if ($reasignacion->datereasignacioninicial || $reasignacion->datereasignacionfinal)
                <tr>
                    <th>NUEVA FECHA INICIAL:
                        <span class="normal-text">
                            @if ($reasignacion->datereasignacioninicial)
                                {{ strtoupper(\Carbon\Carbon::parse($reasignacion->datereasignacioninicial)->translatedFormat('d-F-Y')) }}
                            @else
                                N/A
                            @endif
                        </span>
                    </th>
                    <th>NUEVA FECHA FINAL:
                        <span class="normal-text">
                            @if ($reasignacion->datereasignacionfinal)
                                {{ strtoupper(\Carbon\Carbon::parse($reasignacion->datereasignacionfinal)->translatedFormat('d-F-Y')) }}
                            @else
                                N/A
                            @endif
                        </span>
                    </th>

                    <th>
                        @if ($reasignacion->newimport > ($comision->importe ?? 0))
                            MOTIVO DE AMPLIACIÓN DE PLAZO:
                        @else
                            MOTIVO DE MOVIMIENTO:<br>
                        @endif
                        <span class="normal-text">
                            {{ $reasignacion->motivoampliacion ?? $reasignacion->motivo }}
                        </span>
                    </th>
                </tr>
            @else
                <tr>
                    <th colspan="3">MOTIVO: <span class="normal-text">{{ $reasignacion->motivo }}</span></th>
                </tr>
            @endif

            @if ($reasignacion->datereturn)
                <tr>
                    <th>FECHA DE REGRESO:
                        <span class="normal-text">
                            {{ strtoupper(\Carbon\Carbon::parse($reasignacion->datereturn)->translatedFormat('d-F-Y')) }}
                        </span>
                    </th>
                    <th colspan="2">COMPROBANTE: <span class="normal-text">{{ $reasignacion->comprobante ?? 'N/A' }}</span></th>
                </tr>
            @endif
        </table>
    @endforeach
@else
    <table class="content">
        <tr>
            <th colspan="3" style="background-color: #f0f0f0; text-align: center;">
                NO EXISTEN REASIGNACIONES ASOCIADAS A ESTA COMISIÓN.
            </th>
        </tr>
    </table>
@endif
<div class="no-break-section">
    @if (in_array($query->idstatuscomision, $inicial))
        <table class="content">
            <tr>
                <th colspan="3">COMISIÓN PENDIENTE DE AUTORIZACIÓN</th>
            </tr>
        </table>
    @else
        @switch($query->idstatuscomision)
            @case(in_array($query->idstatuscomision, $cancelacion))
                <table class="content">
                    <tr>
                        <th colspan="3">LA COMISIÓN HA SIDO CANCELADA POR PARTE DE SU VALIDADOR</th>
                    </tr>
                    <tr>
                        <th colspan="3">JUSTIFICACIÓN: <span class="normal-text">{{ $query->justificacion }}</span></th>
                    </tr>
                </table>
            @break

            @default
                <table class="content" style="table-layout: fixed; width: 100%;">
                    <tr>
                        <th style="width: 50%; text-align: center;">FIRMA DEL COMISIONADO</th>
                        <th style="width: 50%; text-align: center;">FIRMA DE QUIEN AUTORIZA</th>
                    </tr>
                    <tr>
                        <td style="height: 120px; text-align: center; vertical-align: bottom; padding-bottom: 15px;">
                            <div style="border-top: 0.5px solid black; width: 80%; margin: 0 auto;"></div>
                            <span class="normal-text">
                                <strong>{{ $query->nombre_completo }}</strong><br>
                                <small>{{ $query->cargo_comisionado }}</small>
                            </span>
                        </td>
                        <td style="height: 120px; text-align: center; vertical-align: bottom; padding-bottom: 15px;">
                            <br><br><br>
                            <div style="border-top: 0.5px solid black; width: 80%; margin: 0 auto;"></div>
                            <span class="normal-text">
                                @if (!empty($cargoData))
                                <strong><b>{{ $validador->nombre_completo }}</b></strong><br>
                                <small>{{ $cargoData->cargo }}</small>
                                @else
                                                                <strong><b>{{ $validador->nombre_completo }}</b></strong><br>
                                <small>{{ $validador->cargo }}</small>

                                @endif
                            </span>
                        </td>
                    </tr>
                </table>
            @break
        @endswitch
    @endif

    <table class="content">
        <tr>
            <th colspan="3">
                <p class="normal-text-final">El titular de la Unidad Responsable que suscribe, manifiesta, bajo protesta
                    de
                    decir verdad, que la
                    presente comisión es estrictamente necesaria para dar cumplimiento con los objetivos
                    institucionales,
                    los programas o las funciones conferidas a la dependencia y que esta comisión no puede ser efectuada
                    por
                    el personal de las oficinas o representaciones locales o regionales que tiene la dependencia en el
                    lugar
                    de la comisión.
                </p>
            </th>
        </tr>
    </table>
</div>
