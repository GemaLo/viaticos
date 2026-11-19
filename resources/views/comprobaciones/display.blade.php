<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contenido del Archivo XML</title>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>
<body class="bg-gray-100 p-8">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6 text-center">Contenido del Archivo XML</h1>

        {{-- Mostrar el resultado de la validación de fecha --}}
        @if (isset($dateValidationMessage))
            @php
                $alertClass = '';
                if ($dateValidationStatus === 'success') {
                    $alertClass = 'bg-green-100 border-green-400 text-green-700';
                } elseif ($dateValidationStatus === 'warning') {
                    $alertClass = 'bg-yellow-100 border-yellow-400 text-yellow-700';
                } else { // error
                    $alertClass = 'bg-red-100 border-red-400 text-red-700';
                }
            @endphp
            <div class="{{ $alertClass }} px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline font-bold">{{ $dateValidationMessage }}</span>
            </div>
        @endif

        <pre class="bg-gray-800 text-green-400 p-4 rounded-md overflow-x-auto text-sm"><code>{{ json_encode($xmlArray, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) }}</code></pre>

        <div class="mt-6 text-center">
            <a href="{{ route('comprobacionForm') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Subir otro archivo
            </a>
        </div>
    </div>
</body>
</html>
