@extends('layouts.master')
@section('title', 'Database')

@section('vendor-style')
    @vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('vendor-script')
    @vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection

@section('page-script')
    @vite('resources/assets/js/dashboards-analytics.js')
@endsection

@section('content')
    <?php
    setlocale(LC_TIME, 'es_ES', 'Spanish_Spain', 'Spanish');
    ?>

    <div id="preloader"
        style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
    </div>

    <div id="mainContent" class="main flex-grow p-12 ml-40 transition-all duration-300">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <h3 class="mb-4 display-4">Alta de Objetivos por UR</h3>

                    <form action="{{ route('database.store') }}" method="POST" class="row g-3 mb-5 pb-4 border-bottom">
                        @csrf
                        <div class="col-md-6">
                            <label for="objetive" class="form-label font-bold">Objetivo</label>
                            <input type="text" name="objetive" id="objetive" class="form-control"
                                placeholder="Ej: Cumplimiento de metas trimestrales" required>
                        </div>

                        <div class="col-md-4">
                            <label for="ur_multiselect" class="form-label font-bold">Unidades Responsables (UR)</label>
                            <select name="ur[]" id="ur_multiselect" class="form-control select2" multiple="multiple"
                                required>
                                @foreach ($direcciones as $dir)
                                    <option value="{{ $dir->no_direccion }}">{{ $dir->no_direccion }} -
                                        {{ $dir->nombre_ur }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Puedes seleccionar varias direcciones.</small>
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card-header">
                <h5 class="mb-4">Consulta de objetivos</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table id="myTable" class="table table-striped w-100">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Objetive</th>
                                <th>UR</th>
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

    <div class="modal fade" id="editObjectiveModal" tabindex="-1" aria-labelledby="editObjectiveModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editObjectiveForm" method="POST" action="">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="editObjectiveModalLabel">Editar Objetivo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <input type="hidden" id="edit_id" name="id">

                        <div class="col-md-12">
                            <label for="edit_objetive" class="form-label font-bold">Objetivo</label>
                            <input type="text" name="objetive" id="edit_objetive" class="form-control" required>
                        </div>

                        <div class="col-md-12">
                            <label for="edit_ur_multiselect" class="form-label font-bold">Unidades Responsables (UR)</label>
                            <select name="ur[]" id="edit_ur_multiselect" class="form-control select2-modal"
                                multiple="multiple" required>
                                @foreach ($direcciones as $dir)
                                    <option value="{{ $dir->no_direccion }}">{{ $dir->no_direccion }} -
                                        {{ $dir->nombre_ur }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Actualizar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .filters {
            width: 100% !important;
            min-width: 80px;
            box-sizing: border-box;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .tab-btn.active {
            background-color: #007bff;
            color: #fff;
        }

        .filters {
            width: 100%;
        }

        #celda0 {
            width: 25px;
            border-color: #000000;
        }

        #celda1 {
            width: 150px;
        }

        #celda2 {
            width: 50px;
        }

        #celda3 {
            width: 150px;
        }

        #celda4 {
            width: 150px;
        }

        #celda5 {
            width: 150px;
        }

        .column-objetive {
            white-space: normal !important;
            word-wrap: break-word;
            min-width: 250px;
            max-width: 400px;
            vertical-align: middle;
        }

        .ur-container {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            white-space: normal;
            width: 150px;
        }

        .ur-badge {
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            color: #374151;
        }
    </style>

    <script type="text/javascript">
        $(document).ready(function() {
            if ($('#ur_multiselect').length) {
                $('#ur_multiselect').select2({
                    placeholder: "Seleccione direcciones...",
                    allowClear: true,
                    width: '100%'
                });
            }

            if ($('#edit_ur_multiselect').length) {
                $('#edit_ur_multiselect').select2({
                    dropdownParent: $(
                    '#editObjectiveModal'),
                    placeholder: "Seleccione direcciones...",
                    allowClear: true,
                    width: '100%'
                });
            }
        });
    </script>

    <script type="text/javascript" defer>
        $(document).ready(function() {
            $('#myTable thead tr').clone(true).appendTo('#myTable thead');
            $('#myTable thead tr:eq(1) th').each(function(i) {
                var title = $(this).text();
                if (i !== 3) {
                    $(this).html('<input class="filters" id="celda' + i + '" type="text" placeholder="' +
                        title + '" />');
                    $('input', this).on('keyup change', function() {
                        if (table.column(i).search() !== this.value) {
                            table.column(i).search(this.value).draw();
                        }
                    });
                } else {
                    $(this).html('');
                }
            });

            var table = $('#myTable').DataTable({
                "destroy": true,
                "serverSide": true,
                "orderCellsTop": true,
                "ajax": {
                    "url": "{{ url('objetives_list_json') }}",
                    "data": function(d) {
                        d.tabType = $('.tab-btn.active').data('tab-type');
                    }
                },
                "columns": [{
                        data: 'idobjetive',
                        name: 'objetives.idobjetive'
                    },
                    {
                        data: 'objetive',
                        name: 'objetives.objetive',
                        className: 'column-objetive'
                    },
                    {
                        data: 'ur',
                        name: 'objetives.ur',
                        render: function(data, type, row) {
                            if (!data) return '';
                            let arrayDeNumeros = [];
                            try {
                                arrayDeNumeros = (typeof data === 'string') ? JSON.parse(data) :
                                    data;
                            } catch (e) {
                                if (typeof data === 'string') {
                                    arrayDeNumeros = data.replace(/[\[\]\" ]/g, '').split(',');
                                }
                            }
                            if (Array.isArray(arrayDeNumeros)) {
                                let html = '<div class="ur-container">';
                                arrayDeNumeros.forEach(num => {
                                    if (num.trim() !== "") {
                                        let soloNumero = num.split(' ')[0];
                                        html +=
                                            `<span class="ur-badge">${soloNumero}</span>`;
                                    }
                                });
                                html += '</div>';
                                return html;
                            }
                            return data;
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            const ursData = typeof row.ur === 'string' ? row.ur : JSON.stringify(row
                                .ur);
                            const cleanObjective = row.objetive.replace(/'/g, "&apos;");

                            return `
                                <button type="button" class="btn btn-sm btn-primary edit-objective-btn"
                                    data-id="${row.idobjetive}"
                                    data-objetive="${cleanObjective}"
                                    data-urs='${ursData}'>
                                    <i class="bx bx-edit"></i> Editar
                                </button>`;
                        }
                    }
                ],
                "dom": 'Blfrtip',
                "language": {
                    "info": "_TOTAL_ registros",
                    "search": "Buscar",
                    "paginate": {
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                    "lengthMenu": 'Mostrar <select><option value="10">10</option><option value="30">30</option><option value="-1">Todos</option></select> registros',
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "emptyTable": "No hay datos",
                    "zeroRecords": "No hay coincidencias",
                    "infoEmpty": "",
                    "infoFiltered": ""
                }
            });

            $(document).on('click', '.edit-objective-btn', function() {
                const id = $(this).data('id');
                const objective = $(this).data('objetive');
                let urs = $(this).data('urs');

                if (typeof urs === 'string') {
                    try {
                        urs = JSON.parse(urs);
                    } catch (e) {
                        urs = urs.replace(/[\[\]\" ]/g, '').split(',');
                    }
                }

                $('#edit_id').val(id);
                $('#edit_objetive').val(objective);

                $('#edit_ur_multiselect').val(urs).trigger('change');

                $('#editObjectiveForm').attr('action', `/objetivos/${id}`);

                $('#editObjectiveModal').modal('show');
            });
        });
    </script>
@endsection
