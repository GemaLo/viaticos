@extends('layouts.master')
@section('title', 'Reportes')

@section('vendor-style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .select2-container--default .select2-selection--multiple {
            border-color: #d1d5db !important;
            border-radius: 0.5rem !important;
            min-height: 45px;
            padding: 4px;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #6366f1 !important;
            ring: 2px;
        }

        .select2-container {
            width: 100% !important;
        }
    </style>
@endsection

@section('vendor-script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection

@section('page-script')
    @vite('resources/assets/js/dashboards-analytics.js')

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Seleccione las áreas correspondientes",
                allowClear: true
            });

            const modalidadSelect = document.getElementById('idmodalidad');
            const statusSelect = document.getElementById('idstatuscomision');

            if (modalidadSelect && statusSelect) {
                modalidadSelect.addEventListener('change', function() {
                    const selectedModalidad = this.value;
                    const options = statusSelect.querySelectorAll('option');

                    options.forEach(option => {
                        if (option.value === "") {
                            option.hidden = false;
                            return;
                        }

                        const dataMod = option.getAttribute('data-modalidad');
                        if (selectedModalidad === "" || dataMod === selectedModalidad) {
                            option.hidden = false;
                        } else {
                            option.hidden = true;
                        }
                    });

                    statusSelect.value = "";
                });
            }
        });
    </script>
@endsection

