@extends('layouts.master')

@section('title', 'Dashboard')

@section('vendor-style')
    @vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('vendor-script')
    @vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>
@endsection

@section('page-style')
    <link href="{{ asset('assets/css/validateComission.css') }}" rel="stylesheet">
@endsection

@section('page-script')
    @vite('resources/assets/js/dashboards-analytics.js')
    <script src="{{ asset('assets/js/validateComission.js') }}"></script>
@endsection

@section('content')
    @php
        $cancelacion = [3, 14, 15, 18, 24];
        $importeDiaCompleto = 500.0;
        $importeMedioDia = 250.0;
        $permissions = [1, 13, 16, 22];

        $statusComisionData = [
            ['idstatuscomision' => 1, 'statuscomision' => 'Solicitud', 'idModalidad' => 1],
            ['idstatuscomision' => 2, 'statuscomision' => 'Autorizada por UR', 'idModalidad' => 1],
            ['idstatuscomision' => 3, 'statuscomision' => 'Cancelación por UR', 'idModalidad' => 1],
            ['idstatuscomision' => 4, 'statuscomision' => 'En revisión de solicitud por DGPPR', 'idModalidad' => 1],
            ['idstatuscomision' => 5, 'statuscomision' => 'Comisión revisada y Proceso de Pago', 'idModalidad' => 1],
            ['idstatuscomision' => 6, 'statuscomision' => 'Comisión rechazada', 'idModalidad' => 1],
            ['idstatuscomision' => 7, 'statuscomision' => 'En dispersión', 'idModalidad' => 1],
            ['idstatuscomision' => 8, 'statuscomision' => 'Pagada', 'idModalidad' => 1],
            ['idstatuscomision' => 9, 'statuscomision' => 'En curso', 'idModalidad' => 1],
            ['idstatuscomision' => 10, 'statuscomision' => 'En comprobación', 'idModalidad' => 1],
            ['idstatuscomision' => 11, 'statuscomision' => 'En ampliación', 'idModalidad' => 1],
            ['idstatuscomision' => 12, 'statuscomision' => 'En revisión de comprobación por DGPPR', 'idModalidad' => 1],
            ['idstatuscomision' => 13, 'statuscomision' => 'Comprobación validada por DGPPR', 'idModalidad' => 1],
            ['idstatuscomision' => 14, 'statuscomision' => 'Terminación anticipada', 'idModalidad' => 1],
            ['idstatuscomision' => 15, 'statuscomision' => 'Finalizada', 'idModalidad' => 1],
            ['idstatuscomision' => 26, 'statuscomision' => 'Autorización de comprobación por UR', 'idModalidad' => 2],
            ['idstatuscomision' => 16, 'statuscomision' => 'Solicitud', 'idModalidad' => 2],
            ['idstatuscomision' => 17, 'statuscomision' => 'Autorizada por UR', 'idModalidad' => 2],
            ['idstatuscomision' => 18, 'statuscomision' => 'Cancelación por UR', 'idModalidad' => 2],
            ['idstatuscomision' => 19, 'statuscomision' => 'En curso', 'idModalidad' => 2],
            ['idstatuscomision' => 20, 'statuscomision' => 'En comprobación', 'idModalidad' => 2],
            ['idstatuscomision' => 21, 'statuscomision' => 'En revisión de comprobación por DGPPR', 'idModalidad' => 2],
            ['idstatuscomision' => 22, 'statuscomision' => 'Comprobación validada por DGPPR', 'idModalidad' => 2],
            ['idstatuscomision' => 23, 'statuscomision' => 'En dispersión', 'idModalidad' => 2],
            ['idstatuscomision' => 24, 'statuscomision' => 'Pagada y finalizada', 'idModalidad' => 2],
            ['idstatuscomision' => 25, 'statuscomision' => 'Autorización de comprobación por UR', 'idModalidad' => 1],
            ['idstatuscomision' => 27, 'statuscomision' => 'Finalizada sin gasto', 'idModalidad' => 1],
            ['idstatuscomision' => 28, 'statuscomision' => 'Finalizada sin gasto', 'idModalidad' => 2],
            ['idstatuscomision' => 241, 'statuscomision' => 'En observaciones', 'idModalidad' => 1],
            ['idstatuscomision' => 242, 'statuscomision' => 'En observaciones', 'idModalidad' => 2],
        ];
    @endphp

    <script>
        window.validateConfig = {
            CANCELACION_STATUSES: @json($cancelacion),
            IMPORTE_DIA_COMPLETO: {{ $importeDiaCompleto }},
            IMPORTE_MEDIO_DIA: {{ $importeMedioDia }},
            STATUS_COMISION_DATA: @json($statusComisionData),
            USER_TYPE: {{ Auth::user()->idtype ?? 0 }},
            csrf: "{{ csrf_token() }}",
            routes: {
                listJson: "{{ url('comision_list_json') }}",

                aprobarMasivo: "{{ url('aprobarMasivo') }}",
                cancelarMasivo: "{{ url('cancelarMasivo') }}",
                editarMasivo: "{{ url('editarMasivo') }}",
                reportes: "{{ route('reportes') }}",
                reportesSicop: "{{ route('reportes.sicop') }}",
                reportesZip: "{{ route('reportes.zip') }}"
            }
        };
    </script>

    <div id="mainContent" class="main flex-grow p-12 ml-40 transition-all duration-300">
        <div class="card">
            <h5 class="m-0 me-2">Historial de Viático</h5>

            <div class="d-flex my-3 mx-3 align-items-center justify-content-between">
                <div class="d-flex my-3">
                    <button class="btn btn-outline-primary me-2 tab-btn active" data-tab-type="1">Anticipados</button>
                    <button class="btn btn-outline-primary tab-btn" data-tab-type="2">Devengados</button>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-warning" id="editar-comisiones">
                        <i class="bx bx-pencil me-2"></i>Editar masivos seleccionados
                    </button>
                    @if (Auth::user()->idtype == 2)
                        <button class="btn btn-success" id="aprobar-comisiones">
                            <i class="bx bx-check me-2"></i>Aprobación Masiva
                        </button>
                        <button class="btn btn-danger" id="cancelar-comisiones">
                            <i class="bx bx-x me-2"></i>Cancelación Masiva Total
                        </button>
                    @endif
                </div>
            </div>

            <div class="d-flex gap-2">
                @if (Auth::user()->idtype == 1)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="massAction" id="actionLayoutBancario"
                            value="download_bancario" checked>
                        <label class="form-check-label" for="actionLayoutBancario">
                            LAYOUT BANCARIO
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="massAction" id="actionLayoutSicop"
                            value="download_sicop">
                        <label class="form-check-label" for="actionLayoutSicop">
                            LAYOUT SICOP
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="massAction" id="actionZip"
                            value="zip_archivos">
                        <label class="form-check-label" for="actionZip">
                            ZIP ARCHIVOS
                        </label>
                    </div>
                    <button class="btn btn-primary" id="btnEjecutarAccion">
                        Ejecutar
                    </button>
                @endif
            </div>

            <div class="card-datatable w-100 overflow-hidden">
                <div class="table-responsive text-nowrap">
                    <table id="myTable" class="table table-striped w-100">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAll"></th>
                                <th>No. Comisión</th>
                                {{-- <th>Modalidad de Comisión</th> --}}
                                <th>Unidad que comisiona este folio</th>
                                <th>Nombre del Comisionado</th>
                                <th>Destino</th>
                                <th>Importe</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Final</th>
                                <th>CLC</th>
                                <th>Etapa de la Comisión</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .tab-btn.active {
            background-color: #007bff;
            color: #fff;
        }

        #myTable thead tr:eq(1) input.filters {
            width: 90%;
        }
    </style>

    @include('comision.partials.aprobarModal', ['filteredObjetives' => $filteredObjetives])
    @include('comision.partials.cancelarModal', ['filteredObjetives' => $filteredObjetives])
    @include('comision.partials.editarMasivoModal', ['filteredObjetives' => $filteredObjetives])
@endsection
