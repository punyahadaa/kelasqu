@extends('layouts.app')

@section('title', 'Jelajahi Kelas')
@section('page-heading', 'Jelajahi Kelas')

@section('content')
<div class="welcome-row"><div><span class="section-kicker">KATALOG PEMBELAJARAN</span><h1>Temukan kelasmu</h1><p>Pilih topik yang menarik dan mulai belajar sesuai ritmemu.</p></div></div>
<form method="GET" action="{{ route('user.catalog') }}" class="catalog-filters">
    <div class="catalog-search"><i class="bi bi-search"></i><input type="search" name="search" value="{{ request('search') }}" placeholder="Cari kelas..."></div>
    <select name="category" class="form-select"><option value="">Semua kategori</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>@endforeach</select>
    <button class="btn btn-primary profile-save" type="submit">Cari kelas</button>
</form>
@if(session('status'))<div class="alert alert-success profile-alert">{{ session('status') }}</div>@endif
<div class="course-grid">
    @forelse($courses as $course)
        <article class="content-card course-card">
            <div class="course-cover"><span class="course-cover-icon"><i class="bi bi-journal-richtext"></i></span><span class="course-level">{{ $course->level }}</span></div>
            <div class="course-card-body">
                <div class="course-meta"><span>{{ $course->category?->name ?? 'Umum' }}</span><span><i class="bi bi-play-circle"></i> {{ $course->lessons_count }} materi</span></div>
                <h2>{{ $course->title }}</h2>
                <p>{{ \Illuminate\Support\Str::limit($course->description, 110) }}</p>
                <div class="course-card-footer">
                    <span class="course-duration"><i class="bi bi-clock"></i> {{ $course->duration_minutes ? $course->duration_minutes.' menit' : 'Belajar mandiri' }}</span>
                    @if(in_array($course->id, $enrolledIds))
                        <a class="btn btn-primary profile-save" href="{{ route('user.course.show', $course) }}">Lanjutkan</a>
                    @else
                        <a class="btn btn-primary profile-save" href="{{ route('user.course.show', $course) }}">Lihat kelas</a>
                    @endif
                </div>
            </div>
        </article>
    @empty
        <section class="content-card portal-empty-card catalog-empty"><div class="portal-empty-illustration"><i class="bi bi-search"></i></div><span class="section-kicker">KATALOG KELAS</span><h2>Belum ada kelas yang cocok</h2><p>Coba kata pencarian atau kategori lain. Kelas baru akan muncul di sini setelah dipublikasikan.</p></section>
    @endforelse
</div>
@if($courses->hasPages())<div class="pagination-wrap">{{ $courses->links() }}</div>@endif
@endsection
