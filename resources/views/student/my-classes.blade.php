@extends('layouts.app')

@section('title', 'Kelas Saya')
@section('page-heading', 'Kelas Saya')

@section('content')
<div class="welcome-row"><div><span class="section-kicker">RUANG BELAJARMU</span><h1>Kelas yang kamu ikuti</h1><p>Lanjutkan materi dan pantau progres belajarmu.</p></div><a href="{{ route('user.catalog') }}" class="btn btn-primary profile-save"><i class="bi bi-compass"></i> Jelajahi kelas</a></div>
<div class="course-grid">
    @forelse($enrollments as $enrollment)
        <article class="content-card course-card">
            <div class="course-cover"><span class="course-cover-icon"><i class="bi bi-journal-richtext"></i></span><span class="course-level">{{ $enrollment->course->level }}</span></div>
            <div class="course-card-body">
                <div class="course-meta"><span>{{ $enrollment->course->category?->name ?? 'Umum' }}</span><span>{{ $enrollment->course->lessons->count() }} materi</span></div>
                <h2>{{ $enrollment->course->title }}</h2>
                <p>{{ \Illuminate\Support\Str::limit($enrollment->course->description, 100) }}</p>
                <div class="progress-label"><span>Progres belajar</span><strong>{{ $progress[$enrollment->course_id] ?? 0 }}%</strong></div>
                <div class="progress course-progress"><div class="progress-bar" style="width: {{ $progress[$enrollment->course_id] ?? 0 }}%"></div></div>
                <div class="course-card-footer"><span class="course-duration"><i class="bi bi-calendar3"></i> Gabung {{ $enrollment->enrolled_at->format('d M Y') }}</span><a href="{{ route('user.course.show', $enrollment->course) }}" class="btn btn-primary profile-save">{{ $enrollment->certificate ? 'Lihat kelas' : 'Lanjut belajar' }}</a></div>
            </div>
        </article>
    @empty
        <section class="content-card portal-empty-card catalog-empty"><div class="portal-empty-illustration"><i class="bi bi-journal-bookmark"></i></div><span class="section-kicker">RUANG BELAJARMU</span><h2>Belum mengikuti kelas</h2><p>Jelajahi katalog dan gabung ke kelas gratis untuk mulai belajar.</p><a class="btn btn-primary profile-save" href="{{ route('user.catalog') }}">Jelajahi kelas <i class="bi bi-arrow-right"></i></a></section>
    @endforelse
</div>
@if($enrollments->hasPages())<div class="pagination-wrap">{{ $enrollments->links() }}</div>@endif
@endsection
