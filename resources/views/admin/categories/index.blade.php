@extends('layouts.app')

@section('title', 'Kategori')
@section('page-heading', 'Kategori')

@section('content')
<div class="welcome-row"><div><span class="section-kicker">PENGELOMPOKAN KELAS</span><h1>Kategori kelas</h1><p>Kelompokkan kelas agar siswa lebih mudah menemukan topik yang sesuai.</p></div></div>
@if(session('status'))<div class="alert alert-success profile-alert">{{ session('status') }}</div>@endif
@if($errors->any())<div class="alert alert-danger profile-alert">{{ $errors->first() }}</div>@endif
<div class="category-layout">
    <section class="content-card profile-form-card">
        <div class="card-heading"><div><span class="section-kicker">KATEGORI BARU</span><h2>Tambah kategori</h2><p>Buat topik baru untuk katalog kelas.</p></div></div>
        <form method="POST" action="{{ route('admin.categories.store') }}" class="profile-form">
            @csrf
            <div class="field-group"><label class="form-label" for="category-name">Nama kategori</label><input id="category-name" name="name" class="form-control" maxlength="100" required></div>
            <div class="field-group"><label class="form-label" for="category-description">Deskripsi (opsional)</label><textarea id="category-description" name="description" rows="3" class="form-control"></textarea></div>
            <button class="btn btn-primary profile-save" type="submit"><i class="bi bi-plus-lg"></i> Tambah kategori</button>
        </form>
    </section>
    <section class="content-card">
        <div class="card-heading"><div><span class="section-kicker">TOPIK BELAJAR</span><h2>Semua kategori</h2><p>{{ $categories->total() }} kategori</p></div></div>
        @forelse($categories as $category)
            <details class="category-row">
                <summary><span class="stat-icon stat-icon-green"><i class="bi bi-collection"></i></span><span class="category-row-copy"><strong>{{ $category->name }}</strong><small>{{ $category->courses_count }} kelas</small></span><i class="bi bi-chevron-down"></i></summary>
                <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="profile-form category-edit-form">
                    @csrf @method('PUT')
                    <div class="field-group"><label class="form-label" for="category-name-{{ $category->id }}">Nama kategori</label><input id="category-name-{{ $category->id }}" name="name" class="form-control" value="{{ $category->name }}" required></div>
                    <div class="field-group"><label class="form-label" for="category-description-{{ $category->id }}">Deskripsi</label><textarea id="category-description-{{ $category->id }}" name="description" rows="2" class="form-control">{{ $category->description }}</textarea></div>
                    <button class="btn btn-primary profile-save" type="submit">Simpan</button>
                </form>
                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori ini? Kelasnya tidak ikut terhapus.')">@csrf @method('DELETE')<button class="btn btn-outline-danger btn-sm mt-2" type="submit">Hapus kategori</button></form>
            </details>
        @empty
            <div class="empty-state"><span class="empty-icon"><i class="bi bi-collection"></i></span><strong>Belum ada kategori</strong><p>Tambahkan kategori pertama menggunakan formulir.</p></div>
        @endforelse
        @if($categories->hasPages())<div class="pagination-wrap">{{ $categories->links() }}</div>@endif
    </section>
</div>
@endsection
