<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    public function index()
    {
        // Ilista dagiti kategoria (wenno search)
        $categories = Category::withCount('courses')
            ->when(request('search'), function($q) {
                $q->where('name', 'like', '%'.request('search').'%');
            })
            ->orderBy('name')
            ->paginate(12);
            
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:50',
            'status' => 'required|boolean',
            'target_type' => 'required|in:course,diploma,both'
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'icon' => $request->icon,
            'status' => $request->status,
            'user_id' => auth()->id(),
            'target_type' => $request->target_type
        ]);

        return redirect()->back()->with('success', 'Kategoria napartuat a sibaballigi.');
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:50',
            'status' => 'required|boolean',
            'target_type' => 'required|in:course,diploma,both'
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'icon' => $request->icon,
            'status' => $request->status,
            'target_type' => $request->target_type
        ]);

        return redirect()->back()->with('success', 'Kategoria na-update a sibaballigi.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        // Opsyonal: Kitaen no adda kurso nga agus-usar
        if($category->courses()->count() > 0) {
            return redirect()->back()->with('error', 'Saan a mabalin nga ikkaten, adda pay dagiti kurso nga naisilpo.');
        }

        $category->delete();
        return redirect()->back()->with('success', 'Kategoria naikkat.');
    }
}