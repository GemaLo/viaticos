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
            font-size: 9;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 8;
            color: #000;
        }

        .text,
        .subtitle {
            text-align: center;
            font-size: 8;
        }

        .content {
            border-collapse: collapse;
            border: 0.5px solid black;
            width: 100%;
            margin-bottom: 15px;
        }

        .content th,
        .content td {
            font-size: 8;
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
            font-size: 9;
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
            font-size: 9;
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
            margin-bottom: 8;
        }
    </style>
</header>
<img src="{{ public_path('images/SSPC.png') }}" class="logo" width="150px">

<p class="dgpyp">{{ strtoupper($query->nombre_ur) }}</p>
<p class="title">FORMATO DE COMPROBACIÓN DE VIATICOS Y PASAJES</p>
<hr style="border: 3px solid red; margin-top: 8; margin-bottom: 20px;">

<table class="content">
    <tr>
        <th colspan="3" class="text-center-th"><span class="normal-text">{{ $query->adscripcion }}</span></th>
    </tr>
    <tr>
        <th colspan="3" class="text-center-th">FORMATO DE COMPROBACIÓN DE VIATICOS Y PASAJES</th>
    </tr>
    <tr>
        <th colspan="2">FECHA DE COMPROBACIÓN: <span
                class="normal-text">{{ $query->datecomprobation ? strtoupper(\Carbon\Carbon::parse($query->datecomprobation)->locale('es')->isoFormat('D [de] MMMM [de] YYYY')) : $query->lugar }}</span>
        </th>

        <th>UNIDAD QUE COMISIONA ESTE FOLIO:
            <span class="normal-text">{{ $query->oficio ?? 'N/A' }}</span>
        </th>
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
        <th> CORREO: <span class="normal-text">{{ $query->email }}</span></th>
        <th> CARGO: <span
                class="normal-text">{{ !empty($query->puestocomision) ? $query->puestocomision : $query->cargo }}</span>
        </th>
        <th> CARGO: <span
                class="normal-text">{{ !empty($query->nivelcomision) ? $query->nivelcomision : $query->nivel }}</span>
        </th>
    </tr>
    <tr>
        <th>NÚMERO DE EMPLEADO: <span class="normal-text">{{ $query->numempleado }}</span></th>
        <th>PARTIDA DEL PASAJE: <span class="normal-text">{{ $query->partidapasaje }}</span></th>
        <th>PARTIDA DE VIATICO: <span class="normal-text">{{ $query->partidaviatico }}</span></th>
    </tr>
    <tr>
        <th>ORIGEN: <span class="normal-text"> {{ $query->origen }} </span></th>
        <th colspan="2">DESTINO: <span class="normal-text">{{ $query->destino }}</span></th>
    </tr>
    <tr>
        <th colspan="2">ADSCRIPCIÓN: <span class="normal-text">{{ $query->unidad }} -
                {{ $query->adscripcion }}</span></th>


        <th>Número de comisión:
            @if ($query->autorizacion != 1)
                <br><span class="normal-text">Comisión pendiente de autorización, folio de seguimiento:
                    <b>{{ $query->folio }}</b></span>
            @else
                <span
                    class="normal-text">{{ $query->acronym }}_{{ $query->comision }}_{{ $query->yearcomision }}</span>
            @endif
        </th>
    </tr>
</table>

<table class="content">
    <tr>
        <th colspan="3">DATOS DE LA COMISIÓN POR AUTORIZAR</th>
    </tr>
    <tr>
        <th>TIPO DE COMISIÓN: <span class="normal-text">{{ $query->typecomision }}</span></th>
        <th>PARTIDA DEL PASAJE: <span class="normal-text">{{ $query->partidapasaje }}</span></th>
        <th>PARTIDA DE VIATICO: <span class="normal-text">{{ $query->partidaviatico }}</span></th>
    </tr>
    <tr>
        <th>FECHA DE INICIO:
            <span
                class="normal-text">{{ strtoupper(\Carbon\Carbon::parse($query->initialdate)->translatedFormat('d-F-Y')) }}</span>
        </th>
        <th>FECHA FINAL:
            <span
                class="normal-text">{{ strtoupper(\Carbon\Carbon::parse($query->finaldate)->translatedFormat('d-F-Y')) }}</span>
        </th>
        @php
            $importe = $query->importe;
            $formattedImporte = '$' . number_format($importe, 2, '.', ',');
        @endphp
        <th>IMPORTE: <span class="normal-text">{{ $formattedImporte }}</span></th>
    </tr>
    <tr>
        <th>DIAS COMPLETOS: <span class="normal-text">{{ $query->dayscompletes }}</span></th>
        <th>MEDIOS DÍAS: <span class="normal-text">{{ $query->hours }}</span></th>
        <th>CONTACTO DE JEFE INMEDIATO: <br><span
                class="normal-text">{{ $validador->contacto ?? 'No cuenta con contacto registrado' }}</span></th>
    </tr>
    <tr>
        <th colspan="3">NOTA: <span class="normal-text">{{ $query->nota }}</span></th>
    </tr>
