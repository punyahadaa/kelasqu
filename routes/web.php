<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PortalController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1')->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/profile', [PortalController::class, 'profile'])->name('profile.edit');
    Route::put('/profile', [PortalController::class, 'updateProfile'])->name('profile.update');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/users', [DashboardController::class, 'users'])->name('admin.users');
    Route::get('/admin/classes', [PortalController::class, 'classes'])->name('admin.classes');
    Route::get('/admin/categories', [PortalController::class, 'categories'])->name('admin.categories');
    Route::get('/admin/reports', [PortalController::class, 'reports'])->name('admin.reports');
    Route::get('/admin/settings', [PortalController::class, 'settings'])->name('admin.settings');
});

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/my-classes', [PortalController::class, 'myClasses'])->name('user.classes');
    Route::get('/materials', [PortalController::class, 'materials'])->name('user.materials');
    Route::get('/certificates', [PortalController::class, 'certificates'])->name('user.certificates');
});
