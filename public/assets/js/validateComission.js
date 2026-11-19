/**
 * validateComission.js
 * Logic for massive validation and management of commissions.
 */

console.log('archivo de js ')
let table
let selectedIds = []
let selectedComisionesData = []

// Persistencia de checkboxes entre páginas
const selectedSet = new Set()

function syncCheckboxes() {
    $('#myTable tbody input[name="comision_ids[]"]').each(function () {
        $(this).prop('checked', selectedSet.has($(this).val()))
    })
    updateSelectAllState()
    updateSelectionCounter()
}

function updateSelectAllState() {
    const $visible = $('#myTable tbody input[name="comision_ids[]"]')
    const $checked = $visible.filter(':checked')
    $('#selectAll').prop(
        'checked',
        $visible.length > 0 && $visible.length === $checked.length
    )
}

function updateSelectionCounter() {
    const count = selectedSet.size
    if (count > 0) {
        $('#selectionCounter')
            .text(count + ' comisiones seleccionadas')
            .show()
    } else {
        $('#selectionCounter').hide()
    }
}

function getSelectedIdsFromSet() {
    return Array.from(selectedSet)
}

function clearSelection() {
    selectedSet.clear()
    $('#selectAll').prop('checked', false)
    $('#myTable tbody input[name="comision_ids[]"]').prop('checked', false)
    updateSelectionCounter()
}

// Funciones existentes
function clearEditarMasivoForm() {
    $('#newStatusMasivo').val('')
    $('#clcMasivo').val('')
    $('#importeComision').val('')
    $('#deposito').val('')
    $('#documentacionFisica').val('')
    $('#importeComisionado').val('')
    $('#notaMasiva').val('')
    $('#urMasiva').val('')

    $('#fechaFinAmpliacion').val('')
    $('#motivoAmpliacion').val('')
    $('#fechaInicioCalculo').val('')
    $('#mensajeErrorAmpliacion').text('')
    $('#ampliacionForm').hide()
    $('#confirmarEdicionMasiva').prop('disabled', false)
}

function calcularAmpliacion() {
    const statusSelected = $('#newStatusMasivo').val()
    const fechaNuevaFinalStr = $('#fechaFinAmpliacion').val()
    const fechaFinalActualStr = $('#fechaInicioCalculo').val()

    if (!fechaNuevaFinalStr || !fechaFinalActualStr) {
        $('#mensajeErrorAmpliacion').text('Debes seleccionar las fechas correspondientes.')
        $('#confirmarEdicionMasiva').prop('disabled', true)
        return
    }

    const fechaFinalActual = moment(fechaFinalActualStr)
    const fechaNuevaFinal = moment(fechaNuevaFinalStr)

    if (statusSelected === '11') {
        if (fechaNuevaFinal.isBefore(fechaFinalActual)) {
            $('#mensajeErrorAmpliacion').text('La nueva fecha final debe ser posterior a ' + fechaFinalActualStr)
            $('#confirmarEdicionMasiva').prop('disabled', true)
            return
        }
        const diasAdicionales = fechaNuevaFinal.diff(fechaFinalActual, 'days')
        if (diasAdicionales === 0) {
            $('#mensajeErrorAmpliacion').text('La nueva fecha debe ser posterior para aplicar una ampliación.')
            $('#confirmarEdicionMasiva').prop('disabled', true)
            return
        }
        $('#mensajeErrorAmpliacion').text('Se calcularán ' + diasAdicionales + ' días adicionales con el importe correspondiente.')
        $('#confirmarEdicionMasiva').prop('disabled', false)
    }

    else if (statusSelected === '14') {
        if (fechaNuevaFinal.isAfter(fechaFinalActual)) {
            $('#mensajeErrorAmpliacion').text('La fecha de terminación debe ser anterior a la fecha final original: ' + fechaFinalActualStr)
            $('#confirmarEdicionMasiva').prop('disabled', true)
            return
        }
        const diasMenos = fechaFinalActual.diff(fechaNuevaFinal, 'days')
        if (diasMenos === 0) {
            $('#mensajeErrorAmpliacion').text('La fecha debe ser anterior para aplicar una terminación anticipada.')
            $('#confirmarEdicionMasiva').prop('disabled', true)
            return
        }
        $('#mensajeErrorAmpliacion').text('Se recortará la comisión por ' + diasMenos + ' días. Se reajustará el importe correspondiente.')
        $('#confirmarEdicionMasiva').prop('disabled', false)
    }
}

