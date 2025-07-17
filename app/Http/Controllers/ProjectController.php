<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Controller responsible for handling operations related to projects,
 * including listing, storing, and fetching project data.
 */
class ProjectController extends Controller
{
    /**
     * Display a list of projects ordered by creation date.
     * If the request is via AJAX, returns a DataTable-ready JSON.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $projects = Project::with('user')
                ->orderByDesc('created_at')
                ->get()
                ->map(function ($project) {
                    return [
                        'name' => $project->name,
                        'creator' => $project->user->name ?? 'N/D',
                        'created_at' => $project->created_at->format('Y-m-d H:i'),
                    ];
                });

            return datatables()->of($projects)->make(true);
        }

        return view('projects.index');
    }

    /**
     * Store a new project in the database and assign it to the authenticated user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        Project::create([
            'name' => $request->name,
            'user_id' => auth()->id(),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Return a list of all projects, ordered by last update date.
     * Includes basic information and the name of the creator.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        $projects = Project::with('user')
            ->orderByDesc('updated_at')
            ->get(['id', 'name', 'user_id']);

        $projects = $projects->map(function ($project) {
            return [
                'id' => $project->id,
                'name' => $project->name,
                'creator' => $project->user->name ?? 'Desconocido',
            ];
        });

        return response()->json($projects);
    }
}
