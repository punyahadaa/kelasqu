@extends('layouts.app')

@section('title', 'Pengaturan')
@section('page-heading', 'Pengaturan')

@section('content')
<div class="welcome-row">
    <div>
        <span class="section-kicker">PREFERENSI PLATFORM</span>
        <h1>Pengaturan</h1>
        <p>Informasi konfigurasi aplikasi KelasQu.</p>
    </div>
</div>
<section class="content-card settings-card">
    <div class="card-heading">
        <div><span class="section-kicker">INFORMASI APLIKASI</span><h2>Konfigurasi saat ini</h2><p>Nilai ini mengikuti konfigurasi lingkungan aplikasi.</p></div>
        <span class="settings-icon"><i class="bi bi-sliders"></i></span>
    </div>
    <div class="settings-list">
        <div class="settings-row"><div><strong>Nama aplikasi</strong><span>Nama yang ditampilkan pada halaman KelasQu.</span></div><span class="settings-value">{{ config('app.name') }}</span></div>
        <div class="settings-row"><div><strong>Zona waktu</strong><span>Zona waktu yang digunakan untuk tanggal dan laporan.</span></div><span class="settings-value">{{ config('app.timezone') }}</span></div>
        <div class="settings-row"><div><strong>Lingkungan</strong><span>Mode aplikasi saat ini.</span></div><span class="settings-value">{{ app()->environment() }}</span></div>
        <div class="settings-row"><div><strong>Status aplikasi</strong><span>Layanan KelasQu saat ini.</span></div><span class="status-badge"><span></span>Berjalan</span></div>
    </div>
    <div class="settings-note"><i class="bi bi-lock"></i> Pengaturan lingkungan dikelola melalui file <code>.env</code> oleh administrator server.</div>
</section>
@endsection