function updateStatusOptions() {
    const STATUS_COMISION_DATA = window.validateConfig.STATUS_COMISION_DATA
    const userType = window.validateConfig.USER_TYPE
    const tabType = $('.tab-btn.active').data('tab-type')

    let idModalidad = tabType

    const $statusSelect = $('#newStatusMasivo')
    $statusSelect
        .empty()
        .append(
            '<option value="" selected disabled>Selecciona un estado</option>'
        )

    let availableStatuses = STATUS_COMISION_DATA
    let filteredOptions = []

    if (
        idModalidad !== null &&
        idModalidad !== undefined &&
        idModalidad !== ''
    ) {
        idModalidad = parseInt(idModalidad)
        filteredOptions = availableStatuses.filter(function (status) {
            return parseInt(status.idModalidad) === idModalidad
        })
    } else {
        filteredOptions = availableStatuses
    }

    if (userType === 2) {
        if (idModalidad === 1) {
            filteredOptions = availableStatuses.filter(function (status) {
                return (
                    parseInt(status.idModalidad) === 1 &&
                    [3, 11, 14, 25].includes(parseInt(status.idstatuscomision)) // <-- Añadido 14 aquí si aplica a modalidad 1
                )
            })
        } else if (idModalidad === 2) {
            filteredOptions = availableStatuses.filter(function (status) {
                return (
                    parseInt(status.idModalidad) === 2 &&
                    [26].includes(parseInt(status.idstatuscomision))
                )
            })
        } else {
            filteredOptions = availableStatuses.filter(function (status) {
                return [3, 11, 14, 25, 26].includes(
                    parseInt(status.idstatuscomision)
                )
            })
        }
    } else if (userType === 2 && !idModalidad) {
        filteredOptions = availableStatuses.filter(function (status) {
            return [3, 11, 14, 25, 26].includes(parseInt(status.idstatuscomision))
        })
    }

    filteredOptions.forEach(function (status) {
        $statusSelect.append(
            $('<option>', {
                value: status.idstatuscomision,
                text: status.statuscomision
            })
        )
    })

    clearEditarMasivoForm()
}

