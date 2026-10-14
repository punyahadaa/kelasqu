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
    <article class="stat-card">
        <div class="stat-card-top"><span class="stat-icon stat-icon-green"><i class="bi bi-easel2"></i></span><span class="stat-caption">KONTEN</span></div>
        <div class="stat-number">{{ number_format($publishedCourses, 0, ',', '.') }}<small class="stat-denominator"> / {{ number_format($totalCourses, 0, ',', '.') }}</small></div>
        <div class="stat-label">Kelas dipublikasikan</div>
        <div class="stat-foot"><span class="stat-foot-icon"><i class="bi bi-journal-text"></i></span>{{ number_format($totalCourses, 0, ',', '.') }} kelas keseluruhan</div>
    </article>
    <article class="stat-card">
        <div class="stat-card-top"><span class="stat-icon stat-icon-violet"><i class="bi bi-person-workspace"></i></span><span class="stat-caption">PARTISIPASI</span></div>
        <div class="stat-number">{{ number_format($totalEnrollments, 0, ',', '.') }}</div>
        <div class="stat-label">Pendaftaran kelas</div>
        <div class="stat-foot"><span class="stat-foot-icon"><i class="bi bi-check-circle"></i></span>{{ number_format($completedCourses, 0, ',', '.') }} kelas diselesaikan</div>
    </article>
    <article class="stat-card">
        <div class="stat-card-top"><span class="stat-icon stat-icon-amber"><i class="bi bi-check2-square"></i></span><span class="stat-caption">PROGRES BELAJAR</span></div>
        <div class="stat-number">{{ number_format($completionCount, 0, ',', '.') }}</div>
        <div class="stat-label">Materi diselesaikan</div>
        <div class="stat-foot"><span class="stat-foot-icon"><i class="bi bi-graph-up-arrow"></i></span>Seluruh siswa</div>
    </article>
</div>
<section class="content-card">
    <div class="card-heading"><div><span class="section-kicker">KELAS PALING DIMINATI</span><h2>Pendaftaran per kelas</h2><p>Urut berdasarkan jumlah siswa terdaftar.</p></div></div>
    @if($popularCourses->isEmpty())
        <div class="empty-state"><span class="empty-icon"><i class="bi bi-bar-chart-line"></i></span><strong>Belum ada data kelas</strong><p>Statistik akan muncul setelah kelas dan pendaftaran tersedia.</p></div>
    @else
        <div class="table-responsive"><table class="table user-table align-middle mb-0"><thead><tr><th>KELAS</th><th>KATEGORI</th><th>MATERI</th><th>SISWA TERDAFTAR</th><th>STATUS</th></tr></thead>
            <tbody>@foreach($popularCourses as $course)<tr><td><strong>{{ $course->title }}</strong></td><td class="muted-cell">{{ $course->category?->name ?? 'Tanpa kategori' }}</td><td class="muted-cell">{{ $course->lessons()->count() }}</td><td class="muted-cell">{{ $course->enrollments_count }}</td><td><span class="status-badge"><span></span>{{ $course->status === 'published' ? 'Publik' : 'Draft' }}</span></td></tr>@endforeach</tbody>
        </table></div>
    @endif
</section>
@endsection
