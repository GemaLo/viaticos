<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Viaticos')</title>

    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='32' height='32'%3E%3Crect width='100%25' height='100%25' rx='40' ry='40' fill='%23a57f2c'/%3E%3C/svg%3E" type="image/svg+xml">

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.min.css') }}">

    <script src="{{ asset('assets/js/tailwind.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('assets/css/apexcharts') }}">

    <script src="{{ asset('assets/js/chart.js') }}"></script>
    @vite(['resources/css/admin.css', 'resources/js/loader.js'])
    @vite(['resources/assets/vendor/scss/core.scss', 'resources/assets/vendor/scss/theme-default.scss', 'resources/assets/css/demo.css'])
    @vite(['resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.scss'])

    @stack('styles')
    @yield('vendor-style')

    <style>
        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 10%;
        }
    </style>
</head>

<body>
    @include('partials.loader')

    <div class="header-container">
        <div class="logo-container">
            <img src="{{ asset('images/SSPC.png') }}" alt="Logo SSPC" height="20%" width="30%">
        </div>

        <div class="text-container flex items-center space-x-4">
            <a href="{{ route('lineamientos') }}" class="btn btn-outline-primary flex items-center">
                <i class='bx bx-book-open me-1'></i> Consulta de Lineamientos
            </a>

            <div class="inline-block p-2 bg-[#611232] text-white rounded-lg">
                <span style="white-space: nowrap;">
                    {{ Auth::user()->nomusuario }} {{ Auth::user()->appusuario }} {{ Auth::user()->apmusuario }}
                </span><br>
                {{ Auth::user()->userType->typeuser }}
            </div>
        </div>
    </div>

    @include('partials.sidebar')

    <div class="container mt-5 ml-5">
        @yield('content')
    </div>

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/chart.min.js') }}"></script>

    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/js/dropzone.min.js') }}"></script>

    @stack('scripts')
    @yield('vendor-script')
    @yield('page-script')
</body>
</html>
