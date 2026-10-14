<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StudentCourseController extends Controller
{
    public function catalog(Request $request): View
    {
        $courses = Course::with(['category', 'creator'])
            ->withCount('lessons')
            ->where('status', 'published')
            ->when($request->filled('search'), fn ($query) => $query->where('title', 'like', '%'.$request->string('search')->toString().'%'))
            ->when($request->filled('category'), fn ($query) => $query->where('category_id', $request->integer('category')))
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $enrolledIds = $request->user()->enrollments()->pluck('course_id')->all();

        return view('student.catalog', [
            'courses' => $courses,
            'categories' => \App\Models\Category::orderBy('name')->get(),
            'enrolledIds' => $enrolledIds,
        ]);
    }

    public function show(Course $course, Request $request): View
    {
        $enrollment = $request->user()->enrollments()->where('course_id', $course->id)->first();
        abort_unless($course->status === 'published' || $enrollment, 404);

        $course->load(['category', 'creator', 'lessons']);
        $completedIds = $enrollment
            ? LessonProgress::where('user_id', $request->user()->id)
                ->whereIn('lesson_id', $course->lessons->pluck('id'))
                ->whereNotNull('completed_at')
                ->pluck('lesson_id')
                ->all()
            : [];

        return view('student.course', compact('course', 'enrollment', 'completedIds'));
    }

    public function enroll(Course $course, Request $request): RedirectResponse
    {
        abort_unless($course->status === 'published', 404);

        Enrollment::firstOrCreate(
            ['user_id' => $request->user()->id, 'course_id' => $course->id],
            ['enrolled_at' => now()]
        );

        return redirect()->route('user.course.show', $course)->with('status', 'Kamu berhasil bergabung ke kelas.');
    }

    public function myClasses(Request $request): View
    {
        $enrollments = $request->user()->enrollments()
            ->with(['course.category', 'course.lessons', 'certificate'])
            ->latest('enrolled_at')
            ->paginate(9);

        $progress = [];
        foreach ($enrollments as $enrollment) {
            $lessonIds = $enrollment->course->lessons->pluck('id');
            $progress[$enrollment->course_id] = $lessonIds->isEmpty()
                ? 0
                : (int) round(LessonProgress::where('user_id', $request->user()->id)
                    ->whereIn('lesson_id', $lessonIds)
                    ->whereNotNull('completed_at')
                    ->count() / $lessonIds->count() * 100);
        }

        return view('student.my-classes', compact('enrollments', 'progress'));
    }

    public function materials(Request $request): View
    {
        $enrolledCourseIds = $request->user()->enrollments()->pluck('course_id');
        $lessons = Lesson::with('course')
            ->whereIn('course_id', $enrolledCourseIds)
            ->orderBy('course_id')
            ->orderBy('position')
            ->paginate(15);
        $completedLessonIds = LessonProgress::where('user_id', $request->user()->id)
            ->whereNotNull('completed_at')
            ->pluck('lesson_id')
            ->all();

        return view('student.materials', compact('lessons', 'completedLessonIds'));
    }

    public function lesson(Course $course, Lesson $lesson, Request $request): View
    {
        abort_unless($lesson->course_id === $course->id, 404);
        abort_unless($request->user()->enrollments()->where('course_id', $course->id)->exists(), 403);

        $course->load('lessons');
        $completedIds = LessonProgress::where('user_id', $request->user()->id)
            ->whereIn('lesson_id', $course->lessons->pluck('id'))
            ->whereNotNull('completed_at')
            ->pluck('lesson_id')
            ->all();

        return view('student.lesson', compact('course', 'lesson', 'completedIds'));
    }

    public function completeLesson(Course $course, Lesson $lesson, Request $request): RedirectResponse
    {
        abort_unless($lesson->course_id === $course->id, 404);

        $user = $request->user();
        DB::transaction(function () use ($course, $lesson, $user) {
            $enrollment = Enrollment::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->lockForUpdate()
                ->firstOrFail();

            LessonProgress::firstOrCreate(
                ['user_id' => $user->id, 'lesson_id' => $lesson->id],
                ['completed_at' => now()]
            );

            $lessonCount = $course->lessons()->count();
            $completedCount = LessonProgress::where('user_id', $user->id)
                ->whereIn('lesson_id', $course->lessons()->select('id'))
                ->whereNotNull('completed_at')
                ->count();

            if ($lessonCount > 0 && $completedCount === $lessonCount) {
                $enrollment->update(['completed_at' => $enrollment->completed_at ?? now()]);
                Certificate::firstOrCreate(
                    ['enrollment_id' => $enrollment->id],
                    ['code' => (string) Str::uuid(), 'issued_at' => now()]
                );
            }
        });

        return redirect()->route('user.course.show', $course)->with('status', 'Progres materi berhasil disimpan.');
    }

    public function certificates(Request $request): View
    {
        $certificates = Certificate::with('enrollment.course')
            ->whereHas('enrollment', fn ($query) => $query->where('user_id', $request->user()->id))
            ->latest('issued_at')
            ->paginate(10);

        return view('student.certificates', compact('certificates'));
    }

    public function certificate(Certificate $certificate, Request $request): View
    {
        $certificate->load('enrollment.course', 'enrollment.user');
        abort_unless($certificate->enrollment->user_id === $request->user()->id, 404);

        return view('student.certificate', compact('certificate'));
    }
}
