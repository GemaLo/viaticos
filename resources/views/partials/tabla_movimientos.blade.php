@if($movimientos->isEmpty())
    <div class="text-center py-4 text-muted">
        <i class="fas fa-folder-open fa-2x mb-2 text-secondary"></i>
        <p class="mb-0">No se encontraron movimientos registrados para esta comisión.</p>
    </div>
@else
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle text-start" style="font-size: 13px;">
            <thead class="table-light">
                <tr>
                    <th style="width: 20%;">Nombre Completo</th>
                    <th style="width: 25%;">Movimiento</th>
                    <th style="width: 25%;">Detalles de Comisión</th>
                    <th style="width: 15%;">Fecha Registro</th>
                    <th style="width: 15%;">Estatus</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($movimientos as $reg)
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
                        <td><strong>{{ $reg->nombre_completo }}</strong></td>

                        <td>{{ $reg->movimiento }}</td>

                        <td>
                            @if (count($ids) > 0)
                                @foreach ($ids as $id)
                                    @php
                                        $detalles = DB::table('comisiones')->where('idcomision', trim($id))->first();
                                    @endphp
                                    <div class="mb-2 pb-2 border-bottom border-light" style="line-height: 1.4;">
                                        <span class="d-block text-muted" style="font-size: 11px;">
                                            <strong>ID BD:</strong> {{ $id }}
                                        </span>
                                        <span class="d-block">
                                            <strong>FOLIO SEGUIMIENTO:</strong> <span class="text-primary">{{ mb_strtoupper($detalles->folio ?? 'N/A') }}</span>
                                        </span>
                                        <span class="d-block">
                                            <strong>FOLIO APROBADA:</strong> <span class="text-success">{{ mb_strtoupper($detalles->comision ?? 'N/A') }}</span>
                                        </span>
                                    </div>
                                @endforeach
                            @else
                                <span class="text-muted italic" style="font-size: 11px;">SIN COMISIÓN ASIGNADA</span>
                            @endif
                        </td>

                        <!-- Fecha Registro -->
                        <td class="text-nowrap">
                            {{ $reg->fecha_movimiento ? date('d/m/Y H:i', strtotime($reg->fecha_movimiento)) : 'N/A' }}
                        </td>

                        <td>
                            <span class="badge bg-secondary text-white">
                                {{ $reg->estatus->statuscomision ?? 'N/A' }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