@section('content')

    @if (Auth::user()->idtype == 1)
        <div class="main flex-grow p-12 ml-40 transition-all duration-300">
            <div class="row">
                @if (session('success'))
                    <div class="alert alert-success p-4 mb-4 rounded-lg bg-green-100 text-green-700">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-error p-4 mb-4 rounded-lg bg-red-100 text-red-700">
                        {{ session('error') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-error p-4 mb-4 rounded-lg bg-red-100 text-red-700">
                        <ul class="list-disc ml-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('facturas_actualizadas') && count(session('facturas_actualizadas')) > 0)
                    <div class="col-12 mb-4">
                        <div class="card shadow-sm border-indigo-200">
                            <div class="card-header bg-indigo-50 py-3">
                                <h5 class="font-bold text-indigo-900 mb-0">Resumen de Facturas Actualizadas Masivamente</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm text-left text-gray-500">
                                        <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                            <tr>
                                                <th scope="col" class="px-6 py-3">Número de Comisión</th>
                                                <th scope="col" class="px-6 py-3">UR que comisionó</th>
                                                <th scope="col" class="px-6 py-3">Factura (CFDI)</th>
                                                <th scope="col" class="px-6 py-3">Modalidad de Comisión</th>
                                                <th scope="col" class="px-6 py-3">Estatus de la Factura (SAT)</th>
                                                <th scope="col" class="px-6 py-3">Estatus de la Factura (DGPPR)</th>
                                                <th scope="col" class="px-6 py-3">CLC</th>
                                                <th scope="col" class="px-6 py-3">Estatus de la comisión</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach (session('facturas_actualizadas') as $item)
                                                <tr class="bg-white border-b hover:bg-gray-50">
                                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $item['comision'] }}
                                                    </td>
                                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $item['ur'] }}</td>
                                                    <td class="px-6 py-4 font-mono text-xs text-indigo-600 font-bold">
                                                        {{ $item['cfdi'] }}</td>
                                                    <td class="px-6 py-4">{{ $item['modalidad'] }}</td>
                                                    <td class="px-6 py-4">{{ $item['status'] }}</td>
                                                    <td class="px-6 py-4">
                                                        <span
                                                            class="px-2 py-1 rounded text-xs font-semibold
                                                            @switch($item['cancelacion'])
                                                                @case(0) bg-amber-100 text-amber-800 @break
                                                                @case(1) bg-red-100 text-red-800 @break
                                                                @case(2) bg-green-100 text-green-800 @break
                                                                @case(3) bg-gray-100 text-gray-800 @break
                                                                @default bg-light text-dark
                                                            @endswitch">
                                                            @switch($item['cancelacion'])
                                                                @case(0)
                                                                    Pendiente
                                                                @break

                                                                @case(1)
                                                                    Cancelada
                                                                @break

                                                                @case(2)
                                                                    Aceptada
                                                                @break

                                                                @case(3)
                                                                    No corresponde a comisión
                                                                @break

                                                                @default
                                                                    Desconocido ({{ $item['cancelacion'] }})
                                                            @endswitch
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4">{{ $item['clc'] }}</td>
                                                    <td class="px-6 py-4">{{ $item['statuscomision'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="font-bold mb-2">Carga de Excel para validación de facturas (Masiva)</h5>
                            <form action="{{ route('import_status') }}" method="POST" enctype="multipart/form-data"
                                class="space-y-6">
                                @csrf
                                <div>
                                    <label for="excel_file" class="block text-sm font-medium text-gray-700 mb-2">Seleccione
                                        el archivo Excel:</label>
                                    <input type="file" name="excel_file" id="excel_file" accept=".xlsx, .xls" required
                                        class="w-full text-gray-700 bg-white border border-gray-300 rounded-lg p-3 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                                </div>
                                <p class="text-sm text-gray-500 mt-2">Se realizará la actualización de status ('VIGENTE' o
                                    'CANCELADA') de forma masiva.</p>
                                <button type="submit"
                                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150">
                                    Cargar y Actualizar Estado
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="font-bold mb-2">Actualización de catálogo Beneficiarios</h5>
                            <div id="import-status-container" class="alert p-3 mb-3 rounded-lg d-none">
                                <div class="flex items-center gap-2">
                                    <div id="import-spinner" class="spinner-border spinner-border-sm d-none" role="status">
                                    </div>
                                    <span id="import-status-text">Preparando importación...</span>
                                </div>
                            </div>
                            <form action="{{ route('beneficiarios.import.process') }}" method="POST"
                                enctype="multipart/form-data" class="space-y-6">
                                @csrf
                                <div>
                                    <label for="csv_file" class="block text-sm font-medium text-gray-700 mb-2">Seleccione el
                                        archivo CSV:</label>
                                    <input type="file" name="csv_file" id="csv_file" accept=".csv, .txt" required
                                        class="w-full text-gray-700 bg-white border border-gray-300 rounded-lg p-3 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                                </div>
                                <p class="text-sm text-gray-500 mt-2">Columnas requeridas: `rfc`, `razon_social`,
                                    `fecha_alta`, `activo`, `id_ramo`, `codigo_beneficiario`.</p>
                                <button type="submit" id="btn-import-beneficiarios"
                                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150">
                                    Iniciar Importación
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div id="mainContent" class="main flex-grow p-12 ml-40 transition-all duration-300">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <h5 class="font-bold mb-4">Reporte de comisiones</h5>
                    <div id="download-status-container" class="alert alert-info p-3 mb-3 rounded d-none">
                        <span id="download-status-text">Generando reporte, por favor espere...</span>
                    </div>
                    <form action="{{ route('downloadReport') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="idmodalidad">Modalidad de Comisión</label>
                                <select name="idmodalidad" id="idmodalidad" class="form-control">
                                    <option value="">SELECCIONE UNA OPCIÓN</option>
                                    @foreach ($modalidadComision as $modalidad)
                                        <option value="{{ $modalidad->idmodalidad }}">{{ $modalidad->modalidad }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Agregar dentro del contenedor div class="row g-3" -->
<div class="col-md-2">
    <label class="form-label" for="folioInicio">Folio Inicial</label>
    <input type="text" id="folioInicio" name="folio_inicio" class="form-control" placeholder="Ej. 10">
</div>
<div class="col-md-2">
    <label class="form-label" for="folioFin">Folio Final</label>
    <input type="text" id="folioFin" name="folio_fin" class="form-control" placeholder="Ej. 250">
</div>
                            <div class="col-md-2">
                                <label class="form-label" for="fechaInicio">Fecha de Inicio</label>
                                <input type="date" id="fechaInicio" name="initialdate" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="fechaFin">Fecha Final</label>
                                <input type="date" id="fechaFin" name="finaldate" class="form-control">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label" for="idstatuscomision">Status de Comisión</label>
                                <select name="idstatuscomision" id="idstatuscomision" class="form-control">
                                    <option value="">SELECCIONE UNA OPCIÓN</option>
                                    @foreach ($statusComision as $status)
                                        <option value="{{ $status->idstatuscomision }}"
                                            data-modalidad="{{ $status->idmodalidad }}">
                                            {{ $status->statuscomision }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="iddestino">Destinos</label>
                                <select name="iddestino" id="iddestino" class="form-control">
                                    <option value="">SELECCIONE UNA OPCIÓN</option>
                                    @foreach ($destinos as $destino)
                                        <option value="{{ $destino->iddestino }}">{{ $destino->destino }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="idtypecomision">Tipo de Comisión</label>
                                <select name="idtypecomision" id="idtypecomision" class="form-control">
                                    <option value="">SELECCIONE UNA OPCIÓN</option>
                                    @foreach ($typeComision as $type)
                                        <option value="{{ $type->idtypecomision }}">{{ $type->typecomision }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 mt-3">
                                <label class="form-label font-bold" for="direcciones">Direcciones Asignadas a la comisión
                                    (Multiselección):</label>
                                <select id="direcciones" name="direcciones[]" class="form-control select2"
                                    multiple="multiple">
                                    @foreach ($direcciones as $dir)
                                        <option value="{{ $dir->no_direccion }}">
                                            {{ $dir->no_direccion }} - {{ $dir->nombre_ur }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <button type="submit" id="submitBtn" class="btn btn-primary w-full">Descargar
                                    Reporte</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @php
        $allowed_users = [1, 2, 3, 30, 97];
    @endphp
    @if (in_array(Auth::user()->id_user, $allowed_users))
        <div class="main flex-grow p-12 ml-40 transition-all duration-300">
            <div class="card">
                <div class="card-header font-bold">Descargar Reporte de Registros</div>
                <div class="card-body">
                    <form id="form-report">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Fecha Inicio (Opcional):</label>
                                <input type="date" name="fecha_inicio" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fecha Fin (Opcional):</label>
                                <input type="date" name="fecha_fin" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Usuario (Opcional):</label>
                                <select name="id_user" class="form-control">
                                    <option value="">-- Todos los usuarios --</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id_user }}">{{ $user->nomusuario }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Buscar por Texto (Opcional):</label>
                                <input type="text" name="texto_movimiento" class="form-control"
                                    placeholder="Ej: Pago, Transferencia...">
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12 text-end">
                                <button class="btn btn-primary" id="button-form" type="button">
                                    <i class="fas fa-download me-1"></i> Generar Reporte
                                </button>
                            </div>
                        </div>
                    </form>

                    <div id="estado-reporte" class="mt-4 d-none">
                        <div id="estado-pendiente" class="alert alert-info d-flex align-items-center gap-3 d-none">
                            <div class="spinner-border spinner-border-sm" role="status"></div>
                            <span id="mensaje-progreso">Generando reporte, por favor espere...</span>
                        </div>
                        <div id="estado-error" class="alert alert-danger d-none">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <span id="mensaje-error"></span>
                        </div>
                        <div id="estado-listo" class="alert alert-success d-none">
                            <i class="fas fa-check-circle me-2"></i>
                            Reporte listo.
                            <a id="link-descarga" href="#" target="_blank" class="alert-link ms-1">Haz clic aquí
                                para descargar el archivo.</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const button = document.getElementById('button-form');
                const estadoWrap = document.getElementById('estado-reporte');
                const estadoPending = document.getElementById('estado-pendiente');
                const estadoError = document.getElementById('estado-error');
                const estadoListo = document.getElementById('estado-listo');
                const msgProgreso = document.getElementById('mensaje-progreso');
                const msgError = document.getElementById('mensaje-error');
                const linkDescarga = document.getElementById('link-descarga');

                let intervalo = null;

                if (button) {
                    button.addEventListener('click', async function() {
                        const fechaInicio = document.querySelector('input[name="fecha_inicio"]').value;
                        const fechaFin = document.querySelector('input[name="fecha_fin"]').value;
                        const textoBusqueda = document.querySelector('input[name="texto_movimiento"]').value
                            .trim();

                        // Validaciones si hay fechas ingresadas
                        if (fechaInicio && fechaFin) {
                            if (fechaInicio > fechaFin) {
                                alert('La fecha de inicio no puede ser mayor que la fecha de fin.');
                                return;
                            }

                            const inicio = new Date(fechaInicio);
                            const fin = new Date(fechaFin);
                            const diffDias = (fin - inicio) / (1000 * 60 * 60 * 24);

                            if (diffDias > 31) {
                                alert('El rango de fechas no puede ser mayor a 31 días.');
                                return;
                            }
                        }

                        mostrarEstado('pendiente');
                        button.disabled = true;

                        const form = document.getElementById('form-report');
                        const formData = new FormData(form);

                        try {
                            const res = await fetch('{{ route('registros.descargar') }}', {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')
                                        .value
                                }
                            });

                            if (!res.ok) throw new Error('Error al iniciar la generación del reporte.');

                            const {
                                job_id
                            } = await res.json();
                            let intentos = 0;
                            const maxIntentos = 180; 

                            intervalo = setInterval(async () => {
                                intentos++;

                                if (intentos >= maxIntentos) {
                                    clearInterval(intervalo);
                                    mostrarEstado('error',
                                        'El reporte tardó demasiado tiempo en procesarse. Reduzca el rango de consulta.'
                                        );
                                    button.disabled = false;
                                    return;
                                }

                                msgProgreso.textContent =
                                    `Generando reporte... (${intentos * 10}s aprox.)`;

                                try {
                                    const statusRes = await fetch(
                                        `{{ route('registros.export_status', ':job') }}`
                                        .replace(':job', job_id)
                                    );
                                    const statusData = await statusRes.json();

                                    if (statusData.status === 'done') {
                                        clearInterval(intervalo);
                                        mostrarEstado('listo', statusData.url);

                                        const tempLink = document.createElement('a');
                                        tempLink.href = statusData.url;
                                        tempLink.setAttribute('download', '');
                                        document.body.appendChild(tempLink);
                                        tempLink.click();
                                        document.body.removeChild(tempLink);

                                        button.disabled = false;
                                    }

                                    if (statusData.status === 'failed') {
                                        clearInterval(intervalo);
                                        mostrarEstado('error', statusData.error ??
                                            'Error al procesar la exportación.');
                                        button.disabled = false;
                                    }
                                } catch (e) {
                                    console.warn('Error al verificar el estado del Job:', e);
                                }
                            }, 10000);

                        } catch (e) {
                            mostrarEstado('error', e.message);
                            button.disabled = false;
                        }
                    });
                }

                function mostrarEstado(tipo, extra = '') {
                    if (!estadoWrap) return;
                    estadoWrap.classList.remove('d-none');
                    estadoPending.classList.add('d-none');
                    estadoError.classList.add('d-none');
                    estadoListo.classList.add('d-none');

                    if (tipo === 'pendiente') {
                        estadoPending.classList.remove('d-none');
                    } else if (tipo === 'error') {
                        msgError.textContent = extra;
                        estadoError.classList.remove('d-none');
                    } else if (tipo === 'listo') {
                        linkDescarga.href = extra;
                        estadoListo.classList.remove('d-none');
                    }
                }
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const downloadId = "{{ session('download_id') }}";

            if (downloadId) {
                const submitBtn = document.getElementById('submitBtn');
                const statusContainer = document.getElementById('download-status-container');
                const statusText = document.getElementById('download-status-text');

                if (submitBtn) submitBtn.disabled = true;
                if (statusContainer) statusContainer.classList.remove('d-none');

                const intervalId = setInterval(() => {
                    fetch(`/reportes/status/${downloadId}`)
                        .then(res => res.json())
                        .then(statusData => {
                            console.log("Estatus del reporte:", statusData);

                            if (statusData.status === 'completed') {
                                clearInterval(intervalId);

                                if (statusContainer) {
                                    statusContainer.style.backgroundColor = '#d1fae5';
                                    statusContainer.style.color = '#065f46';
                                    statusContainer.style.borderColor = '#34d399';
                                }

                                if (statusText) {
                                    statusText.innerHTML = '¡Tu reporte está listo! <a href="' +
                                        statusData.file_url +
                                        '" class="underline font-bold" style="color: #047857;" download>Haz clic aquí para descargarlo manualmente si no inició</a>.';
                                }

                                if (submitBtn) submitBtn.disabled = false;
                                window.location.href = statusData.file_url;
                            } else if (statusData.status === 'failed') {
                                clearInterval(intervalId);

                                if (statusContainer) {
                                    statusContainer.style.backgroundColor = '#fee2e2';
                                    statusContainer.style.color = '#991b1b';
                                }
                                if (statusText) {
                                    statusText.innerText = "Error: " + (statusData.error ||
                                        'No se pudo generar.');
                                }
                                if (submitBtn) submitBtn.disabled = false;
                            }
                        })
                        .catch(err => {
                            console.error("Error en polling:", err);
                        });
                }, 3000);
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const importKey = "{{ session('import_key') }}";

            if (importKey) {
                const container = document.getElementById('import-status-container');
                const text = document.getElementById('import-status-text');
                const spinner = document.getElementById('import-spinner');
                const btnSubmit = document.getElementById('btn-import-beneficiarios');

                // Bloqueamos el botón y mostramos el cuadro de carga
                if (btnSubmit) btnSubmit.disabled = true;
                if (container) {
                    container.classList.remove('d-none', 'alert-success', 'alert-danger');
                    container.classList.add('alert-info', 'bg-blue-100', 'text-blue-800');
                }
                if (spinner) spinner.classList.remove('d-none');

                // Polling cada 3 segundos
                const intervalId = setInterval(() => {
                    fetch(`/beneficiarios/import-status/${importKey}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.status === 'processing') {
                                text.innerText =
                                    `Procesando registros en segundo plano... (Registros guardados: ${data.processed})`;
                            }

                            if (data.status === 'completed') {
                                clearInterval(intervalId);

                                container.classList.remove('alert-info', 'bg-blue-100',
                                    'text-blue-800');
                                container.classList.add('alert-success', 'bg-green-100',
                                    'text-green-800');
                                text.innerHTML =
                                    `<strong>Importación Exitosa.</strong> Se han procesado correctamente ${data.processed} registros.`;

                                if (spinner) spinner.classList.add('d-none');
                                if (btnSubmit) btnSubmit.disabled = false;
                            }

                            if (data.status === 'failed') {
                                clearInterval(intervalId);

                                // Cambiar la alerta a Error
                                container.classList.remove('alert-info', 'bg-blue-100',
                                    'text-blue-800');
                                container.classList.add('alert-danger', 'bg-red-100', 'text-red-800');
                                text.innerText =
                                    `Error en la importación: ${data.error || 'Ocurrió un fallo inesperado.'}`;

                                if (spinner) spinner.classList.add('d-none');
                                if (btnSubmit) btnSubmit.disabled = false;
                            }
                        })
                        .catch(err => console.error("Error al consultar el progreso:", err));
                }, 3000);
            }
        });
    </script>
@endsection
