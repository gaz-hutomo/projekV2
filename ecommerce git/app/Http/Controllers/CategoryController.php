<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->paginate(10);

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
        ]);

        $data['slug'] = Str::slug($data['name']);

        // Pastikan slug unik juga
        if (Category::where('slug', $data['slug'])->exists()) {
            $data['slug'] .= '-' . time();
        }

        Category::create($data);

        return redirect()
            ->route('dashboard.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
        ]);

        $data['slug'] = Str::slug($data['name']);

        if (Category::where('slug', $data['slug'])
            ->where('id', '!=', $category->id)
            ->exists()) {
            $data['slug'] .= '-' . time();
        }

        $category->update($data);

        return redirect()
            ->route('dashboard.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()
            ->route('dashboard.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
