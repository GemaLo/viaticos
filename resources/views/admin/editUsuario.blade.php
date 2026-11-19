@extends('layouts.master')
@section('title', 'Modificar Usuario')

@section('vendor-style')
    @vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
@endsection

@section('vendor-script')
    @vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
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
                    <h1 class="mb-4 display-4">Modificar Usuario</h1>
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

                    <form method="POST" action="{{ route('updateUser') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_user" value="{{ $query->id_user }}">

                        <div class="row g-6 mb-4">
                            <!-- Número de Empleado Principal / Actual -->
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="numEmpleado">Número de Empleado (Actual):</label>
                                <div class="d-flex align-items-center">
                                    <input type="text" id="numEmpleado" name="numEmpleado" 
                                        value="{{ old('numEmpleado', $sedeActual->numero_empleado ?? $query->numempleado) }}"
                                        class="form-control me-2" required>
                                    @if (Auth::user()->idtype == 1)
                                    <button type="button" class="btn btn-info text-nowrap"
                                        onclick="sincronizarEmpleado($('#numEmpleado').val(), $('input[name=procedencia]:checked').val())">
                                        <i class="bx bx-refresh"></i> Validar Datos
                                    </button>                                        
                                    @endif
                                </div>
                            </div>
                            
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="nomUsuario">Nombre(s):</label>
                                <input type="text" id="nomUsuario" name="nomUsuario" value="{{ $query->nomusuario }}" class="form-control"
                                    readonly>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label" for="appUsuario">Apellido Paterno:</label>
                                <input type="text" id="appUsuario" name="appUsuario" value="{{ $query->appusuario }}" class="form-control"
                                    readonly>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="apmUsuario">Apellido Materno:</label>
                                <input type="text" id="apmUsuario" name="apmUsuario" value="{{ $query->apmusuario }}" class="form-control"
                                    readonly>
                            </div>

                            <div class="col-6 col-md-1">
                                <label class="form-label" for="cargo">Nivel:</label>
                                <input type="text" id="cargo" name="cargo" value="{{ $query->cargo }}" class="form-control"
                                    readonly>
                            </div>
                            <div class="col-6 col-md-1">
                                <label class="form-label" for="unidad">Unidad:</label>
                                <input type="text" id="unidad" name="unidad" value="{{ $query->unidad }}" class="form-control"
                                    readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="idType">Tipo de Usuario</label>

                                @if (Auth::user()->idtype != 1)
                                    <input type="text" value="{{ $query->nombre_del_perfil }}" class="form-control"
                                        readonly>
                                    <input type="hidden" name="idtype" value="{{ $query->idtype }}">
                                @else
                                    <select id="idType" name="idtype" class="form-select" required>
                                        @foreach ($typeUsers as $t)
                                            <option value="{{ $t->idtype }}"
                                                {{ $query->idtype == $t->idtype ? 'selected' : '' }}>
                                                {{ $t->typeuser }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>

                            @if (Auth::user()->idtype == 1)
                                <div class="col-12 col-md-2">
                                    <label class="form-label">Estatus:</label>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="radio" name="active" id="activo"
                                            value="1" {{ $query->active == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="activo">
                                            Activo
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="active" id="inactivo"
                                            value="0" {{ $query->active == 0 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="inactivo">
                                            Inactivo
                                        </label>
                                    </div>
                                    @error('active')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label" for="importcomplete">Importe Completo:</label>
                                    <input type="text" id="importcomplete" name="importcomplete"
                                        value="{{ $query->importcomplete }}" class="form-control">
                                </div>
                                <div class="col-12 col-md-2">
                                    <label class="form-label">Procedencia / Sede:</label>
                                    @php
                                        $procedenciaActual = $sedeActual->origin ?? $query->procedencia;
                                    @endphp
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="radio" name="procedencia"
                                            id="secretaria" value="SECRETARÍA" required
                                            {{ $procedenciaActual == 'SECRETARÍA' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="secretaria">
                                            SECRETARÍA
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="procedencia" id="guardia"
                                            required value="GUARDIA"
                                            {{ $procedenciaActual == 'GUARDIA' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="guardia">
                                            GUARDIA
                                        </label>
                                    </div>
                                </div>
                                @if (Auth::user()->id_user == 1 || Auth::user()->id_user == 29 || Auth::user()->id_user == 4389)
                                    <div class="col-12 col-md-2">
                                        <label class="form-label">Restringido:</label>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="radio" name="restringido"
                                                id="si" value="1"
                                                {{ $query->restringido == '1' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="si">
                                                Si
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="restringido"
                                                id="no" value="0"
                                                {{ $query->restringido == '0' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="no">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="send_commitment"
                                            id="send_commitment" value="1">
                                        <label class="form-check-label" for="send_commitment">
                                            <strong>Enviar Carta Compromiso</strong>
                                        </label>
                                        <p class="text-muted small">Útil si el correo falló anteriormente o hubo corrección de email.</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label">Restablecer Contraseña:</label>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="reset_password"
                                            id="reset_password" value="1">
                                        <label class="form-check-label" for="reset_password">
                                            Generar nueva contraseña y notificar por correo
                                        </label>
                                    </div>
                                    <p class="text-muted text-sm mt-1">Si no se marca, la contraseña no cambiará.</p>
                                </div>
                                <div class="col-md-3" id="subcargo-container" style="display: none;">
                                    <label class="form-label" for="idSubcargo">Tipo de subcargo</label>
                                    <select id="idSubcargo" name="idsubcargo" class="form-control">
                                        <option value="">Seleccione un subcargo</option>
                                        @foreach ($subcargos as $subcargo)
                                            <option value="{{ $subcargo->idsubcargo }}"
                                                {{ (isset($query->idsubcargo) && $query->idsubcargo == $subcargo->idsubcargo) || old('idsubcargo') == $subcargo->idsubcargo ? 'selected' : '' }}>
                                                {{ $subcargo->subcargo }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-12 mt-4">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="form-label mb-0" for="direcciones">
                                            Direcciones Asignadas (Multiselección):
                                        </label>
                                        <button type="button" id="btn-select-all-direcciones"
                                            class="btn btn-sm btn-link p-0 text-decoration-none">
                                            Seleccionar todas
                                        </button>
                                    </div>

                                    <select id="direcciones" name="direcciones[]" class="form-control select2"
                                        multiple="multiple">
                                        @foreach ($direccionesDB as $dir)
                                            <option value="{{ $dir->no_direccion }}"
                                                @if (is_array($query->direcciones) && in_array($dir->no_direccion, $query->direcciones)) selected @endif>
                                                [{{ $dir->no_direccion }}] {{ $dir->nombre_ur }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>

                        <!-- SECCIÓN: Historial Dinámico de Sedes (Renglones) -->
                        <hr class="my-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h5 class="mb-0 text-primary"><i class="bx bx-history"></i> Historial de Sedes y Números de Empleado</h5>
                            </div>
                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHistorialSedes" aria-expanded="false" aria-controls="collapseHistorialSedes">
                                <i class="bx bx-show"></i> Ver Bitácora Completa
                            </button>
                        </div>

                        <!-- Contenedor Colapsable con el Historial Dinámico -->
                        <table class="table table-sm table-bordered bg-white mb-0">
    <thead>
        <tr>
            <th>Sede / Origen</th>
            <th>Número Anterior</th>
            <th>Número Nuevo / Asignado</th>
            <th>Estatus</th>
            <th>Fecha de Cambio</th>
        </tr>
    </thead>
    <tbody>
        @foreach($historialSedes as $hist)
            <tr>
                <td><strong>{{ $hist->origin }}</strong></td>
                <td>
                    <span class="text-muted font-monospace">{{ $hist->numero_anterior ?? 'N/D (Inicial)' }}</span>
                </td>
                <td>
                    <span class="text-primary fw-bold font-monospace">{{ $hist->numero_nuevo }}</span>
                </td>
                <td>
                    @if($hist->active == 1)
                        <span class="badge bg-success">Activo (Vigente)</span>
                    @else
                        <span class="badge bg-secondary">Inactivo (Histórico)</span>
                    @endif
                </td>
                <td>{{ $hist->fecha_cambio ?? 'N/D' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

                        <hr class="my-4">
                        <div class="row g-6 mb-4">
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="email">Correo:</label>
                                <input type="email" id="email" name="email"
                                    value="{{ old('email', $query->email) }}" class="form-control"
                                    {{ Auth::user()->idtype != 1 ? 'readonly' : '' }}>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="contacto">Contacto:</label>
                                <input type="text" id="contacto" name="contacto"
                                    value="{{ old('contacto', $query->contacto) }}" class="form-control" readonly>
                            </div>
                            @if (Auth::user()->idtype == 1)
                                <div class="col-12 col-md-4">
                                    <label class="form-label" for="clabe">Clave Interbancaria (CLABE):</label>
                                    <input type="text" id="clabe" name="clabe"
                                        value="{{ $datosReporte->cuenta_clabe ?? '' }}" class="form-control" readonly>
                                </div>
                        </div>
                        <div class="row g-6 mb-4">
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="cuenta">Cuenta Bancaria:</label>
                                <input type="text" id="cuenta" name="cuenta"
                                    value="{{ $datosReporte->cuenta_bancaria ?? '' }}" class="form-control" readonly>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="banco">Banco:</label>
                                <input type="text" id="banco" name="banco"
                                    value="{{ $datosReporte->nombre_banco ?? '' }}" class="form-control" readonly>
                            </div>
                            <div class="col-12 col-md-4"></div>
                        </div>
                            @endif

                        <hr class="my-4">
                        <div class="row g-6">
                            <div class="col-12 mt-4" id="saveComission">
                                @if (Auth::user()->idtype == 1)
                                    <button type="submit" id="submitButton" class="btn btn-primary">Guardar
                                        Cambios</button>
                                @endif
                                <a href="{{ route('users') }}" class="btn btn-outline-secondary">Regresar</a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .tab-btn.active {
            background-color: #007bff;
            color: #fff;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const idTypeSelect = document.getElementById('idType');
            const subcargoContainer = document.getElementById('subcargo-container');
            const idSubcargoSelect = document.getElementById('idSubcargo');

            function toggleSubcargo() {
                if (idTypeSelect && idTypeSelect.value === '2') {
                    subcargoContainer.style.display = 'block';
                } else if (subcargoContainer) {
                    subcargoContainer.style.display = 'none';
                    if(idSubcargoSelect) idSubcargoSelect.value = '';
                }
            }

            if (idTypeSelect) {
                idTypeSelect.addEventListener('change', toggleSubcargo);
                toggleSubcargo();
            }
        });

        $(document).ready(function() {
            $('#btn-select-all-direcciones').on('click', function() {
                let selectElement = $('#direcciones');
                let allOptions = selectElement.find('option').map(function() {
                    return $(this).val();
                }).get();
                let selectedOptions = selectElement.val() || [];

                if (selectedOptions.length === allOptions.length) {
                    selectElement.val(null).trigger('change');
                    $(this).text('Seleccionar todas');
                } else {
                    selectElement.val(allOptions).trigger('change');
                    $(this).text('Deseleccionar todas');
                }
            });

            $('#direcciones').on('change', function() {
                let totalOptions = $(this).find('option').length;
                let totalSelected = ($(this).val() || []).length;

                if (totalSelected === totalOptions) {
                    $('#btn-select-all-direcciones').text('Desmarcar todas');
                } else {
                    $('#btn-select-all-direcciones').text('Marcar todas');
                }
            });

            if ($('#direcciones').length) {
                $('#direcciones').select2({
                    placeholder: "Seleccione direcciones...",
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#mainContent')
                });
            }
        });

        function sincronizarEmpleado(empleado, origen) {
            if (!empleado) return;

            const preloader = document.getElementById('preloader');
            if (preloader) preloader.style.display = 'block';

            $.ajax({
                url: "{{ route('datosEmpleado') }}",
                type: "GET",
                data: {
                    empleado: empleado,
                    origen: origen
                },
                success: function(response) {
                    if (preloader) preloader.style.display = 'none';

                    $('#nomUsuario').val(response.nombre);
                    $('#appUsuario').val(response.apellido_paterno);
                    $('#apmUsuario').val(response.apellido_materno);
                    $('#cargo').val(response.nivel);
                    $('#unidad').val(response.unidad);

                    Swal.fire({
                        title: '¡Éxito!',
                        text: 'Datos del empleado actualizados desde la nómina. Recuerda guardar los cambios.',
                        icon: 'success',
                        confirmButtonText: 'Aceptar',
                        timer: 5000,
                        timerProgressBar: true
                    });
                },
                error: function(xhr) {
                    if (preloader) preloader.style.display = 'none';

                    let errorMsg = 'Ocurrió un error al consultar los datos.';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMsg = xhr.responseJSON.error;
                    }
                    alert(errorMsg);
                }
            });
        }
    </script>
@endsection