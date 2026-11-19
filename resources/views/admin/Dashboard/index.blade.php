@extends('layouts.master')

@section('title', 'Dashboard')

@section('vendor-style')
    @vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
@endsection

@section('vendor-script')
    @vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection

@section('page-script')
    @vite('resources/assets/js/dashboards-analytics.js')
@endsection

@section('content')
    <style>
        .card-container {
            height: 450px;
        }

        .chart-wrapper {
            height: 100%;
            width: 100%;
            position: relative;
            padding: 10px;
        }
    </style>

    <div id="mainContent" class="main flex-grow p-10 ml-40 transition-all duration-300">
        <div class="row ml-5">
            <div class="col-xxl-12 col-xl-8 col-lg-9 mb-6 order-0 ml-5">
                <div class="card">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success col-md-12" role="alert">{{ $message }}</div>
                    @endif
                    @if ($message = Session::get('warning'))
                        <div class="alert alert-warning col-md-12" role="alert">{{ $message }}</div>
                    @endif
                    <div class="d-flex align-items-start row">
                        <div class="col-sm-7">
                            <div class="card-body">
                                <h5 class="card-title text-primary mb-3">¡BIENVENIDO (A) {{ $nomusuario }}! 🎉</h5>
                                <p class="mb-6 text">Este es tu espacio para gestionar viáticos.<br>Revisa el avance de tus
                                    solicitudes o comienza una nueva si es tu primera vez.</p>
                                <a href="{{ route('comisionView') }}" class="btn btn-sm btn-outline-primary">Crear Nueva
                                    Solicitud</a>
                                <a href="{{ route('downloadConstancia') }}"
                                    class="btn btn-sm btn-outline-primary">Constancia Fiscal SSPC</a>
                            </div>
                        </div>
                        <div class="col-sm-5 text-center text-sm-left">
                            <div class="card-body pb-0 px-0 px-md-6">
                                <img src="{{ asset('/assets/img/illustrations/man-with-laptop.png') }}" height="175"
                                    class="scaleX-n1-rtl" alt="View Badge User">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-12 col-xl-8 col-lg-9 mb-6 order-0 ml-5">
                <div class="card card-border-shadow-primary h-100 p-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                        <h5 class="card-title mb-3 mb-md-0">Resumen de Actividad - Estatus de Comisiones</h5>

                        <div class="d-flex flex-column flex-sm-row gap-3" style="min-width: 50%;">
                            <div class="flex-grow-1">
                                <label class="form-label small text-muted mb-1">Unidad:</label>
                                <select id="selectUnidad" class="form-select select2">
                                    <option value="todas">Todas las Unidades (Total)</option>
                                    @foreach ($unidadesDB ?? [] as $unidad)
                                        <option value="{{ $unidad->no_direccion }}">{{ $unidad->no_direccion }} -
                                            {{ $unidad->nombre_ur }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex-grow-1">
                                <label class="form-label small text-muted mb-1">Modalidad:</label>
                                <select id="selectModalidad" class="form-select select2">
                                    <option value="todas">Todas las Modalidades</option>
                                    <option value="1">Anticipada</option>
                                    <option value="2">Devengada</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card-container">
                        <div class="chart-wrapper">
                            <canvas id="miGraficaBarra"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
    let miGrafica;
    const ctxBar = document.getElementById('miGraficaBarra');

    function renderChart(data) {
        const dataBar = {
            labels: [
                'En Solicitud',
                'Aut. UR',
                'En Revisión',
                'En Validación',
                'Ampliación',
                'Aut. Comprobación',
                'Pagada',
                'Finalizada'
            ],
            datasets: [
                {
                    label: `Solicitud (${data.solicitud})`,
                    data: [data.solicitud, 0, 0, 0, 0, 0, 0, 0],
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    borderColor: 'rgb(255, 99, 132)',
                    borderWidth: 1
                },
                {
                    label: `Aut. UR (${data.autorizacionUr})`,
                    data: [0, data.autorizacionUr, 0, 0, 0, 0, 0, 0],
                    backgroundColor: 'rgba(255, 159, 64, 0.6)',
                    borderColor: 'rgb(255, 159, 64)',
                    borderWidth: 1
                },
                {
                    label: `Revisión (${data.revision})`,
                    data: [0, 0, data.revision, 0, 0, 0, 0, 0],
                    backgroundColor: 'rgba(255, 205, 86, 0.6)',
                    borderColor: 'rgb(255, 205, 86)',
                    borderWidth: 1
                },
                {
                    label: `Validación (${data.validacion})`,
                    data: [0, 0, 0, data.validacion, 0, 0, 0, 0],
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgb(75, 192, 192)',
                    borderWidth: 1
                },
                {
                    label: `Ampliación (${data.ampliacion})`,
                    data: [0, 0, 0, 0, data.ampliacion, 0, 0, 0],
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgb(54, 162, 235)',
                    borderWidth: 1
                },
                {
                    label: `Aut. Comp. (${data.autorizacionComp})`,
                    data: [0, 0, 0, 0, 0, data.autorizacionComp, 0, 0],
                    backgroundColor: 'rgba(153, 102, 255, 0.6)',
                    borderColor: 'rgb(153, 102, 255)',
                    borderWidth: 1
                },
                {
                    label: `Pagada (${data.pagada})`,
                    data: [0, 0, 0, 0, 0, 0, data.pagada, 0],
                    backgroundColor: 'rgba(201, 203, 207, 0.6)',
                    borderColor: 'rgb(201, 203, 207)',
                    borderWidth: 1
                },
                {
                    label: `Finalizada (${data.finalizada})`,
                    data: [0, 0, 0, 0, 0, 0, 0, data.finalizada],
                    backgroundColor: 'rgba(46, 204, 113, 0.6)',
                    borderColor: 'rgb(46, 204, 113)',
                    borderWidth: 1
                }
            ]
        };

        if (miGrafica) {
            miGrafica.data = dataBar;
            miGrafica.update();
        } else {
            if (typeof Chart !== 'undefined' && ctxBar) {
                miGrafica = new Chart(ctxBar, {
                    type: 'bar',
                    data: dataBar,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: { boxWidth: 12, font: { size: 11 } }
                            }
                        },
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            }
        }
    }

    // Inicialización por defecto desde las variables cargadas en Blade
    renderChart({
        solicitud: {{ $count_en_solicitud ?? 0 }},
        autorizacionUr: {{ $count_en_autorizacionUr ?? 0 }},
        revision: {{ $count_en_revision ?? 0 }},
        validacion: {{ $count_en_validacion ?? 0 }},
        ampliacion: {{ $count_en_ampliacion ?? 0 }},
        autorizacionComp: {{ $count_en_autorizacionComp ?? 0 }},
        pagada: {{ $count_en_pagada ?? 0 }},
        finalizada: {{ $count_en_finalizada ?? 0 }}
    });

    function filtrarGrafica() {
        const unidadId = document.getElementById('selectUnidad').value;
        const modalidadId = document.getElementById('selectModalidad').value;

        fetch(`/comisiones/status-por-unidad?unidad_id=${unidadId}&modalidad=${modalidadId}`)
            .then(response => response.json())
            .then(data => renderChart(data))
            .catch(error => console.error('Error al filtrar la gráfica:', error));
    }

    document.getElementById('selectUnidad').addEventListener('change', filtrarGrafica);
    document.getElementById('selectModalidad').addEventListener('change', filtrarGrafica);
});
    </script>
@endsection
