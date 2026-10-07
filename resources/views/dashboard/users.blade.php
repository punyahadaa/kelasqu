@extends('layouts.app')

@section('title', 'Pengguna')
@section('page-heading', 'Pengguna')

@section('content')
<div class="welcome-row">
    <div>
        <span class="section-kicker">MANAJEMEN PENGGUNA</span>
        <h1>Daftar pengguna</h1>
        <p>Kelola dan pantau anggota yang terdaftar di KelasQu.</p>
    </div>
    <a href="{{ route('dashboard') }}" class="btn btn-outline-soft"><i class="bi bi-arrow-left"></i> Kembali ke dashboard</a>
</div>
<section class="content-card">
    <div class="card-heading">
        <div><span class="section-kicker">ANGGOTA KELASQU</span><h2>Semua pengguna</h2><p>{{ $users->total() }} pengguna terdaftar</p></div>
    </div>
    @if($users->isEmpty())
        <div class="empty-state"><span class="empty-icon"><i class="bi bi-person-plus"></i></span><strong>Belum ada pengguna</strong><p>Pengguna yang mendaftar akan muncul di halaman ini.</p></div>
    @else
        <div class="table-responsive">
            <table class="table user-table align-middle mb-0">
                <thead><tr><th>NAMA PENGGUNA</th><th>EMAIL</th><th>TANGGAL BERGABUNG</th><th>STATUS</th></tr></thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td><div class="person-cell"><span class="person-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</span><strong>{{ $user->name }}</strong></div></td>
                            <td class="muted-cell">{{ $user->email }}</td>
                            <td class="muted-cell">{{ $user->created_at->format('d M Y') }}</td>
                            <td><span class="status-badge"><span></span>Aktif</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="pagination-wrap">{{ $users->links() }}</div>
        @endif
    @endif
</section>
@endsection
