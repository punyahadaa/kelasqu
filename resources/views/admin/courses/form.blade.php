@extends('layouts.app')

@section('title', $course->exists ? 'Kelola kelas' : 'Buat kelas')
@section('page-heading', 'Kelola kelas')

@section('content')
<div class="welcome-row">
    <div><span class="section-kicker">MANAJEMEN PEMBELAJARAN</span><h1>{{ $course->exists ? 'Kelola kelas' : 'Buat kelas baru' }}</h1><p>Isi informasi kelas. Materi dapat ditambahkan setelah kelas disimpan.</p></div>
    <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-soft"><i class="bi bi-arrow-left"></i> Semua kelas</a>
</div>
@if(session('status'))<div class="alert alert-success profile-alert">{{ session('status') }}</div>@endif
@if($errors->any())<div class="alert alert-danger profile-alert">{{ $errors->first() }}</div>@endif
<section class="content-card profile-form-card">
    <form method="POST" action="{{ $course->exists ? route('admin.courses.update', $course) : route('admin.courses.store') }}" class="profile-form">
        @csrf
        @if($course->exists)@method('PUT')@endif
        <div class="course-form-grid">
            <div class="field-group"><label class="form-label" for="title">Nama kelas</label><input id="title" name="title" class="form-control" value="{{ old('title', $course->title) }}" maxlength="160" required>@error('title')<div class="field-error">{{ $message }}</div>@enderror</div>
            <div class="field-group"><label class="form-label" for="category_id">Kategori</label><select id="category_id" name="category_id" class="form-select"><option value="">Pilih kategori</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id', $course->category_id) == $category->id)>{{ $category->name }}</option>@endforeach</select></div>
            <div class="field-group"><label class="form-label" for="level">Tingkat</label><select id="level" name="level" class="form-select">@foreach(['Pemula', 'Menengah', 'Lanjutan', 'Semua Tingkat'] as $level)<option value="{{ $level }}" @selected(old('level', $course->level) === $level)>{{ $level }}</option>@endforeach</select></div>
            <div class="field-group"><label class="form-label" for="duration_minutes">Durasi perkiraan (menit)</label><input id="duration_minutes" name="duration_minutes" type="number" min="0" max="100000" class="form-control" value="{{ old('duration_minutes', $course->duration_minutes ?? 0) }}" required></div>
            <div class="field-group"><label class="form-label" for="status">Status publikasi</label><select id="status" name="status" class="form-select"><option value="draft" @selected(old('status', $course->status) === 'draft')>Draft</option><option value="published" @selected(old('status', $course->status) === 'published')>Publikasikan</option></select>@if(! $course->exists)<small class="form-hint">Simpan sebagai draft, tambahkan materi, lalu publikasikan.</small>@endif</div>
        </div>
        <div class="field-group"><label class="form-label" for="description">Deskripsi kelas</label><textarea id="description" name="description" rows="5" class="form-control" required>{{ old('description', $course->description) }}</textarea></div>
        <button class="btn btn-primary profile-save" type="submit"><i class="bi bi-check2"></i> {{ $course->exists ? 'Simpan informasi' : 'Buat draft kelas' }}</button>
    </form>
</section>

@if($course->exists)
    <section class="content-card lesson-admin-card">
        <div class="card-heading"><div><span class="section-kicker">KURIKULUM</span><h2>Materi kelas</h2><p>Materi disusun mengikuti urutan penambahannya.</p></div></div>
        @forelse($lessons as $lesson)
            <details class="lesson-admin-item">
                <summary><span class="journey-number">{{ str_pad($lesson->position, 2, '0', STR_PAD_LEFT) }}</span><strong>{{ $lesson->title }}</strong><i class="bi bi-chevron-down"></i></summary>
                <form method="POST" action="{{ route('admin.lessons.update', [$course, $lesson]) }}" class="profile-form lesson-edit-form">
                    @csrf @method('PUT')
                    <div class="field-group"><label class="form-label" for="lesson-title-{{ $lesson->id }}">Judul materi</label><input id="lesson-title-{{ $lesson->id }}" name="title" class="form-control" value="{{ $lesson->title }}" required></div>
                    <div class="field-group"><label class="form-label" for="lesson-content-{{ $lesson->id }}">Isi materi</label><textarea id="lesson-content-{{ $lesson->id }}" name="content" rows="5" class="form-control" required>{{ $lesson->content }}</textarea></div>
                    <div class="field-group"><label class="form-label" for="lesson-video-{{ $lesson->id }}">Tautan video (opsional)</label><input id="lesson-video-{{ $lesson->id }}" name="video_url" type="url" class="form-control" value="{{ $lesson->video_url }}"></div>
                    <button class="btn btn-primary profile-save" type="submit">Simpan materi</button>
                </form>
                <form method="POST" action="{{ route('admin.lessons.destroy', [$course, $lesson]) }}" onsubmit="return confirm('Hapus materi ini?')">@csrf @method('DELETE')<button class="btn btn-outline-danger btn-sm mt-2" type="submit">Hapus materi</button></form>
            </details>
        @empty
            <div class="empty-state"><span class="empty-icon"><i class="bi bi-journal-text"></i></span><strong>Belum ada materi</strong><p>Tambahkan minimal satu materi sebelum kelas dipublikasikan.</p></div>
        @endforelse
        <details class="add-lesson-details">
            <summary><i class="bi bi-plus-circle"></i> Tambahkan materi baru</summary>
            <form method="POST" action="{{ route('admin.lessons.store', $course) }}" class="profile-form lesson-edit-form">
                @csrf
                <div class="field-group"><label class="form-label" for="new-lesson-title">Judul materi</label><input id="new-lesson-title" name="title" class="form-control" maxlength="160" required></div>
                <div class="field-group"><label class="form-label" for="new-lesson-content">Isi materi</label><textarea id="new-lesson-content" name="content" rows="6" class="form-control" required></textarea><small class="form-hint">Teks materi akan ditampilkan kepada siswa di halaman pembelajaran.</small></div>
                <div class="field-group"><label class="form-label" for="new-lesson-video">Tautan video (opsional)</label><input id="new-lesson-video" name="video_url" type="url" class="form-control" placeholder="https://..."></div>
                <button class="btn btn-primary profile-save" type="submit"><i class="bi bi-plus-lg"></i> Tambahkan materi</button>
            </form>
        </details>
    </section>
    <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" class="delete-course-form" onsubmit="return confirm('Hapus kelas ini beserta pendaftaran dan progres siswa?')">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-outline-danger btn-sm">Hapus kelas</button>
        <span>Menghapus kelas juga menghapus materinya dan pendaftaran terkait.</span>
    </form>
@endif
@endsection
