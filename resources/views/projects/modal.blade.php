<div class="modal fade" id="projectModal" tabindex="-1" role="dialog" aria-labelledby="projectModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="project-form">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="projectModalLabel">Nuevo Proyecto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="project-name">Nombre del Proyecto</label>
                        <input type="text" class="form-control" id="project-name" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>
