<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\SkillRequest;

class SkillController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Skill::select(['id', 'title', 'percent', 'created_at'])->latest();

            return DataTables::of($query)
                ->addColumn('title_fr', fn($skill) => $skill->title['fr'] ?? '-')
                ->addColumn('percent_fr', fn($skill) => $skill->percent['fr'] ?? '-')
                ->addColumn('actions', function ($skill) {
                    return view('components.admin.ui.action-buttons', [
                        'editRoute' => route('admin.skills.edit', $skill->id),
                        'deleteRoute' => route('admin.skills.destroy', $skill->id),
                    ])->render();
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('admin.skills.index');
    }

    public function create()
    {
        return view('admin.skills.create');
    }

    public function store(SkillRequest $request)
    {
        $data = $request->validated();

        // Handle icon update
        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('Skills', 'public');
            $data['icon'] = $iconPath;
        }
        Skill::create($data);

        return redirect()->route('admin.skills.index')->with('success', 'Compétence ajoutée avec succès.');
    }

    public function edit(Skill $skill)
    {
        return view('admin.skills.edit', compact('skill'));
    }

    public function update(SkillRequest $request, Skill $skill)
    {
        $data = $request->validated();

        // Handle image update
        if ($request->hasFile('icon')) {
            if ($skill->icon) {
                Storage::disk('public')->delete($skill->icon);
            }

            $iconPath = $request->file('icon')->store('Skills', 'public');
            $data['icon'] = $iconPath;
        }
        $skill->update($data);

        return redirect()->back()->with('success', 'Compétence modifiée avec succès.');
    }

    public function destroy(Skill $skill)
    {
        if ($skill->image) {
            Storage::disk('public')->delete($skill->image);
        }
        $skill->delete();
        return redirect()->route('admin.skills.index')->with('error', 'Project supprimé avec succès.');
    }

    public function removeImage(Skill $skill)
    {
        if ($skill->image) {
            Storage::disk('public')->delete($skill->image);
            $skill->image = null;
            $skill->save();

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
