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
                        <div class="row">
                            <div class="modal fade" id="miModal" tabindex="-1" aria-labelledby="miModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="miModalLabel">Condiciones para
                                                comprobación de facturas
                                        </div>
                                        <div class="modal-body" align="justify">
                                            <p>
                                                Los viáticos se otorgan <strong>exclusivamente para la persona
                                                    comisionada</strong> y sólo se podrán utilizar para pago de
                                                <strong>transporte, alimentos y hospedaje</strong>, en lugares distintos a
                                                los de su adscripción, tal como lo indican las partidas presupuestales del
                                                concepto 372, 375 y 376 del COG de la APF, vigente.
                                            </p>
                                            <p>
                                                Por lo que la persona servidora pública comisionada deberá atender las
                                                siguientes consideraciones:
                                            </p>
                                            <ul>
                                                <li class="lineamiento">Será responsabilidad de la persona comisionada,
                                                    verificar que los
                                                    comprobantes cumplan con los requisitos administrativos y fiscales
                                                    vigentes.</li>
                                                <li class="lineamiento">Es importante destacar que los <strong>VIÁTICOS SON
                                                        PERSONALES</strong>,
                                                    por lo que el importe otorgado a cada comisionado será única y
                                                    exclusivamente para cubrir sus viáticos.</li>
                                                <li class="lineamiento">Los viáticos son exclusivamente para cumplir las
                                                    tareas que le fueron
                                                    encomendadas durante su comisión y en ninguna circunstancia podrán
                                                    disponer del recurso para ninguna otra actividad.</li>
                                                <li class="lineamiento">En ningún caso se podrán realizar compras de
                                                    despensas, es decir,
                                                    artículos para el abasto de más de un día, o bien, para más de una
                                                    persona.</li>
                                                <li class="lineamiento">Los viáticos no deberán utilizarse en la renta de
                                                    vehículos, servicios
                                                    de lavandería, recargas telefónicas, artículos de uso personal tales
                                                    como: cremas corporales, desodorantes, bloqueadores solares, pasta
                                                    dental, jabones, cepillos de dientes, shampoo, navajas de rasurar,
                                                    lociones, fijadores de cabello, medicamentos, tazas y/o termos, dulces y
                                                    demás erogaciones que se consideren como personales; así como, bebidas
                                                    alcohólicas, cigarros, etc.</li>
                                                <li class="lineamiento">Los CFDI de hospedaje deberán indicar el nombre del
                                                    comisionado, las
                                                    fechas de entrada y salida del hotel, o período de estancia.</li>
                                                <li class="lineamiento">La fecha de emisión de los CFDI deberá ser dentro
                                                    del período de
                                                    comisión (día y hora), o en su caso, deberán adjuntarse tickets o
                                                    documentos que comprueben el momento de la erogación, de otra forma no
                                                    serán considerados en la comprobación.</li>
                                                <li class="lineamiento">Los recursos otorgados para viáticos no podrán
                                                    utilizarse dentro de la
                                                    CDMX y área conurbada.</li>
                                                <li class="lineamiento">Si durante el ejercicio fiscal en curso, se
                                                    comprueba que algún CFDI ha
                                                    sido cancelado, duplicado, se solicitará el reintegro que ampare el
                                                    importe de dicha factura.</li>
                                                <li class="lineamiento">No se podrán presentar facturas compartidas entre
                                                    comisionados.</li>
                                                <li class="lineamiento">Sólo serán reembolsados los CFDI's en su totalidad y
                                                    en el caso de
                                                    comprobación de anticipos se tomará el mismo criterio, por lo que se
                                                    deberá evitar facturar productos o consumos no permitidos.</li>
                                                <li class="lineamiento">Los viáticos se pagarán a la cuenta registrada en el
                                                    Oficio de Comisión
                                                    Nacional o el Oficio de Comisión Internacional.</li>
                                                <li class="lineamiento">A la entrega de la comprobación sólo se recibirá la
                                                    versión XML
                                                    original.</li>
                                                <li class="lineamiento">No se otorgarán viáticos a los comisionados que no
                                                    hayan presentado su
                                                    comprobación de anticipos anteriores.</li>
                                                <li class="lineamiento">Si en el proceso de revisión existen observaciones
                                                    en una comprobación,
                                                    se rechazará la comprobación en su totalidad y se podrá reingresar una
                                                    vez solventadas dichas observaciones, considerando el periodo
                                                    establecido de 5 días para presentar la documentación comprobatoria.
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                            data-bs-target="#miModal">
                            Condiciones
                        </button>
                        <a href="{{ route('acusePDF', ['idComision' => Crypt::encryptString($query->idcomision)]) }}"
                            class="btn btn-sm btn-outline-primary" target="_blank">
                            Acuse de comisión
                        </a>

                        <a href="{{ route('solicitudPDF', ['idComision' => Crypt::encryptString($query->idcomision)]) }}"
                            class="btn btn-sm btn-outline-primary" target="_blank">
                            Solicitud de comisión
                        </a>
                        <h1 class="mb-4 display-4">Ingrese las facturas de su comisión para su comprobación.</h1>

                        <p class="amount-display">El importe total a comprobar en esta comisión es de:
                            <span class="amount-value total-original">
                                ${{ number_format($query->importe, 2) }}
                            </span>
                        </p>

                        <p class="amount-display">Importe acumulado (Facturas):
                            <span class="amount-value accumulated">
                                ${{ number_format($facturasAcumuladas, 2) }}
                            </span>
                        </p>

                        <p class="amount-display">Importe acumulado (Gastos Simples):
                            <span class="amount-value simple-expenses">
                                ${{ number_format($gastosSinComprobanteTotal, 2) }}
                            </span>
                        </p>

                        <p class="amount-display" id="importeAcumuladoLabel">Importe Acumulado TOTAL:
                            <span class="amount-value total-comprobado">

                                ${{ number_format($importeTotalComprobado, 2) }}
                            </span>
                        </p>

                        @php
                            $saldoRestante = $query->importe - $importeTotalComprobado;
                        @endphp

                        <p class="amount-display" id="importeRestanteLabel">Saldo Restante:
                            <span class="amount-value">
                                ${{ number_format($saldoRestante, 2) }}
                            </span>
                        </p>
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
                            <a href="{{ route('gastosSinComprobar', ['idComision' => Crypt::encryptString($query->idcomision)]) }}"
                                class="btn btn-sm btn-outline-warning">
                                Cargar Gastos Sin comprobante
                            </a>

                            <a href="{{ route('updateComprobacion', ['idComision' => $query->idcomision]) }}"
                                class="btn btn-sm btn-outline-primary">
                                Guardar Avance
                            </a>

