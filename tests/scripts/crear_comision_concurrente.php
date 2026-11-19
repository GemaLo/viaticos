<?php

require __DIR__.'/../../vendor/autoload.php';

$app = require_once __DIR__.'/../../bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Cache;

$testYear = $argv[1] ?? 9999;
$cacheKey = "test_folio_{$testYear}";

try {
    // Usamos el mismo nombre de Lock que el ViaticoController para probar su eficacia.
    $folioGenerado = Cache::lock('lock:folio_generation', 10)->block(5, function () use ($cacheKey) {
        
        // En lugar de golpear a Oracle, golpeamos a la caché compartida (que simula MAX('folio')).
        // Esto previene que se contamine la BD con inserts de prueba.
        $ultimoFolio = Cache::get($cacheKey, 0);
        $nextFolio = $ultimoFolio + 1;
        
        // Simulamos la latencia que tomaría hacer el insert y consultas en Oracle (50ms).
        // Si el bloqueo falla, otros procesos entrarán aquí simultáneamente y duplicarán folios.
        usleep(50000); 

        Cache::put($cacheKey, $nextFolio);

        return $nextFolio;
    });

    echo $folioGenerado;
} catch (\Exception $e) {
    // Si no obtiene el lock dentro del tiempo de bloqueo, lanzaría error.
    echo "ERROR";
}