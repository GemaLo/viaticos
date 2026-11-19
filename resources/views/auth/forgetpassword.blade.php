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
            <!-- Formulario de login -->
            <form method="POST" action="{{ route('RecuperarAcceso') }}">
                @csrf
                <div style="text-align: justify;">
                     <p>Para recuperar tu contraseña escribe la dirección de correo electrónico y tu número de empleado para validar que seas el titular de la cuenta. Se te hará llegar un correo electrónico para que puedas recuperar tu contraseña.</p>
                </div>
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('general'))
                    <div class="alert alert-danger">
                        {{ session('general') }}
                    </div>
                @endif
                <!-- Campo de correo -->
                <div class="mb-3">
                    <label for="email" class="form-label">Correo electrónico:</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="Ingresa tu correo">
                </div>

                <div class="mb-3">
                    <label for="num_empleado" class="form-label">Número empleado:</label>
                    <input type="number" id="num_empleado" name="num_empleado" class="form-control" value="" placeholder="Ingresa tu número de empleado">
                </div>

                <div class="mb-3">
                    <label for="curp" class="form-label">CURP:</label>
                    <input type="text" id="curp" name="curp" class="form-control" value=""  placeholder="Ingresa tu CURP">
                </div>

                <!-- Botón de login -->
                <div class="d-grid">
                    <button type="submit" class="btn btns-gob btn-load">Recuperar Contraseña</button>
                </div>
            </form>

            <!-- Enlace adicional -->
            <div class="mt-3 text-center">
                <a href="{{ route('login')}} " class="text-decoration-none">Iniciar sesión</a>
            </div>
        </div>
    </div>




</body>
</html>

@if (session('success'))
    <script>

        // Redirige después de unos segundos
        setTimeout(function () {
            window.location.href = "{{ route('login') }}";
        }, 2000); // Espera 2 segundos
    </script>
@endif
