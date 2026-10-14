<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminCourseController extends Controller
{
    public function index(): View
    {
        return view('admin.courses.index', [
            'courses' => Course::with('category')->withCount(['lessons', 'enrollments'])->latest()->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.courses.form', [
            'course' => new Course(['level' => 'Pemula']),
            'categories' => Category::orderBy('name')->get(),
            'lessons' => collect(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateCourse($request);
        if ($validated['status'] === 'published') {
            return back()->withInput()->withErrors(['status' => 'Buat kelas sebagai draft terlebih dahulu, tambahkan minimal satu materi, lalu publikasikan.']);
        }

        $validated['slug'] = $this->uniqueSlug($validated['title']);
        $validated['created_by'] = $request->user()->id;

        $course = Course::create($validated);

        return redirect()->route('admin.courses.edit', $course)->with('status', 'Kelas berhasil dibuat.');
    }

    public function edit(Course $course): View
    {
        return view('admin.courses.form', [
            'course' => $course,
            'categories' => Category::orderBy('name')->get(),
            'lessons' => $course->lessons()->get(),
        ]);
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $validated = $this->validateCourse($request);
        if ($validated['status'] === 'published' && ! $course->lessons()->exists()) {
            return back()->withInput()->withErrors(['status' => 'Tambahkan minimal satu materi sebelum mempublikasikan kelas.']);
        }

        $validated['slug'] = $this->uniqueSlug($validated['title'], $course);
        $course->update($validated);

        return redirect()->route('admin.courses.edit', $course)->with('status', 'Informasi kelas berhasil diperbarui.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();

        return redirect()->route('admin.courses.index')->with('status', 'Kelas dan data terkait berhasil dihapus.');
    }

    private function validateCourse(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'description' => ['required', 'string', 'max:10000'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'level' => ['required', Rule::in(['Pemula', 'Menengah', 'Lanjutan', 'Semua Tingkat'])],
            'duration_minutes' => ['required', 'integer', 'min:0', 'max:100000'],
            'status' => ['required', Rule::in(['draft', 'published'])],
        ]);
    }

    private function uniqueSlug(string $title, ?Course $course = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $suffix = 2;

        while (Course::where('slug', $slug)->when($course, fn ($query) => $query->whereKeyNot($course->id))->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
}
