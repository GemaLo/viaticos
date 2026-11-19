@extends('layouts.master')
@section('title', 'Usuarios')

@section('vendor-style')
    @vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
@endsection

@section('vendor-script')
    @vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection

@section('page-script')
    @vite('resources/assets/js/dashboards-analytics.js')
@endsection


@section('content')
    <div id="mainContent" class="main flex-grow p-10 ml-40 transition-all duration-300">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-9 mx-auto">
                                <!-- 1. Delivery Address -->
                                <h1 class="mb-4 display-4">Formato solicitud Viaticos</h1>
                                <hr style="  border: 3px solid red;">

                                <h3 class="mb-4 mt-2 display-6">Datos del comisionado</h3>
                                <div class="row g-6">
                                    <div class="col-md-12">
                                        <label class="form-label" for="numNombreUr">Numero y Nombre de la unidad
                                            responsable</label>
                                        <input type="text" id="numNombreUr" class="form-control"
                                            placeholder="Nota: se setearan los datos encueanto tengamos la vista a sirno">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="lugar">Lugar</label>
                                        <input type="text" id="lugar" class="form-control" placeholder="">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="fecha">Fecha</label>
                                        <input type="date" id="fecha" class="form-control" placeholder="">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="oficioComision">Oficio de Comisión</label>
                                        <input type="text" id="oficioComision" class="form-control"
                                            placeholder="ViaticoABSRDFS185123">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="fullname">Nombre Completo</label>
                                        <input type="text" id="fullname" class="form-control"
                                            placeholder="Nota: se setearan los datos encueanto tengamos la vista a sirno">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="cargo">Cargo</label>
                                        <input type="text" id="cargo" class="form-control"
                                            placeholder="Nota: se setearan los datos encueanto tengamos la vista a sirno">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="nivel">Nivel</label>
                                        <input type="text" id="nivel" class="form-control"
                                            placeholder="Nota: se setearan los datos encueanto tengamos la vista a sirno">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="rfc">RFC</label>
                                        <input type="text" id="rfc" class="form-control"
                                            placeholder="Nota: se setearan los datos encueanto tengamos la vista a sirno">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="curp">CURP</label>
                                        <input type="text" id="curp" class="form-control"
                                            placeholder="Nota: se setearan los datos encueanto tengamos la vista a sirno">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="numEmpleado">Numero del Empleado</label>
                                        <input type="number" id="numEmpleado" class="form-control"
                                            placeholder="Nota: se setearan los datos encueanto tengamos la vista a sirno">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="nombreBanco">Nombre del Banco</label>
                                        <input type="text" id="nombreBanco" class="form-control"
                                            placeholder="Nota: se setearan los datos encueanto tengamos la vista a sirno">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="numCuenta">Numero de Cuenta</label>
                                        <input type="number" id="numCuenta" class="form-control"
                                            placeholder="Nota: se setearan los datos encueanto tengamos la vista a sirno">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="CuentaClave">Cuenta Clave</label>
                                        <input type="number" id="CuentaClave" class="form-control"
                                            placeholder="Nota: se setearan los datos encueanto tengamos la vista a sirno">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="domicilioAdscripcion">Domicilio Adscripción</label>
                                        <input type="text" id="domicilioAdscripcion" class="form-control"
                                            placeholder="Nota: se setearan los datos encueanto tengamos la vista a sirno">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="numViaje">Numero de Viaje</label>
                                        <input type="text" id="numViaje" class="form-control phone-mask"
                                            placeholder="658 799 89">
                                    </div>


                                    <h3 class="mb-4 display-5">Datos de la Comision Oficial</h3>
                                    <hr style="  border: 2px solid red;">

                                    <div class="col-md-6">
                                        <label class="form-label" for="tipoViatico">Tipo Viatico</label>
                                        <input type="text" id="tipoViatico" class="form-control"
                                            placeholder="Nota: se setearan los datos encueanto tengamos la vista a sirno">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="PartidaPasaje">Partida del Pasaje</label>
                                        <input type="text" id="PartidaPasaje" class="form-control"
                                            placeholder="Nota: se setearan los datos encueanto tengamos la vista a sirno">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="curp">Partida del Viatico</label>
                                        <input type="text" id="curp" class="form-control"
                                            placeholder="Nota: se setearan los datos encueanto tengamos la vista a sirno">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="numEmpleado">Tipo Transporte</label>
                                        <input type="number" id="numEmpleado" class="form-control"
                                            placeholder="Nota: se setearan los datos encueanto tengamos la vista a sirno">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="placas">Placas</label>
                                        <input type="text" id="placas" class="form-control"
                                            placeholder="Nota: se setearan los datos encueanto tengamos la vista a sirno">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="numInventario">Numero de Inventario</label>
                                        <input type="number" id="numInventario" class="form-control"
                                            placeholder="Nota: se setearan los datos encueanto tengamos la vista a sirno">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="pacHotel">Paquete Hotel Incluido</label>
                                        <input type="number" id="pacHotel" class="form-control"
                                            placeholder="Nota: se setearan los datos encueanto tengamos la vista a sirno">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="pacTransporte">Paquete Transporte Incluido</label>
                                        <input type="text" id="pacTransporte" class="form-control"
                                            placeholder="Nota: se setearan los datos encueanto tengamos la vista a sirno">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="Destino">Destino</label>
                                        <input type="text" id="direccion" class="form-control mb-3"
                                            placeholder="Ej. Ciudad de México">
                                        <!-- Coordenadas Destino -->
                                        <input type="hidden" id="lat-destino">
                                        <input type="hidden" id="lng-destino">
                                        {{-- <!-- Contenedor del mapa --> --}}
                                        <div id="map"
                                            style="display: none; height: 400px; width: 100%; border-radius: 8px;"></div>


                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="PuntoPartida">Punto de Partido</label>
                                        <input type="text" id="PuntoPartida" class="form-control"
                                            placeholder="Ej. Guadalajara, Jalisco">

                                        <!-- Coordenadas Punto de Partida -->
                                        <input type="hidden" id="lat-partida">
                                        <input type="hidden" id="lng-partida">
                                        <div id="map-partida"
                                            style="display: none; height: 400px; width: 100%; border-radius: 8px;"
                                            class="mb-4"></div>
                                    </div>

                                    <hr>
                                    <h1 class="mb-1 display-6">Periodo</h1>
                                    <hr style="  border: 2px solid red;">
                                    <div class="col-md-6">
                                        <label class="form-label" for="fechaInicio">Fecha de Inicio</label>
                                        <input type="date" id="fechaInicio" class="form-control"
                                            placeholder="658 799 89">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="fechaFinal">Fecha de Final</label>
                                        <input type="date" id="fechaFinal" class="form-control"
                                            placeholder="658 799 89">
                                    </div>
                                    <h1 class="mb-1 display-6">Dias Otorgados</h1>
                                    <div class="col-md-6">
                                        <label class="form-label" for="fechaInicio">Completo</label>
                                        <input type="date" id="fechaInicio" class="form-control"
                                            placeholder="658 799 89">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="fechaFinal">Menor a 24HRS</label>
                                        <input type="date" id="fechaFinal" class="form-control"
                                            placeholder="658 799 89">
                                    </div>
                                    <h1 class="mb-1 display-6">Cuata por dia</h1>

                                    <div class="col-md-6">
                                        <label class="form-label" for="fechaInicio">Completo</label>
                                        <input type="date" id="fechaInicio" class="form-control"
                                            placeholder="658 799 89">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="fechaFinal">Menor a 24HRS</label>
                                        <input type="date" id="fechaFinal" class="form-control"
                                            placeholder="658 799 89">
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label" for="fechaFinal">Importe</label>
                                        <input type="text" id="fechaFinal" class="form-control"
                                            placeholder="658 799 89">
                                    </div>

                                    <hr style="border: 1px solid red;">

                                    <div class="col-md-12">
                                        <label class="form-label" for="fechaFinal">Objetivo</label>
                                        <input type="text" id="fechaFinal" class="form-control"
                                            placeholder="658 799 89">
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label" for="fechaFinal">Clave Presupuestal</label>
                                        <input type="text" id="fechaFinal" class="form-control"
                                            placeholder="658 799 89">
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label" for="fechaFinal">Autorizado por</label>
                                        <input type="text" id="fechaFinal" class="form-control"
                                            placeholder="658 799 89">
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label" for="fechaFinal">Nombre y Firma</label>
                                        <input type="text" id="fechaFinal" class="form-control"
                                            placeholder="658 799 89">
                                    </div>

                                    <div class="col-md-12">
                                        <label for="justificacion" class="form-label">Justificación</label>
                                        <textarea id="justificacion" class="form-control" rows="7" disabled style="resize: none;"
                                            name="justificacion">El titular de la Unidad Responsable que suscribe, manifiesta, bajo protesta de decir verdad, que la presente comisión es estrictamente necesaria para dar cumplimiento con los objetivos institucionales, los programas o las funciones conferidas a la dependencia y que esta comisión no puede ser efectuada por el personal de las oficinas o representaciones locales o regionales que tiene la dependencia en el lugar de la comisión.</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
