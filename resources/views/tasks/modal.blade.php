<div class="modal fade" id="taskModal" tabindex="-1" role="dialog" aria-labelledby="taskModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="task-form">
            @csrf
            <input type="hidden" id="task-project-id" name="project_id">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="taskModalLabel">Nueva Tarea</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="task-start">Inicio</label>
                        <input type="datetime-local" class="form-control" id="task-start" name="start" required>
                    </div>

                    <div class="form-group">
                        <label for="task-end">Fin</label>
                        <input type="datetime-local" class="form-control" id="task-end" name="end" required>
                    </div>

                    <div class="form-group">
                        <label for="task-description">Descripción</label>
                        <textarea class="form-control" id="task-description" name="description" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="task-user">Asignar a usuario</label>
                        <select id="task-user" name="user_id" class="form-control" required>
                            {{-- Opciones generadas vía JS --}}
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Tarea</button>
                </div>
            </div>
        </form>
    </div>
</div>
