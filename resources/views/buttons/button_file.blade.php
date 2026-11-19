@php
    $estatusRestringidos = [18, 21, 22, 23, 24, 25, 26, 12, 13, 14, 15, 241, 242];
    $deletes = [1, 2];
    $isAdmin = [1, 30, 29, 2];
@endphp
<div class="relative p-2 border rounded-lg bg-gray-50 mt-4">
    @if (
        !in_array($idstatuscomision, $estatusRestringidos) &&
            !in_array($cancelacion, $deletes) &&
            empty($id_factura_reemplazo))
        <button type="button"
            class="absolute top-1 right-1 flex items-center justify-center w-8 h-8 rounded-full text-red-600 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors"
            onclick="confirmarEliminacion('{{ route('facturas.delete', $idfactura) }}')" title="Eliminar registro">
            <svg class="w-5 h-5 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </button>
    @endif

    @if ($status != 'Cancelada')
        @if (!empty($pdf_path))
            <a class="flex items-center w-full h-10 px-3 mt-1 rounded hover:bg-gray-300 transition"
                href="{{ route('stream', ['file' => $pdf_path]) }}" target="_blank">
                <svg class="w-5 h-5 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <span class="ml-2 text-xs font-medium uppercase text-gray-600">Ver PDF</span>
            </a>
        @else
            <span class="text-[10px] text-gray-400 block px-3">PDF PENDIENTE DE ASIGNAR</span>
        @endif
    @endif
    <button type="button" class="flex items-center w-full h-10 px-3 mt-1 rounded hover:bg-gray-300 focus:outline-none"
        data-bs-toggle="modal" data-bs-target="#miModalDetalle_{{ $idfactura }}">
        <svg class="w-5 h-5 stroke-current text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
        </svg>
        <span class="ml-2 text-xs font-medium uppercase text-gray-600">Ver XML</span>
    </button>
    @if (!in_array($idstatuscomision, $estatusRestringidos))
        <div class="px-3 mt-2 flex flex-col gap-2">
            <button type="button" class="btn btn-sm btn-outline-info w-full text-xs" data-bs-toggle="modal"
                data-bs-target="#modalCorreccion_{{ $idfactura }}">
                Agregar ticket
            </button>

            @if ($status == 'Cancelada' || $cancelacion == '1')
                <a href="{{ route('comprobacionAdicional', ['idFactura' => $idfactura]) }}"
                    class="btn btn-sm btn-outline-danger w-full text-xs">Agregar corrección</a>
            @endif
        </div>
    @endif

    @if (!empty($nombres_archivos))
        <div class="flex flex-column gap-1 mt-2 px-3">
            @foreach (explode(',', $nombres_archivos) as $archivo)
                <a href="{{ route('downloadAditional', ['name_file' => Crypt::encryptString(trim($archivo))]) }}"
                    class="btn btn-sm btn-outline-success" target="_blank">
                    Ver Ticket
                </a>
                {{-- <a href="{{ route('downloadAditional', ['name_file' => trim($archivo)]) }}"
                    class="btn btn-sm btn-success text-xs py-1">
                    <i class="fas fa-ticket-alt mr-1"></i> Ver Ticket
                </a> --}}
            @endforeach
        </div>
    @endif
