<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Education;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EducationController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Education::select(['id', 'title', 'lieu', 'datedebut', 'datefin', 'created_at'])->latest();

            return DataTables::of($query)
                ->addColumn('title_fr', function ($education) {
                    return $education->title['fr'] ?? '-';
                })
                ->addColumn('lieu_fr', function ($education) {
                    return $education->lieu['fr'] ?? '-';
                })
                ->addColumn('actions', function ($education) {
                    return view('components.admin.ui.action-buttons', [
                        'editRoute' => route('admin.educations.edit', $education->id),
                        'deleteRoute' => route('admin.educations.destroy', $education->id),
                    ])->render();
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('admin.educations.index');
    }

    public function create()
    {
        return view('admin.educations.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|array',
            'title.fr' => 'required|string',
            'title.en' => 'nullable|string',
            'title.ar' => 'nullable|string',

            'lieu' => 'required|array',
            'lieu.fr' => 'required|string',
            'lieu.en' => 'nullable|string',
            'lieu.ar' => 'nullable|string',

            'datedebut' => 'required|date',
            'datefin' => 'nullable|date|after_or_equal:datedebut',
        ]);

        Education::create($data);

        return redirect()->route('admin.educations.index')->with('success', 'Éducation ajoutée avec succès.');
    }

    public function edit(Education $education)
    {
        return view('admin.educations.edit', compact('education'));
    }

    public function update(Request $request, Education $education)
    {
        $data = $request->validate([
            'title' => 'required|array',
            'title.fr' => 'required|string',
            'title.en' => 'nullable|string',
            'title.ar' => 'nullable|string',

            'lieu' => 'required|array',
            'lieu.fr' => 'required|string',
            'lieu.en' => 'nullable|string',
            'lieu.ar' => 'nullable|string',

            'datedebut' => 'required|date',
            'datefin' => 'nullable|date|after_or_equal:datedebut',
        ]);

        $education->update($data);

        return redirect()->back()->with('success', 'Éducation modifiée avec succès.');
    }

    public function destroy(Education $education)
    {
        $education->delete();
        return redirect()->route('admin.educations.index')->with('error', 'Éducation supprimée avec succès.');
    }
}
