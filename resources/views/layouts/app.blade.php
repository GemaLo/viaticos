<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> --}}
    {{-- icono de la pestaña --}}
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='32' height='32'%3E%3Crect width='100%' height='100%' rx='40' ry='40' fill='%23a57f2c'/%3E%3C/svg%3E" type="image/svg+xml">
    <title>Recuperar Contraseña</title>
    @vite(['resources/js/app.js', 'resources/css/app.css' , 'resources/js/loader.js' ])

    @include('partials.loader')

</head>
<body class="bg-light">
    {{-- <nav class="navbar">
        <div class="navbar-logo"><img src="../public/images/logo_blanco.svg" alt=""></div>
    </nav> --}}
    @include('partials.Lognavbar');


    <div class="container d-flex justify-content-center align-items-center mt-5  ">
        <div class="card shadow p-4 mt-1" style="max-width: 400px; width: 100%;">
            <h3 class="text-center mb-4">Recuperar contraseña</h3>
            @yield('content')
            <div class="mt-3 text-center">
                <a href="{{ route('login')}} " class="text-decoration-none">Iniciar sesión</a>
            </div>
        </div>
    </div>



</body>
</html>
