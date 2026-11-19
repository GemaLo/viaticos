<?php

namespace App\Observers;

use App\Models\Comision;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class ComisionObserver
{
    // protected $flujos = [
    //     1 => [1, 2, 4, 5, 7, 8, 9, 10, 12, 13, 25, 15],
    //     2 => [16, 17, 19, 20, 21, 22, 26, 23, 24]
    // ];

    // public function updating(Comision $comision): void
    // {
    //     if ($comision->isDirty('idstatuscomision')) {
    //         $oldStatus = (int) $comision->getOriginal('idstatuscomision');
    //         $newStatus = (int) $comision->idstatuscomision;
    //         $modalidad = (int) $comision->idmodalidad;

    //         if ($modalidad == 1 && $oldStatus == 4 && $newStatus == 2) {
    //             $this->registrarMovimiento($comision, "RETORNO DE REVISIÓN A AUTORIZADA", $newStatus);
    //             return;
    //         }

    //         if ($modalidad == 1 && $oldStatus == 11 && $newStatus == 9) {
    //             $this->registrarMovimiento($comision, "RETORNO DE AMPLIACIÓN A EN CURSO", $newStatus);
    //             return;
    //         }

    //         if ($modalidad == 1 && $oldStatus == 26 && $newStatus == 9) {
    //             $this->registrarMovimiento($comision, "RETORNO EXCEPCIONAL DE PASO 26 A EN CURSO", $newStatus);
    //             return;
    //         }

    //         $flujoActual = $this->flujos[$modalidad] ?? [];
    //         $posActual = array_search($oldStatus, $flujoActual);
    //         $posNueva = array_search($newStatus, $flujoActual);

    //         if ($posNueva === ($posActual + 1)) {
    //             $this->registrarMovimiento($comision, "AVANCE DE FLUJO ESTÁNDAR", $newStatus);
    //             return;
    //         }

    //         if (!in_array($newStatus, [3, 6, 14, 18, 11])) {
    //             throw new Exception("Movimiento de estado no permitido, debe serguir el flujo correcto.");
    //         }
    //     }
    // }

    // private function registrarMovimiento(Comision $comision, string $descripcion, int $statusId)
    // {
    //     $folio = "{$comision->comision}/{$comision->yearcomision}";
    //     $userName = Auth::user() ? Auth::user()->username : 'Sistema';

    //     DB::table('registros')->insert([
    //         'movimiento' => "EL USUARIO $userName $descripcion PARA LA COMISIÓN: $folio",
    //         'id_user' => Auth::id() ?? 0,
    //         'fecha_movimiento' => now(),
    //         'created_at' => now(),
    //         'idcomision' => json_encode([$comision->idcomision]),
    //         'idstatus' => $statusId
    //     ]);
    // }
}
