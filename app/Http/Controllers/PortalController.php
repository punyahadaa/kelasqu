<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class PortalController extends Controller
{
    public function classes(): View
    {
        return view('portal.empty', [
            'title' => 'Kelas',
            'eyebrow' => 'MANAJEMEN PEMBELAJARAN',
            'description' => 'Kelola kelas dan pantau materi belajar yang tersedia di KelasQu.',
            'cardTitle' => 'Kelas yang tersedia',
            'cardDescription' => 'Kelas akan tampil di sini setelah ditambahkan ke platform.',
            'icon' => 'bi-easel2',
            'pageTitle' => 'Kelas',
        ]);
    }

    public function categories(): View
    {
        return view('portal.empty', [
            'title' => 'Kategori',
            'eyebrow' => 'PENGELOMPOKAN KELAS',
            'description' => 'Kategori membantu siswa menemukan kelas sesuai minat belajarnya.',
            'cardTitle' => 'Kategori kelas',
            'cardDescription' => 'Kategori akan tampil di sini setelah tersedia.',
            'icon' => 'bi-collection',
            'pageTitle' => 'Kategori',
        ]);
    }

    public function myClasses(): View
    {
        return view('portal.empty', [
            'title' => 'Kelas Saya',
            'eyebrow' => 'RUANG BELAJARMU',
            'description' => 'Semua kelas yang kamu ikuti akan tersimpan dan mudah diakses di sini.',
            'cardTitle' => 'Belum ada kelas',
            'cardDescription' => 'Kelas yang kamu ikuti akan muncul di halaman ini.',
            'icon' => 'bi-journal-bookmark',
            'pageTitle' => 'Kelas Saya',
        ]);
    }

    public function materials(): View
    {
        return view('portal.empty', [
            'title' => 'Materi',
            'eyebrow' => 'SUMBER BELAJAR',
            'description' => 'Akses materi pembelajaran dari kelas yang sedang kamu ikuti.',
            'cardTitle' => 'Materi belum tersedia',
            'cardDescription' => 'Materi kelas akan ditampilkan di sini setelah kamu bergabung ke kelas.',
            'icon' => 'bi-journal-text',
            'pageTitle' => 'Materi',
        ]);
    }

    public function certificates(): View
    {
        return view('portal.empty', [
            'title' => 'Sertifikat',
            'eyebrow' => 'PENCAPAIANMU',
            'description' => 'Kumpulan sertifikat dari perjalanan belajar yang telah kamu selesaikan.',
            'cardTitle' => 'Belum ada sertifikat',
            'cardDescription' => 'Sertifikat kelas yang berhasil kamu selesaikan akan tersimpan di sini.',
            'icon' => 'bi-award',
            'pageTitle' => 'Sertifikat',
        ]);
    }

    public function reports(): View
    {
        return view('portal.reports', [
            'totalUsers' => User::where('role', 'user')->count(),
            'newUsers' => User::where('role', 'user')->where('created_at', '>=', now()->startOfMonth())->count(),
            'totalAdmins' => User::where('role', 'admin')->count(),
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
