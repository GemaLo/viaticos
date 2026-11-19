@extends('layouts.master')
@section('title', 'Comprobaciones')

@section('vendor-style')
    @vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" />
@endsection

@section('vendor-script')
    @vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('page-script')
    @vite('resources/assets/js/dashboards-analytics.js')
@endsection
<style>
    .table-container {
        overflow-x: auto;
    }

    h3 {
        border-bottom: 2px solid #007bff;
        padding-bottom: 5px;
        margin-top: 1.5rem;
        margin-bottom: 1rem;
    }
</style>
@section('content')
    @php
        $endStatus = [3, 18, 6, 15, 24, 28, 27];
        $button = [21, 22, 23, 25, 26, 12, 13, 14];

    @endphp
    <div id="mainContent" class="main flex-grow p-10 ml-40 transition-all duration-300">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success col-md-12" role="alert">{{ $message }}
                            </div>
                        @endif
                        @if ($message = Session::get('warning'))
                            <div class="alert alert-warning col-md-12" role="alert">{{ $message }}
                            </div>
                        @endif
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
                        <a href="{{ route('acusePDF', ['idComision' => Crypt::encryptString($query->idcomision)]) }}"
                            class="btn btn-sm btn-outline-primary" target="_blank">
                            Acuse de comisión
                        </a>

                        <a href="{{ route('solicitudPDF', ['idComision' => Crypt::encryptString($query->idcomision)]) }}"
                            class="btn btn-sm btn-outline-primary" target="_blank">
                            Solicitud de comisión
                        </a>
                        <a href="{{ route('generarGastosSinComprobantePDF', ['idComision' => Crypt::encryptString($query->idcomision)]) }}"
                            class="btn btn-sm btn-outline-success" target="_blank">
                            Formato Gastos Sin Comprobante
                        </a>
                        @php
                            $idPerFormato = [15, 24, 25, 26, 27, 28];
                            $idPerRemanente = [9, 10, 11, 12, 13, 14];
                        @endphp
                        @if (in_array($query->idstatuscomision, $idPerRemanente) && $query->idmodalidad == 1)
                            <button type="button" class="btn btn-sm btn-outline-primary" id="boton-datos-bancarios">
                                Datos Bancarios
                            </button>
                        @endif
                        @if (Auth::user()->idtype == 1)
                            <a href="{{ route('comprobacionEnd', ['idComision' => $query->idcomision]) }}"
                                class="btn btn-sm btn-success">Enviar Revisión</a>
                        @endif

                        @if (in_array($query->idstatuscomision, $idPerFormato))
                            <a href="{{ route('formatoComprobacion', ['idComision' => Crypt::encryptString($query->idcomision)]) }}"
                                class="btn btn-sm btn-primary">Formato de comprobación</a>
                        @endif
                        @php
                            $idUserAdmit = [691, 643, 690];
                        @endphp

                        @if (Auth::user()->idtype == 1 || in_array(Auth::user()->id_user, $idUserAdmit))
                            <div class="alert alert-info flex justify-between items-center" role="alert">
                                <span>Información de la Comisión</span>
                                <button type="button" class="btn btn-sm btn-info text-white" id="btn-historial-movimientos"
                                    title="Ver movimientos">
                                    <i class="fas fa-info-circle"></i> Ver Movimientos
                                </button>
                            </div>
                        @endif
                        <div class="modal fade" id="modalMovimientos" tabindex="-1" aria-labelledby="modalMovimientosLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalMovimientosLabel">
                                            <i class="fas fa-history text-info"></i> Historial de Movimientos — Comisión
                                            #{{ $query->comision }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div id="contenido-modal-movimientos" class="text-center py-4">
                                            <div class="spinner-border text-info" role="status">
                                                <span class="visually-hidden">Cargando...</span>
                                            </div>
                                            <p class="mt-2 text-muted">Buscando movimientos...</p>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form action="{{ route('updateComision') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-6">
                                <div class="col-md-6">
                                    <label class="form-label" for="numNombreUr">Número y Nombre de la unidad
                                        responsable</label>
                                    <input type="text" id="numNombreUr" class="form-control"
                                        value="{{ $query->unidad }} - {{ $query->adscripcion }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="fullname">Nombre Completo</label>
                                    <input type="text" id="fullname" class="form-control"
                                        value="{{ $query->nombre_completo }}" readonly>
                                </div>
                                <div class="col-md-8" hidden>
                                    <label class="form-label" for="idcomision">Id de Comisión</label>
                                    <input type="text" id="idcomision" name="idcomision" class="form-control"
                                        value="{{ $query->idcomision }}" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label" for="initialdate">Folio de la comisión</label>
                                    <input type="text" id="folio"
                                        value="{{ $query->acronym }}_{{ $query->comision }}_{{ $query->yearcomision }}"
                                        class="form-control" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label" for="initialdate">Fecha de inicio </label>
                                    <input type="date" id="initialdate" name="initialdate"
                                        value="{{ date('Y-m-d', strtotime($query->initialdate)) }}" class="form-control"
                                        readonly>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label" for="finaldate">Fecha final</label>
                                    <input type="date" id="finaldate" name="finaldate"
                                        value="{{ date('Y-m-d', strtotime($query->finaldate)) }}" class="form-control"
                                        readonly>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label" for="typecomision">Tipo de Comisión</label>
                                    <input type="text" id="typecomision" name="typecomision" class="form-control"
                                        value="{{ $query->typecomision }}" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label" for="numEmpleado">Número del Empleado</label>
                                    <input type="number" id="numEmpleado" class="form-control"
                                        value="{{ $query->numempleado }}" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label" for="nota">Destino: </label>
                                    <input type="text" id="destino" name="destino" value="{{ $query->destino }}"
                                        class="form-control" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label" for="nota">Importe Completo: </label>
                                    <input type="text" id="nota" name="nota"
                                        value="$ {{ number_format($importes_ley->completo, 2) }}" class="form-control"
                                        readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label" for="nota">Importe Medio Día: </label>
                                    <input type="text" id="nota" name="nota"
                                        value="$ {{ number_format($importes_ley->medio, 2) }}" class="form-control"
                                        readonly>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label" for="deposito">Información de Depósito</label>
                                    <input type="text"class="form-control" value="{{ $query->deposito }}"
                                        name="deposito" @if (Auth::user()->idtype == 1) @else readonly @endif>
                                </div>
                                @if (Auth::user()->idtype == 1)
                                    <div class="col-md-2">
                                        <label class="form-label" for="clc">CLC</label>
                                        <input type="text" id="clc" class="form-control"
                                            value="{{ $query->clc }}" name="clc"
                                            @if (!in_array($query->idstatuscomision, $endStatus)) @else disabled @endif>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Documentación física:</label>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="radio" name="documentacionfisica"
                                                id="si" value="1"
                                                {{ $query->documentacionfisica == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="si">
                                                SI
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="documentacionfisica"
                                                id="no" value="0"
                                                {{ $query->documentacionfisica == 0 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="no">
                                                NO
                                            </label>
                                        </div>
                                    </div>
                                    @php
                                        $right = [243, 30];
                                    @endphp
                                    @php
                                        $validadorEnLista = $autorizadores->contains(
                                            fn($autorizador) => (string) $autorizador->numempleado ===
                                                (string) $query->validador,
                                        );
                                    @endphp


                                    <div class="col-md-4">
                                        <label class="form-label" for="validador">Autorizador</label>
                                        <select name="validador" id="validador" class="form-control"
                                            @if (!in_array(Auth::id(), $right)) required @endif>
                                            @if ($query->validador && !$validadorEnLista)
                                                <option value="{{ $query->validador }}" selected>
                                                    {{ $validador->nombre_completo ?? 'Autorizador registrado' }}
                                                </option>
                                            @endif

                                            @foreach ($autorizadores as $autorizador)
                                                <option value="{{ $autorizador->numempleado }}"
                                                    @selected((string) $query->validador === (string) $autorizador->numempleado)>
                                                    {{ $autorizador->nomusuario }}
                                                    {{ $autorizador->appusuario }}
                                                    {{ $autorizador->apmusuario }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label" for="idstatuscomision">Status de Comisión</label>
                                        <select name="idstatuscomision" id="idstatuscomision" class="form-control"
                                            data-rev1="{{ $query->rev1_estatus ?? '' }}"
                                            data-rev2="{{ $query->rev2_estatus ?? '' }}"
                                            @if (in_array(Auth::id(), $right)) @else required @endif>

                                            <option value="">SELECCIONE UN STATUS NUEVO A LA COMISIÓN</option>

                                            @foreach ($statusComision as $status)
                                                <option value="{{ $status->idstatuscomision }}"
                                                    {{ $query->idstatuscomision == $status->idstatuscomision ? 'selected' : '' }}>
                                                    {{ $status->statuscomision }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if (in_array($query->idstatuscomision, $button) && $query->idmodalidad == 1)
                                        @php
                                            $montoReal = $reintegro->monto ?? 0;
                                            $esSaldoAFavor = (float) $montoReal < 0;
                                        @endphp

                                        <div class="col-md-2">
                                            <label class="form-label" id="lbl_monto_reintegro" for="monto_reintegro">
                                                {{ $esSaldoAFavor ? 'Saldo a favor del comisionado' : 'Monto a reintegrar' }}
                                            </label>

                                            <input type="text" id="monto_reintegro" class="form-control"
                                                value="${{ number_format(abs($montoReal), 2) }}" name="monto_reintegro">

                                            <input type="hidden" id="monto_original" value="{{ $montoReal }}">
                                        </div>
                                    @endif

                                    @if (Auth::user()->idtype == 1 && in_array($query->idstatuscomision, $button) && $query->idmodalidad == 1)
                                        <div class="col-md-2">
                                            <button type="button" id="btnCalcularReintegro" class="btn btn-primary"
                                                style="margin-top: 25px;">
                                                <i class="fas fa-calculator"></i> Calcular y Guardar Reintegro
                                            </button>
                                        </div>
                                    @endif
                                    <div class="col-12 col-md-3">
                                        <label class="form-label">Estatus:</label>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="radio" name="status" id="permitir"
                                                value="1" {{ $query->status == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="permitir">
                                                Permitir captura de facturas
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status" id="retringir"
                                                value="0" {{ $query->status == 0 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="retringir">
                                                Cerrar fechas
                                            </label>
                                        </div>
                                        @error('active')
                                            <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @if (!is_null($query->rev1_fecha) || !is_null($query->rev2_fecha))
                                        <div class="col-6 my-2">
                                            <label class="form-label" for="notarevisor">Historial de Revisiones:</label>
                                            <div class="p-9 bg-light rounded border">
                                                <div class="d-flex flex-wrap gap-4">
                                                    @if (!is_null($query->rev1_fecha))
                                                        <div>
                                                            <span class="badge bg-info text-dark">1ª Revisión
                                                                realizada</span>
                                                            <small class="d-block text-muted mt-1">
                                                                <strong>Fecha:</strong>
                                                                {{ \Carbon\Carbon::parse($query->rev1_fecha)->format('d/m/Y H:i:s') }}
                                                            </small>
                                                        </div>
                                                    @endif
                                                    @if (!is_null($query->rev2_fecha))
                                                        <div>
                                                            <span class="badge bg-warning text-dark">2ª Revisión
                                                                realizada</span>
                                                            <small class="d-block text-muted mt-1">
                                                                <strong>Fecha:</strong>
                                                                {{ \Carbon\Carbon::parse($query->rev2_fecha)->format('d/m/Y H:i:s') }}
                                                            </small>
                                                        </div>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-md-6 mt-2">
                                        <label class="form-label" for="notarevisor">Notas del Revisor:</label>
                                        <textarea id="notarevisor" name="notarevisor" class="form-control" rows="5"
                                            placeholder="Escriba aquí observaciones o notas relativas a la revisión...">{{ old('notarevisor', $query->notarevisor) }}</textarea>
                                    </div>
                                    <div class="col-12 mt-4">
                                        @if (!in_array($query->idstatuscomision, $endStatus))
                                            <button type="submit" class="btn btn-primary">Enviar</button>
                                        @endif

                                        <a href="{{ route('validateComition') }}" class="btn btn-danger">Regresar a
                                            Consulta</a>
                                    </div>
                                @endif
                            </div>
                        </form>
                        @if (!empty($query->comprobante_reintegro) && $query->idmodalidad == 1)
                            <div class="alert alert-info" role="alert">Comprobante de reintegro por concepto:
                                {{ $query->tiporeintegro }} </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="form-label" for="comprobanteReintegro">Comprobante de
                                        Reintegro</label><br>
                                    <a href="{{ route('downloadAditional', ['name_file' => Crypt::encryptString(trim($query->comprobante_reintegro))]) }}"
                                        class="btn btn-sm btn-outline-success" target="_blank">Ver Comprobante</a>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="tipoDeposito">Tipo Deposito</label>
                                    <input type="text" id="tipoDeposito" class="form-control"
                                        value="{{ $query->tipodeposito }}" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label" id="lbl_monto_reintegro" for="monto_reintegro">
                                        {{ isset($reintegro->monto) && $reintegro->monto < 0 ? 'Saldo a favor del comisionado' : 'Monto a reintegrar' }}
                                    </label>

                                    <input type="text" id="monto_reintegro" class="form-control" readonly
                                        value="${{ number_format(abs($reintegro->monto ?? 0), 2) }}">

                                    <input type="hidden" id="monto_original" name="monto"
                                        value="{{ $reintegro->monto ?? 0 }}">
                                </div>
                                @if (Auth::user()->idtype == 1)
                                    <div class="col-md-2">
                                        <label class="form-label">&nbsp;</label><br>
                                        <form id="delete-comprobante-form" method="POST"
                                            action="{{ route('reintegro.eliminarComprobante', $query->idreintegro) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="button" id="btn-eliminar-comprobante"
                                                class="btn btn-sm btn-outline-warning">
                                                Eliminar Comprobante
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @endif
                        <div class="row g-6">
                            @if (!empty($query->nota))
                                <div class="col-md-12">
                                    <label class="form-label" for="nota">Notas: </label>
                                    <textarea id="nota" name="nota" class="form-control" rows="7" readonly>{{ $query->nota }}</textarea>
                                </div>
                            @endif
                            @if (Auth::user()->idtype == 3)
                                <div class="col-md-2">
                                    <a href="{{ route('dashboard.index') }}"
                                        class="btn btn-sm btn-outline-danger">Regresar a Consulta</a>
                                </div>
                            @endif
                            @if (Auth::user()->idtype == 2)
                                <div class="col-md-12">

                                    <a href="{{ route('validateComition') }}"
                                        class="btn btn-sm btn-outline-danger">Regresar a consulta</a>
                                </div>
                            @endif
                        </div>
                        @if ($query->monto_reintegro == 0 && Auth::user()->id_user == $query->id_user && $query->idmodalidad == 1)
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="alert alert-light border"
                                        style="border-left: 5px solid #dc3545 !important;">
                                        <p class="mb-0">
                                            <i class="fas fa-info-circle text-danger"></i>
                                            Una vez que el área de <strong>DGPPR</strong> valide las facturas
                                            correspondientes a esta comisión,
                                            se solicitará el depósito del reintegro Asegúrese de que el comprobante adjunto
                                            cuente
                                            con la información solicitada en el botón llamado:
                                            <span style="color: red; font-weight: bold;">Datos Bancarios</span>.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if (in_array($query->idstatuscomision, $idPerRemanente) &&
                                empty($query->comprobante_reintegro) &&
                                $query->cancelacion_reintegro != '1' &&
                                !empty($query->monto_reintegro) &&
                                Auth::user()->id_user == $query->id_user)
                            <form action="{{ route('uploadComprobante') }}" method="POST" enctype="multipart/form-data"
                                id="uploadForm">
                                @csrf

                                <input type="text" name="idcomision" value="{{ $idComision }}" hidden>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label" for="comprobanteReintegro">Comprobante de
                                            Reintegro</label> <span style="color: red;">(Únicamente archivos: .jpg,
                                            .png y .pdf)</span>
                                        <input type="file" id="comprobanteReintegro" name="comprobante"
                                            class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="tipoComision">Tipo Deposito</label>
                                        <select name="idtipodeposito" class="form-control" id="idTipoDeposito" required>
                                            <option value="">Seleccione tipo deposito realizado</option>
                                            @foreach ($tipoDepositos as $deposito)
                                                <option value="{{ $deposito->iddeposito }}">
                                                    {{ $deposito->tipodeposito }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label" for="monto_reintegro">Monto a reintegrar</label>
                                        <input type="text" id="monto_reintegro" class="form-control"
                                            value="${{ number_format($query->monto_reintegro, 2, '.', ',') }}" disabled>
                                        <input type="text" id="monto_reintegro" class="form-control"
                                            value="{{ $query->monto_reintegro }}" name="monto" hidden>
                                    </div>
                                    <div class="col-md-4">

                                        <button type="submit" class="btn btn-secondary">Guardar</button>
                                    </div>
                                </div>
                            </form>
                        @endif



                        <h1>Resumen de Facturas</h1>
                        <div class="resumen">
                            <strong>Total Comprobado (Facturas):</strong>
                            ${{ number_format($comprobaciones->flatten()->sum('importe'), 2, '.', ',') }}
                        </div>
                        <input type="hidden" id="currentCommissionId" value="{{ $idComision }}">
                        <div class="table-container">
                            <table id="facturas-table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID Factura</th>
                                        <th>UUID</th>
                                        <th>Importe</th>
                                        <th>Fecha Factura</th>
                                        <th>Descripción</th>
                                        <th>Usuario</th>
                                        <th>Tipo de Gasto</th>
                                        <th>Status</th>
                                        <th>Observaciones (Revisor)</th>
                                        <th>Adjuntar Comprobante</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="alert alert-info" role="alert">Comprobantes Cargados</div>
                        @if ($gastos->isEmpty())
                            <div class="alert alert-info" role="alert">
                                Aún no hay comprobantes cargados para esta comisión.
                            </div>
                        @else
                            <div class="table-container">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Fecha de Compra</th>
                                            <th>Concepto</th>
                                            <th>Monto</th>
                                            <th>Archivo</th>
                                            <th>Observaciones</th>
                                            @if (Auth::user()->idtype == 1)
                                                <th>Acciones</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $totalGastado = 0; @endphp
                                        @foreach ($gastos as $gasto)
                                            <tr>
                                                <td>{{ date('d/m/Y', strtotime($gasto->datepay)) }}</td>
                                                <td>{{ $gasto->conceptopago }}</td>
                                                <td>$ {{ number_format($gasto->monto, 2) }}</td>
                                                <td>
                                                    @if ($gasto->proof_file)
                                                        <a href="{{ route('tickets.download', $gasto->idsincomprobante) }}"
                                                            class="btn btn-sm btn-outline-info">
                                                            Descargar
                                                        </a>
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($gasto->activo == 1)
                                                        SIN OBSERVACIONES
                                                    @else
                                                        <strong style="color: red;">GASTO ANULADO POR PARTE DE LA DGPPR,
                                                            LAS OBSERVACIONES SON LAS SIGUIENTES:</strong>
                                                        {{ $gasto->observaciones }}
                                                    @endif


                                                </td>
                                                @if (Auth::user()->idtype == 1)
                                                    <td>
                                                        @if ($gasto->activo == 1)
                                                            <button type="button" class="btn btn-sm btn-danger"
                                                                data-bs-toggle="modal" data-bs-target="#modalEliminar"
                                                                data-id="{{ $gasto->idsincomprobante }}">
                                                                Eliminar
                                                            </button>
                                                        @else
                                                            <a href="{{ route('active', Crypt::encryptString($gasto->idsincomprobante)) }}"
                                                                class="btn btn-sm btn-info">
                                                                Activar
                                                            </a>
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                            @php
                                                if ($gasto->activo == '1') {
                                                    $totalGastado += $gasto->monto;
                                                }
                                            @endphp
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2" class="text-right">Total Comprobado:</th>
                                            <th>$ {{ number_format($totalGastado, 2) }}</th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formEliminar" method="POST" action="{{ route('gastos.destroy') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmar eliminación</h5>
                    </div>
                    <input type="hidden" name="idsincomprobante" id="gasto_id">
                    <div class="modal-content">
                    </div>
                    <div class="modal-body">
                        <p>¿Por qué deseas eliminar este gasto?</p>
                        <textarea name="observaciones" class="form-control" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Confirmar eliminación</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        $(function() {
            var currentCommissionId = $('#currentCommissionId').val() ||
                "{{ $idComision ?? ($query->idcomision ?? '') }}";

            if ($('#facturas-table').length) {
                $('#facturas-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{!! route('facturas_list_json') !!}',
                        type: 'GET',
                        data: function(d) {
                            d.idcomision = $('#currentCommissionId').val() ||
                                "{{ $idComision ?? ($query->idcomision ?? '') }}";
                        },
                        error: function(xhr, error, thrown) {
                            console.error('Error al cargar la tabla de facturas:', xhr.responseText);
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
                            data: 'datefactura',
                            name: 'datefactura'
                        },
                        {
                            data: 'descripcion',
                            name: 'descripcion'
                        },
                        {
                            data: 'nombre_completo',
                            name: 'nombre_completo'
                        },
                        {
                            data: 'typefactura',
                            name: 'typefactura'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'cancelacion',
                            name: 'cancelacion',
                            render: function(data, type, row) {
                                if (row.cancelacion === '1') {
                                    return '<strong style="color: red;">FACTURA ANULADA, LAS OBSERVACIONES SON LAS SIGUIENTES:</strong> ' +
                                        (row.observaciones ? row.observaciones :
                                            'Sin observaciones.');
                                }
                                if (row.cancelacion === '2') {
                                    return '<strong style="color: green;">FACTURA ACEPTADA</strong>';
                                }
                                if (row.cancelacion === '0') {
                                    return 'FACTURA PENDIENTE DE REVISIÓN';
                                }
                                if (row.cancelacion === '3') {
                                    return '<strong style="color: orange;">FACTURA CORRESPONDIENTE A OTRA COMISIÓN</strong>';
                                }
                                return data;
                            }
                        },
                        {
                            data: 'btn_f'
                        }
                    ],
                    language: {
                        url: "https://cdn.datatables.net/plug-ins/2.0.8/i18n/es-ES.json"
                    }
                });
            }
            var modalEliminar = document.getElementById('modalEliminar');
            if (modalEliminar) {
                modalEliminar.addEventListener('show.bs.modal', function(event) {
                    var button = event.relatedTarget;
                    var id = button.getAttribute('data-id');
                    var inputId = modalEliminar.querySelector('#gasto_id');
                    if (inputId) inputId.value = id;
                });
            }
            $('#btn-eliminar-comprobante').on('click', function(event) {
                Swal.fire({
                    title: "¿Confirma que desea eliminar el comprobante?",
                    text: "Tenga en cuenta que este paso no puede revertirse",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Eliminar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        var form = document.getElementById('delete-comprobante-form');
                        if (form) form.submit();
                        Swal.fire({
                            text: "El comprobante ha sido eliminado, el comisionado puede adjuntar uno nuevo.",
                            icon: "success"
                        });
                    }
                });
            });
            $('#btnCalcularReintegro').on('click', function(e) {
                e.preventDefault();

                const idComision = "{{ $idComision ?? ($query->idcomision ?? '') }}";
                if (!idComision) {
                    console.error('ID de Comisión no definido');
                    return;
                }

                Swal.fire({
                    title: '¿Calcular reintegro?',
                    text: "Se calculará el saldo a favor basándose en las facturas capturadas.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, calcular',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Procesando...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        $.ajax({
                            url: "{{ route('calcularReintegro', $idComision ?? ($query->idcomision ?? 0)) }}",
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    let rawMonto = parseFloat(response.monto);
                                    if (isNaN(rawMonto)) rawMonto = 0;
                                    let esSaldoAFavor = rawMonto < 0;
                                    let montoFormateado = Math.abs(rawMonto).toFixed(2);
                                    if (esSaldoAFavor) {
                                        $('#lbl_monto_reintegro').text(
                                            'Saldo a favor del comisionado');
                                    } else {
                                        $('#lbl_monto_reintegro').text(
                                            'Monto a reintegrar');
                                    }
                                    $('#monto_reintegro').val('$' + montoFormateado);
                                    $('#monto_original').val(rawMonto);

                                    let textoMensaje = esSaldoAFavor ?
                                        `El comisionado tiene un <b>saldo a favor</b> de: <b>$${montoFormateado}</b>` :
                                        `El monto de reintegro se ha actualizado a: <b>$${montoFormateado}</b>`;
                                    Swal.fire({
                                        title: '¡Proceso Completado!',
                                        html: textoMensaje,
                                        icon: esSaldoAFavor ? 'info' :
                                            'success',
                                        confirmButtonText: 'Entendido'
                                    }).then(() => {
                                        if (response.redirect_url) {
                                            window.location.href = response
                                                .redirect_url;
                                        }
                                    });
                                }
                            },
                            error: function(xhr) {
                                let errorMsg = xhr.responseJSON ? xhr.responseJSON
                                    .message : 'Error desconocido';
                                Swal.fire(
                                    'Error',
                                    'No se pudo procesar la solicitud: ' + errorMsg,
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
            $('#boton-datos-bancarios').on('click', function() {
                const concepto =
                    @if (isset($query) && $query->idmodalidad == 1)
                        `VA{{ $query->comision }}{{ $query->yearcomision }}{{ $query->acronymreintegro }}`;
                    @else
                        `VD{{ $query->comision ?? '' }}{{ $query->yearcomision ?? '' }}{{ $query->acronymreintegro ?? '' }}`;
                    @endif
                const textContent = `<p>Se pide el reintegro a la siguiente CLABE: <br>
                                <b>012180001219177495</b> <br> o número de cuenta: <br> <b>0121917749</b><br>
                                el concepto debe ser el siguiente: </p>
                                <b>${concepto}</b>`;
                Swal.fire({
                    title: "Información bancaria para el reintegro correspondiente.",
                    html: textContent,
                    icon: "info"
                });
            });
            $('#btn-historial-movimientos').on('click', function() {
                $('#modalMovimientos').modal('show');
                const idComision = "{{ $query->idcomision ?? ($idComision ?? '') }}";
                if (!idComision) return;
                $.ajax({
                    url: `/comisiones/${idComision}/movimientos`,
                    type: 'GET',
                    success: function(html) {
                        $('#contenido-modal-movimientos').html(html);
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        $('#contenido-modal-movimientos').html(`
                        <div class="alert alert-danger">
                            Hubo un error al cargar los movimientos. Por favor, intente más tarde.
                        </div>
                    `);
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectStatus = document.getElementById('idstatuscomision');
            if (!selectStatus) return;
            selectStatus.addEventListener('change', function() {
                const valorSeleccionado = parseInt(this.value);
                const tieneRev1 = this.dataset.rev1 !== '';
                const tieneRev2 = this.dataset.rev2 !== '';
                const valoresRevision = [13, 22, 241, 242];
                if (valoresRevision.includes(valorSeleccionado)) {
                    if (tieneRev1 && !tieneRev2) {
                        Swal.fire({
                            title: '¡Atención! Segunda Revisión',
                            text: 'Se agregará la segunda revisión. Este es el LÍMITE de revisiones por comisión y no se podrá retornar a alguna etapa anterior.',
                            icon: 'warning',
                            confirmButtonText: 'Entendido'
                        });
                    } else if (!tieneRev1) {
                        Swal.fire({
                            title: 'Primera Revisión',
                            text: 'La primera revisión será guardada al momento de enviar el formulario. Se sugiere añadir una nota del revisor.',
                            icon: 'info',
                            confirmButtonText: 'Entendido'
                        });
                    } else if (tieneRev1 && tieneRev2) {
                        Swal.fire({
                            title: 'Límite alcanzado',
                            text: 'Esta comisión ya cuenta con las 2 revisiones permitidas.',
                            icon: 'error',
                            confirmButtonText: 'Cerrar'
                        });
                    }
                }
            });
        });
    </script>
@endsection
