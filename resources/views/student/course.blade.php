@extends('layouts.app')

@section('title', $course->title)
@section('page-heading', 'Detail Kelas')

@section('content')
<div class="course-detail-hero">
    <a href="{{ route('user.catalog') }}" class="course-back"><i class="bi bi-arrow-left"></i> Kembali ke katalog</a>
    <div class="course-detail-meta"><span>{{ $course->category?->name ?? 'Umum' }}</span><span>{{ $course->level }}</span><span><i class="bi bi-journal-text"></i> {{ $course->lessons->count() }} materi</span></div>
    <h1>{{ $course->title }}</h1>
    <p>{{ $course->description }}</p>
    <div class="course-detail-byline">Disusun oleh <strong>{{ $course->creator?->name ?? 'Tim KelasQu' }}</strong></div>
    @if(! $enrollment)
        <form method="POST" action="{{ route('user.course.enroll', $course) }}">@csrf<button class="btn btn-light course-enroll-btn" type="submit"><i class="bi bi-plus-circle"></i> Gabung kelas gratis</button></form>
    @else
        <div class="course-enrolled-label"><i class="bi bi-check-circle"></i> Kamu sudah terdaftar di kelas ini</div>
    @endif
</div>
@if(session('status'))<div class="alert alert-success profile-alert mt-3">{{ session('status') }}</div>@endif
@if($enrollment)
    <section class="content-card course-curriculum">
        <div class="card-heading"><div><span class="section-kicker">KURIKULUM</span><h2>Materi pembelajaran</h2><p>Selesaikan semua materi untuk mendapatkan sertifikat.</p></div>@if($enrollment->certificate)<a class="btn btn-outline-soft" href="{{ route('user.certificate.show', $enrollment->certificate) }}"><i class="bi bi-award"></i> Lihat sertifikat</a>@endif</div>
        @forelse($course->lessons as $lesson)
            <a class="course-lesson-row" href="{{ route('user.lesson.show', [$course, $lesson]) }}">
                <span class="journey-number">{{ str_pad($lesson->position, 2, '0', STR_PAD_LEFT) }}</span>
                <span class="course-lesson-copy"><strong>{{ $lesson->title }}</strong><small>{{ \Illuminate\Support\Str::limit(strip_tags($lesson->content), 115) }}</small></span>
                @if(in_array($lesson->id, $completedIds))<span class="status-badge"><span></span>Selesai</span>@else<i class="bi bi-chevron-right course-lesson-arrow"></i>@endif
            </a>
        @empty
            <div class="empty-state"><span class="empty-icon"><i class="bi bi-journal-text"></i></span><strong>Materi segera hadir</strong><p>Pengajar belum menambahkan materi ke kelas ini.</p></div>
        @endforelse
    </section>
@else
    <section class="content-card course-preview-note"><span class="help-icon"><i class="bi bi-info-circle"></i></span><div><strong>Mulai belajar kapan saja</strong><p>Gabung kelas ini gratis untuk membuka semua materi dan menyimpan progres belajarmu.</p></div></section>
@endif
@endsection
