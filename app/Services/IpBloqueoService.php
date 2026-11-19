<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class IpBloqueoService
{
    protected $connection = 'oracle_primary';

    public function registrar(string $ip, string $motivo)
    {
        DB::connection($this->connection)->table('ips_bloqueadas')->updateOrInsert(
            ['ip' => $ip],
            ['motivo' => $motivo, 'updated_at' => now(), 'created_at' => now()]
        );
    }

    public function estaBloqueada(string $ip): bool
    {
        return DB::connection($this->connection)
                 ->table('ips_bloqueadas')
                 ->where('ip', $ip)
                 ->exists();
    }
}
