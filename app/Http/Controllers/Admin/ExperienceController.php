<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ExperienceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Experience::select(['id', 'title', 'lieu', 'datedebut', 'datefin', 'created_at'])->latest();

            return DataTables::of($query)
                ->addColumn('title_fr', fn($exp) => $exp->title['fr'] ?? '-')
                ->addColumn('lieu_fr', fn($exp) => $exp->lieu['fr'] ?? '-')
                ->addColumn('actions', function ($exp) {
                    return view('components.admin.ui.action-buttons', [
                        'editRoute' => route('admin.experiences.edit', $exp->id),
                        'deleteRoute' => route('admin.experiences.destroy', $exp->id),
                    ])->render();
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('admin.experiences.index');
    }

    public function create()
    {
        return view('admin.experiences.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|array',
            'title.fr' => 'required|string',
            'title.en' => 'nullable|string',
            'title.ar' => 'nullable|string',

            'description' => 'nullable|array',
            'description.fr' => 'nullable|string',
            'description.en' => 'nullable|string',
            'description.ar' => 'nullable|string',

            'lieu' => 'required|array',
            'lieu.fr' => 'required|string',
            'lieu.en' => 'nullable|string',
            'lieu.ar' => 'nullable|string',

            'datedebut' => 'required|date',
            'datefin' => 'nullable|date',
        ]);

        Experience::create($data);

        return redirect()->route('admin.experiences.index')->with('success', 'Expérience ajoutée avec succès.');
    }

    public function edit(Experience $experience)
    {
        return view('admin.experiences.edit', compact('experience'));
    }

    public function update(Request $request, Experience $experience)
    {
        $data = $request->validate([
            'title' => 'required|array',
            'title.fr' => 'required|string',
            'title.en' => 'nullable|string',
            'title.ar' => 'nullable|string',

            'description' => 'nullable|array',
            'description.fr' => 'nullable|string',
            'description.en' => 'nullable|string',
            'description.ar' => 'nullable|string',

            'lieu' => 'required|array',
            'lieu.fr' => 'required|string',
            'lieu.en' => 'nullable|string',
            'lieu.ar' => 'nullable|string',

            'datedebut' => 'required|date',
            'datefin' => 'nullable|date',
        ]);

        $experience->update($data);

        return redirect()->back()->with('success', 'Expérience modifiée avec succès.');
    }

    public function destroy(Experience $experience)
    {
        $experience->delete();

        return redirect()->route('admin.experiences.index')->with('error', 'Expérience supprimée avec succès.');
    }
}
