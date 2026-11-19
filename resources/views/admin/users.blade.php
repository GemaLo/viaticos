@extends('layouts.master')
@section('title', 'Alta Usuarios')

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
                    <h1 class="mb-4 display-4">Crear Nuevo Usuario</h1>
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <strong class="font-bold">¡Guardado no exitoso!</strong>
                            <span class="block sm:inline">Por favor, corrige los siguientes errores:</span>
                            <ul class="mt-3 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success col-md-12" role="alert">{{ $message }}
                        </div>
                    @endif
                    @if ($message = Session::get('warning'))
                        <div class="alert alert-warning col-md-12" role="alert">{{ $message }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('saveUser') }}" enctype="multipart/form-data" id="formUser">
                        @csrf
                        <div class="row g-6">
                            <div class="col-md-12 mb-3">
                                <label class="form-label d-block">Origen del Empleado:</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="origen" id="origenSecretaria"
                                        value="secretaría" checked>
                                    <label class="form-check-label" for="origenSecretaria">Secretaría</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="origen" id="origenGuardia"
                                        value="guardia">
                                    <label class="form-check-label" for="origenGuardia">Guardia Nacional</label>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="claveServidor">Número de Empleado:</label>
                                <div class="d-flex align-items-center">
                                    <input type="text" id="claveServidor" value="{{ old('numEmpleado') }}"
                                        name="numEmpleado" class="form-control me-2" required>
                                    <button type="button" class="btn btn-primary"
                                        onclick="info($('#claveServidor').val(), $('input[name=origen]:checked').val())">Validar</button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="nomUsuario">Nombre(s):</label>
                                <input type="text" id="nomUsuario" name="nomUsuario" value="{{ old('nomUsuario') }}"
                                    class="form-control" readonly>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="appUsuario">Apellido Paterno:</label>
                                <input type="text" id="appUsuario" name="appUsuario" value="{{ old('appUsuario') }}"
                                    class="form-control" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="apmUsuario">Apellido Materno:</label>
                                <input type="text" id="apmUsuario" name="apmUsuario" value="{{ old('apmUsuario') }}"
                                    class="form-control" readonly>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label" for="cargo">Nivel:</label>
                                <input type="text" id="cargo" name="cargo" value="{{ old('cargo') }}"
                                    class="form-control" readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="unidad">Unidad:</label>
                                <input type="text" id="unidad" name="unidad" value="{{ old('unidad') }}"
                                    class="form-control" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="email">Correo:</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                    class="form-control">
                            </div>
                            @if (Auth::user()->idtype == 1)
                                <div class="col-md-4">
                                    <label class="form-label" for="idType">Tipo de Usuario</label>
                                    <select id="idType" name="idtype" class="form-control" required>
                                        <option value="">Seleccione un tipo</option>
                                        @foreach ($typeUsers as $typeUser)
                                            <option value="{{ $typeUser->idtype }}"
                                                {{ old('idtype') == $typeUser->idtype ? 'selected' : '' }}>
                                                {{ $typeUser->typeuser }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4" id="idsubcargo" style="display:none;">
                                    <label class="form-label" for="idsubcargo">Tipo de Subnivel</label>
                                    <select name="idsubcargo" class="form-control">
                                        <option value="">Seleccione subnivel</option>
                                        @foreach ($subcargos as $subcargo)
                                            <option value="{{ $subcargo->idsubcargo }}">{{ $subcargo->subcargo }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-12" id="iddireccion" style="display:none;">
                                    <label class="form-label" for="direcciones">Direcciones Asignadas
                                        (Multiselección):</label>
                                    <select id="direcciones" name="direcciones[]" class="form-control select2"
                                        multiple="multiple">
                                        @foreach ($direcciones as $dir)
                                            <option value="{{ $dir->no_direccion }}">{{ $dir->no_direccion }} -
                                                {{ $dir->nombre_ur }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted italic">Seleccione todas las áreas a las que pertenece el
                                        usuario.</small>
                                </div>
                            @endif
                            <div class="col-12 mt-4">
                                <button type="submit" id="submitButton" class="btn btn-primary" disabled>Enviar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card-header">
                <h5 class="mb-4">Consulta de usuarios</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table id="myTable" class="table table-striped w-100">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Número de Empleado</th>
                                <th>Nombre de usuario</th>
                                <th>Perfil de Usuario</th>
                                <th>Unidad</th>
                                <th>Email</th>
                                <th>Estatus</th>
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
    </style>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#claveServidor, #nomUsuario, #appUsuario, #apmUsuario, #cargo, #unidad').on('change input',
                validateForm);
            if ($('.select2').length) {
                $('.select2').select2({
                    placeholder: "Seleccione direcciones...",
                    allowClear: true,
                    width: '100%'
                });
            }
            $('#direcciones').on('change', validateForm);
            $('#email').on('input', validateForm);

            $('#idType').on('change', function() {
                if ($(this).val() == 2 || $(this).val() == 1) {
                    $('#idsubcargo').show();
                    $('#iddireccion').show();
                } else {
                    $('#idsubcargo').hide();
                    $('#iddireccion').hide();
                }
                validateForm();
            });

            setTimeout(validateForm, 500);
        });

        // 1. Creamos una variable global en el script para rastrear si ya se dio clic
        let formSubmitting = false;

        function validateForm() {
            const numEmpleado = $('#claveServidor').val() || "";
            const nomUsuario = $('#nomUsuario').val() || "";
            const apmUsuario = $('#apmUsuario').val();
            const appUsuario = $('#appUsuario').val();
            const cargo = $('#cargo').val();
            const unidad = $('#unidad').val();
            const email = $('#email').val();

            let isIdTypeSelected = true;
            @if (Auth::user()->idtype == 1)
                const idType = $('#idType').val();
                isIdTypeSelected = idType !== null && idType !== '';
                if (idType == 2) {
                    $('#idsubcargo').show();
                } else {
                    $('#idsubcargo').hide();
                }
            @endif

            const isFormValid = numEmpleado.length > 0 && nomUsuario.length > 0 && appUsuario.length > 0 && apmUsuario
                .length > 0 &&
                cargo.length > 0 && unidad.length > 0 && isIdTypeSelected;

            if (formSubmitting) {
                $('#submitButton').prop('disabled', true);
            } else {
                $('#submitButton').prop('disabled', !isFormValid);
            }
        }

        $(document).ready(function() {
            $('#formUser').on('submit', function() {
                formSubmitting = true;
                validateForm();

                $('#submitButton').html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enviando...'
                );
            });
        });


        function info(seleccion, origen) {

            var parametros = {
                "empleado": seleccion,
                "origen": origen
            };

            $.ajax({
                type: 'GET',
                url: "{{ route('datosEmpleado') }}",
                data: parametros,
                success: function(response) {
                    if (response && response.num_empleado) {
                        $('#numEmpleado').val(response.num_empleado);
                        $('#nomUsuario').val(response.nombre);
                        $('#appUsuario').val(response.apellido_paterno);
                        $('#apmUsuario').val(response.apellido_materno);
                        $('#cargo').val(response.nivel);
                        $('#unidad').val(response.unidad);

                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });

                        Toast.fire({
                            icon: 'success',
                            title: 'Empleado encontrado correctamente'
                        });

                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Sin coincidencias',
                            text: 'No se encontró información del empleado.',
                            confirmButtonColor: '#3085d6'
                        });
                        limpiarCampos();
                    }
                    validateForm();
                },
                error: function(xhr) {
                    let msj = (xhr.responseJSON && xhr.responseJSON.error) ? xhr.responseJSON.error :
                        "Hubo un problema al consultar la base de datos.";

                    Swal.fire({
                        icon: 'error',
                        title: 'Error de validación',
                        text: msj,
                        confirmButtonColor: '#d33'
                    });

                    limpiarCampos();
                    validateForm();
                },

            });
        }
    </script>
    <script type="text/javascript" defer>
        $(document).ready(function() {

            $('#email').on('input', validateForm);

            @if (Auth::user()->idtype == 1)
                $('#idType').on('change', validateForm);
            @endif

            setTimeout(validateForm, 100);

            $('#myTable thead tr').clone(true).appendTo('#myTable thead');
            $('#myTable thead tr:eq(1) th').each(function(i) {
                var title = $(this).text();
                if (i === 6) {
                    $(this).html(`
                        <select class="filters" id="celda${i}">
                            <option value="">Todos</option>
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    `);

                    $('select', this).on('change', function() {
                        table.column(i).search(this.value).draw();
                    });

                } else if (i !== 7) { // excluir botones
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
                    "url": "{{ url('users_list_json') }}",
                    "data": function(d) {
                        d.tabType = $('.tab-btn.active').data('tab-type');
                    }
                },
                "columns": [{
                        data: 'id_user',
                        name: 'users.id_user'
                    },
                    {
                        data: 'numempleado',
                        name: 'users.numempleado'
                    },
                    {
                        data: 'name_complete'
                    },
                    {
                        data: 'typeuser',
                        name: 'typeuser.typeuser'
                    },
                    {
                        data: 'unidad',
                        name: 'users.unidad'
                    },
                    {
                        data: 'email',
                        name: 'users.email'
                    },
                    {
                        data: 'active',
                        name: 'users.active'
                    },
                    {
                        data: 'btn_edit',
                        orderable: false,
                        searchable: false
                    }
                ],
                "dom": 'Blfrtip',
                "buttons": [{
                    extend: 'excel',
                    text: '<button type="button" class="btn btn-success round">Comisiones</button>',
                    title: 'Comisiones-Excel',
                }, ],
                "language": {
                    "info": "_TOTAL_ registros",
                    "search": "Buscar",
                    "paginate": {
                        "next": "Siguiente",
                        "previous": "Anterior",
                    },
                    "lengthMenu": 'Mostrar <select >' +
                        '<option value="10">10</option>' +
                        '<option value="30">30</option>' +
                        '<option value="100">100</option>' +
                        '<option value="-1">Todos</option>' +
                        '</select> registros',
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "emptyTable": "No hay datos",
                    "zeroRecords": "No hay coincidencias",
                    "infoEmpty": "",
                    "infoFiltered": ""
                },
                "drawCallback": function(settings) {
                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }
                },
            });
        });
    </script>
@endsection
