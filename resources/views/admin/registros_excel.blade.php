<table>
    <thead>
        <tr>
            <th>Nombre Completo</th>
            <th>Movimiento</th>
            <th>Detalles de Comisión</th>
            <th>Fecha Registro</th>
            <th>Estatus</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($registros as $reg)
            @php
                $ids = [];
                if (!empty($reg->idcomision)) {
                    $decoded = json_decode($reg->idcomision, true);
                    $ids = is_array($decoded)
                        ? $decoded
                        : explode(',', str_replace(['[', ']', '"'], '', $reg->idcomision));
                }
            @endphp
            <tr>
                <td>{{ $reg->nomusuario }} {{ $reg->appusuario }} {{ $reg->apmusuario }}</td>
                <td>{{ $reg->movimiento }}</td>
                <td>
                    @if (count($ids) > 0)
                        @foreach ($ids as $id)
                            @php
                                $detalles = DB::table('comisiones')->where('idcomision', trim($id))->first();
                            @endphp

                            <div style="margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 5px;">
                                <strong>ID COMISIÓN (BD):</strong> {{ $id }} <br>
                                <strong>FOLIO DE SEGUIMIENTO:</strong> {{ mb_strtoupper($detalles->folio ?? 'N/A') }}
                                <br>
                                <strong>FOLIO DE COMISIÓN APROBADA:</strong>
                                {{ mb_strtoupper($detalles->comision ?? 'N/A') }}
                            </div>
                            <br>
                        @endforeach
                    @else
                        <span class="text-muted">SIN COMISIÓN ASIGNADA</span>
                    @endif
                </td>
                <td>{{ $reg->fecha_movimiento }}</td>
                <td>{{ $reg->statuscomision }}</td>
            </tr>
@endforeach
    </tbody>
</table>
