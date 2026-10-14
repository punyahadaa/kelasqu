@extends('layouts.app')

@section('title', $lesson->title)
@section('page-heading', 'Belajar')

@section('content')
<div class="lesson-page">
    <a href="{{ route('user.course.show', $course) }}" class="course-back lesson-back"><i class="bi bi-arrow-left"></i> {{ $course->title }}</a>
    <div class="lesson-layout">
        <article class="content-card lesson-content-card">
            <span class="section-kicker">MATERI {{ str_pad($lesson->position, 2, '0', STR_PAD_LEFT) }}</span>
            <h1>{{ $lesson->title }}</h1>
            @if($lesson->video_url)<a class="lesson-video-link" href="{{ $lesson->video_url }}" target="_blank" rel="noopener noreferrer"><i class="bi bi-play-circle"></i> Buka video pembelajaran <i class="bi bi-box-arrow-up-right"></i></a>@endif
            <div class="lesson-content-text">{!! nl2br(e($lesson->content)) !!}</div>
            @if(in_array($lesson->id, $completedIds))
                <div class="lesson-done-message"><i class="bi bi-check-circle"></i> Materi ini sudah selesai.</div>
            @else
                <form method="POST" action="{{ route('user.lesson.complete', [$course, $lesson]) }}">@csrf<button type="submit" class="btn btn-primary profile-save"><i class="bi bi-check2-circle"></i> Tandai selesai</button></form>
            @endif
        </article>
        <aside class="content-card lesson-outline">
            <span class="section-kicker">DAFTAR MATERI</span>
            <h2>{{ $course->title }}</h2>
            @foreach($course->lessons as $courseLesson)
                <a class="lesson-outline-row {{ $courseLesson->id === $lesson->id ? 'current' : '' }}" href="{{ route('user.lesson.show', [$course, $courseLesson]) }}">
                    <span class="journey-number">{{ str_pad($courseLesson->position, 2, '0', STR_PAD_LEFT) }}</span><span>{{ $courseLesson->title }}</span>
                    @if(in_array($courseLesson->id, $completedIds))<i class="bi bi-check-circle-fill"></i>@endif
                </a>
            @endforeach
        </aside>
    </div>
</div>
@endsection
