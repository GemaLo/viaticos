<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use App\Models\Comision;
use Illuminate\Support\Facades\Log;

class CommissionReportExport implements FromQuery, WithMapping, WithHeadings, WithChunkReading
{
    protected array $filters;
    protected $idtype;

    public function __construct(array $filters = [], $idtype = null)
    {
        $this->filters = $filters;
        $this->idtype = $idtype;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function query()
    {
        $inicio = microtime(true);
        $query = Comision::query();

        $query->where(function ($q) {
            $q->where('clasificado', '!=', 1)->orWhereNull('clasificado');
        });

        $query->when(isset($this->filters['idtypecomision']), function ($q) {
            return $q->where('idtypecomision', $this->filters['idtypecomision']);
        });

        $query->when(isset($this->filters['idstatuscomision']), function ($q) {
            return $q->where('idstatuscomision', $this->filters['idstatuscomision']);
        });

        $query->when(isset($this->filters['initialdate']), function ($q) {
            return $q->whereDate('initialdate', '>=', $this->filters['initialdate']);
        });

        $query->when(isset($this->filters['finaldate']), function ($q) {
            return $q->whereDate('finaldate', '<=', $this->filters['finaldate']);
        });

        $query->when(isset($this->filters['idmodalidad']), function ($q) {
            return $q->where('idmodalidad', $this->filters['idmodalidad']);
        });

        $query->when(!empty($this->filters['direcciones']), function ($q) {
            return $q->whereIn('oficio', $this->filters['direcciones']);
        });

        $query->when(isset($this->filters['validador_numempleado']), function ($q) {
            return $q->where('validador', $this->filters['validador_numempleado']);
        });

        $query->when(isset($this->filters['folio_inicio']), function ($q) {
            return $q->where('comision', '>=', $this->filters['folio_inicio']);
        });

        $query->when(isset($this->filters['folio_fin']), function ($q) {
            return $q->where('comision', '<=', $this->filters['folio_fin']);
        });

        $query->withSum(['facturas as facturas_importe_sum' => function ($q) {
            $q->where('cancelacion', 2);
        }], 'importe');

        $query->withSum(['facturas as total_isr' => function ($q) {
            $q->where('cancelacion', 2);
        }], 'isr');

        $query->withSum(['sinComprobantes as sin_comprobantes_sum' => function ($q) {
            $q->where('activo', 1);
        }], 'monto');
        Log::info('Tiempo armado query: ' . round(microtime(true) - $inicio, 2));

        return $query->with([
            'personComision.user',
            'validadorUser', 
            'modalidad',
            'tipo',
            'objetives',
            'statusComision',
            'reintegros'
        ]);
    }

    public function map($comision): array
    {
        $person = $comision->personComision;
        $user = $person?->user;

        $validadorUser = $comision->validadorUser;
        $nombreCompletoValidador = $validadorUser
            ? trim(($validadorUser->appusuario ?? '') . ' ' . ($validadorUser->apmusuario ?? '') . ' ' . ($validadorUser->nomusuario ?? ''))
            : $comision->validador;

        $ejercidoTotal = ($comision->facturas_importe_sum ?? 0) + ($comision->sin_comprobantes_sum ?? 0);
        $totalISR = $comision->total_isr ?? 0;

        $reintegroTotal = ($comision->idmodalidad == 1)
            ? ($comision->reintegros->monto ?? 0)
            : 0;

        if ($this->idtype == 2) {
            return [
                $comision->comision,
                $user?->unidad ?? 'N/A',
                $comision->modalidad?->modalidad ?? 'N/A',
                $comision->tipo?->typecomision ?? 'N/A',
                $comision->created_at ? $comision->created_at->format('d/m/Y') : '',
                $user?->numempleado ?? 'N/A',
                $person?->cargo ?? '',
                $user?->nomusuario ?? '',
                $user?->appusuario ?? '',
                $user?->apmusuario ?? '',
                $comision->objetives?->objetive ?? '',
                $comision->destino ?? '',
                $comision->initialdate,
                $comision->finaldate,
                $comision->importe,
                $comision->statusComision?->statuscomision ?? '',
            ];
        }

        return [
            $comision->comision,
            $comision->oficio,
            $nombreCompletoValidador,
            $comision->modalidad?->modalidad ?? 'N/A',
            $comision->tipo?->typecomision ?? 'N/A',
            $comision->created_at ? $comision->created_at->format('d/m/Y') : '',
            $user?->unidad ?? '',
            $user?->numempleado ?? 'N/A',
            $person?->curp ?? '',
            $person?->nivel ?? '',
            $person?->cargo ?? '',
            $person?->adscripcion ?? '',
            $user?->nomusuario ?? '',
            $user?->appusuario ?? '',
            $user?->apmusuario ?? '',
            $person?->banco ?? '',
            $person?->cuenta ?? '',
            $comision->objetives?->objetive ?? '',
            $comision->destino ?? '',
            $comision->initialdate,
            $comision->finaldate,
            $comision->dayscompletes,
            $comision->importe,
            $ejercidoTotal,
            $reintegroTotal,
            $comision->clc ?? '',
            $comision->deposito ?? '',
            $comision->statusComision?->statuscomision ?? '',
            $comision->partidaviatico ?? '',
            $comision->partidapasaje ?? '',
            $comision->justificacion,
            ($comision->documentacionfisica == 0) ? 'NO' : 'SI',
            $totalISR,
            $comision->folio
        ];
    }

    public function headings(): array
    {
        if ($this->idtype == 2) {
            return [
                'FOLIO',
                'UNIDAD RESPONSABLE',
                'MODALIDAD',
                'TIPO DE VIÁTICO',
                'FECHA SOLICITUD',
                'No. EMPLEADO',
                'DENOMINACIÓN PUESTO',
                'NOMBRE(S)',
                'PRIMER APELLIDO',
                'SEGUNDO APELLIDO',
                'OBJETIVO',
                'ESTADO DESTINO',
                'FECHA INICIO',
                'FECHA TÉRMINO',
                'IMPORTE AUTORIZADO',
                'STATUS COMISIÓN'
            ];
        }

        return [
            'FOLIO',
            'UR QUE COMISIONA',
            'VALIDADOR',
            'MODALIDAD',
            'TIPO DE VIÁTICO',
            'FECHA SOLICITUD',
            'UR',
            'No. EMPLEADO',
            'CURP',
            'CLAVE O NIVEL',
            'DENOMINACIÓN PUESTO',
            'ÁREA DE ADSCRIPCIÓN',
            'NOMBRE(S)',
            'PRIMER APELLIDO',
            'SEGUNDO APELLIDO',
            'BANCO',
            'CUENTA BANCARIA',
            'OBJETIVO',
            'ESTADO DESTINO',
            'FECHA INICIO',
            'FECHA TÉRMINO',
            'DÍAS VIATICADOS',
            'IMPORTE AUTORIZADO',
            'EJERCIDO',
            'REINTEGRO',
            'CLC',
            'DEPOSITO',
            'STATUS COMISIÓN',
            'PARTIDA DE VIATICO',
            'PARTIDA DE PASAJE',
            'OBSERVACIONES',
            'DOCUMENTACIÓN FÍSICA',
            'ISR',
            'FOLIO SEGUIMIENTO'
        ];
    }
}
