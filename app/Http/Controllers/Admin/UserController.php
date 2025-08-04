<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::select(['id', 'name', 'email', 'created_at'])->latest();

            return DataTables::of($query)
                ->addColumn('actions', function ($user) {
                    return view('components.admin.ui.action-buttons', [
                        'editRoute' => route('admin.users.edit', $user),
                        'deleteRoute' => route('admin.users.destroy', $user),
                    ])->render();
                })
                ->rawColumns(['actions']) // Allow HTML for the 'actions' column
                ->make(true);
        }

        // For initial non-AJAX view load
        return view('admin.users.index');
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(UserRequest $request)
    {
        $data = $request->validated();

        $data['password'] = bcrypt($data['password']);
        $data['is_admin'] = $request->has('is_admin');
        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé avec succès');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user)
    {
        $data = $request->validated();

        if ($request->filled('password')) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }
        $data['is_admin'] = $request->has('is_admin');
        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour avec succès');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('error', 'Utilisateur supprimé avec succès');
    }
}
