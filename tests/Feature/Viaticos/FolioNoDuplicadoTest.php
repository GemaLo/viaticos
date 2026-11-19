<?php

namespace Tests\Feature\Viaticos;

use Tests\TestCase;
use Illuminate\Support\Facades\Cache;

class FolioNoDuplicadoTest extends TestCase
{
    public function test_multiples_requests_simultáneos_no_generan_folios_duplicados(): void
    {
        $totalProcesos = 10;
        $testYear = 9999; 
        $cacheKey = "test_folio_{$testYear}";
        $script = base_path('tests/scripts/crear_comision_concurrente.php');

        $this->assertFileExists($script, "El script no existe en: {$script}");

        // Reiniciar la variable simulada en caché antes del test para asegurar que empiece en 0
        Cache::put($cacheKey, 0);

        $procs = [];
        $pipes = [];

        for ($i = 0; $i < $totalProcesos; $i++) {
            // Pasamos el testYear para mantener el aislamiento
            $procs[$i] = proc_open(
                "php {$script} {$testYear}",
                [
                    0 => ['pipe', 'r'],
                    1 => ['pipe', 'w'],
                    2 => ['pipe', 'w'],
                ],
                $pipes[$i]
            );
        }

        $outputs = [];
        $folios = [];

        foreach ($procs as $i => $proc) {
            $stdout = trim(stream_get_contents($pipes[$i][1]));
            $stderr = trim(stream_get_contents($pipes[$i][2]));

            fclose($pipes[$i][0]);
            fclose($pipes[$i][1]);
            fclose($pipes[$i][2]);

            $exitCode = proc_close($proc); // espera al proceso

            $outputs[] = "Proceso {$i} [exit={$exitCode}] stdout='{$stdout}' stderr='{$stderr}'";

            if (is_numeric($stdout)) {
                $folios[] = (int) $stdout;
            }
        }

        // Filtramos para obtener folios únicos generados
        $foliosUnicos = array_unique($folios);

        // Para inspección de lo que devolvieron los subprocesos si se desea debugear usando: 
        // $this->fail("DEBUG COMPLETO:\n" . implode("\n", $outputs));

        $this->assertEquals(
            $totalProcesos, 
            count($folios), 
            "No todos los procesos retornaron un folio válido o falló un lock.\nDetalles:\n" . implode("\n", $outputs)
        );

        $this->assertEquals(
            $totalProcesos, 
            count($foliosUnicos), 
            "Fallo de concurrencia: Se detectaron folios duplicados a pesar del Lock.\nFolios obtenidos: " . implode(', ', $folios)
        );
    }
}