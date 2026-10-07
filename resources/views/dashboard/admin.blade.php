@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-heading', 'Dashboard Admin')

@section('content')
<div class="welcome-row">
    <div>
        <span class="section-kicker">RINGKASAN PLATFORM</span>
        <h1>Selamat pagi, {{ auth()->user()->name }} <span class="wave">✦</span></h1>
        <p>Berikut ringkasan aktivitas KelasQu saat ini.</p>
    </div>
    <div class="today-date"><i class="bi bi-calendar3"></i>{{ now()->translatedFormat('l, d F Y') }}</div>
</div>

<div class="stats-grid">
    <article class="stat-card">
        <div class="stat-card-top"><span class="stat-icon stat-icon-violet"><i class="bi bi-people"></i></span><span class="stat-caption">SELURUH WAKTU</span></div>
        <div class="stat-number">{{ number_format($totalUsers, 0, ',', '.') }}</div>
        <div class="stat-label">Total pengguna terdaftar</div>
        <div class="stat-foot"><span class="stat-foot-icon"><i class="bi bi-person-check"></i></span>Anggota komunitas KelasQu</div>
    </article>
    <article class="stat-card">
        <div class="stat-card-top"><span class="stat-icon stat-icon-green"><i class="bi bi-person-plus"></i></span><span class="stat-caption">BULAN INI</span></div>
        <div class="stat-number">{{ number_format($newUsers, 0, ',', '.') }}</div>
        <div class="stat-label">Pengguna baru</div>
        <div class="stat-foot"><span class="stat-foot-icon"><i class="bi bi-graph-up-arrow"></i></span>Pendaftar sejak awal bulan</div>
    </article>
    <article class="stat-card">
        <div class="stat-card-top"><span class="stat-icon stat-icon-amber"><i class="bi bi-shield-lock"></i></span><span class="stat-caption">AKSES TERKELOLA</span></div>
        <div class="stat-number">{{ number_format($totalAdmins, 0, ',', '.') }}</div>
        <div class="stat-label">Administrator</div>
        <div class="stat-foot"><span class="stat-foot-icon"><i class="bi bi-check-circle"></i></span>Pengelola platform</div>
    </article>
</div>

<section class="content-card">
    <div class="card-heading">
        <div><span class="section-kicker">KOMUNITAS KELASQU</span><h2>Pengguna terbaru</h2><p>Anggota yang baru bergabung di platform.</p></div>
        <a href="{{ route('admin.users') }}" class="btn btn-outline-soft">Lihat semua <i class="bi bi-arrow-up-right"></i></a>
    </div>
    @if($recentUsers->isEmpty())
        <div class="empty-state"><span class="empty-icon"><i class="bi bi-people"></i></span><strong>Belum ada pengguna</strong><p>Pengguna yang mendaftar akan muncul di sini.</p></div>
    @else
        <div class="table-responsive">
            <table class="table user-table align-middle mb-0">
                <thead><tr><th>NAMA PENGGUNA</th><th>EMAIL</th><th>TANGGAL BERGABUNG</th><th>STATUS</th></tr></thead>
                <tbody>
                    @foreach($recentUsers as $user)
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
    @endif
</section>
@endsection
