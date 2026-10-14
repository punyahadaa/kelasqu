<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminLessonController extends Controller
{
    public function store(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'content' => ['required', 'string', 'max:50000'],
            'video_url' => ['nullable', 'url', 'max:2048'],
        ]);

        DB::transaction(function () use ($course, $validated) {
            $validated['position'] = (int) $course->lessons()->max('position') + 1;
            $course->lessons()->create($validated);
            $this->resetCourseProgress($course);
        });

        return redirect()->route('admin.courses.edit', $course)->with('status', 'Materi berhasil ditambahkan. Progres dan sertifikat siswa kelas ini direset.');
    }

    public function update(Request $request, Course $course, Lesson $lesson): RedirectResponse
    {
        abort_unless($lesson->course_id === $course->id, 404);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'content' => ['required', 'string', 'max:50000'],
            'video_url' => ['nullable', 'url', 'max:2048'],
        ]);

        DB::transaction(function () use ($course, $lesson, $validated) {
            $lesson->fill($validated);
            if ($lesson->isDirty()) {
                $lesson->save();
                $this->resetCourseProgress($course);
            }
        });

        return redirect()->route('admin.courses.edit', $course)->with('status', 'Materi berhasil diperbarui. Progres siswa direset jika isi materi berubah.');
    }

    public function destroy(Course $course, Lesson $lesson): RedirectResponse
    {
        abort_unless($lesson->course_id === $course->id, 404);
        DB::transaction(function () use ($course, $lesson) {
            $lesson->delete();
            $this->resetCourseProgress($course);

            if (! $course->lessons()->exists()) {
                $course->update(['status' => 'draft']);
            }
        });

        return redirect()->route('admin.courses.edit', $course)->with('status', 'Materi berhasil dihapus. Progres dan sertifikat siswa kelas ini direset.');
    }

    private function resetCourseProgress(Course $course): void
    {
        Certificate::whereHas('enrollment', fn ($query) => $query->where('course_id', $course->id))->delete();
        Enrollment::where('course_id', $course->id)->update(['completed_at' => null]);
        LessonProgress::whereIn('lesson_id', $course->lessons()->select('id'))->delete();
    }
}
