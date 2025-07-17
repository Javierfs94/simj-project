<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Informe de Tareas</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        h2 {
            border-bottom: 1px solid #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #aaa;
            padding: 6px;
            text-align: left;
        }
    </style>
</head>

<body>
    <h1>Informe de Tareas</h1>
    <p>
        <strong>Desde:</strong> {{ $request->from }}
        <strong>Hasta:</strong> {{ $request->to }}<br>

        <strong>Proyecto:</strong>
        @if ($request->project_id)
            {{ optional(\App\Models\Project::find($request->project_id))->name ?? 'Desconocido' }}
        @else
            *
        @endif
        &nbsp;&nbsp;
        <strong>Usuario:</strong>
        @if ($request->user_id)
            {{ optional(\App\Models\User::find($request->user_id))->name ?? 'Desconocido' }}
        @else
            *
        @endif
    </p>

    @foreach ($tasks as $projectName => $projectTasks)
        <h2>{{ $projectName ?? 'Sin proyecto' }}</h2>
        <table>
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Descripción</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Duración (minutos)</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach ($projectTasks as $task)
                    @php
                        $start = \Carbon\Carbon::parse($task->start);
                        $end = \Carbon\Carbon::parse($task->end);
                        $minutes = $start->diffInMinutes($end);
                        $total += $minutes;
                    @endphp
                    <tr>
                        <td>{{ $task->user->name ?? '—' }}</td>
                        <td>{{ $task->description }}</td>
                        <td>{{ $start }}</td>
                        <td>{{ $end }}</td>
                        <td>{{ $minutes }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="4"><strong>Total del proyecto</strong></td>
                    <td><strong>{{ $total }}</strong></td>
                </tr>
            </tbody>
        </table>
    @endforeach
</body>

</html>
