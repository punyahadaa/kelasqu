<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class PortalController extends Controller
{
    public function reports(): View
    {
        return view('portal.reports', [
            'totalUsers' => User::where('role', 'user')->count(),
            'newUsers' => User::where('role', 'user')->where('created_at', '>=', now()->startOfMonth())->count(),
            'totalAdmins' => User::where('role', 'admin')->count(),
            'totalCourses' => Course::count(),
            'publishedCourses' => Course::where('status', 'published')->count(),
            'totalEnrollments' => Enrollment::count(),
            'completedCourses' => Enrollment::whereNotNull('completed_at')->count(),
            'popularCourses' => Course::withCount('enrollments')->orderByDesc('enrollments_count')->take(5)->get(),
            'completionCount' => LessonProgress::whereNotNull('completed_at')->count(),
        ]);
    }

    public function settings(): View
    {
        return view('portal.settings');
    }

    public function profile(): View
    {
        return view('portal.profile');
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (! empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        return redirect()->route('profile.edit')->with('status', 'Profil berhasil diperbarui.');
    }
}
