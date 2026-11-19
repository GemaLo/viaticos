@extends('admin.layouts.master')

@section('title', 'Usuarios')

<style>
    .cards-users {
        display: flex;
        justify-content: space-between;
    }

    .car.card-move {
        padding: 0px !important;
    }

    .form-label {
        color: white;
    }

    .buttons-submit a {
        color: white !important;
    }
</style>

@section('content')
    {{-- mensaje de error --}}
    <!-- Modal de Error -->
<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="errorModalLabel">¡Error!</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          {{ session('error') }}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

{{-- 
  @if (session('success'))
    <div class="alert alert-success position-fixed top-0 start-50 translate-middle-x shadow" role="alert">{{ $success }}</div>
@endif   --}}


    <div id="mainContent" class="main flex-grow p-10 ml-40 transition-all duration-300">
        <div class="row align-items-start">
            <div class="cards-users">
                <div class="col-xl-9 col-sm-6 mb-xl-0 mb-4 ">
                    <div class="card card-move">
                        <h2 class="contadores-text">Bitácora de movimientos </h2>
                        <hr class="ht-style">
                        <form method="GET" action="{{ route('movimiento') }}">
                            <br>
                            <div class="row mb-3">
                                @if ($role != "Enlace")
                                <div class="col-md-2">
                                    <label for="nombre" class="form-label contadores-text">Nombre:</label>
                                    <input type="text" name="nombre" class="form-control" placeholder="Nombre"
                                    value="{{ request('nombre') }}">
                                </div>
                                @endif
                                <div class="col-md-2">
                                    <label for="nombre" class="form-label contadores-text">Movimiento:</label>
                                    <select name="movimiento" class="form-control">
                                        <option value="">-- Selecciona Movimiento --</option>
                                        <option value="Descarga recibo"
                                            {{ request('movimiento') == 'Descarga recibo' ? 'selected' : '' }}>Descarga recibo</option>
                                        <option value="Consulta recibo"
                                            {{ request('movimiento') == 'Consulta recibo' ? 'selected' : '' }}>Consulta recibo</option>
                                        <option value="Alta usuario"
                                            {{ request('movimiento') == 'Alta usuario' ? 'selected' : '' }}>Alta usuario</option>
                                        <option value="Actualización usuario"
                                            {{ request('movimiento') == 'Actualización usuario' ? 'selected' : '' }}>Actualización usuario</option>    
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="fecha_inicial" class="form-label contadores-text">Fecha Inicial:</label>
                                    <input type="date" name="fecha_inicial" class="form-control"
                                        placeholder="Fecha Inicial" value="{{ request('fecha_inicial') }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="fecha_final" class="form-label contadores-text">Fecha Final:</label>
                                    <input type="date" name="fecha_final" class="form-control"
                                        placeholder="Fecha Final" value="{{ request('fecha_final') }}">
                                </div>
                            </div>

                            <!-- Botón de Exportar Excel -->
                            <div class="row">
                                <div class="col-md-2">
                                    <button type="submit" class="button-sumit btn mb-3"
                                        style="background-color:A57F2C; color:white;">BUSCAR</button>
                                    <a href="{{ route('movimiento') }}" class="btn btn-danger">LIMPIAR</a>
                                </div>
                                <div class="col-md-3">
                                    <input type="hidden" name="export" id="export-input" value="">
                                    <button type="submit" onclick="setExportValue()" class="btn btn-success mb-3">DESCARGAR
                                        EXCEL</button>

                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <div class="row align-items-center">
                <div class="col-xl-12 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <h1>Ultimos movimientos</h1> <br>
                        <div class="table-responsive-md">
                        <table
                            summary="This table shows how to create responsive tables using RWD-Table-Patterns' functionality"
                            class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th data-priority="1">Nombre empleado</th>
                                    <th data-priority="3">Fecha y hora de movimiento</th>
                                    <th data-priority="4">UR</th>
                                    <th data-priority="5">Fecha y Hora de Movimiento</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($movimientos))
                                    @foreach ($movimientos as $index => $movimiento)
                                        <tr>
                                            <td>{{ $movimiento->nombre }}</td>
                                            <td>{{ $movimiento->movimiento == null ? 'Sin movimientos' : $movimiento->movimiento }}
                                            </td>
                                            <td>{{ $movimiento->seccion }}</td>
                                            <td>{{ $movimiento->hora_entrada }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    @endsection
    <script>
        function setExportValue() {
            document.getElementById('export-input').value = '1';
        }
    </script>
    @if (session('error'))
    <script>
        window.onload = function() {
            $('#errorModal').modal('show');
        };
    </script>
@endif

