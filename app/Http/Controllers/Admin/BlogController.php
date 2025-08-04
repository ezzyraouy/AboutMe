<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\BlogRequest;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Blog::select(['id', 'slug', 'created_at', 'title'])->latest();

            return DataTables::of($query)
                ->addColumn('title_fr', function ($blog) {
                    return $blog->title['fr'] ?? '-';
                })
                ->addColumn('date', function ($blog) {
                    return $blog->created_at->format('Y-m-d');
                })
                ->addColumn('actions', function ($blog) {
                    return view('components.admin.ui.action-buttons', [
                        'editRoute' => route('admin.blogs.edit', $blog->id),
                        'deleteRoute' => route('admin.blogs.destroy', $blog->id),
                    ])->render();
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('admin.blogs.index');
    }




    public function create()
    {
        return view('admin.blogs.create');
    }

    public function store(BlogRequest $request)
    {
        $data = $request->validated();

        // Slug generation
        $count = 1;
        $slug = Str::slug($data['title']['fr'], '-', 'fr');
        while (Blog::where('slug', $slug)->exists()) {
            $slug = Str::slug($data['title']['fr'], '-', 'fr') . '-' . $count;
            $count++;
        }
        $data['slug'] = $slug;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('blogs', 'public');
            $data['image'] = $imagePath;
        }


        // Create blog
        $blog = Blog::create($data);

        // Handle file uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $index => $file) {
                $path = $file->store('uploads/resources', 'public');

                $blog->resources()->create([
                    'file_path' => $path,
                    'file_type' => $file->getClientOriginalExtension(),
                    'mime_type' => $file->getMimeType(),
                    'is_main' => $index === 0 // mark first file as main
                ]);
            }
        }

        return redirect()->route('admin.blogs.index')->with('success', 'Article ajouté avec succès.');
    }


    public function edit(Blog $blog)
    {
        return view('admin.blogs.edit', compact('blog'));
    }

    public function update(BlogRequest $request, Blog $blog)
    {
        $data = $request->validated();

        // Slug generation
        $count = 1;
        $slug = Str::slug($data['title']['fr'], '-', 'fr');
        while (Blog::where('slug', $slug)->where('id', '!=', $blog->id)->exists()) {
            $slug = Str::slug($data['title']['fr'], '-', 'fr') . '-' . $count;
            $count++;
        }
        $data['slug'] = $slug;

        // Handle image update
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($blog->image) {
                Storage::disk('public')->delete($blog->image);
            }

            $imagePath = $request->file('image')->store('blogs', 'public');
            $data['image'] = $imagePath;
        }

        // Update the blog
        $blog->update($data);

        // Handle new file uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $index => $file) {
                $path = $file->store('uploads/resources', 'public');

                $blog->resources()->create([
                    'file_path' => $path,
                    'file_type' => $file->getClientOriginalExtension(),
                    'mime_type' => $file->getMimeType(),
                    'is_main' => $blog->resources()->count() === 0 && $index === 0,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Article modifié avec succès.');
    }

    public function destroy(Blog $blog)
    {
        if ($blog->image) {
            Storage::disk('public')->delete($blog->image);
        }
        $blog->delete();
        return redirect()->route('admin.blogs.index')->with('error', 'Article supprimé avec succès.');
    }

    public function removeImage(Blog $blog)
    {
        if ($blog->image) {
            Storage::disk('public')->delete($blog->image);
            $blog->image = null;
            $blog->save();

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
