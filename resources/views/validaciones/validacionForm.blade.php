@extends('layouts.master')
@section('title', 'Comprobaciones')

@section('vendor-style')
    @vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
    <style>
        .map-container {
            margin-bottom: 20px;
            margin-top: 15px;
        }

        .lineamiento {
            list-style-type: disc;
            margin-top: 20px;

        }

        .map-div {
            height: 300px;
            width: 100%;
            border: 1px solid #ccc;
            display: none;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        textarea,
        select {
            padding: 8px;
            width: 100%;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-check {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }

        .form-check-input {
            margin-right: 8px;
        }

        .amount-display {
            font-size: 1.3em;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 15px;
            text-align: center;
        }

        .amount-value {
            color: #28a745;
        }

        .amount-value.negative {
            color: #dc3545;
        }

        .amount-value.total-original {
            color: #6c757d;
        }

        .amount-value.accumulated {
            color: #ffc107;
        }

        .dropzone-container {
            display: flex;
            gap: 20px;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .dropzone-box {
            flex: 1;
        }
    </style>
@endsection

@section('vendor-script')
    @vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection

@section('page-script')
    @vite(['resources/js/app.js', 'resources/assets/js/dashboards-analytics.js'])
@endsection

@section('content')
    <style>
        .dropzone {
            border: 2px dashed #cbd5e0;
            border-radius: 0.5rem;
            background-color: #f8fafc;
            padding: 2.5rem;
            text-align: center;
            font-size: 1.125rem;
            color: #4a5568;
            cursor: pointer;
        }

        .dropzone.dz-drag-hover {
            border-color: #6366f1;
            background-color: #eef2ff;
        }

        .dropzone .dz-message {
            margin: 0;
        }

        .dropzone .dz-preview .dz-details {
            opacity: 1;
        }
    </style>
    <div id="mainContent" class="main flex-grow p-10 ml-40 transition-all duration-300">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <h1 class="mb-4 display-4">Validación de facturas.</h1>
                            @if ($message = Session::get('success'))
                                <div class="alert alert-success col-md-12" role="alert">{{ $message }}
                                </div>
                            @endif
                            @if ($message = Session::get('warning'))
                                <div class="alert alert-warning col-md-12" role="alert">{{ $message }}
                                </div>
                            @endif
                            @if ($query->idstatuscomision == 5)
                                <div class="d-flex justify-content-end mt-4">
                                    <div class="d-flex justify-content-end mt-4">
                                        <a href="{{ route('layoutSicop') }}" class="btn btn-success" id="btnPasarPago"
                                            style="display: none;">
                                            Descarga de Layout(Facturas)
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="d-flex justify-content-end mt-4">
                                    <div class="d-flex justify-content-end mt-4">
                                        <a href="{{ route('procesarPago', ['idComision' => $idComision]) }}"
                                            class="btn btn-primary" id="btnPasarPago" style="display: none;">
                                            Aprobar Comisión
                                        </a>
                                    </div>
                                </div>
                            @endif



                            <p>Nombre del Usuario: <b>{{ $query->personComision->user->nomusuario }}
                                    {{ $query->personComision->user->appusuario }}
                                    {{ $query->personComision->user->apmusuario }}</b></p>
                            <p class="amount-display">El importe total a comprobar en esta comisión es de: <span
                                    class="amount-value total-original">${{ number_format($query->importe, 2) }}</span></p>
                            <p class="amount-display" id="importeAcumuladoLabel">Importe acumulado: <span
                                    class="amount-value accumulated">${{ number_format($facturasAcumuladas, 2) }}</span></p>
                            <p class="amount-display" id="importeRestanteLabel">Saldo Restante: <span
                                    class="amount-value">${{ number_format($query->importe - $facturasAcumuladas, 2) }}</span>
                            </p>
                            <input type="hidden" id="currentCommissionId" value="{{ $idComision }}">
                            <table id="facturas-table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID Factura</th>
                                        <th>UUID</th>
                                        <th>Importe</th>
                                        <th>Fecha Factura</th>
                                        <th>Descripción</th>
                                        <th>Estado de la Factura (administrador)</th>
                                        <th>Validación SAT</th>
                                        <th>Adjuntar Comprobante</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                $(function() {
                    var currentCommissionId = $('#currentCommissionId').val();

                    $('#facturas-table').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: '{!! route('facturas_list_json') !!}',
                            type: 'GET',
                            data: function(d) {
                                d.idcomision = currentCommissionId;
                            }
                        },
                        columns: [{
                                data: 'idfactura',
                                name: 'idfactura'
                            },
                            {
                                data: 'cfdi',
                                name: 'cfdi'
                            },
                            {
                                data: 'importe',
                                name: 'comisiones.importe',
                                render: function(data, type, row) {
                                    if (type === 'display' || type === 'filter') {
                                        return '$' + parseFloat(data).toLocaleString('es-MX', {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2
                                        });
                                    }
                                    return data;
                                }
                            },
                            {
                                data: 'datefactura',
                                name: 'datefactura'
                            },
                            {
                                data: 'descripcion',
                                name: 'descripcion'
                            },
                            {
                                data: 'cancelacion',
                                render: function(data, type, row) {
                                    if (row.cancelacion === '1') {
                                        return '<strong style="color: red;">FACTURA ANULADA, LAS OBSERVACIONES SON LAS SIGUIENTES:</strong> ' +
                                            (
                                                row.observaciones ? row.observaciones : 'Sin observaciones.'
                                            );
                                    }
                                    if (row.cancelacion === '2') {
                                        return '<strong style="color: green;">FACTURA ACEPTADA</strong>';
                                    }
                                    if (row.cancelacion === '0') {
                                        return 'FACTURA PENDIENTE DE REVISIÓN';
                                    }
                                    return data;
                                }
                            },
                            {
                                data: 'status',
                                name: 'status'
                            },
                            {
                                data: 'btn_f'
                            }
                        ],
                        language: {
                            "url": "/vendor/i18n/datatables/es-ES.json"
                        }
                    });
                });
            </script>
        @endsection