</div>
<div class="modal fade" id="miModalDetalle_{{ $idfactura }}" tabindex="-1" aria-labelledby="miModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="miModalLabel">Detalle del Comprobante Fiscal Digital por Internet (CFDI)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="cfdi-container" style="box-shadow: none; max-width: none; margin: 0; padding: 0;">
                    <h2><strong>Detalle del Comprobante Fiscal Digital por Internet (CFDI)</strong></h2>
                    <p>
                        <a href="{{ url('downloadXml/' . Crypt::encryptString($cfdi)) }}" class="btn btn-info mt-3" download>DESCARGA DE
                            XML</a>
                        @if (Auth::user()->idtype == 1)
                            <button type="button" class="btn btn-success mt-3" data-bs-toggle="modal"
                                data-bs-target="#anularFacturaModal_{{ $idfactura }}">
                                Rechazar/Aceptar Factura
                            </button>
                        @endif
                    </p>
                    <br>
                    <h3>Información General</h3>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td><strong>CFDI:</strong></td>
                                <td>{{ $cfdi }}</td>
                            </tr>
                            <tr>
                                <td><strong>Fecha de Factura:</strong></td>
                                <td>{{ $datefactura }}</td>
                            </tr>
                            <tr>
                                <td><strong>Descripción:</strong></td>
                                <td>{{ $descripcion }}</td>
                            </tr>
                            <tr>
                                <td><strong>Forma de Pago:</strong></td>
                                <td>{{ $formapago }}</td>
                            </tr>
                            <tr>
                                <td><strong>Código Postal (CP):</strong></td>
                                <td>{{ $cp }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <h3>Montos del Comprobante</h3>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td><strong>Subtotal:</strong></td>
                                <td>${{ $subtotal }}</td>
                            </tr>
                            <tr>
                                <td><strong>Importe Total:</strong></td>
                                <td>${{ $importe }}</td>
                            </tr>
                            <tr>
                                <td><strong>Propina:</strong></td>
                                <td>${{ $propina }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <h3>Datos de las Partes</h3>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td colspan="2"><strong>Emisor:</strong></td>
                            </tr>
                            <tr>
                                <td><strong>RFC:</strong></td>
                                <td>{{ $rfc_emisor }}</td>
                            </tr>
                            <tr>
                                <td><strong>Régimen Fiscal:</strong></td>
                                <td>{{ $regimenemisor }}</td>
                            </tr>
                            <tr>
                                <td colspan="2"><strong>Receptor:</strong></td>
                            </tr>
                            <tr>
                                <td><strong>RFC:</strong></td>
                                <td>{{ $rfc_receptor }}</td>
                            </tr>
                            <tr>
                                <td><strong>Régimen Fiscal:</strong></td>
                                <td>{{ $regimenreceptor }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <h3>Desglose de Impuestos y Retenciones</h3>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td><strong>IVA:</strong></td>
                                <td>${{ $iva }}</td>
                            </tr>
                            <tr>
                                <td><strong>IEPS:</strong></td>
                                <td>${{ $ieps }}</td>
                            </tr>
                            <tr>
                                <td><strong>ISH:</strong></td>
                                <td>${{ $ish }}</td>
                            </tr>
                            <tr>
                                <td><strong>Retención de IVA (RetenIVA):</strong></td>
                                <td>${{ $reteniva }}</td>
                            </tr>
                            <tr>
                                <td><strong>Retención de ISH (RetenISH):</strong></td>
                                <td>${{ $retenish }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="anularFacturaModal_{{ $idfactura }}" tabindex="-1"
    aria-labelledby="anularFacturaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="anularFacturaModalLabel">Validar Factura</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('anularFactura') }}" method="POST">
                    @csrf
                    <input type="hidden" name="idfactura" value="{{ $idfactura }}">
                    <div class="mb-3">
                        <label for="actionSelect_{{ $idfactura }}" class="form-label">Acción</label>
                        <select class="form-select action-select" id="actionSelect_{{ $idfactura }}"
                            name="action" onchange="showSelectedJustify(this)">
                            <option value="">Seleccionar un valor</option>
                            <option value="aceptar">Aceptar</option>
                            <option value="anular">Rechazar</option>
                            <option value="rechazar">No Corresponde a esta comisión</option>
                        </select>
                    </div>
                    <div class="col-md-12" id="justificacion_txt_{{ $idfactura }}" style="display: none;">
                        <label for="justificacion_{{ $idfactura }}" class="form-label">Justificación</label>
                        <textarea id="justificacion_{{ $idfactura }}" name="observaciones" class="form-control" rows="7"></textarea>
                    </div>
                    <div class="col-md-12" id="evidenciaSelect_{{ $idfactura }}" style="display: none;">
                        <label for="evidenciaSelect_{{ $idfactura }}" class="form-label">Tipo de Evidencia
                            Requerida</label>
                        <select class="form-select action-select" id="evidenciaSelect_{{ $idfactura }}"
                            name="evidenciaRequerida">
                            <option value="">Seleccionar un valor</option>
                            <option value="ticket">Ticket de Compra</option>
                            <option value="factura">Factura de Reemplazo</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalCorreccion_{{ $idfactura }}" tabindex="-1"
    aria-labelledby="modalCorreccionLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCorreccionLabel">Cargar comprobante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('saveTickets') }}" enctype="multipart/form-data"
                id="uploadFormCorreccion_{{ $idfactura }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="factura_id" value="{{ $idfactura }}">
                    <input type="hidden" name="idFactura" value="{{ $idfactura }}">
                    @if (isset($idcomision))
                        <input type="hidden" name="idComision" value="{{ $idcomision }}">
                    @endif

                    <div class="mb-3">
                        <label for="fileInputCorr_{{ $idfactura }}" class="form-label">Archivos</label>
                        <input class="form-control" type="file" name="files[]"
                            id="fileInputCorr_{{ $idfactura }}" multiple>
                    </div>

                    <div id="fileListCorr_{{ $idfactura }}" class="mt-3"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="submitButtonCorr_{{ $idfactura }}">
                        Cargar Archivos
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modals = document.querySelectorAll('[id^="miModalUpdate_"]');
        modals.forEach(modal => {
            const idfactura = modal.id.split('_')[1];
            const fileInput = document.getElementById(`fileInput_${idfactura}`);
            const fileList = document.getElementById(`fileList_${idfactura}`);
            const submitButton = document.getElementById(`submitButton_${idfactura}`);

            fileInput.addEventListener('change', (e) => {
                previewFiles(fileInput.files);
                updateSubmitButtonState(fileInput.files.length > 0);
            });

            function previewFiles(files) {
                fileList.innerHTML = '';
                if (files.length === 0) {
                    fileList.innerHTML = '<p class="text-danger">No se han seleccionado archivos.</p>';
                    return;
                }
                const ul = document.createElement('ul');
                ul.className = 'list-group';
                Array.from(files).forEach(file => {
                    const li = document.createElement('li');
                    li.className =
                        'list-group-item d-flex justify-content-between align-items-center';
                    li.textContent = file.name;
                    const span = document.createElement('span');
                    span.className = 'badge bg-primary rounded-pill';
                    span.textContent = (file.size / 1024).toFixed(2) + ' KB';
                    li.appendChild(span);
                    ul.appendChild(li);
                });
                fileList.appendChild(ul);
            }

            function updateSubmitButtonState(hasFiles) {
                submitButton.disabled = !hasFiles;
            }

            updateSubmitButtonState(false);
        });
    });

    function showSelectedJustify(selectElement) {
        const idFactura = selectElement.id.split('_')[1];
        const justificacionDiv = document.getElementById(`justificacion_txt_${idFactura}`);
        const justificacionTextarea = document.getElementById(`justificacion_${idFactura}`);
        const evidenciaSelect = document.getElementById(`evidenciaSelect_${idFactura}`);
        if (selectElement.value === 'anular') {
            justificacionDiv.style.display = 'block';
            evidenciaSelect.style.display = 'block';
            justificacionTextarea.setAttribute('required', 'required');
            justificacionTextarea.setAttribute('required', 'required');
        } else {
            justificacionDiv.style.display = 'none';
            evidenciaSelect.style.display = 'none';
            justificacionTextarea.removeAttribute('required');
            evidenciaSelect.removeAttribute('required');
            evidenciaSelect.value = '';
            justificacionTextarea.value = '';
        }
    }

    function confirmarEliminacion(url) {
        Swal.fire({
            title: '¿Está seguro de eliminar este registro?',
            text: "Se eliminarán permanentemente la factura y todos sus documentos asociados (tickets y PDF), si cuenta con una factura de reemplazo esta acción no procederá. ¿Deseas continuar?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        })
    }

    function confirmarRestauracion(url) {
        Swal.fire({
            title: '¿Está seguro de eliminar las observaciones de la factura seleccionada?',
            text: "Se eliminarán permanentemente las observaciones de esta factura. ¿Deseas continuar?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, limpiar factura',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        })
    }
</script>
