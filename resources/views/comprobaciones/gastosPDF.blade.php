<header>
    <style>
        /* Estilos Generales */
        body {
            font-family: 'Helvetica', Arial, sans-serif; /* ¡Fuente cambiada a Helvetica! */
            font-size: 11pt;
            margin: 40pt;
            color: #444;
            line-height: 1.5;
        }

        /* Contenedor del Encabezado (Membrete) */
        .header-grid {
            width: 100%;
            display: table;
            margin-bottom: 25pt;
        }

        .header-col {
            display: table-cell;
            vertical-align: top;
            padding: 0;
        }

        /* Título Principal */
        .header-title {
            text-align: center;
            font-size: 18pt;
            font-weight: bold;
            color: #000;
            text-transform: uppercase;
            padding-bottom: 5pt;
            border-bottom: 1pt solid #444;
            margin-top: 10pt;
        }

        /* Folio y Fecha */
        .header-meta {
            text-align: right;
            font-size: 10pt;
            font-weight: bold;
            padding-right: 10pt;
        }

        /* Bloque de Información de la Comisión */
        .info-comision {
            margin-top: 15pt;
            margin-bottom: 25pt;
            padding: 12pt;
            border: 1pt solid #ccc;
            background-color: #f7f7f7;
            font-size: 10pt;
            border-radius: 3pt;
        }
        .info-comision p {
            margin: 3pt 0;
            line-height: 1.4;
            font-weight: bold;
        }
        .info-comision span {
            font-weight: normal;
        }

        /* Tabla de Detalles */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40pt;
            font-size: 10pt;
        }
        .data-table th, .data-table td {
            border: 1pt solid #000;
            padding: 8pt 10pt;
            text-align: left;
            vertical-align: middle;
        }
        .data-table thead th {
            background-color: #e9e9e9;
            color: #000;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
        }
        .data-table tbody td:nth-child(1) {
            width: 15%;
            text-align: center;
        }
        .data-table tbody td:nth-child(3) {
            width: 20%;
            text-align: right;
            font-weight: bold;
            color: #000;
        }
        .data-table tbody tr:nth-child(even) {
            background-color: #fcfcfc;
        }
        .data-table tbody tr:hover {
            background-color: #f0f0f0;
        }

        /* Fila de Total (Footer de tabla) */
        .total-row th {
            background-color: #cccccc;
            color: #000;
            text-align: right;
            font-size: 12pt;
            font-weight: bold;
            padding: 10pt;
        }
        .total-row th:first-child {
            text-align: right;
            padding-right: 20pt;
        }
        .total-row th:last-child {
            text-align: right;
            width: 20%;
        }

        /* Firmas: Usar tabla para estabilidad en impresión/PDF */
        .signature-table {
            margin-top: 60pt;
            width: 100%;
            border: none;
        }
        .signature-table td {
            border: none;
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 0 10pt;
            height: 120pt;
        }
        .signature-line {
            height: 1pt;
            background-color: #000;
            margin: 60pt auto 5pt auto;
            width: 80%;
        }
        .firma-label {
            font-size: 9pt;
        }
        .firma-label strong {
            font-size: 10pt;
            display: block;
            margin-bottom: 2pt;
            text-transform: uppercase;
        }
        .firma-details {
            font-size: 9pt;
            line-height: 1.4;
        }
    </style>
</header>
<body>

    <div class="header-grid">
        <div class="header-col" style="width: 50%;">
            <p class="header-meta" style="text-align: left; padding-left: 10pt;">
                Ciudad de México a {{ date('d') }} de {{ date('M') }} de {{ date('Y') }}.
            </p>
        </div>
        <div class="header-col" style="width: 50%;">
            <p class="header-meta">
                Folio:  {{ $query->acronym }}_{{ $query->comision }}_{{ $query->yearcomision }}
            </p>
        </div>
    </div>

    <div class="header-title">
        Relación de gastos sin comprobantes fiscales
    </div>

    <div class="info-comision">
        <p>
            UNIDAD RESPONSABLE (UR): <span>{{ $query->adscripcion }}</span><br>
            PERIODO DE COMISIÓN: <span>{{ date('d/m/Y', strtotime($query->initialdate)) }} al {{ date('d/m/Y', strtotime($query->finaldate)) }}</span>
        </p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 15%;">FECHA<br>(DD/MM/AA)</th>
                <th>DESCRIPCIÓN DEL GASTO</th>
                <th style="width: 20%;">IMPORTE</th>
            </tr>
        </thead>
        <tbody>
            @php $totalGastos = 0; @endphp
            @forelse ($gastos as $gasto)
                <tr>
                    <td>{{ date('d/m/y', strtotime($gasto->datepay)) }}</td>
                    <td>{{ $gasto->conceptopago }}</td>
                    <td>$ {{ number_format($gasto->monto, 2) }}</td>
                </tr>
                @php $totalGastos += $gasto->monto; @endphp
            @empty
                <tr>
                    <td colspan="3" style="text-align: center; font-style: italic; color: #777; padding: 15pt;">
                        No hay gastos sin comprobante registrados para esta comisión.
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <th colspan="2">TOTAL A COMPROBAR:</th>
                <th>$ {{ number_format($totalGastos, 2) }}</th>
            </tr>
        </tfoot>
    </table>

    <table class="signature-table">
        <tr>
            <td>
                <div class="signature-line"></div>
                <div class="firma-label">
                    <strong>Comisionado</strong>
                    <div class="firma-details">
                        {{ strtoupper($query->nombre_completo) }}<br>
                        NÚMERO DE EMPLEADO: {{ $query->numempleado }}<br>
                        CARGO: {{ $query->cargo_comisionado }}
                    </div>
                </div>
            </td>
            <td>
                <div class="signature-line"></div>
                <div class="firma-label">
                    <strong>Autorizador</strong>
                    <div class="firma-details">
                    <strong>{{ $validador->nombre_completo }}</strong><br>
                    <small>{{ $validador->cargo }}</small>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</body>
