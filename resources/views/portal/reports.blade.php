@extends('layouts.app')

@section('title', 'Laporan')
@section('page-heading', 'Laporan')

@section('content')
<div class="welcome-row">
    <div>
        <span class="section-kicker">ANALISIS PLATFORM</span>
        <h1>Laporan KelasQu</h1>
        <p>Ringkasan pengguna dan aktivitas pendaftaran platform.</p>
    </div>
    <div class="today-date"><i class="bi bi-calendar3"></i>{{ now()->translatedFormat('F Y') }}</div>
</div>
<div class="stats-grid">
    <article class="stat-card">
        <div class="stat-card-top"><span class="stat-icon stat-icon-violet"><i class="bi bi-people"></i></span><span class="stat-caption">PENGGUNA</span></div>
        <div class="stat-number">{{ number_format($totalUsers, 0, ',', '.') }}</div>
        <div class="stat-label">Total siswa terdaftar</div>
        <div class="stat-foot"><span class="stat-foot-icon"><i class="bi bi-person-check"></i></span>Seluruh akun siswa aktif</div>
    </article>
    <article class="stat-card">
        <div class="stat-card-top"><span class="stat-icon stat-icon-green"><i class="bi bi-person-plus"></i></span><span class="stat-caption">BULAN INI</span></div>
        <div class="stat-number">{{ number_format($newUsers, 0, ',', '.') }}</div>
        <div class="stat-label">Siswa baru bulan ini</div>
        <div class="stat-foot"><span class="stat-foot-icon"><i class="bi bi-graph-up-arrow"></i></span>Pendaftaran sejak awal bulan</div>
    </article>
    <article class="stat-card">
        <div class="stat-card-top"><span class="stat-icon stat-icon-amber"><i class="bi bi-shield-lock"></i></span><span class="stat-caption">PENGELOLA</span></div>
        <div class="stat-number">{{ number_format($totalAdmins, 0, ',', '.') }}</div>
        <div class="stat-label">Administrator</div>
        <div class="stat-foot"><span class="stat-foot-icon"><i class="bi bi-check-circle"></i></span>Pengelola platform</div>
    </article>
</div>
<section class="content-card report-note">
    <span class="help-icon"><i class="bi bi-info-circle"></i></span>
    <div><strong>Laporan kelas segera hadir</strong><p>Statistik kelas, progres belajar, dan penyelesaian materi akan tersedia setelah fitur kelas dan materi diaktifkan.</p></div>
</section>
@endsection
