@extends('layouts.app')

@section('title', 'Pengguna')
@section('page-heading', 'Pengguna')

@section('content')
<div class="welcome-row">
    <div><span class="section-kicker">MANAJEMEN PENGGUNA</span><h1>Daftar pengguna</h1><p>Kelola akses dan peran anggota KelasQu.</p></div>
</div>
@if(session('status'))<div class="alert alert-success profile-alert">{{ session('status') }}</div>@endif
@if($errors->has('account'))<div class="alert alert-danger profile-alert">{{ $errors->first('account') }}</div>@endif
<section class="content-card">
    <div class="card-heading"><div><span class="section-kicker">ANGGOTA KELASQU</span><h2>Semua pengguna</h2><p>{{ $users->total() }} akun</p></div></div>
    <form method="GET" action="{{ route('admin.users') }}" class="user-filter-form">
        <input type="search" class="form-control" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email">
        <select class="form-select" name="role"><option value="">Semua peran</option><option value="user" @selected(request('role') === 'user')>Siswa</option><option value="admin" @selected(request('role') === 'admin')>Admin</option></select>
        <button class="btn btn-primary profile-save" type="submit">Filter</button>
    </form>
    <div class="table-responsive">
        <table class="table user-table align-middle mb-0">
            <thead><tr><th>NAMA PENGGUNA</th><th>EMAIL</th><th>BERGABUNG</th><th>STATUS</th><th>AKSES</th></tr></thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td><div class="person-cell"><span class="person-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</span><strong>{{ $user->name }}</strong></div></td>
                        <td class="muted-cell">{{ $user->email }}</td>
                        <td class="muted-cell">{{ $user->created_at->format('d M Y') }}</td>
                        <td><span class="status-badge"><span style="background:{{ $user->is_active ? '#7eaa64' : '#ca7272' }}"></span>{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td>
                            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="user-access-form">
                                @csrf @method('PUT')
                                <select class="form-select form-select-sm" name="role" aria-label="Peran {{ $user->name }}"><option value="user" @selected($user->role === 'user')>Siswa</option><option value="admin" @selected($user->role === 'admin')>Admin</option></select>
                                <input type="hidden" name="is_active" value="0">
                                <label class="user-active-check"><input type="checkbox" name="is_active" value="1" @checked($user->is_active)> Aktif</label>
                                <button class="btn btn-outline-soft" type="submit">Simpan</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5"><div class="empty-state"><strong>Pengguna tidak ditemukan</strong><p>Coba ubah kata kunci atau filter peran.</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())<div class="pagination-wrap">{{ $users->links() }}</div>@endif
</section>
@endsection
