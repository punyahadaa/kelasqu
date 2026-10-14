@extends('layouts.app')

@section('title', 'Sertifikat')
@section('page-heading', 'Sertifikat')

@section('content')
<div class="welcome-row"><div><span class="section-kicker">PENCAPAIANMU</span><h1>Sertifikat belajar</h1><p>Sertifikat diterbitkan otomatis setelah semua materi kelas diselesaikan.</p></div></div>
<div class="certificate-grid">
    @forelse($certificates as $certificate)
        <a href="{{ route('user.certificate.show', $certificate) }}" class="content-card certificate-card">
            <span class="certificate-icon"><i class="bi bi-award"></i></span>
            <span class="section-kicker">KELASQU CERTIFICATE</span>
            <h2>{{ $certificate->enrollment->course->title }}</h2>
            <p>Diterbitkan {{ $certificate->issued_at->format('d M Y') }}</p>
            <span class="certificate-open">Lihat sertifikat <i class="bi bi-arrow-up-right"></i></span>
        </a>
    @empty
        <section class="content-card portal-empty-card catalog-empty"><div class="portal-empty-illustration"><i class="bi bi-award"></i></div><span class="section-kicker">PENCAPAIANMU</span><h2>Belum ada sertifikat</h2><p>Selesaikan semua materi di salah satu kelas untuk mendapatkan sertifikat.</p><a class="btn btn-primary profile-save" href="{{ route('user.classes') }}">Lihat kelas saya</a></section>
    @endforelse
</div>
@if($certificates->hasPages())<div class="pagination-wrap">{{ $certificates->links() }}</div>@endif
@endsection
