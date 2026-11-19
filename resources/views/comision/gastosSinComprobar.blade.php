@extends('layouts.master')
@section('title', 'Comprobaciones')

@section('vendor-style')
    @vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
@endsection

@section('vendor-script')
    @vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection

@section('page-script')
    @vite(['resources/js/app.js', 'resources/assets/js/dashboards-analytics.js'])
@endsection
<style>
    .table-container {
        overflow-x: auto;
    }
</style>
@section('content')
    <div id="mainContent" class="main flex-grow p-10 ml-40 transition-all duration-300">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('acusePDF', ['idComision' => Crypt::encryptString($query->idcomision)]) }}"
                            class="btn btn-sm btn-outline-primary" target="_blank">Acuse de comisión</a>

                        <a href="{{ route('solicitudPDF', ['idComision' => Crypt::encryptString($query->idcomision)]) }}"
                            class="btn btn-sm btn-outline-primary" target="_blank">Solicitud de
                            comisión</a>
                        <a href="{{ route('generarGastosSinComprobantePDF', ['idComision' => Crypt::encryptString($query->idcomision)]) }}"
                            class="btn btn-sm btn-outline-success" target="_blank">
                            Formato Gastos Sin Comprobante
                        </a>
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success col-md-12" role="alert">{{ $message }}
                            </div>
                        @endif
                        <div class="row">
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
                        </div>
                        <div class="row g-6">
                            <div class="col-md-6">
                                <label class="form-label" for="numNombreUr">Número y Nombre de la unidad
                                    responsable</label>
                                <input type="text" id="numNombreUr" class="form-control"
                                    value="{{ $query->adscripcion }}" name="adscripcion" readonly>
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
                                <label class="form-label" for="initialdate">Fecha de inicio </label>
                                <input type="date" id="initialdate" name="initialdate"
                                    value="{{ date('Y-m-d', strtotime($query->initialdate)) }}" class="form-control"
                                    readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="finaldate">Fecha final</label>
                                <input type="date" id="finaldate" name="finaldate"
                                    value="{{ date('Y-m-d', strtotime($query->finaldate)) }}" class="form-control" readonly>
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
                            @if (Auth::user()->idtype == 1)
                                <div class="col-md-2">
                                    <label class="form-label" for="clc">CLC</label>
                                    <input type="number" id="clc" class="form-control" value="{{ $query->clc }}"
                                        name="clc">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Documentación física:</label>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="radio" name="documentacionfisica"
                                            id="activo" value="1"
                                            {{ $query->documentacionfisica == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="activo">
                                            SI
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="documentacionfisica"
                                            id="inactivo" value="0"
                                            {{ $query->documentacionfisica == 0 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="inactivo">
                                            NO
                                        </label>
                                    </div>
                                </div>
                            @endif

                            <form action="{{ route('savePays') }}" method="POST" enctype="multipart/form-data"
                                id="savePays">
                                @csrf
                                <input type="text" name="idcomision" value="{{ $query->idcomision }}" hidden>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label" for="datePay">Fecha de compra</label>
                                        <input type="date" id="datepay" name="datepay" class="form-control"
                                            required value="{{ old('datepay') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="description">Descripción</label>
                                        <input type="text" id="conceptopago" name="conceptopago" value="PASAJE LOCAL EN EL LUGAR DE LA COMISIÓN" class="form-control"
                                            required value="{{ old('conceptopago') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label" for="amount">Monto</label>
                                        <input type="number" step="0.01" id="monto" name="monto"
                                            class="form-control" required value="{{ old('monto') }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label" for="proof_file">Comprobante (Archivo)</label>
                                        <input type="file" id="proof_file" name="proof_file" class="form-control">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">Guardar Comprobante</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <hr class="my-4">
                        <h2>Comprobantes Cargados</h2>
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
                                                        <a href="{{ Storage::disk('public')->url($gasto->proof_file) }}"
                                                            target="_blank" class="btn btn-sm btn-outline-info">Ver
                                                            Archivo</a>
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                            </tr>
                                            @php $totalGastado += $gasto->monto; @endphp
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-right">Total Comprobado:</th>
                                            <th>$ {{ number_format($totalGastado, 2) }}</th>
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
@endsection
