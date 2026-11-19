@extends('layouts.master')

@section('content')
    <style>
        .card-container {
            background-color: #ffffff;
            height: 500px;
        }

        .chart-wrapper {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;
        }

        .tab-btn.active {
            background-color: #007bff;
            color: #fff;
        }

        #celda0 {
            width: 50px;
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

        #celda5,
        #celda6 {
            display: none;
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
                                <h5 class="card-title text-primary mb-3">BIENVENIDO (A) {{ $nomusuario }}! 🎉</h5>
                                <p class="mb-6">Este es tu espacio para gestionar viáticos.<br>Revisa el avance de tus
                                    solicitudes o comienza una nueva.</p>
                                <a href="{{ route('comisionView') }}" class="btn btn-sm btn-outline-primary">Crear Nueva
                                    Solicitud</a>
                                <a href="{{ route('downloadConstancia') }}"
                                    class="btn btn-sm btn-outline-primary">Constancia Fiscal SSPC</a>
                            </div>
                        </div>
                        <div class="col-sm-5 text-center">
                            <div class="card-body pb-0 px-0 px-md-6">
                                <img src="{{ asset('/assets/img/illustrations/man-with-laptop.png') }}" height="175"
                                    alt="User">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if (Auth::user()->idtype == 2)
                <div class="col-xxl-12 col-xl-8 col-lg-9 mb-6 order-0 ml-5">
                    <div class="card card-border-shadow-primary h-100 p-3">
                        <h5 class="card-title text-center">Resumen de Actividad</h5>
                        <div class="row card-container">
                            <div class="col-md-6">
                                <div class="chart-wrapper"><canvas id="miGraficaPolar"></canvas></div>
                            </div>
                            <div class="col-md-6">
                                <div class="chart-wrapper"><canvas id="miGraficaBarra"></canvas></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (Auth::user()->idtype == 3)
                <div class="col-xxl-12 col-xl-8 col-lg-9 mb-6 order-0 ml-5">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="m-0">Historial de Viáticos</h5>
                            <div class="d-flex my-3">
                                <button class="btn btn-outline-primary me-2 tab-btn active"
                                    data-tab-type="1">Anticipados</button>
                                <button class="btn btn-outline-primary tab-btn" data-tab-type="2">Devengados</button>
                            </div>
                            <div class="row">

                                <div class="d-flex gap-2 align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="massAction" id="actionZip"
                                            value="zip_archivos" checked>
                                        <label class="form-check-label" for="actionZip">ZIP ARCHIVOS</label>
                                    </div>
                                    <button class="btn btn-primary" id="btnEjecutarAccion">Ejecutar</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-datatable table-responsive">
                            <table id="myTable" class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th>No. Comisión</th>
                                        <th>Importe</th>
                                        <th>Modalidad</th>
                                        <th>Año</th>
                                        <th>Status</th>
                                        <th>Vigencia</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            Swal.fire({
                title: 'Aviso Importante',
                text: 'Al elegir viáticos devengados, recuerde cumplir con el punto 13 de los lineamientos. Los formatos de oficio y solicitud deben generarse 2 días hábiles antes de la comisión. Puede consultar el documento completo en el botón "Consulta de lineamientos" (parte superior derecha, a un costado de su nombre).',
                icon: 'warning',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#3085d6'
            });
            var table = $('#myTable').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ url('comision_list_json') }}",
                    data: function(d) {
                        d.tabType = $('.tab-btn.active').data('tab-type');
                    }
                },
                columns: [{
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            return `<input type="checkbox" name="comision_ids[]" value="${row.idcomision}" class="row-checkbox">`;
                        }
                    },
                    {
                        data: 'comision',
                        render: function(data, type, row) {
                            if (row.statuscomision === 'Cancelación') return 'Cancelada';
                            if (!data) return 'Pendiente';
                            let prefix = row.idmodalidad == 1 ? 'VA_' : 'VD_';
                            return prefix + data + '_' + row.yearcomision;
                        }
                    },
                    {
                        data: 'importe',
                        render: $.fn.dataTable.render.number(',', '.', 2, '$')
                    },
                    {
                        data: 'modalidad',
                        name: 'modalidades.modalidad'
                    },
                    {
                        data: 'yearcomision',
                        name: 'comisiones.yearcomision'
                    },
                    {
                        data: 'statuscomision',
                        name: 'statuscomision.statuscomision'
                    },
                    {
                        data: 'btn_vigencia'
                    },
                    {
                        data: 'btn_v'
                    }
                ],
                language: {
                    "processing": "Procesando...",
                    "lengthMenu": "Mostrar _MENU_ registros",
                    "zeroRecords": "No se encontraron resultados",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "search": "Buscar:",
                    "loadingRecords": "Cargando...",
                    "emptyTable": "No hay datos",
                    "info": "_TOTAL_ registros",
                    "paginate": {
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }
            });
            $('#myTable thead tr').clone(true).appendTo('#myTable thead');
            $('#myTable thead tr:eq(1) th').each(function(i) {
                if (i == 0 || i > 5) {
                    $(this).html('');
                    return;
                }
                $(this).html(
                    '<input type="text" class="form-control form-control-sm" placeholder="Filtrar">');
                $('input', this).on('keyup change', function() {
                    if (table.column(i).search() !== this.value) {
                        table.column(i).search(this.value).draw();
                    }
                });
            });

            $('#selectAll').on('click', function() {
                var rows = table.rows({
                    'search': 'applied'
                }).nodes();
                $('input[type="checkbox"]', rows).prop('checked', this.checked);
            });

            $(document).on('click', '#btnEjecutarAccion', function(e) {
                e.preventDefault();
                console.log("Botón presionado...");
                var selectedIds = [];
                table.$('input[name="comision_ids[]"]:checked').each(function() {
                    selectedIds.push($(this).val());
                });
                console.log("IDs seleccionados:", selectedIds);

                if (selectedIds.length === 0) {
                    Swal.fire({
                        icon: "warning",
                        title: "Atención",
                        text: "Selecciona al menos una comisión."
                    });
                    return;
                }

                if ($('#downloadFrame').length === 0) {
                    $('body').append(
                        '<iframe id="downloadFrame" name="downloadFrame" style="display:none;"></iframe>'
                        );
                }

                var form = $('<form>', {
                    action: "{{ route('reportes.zip') }}",
                    method: 'POST',
                    target: 'downloadFrame'
                });

                form.append($('<input>', {
                    type: 'hidden',
                    name: '_token',
                    value: '{{ csrf_token() }}'
                }));

                selectedIds.forEach(function(id) {
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'ids[]',
                        value: id
                    }));
                });

                $('body').append(form);
                form.submit();
                form.remove();

                console.log("Formulario enviado al iframe.");
            });

            $('.tab-btn').on('click', function() {
                $('.tab-btn').removeClass('active');
                $(this).addClass('active');
                table.ajax.reload();
            });

        });
    </script>
@endsection
