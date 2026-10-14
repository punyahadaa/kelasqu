@extends('layouts.app')

@section('title', 'Materi')
@section('page-heading', 'Materi')

@section('content')
<div class="welcome-row"><div><span class="section-kicker">SUMBER BELAJAR</span><h1>Materi pembelajaran</h1><p>Akses materi dari semua kelas yang kamu ikuti.</p></div></div>
<section class="content-card">
    @forelse($lessons as $lesson)
        <a href="{{ route('user.lesson.show', [$lesson->course, $lesson]) }}" class="material-row">
            <span class="material-icon"><i class="bi bi-journal-text"></i></span>
            <span class="course-lesson-copy"><strong>{{ $lesson->title }}</strong><small>{{ $lesson->course->title }}</small></span>
            @if(in_array($lesson->id, $completedLessonIds))<span class="status-badge"><span></span>Selesai</span>@else<span class="material-action">Buka materi <i class="bi bi-arrow-right"></i></span>@endif
        </a>
    @empty
        <div class="empty-state"><span class="empty-icon"><i class="bi bi-journal-text"></i></span><strong>Belum ada materi untuk dipelajari</strong><p>Gabung ke kelas terlebih dahulu untuk melihat materinya.</p><a href="{{ route('user.catalog') }}" class="btn btn-primary profile-save mt-3">Jelajahi kelas</a></div>
    @endforelse
    @if($lessons->hasPages())<div class="pagination-wrap">{{ $lessons->links() }}</div>@endif
</section>
@endsection
