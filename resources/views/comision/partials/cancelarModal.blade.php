<div class="modal fade" id="cancelarModal" tabindex="-1" aria-labelledby="cancelarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelarModalLabel">Cancelar Comisiones</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Estás a punto de cancelar <span id="count-cancelar"></span> comisiones. Por favor, ingresa una justificación.</p>
                <div class="mb-3">
                    <label for="justificacionCancelacion" class="form-label">Justificación:</label>
                    <textarea class="form-control" id="justificacionCancelacion" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-danger" id="confirmarCancelacion">Cancelar</button>
            </div>
        </div>
    </div>
</div>
