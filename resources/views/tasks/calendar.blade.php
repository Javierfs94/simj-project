@extends('adminlte::page')

@section('title', 'Control de Tareas')

@section('content_header')
    <div class="d-flex align-items-center flex-wrap gap-2">
        <h1 class="mb-0 me-3">Control de Tareas</h1>

        <button class="btn btn-primary me-2" id="btn-add-project">
            <i class="fas fa-plus"></i>
        </button>

        <button class="btn btn-primary" id="btn-generate-report">
            <i class="fas fa-file-pdf"></i>
        </button>

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
            </div>
            <div id="external-projects" class="p-2 border bg-light">
            </div>
        </div>
        <div class="col-md-8">
            <div class="form-group">
                <label for="user-filter">Ver tareas de:</label>
                <select id="user-filter" class="form-control" style="width: 250px;">
                    {{-- Opciones cargadas dinámicamente --}}
                </select>
            </div>

            <div id="calendar"></div>
        </div>
    </div>

    @include('tasks.modal')
@endsection

@include('projects.modal')

<!-- Modal de detalle de tarea -->
<div class="modal fade" id="taskDetailModal" tabindex="-1" role="dialog" aria-labelledby="taskDetailModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskDetailModalLabel">Detalle de la Tarea</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Proyecto:</strong> <span id="detail-project"></span></p>
                <p><strong>Usuario:</strong> <span id="detail-user"></span></p>
                <p><strong>Inicio:</strong> <span id="detail-start"></span></p>
                <p><strong>Fin:</strong> <span id="detail-end"></span></p>
                <p><strong>Descripción:</strong> <span id="detail-description"></span></p>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Filtros para Informe -->
<div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="report-form" method="GET" action="{{ route('tasks.report') }}" target="_blank">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Generar Informe de Tareas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf

                    <div class="form-group">
                        <label for="report-from">Fecha desde</label>
                        <input type="date" class="form-control" id="report-from" name="from" required>
                    </div>

                    <div class="form-group">
                        <label for="report-to">Fecha hasta</label>
                        <input type="date" class="form-control" id="report-to" name="to" required>
                    </div>

                    <div class="form-group">
                        <label for="report-project">Proyecto</label>
                        <select id="report-project" name="project_id" class="form-control">
                            <option value="">Todos</option>
                            @foreach (\App\Models\Project::orderBy('name')->get() as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="report-user">Usuario</label>
                        <select id="report-user" name="user_id" class="form-control">
                            <option value="">Todos</option>
                            @foreach (\App\Models\User::orderBy('name')->get() as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Generar PDF</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>

@section('footer')
    @include('vendor.adminlte.partials.footer.footer')
@endsection

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.11/index.global.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.11/index.global.min.css" rel="stylesheet" />
    <style>
        #external-projects .fc-event {
            margin: 10px 0;
            cursor: move;
        }
    </style>
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales-all.min.js"></script>

    @vite('resources/js/calendar.js')
@endpush
