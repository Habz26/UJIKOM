<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(Request $request): View
    {
        $query = Category::query();
        
        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%");
        }
        
        $search = $request->get('search');
        $categories = $query->latest()->paginate(10);
        
        return view('profile.partials.category-management', compact('categories', 'search'));
    }

    /**
     * Display the specified category for AJAX request.
     */
    public function show(Category $category)
    {
        return response()->json([
            'id' => $category->id,
            'name' => $category->name,
            'description' => $category->description,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string|max:500',
        ]);

        Category::create($request->all());

        return redirect()->route('profile.edit', ['tab' => 'categories'])->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('categories')->ignore($category)],
            'description' => 'nullable|string|max:500',
        ]);

        $category->update($request->all());

        return redirect()->route('profile.edit', ['tab' => 'categories'])->with('success', 'Kategori berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        if ($category->books()->count() > 0) {
            return redirect()->route('profile.edit', ['tab' => 'categories'])->with('error', 'Kategori tidak bisa dihapus karena masih digunakan di buku!');
        }

        $category->delete();

        return redirect()->route('profile.edit', ['tab' => 'categories'])->with('success', 'Kategori berhasil dihapus!');
    }
}

