@extends('layouts.app')

@section('title', 'Kelas')
@section('page-heading', 'Kelas')

@section('content')
<div class="welcome-row">
    <div><span class="section-kicker">MANAJEMEN PEMBELAJARAN</span><h1>Kelola kelas</h1><p>Buat kelas, susun materinya, lalu publikasikan untuk siswa.</p></div>
    <a href="{{ route('admin.courses.create') }}" class="btn btn-primary profile-save"><i class="bi bi-plus-lg"></i> Buat kelas</a>
</div>
@if(session('status'))<div class="alert alert-success profile-alert">{{ session('status') }}</div>@endif
<section class="content-card">
    <div class="card-heading"><div><span class="section-kicker">KATALOG KELAS</span><h2>Semua kelas</h2><p>{{ $courses->total() }} kelas tersedia</p></div></div>
    @if($courses->isEmpty())
        <div class="empty-state"><span class="empty-icon"><i class="bi bi-easel2"></i></span><strong>Belum ada kelas</strong><p>Buat kelas pertama lalu tambahkan materi sebelum dipublikasikan.</p></div>
    @else
        <div class="table-responsive"><table class="table user-table align-middle mb-0">
            <thead><tr><th>NAMA KELAS</th><th>KATEGORI</th><th>MATERI</th><th>SISWA</th><th>STATUS</th><th>AKSI</th></tr></thead>
            <tbody>@foreach($courses as $course)
                <tr>
                    <td><strong>{{ $course->title }}</strong><div class="muted-cell">{{ $course->level }}</div></td>
                    <td class="muted-cell">{{ $course->category?->name ?? 'Tanpa kategori' }}</td>
                    <td class="muted-cell">{{ $course->lessons_count }}</td>
                    <td class="muted-cell">{{ $course->enrollments_count }}</td>
                    <td><span class="status-badge"><span style="background:{{ $course->status === 'published' ? '#7eaa64' : '#d0a64c' }}"></span>{{ $course->status === 'published' ? 'Publik' : 'Draft' }}</span></td>
                    <td><a class="btn btn-outline-soft" href="{{ route('admin.courses.edit', $course) }}">Kelola <i class="bi bi-arrow-right"></i></a></td>
                </tr>
            @endforeach</tbody>
        </table></div>
        @if($courses->hasPages())<div class="pagination-wrap">{{ $courses->links() }}</div>@endif
    @endif
</section>
@endsection