<!-- API de Google Maps con Autocomplete -->
{{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCQyqxColpMIJLf4pn12qTui0gchYmCSh8&libraries=places"></script>
<script>
  const input = document.getElementById('direccion');
  const autocomplete = new google.maps.places.Autocomplete(input);
</script> --}}

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCQyqxColpMIJLf4pn12qTui0gchYmCSh8&libraries=places">
</script>
<script>
    function iniciarMapaCampo(inputId, mapaId, defaultCoords = {
        lat: 19.4326,
        lng: -99.1332
    }) {
        const input = document.getElementById(inputId);
        const mapaDiv = document.getElementById(mapaId);

        const map = new google.maps.Map(mapaDiv, {
            center: defaultCoords,
            zoom: 14,
        });

        const marker = new google.maps.Marker({
            map,
            position: defaultCoords
        });

        const autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo("bounds", map);

        input.addEventListener("click", () => {
            mapaDiv.style.display = "block";
            setTimeout(() => {
                google.maps.event.trigger(map, 'resize');
                map.setCenter(marker.getPosition());
            }, 300);
        });

        autocomplete.addListener("place_changed", () => {
            const place = autocomplete.getPlace();
            if (!place.geometry || !place.geometry.location) {
                alert("No se encontró la ubicación seleccionada.");
                return;
            }

            map.setCenter(place.geometry.location);
            map.setZoom(16);
            marker.setPosition(place.geometry.location);

            setTimeout(() => {
                mapaDiv.style.display = "none";
            }, 500);
        });
    }

    window.onload = () => {
        iniciarMapaCampo("direccion", "map");
        iniciarMapaCampo("PuntoPartida", "map-partida");
    };
</script>
