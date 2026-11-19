@extends('layouts.master')
@section('title', 'Alta de comisión')

@section('vendor-style')
    @vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
    <style>
        .map-container {
            margin-bottom: 20px;
            margin-top: 15px;
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

        #distanciaResultado {
            margin-top: 20px;
            font-weight: bold;
            color: #333;
            font-size: 1.1em;
        }

        .form-check {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }

        .form-check-input {
            margin-right: 8px;
        }
    </style>
@endsection

@section('vendor-script')
    @vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection

@section('page-script')
    @vite('resources/assets/js/dashboards-analytics.js')
@endsection

@section('content')
    <?php
    setlocale(LC_TIME, 'es_ES', 'Spanish_Spain', 'Spanish');
    ?>
    <div id="mainContent" class="main flex-grow p-10 ml-40 transition-all duration-300">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-9 mx-auto">
                                <h1 class="mb-4 display-4">Formato de Solicitud Para Viáticos</h1>
                                <hr style="border: 3px solid red;">
                                <h3 class="mb-4 mt-2 display-6">Datos del comisionado</h3>
                                <form id="formComision" method="POST" action="{{ route('saveComision') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row g-6">
                                        <div class="col-md-2">
                                            <label class="form-label" for="numNombreUr">Número de UR</label>
                                            <input type="text" id="numNombreUr" name="unidad" class="form-control"
                                                value="{{ $datosReporte->unidad }}" readonly>
                                        </div>
                                        <div class="col-md-10">
                                            <label class="form-label" for="numNombreUr">Nombre de la unidad
                                                responsable</label>
                                            <input type="text" id="numNombreUr" class="form-control"
                                                value="{{ $datosReporte->nombre_unidad }}" name="adscripcion" readonly>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label" for="direccion">Domicilio
                                                Adscripción</label>
                                            <input type="text" id="direccion" name="direccion" class="form-control"
                                                value="{{ $datosReporte->domicilio_unidad }}" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="lugar">Lugar</label>
                                            <input type="text" id="lugar" name="lugar" class="form-control"
                                                value="CIUDAD DE MÉXICO {{ strtoupper(\Carbon\Carbon::now()->translatedFormat('d-F-Y')) }}"
                                                readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label" for="nivel">Nivel</label>
                                            <input type="text" id="nivel" name="nivel" class="form-control"
                                                value="{{ $datosReporte->nivel }}" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label" for="numEmpleado">Número del Empleado</label>
                                            <input type="number" id="numEmpleado" class="form-control"
                                                value="{{ $datosReporte->num_empleado }}"name="num_empleado" readonly>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label" for="fullname">Nombre Completo</label>
                                            <input type="text" id="fullname" class="form-control"
                                                value="{{ $datosReporte->nombre_completo }}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="cargo">Cargo</label>
                                            <input type="text" id="cargo" name="cargo" class="form-control"
                                                value="{{ $datosReporte->cargo }}" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label" for="rfc">RFC</label>
                                            <input type="text" id="rfc" name="rfc" class="form-control"
                                                value="{{ $datosReporte->rfc }}" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label" for="curp">CURP</label>
                                            <input type="text" id="curp" name="curp" class="form-control"
                                                value="{{ $datosReporte->curp }}" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label" for="completeimport">Importe por día completo</label>
                                            <input type="text" id="completeimport" class="form-control"
                                                value="{{ $import->completo }}" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label" for="mediumimport">Importe por Medio Día</label>
                                            <input type="text" id="mediumimport" class="form-control"
                                                value="{{ $import->medio }}" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label" for="nombreBanco">Nombre del Banco</label>
                                            <input type="text" id="nombreBanco" name="banco" class="form-control"
                                                value="{{ $datosReporte->nombre_banco }}" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label" for="numCuenta">Número de Cuenta</label>
                                            <input type="number" id="numCuenta" name="cuenta" class="form-control"
                                                value="{{ $datosReporte->cuenta_bancaria }}" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="CuentaClave">Cuenta Clabe</label>
                                            <input type="number" id="CuentaClave" name="clabe" class="form-control"
                                                value="{{ $datosReporte->cuenta_clabe }}" readonly>
                                        </div>
                                    </div>
                                    <div class="row g-6">
                                        <h3 class="mb-4 mt-4 display-5 w-100">Datos de la Comisión Oficial</h3>
                                        <hr class="w-100" style="border: 2px solid red;">
                                        <div class="col-md-4">
                                            <label class="form-label" for="tipoViatico">Modalidad de Viatico</label>
                                            <select name="idmodalidad" class="form-control" required id="idModalidad">
                                                @if ($datosReporte->unidad == 220)
                                                    <option value="2" selected>DEVENGADA</option>
                                                @else
                                                    <option value="">Seleccione modalidad comisión</option>
                                                    @foreach ($modalidades as $modalidad)
                                                        <option value="{{ $modalidad->idmodalidad }}">
                                                            {{ $modalidad->modalidad }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>


                                        <div class="col-md-4">
                                            <label class="form-label" for="tipoComision">Tipo Comisión</label>
                                            <select name="idtypecomision" class="form-control" id="tipoComision"
                                                required>
                                                <option value="">Seleccione tipo comisión</option>
                                                @foreach ($typeComision as $type)
                                                    <option value="{{ $type->idtypecomision }}">{{ $type->typecomision }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label" for="partidaPasaje">Partida del Pasaje</label>
                                            <select name="partidapasaje" id="partidaPasaje" class="form-control">
                                                <option value="">Seleccione una opción</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label" for="partidaViatico">Partida del Viático</label>
                                            <select name="partidaviatico" id="partidaViatico" class="form-control"
                                                required>
                                                <option value="">Seleccione una opción</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="id_transport">Tipo Transporte</label>
                                            <select name="id_transport" class="form-control" required>
                                                <option value="">Seleccione un transporte</option>
                                                @foreach ($typeTransporte as $typeTr)
                                                    <option value="{{ $typeTr->id_transport }}">
                                                        {{ $typeTr->transport }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{-- <div class="col-md-4">
                                            <div class="map-container">
                                                <label for="PuntoPartida">Punto de Partida:</label><br>
                                                <input id="PuntoPartida" type="text"
                                                    placeholder="Ingresa el punto de partida" name="origen">
                                                <div id="map-partida" class="map-div"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="map-container">
                                                <label for="destino">Destino:</label><br>
                                                <input id="destino" type="text" placeholder="Ingresa el destino">
                                                <div id="map" class="map-div"></div>
                                            </div>
                                        </div> --}}
                                        {{-- <div class="col-md-4">
                                            <div class="map-container">
                                                <label class="form-label" for="placas">El destino es:</label>
                                                <input type="text" name="destino" id="destino_txt"
                                                    class="form-control" readonly>
                                            </div>
                                        </div> --}}

                                        {{-- <div class="col-12">
                                            <div id="distanciaResultado">
                                            </div>
                                        </div> --}}
                                        <div class="col-md-4" id="wrapper_destino" style="display: none;">
                                            <label class="form-label" for="destino">Destino:</label>
                                            <select name="destino" class="form-control" id="destino">
                                                <option value="">Seleccione destino de comisión</option>
                                                @foreach ($destinos as $destino)
                                                    <option value="{{ $destino->destino }}">{{ $destino->destino }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4" id="wrapper_internacional" style="display: none;">
                                            <label class="form-label" for="destino_internacional">Destino
                                                Internacional</label>
                                            <input type="text" id="destino_internacional" name="destino_internacional"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="row g-6" id="saveComission">
                                        <h2 class="mb-1 display-6 w-100">Periodo</h2>
                                        <hr class="w-100" style="border: 2px solid red;">
                                        <div class="col-md-3">
                                            <label class="form-label" for="fechaInicio">Fecha de Inicio</label>
                                            <input type="date" id="fechaInicio" name="initialdate"
                                                onchange="calcularDiasSeparados()" class="form-control" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label" for="fechaFin">Fecha de Final</label>
                                            <input type="date" id="fechaFin" name="finaldate"
                                                onchange="calcularDiasSeparados()" class="form-control" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label" for="diasCompletosInput">Días Completos</label>
                                            <input type="number" id="diasCompletosInput" name="dayscompletes"
                                                class="form-control" readonly required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label" for="medioDiaInput">Menor a 24HRS</label>
                                            <input type="number" id="medioDiaInput" name="hours" class="form-control"
                                                readonly required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label" for="importeTotalInput">Importe</label>
                                            <input type="text" id="importeTotalInput" name="importe"
                                                class="form-control" readonly>
                                        </div>
                                        <p id="mensajeError" class="col-12 text-danger"></p>

                                        <hr class="w-100" style="border: 1px solid red;">
                                        <div class="col-md-12">
                                            <label class="form-label">Seleccionar Objetivo de Comisión:</label>
                                            <select id="idobjetive" name="idobjetive" class="form-select" required>
                                                <option value="">Seleccione una opción...</option>
                                                @foreach ($filteredObjetives as $filteredObjetive)
                                                    <option value="{{ $filteredObjetive->idobjetive }}">
                                                        {{ $filteredObjetive->objetive }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Seleccionar Autorizador:</label>
                                            <select id="selectorEmpleado" class="form-select" required>
                                                <option value="">Seleccione una opción...</option>
                                                @foreach ($query as $emp)
                                                    <option value="{{ $emp->numempleado }}"
                                                        data-email="{{ $emp->email }}">
                                                        {{ $emp->nomusuario }} {{ $emp->appusuario }}
                                                        {{ $emp->apmusuario }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label" for="numEmpleadoValidador">Número de
                                                Autorizador:</label>
                                            <input type="text" id="validador" name="validador" class="form-control"
                                                readonly>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label" for="email">Correo del autorizador:</label>
                                            <input type="text" id="email" name="email" class="form-control"
                                                readonly>
                                        </div>
                                        <div class="col-md-12">
                                            <textarea id="justificacion" class="form-control" rows="7" disabled style="resize: none;"
                                                name="justificacion">El titular de la Unidad Responsable que suscribe, manifiesta, bajo protesta de decir verdad, que la presente comisión es estrictamente necesaria para dar cumplimiento con los objetivos institucionales, los programas o las funciones conferidas a la dependencia y que esta comisión no puede ser efectuada por el personal de las oficinas o representaciones locales o regionales que tiene la dependencia en el lugar de la comisión.</textarea>
                                        </div>
                                        <div class="col-12 mt-4">
                                            <button type="submit" id="btnEnviarComision"
                                                class="btn btn-primary">Enviar</button>
                                            <a href="{{ route('dashboard.index') }}" class="btn btn-danger">Cancelar</a>
                                        </div>
                                    </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@push('scripts')
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{ config('services.Maps.key') }}&libraries=places,geometry,marker&callback=initMap&map_id={{ config('services.Maps.map_id') }}">
    </script>

    <script>
        let marcadorPartida;
        let marcadorDestino;

        async function iniciarMapaCampo(inputId, mapaId, defaultCoords = {
            lat: 19.4326,
            lng: -99.1332
        }) {
            const {
                Map
            } = await google.maps.importLibrary("maps");
            const {
                AdvancedMarkerElement
            } = await google.maps.importLibrary("marker");

            const input = document.getElementById(inputId);
            const mapaDiv = document.getElementById(mapaId);

            const map = new Map(mapaDiv, {
                center: defaultCoords,
                zoom: 14,
                mapId: "{{ config('services.Maps.map_id') }}",
            });

            const marker = new AdvancedMarkerElement({
                map,
                position: defaultCoords
            });

            const autocomplete = new google.maps.places.Autocomplete(input, {
                types: ['geocode'],
                fields: ['geometry', 'address_components', 'formatted_address']
            });
            autocomplete.bindTo("bounds", map);

            input.addEventListener("click", () => {
                mapaDiv.style.display = "block";
                setTimeout(() => {
                    google.maps.event.trigger(map, 'resize');
                    if (marker.position) {
                        map.setCenter(marker.position);
                    }
                }, 300);
            });

            autocomplete.addListener("place_changed", () => {
                const place = autocomplete.getPlace();

                if (!place.geometry || !place.geometry.location) {
                    alert(
                        "No se encontró la ubicación seleccionada. Por favor, selecciona una de las sugerencias."
                    );
                    return;
                }

                map.setCenter(place.geometry.location);
                map.setZoom(16);
                marker.position = place.geometry.location;

                let stateName = '';
                let countryName = '';

                if (place.address_components) {
                    for (const component of place.address_components) {
                        if (component.types.includes('administrative_area_level_1')) {
                            stateName = component.long_name;
                        }
                        if (component.types.includes('country')) {
                            countryName = component.long_name;
                        }
                    }
                }

                let valorFinalMostrar = "";

                if (countryName === "México" || countryName === "Mexico") {
                    valorFinalMostrar = stateName;
                } else {
                    valorFinalMostrar = countryName || stateName;
                }

                if (inputId === 'destino') {
                    const destinoTxtInput = document.getElementById('destino_txt');
                    if (destinoTxtInput) destinoTxtInput.value = valorFinalMostrar;
                } else if (inputId === 'PuntoPartida') {
                    const originStateInput = document.getElementById('originStateInput');
                    if (originStateInput) originStateInput.value = valorFinalMostrar;
                }

                setTimeout(() => {
                    mapaDiv.style.display = "none";
                    if (typeof calcularDistancia === "function") {
                        calcularDistancia();
                    }
                }, 500);
            });

            return marker;
        }

        function calcularDistancia() {
            if (marcadorPartida && marcadorPartida.position &&
                marcadorDestino && marcadorDestino.position) {

                const posicionPartida = marcadorPartida.position;
                const posicionDestino = marcadorDestino.position;
                const button = $('#saveComission');

                if (typeof google.maps.geometry === 'undefined' || typeof google.maps.geometry.spherical === 'undefined') {
                    console.error("Google Maps Geometry library not loaded. Cannot compute distance.");
                    return;
                }

                const distanciaMetros = google.maps.geometry.spherical.computeDistanceBetween(
                    posicionPartida,
                    posicionDestino
                );

                const distanciaKm = (distanciaMetros / 1000).toFixed(2);

                const elementoDistancia = document.getElementById("distanciaResultado");
                if (elementoDistancia) {
                    if (distanciaKm <= 49.0) {
                        elementoDistancia.textContent =
                            `La distancia mínima para realizar la comisión es de 49km, mientras que la señalada es de: ${distanciaKm} km`;
                        if (button.length) {
                            button.hide();
                        }
                    } else {
                        elementoDistancia.textContent = `La distancia es: ${distanciaKm} km, puede continuar.`;
                        if (button.length) {
                            button.show();
                        }
                    }
                } else {}

            } else {
                const elementoDistancia = document.getElementById("distanciaResultado");
                if (elementoDistancia) {
                    elementoDistancia.textContent = "";
                }
            }
        }

        function calcularDiasSeparados() {
            const fechaInicioInput = document.getElementById('fechaInicio');
            const fechaFinInput = document.getElementById('fechaFin');
            const diasCompletosInput = document.getElementById('diasCompletosInput');
            const medioDiaInput = document.getElementById('medioDiaInput');
            const importeTotalInput = document.getElementById('importeTotalInput');
            const mensajeError = document.getElementById('mensajeError');
            const importeCompletoInput = document.getElementById(
                'completeimport');
            const importeMedioInput = document.getElementById(
                'mediumimport');

            if (!fechaInicioInput || !fechaFinInput || !diasCompletosInput || !medioDiaInput ||
                !importeTotalInput || !mensajeError || !importeCompletoInput || !importeMedioInput) {
                console.error("Error: No se encontraron todos los elementos HTML necesarios para la función.");
                if (mensajeError) {
                    mensajeError.textContent = "Error interno: Faltan elementos de configuración. Contacta al soporte.";
                }
                return;
            }

            diasCompletosInput.value = '';
            medioDiaInput.value = '';
            importeTotalInput.value = '';
            mensajeError.textContent = '';

            const fechaInicio = new Date(fechaInicioInput.value + 'T00:00:00Z');
            const fechaFin = new Date(fechaFinInput.value + 'T00:00:00Z');

            if (isNaN(fechaInicio.getTime()) || isNaN(fechaFin.getTime())) {
                mensajeError.textContent = "Por favor, selecciona ambas fechas válidas.";
                return;
            }

            if (fechaInicio > fechaFin) {
                mensajeError.textContent = "La fecha de inicio no puede ser posterior a la fecha de fin.";
                return;
            }

            const diffTime = fechaFin.getTime() - fechaInicio.getTime();
            const diffDays = Math.round(diffTime / (1000 * 60 * 60 * 24));

            let diasCompletos = 0;
            let medioDia = 0;

            if (diffDays === 0) {
                medioDia = 1;
            } else {
                diasCompletos = diffDays;
                medioDia = 1;
            }

            diasCompletosInput.value = diasCompletos;
            medioDiaInput.value = medioDia;

            const importeCompletoPorDia = parseFloat(importeCompletoInput.value);
            const importeMedioDia = parseFloat(importeMedioInput.value);

            if (isNaN(importeCompletoPorDia) || isNaN(importeMedioDia)) {
                importeTotalInput.value = 'N/A';
                mensajeError.textContent =
                    "Configuración de importes por día no válida. Asegúrate de que los campos de importe (completeimport y mediumimport) tienen valores numéricos.";
                return;
            }

            let importeTotal = (diasCompletos * importeCompletoPorDia) + (medioDia * importeMedioDia);

            if (!isNaN(importeTotal)) {
                importeTotalInput.value = importeTotal.toFixed(2);
            } else {
                importeTotalInput.value = 'N/A';
                mensajeError.textContent = "Error al calcular el importe total.";
            }
        }
        async function initMap() {
            marcadorDestino = await iniciarMapaCampo("destino", "map");
            marcadorPartida = await iniciarMapaCampo("PuntoPartida", "map-partida");
        }

        document.addEventListener('DOMContentLoaded', function() {
            const nominaRadio = document.getElementById('nomina_data');
            const personalRadio = document.getElementById('personal_data');
            const nominaBankData = document.getElementById('nomina_bank_data');
            const personalBankData = document.getElementById('personal_bank_data');
            const estadoCuentaUpload = document.getElementById('estado_cuenta_upload');

            calcularDiasSeparados();
            document.getElementById('selectorEmpleado').addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];

                const numEmpleado = this.value;
                const email = selectedOption.getAttribute('data-email');

                document.getElementById('validador').value = numEmpleado;
                document.getElementById('email').value = email ? email : '';
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $.ajax({
                type: 'GET',
                url: "{{ route('autoEmpleados') }}",
                success: function(data) {
                    $("#claveServidor").autocomplete({
                        source: data,
                        minLength: 2,
                        select: function(event, ui) {
                            obtenDatos(ui.item.label);
                            return true;
                        }
                    });
                },
                error: function(data) {
                    alert("Problemas al tratar de enviar el formulario");
                }
            });

            $('input[name="deposit_method"]').change(function() {
                const nominaFieldsDiv = $('#nomina_bank_data');
                const nominaFields = $('#nombreBanco, #numCuenta, #CuentaClave');
                const nominaDiv = $('#nominaDiv');
                const personalFieldsDiv = $('#personal_bank_data');
                const personalFields = $('#personalNombreBanco, #personalNumCuenta, #personalCuentaClave');
                const estadoCuentaUpload = $('#estado_cuenta_upload');
                const estadoCuentaFile = $('#estadoCuentaFile');

                if (this.value === 'nomina') {
                    nominaFields.prop('readonly', true).val("{{ $datosReporte->nombre_banco }}");
                    $('#numCuenta').val("{{ $datosReporte->cuenta_bancaria }}");
                    $('#CuentaClave').val("{{ $datosReporte->cuenta_clabe }}");
                    nominaFieldsDiv.show();
                    personalFieldsDiv.hide();
                    personalFields.prop('disabled', true).val('');
                    estadoCuentaUpload.hide();
                    estadoCuentaFile.prop('disabled', true).val('');

                } else {
                    nominaFields.prop('readonly', true).val('');
                    $('#numCuenta').val('');
                    $('#CuentaClave').val('');
                    nominaFieldsDiv.hide();
                    personalFieldsDiv.show();
                    personalFields.prop('disabled', false).prop('required', true);
                    estadoCuentaUpload.show();
                    estadoCuentaFile.prop('disabled', false).prop('required', true);
                }
            });
        });


        function obtenDatos(seleccion) {
            var claveServ = $('#claveServidor').val();
            var parametros = {
                "claveServ": claveServ,
                "empleado": seleccion
            };
            $.ajax({
                type: 'GET',
                url: "{{ route('datosUnEmpleado') }}",
                data: parametros,
                success: function(data) {
                    datosObt = data.split('|');
                    var nom = datosObt[2];
                    $('#numEmpleadoValidador').val(datosObt[0]);
                    $('#email').val(datosObt[1]);
                },
                error: function(data) {
                    alert("Problemas al tratar de enviar el formulario");
                }
            });
        }
        document.addEventListener('DOMContentLoaded', function() {
            const tipoComisionSelect = document.getElementById('tipoComision');
            const partidaPasajeSelect = document.getElementById('partidaPasaje');
            const partidaViaticoSelect = document.getElementById('partidaViatico');

            tipoComisionSelect.addEventListener('change', function() {
                const idTypeComision = this.value;

                partidaPasajeSelect.innerHTML = '<option value="">Cargando...</option>';
                partidaViaticoSelect.innerHTML = '<option value="">Cargando...</option>';
                partidaPasajeSelect.disabled = true;
                partidaViaticoSelect.disabled = true;

                if (idTypeComision) {
                    fetch(`/api/partidas/${idTypeComision}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            partidaPasajeSelect.innerHTML =
                                '<option value="">Seleccione...</option>';
                            data.pasaje.forEach(partida => {
                                const option = document.createElement('option');
                                option.value = partida.partida;
                                option.textContent = `${partida.partida}`;
                                partidaPasajeSelect.appendChild(option);
                            });

                            partidaViaticoSelect.innerHTML =
                                '<option value="">Seleccione...</option>';
                            data.viatico.forEach(partida => {
                                const option = document.createElement('option');
                                option.value = partida.partida;
                                option.textContent = `${partida.partida}`;
                                partidaViaticoSelect.appendChild(option);
                            });

                            partidaPasajeSelect.disabled = false;
                            partidaViaticoSelect.disabled = false;
                        })
                        .catch(error => {
                            console.error('Error al obtener las partidas:', error);
                            partidaPasajeSelect.innerHTML = '<option value="">Error al cargar</option>';
                            partidaViaticoSelect.innerHTML =
                                '<option value="">Error al cargar</option>';
                            partidaPasajeSelect.disabled = false;
                            partidaViaticoSelect.disabled = false;
                        });
                } else {
                    partidaPasajeSelect.innerHTML =
                        '<option value="">Seleccione un tipo de comisión</option>';
                    partidaViaticoSelect.innerHTML =
                        '<option value="">Seleccione un tipo de comisión</option>';
                    partidaPasajeSelect.disabled = true;
                    partidaViaticoSelect.disabled = true;
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tipoComision = document.getElementById('tipoComision');
            const wrapperDestino = document.getElementById('wrapper_destino');
            const wrapperInternacional = document.getElementById('wrapper_internacional');

            const selectDestino = document.getElementById('destino');
            const inputInternacional = document.getElementById('destino_internacional');

            const modalidadSelect = document.getElementById('idModalidad');
            let alertMostradoDevengado = false;

            function verificarAdvertenciaDevengado() {
                if (modalidadSelect && modalidadSelect.value == '2') {
                    if (!alertMostradoDevengado) {
                        Swal.fire({
                            title: 'Aviso Importante',
                            text: 'Al elegir viáticos devengados, recuerde cumplir con el punto 13 de los lineamientos. Los formatos de oficio y solicitud deben generarse 2 días hábiles antes de la comisión. Puede consultar el documento completo en el botón "Consulta de lineamientos" (parte superior derecha, a un costado de su nombre).',
                            icon: 'warning',
                            confirmButtonText: 'Entendido',
                            confirmButtonColor: '#3085d6'
                        });
                        alertMostradoDevengado = true;
                    }
                } else {
                    alertMostradoDevengado = false;
                }
            }

            if (modalidadSelect) {
                modalidadSelect.addEventListener('change', verificarAdvertenciaDevengado);
            }
            verificarAdvertenciaDevengado();
            tipoComision.addEventListener('change', function() {
                const valor = this.value;

                if (valor == '1') {
                    wrapperDestino.style.display = 'block';
                    wrapperInternacional.style.display = 'none';

                    selectDestino.setAttribute('required', 'required');
                    inputInternacional.removeAttribute('required');
                    inputInternacional.value = '';
                } else if (valor == '2') {
                    wrapperDestino.style.display = 'none';
                    wrapperInternacional.style.display = 'block';

                    inputInternacional.removeAttribute('readonly');
                    inputInternacional.setAttribute('required', 'required');
                    selectDestino.removeAttribute('required');
                    selectDestino.value = '';
                } else {
                    wrapperDestino.style.display = 'none';
                    wrapperInternacional.style.display = 'none';
                }
            });

            $('#formComision').on('submit', function() {
                const $btn = $('#btnEnviarComision');
                if (!$btn.prop('disabled')) {
                    $btn.prop('disabled', true);
                    $btn.html(
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enviando...'
                    );
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const modalidadInput = document.getElementById('idModalidad');
            const tipoComisionSelect = document.getElementById('tipoComision');
            const fechaInicioInput = document.getElementById('fechaInicio');
            const fechaFinInput = document.getElementById('fechaFin');
            const urInput = document.getElementById('numNombreUr');

            if (!modalidadInput || !tipoComisionSelect || !fechaInicioInput || !fechaFinInput || !urInput) return;

            function formatearFecha(date) {
                const yyyy = date.getFullYear();
                const mm = String(date.getMonth() + 1).padStart(2, '0');
                const dd = String(date.getDate()).padStart(2, '0');
                return `${yyyy}-${mm}-${dd}`;
            }

            function actualizarLimitesCalendario() {
                const hoy = new Date();
                const modalidad = modalidadInput.value;
                const tipoComision = tipoComisionSelect.value;

                const userUnidad = parseInt(urInput.value, 10);
                const permiss = [
                    100, 121, 122, 123, 124, 111, 130, 131, 132, 133, 134, 135, 140, 141, 142, 143,
                    144, 190, 500, 150, 151, 152, 221, 400, 171, 172, 'E00', 410, 430, 160, 161,
                    162, 163, 200, 210, 211, 212, 230, 231, 240, 250, 252, 260, 270, 280, 281, 290
                ];

                const hasPermission = !isNaN(userUnidad) && permiss.includes(userUnidad);

                // Limpiamos valores actuales
                fechaInicioInput.value = '';
                fechaFinInput.value = '';

                const diasTolerancia = (tipoComision == '2' || tipoComision === 'INTERNACIONAL') ? 90 : 60;

                const fechaMax = new Date(hoy);
                fechaMax.setDate(hoy.getDate() + diasTolerancia);
                const maxStr = formatearFecha(fechaMax);

                const fechaMinFija = new Date(2026, 7, 1); 
                console.log("fechaMinFija", fechaMinFija)

                if (modalidad == '1' || modalidad == '2' || modalidad === 'DEVENGADA') {
                    console.log("if");
                    let fechaMinObj = new Date(2026, 7, 1); 
                    if (hasPermission) {
                        fechaMinObj.setMonth(fechaMinObj.getMonth() - 2);
                    }

                    if (fechaMinObj < fechaMinFija) {
                        fechaMinObj = fechaMinFija;
                    }
                    const minStr = formatearFecha(fechaMinObj);
                    console.log("if" + fechaMinObj + ' --- ' + fechaMinFija + ' minStr ' + minStr);

                    fechaInicioInput.min = minStr;
                    fechaInicioInput.max = maxStr;
                    fechaFinInput.min = minStr;
                    fechaFinInput.max = maxStr;

                } else {
                    let fechaMinObj = hoy < fechaMinFija ? fechaMinFija : hoy;
                    const minStr = formatearFecha(fechaMinObj);

                    fechaInicioInput.min = minStr;
                    fechaInicioInput.max = maxStr;

                    fechaFinInput.min = minStr;
                    fechaFinInput.max = maxStr;
                    console.log("else");
                }
            }
            modalidadInput.addEventListener('change', actualizarLimitesCalendario);
            tipoComisionSelect.addEventListener('change', actualizarLimitesCalendario);

            actualizarLimitesCalendario();
        });
    </script>
@endpush
