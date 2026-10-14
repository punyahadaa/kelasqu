<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();
                $query->where(fn ($query) => $query
                    ->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%'));
            })
            ->when($request->filled('role'), fn ($query) => $query->where('role', $request->input('role')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('dashboard.users', ['users' => $users]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', Rule::in(['admin', 'user'])],
            'is_active' => ['required', 'boolean'],
        ]);

        if ($user->is($request->user()) && ($validated['role'] !== 'admin' || ! $validated['is_active'])) {
            return back()->withErrors(['account' => 'Anda tidak dapat menonaktifkan atau mengubah peran akun sendiri.']);
        }

        if ($user->role === 'admin' && $validated['role'] !== 'admin'
            && User::where('role', 'admin')->where('is_active', true)->count() <= 1) {
            return back()->withErrors(['account' => 'Minimal harus ada satu administrator aktif.']);
        }

        $user->update($validated);

        return redirect()->route('admin.users')->with('status', 'Akun pengguna berhasil diperbarui.');
    }
}
