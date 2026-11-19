@extends('layouts.master')

@section('title', 'Dashboard')

@section('vendor-style')
    @vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
@endsection

@section('vendor-script')
    @vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection

@section('page-script')
    @vite(['resources/js/app.js', 'resources/assets/js/dashboards-analytics.js'])
@endsection


@section('content')
    <div id="mainContent" class="main flex-grow p-10 ml-40 transition-all duration-300">
        <div class="card">
            <h5 class="card-header pb-0 text-md-start text-center display-4 mb-4">Modificación de Comisión</h5>
            <div class="card-body">

                <a href="{{ route('acusePDF', ['idComision' => $query->idcomision]) }}"
                    class="btn btn-sm btn-outline-primary" target="_blank">Oficio de comisión</a>

                <a href="{{ route('solicitudPDF', ['idComision' => $query->idcomision]) }}"
                    class="btn btn-sm btn-outline-primary" target="_blank">Solicitud de comisión</a>

                @if ($message = Session::get('success'))
                    <div class="alert alert-success col-md-12" role="alert">{{ $message }}
                    </div>
                @endif
                @if ($message = Session::get('warning'))
                    <div class="alert alert-warning col-md-12" role="alert">{{ $message }}
                    </div>
                @endif
                <form action="{{ route('updateComision') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-6">
                        <div class="col-md-12">
                            <label class="form-label" for="numNombreUr">Número y Nombre de la unidad
                                responsable</label>
                            <input type="text" id="numNombreUr" class="form-control"
                                value="{{ $query->adscripcion }}" name="adscripcion" readonly>
                        </div>
                        <div class="col-md-8" hidden>
                            <label class="form-label" for="idcomision">Id de Comisión</label>
                            <input type="text" id="idcomision" name="idcomision" class="form-control"
                                value="{{ $query->idcomision }}" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label" for="initialdate">Fecha de inicio </label>
                            <input type="date" id="initialdate" name="initialdate"
                                value="{{ date('Y-m-d', strtotime($query->initialdate)) }}" class="form-control"
                                readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label" for="finaldate">Fecha final</label>
                            <input type="date" id="finaldate" name="finaldate"
                                value="{{ date('Y-m-d', strtotime($query->finaldate)) }}" class="form-control" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label" for="typecomision">Tipo de Comisión</label>
                            <input type="text" id="typecomision" name="typecomision" class="form-control"
                                value="{{ $query->typecomision }}" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label" for="numEmpleado">Número del Empleado</label>
                            <input type="number" id="numEmpleado" class="form-control"
                                value="{{ $query->numempleado }}" readonly>
                        </div>
                        @if (Auth::user()->idtype == 1)
                            <div class="col-md-2">
                                <label class="form-label" for="clc">CLC</label>
                                <input type="number" id="clc" class="form-control" value="{{ $query->clc }}"
                                    name="clc">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Documentación física:</label>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="radio" name="documentacionfisica"
                                        id="activo" value="1"
                                        {{ $query->documentacionfisica == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="activo">
                                        SI
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="documentacionfisica"
                                        id="inactivo" value="0"
                                        {{ $query->documentacionfisica == 0 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="inactivo">
                                        NO
                                    </label>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-6">
                            <label class="form-label" for="fullname">Nombre Completo</label>
                            <input type="text" id="fullname" class="form-control"
                                value="{{ $query->nombre_completo }}" readonly>
                        </div>
                        @if (Auth::user()->idtype == 1)
                        <div class="col-md-3">
                            <label class="form-label" for="idstatuscomision">Status de Comisión</label>
                            <select name="idstatuscomision" id="idstatuscomision" class="form-control" required>
                                <option value="">SELECCIONE UN VALOR</option>
                                    @foreach ($statusComision as $status)
                                        <option value="{{ $status->idstatuscomision }}"
                                            {{ $query->idstatuscomision == $status->idstatuscomision ? 'selected' : '' }}>
                                            {{ $status->statuscomision }}
                                        </option>
                                    @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="col-md-3">
                            <label class="form-label" for="completeimport">Importe por día completo</label>
                            <input type="text" id="completeimport" class="form-control"
                                value="{{ $import->completeimport }}" disabled>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label" for="mediumimport">Importe por Medio Día</label>
                            <input type="text" id="mediumimport" class="form-control"
                                value="{{ $import->mediumimport }}" disabled>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label" for="originalCommissionImport">Importe de comisión
                                Original</label>
                            <input type="text" id="originalCommissionImport" class="form-control"
                                value="{{ $query->importe }}" readonly>
                        </div>
                        <div id="ampliacionForm" class="row g-6 mt-4" style="display: none;">
                            <h2 class="mb-1 display-6 w-100">Ampliación</h2>
                            <hr class="w-100" style="border: 2px solid red;">
                            <div class="col-md-3">
                                <label class="form-label" for="fechaInicioAmpliacion">Fecha de inicio de Ampliación
                                </label>
                                <input type="date" id="fechaInicioAmpliacion" name="datereasignacioninicial"
                                    class="form-control" onchange="calcularDiasSeparados()">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="fechaFinAmpliacion">Fecha de Final</label>
                                <input type="date" id="fechaFinAmpliacion" name="dateReasignacionFinal"
                                    class="form-control" onchange="calcularDiasSeparados()">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="diasCompletosInput">Días Completos</label>
                                <input type="number" id="diasCompletosInput" name="daysCompletes"
                                    class="form-control" readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="medioDiaInput">Menor a 24HRS</label>
                                <input type="number" id="medioDiaInput" name="hours" class="form-control"
                                    readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="importeTotalInput">Importe</label>
                                <input type="text" id="importeTotalInput" name="newimport" class="form-control"
                                    readonly>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="motivoAmpliacion">Justificación</label>
                                <textarea id="motivoAmpliacion" name="motivoAmpliacion" class="form-control"></textarea>
                            </div>
                            <p id="mensajeErrorAmpliacion" class="col-12 text-danger"></p>
                        </div>

                        <div id="fechaImporteForm" class="row g-6 mt-4" style="display: none;">
                            <h2 class="mb-1 display-6 w-100">Formulario para reintegro</h2>
                            <hr class="w-100" style="border: 2px solid blue;">
                            <div class="col-md-6">
                                <label class="form-label" for="fechaReintegro">Fecha de Reintegro</label>
                                <input type="date" id="fechaReintegro"
                                    value="{{ date('Y-m-d', strtotime($query->initialdate)) }}" class="form-control"
                                    onchange="calcularImporteReintegro()" name="dateReturn">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="importeReintegroTotal">Importe pendiente de
                                    Reintegro</label>
                                <input type="number" id="importeReintegroTotal" name="returnImport"
                                    value = "{{ $query->returnimport }}" class="form-control" step="0.01">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="motivoReintegro">Justificación</label>
                                <textarea id="motivoReintegro" name="motivo" class="form-control">{{ $query->motivo }}</textarea>
                            </div>
                            <p id="mensajeErrorReintegro" class="col-12 text-danger"></p>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary">Enviar</button>
                            <a href="{{ route('validateComition') }}" class="btn btn-danger">Cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-12 mt-5">
                <h4 class="mb-3 display-6 w-100">Historial de Ampliaciones / Reasignaciones</h4>
                <div class="table-responsive">
                    @if ($historialAmpliaciones->count() > 0)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Fecha Inicial</th>
                                    <th>Fecha Final</th>
                                    <th>Importe Nuevo</th>
                                    <th>Motivo / Justificación</th>
                                    @if (Auth::user()->idtype == 1) {{-- SOLO MOSTRAR SI ES TIPO 1 (ADMIN) --}}
                                        <th>Archivo de Dispersión</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($historialAmpliaciones as $reasignacion)
                                    <tr>
                                        <td>{{ date('Y-m-d', strtotime($reasignacion->datereasignacioninicial)) }}</td>
                                        <td>{{ date('Y-m-d', strtotime($reasignacion->datereasignacionfinal)) }}</td>
                                        <td>${{ number_format($reasignacion->newimport, 2) }}</td>
                                        <td>{{ $reasignacion->motivoampliacion ?? 'Sin especificar' }}</td>
                                        @if (Auth::user()->idtype == 1)
                                            <td>
                                                {{-- 1. Añadimos la clase 'btnDescargaAmpliacion' y el atributo 'data-idreasignacion' --}}
                                                <button type="button" class="btn btn-info btn-sm btnDescargaAmpliacion"
                                                    data-idreasignacion="{{ $reasignacion->idreasignacion }}">
                                                    Descargar Layout
                                                </button>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="alert alert-info">No se encontraron modificaciones previas (ampliaciones o
                            cancelaciones parciales) para esta comisión.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('idstatuscomision');
            const ampliacionForm = document.getElementById('ampliacionForm');
            const reintegroForm = document.getElementById('fechaImporteForm');

            function toggleForms() {
                const selectedStatus = statusSelect.value;

                ampliacionForm.style.display = 'none';
                reintegroForm.style.display = 'none';

                if (selectedStatus === '11') {
                    ampliacionForm.style.display = 'flex';
                } else if (selectedStatus === '14') {
                    reintegroForm.style.display = 'flex';
                }
            }

            toggleForms();

            statusSelect.addEventListener('change', toggleForms);

            document.querySelectorAll('.btnDescargaAmpliacion').forEach(button => {
                button.addEventListener('click', function() {
                    const idReasignacion = this.getAttribute('data-idreasignacion');
                    const url = "{{ route('reporteBancarioAmpliacion') }}";

                    if (!idReasignacion) {
                        alert('Error: No se encontró el ID de la ampliación.');
                        return;
                    }
                    
                    const form = document.createElement('form');
                    form.setAttribute('action', url);
                    form.setAttribute('method', 'POST');
                    form.setAttribute('target', '_self');

                    form.innerHTML += '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
                    
                    form.innerHTML += '<input type="hidden" name="idreasignacion" value="' + idReasignacion + '">';

                    document.body.appendChild(form);
                    form.submit();
                    form.remove();

                    Swal.fire({
                        title: "Descargando layout de ampliación...",
                        icon: "info",
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                });
            });
        });


        function calcularDiasSeparados() {
            const fechaInicio = document.getElementById('fechaInicioAmpliacion').value;
            const fechaFin = document.getElementById('fechaFinAmpliacion').value;
            const importeCompleto = parseFloat(document.getElementById('completeimport').value) || 0;
            const importeMedioDia = parseFloat(document.getElementById('mediumimport').value) || 0;

            if (fechaInicio && fechaFin) {
                const start = new Date(fechaInicio);
                const end = new Date(fechaFin);
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

                let diasCompletos = Math.max(0, diffDays - 1);
                let medioDia = diffDays > 0 && diffDays <= 1 ? 1 : 0;

                const nuevoImporte = (diasCompletos * importeCompleto) + (medioDia * importeMedioDia);

                document.getElementById('diasCompletosInput').value = diasCompletos;
                document.getElementById('medioDiaInput').value = medioDia;
                document.getElementById('importeTotalInput').value = nuevoImporte.toFixed(2);
                document.getElementById('mensajeErrorAmpliacion').textContent = '';
            } else {
                document.getElementById('diasCompletosInput').value = '';
                document.getElementById('medioDiaInput').value = '';
                document.getElementById('importeTotalInput').value = '';
                document.getElementById('mensajeErrorAmpliacion').textContent =
                    'Debe seleccionar ambas fechas para calcular la ampliación.';
            }
        }

        function calcularImporteReintegro() {
            const fechaReintegro = document.getElementById('fechaReintegro').value;
            const importeOriginal = parseFloat(document.getElementById('originalCommissionImport').value) || 0;
            const fechaInicialComision = document.getElementById('initialdate').value;
            const fechaFinalComision = document.getElementById('finaldate').value;
            const importeCompleto = parseFloat(document.getElementById('completeimport').value) || 0;

            if (fechaReintegro && importeOriginal > 0) {
                const mockReintegro = importeOriginal * 0.50;
                document.getElementById('importeReintegroTotal').value = mockReintegro.toFixed(2);
                document.getElementById('mensajeErrorReintegro').textContent = '';
            } else {
                document.getElementById('importeReintegroTotal').value = importeOriginal.toFixed(2);
                document.getElementById('mensajeErrorReintegro').textContent = 'Seleccione la fecha de reintegro.';
            }
        }
    </script>
@endsection