<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ExperienceRequest;
use App\Http\Requests\SkillRequest;

class ExperienceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Experience::select(['id', 'title', 'lieu', 'start_date', 'end_date', 'created_at'])->latest();

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

    public function store(ExperienceRequest $request)
    {
        $data = $request->validated();

        Experience::create($data);

        return redirect()->route('admin.experiences.index')->with('success', 'Expérience ajoutée avec succès.');
    }

    public function edit(Experience $experience)
    {
        return view('admin.experiences.edit', compact('experience'));
    }

    public function update(ExperienceRequest $request, Experience $experience)
    {
        $data = $request->validated();

        $experience->update($data);

        return redirect()->back()->with('success', 'Expérience modifiée avec succès.');
    }

    public function destroy(Experience $experience)
    {
        if ($experience->image) {
            Storage::disk('public')->delete($experience->image);
        }
        $experience->delete();
        return redirect()->route('admin.experiences.index')->with('error', 'Expérience supprimée avec succès.');
    }
    public function removeImage(Experience $experience)
    {
        if ($experience->image) {
            Storage::disk('public')->delete($experience->image);
            $experience->image = null;
            $experience->save();

            return response()->json([
                'success' => true,
                'message' => "L'image principale a été supprimée avec succès."
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => "Aucune image trouvée à supprimer."
        ], 404);
    }
}
