<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use PDF;

/**
 * Controller responsible for handling task-related operations such as
 * calendar view, listing tasks (with filters), storing new tasks, and generating reports.
 */
class TaskController extends Controller
{
    /**
     * Show the main calendar view for task management.
     *
     * @return \Illuminate\View\View
     */    public function calendar()
    {
        return view('tasks.calendar');
    }

    /**
     * Retrieve and return a list of tasks, optionally filtered by user ID.
     * The result includes project and user info and is formatted for FullCalendar.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $tasks = Task::with('project', 'user')
            ->when($request->has('user_id'), function ($query) use ($request) {
                $query->where('user_id', $request->user_id);
            })
            ->get()
            ->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => ($task->project->name ?? 'Sin proyecto') . ' - ' . ($task->description ?? ''),
                    'start' => $task->start?->format('Y-m-d\TH:i:s'),
                    'end'   => $task->end?->format('Y-m-d\TH:i:s'),
                    'extendedProps' => [
                        'description' => $task->description,
                        'project_id' => $task->project_id,
                        'user_id' => $task->user_id,
                        'project_name' => $task->project->name ?? null,
                        'user_name' => $task->user->name ?? null,
                    ],
                ];
            });

        return response()->json($tasks);
    }

    /**
     * Return a raw list of tasks assigned to the given user.
     * Defaults to the currently authenticated user if not specified.
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function tasks(Request $request)
    {
        $userId = $request->query('user_id', auth()->id());

        return Task::where('user_id', $userId)->get();
    }

    /**
     * Store a new task in the database after validating the input.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'start'      => 'required|date',
            'end'        => 'required|date|after_or_equal:start',
            'project_id' => 'required|exists:projects,id',
            'user_id'    => 'required|exists:users,id',
        ]);

        Task::create($validated);

        return response()->json(['success' => true]);
    }

    /**
     * Generate a PDF report of tasks, grouped by project, and filtered by
     * date range, project ID, and user ID if provided in the request.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function report(Request $request)
    {
        $tasks = Task::with(['project', 'user'])
            ->when($request->filled('from'), fn($q) => $q->whereDate('start', '>=', $request->from))
            ->when($request->filled('to'), fn($q) => $q->whereDate('end', '<=', $request->to))
            ->when($request->filled('project_id'), fn($q) => $q->where('project_id', $request->project_id))
            ->when($request->filled('user_id'), fn($q) => $q->where('user_id', $request->user_id))
            ->get()
            ->groupBy('project.name');

        $pdf = PDF::loadView('tasks.report_pdf', compact('tasks', 'request'));
        return $pdf->download('informe_tareas.pdf');
    }
}
