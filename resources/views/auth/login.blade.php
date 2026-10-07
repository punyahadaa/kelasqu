@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')
<main class="auth-layout">
    <section class="auth-showcase">
        <a href="{{ route('login') }}" class="brand brand-light">
            <span class="brand-mark"><i class="bi bi-layers-half"></i></span>
            <span>Kelas<span class="brand-accent">Qu</span></span>
        </a>
        <div class="showcase-content">
            <span class="eyebrow-pill"><span class="eyebrow-dot"></span> BELAJAR DENGAN CARA BARU</span>
            <h1>Ruang untuk<br>bertumbuh <em>tanpa batas.</em></h1>
            <p>Temukan pengalaman belajar yang lebih personal. Mulai langkah baru dan capai potensi terbaikmu bersama KelasQu.</p>
            <div class="showcase-note">
                <div class="note-symbol"><i class="bi bi-lightbulb"></i></div>
                <div><strong>Belajar jadi lebih bermakna</strong><span>Dirancang untuk perjalanan belajarmu.</span></div>
            </div>
        </div>
        <div class="showcase-orbit orbit-one"></div>
        <div class="showcase-orbit orbit-two"></div>
        <div class="showcase-footer">PENGETAHUAN MEMBUKA PELUANG</div>
    </section>
    <section class="auth-panel">
        <div class="auth-form-wrap">
            <div class="auth-mobile-brand">
                <a href="{{ route('login') }}" class="brand"><span class="brand-mark"><i class="bi bi-layers-half"></i></span><span>Kelas<span class="brand-accent">Qu</span></span></a>
            </div>
            <div class="form-heading">
                <span class="form-kicker">SELAMAT DATANG KEMBALI</span>
                <h2>Senang melihatmu lagi</h2>
                <p>Masuk ke akunmu dan lanjutkan perjalanan belajar.</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger auth-alert" role="alert"><i class="bi bi-exclamation-circle me-2"></i>{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="auth-form">
                @csrf
                <div class="field-group">
                    <label for="email" class="form-label">Alamat email</label>
                    <div class="input-with-icon"><i class="bi bi-envelope"></i><input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="nama@email.com" autocomplete="email" required autofocus></div>
                    @error('email')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="field-group">
                    <div class="label-row"><label for="password" class="form-label">Kata sandi</label></div>
                    <div class="input-with-icon"><i class="bi bi-lock"></i><input id="password" name="password" type="password" class="form-control" placeholder="Masukkan kata sandi" autocomplete="current-password" required><button type="button" class="password-toggle" aria-label="Tampilkan kata sandi" onclick="const field=document.getElementById('password'); field.type=field.type==='password'?'text':'password'"><i class="bi bi-eye"></i></button></div>
                </div>
                <div class="form-check remember-check">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">Ingat saya</label>
                </div>
                <button class="btn btn-primary auth-submit" type="submit">Masuk ke KelasQu <i class="bi bi-arrow-right"></i></button>
            </form>
            <div class="auth-switch">Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></div>
            <div class="auth-legal">Dengan masuk, kamu menyetujui <span>ketentuan penggunaan KelasQu.</span></div>
        </div>
    </section>
</main>
@endsection
