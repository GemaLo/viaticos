@php
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='32' height='32'%3E%3Crect width='100%' height='100%' rx='40' ry='40' fill='%23a57f2c'/%3E%3C/svg%3E" type="image/svg+xml">    
    <title>Login</title>
   @vite(['resources/css/app.css', 'resources/js/app.js','resources/js/seePassword.js' , 'resources/js/loader.js' ])
    @include('partials.loader')
</head>
<body class="bg-light">
    @include('partials.Lognavbar')
    <div class="container d-flex justify-content-center align-items-center mt-1  ">
        <div class="card shadow p-4" style="max-width: 600px; width: 100%;">
        <img src="{{ asset('/assets/img/SSPC.png') }}">
            <h1 class="text-center mb-4">Sistema de Viaticos</h1>
            <h2>Cambiar Contraseña</h2>
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <form method="POST" action="{{ route('changePassword') }}">
                    @csrf 
                    <div class="mb-3">
                        <label for="current_password" class="form-label" style="color: #000;">Contraseña Actual</label>
                        <div class="position-relative">
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                            <i class="fa fa-eye toggle-password position-absolute"
                               data-target="current_password"
                               style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label" style="color: #000;">Nueva Contraseña</label>
                        <div class="position-relative">
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                            <i class="fa fa-eye toggle-password position-absolute"
                               data-target="new_password"
                               style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label" style="color: #000;">Confirmar Nueva Contraseña</label>
                        <div class="position-relative">
                            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                            
                            <i class="fa fa-eye toggle-password position-absolute"
                               data-target="new_password_confirmation"
                               style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btns-gob">Cambiar Contraseña</button>
                </form>
        </div>
    </div>
    

</body>
</html> 