$(document).ready(function () {
    // Initial status select setup
    updateStatusOptions()

    $('body').append(`
            <div id="loadingOverlay" style="
                display:none; position:fixed; inset:0;
                background:rgba(0,0,0,0.45); z-index:9999;
                align-items:center; justify-content:center; flex-direction:column;
            ">
                <div class="spinner-border text-light" style="width:3.5rem;height:3.5rem;" role="status"></div>
                <p class="text-white mt-3 fw-bold fs-5">Cargando datos...</p>
            </div>
        `)

    $('#myTable').on('preXhr.dt', function () {
        $('#loadingOverlay').css('display', 'flex')
    })

    $('#myTable').on('xhr.dt', function () {
        $('#loadingOverlay').css('display', 'none')
    })

    $('#myTable').on('draw.dt', function () {
        $('#loadingOverlay').css('display', 'none')
    })

    $(
        '<span id="selectionCounter" class="badge bg-primary ms-3" style="display:none; vertical-align:middle;"></span>'
    ).insertAfter('#selectAll')

    $('#myTable thead tr').clone(true).appendTo('#myTable thead')
    $('#myTable thead tr:eq(1) th').each(function (i) {
        if (i > 0 && i < 11) {
            var title = $(this).text()
            $(this).html(
                '<input class="filters" id="celda' +
                i +
                '" type="text" placeholder="' +
                title +
                '" />'
            )

            $('input', this).on('keyup change', function () {
                const $input = $(this)
                const val = $input.val()

                clearTimeout($input.data('searchTimer'))

                $input.data(
                    'searchTimer',
                    setTimeout(function () {
                        if (table && table.column(i).search() !== val) {
                            table.column(i).search(val).draw()
                        }
                    }, 200)
                )
            })
        } else {
            $(this).empty()
        }
    })

    table = $('#myTable').DataTable({
        destroy: true,
        serverSide: true,
        orderCellsTop: true,
        ajax: {
            url: window.validateConfig.routes.listJson,
            data: function (d) {
                d.tabType = $('.tab-btn.active').data('tab-type')
            }
        },
        columns: [
            {
                data: null,
                orderable: false,
                render: function (data, type, row) {

                    const idStatus = parseInt(row.idstatuscomision)
                    const userType = parseInt(window.validateConfig.USER_TYPE)
                    const id = String(row.idcomision)

                    if (
                        userType === 1 ||
                        !window.validateConfig.CANCELACION_STATUSES.includes(
                            idStatus
                        )
                    ) {
                        const checked = selectedSet.has(id) ? ' checked' : ''
                        return (
                            '<input type="checkbox" name="comision_ids[]" value="' +
                            id +
                            '"' +
                            checked +
                            '>'
                        )
                    }
                    return ''
                }
            },
            {
                data: 'comision',
                render: function (data, type, row) {
                    if (!data || data === 'null') {
                        return 'Folio pendiente de aprobación'
                    }
                    const idModalidad = String(row.idmodalidad)
                    if (idModalidad == '1') {
                        return 'VA_' + data + '_' + row.yearcomision
                    } else if (idModalidad == '2') {
                        return 'VD_' + data + '_' + row.yearcomision
                    }
                    return data
                }
            },
            { data: 'oficio', name: 'comisiones.oficio' },
            { data: 'nombre_completo' },
            { data: 'destino', name: 'comisiones.destino' },
            {
                data: 'importe',
                name: 'comisiones.importe',
                render: function (data, type, row) {
                    if (type === 'display' || type === 'filter') {
                        return (
                            '$' +
                            parseFloat(data).toLocaleString('es-MX', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            })
                        )
                    }
                    return data
                }
            },
            {
                data: 'initialdate',
                name: 'comisiones.initialdate',
                render: function (data, type, row) {
                    if (data) {
                        let date = new Date(data)
                        let day = String(date.getDate()).padStart(2, '0')
                        let month = String(date.getMonth() + 1).padStart(2, '0')
                        let year = date.getFullYear()
                        return `${day}-${month}-${year}`
                    }
                    return data
                }
            },
            {
                data: 'finaldate',
                name: 'comisiones.finaldate',
                render: function (data, type, row) {
                    if (data) {
                        let date = new Date(data)
                        let day = String(date.getDate()).padStart(2, '0')
                        let month = String(date.getMonth() + 1).padStart(2, '0')
                        let year = date.getFullYear()
                        return `${day}-${month}-${year}`
                    }
                    return data
                }
            },
            { data: 'clc', name: 'comisiones.clc' },
            { data: 'statuscomision', name: 'statuscomision.statuscomision' },
            { data: 'btn_v' }
        ],
        language: {
            url: '/vendor/i18n/datatables/es-ES.json'
        },
        "lengthMenu": [[10, 25, 100, -1], [10, 25, 100, "Todos"]],
        "pageLength": 10,
        dom: 'Blfrtip',
        buttons: [
            {
                extend: 'excel',
                text: '<button type="button" class="btn btn-success round">Comisiones</button>',
                title: 'Comisiones-Excel'
            }
        ]
    })

    table.on('draw.dt', function () {
        syncCheckboxes()
    })

    $('.tab-btn').on('click', function () {
        $('.tab-btn').removeClass('active')
        $(this).addClass('active')
        clearSelection()
        table.ajax.reload()
        updateStatusOptions()
    })

    $('#selectAll').on('click', function () {
        const isChecked = this.checked
        $('#myTable tbody input[name="comision_ids[]"]').each(function () {
            const id = $(this).val()
            $(this).prop('checked', isChecked)
            if (isChecked) {
                selectedSet.add(id)
            } else {
                selectedSet.delete(id)
            }
        })
        updateSelectionCounter()
    })

    $('#myTable tbody').on('change', 'input[type="checkbox"]', function () {
        const id = $(this).val()
        if (this.checked) {
            selectedSet.add(id)
        } else {
            selectedSet.delete(id)
        }
        updateSelectAllState()
        updateSelectionCounter()
    })

    // Aprobar
    $('#aprobar-comisiones').on('click', function () {
        const $btn = $(this);
        const selectedIds = getSelectedIdsFromSet();

        if (selectedIds.length === 0) {
            Swal.fire({
                title: 'Selecciona al menos una comisión.',
                icon: 'warning',
                draggable: true
            });
            return;
        }

        Swal.fire({
            title: '¿Estás seguro?',
            text: `Vas a aprobar masivamente ${selectedIds.length} comisión(es).`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, aprobar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {

                $btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Procesando...'
                );

                $.ajax({
                    url: window.validateConfig.routes.aprobarMasivo,
                    method: 'POST',
                    data: {
                        _token: window.validateConfig.csrf,
                        comision_ids: selectedIds,
                    },
                    success: function (response) {
                        Swal.fire({
                            title: '¡Logrado!',
                            text: response.message,
                            icon: 'success',
                            draggable: true
                        });
                        clearSelection();
                        table.ajax.reload();
                    },
                    error: function (xhr) {
                        let errorMsg = xhr.responseJSON && xhr.responseJSON.message
                            ? xhr.responseJSON.message
                            : 'Hubo un error al aprobar las comisiones.';

                        Swal.fire({
                            title: 'Error',
                            text: errorMsg,
                            icon: 'error'
                        });
                    },
                    complete: function () {
                        $btn.prop('disabled', false).html(
                            '<i class="bx bx-check me-2"></i>Aprobación Masiva'
                        );
                    }
                });
            }
        });
    });
    $('#cancelar-comisiones').on('click', function () {
        selectedIds = getSelectedIdsFromSet()

        if (selectedIds.length > 0) {
            $('#count-cancelar').text(selectedIds.length)
            $('#cancelarModal').modal('show')
        } else {
            Swal.fire({
                title: 'Selecciona al menos una comisión.',
                icon: 'warning',
                draggable: true
            })
        }
    })

    $('#confirmarCancelacion').on('click', function () {
        const justificacion = $('#justificacionCancelacion').val()
        if (!justificacion) {
            alert('El campo de justificación es obligatorio.')
            return
        }

        const $btn = $(this)
        $btn.prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm"></span> Cancelando...'
        )

        $.ajax({
            url: window.validateConfig.routes.cancelarMasivo,
            method: 'POST',
            data: {
                _token: window.validateConfig.csrf,
                comision_ids: selectedIds,
                justificacion: justificacion
            },
            success: function (response) {
                Swal.fire({
                    title: '¡Éxito!',
                    text: response.message,
                    icon: 'success',
                    draggable: true
                })
                $('#cancelarModal').modal('hide')
                clearSelection()
                table.ajax.reload()
                $btn.prop('disabled', false).html('Confirmar Cancelación')
            },
            error: function (xhr) {
                let errorMsg =
                    xhr.status === 422 && xhr.responseJSON.errors
                        ? Object.values(xhr.responseJSON.errors)
                            .flat()
                            .join('<br>')
                        : xhr.responseJSON && xhr.responseJSON.message
                            ? xhr.responseJSON.message
                            : 'Ocurrió un error inesperado.'
                Swal.fire({ title: 'Error', html: errorMsg, icon: 'error' })
                if (xhr.status !== 422) $('#cancelarModal').modal('hide')
                $btn.prop('disabled', false).html('Confirmar Cancelación')
            }
        })
    })

    // Editar masivo
    $('#editar-comisiones').on('click', function () {
        selectedIds = getSelectedIdsFromSet()
        selectedComisionesData = []
        let firstFinalDate = null
        let latestFinalDate = moment('1900-01-01')
        let datesMatch = true

        $('#myTable tbody input[name="comision_ids[]"]:checked').each(
            function () {
                const row = table.row($(this).closest('tr')).data()
                if (row) {
                    selectedComisionesData.push(row)
                    const finalDateMoment = moment(row.finaldate)
                    if (firstFinalDate === null) firstFinalDate = row.finaldate
                    else if (firstFinalDate !== row.finaldate)
                        datesMatch = false
                    if (finalDateMoment.isAfter(latestFinalDate))
                        latestFinalDate = finalDateMoment
                }
            }
        )

        if (selectedIds.length > 0) {
            clearEditarMasivoForm()
            updateStatusOptions()
            if (datesMatch && firstFinalDate) {
                $('#fechaInicioCalculo').val(firstFinalDate)
            } else {
                $('#fechaInicioCalculo').val(
                    latestFinalDate.format('YYYY-MM-DD')
                )
                $('#mensajeErrorAmpliacion').text(
                    'Advertencia: Las comisiones tienen fechas finales diferentes. El movimiento se calculará a partir de la fecha final más reciente (' +
                    latestFinalDate.format('YYYY-MM-DD') +
                    ').'
                )
            }
            $('#comision-count').text(selectedIds.length)
            $('#editarMasivoModal').modal('show')
        } else {
            Swal.fire({
                title: 'Selecciona al menos una comisión.',
                icon: 'warning',
                draggable: true
            })
        }
    })

    $('#btnEjecutarAccion').on('click', function (e) {
        e.preventDefault()
        const selectedAction = $('input[name="massAction"]:checked').val()
        const selectedIdsLocal = getSelectedIdsFromSet()

        if (
            selectedIdsLocal.length === 0 &&
            (selectedAction === 'download_bancario' ||
                selectedAction === 'download_sicop' ||
                selectedAction === 'zip_archivos')
        ) {
            Swal.fire({
                title: 'Error',
                text: 'Por favor, seleccione al menos una comisión.',
                icon: 'warning',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            })
            return
        }

        let urlDescarga = ''
        let fileNameAction = ''
        if (selectedAction === 'download_bancario') {
            urlDescarga = window.validateConfig.routes.reportes
            fileNameAction = 'LAYOUT BANCARIO'
        } else if (selectedAction === 'download_sicop') {
            urlDescarga = window.validateConfig.routes.reportesSicop
            fileNameAction = 'LAYOUT SICOP'
        } else if (selectedAction === 'zip_archivos') {
            urlDescarga = window.validateConfig.routes.reportesZip
            fileNameAction = 'ZIP ARCHIVOS'
        } else return

        Swal.fire({
            title: 'Preparando descarga...',
            icon: 'info',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        })

        const iframeName = 'download_iframe_' + Date.now()
        const $iframe = $('<iframe>', {
            id: iframeName,
            name: iframeName,
            style: 'display: none;'
        })
        const form = $('<form>', {
            action: urlDescarga,
            method: 'POST',
            target: iframeName
        }).append(
            $('<input>', {
                name: '_token',
                value: window.validateConfig.csrf,
                type: 'hidden'
            })
        )

        selectedIdsLocal.forEach(function (id) {
            form.append(
                $('<input>', { name: 'ids[]', value: id, type: 'hidden' })
            )
        })
        $('body').append($iframe).append(form)
        form.submit()
        setTimeout(function () {
            form.remove()
        }, 500)
        clearSelection()
        table.ajax.reload()
    })

    $('#confirmarEdicionMasiva').on('click', function () {
        const newStatusId = $('#newStatusMasivo').val();
        const isMovimientoFechas = newStatusId === '11' || newStatusId === '14';

        let ampliacionData = {};
        if (isMovimientoFechas) {
            ampliacionData.dateReasignacionFinal = $('#fechaFinAmpliacion').val();
            ampliacionData.motivoAmpliacion = $('#motivoAmpliacion').val();

            if (
                !ampliacionData.dateReasignacionFinal ||
                !ampliacionData.motivoAmpliacion
            ) {
                Swal.fire({
                    title: 'Debes completar las fechas y la justificación obligatoriamente.',
                    icon: 'warning',
                    draggable: true
                });
                return;
            }

            const fechaFinalActual = moment($('#fechaInicioCalculo').val());
            const fechaNuevaFinal = moment($('#fechaFinAmpliacion').val());

            if (newStatusId === '11' && fechaNuevaFinal.diff(fechaFinalActual, 'days') <= 0) {
                Swal.fire({
                    title: 'La nueva fecha debe ser posterior para una ampliación.',
                    icon: 'warning',
                    draggable: true
                });
                return;
            }
            if (newStatusId === '14' && fechaFinalActual.diff(fechaNuevaFinal, 'days') <= 0) {
                Swal.fire({
                    title: 'La nueva fecha debe ser anterior para una terminación anticipada.',
                    icon: 'warning',
                    draggable: true
                });
                return;
            }
        }

        let postData = {
            _token: window.validateConfig.csrf,
            comision_ids: selectedIds
        };

        const addIfNotEmpty = (key, value) => {
            if (value !== null && value !== undefined && String(value).trim() !== '') {
                postData[key] = String(value).trim();
            }
        };

        addIfNotEmpty('idstatuscomision', $('#newStatusMasivo').val());
        addIfNotEmpty('clc', $('#clcMasivo').val());
        addIfNotEmpty('documentacionFisica', $('#documentacionFisica').val());
        addIfNotEmpty('importComision', $('#importComision').val());
        addIfNotEmpty('deposito', $('#deposito').val());
        addIfNotEmpty('importComisionado', $('#importComisionado').val());
        addIfNotEmpty('nota', $('#notaMasiva').val());
        addIfNotEmpty('oficio', $('#urMasiva').val());

        if (isMovimientoFechas) {
            postData = { ...postData, ...ampliacionData };
        }

        const keysEnviadas = Object.keys(postData).filter(
            (key) => key !== '_token' && key !== 'comision_ids'
        );

        if (keysEnviadas.length === 0) {
            Swal.fire({
                title: 'Atención',
                text: 'Debes ingresar al menos un dato para actualizar.',
                icon: 'warning',
                draggable: true
            });
            return;
        }

        $.ajax({
            url: window.validateConfig.routes.editarMasivo,
            method: 'POST',
            data: postData,
            success: function (response) {
                Swal.fire({
                    title: response.message,
                    icon: 'success',
                    draggable: true
                });
                $('#editarMasivoModal').modal('hide');
                clearSelection();
                table.ajax.reload();
                clearEditarMasivoForm();
            },
            error: function (xhr) {
                Swal.fire({
                    title: 'Error',
                    text: xhr.responseJSON ? xhr.responseJSON.message : 'Error desconocido.',
                    icon: 'error',
                    draggable: true
                });
                $('#editarMasivoModal').modal('hide');
                clearEditarMasivoForm();
            }
        });
    });

    $('#fechaFinAmpliacion').on('change input', function () {
        calcularAmpliacion();
    });

    $('#newStatusMasivo').on('change', function () {
        const $ampliacionForm = $('#ampliacionForm');
        const statusSelected = this.value;

        if (statusSelected === '11' || statusSelected === '14') {
            $ampliacionForm.show().css('display', 'flex');

            if (statusSelected === '11') {
                $('#lblFechaMovimiento').text('Nueva Fecha Final (Ampliación):');
                $('#lblMotivoMovimiento').text('Motivo de la Ampliación:');
            } else {
                $('#lblFechaMovimiento').text('Nueva Fecha Final (Terminación Anticipada):');
                $('#lblMotivoMovimiento').text('Motivo de la Terminación Anticipada:');
            }

            calcularAmpliacion();
        } else {
            $ampliacionForm.hide();
            $('#fechaFinAmpliacion').val('');
            $('#motivoAmpliacion').val('');
            $('#mensajeErrorAmpliacion').text('');
            $('#confirmarEdicionMasiva').prop('disabled', false);
        }
    });
})
