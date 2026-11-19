@php
    header('Cache-Control: no-cache, must-revalidate');
    header('Pragma: no-cache');
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon"
        href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='32' height='32'%3E%3Crect width='100%' height='100%' rx='40' ry='40' fill='%23a57f2c'/%3E%3C/svg%3E"
        type="image/svg+xml">
    <title>Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/seePassword.js', 'resources/js/loader.js'])
    @include('partials.loader')
</head>

<body class="bg-light">
    @include('partials.Lognavbar')
    <div class="container d-flex justify-content-center align-items-center mt-1  ">
        <div class="card shadow p-4" style="max-width: 600px; width: 100%;">
            <img src="{{ asset('/assets/img/SSPC.png') }}">
            <h1 class="text-center mb-4">Sistema de Viaticos</h1>
            <h3 class="text-center mb-4">Iniciar sesión</h3>
            <form method="POST" action="{{ route('login') }}">
                @csrf

                @error('email')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <div class="mb-3">
                    <label for="email" class="form-label text-dark">Correo electrónico:</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}"
                        required placeholder="Ingresa tu correo" autocomplete="username">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label text-dark">Contraseña:</label>
                    <div class="input-password-wrapper" style="position: relative;">
                        <input type="password" id="password" name="password" class="form-control" required
                            placeholder="Ingresa tu contraseña" autocomplete="current-password">
                        <i class="fa fa-eye toggle-password" data-target="password"
                            style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer;"></i>
                    </div>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btns-gob">Iniciar Sesión</button>
                </div>
            </form>
        </div>
    </div>


</body>

</html>
