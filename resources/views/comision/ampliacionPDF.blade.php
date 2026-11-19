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
    </style>
</header>
<img src="{{ public_path('images/SSPC.png') }}" class="logo" width="150px">

<p class="dgpyp">{{ strtoupper($query->adscripcion) }}</p>
<p class="title">SOLICITUD DE VIATICOS Y PASAJES(AMPLIACIÓN)</p>
<hr style="border: 3px solid red; margin-top: 10px; margin-bottom: 20px;">

<table class="content">
    <tr>
        <th colspan="3" class="text-center-th"><span class="normal-text">{{ $query->adscripcion }}</span></th>
    </tr>
    <tr>
        <th colspan="3" class="text-center-th">OFICIO DE COMISION NACIONAL</th>
    </tr>
    <tr>
        <th>LUGAR Y FECHA: <span class="normal-text">CIUDAD DE MÉXICO {{ strtoupper(\Carbon\Carbon::parse($query->created_at)->translatedFormat('d-F-Y')) }}</span></th>
        <th colspan="2"> OFICIO DE COMISIÓN: <span class="normal-text">{{ $query->comision }}</span></th>
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
        <th> CARGO: <span class="normal-text">{{ $query->cargo }}</span></th>
        <th> NIVEL: <span class="normal-text">{{ $query->nivel }}</span></th>
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
        <th>CONTACTO: <span class="normal-text">{{ $query->contacto }}</span></th>
        <th>Número de comisión: <span
                class="normal-text">{{ $query->acronym }}_{{ $query->comision }}_{{ $query->yearcomision }}</span>
        </th>
        <th>PLACAS: <span class="normal-text">{{ $query->placas }}</span></th>
    </tr>
</table>

<table class="content">
    <tr>
        <th colspan="3">DATOS DE LA AMPLIACIÓN</th>
    </tr>
    <tr>
        <th>FECHA DE INICIO DE AMPLIACIÓN:
            <span
                class="normal-text">{{ strtoupper(\Carbon\Carbon::parse($query->datereasignacioninicial)->translatedFormat('d-F-Y')) }}</span>
        </th>
        <th>FECHA FINAL DE AMPLIACIÓN:
            <span
                class="normal-text">{{ strtoupper(\Carbon\Carbon::parse($query->datereasignacionfinal)->translatedFormat('d-F-Y')) }}</span>
        </th>
        @php
            $importeNuevo = $query->newimport;
            $formattedImporte = '$' . number_format($importeNuevo, 2, '.', ',');
        @endphp
        <th>NUEVO IMPORTE: <span class="normal-text">{{ $formattedImporte }}</span></th>
    </tr>
</table>
<table class="content">
    <tr>
        <th colspan="3">FIRMA DEL COMISIONADO: <span class="normal-text"><br><br><br><br></span></th>
    </tr>
</table>
<table class="content">
    <tr>
        <th colspan="3">
            <p class="normal-text-final">Me comprometo a realizar la comprobación de los gastos efectuados en la Comisión dentro de los 5 días hábiles siguientes
                al termino de la misma; en caso de no realizarlo, autorizo que se haga el cargo a mi cuenta bancaria de depósito.
            </p>
        </th>
    </tr>
</table>
