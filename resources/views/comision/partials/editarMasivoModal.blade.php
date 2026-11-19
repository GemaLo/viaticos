<div class="modal fade" id="editarMasivoModal" tabindex="-1" aria-labelledby="editarMasivoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarMasivoModalLabel">Editar Comisiones Seleccionadas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if (Auth::user()->idtype == 1)
                    <p>Estás a punto de editar <span id="comision-count"></span></p>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="clcMasivo" class="form-label">Código CLC (Cuenta por Liquidar Certificada):</label>
                            <input type="text" class="form-control" id="clcMasivo" name="clcMasivo" placeholder="Ingresa CLC">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="deposito" class="form-label">Información de Depósito</label>
                            <input type="text" class="form-control" id="deposito" name="deposito">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="importComision" class="form-label">Importe Homologado Por Comisión</label>
                            <input type="number" class="form-control" id="importComision" name="importeComision">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="importComisionado" class="form-label">Importe Homologado Por Comisionado</label>
                            <input type="number" class="form-control" id="importComisionado" name="importeComisionado">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="documentacionFisica" class="form-label">Documentación Física:</label>
                        <select name="documentacionFisica" class="form-control" id="documentacionFisica">
                            <option value="">Selecciona una opción</option>
                            <option value="0">NO</option>
                            <option value="1">SI</option>
                        </select>
                    </div>
                @endif

                @if (Auth::user()->idtype == 2)
                    <p>Puede agregar una nota a las comisiones seleccionadas:</p>
                    <div class="mb-3">
                        <label for="notaMasiva" class="form-label">Nota:</label>
                        <input type="text" class="form-control" id="notaMasiva" name="notaMasiva" placeholder="Nota para la o las comisiones seleccionadas" maxlength="250">
                    </div>
                    <p>Puede agregar una UR a las comisiones seleccionadas:</p>
                    <div class="mb-3">
                        <label for="urMasiva" class="form-label">UR que comisiona:</label>
                        <input type="text" class="form-control" id="urMasiva" name="urMasiva" placeholder="UR que comisiona a las seleccionadas">
                    </div>
                @endif

                <div class="mb-3">
                    <label for="newStatusMasivo" class="form-label">Nuevo Estado de la Comisión:</label>
                    <select name="newStatusMasivo" class="form-control" id="newStatusMasivo">
                        <option value="" selected disabled>Selecciona un estado</option>
                    </select>
                </div>
                <div id="ampliacionForm" class="row g-3 mt-4" style="display: none;">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="fechaFinAmpliacion">Fecha nueva de fin de comisión</label>
                        <input type="date" id="fechaFinAmpliacion" name="dateReasignacionFinal" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="motivoAmpliacion">Motivo</label>
                        <input type="text" id="motivoAmpliacion" name="motivoAmpliacion" class="form-control" placeholder="Justificación de la ampliación">
                    </div>
                    <input type="hidden" id="fechaInicioCalculo" value="">
                    <div id="mensajeErrorAmpliacion" class="text-info"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-warning" id="confirmarEdicionMasiva">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>