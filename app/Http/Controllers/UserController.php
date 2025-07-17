<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

/**
 * Controller responsible for managing user operations such as listing,
 * creation, updating, deletion, and AJAX support for DataTables.
 */
class UserController extends Controller
{
    /**
     * Display a listing of users.
     * If the request is AJAX, returns a JSON formatted response for DataTables.
     * Otherwise, renders the main users view.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::select(['id', 'name', 'email', 'is_admin']);

            return DataTables::of($users)
                ->editColumn('is_admin', function ($user) {
                    return $user->is_admin ? 'Sí' : 'No'; // ← Aquí se transforma
                })
                ->addColumn('actions', function ($user) {
                    return view('users.partials.actions', compact('user'))->render();
                })
                ->rawColumns(['actions']) // ← Por si `actions` contiene HTML
                ->make(true);
        }

        return view('users.index');
    }


    /**
     * Store a newly created user in the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'is_admin' => 'nullable|boolean',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => $request->boolean('is_admin'),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Return a specific user as JSON for editing.
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Update the specified user in the database.
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'is_admin' => 'nullable|boolean',
        ]);

        if ($data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['is_admin'] = $request->boolean('is_admin');

        $user->update($data);

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified user from the database.
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Return a simplified list of users (id and name only), ordered by name.
     * Used typically for populating dropdowns.
     *
     * @return \Illuminate\Support\Collection
     */
    public function list()
    {
        return User::select('id', 'name')->orderBy('name')->get();
    }
}
