<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Comision; 
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class CheckCommissionStatus extends Command
{
    protected $signature = 'commissions:check-status';

    protected $description = 'Checks for overdue commissions and updates their status.';

    public function handle()
    {
        $excluded_statuses = [7, 8, 10]; 

        $commissions = Comision::whereNotIn('idstatuscomision', $excluded_statuses)
                                ->get();
        
        $updatedCount = 0;

        foreach ($commissions as $commission) {
            $finalDate = Carbon::parse($commission->finaldate);
            $dueDate = $finalDate->addDays(5);

            if (Carbon::now()->greaterThan($dueDate)) {
            DB::connection('oracle_primary')->table('comisiones')
            ->update([
                'idstatuscomision' => 7
            ]);
                // $commission->idstatuscomision = 7;
                // $commission->save();
                $updatedCount++;
            }
        }

        // $this->info("{$updatedCount} comisiones han sido actualizadas a estado 'Atrasada'.");
        return 0;
    }
}