<a href="{{ route('detallesComision', ['idComision' =>  Crypt::encryptString($query->idcomision)]) }}"
   class="btn btn-sm btn-outline-success"
   id="final-comprobacion"
   data-url="{{ route('detallesComision', ['idComision' =>  Crypt::encryptString($query->idcomision)]) }}"
   data-update-url="{{ route('updateRevision', ['idComision' =>  Crypt::encryptString($query->idcomision)]) }}">
   Finalizar Comprobación
</a>
                        </div>
                    </div>
                    <input type="hidden" id="currentCommissionId" value="{{ $idComision }}">
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

            let importeTotalOriginal = parseFloat({{ $query->importe ?? 0 }});
            let importeAcumulado = parseFloat({{ $facturasAcumuladas ?? 0 }});
            let importeRestante = importeTotalOriginal - importeAcumulado;



            document.addEventListener('DOMContentLoaded', function() {
                var validationResults = document.getElementById('validation-results');
                var resultsList = document.getElementById('results-list');
                var noResultsMessage = document.getElementById('no-results-message');

                var importeRestanteLabel = document.getElementById('importeRestanteLabel');
                var importeRestanteSpan = importeRestanteLabel.querySelector('.amount-value');

                var importeAcumuladoLabel = document.getElementById('importeAcumuladoLabel');
                var importeAcumuladoSpan = importeAcumuladoLabel.querySelector('.amount-value');

                const idComisionInput = document.querySelector('input[name="idcomision"]');
                const maxFilesizeMB = 10;

                function updateImporteRestanteDisplay() {
                    importeRestanteSpan.textContent = `$${importeRestante.toFixed(2)}`;
                    if (importeRestante < 0) {
                        importeRestanteSpan.classList.add('negative');
                    } else {
                        importeRestanteSpan.classList.remove('negative');
                    }
                }

                function updateImporteAcumuladoDisplay() {
                    importeAcumuladoSpan.textContent = `$${importeAcumulado.toFixed(2)}`;
                }

                updateImporteRestanteDisplay();
                updateImporteAcumuladoDisplay();

                const commonDropzoneOptions = {
                    paramName: "file",
                    maxFilesize: maxFilesizeMB,
                    addRemoveLinks: true,
                    dictRemoveFile: "Eliminar archivo",
                    url: "{{ route('xml.process-multiple') }}",
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    init: function() {
                        this.on("success", function(file, response) {
                            noResultsMessage.classList.add('hidden');
                            var listItem = document.createElement('li');
                            listItem.className = 'p-3 rounded-md flex justify-between items-center';

                            if (response.status === 'success') {
                                listItem.classList.add('bg-green-50', 'border', 'border-green-300',
                                    'text-green-800');
                                listItem.innerHTML =
                                    `<span class="font-medium">${response.filename}:</span> <span>✅ ${response.message}</span>`;
                                if (response.invoice_amount) {
                                    let currentInvoiceAmount = parseFloat(response.invoice_amount);
                                    importeRestante -= currentInvoiceAmount;
                                    importeAcumulado += currentInvoiceAmount;
                                    updateImporteRestanteDisplay();
                                    updateImporteAcumuladoDisplay();
                                    $('#facturas-table').DataTable().ajax.reload();
                                }
                            } else if (response.status === 'warning') {
                                listItem.classList.add('bg-yellow-50', 'border',
                                    'border-yellow-300', 'text-yellow-800');
                                listItem.innerHTML =
                                    `<span class="font-medium">${response.filename}:</span> <span>⚠️ ${response.message}</span>`;
                            } else if (response.status === 'error') {
                                listItem.classList.add('bg-red-50', 'border', 'border-red-300',
                                    'text-red-800');
                                listItem.innerHTML =
                                    `<span class="font-medium">${response.filename}:</span> <span>❌ ${response.message}</span>`;
                            } else {
                                listItem.classList.add('bg-gray-50', 'border', 'border-gray-300',
                                    'text-gray-800');
                                listItem.innerHTML =
                                    `<span class="font-medium">${response.filename}:</span> <span>ℹ️ Respuesta inesperada: ${JSON.stringify(response)}</span>`;
                            }
                            resultsList.prepend(listItem);
                            setTimeout(() => this.removeFile(file), 3000);
                        });

                        this.on("error", function(file, message, xhr) {
                            noResultsMessage.classList.add('hidden');
                            var listItem = document.createElement('li');
                            listItem.className =
                                'p-3 rounded-md bg-red-50 border border-red-300 text-red-800 flex justify-between items-center';
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
                            listItem.innerHTML =
                                `<span class="font-medium">${file.name}:</span> <span>❌ ${displayMessage}</span>`;
                            resultsList.prepend(listItem);
                            setTimeout(() => this.removeFile(file), 3000);
                        });
                    }
                };

                var xmlDropzone = new Dropzone("#xml-dropzone", {
                    ...commonDropzoneOptions,
                    acceptedFiles: ".xml",
                    dictDefaultMessage: "Arrastra tus archivos XML aquí o haz clic para seleccionarlos.",
                    dictInvalidFileType: "Solo puedes subir archivos XML."
                });

                var pdfDropzone = new Dropzone("#pdf-dropzone", {
                    ...commonDropzoneOptions,
                    acceptedFiles: ".pdf",
                    dictDefaultMessage: "Arrastra tus comprobantes en PDF aquí o haz clic para seleccionarlos.",
                    dictInvalidFileType: "Solo puedes subir archivos PDF."
                });

                $(function() {
                    var currentCommissionId = $('#currentCommissionId').val();
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
                                data: 'descripcion',
                                name: 'descripcion'
                            },
                            {
                                data: 'tipo_gasto',
                                name: 'facturas.idtypefactura',
                                orderable: false,
                                searchable: true,
                                render: function(data, type, row) {
                                    if (data && data !== "") {
                                        return `<span class="font-bold text-green-700">${tipoGastoOptions[data] || data}</span>`;
                                    }
                                    let selectHtml = `<select class="form-select tipo-gasto-select"
                                        data-idfactura="${row.idfactura}"
                                        style="min-width: 150px;">`;
                                    selectHtml += `<option value="">Clasificar</option>`;

                                    for (const key in tipoGastoOptions) {
                                        const selected = (key === data) ? 'selected' : '';
                                        selectHtml +=
                                            `<option value="${key}" ${selected}>${tipoGastoOptions[key]}</option>`;
                                    }
                                    selectHtml += `</select>`;
                                    return selectHtml;
                                }
                            },
                            {
                                data: 'status',
                                name: 'status'
                            },
                            {
                                data: 'cancelacion',
                                render: function(data, type, row) {
                                    if (row.cancelacion === '1') {
                                        return '<strong style="color: red;">FACTURA ANULADA, LAS OBSERVACIONES SON LAS SIGUIENTES:</strong> ' +
                                            (
                                                row.observaciones ? row.observaciones :
                                                'Sin observaciones.'
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
                                data: 'btn_f'
                            },
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

                    const url = '{{ route('facturas.clasificarFactura', ['idfactura' => '__ID__']) }}'.replace(
                        '__ID__', idFactura);

                    console.log(idFactura);

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
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const botonFinalizar = document.getElementById('final-comprobacion');

                if (botonFinalizar) {
                    botonFinalizar.addEventListener('click', function(e) {
                        e.preventDefault();

                        const updateUrl = this.getAttribute('data-update-url');

                        Swal.fire({
                            title: '¿Confirmar finalización?',
                            text: "Estás a punto de finalizar tu comprobación para pasar a la siguiente etapa. No podrás añadir más facturas.",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Sí, finalizar",
                            cancelButtonText: "Cancelar"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                botonFinalizar.disabled = true;

                                Swal.fire({
                                    title: 'Actualizando...',
                                    text: 'Por favor, espera un momento.',
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                });

                                fetch(updateUrl, {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector(
                                                'meta[name="csrf-token"]').getAttribute(
                                                'content'),
                                            'Accept': 'application/json',
                                            'X-Requested-With': 'XMLHttpRequest'
                                        }
                                    })
                                    .then(async response => {
                                        const data = await response.json();
                                        if (!response.ok) {
                                            throw new Error(data.message ||
                                                'Error en el servidor');
                                        }
                                        return data;
                                    })
                                    .then(data => {
                                        if (data.success) {
                                            Swal.fire({
                                                title: '¡Logrado!',
                                                text: 'La revisión se ha actualizado correctamente.',
                                                icon: 'success',
                                                timer: 2000,
                                                showConfirmButton: false
                                            });
                                            setTimeout(() => {
                                                window.location.href = data.redirect_url;
                                            }, 1500);
                                        } else {
                                            throw new Error(data.message || 'Error inesperado');
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        botonFinalizar.disabled = false;
                                        Swal.fire({
                                            title: 'No se puede finalizar',
                                            text: error.message,
                                            icon: 'error',
                                            confirmButtonText: 'Revisar facturas'
                                        });
                                    });
                            }
                        });
                    });
                }
            });
        </script>
    @endsection
