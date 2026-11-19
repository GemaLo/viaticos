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
    <div id="mainContent" class="main flex-grow p-12 ml-40 transition-all duration-300">
        <div class="card">
            <div class="card-body">
                <div class="row">

                    @if(session('success'))
                        <div class="alert alert-success p-4 mb-4 rounded-lg bg-green-100 text-green-700">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-error p-4 mb-4 rounded-lg bg-red-100 text-red-700">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-error p-4 mb-4 rounded-lg bg-red-100 text-red-700">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <h3 class="mb-4 display-4">Generación de Layout Bancario</h3>
                    <form action="{{ route('import_status') }}" method="POST" enctype="multipart/form-data" class="mb-4">
                        @csrf
                        <div class="input-group">
                            <input type="file" class="form-control" id="excel_file" name="excel_file"
                                accept=".xlsx, .xls" required>
                            <button class="btn btn-primary" type="submit">Cargar y Actualizar Estado</button>
                        </div>
                        @error('excel_file')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </form>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <h3 class="mb-4 display-4">Generación de Reporte de Movimientos(Bitácora)</h3>
                    <form action="{{ route('import_status') }}" method="POST" enctype="multipart/form-data" class="mb-4">
                        @csrf
                        <div class="input-group">
                            <input type="file" class="form-control" id="excel_file" name="excel_file"
                                accept=".xlsx, .xls" required>
                            <button class="btn btn-primary" type="submit">Cargar y Actualizar Estado</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">                    
                    <h3 class="mb-4 display-4"></h3>
                    <form action="{{ route('import_status') }}" method="POST" enctype="multipart/form-data" class="mb-4">
                        @csrf
                        <div class="input-group">
                            <input type="file" class="form-control" id="excel_file" name="excel_file"
                                accept=".xlsx, .xls" required>
                            <button class="btn btn-primary" type="submit">Cargar y Actualizar Estado</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