</table>
@php
    $categorias = [
        '1' => 'HOSPEDAJE',
        '2' => 'INTERNACIONAL',
        '3' => 'ALIMENTOS',
        '4' => 'TRANSPORTE FORANEO',
        '5' => 'TRANSPORTE LOCAL',
        '6' => 'HOSPEDAJE Y ALIMENTOS',
        '7' => 'PEAJES',
    ];
    $total_comprobado = 0;
@endphp

<table class="content">
    <tr>
        <th colspan="3">COMPROBACIÓN</th>
    </tr>

    @foreach ($categorias as $key => $titulo)
        @if ($comprobaciones->has($key) && $comprobaciones[$key]->count() > 0)
            <tr>
                <th colspan="3">{{ $titulo }}</th>
            </tr>
            <tr>
                <th>CONCEPTO</th>
                <th>FACTURA</th>
                <th>MONTO</th>
            </tr>

            @foreach ($comprobaciones[$key] as $comprobacion)
                <tr>
                    <td class="normal-text">{{ $comprobacion->descripcion }}</td>
                    <td class="normal-text">{{ $comprobacion->cfdi }}</td>

                    @php
                        $monto_formateado = '$' . number_format($comprobacion->importe, 2, '.', ',');
                        $total_comprobado += $comprobacion->importe; // Sumar las facturas
                    @endphp
                    <td class="normal-text" style="text-align: right;">{{ $monto_formateado }}</td>
                </tr>
            @endforeach
        @endif
    @endforeach

    @if ($gastos->count() > 0)
        <tr>
            <th colspan="3">GASTOS SIN COMPROBANTE</th>
        </tr>
        <tr>
            <th>CONCEPTO</th>
            <th>FECHA</th>
            <th>MONTO</th>
        </tr>
        @foreach ($gastos as $gasto)
            <tr>
                <td class="normal-text">{{ $gasto->conceptopago }}</td>
                <td class="normal-text">
                    @php
                        $fecha_gasto = \Carbon\Carbon::parse($gasto->datepay)->translatedFormat('d-m-Y');
                    @endphp
                    {{ $fecha_gasto }}
                </td>
                @php
                    $monto_formateado_gasto = '$' . number_format($gasto->monto, 2, '.', ',');
                    $total_comprobado += $gasto->monto;
                @endphp
                <td class="normal-text" style="text-align: right;">{{ $monto_formateado_gasto }}</td>
            </tr>
        @endforeach
    @endif
    <tr>
        <th colspan="2" style="text-align: right;">TOTAL COMPROBADO:</th>
        <th style="text-align: right;">${{ number_format($total_comprobado, 2, '.', ',') }}</th>
    </tr>


    @if ($query->modalidad == 'ANTICIPADA')
        @php
            $montoReintegro = (float) ($query->monto_reintegro ?? 0);
            $esSaldoAFavor = $montoReintegro < 0;
            $etiqueta = $esSaldoAFavor ? 'SALDO A FAVOR DEL COMISIONADO:' : 'REINTEGRO:';
            $montoFormateado = '$' . number_format(abs($montoReintegro), 2, '.', ',');
        @endphp
        <tr>
            <th colspan="2" style="text-align: right;">{{ $etiqueta }}</th>
            <th style="text-align: right;">{{ $montoFormateado }}</th>
        </tr>
    @endif
</table>

</table>
<table class="content">
    <tr>
        <th>
            <p class="normal-text-final">Declaro bajo protesta de decir verdad que los datos de comprobación que
                presento son verídicos
            </p>
            FIRMA DEL COMISIONADO: <span
                class="normal-text"><br><br><br><br><b>{{ $query->nombre_completo }}</b></span><br><small>{{ $query->cargo_comisionado }}</small>
        </th>
        <th style="height: 120px; text-align: center; vertical-align: bottom; padding-bottom: 15px;">
            <br><br><br>
            <br><br>FIRMA DEL AUTORIZADOR: <span class="normal-text"><br><br><br><br><br></b>
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
        </th>
    </tr>
</table>
