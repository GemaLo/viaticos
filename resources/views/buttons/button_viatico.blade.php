@php
    $id_user = $comision->id_user;
    $idcomision = $comision->idcomision;
    $idstatuscomision = $comision->idstatuscomision;
    $idmodalidad = $comision->idmodalidad;
    $nombre_completo = $comision->nombre_completo;
    $initialdate = $comision->initialdate;
    $finaldate = $comision->finaldate;
    $importe = $comision->importe;
    $facturas_canceladas_count = $comision->facturas_canceladas_count;

    $userType = Auth::user()->idtype;
    $userId = Auth::user()->id_user;

    $permisions = [1, 21, 30];

    $statusGrupos = [
        'initialPDF' => [1, 2, 3, 4, 5, 6, 7, 16],
        'secondPDF' => [1, 2, 3, 4, 5, 6, 7, 16],
        'uploadFacturas' => [8, 9, 10, 11, 17, 19, 20],
        'detalles' => [1,2,3,4,5,6,7,8,9, 10, 11, 12, 13, 14, 15, 21, 22, 23, 24, 25, 26, 27, 28, 241, 242],
    ];

    $botones = [
        'Solicitud' => [
            'status' => $statusGrupos['initialPDF'],
            'route' => 'solicitudPDF',
            'text' => 'Solicitud de comisión',
        ],
        'Oficio/Editar' => [
            'status' => $statusGrupos['secondPDF'],
            'route' => 'acusePDF',
            'text' => 'Oficio de comisión',
        ],
        'Carga' => [
            'status' => $statusGrupos['uploadFacturas'],
            'route' => 'comprobacionForm',
            'text' => 'Carga de Facturas',
            'condition' => $userId == $id_user,
        ],
        'Detalles' => [
            'status' => $statusGrupos['detalles'],
            'route' => 'detallesComision',
            'text' => 'Detalles de comisión',
        ],
        // 'Edit' => [
        //     'status' => $statusGrupos['detalles'],
        //     'route' => 'editComision',
        //     'text' => 'Editar comisión',
        //     'condition' => in_array($userId, $permisions),
        // ],
    ];
    $finalizada = [15, 22, 23, 24];
@endphp
@if (isset($comision->facturas_canceladas_count) && $comision->facturas_canceladas_count > 0)
    <div class="alert alert-warning" role="alert">
        Esta comisión tiene {{ $comision->facturas_canceladas_count }} factura(s) rechazada(s) por parte de la DGPPR.
    </div>
@endif

@if (isset($comision->facturas_canceladas_sat) && $comision->facturas_canceladas_sat > 0)
    <div class="alert alert-danger" role="alert">
         Esta comisión contiene {{ $comision->facturas_canceladas_sat }} factura(s) con estatus 'Cancelado' en el SAT.
    </div>
@endif
@if ($idmodalidad == 1 || $idmodalidad == 2)
    @foreach ($botones as $nombreBoton => $config)
        @php
            $isDetalleForUser1 = $nombreBoton === 'Detalles' && $userType == 1;
            $statusMatches = in_array($idstatuscomision, $config['status']);
            $conditionMet = !isset($config['condition']) || $config['condition'];
        @endphp

        @if ($statusMatches || $isDetalleForUser1)
            @if ($conditionMet)
                <a href="{{ route($config['route'], ['idComision' => Crypt::encryptString($idcomision)]) }}"
                    class="btn btn-sm btn-outline-primary">
                    {{ $config['text'] }}
                </a>
            @endif
        @endif
    @endforeach
@endif
