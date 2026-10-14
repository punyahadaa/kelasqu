<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminCourseController;
use App\Http\Controllers\AdminLessonController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\StudentCourseController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1')->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
    Route::get('/forgot-password', [PasswordResetController::class, 'requestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendLink'])->middleware('throttle:5,1')->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'resetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/profile', [PortalController::class, 'profile'])->name('profile.edit');
    Route::put('/profile', [PortalController::class, 'updateProfile'])->name('profile.update');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

Route::middleware(['auth', 'active', 'verified'])
    ->get('/dashboard', DashboardController::class)
    ->name('dashboard');

Route::middleware(['auth', 'active', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users');
    Route::put('/admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::get('/admin/classes', [AdminCourseController::class, 'index'])->name('admin.classes');
    Route::get('/admin/courses', [AdminCourseController::class, 'index'])->name('admin.courses.index');
    Route::get('/admin/courses/create', [AdminCourseController::class, 'create'])->name('admin.courses.create');
    Route::post('/admin/courses', [AdminCourseController::class, 'store'])->name('admin.courses.store');
    Route::get('/admin/courses/{course}/edit', [AdminCourseController::class, 'edit'])->name('admin.courses.edit');
    Route::put('/admin/courses/{course}', [AdminCourseController::class, 'update'])->name('admin.courses.update');
    Route::delete('/admin/courses/{course}', [AdminCourseController::class, 'destroy'])->name('admin.courses.destroy');
    Route::post('/admin/courses/{course}/lessons', [AdminLessonController::class, 'store'])->name('admin.lessons.store');
    Route::put('/admin/courses/{course}/lessons/{lesson}', [AdminLessonController::class, 'update'])->name('admin.lessons.update');
    Route::delete('/admin/courses/{course}/lessons/{lesson}', [AdminLessonController::class, 'destroy'])->name('admin.lessons.destroy');
    Route::get('/admin/categories', [AdminCategoryController::class, 'index'])->name('admin.categories');
    Route::post('/admin/categories', [AdminCategoryController::class, 'store'])->name('admin.categories.store');
    Route::put('/admin/categories/{category}', [AdminCategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/admin/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('admin.categories.destroy');
    Route::get('/admin/reports', [PortalController::class, 'reports'])->name('admin.reports');
    Route::get('/admin/settings', [PortalController::class, 'settings'])->name('admin.settings');
});

Route::middleware(['auth', 'active', 'verified', 'role:user'])->group(function () {
    Route::get('/courses', [StudentCourseController::class, 'catalog'])->name('user.catalog');
    Route::get('/courses/{course}', [StudentCourseController::class, 'show'])->name('user.course.show');
    Route::post('/courses/{course}/enroll', [StudentCourseController::class, 'enroll'])->name('user.course.enroll');
    Route::get('/my-classes', [StudentCourseController::class, 'myClasses'])->name('user.classes');
    Route::get('/materials', [StudentCourseController::class, 'materials'])->name('user.materials');
    Route::get('/certificates', [StudentCourseController::class, 'certificates'])->name('user.certificates');
    Route::get('/courses/{course}/lessons/{lesson}', [StudentCourseController::class, 'lesson'])->name('user.lesson.show');
    Route::post('/courses/{course}/lessons/{lesson}/complete', [StudentCourseController::class, 'completeLesson'])->name('user.lesson.complete');
    Route::get('/certificates/{certificate}', [StudentCourseController::class, 'certificate'])->name('user.certificate.show');
});
