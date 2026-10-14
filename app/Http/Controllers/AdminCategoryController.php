<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminCategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => Category::withCount('courses')->orderBy('name')->paginate(10),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $validated['slug'] = $this->uniqueSlug($validated['name']);
        Category::create($validated);

        return redirect()->route('admin.categories')->with('status', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('categories')->ignore($category->id)],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $validated['slug'] = $this->uniqueSlug($validated['name'], $category);
        $category->update($validated);

        return redirect()->route('admin.categories')->with('status', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('admin.categories')->with('status', 'Kategori dihapus. Kelas di dalamnya tetap ada tanpa kategori.');
    }

    private function uniqueSlug(string $name, ?Category $category = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $suffix = 2;

        while (Category::where('slug', $slug)->when($category, fn ($query) => $query->whereKeyNot($category->id))->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
}
