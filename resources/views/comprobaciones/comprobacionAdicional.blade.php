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

        .dropzone-container {
            display: flex;
            gap: 20px;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .dropzone-box {
            flex: 1;
        }

        .table-container {
            overflow-x: auto;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" />
@endsection

@section('vendor-script')
    @vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection

@section('page-script')
    @vite('resources/assets/js/dashboards-analytics.js')
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
    <div id="mainContent" class="main flex-grow p-12 ml-40 transition-all duration-300">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success col-md-12" role="alert">{{ $message }}</div>
                    @endif
                    @if ($message = Session::get('warning'))
                        <div class="alert alert-warning col-md-12" role="alert">{{ $message }}</div>
                    @endif
                        <a href="{{ route('acusePDF', ['idComision' => Crypt::encryptString($query->idcomision)]) }}"
                            class="btn btn-sm btn-outline-primary" target="_blank">
                            Acuse de comisión
                        </a>

                        <a href="{{ route('solicitudPDF', ['idComision' => Crypt::encryptString($query->idcomision)]) }}"
                            class="btn btn-sm btn-outline-primary" target="_blank">
                            Solicitud de comisión
                        </a>
                        <h1 class="mb-4 display-4">Ingrese la Factura a Reemplazar</h1>

                        <div class="dropzone-container">
                            <div class="dropzone-box">
                                <h4 class="text-lg font-semibold mb-2"><b>PASO 1: </b>Subir Archivos XML</h4>
                                <form action="{{ route('xml.process-multiple') }}" method="POST"
                                    enctype="multipart/form-data" id="xml-dropzone" class="dropzone">
                                    @csrf
                                    <input type="hidden" name="idcomision" value="{{ $query->idcomision }}">
                                    <input type="hidden" name="initialDate"
                                        value="{{ \Carbon\Carbon::parse($query->initialdate)->format('Y-m-d') }}">
                                    <input type="hidden" name="finalDate"
                                        value=" {{ \Carbon\Carbon::parse($query->finaldate)->addDays(7)->format('Y-m-d') }} ">
                                    <div class="dz-message needsclick">
                                        Arrastra tus archivos XML aquí o haz clic para seleccionarlos.<br>
                                        <span class="note needsclick">(Solo se aceptan archivos **.xml**)</span>
                                    </div>
                                </form>
                            </div>

                            <div class="dropzone-box">
                                <h4 class="text-lg font-semibold mb-2"><b>PASO 2: </b>Subir Comprobantes en PDF</h4>
                                <form action="{{ route('xml.process-multiple') }}" method="POST"
                                    enctype="multipart/form-data" id="pdf-dropzone" class="dropzone">
                                    @csrf
                                    <input type="hidden" name="idcomision" value="{{ $query->idcomision }}">
                                    <input type="hidden" name="initialDate"
                                        value="{{ \Carbon\Carbon::parse($query->initialdate)->format('Y-m-d') }}">
                                    <input type="hidden" name="finalDate"
                                        value="{{ \Carbon\Carbon::parse($query->finaldate)->format('Y-m-d') }}">
                                    <div class="dz-message needsclick">
                                        Arrastra tus archivos PDF aquí o haz clic para seleccionarlos.<br>
                                        <span class="note needsclick">(Solo se aceptan archivos **.pdf**)</span>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div id="validation-results" class="mt-8">
                            <h2 class="text-xl font-semibold mb-4">Resultados de la Validación:</h2>
                            <ul id="results-list" class="space-y-2">
                            </ul>
                            <p id="no-results-message" class="text-gray-500 text-center hidden">No hay archivos para
                                validar aún.</p>
                        </div>
                        <div class="mt-6 text-center">

                            <a href="{{ route('comprobacionForm', ['idComision' => $query->idcomision]) }}"
                                class="btn btn-sm btn-outline-primary">
                                Guardar
                            </a>

                    </div>
                    <input type="hidden" id="currentCommissionId" value="{{ $idComision }}">
                    <input type="hidden" id="currentInvoiceParentId" value="{{ $factura->idfactura }}">
                    <div class="table-container">
                        <table id="facturas-table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID Factura</th>
                                    <th>UUID</th>
                                    <th>Importe</th>
                                    <th>Descripción</th>
                                    <th>Tipo de Gasto</th>
                                    <th>Status</th>
                                    <th>Observaciones (Revisor)</th>
                                    <th>Adjuntar Comprobante</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
    Dropzone.autoDiscover = false;

    document.addEventListener('DOMContentLoaded', function() {
        var validationResults = document.getElementById('validation-results');
        var resultsList = document.getElementById('results-list');
        var noResultsMessage = document.getElementById('no-results-message');

        const maxFilesizeMB = 10;

        // Opciones comunes de Dropzone corregidas para usar estilos Bootstrap en el listado de resultados
        const commonDropzoneOptions = {
            paramName: "file",
            maxFilesize: maxFilesizeMB,
            addRemoveLinks: true,
            dictRemoveFile: "Eliminar archivo",
            // Forzamos a que suba un archivo a la vez para capturar la respuesta individual de cada XML/PDF
            parallelUploads: 1,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            init: function() {
                this.on("success", function(file, response) {
                    // Asegurar que el contenedor principal de resultados sea visible
                    if(validationResults) validationResults.classList.remove('hidden');
                    if(noResultsMessage) noResultsMessage.classList.add('hidden');

                    var listItem = document.createElement('li');
                    // Cambiado a clases de Bootstrap para asegurar visibilidad
                    listItem.className = 'alert d-flex justify-content-between align-items-center my-2 ';

                    if (response.status === 'success') {
                        listItem.classList.add('alert-success');
                        listItem.innerHTML = `<div><strong>${response.filename || file.name}:</strong> ✅ ${response.message}</div>`;

                        if (response.invoice_amount) {
                            $('#facturas-table').DataTable().ajax.reload();
                        }
                    } else if (response.status === 'warning') {
                        listItem.classList.add('alert-warning');
                        listItem.innerHTML = `<div><strong>${response.filename || file.name}:</strong> ⚠️ ${response.message}</div>`;
                    } else if (response.status === 'error') {
                        listItem.classList.add('alert-danger');
                        listItem.innerHTML = `<div><strong>${response.filename || file.name}:</strong> ❌ ${response.message}</div>`;
                    } else {
                        listItem.classList.add('alert-secondary');
                        listItem.innerHTML = `<div><strong>${response.filename || file.name}:</strong> ℹ️ ${JSON.stringify(response)}</div>`;
                    }

                    resultsList.prepend(listItem);

                    // Remueve de la vista del Dropzone el cuadro miniatura tras 4 segundos, el log se queda arriba.
                    setTimeout(() => this.removeFile(file), 4000);
                });

                this.on("error", function(file, message, xhr) {
                    if(validationResults) validationResults.classList.remove('hidden');
                    if(noResultsMessage) noResultsMessage.classList.add('hidden');

                    var listItem = document.createElement('li');
                    listItem.className = 'alert alert-danger d-flex justify-content-between align-items-center my-2';

                    let displayMessage = `Error al subir: ${file.name}.`;

                    if (typeof message === 'object' && message !== null) {
                        if (message.message) {
                            displayMessage = message.message;
                        } else if (message.errors) {
                            displayMessage = "Errores de validación:<br>";
                            for (const field in message.errors) {
                                displayMessage += `- ${message.errors[field].join(', ')}<br>`;
                            }
                        }
                    } else if (typeof message === 'string') {
                        displayMessage = message;
                    }

                    if (xhr && xhr.status && xhr.responseText) {
                        try {
                            const errorResponse = JSON.parse(xhr.responseText);
                            if (errorResponse.message) {
                                displayMessage = errorResponse.message;
                            }
                        } catch (e) {
                            displayMessage += ` (HTTP ${xhr.status}: ${xhr.statusText})`;
                        }
                    }

                    listItem.innerHTML = `<div><strong>${file.name}:</strong> ❌ ${displayMessage}</div>`;
                    resultsList.prepend(listItem);

                    setTimeout(() => this.removeFile(file), 4000);
                });
            }
        };

        // Instanciación explícita leyendo la ruta del atributo action del form
        var xmlDropzone = new Dropzone("#xml-dropzone", {
            ...commonDropzoneOptions,
            url: document.getElementById('xml-dropzone').getAttribute('action'),
            acceptedFiles: ".xml",
            dictDefaultMessage: "Arrastra tus archivos XML aquí o haz clic para seleccionarlos.",
            dictInvalidFileType: "Solo puedes subir archivos XML."
        });

        var pdfDropzone = new Dropzone("#pdf-dropzone", {
            ...commonDropzoneOptions,
            url: document.getElementById('pdf-dropzone').getAttribute('action'),
            acceptedFiles: ".pdf",
            dictDefaultMessage: "Arrastra tus comprobantes en PDF aquí o haz clic para seleccionarlos.",
            dictInvalidFileType: "Solo puedes subir archivos PDF."
        });

        // Inicialización de DataTable
        $(function() {
            var currentInvoiceParentId = $('#currentInvoiceParentId').val();
            const tipoGastoOptions = {
                '1': 'HOSPEDAJE',
                '2': 'INTERNACIONAL',
                '3': 'ALIMENTOS',
                '4': 'TRANSPORTE FORANEO',
                '5': 'TRANSPORTE LOCAL',
                '6': 'HOSPEDAJE Y ALIMENTOS',
                '7': 'PEAJES'
            };
            $('#facturas-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('facturas_reemplazo_list_json') !!}',
                    type: 'GET',
                    data: function(d) {
                        d.idFactura = currentInvoiceParentId;
                    }
                },
                columns: [
                    { data: 'idfactura', name: 'idfactura' },
                    { data: 'cfdi', name: 'cfdi' },
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
                    { data: 'descripcion', name: 'descripcion' },
                    {
                        data: 'tipo_gasto',
                        name: 'facturas.idtypefactura',
                        orderable: false,
                        searchable: true,
                        render: function(data, type, row) {
                            if (data && data !== "") {
                                return `<span class="font-bold text-success">${tipoGastoOptions[data] || data}</span>`;
                            }
                            let selectHtml = `<select class="form-select tipo-gasto-select" data-idfactura="${row.idfactura}" style="min-width: 150px;">`;
                            selectHtml += `<option value="">Clasificar</option>`;
                            for (const key in tipoGastoOptions) {
                                const selected = (key === data) ? 'selected' : '';
                                selectHtml += `<option value="${key}" ${selected}>${tipoGastoOptions[key]}</option>`;
                            }
                            selectHtml += `</select>`;
                            return selectHtml;
                        }
                    },
                    { data: 'status', name: 'status' },
                    {
                        data: 'cancelacion',
                        render: function(data, type, row) {
                            if (row.cancelacion === '1') {
                                return '<strong class="text-danger">FACTURA ANULADA, LAS OBSERVACIONES SON LAS SIGUIENTES:</strong> ' + (row.observaciones ? row.observaciones : 'Sin observaciones.');
                            }
                            if (row.cancelacion === '2') {
                                return '<strong class="text-success">FACTURA ACEPTADA</strong>';
                            }
                            if (row.cancelacion === '0') {
                                return 'FACTURA PENDIENTE DE REVISIÓN';
                            }
                            return data;
                        }
                    },
                    { data: 'btn_f' }
                ],
                language: {
                    "url": "https://cdn.datatables.net/plug-ins/2.0.8/i18n/es-ES.json"
                },
                drawCallback: function() {
                    $('.tipo-gasto-select').off('change').on('change', function() {
                        const idFactura = $(this).data('idfactura');
                        const tipoGasto = $(this).val();
                        saveTipoGasto(idFactura, tipoGasto);
                    });
                }
            });
        });

        function saveTipoGasto(idFactura, tipoGasto) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const url = '{{ route('facturas.clasificarFactura', ['idfactura' => '__ID__']) }}'.replace('__ID__', idFactura);

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: csrfToken,
                    tipo_gasto: tipoGasto
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Actualizado',
                        text: response.message || 'Tipo de gasto guardado correctamente.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                },
                error: function(xhr) {
                    let errorMessage = "Error al guardar. Inténtalo de nuevo.";
                    try {
                        const errorResponse = JSON.parse(xhr.responseText);
                        if (errorResponse.message) {
                            errorMessage = errorResponse.message;
                        }
                    } catch (e) {}

                    Swal.fire({
                        icon: 'error',
                        title: 'Error de Actualización',
                        text: errorMessage
                    });
                }
            });
        }
    });
</script>
@endsection
