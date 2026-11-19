@php
    $finalDateOriginal = new DateTime($finaldate);

    $dueDate = clone $finalDateOriginal;
    $dueDate->modify('+7 days');

    $today = new DateTime();

    $diff = $today->diff($dueDate);
    $diffInDays = $diff->days;
    $sign = $diff->invert;

    $detalles = [15, 22, 23, 24, 25, 26];
    $cancelada = [3, 18];
@endphp

@if (in_array($idstatuscomision, $detalles))
    <span style="color: blue; font-weight: bold;">COMISIÓN FINALIZADA</span>

@elseif (in_array($idstatuscomision, $cancelada))
    <span style="color: orange; font-weight: bold;">COMISIÓN CANCELADA</span>

@else
    @if ($sign == 0)
        <span style="color: green; font-weight: bold;">Faltan {{ $diffInDays }} días para la comprobación.</span>
    @elseif ($sign == 1)
        <span style="color: red; font-weight: bold;">Comprobación atrasada por {{ $diffInDays }} días.</span>
    @endif
@endif
