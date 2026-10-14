@extends('layouts.guest')

@section('title', 'Verifikasi email')

@section('content')
<main class="auth-layout">
    <section class="auth-showcase">
        <a href="{{ route('dashboard') }}" class="brand brand-light"><span class="brand-mark"><i class="bi bi-layers-half"></i></span><span>Kelas<span class="brand-accent">Qu</span></span></a>
        <div class="showcase-content">
            <span class="eyebrow-pill"><span class="eyebrow-dot"></span> SATU LANGKAH LAGI</span>
            <h1>Pastikan akun<br>belajarmu <em>aman.</em></h1>
            <p>Verifikasi alamat email agar kamu dapat mengakses kelas, menyimpan progres, dan menerima sertifikat KelasQu.</p>
        </div>
        <div class="showcase-orbit orbit-one"></div><div class="showcase-orbit orbit-two"></div>
        <div class="showcase-footer">PENGETAHUAN MEMBUKA PELUANG</div>
    </section>
    <section class="auth-panel">
        <div class="auth-form-wrap">
            <div class="auth-mobile-brand"><a href="{{ route('login') }}" class="brand"><span class="brand-mark"><i class="bi bi-layers-half"></i></span><span>Kelas<span class="brand-accent">Qu</span></span></a></div>
            <div class="form-heading"><span class="form-kicker">VERIFIKASI AKUN</span><h2>Periksa emailmu</h2><p>Kami mengirim tautan verifikasi ke alamat email yang kamu daftarkan.</p></div>
            @if(session('status'))<div class="alert alert-success auth-alert">{{ session('status') }}</div>@endif
            @if($errors->any())<div class="alert alert-danger auth-alert">{{ $errors->first() }}</div>@endif
            <div class="verification-address"><i class="bi bi-envelope-check"></i><div><strong>{{ auth()->user()->email }}</strong><span>Periksa juga folder spam jika email belum muncul.</span></div></div>
            <form method="POST" action="{{ route('verification.send') }}" class="auth-form verification-form">
                @csrf
                <button class="btn btn-primary auth-submit" type="submit">Kirim ulang tautan <i class="bi bi-arrow-clockwise"></i></button>
            </form>
            <form method="POST" action="{{ route('logout') }}" class="auth-switch">@csrf<button type="submit" class="verification-logout">Keluar dari akun</button></form>
        </div>
    </section>
</main>
@endsection
