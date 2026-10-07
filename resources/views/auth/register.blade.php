@extends('layouts.guest')

@section('title', 'Buat akun')

@section('content')
<main class="auth-layout">
    <section class="auth-showcase">
        <a href="{{ route('register') }}" class="brand brand-light">
            <span class="brand-mark"><i class="bi bi-layers-half"></i></span>
            <span>Kelas<span class="brand-accent">Qu</span></span>
        </a>
        <div class="showcase-content">
            <span class="eyebrow-pill"><span class="eyebrow-dot"></span> MULAI PERJALANANMU</span>
            <h1>Setiap ahli<br>pernah menjadi <em>pemula.</em></h1>
            <p>Buat akun KelasQu dan berikan dirimu ruang untuk mencoba, berkembang, dan melangkah lebih jauh.</p>
            <div class="showcase-note">
                <div class="note-symbol"><i class="bi bi-rocket-takeoff"></i></div>
                <div><strong>Awal yang baik dimulai di sini</strong><span>Belajar dengan ritmemu sendiri.</span></div>
            </div>
        </div>
        <div class="showcase-orbit orbit-one"></div>
        <div class="showcase-orbit orbit-two"></div>
        <div class="showcase-footer">PENGETAHUAN MEMBUKA PELUANG</div>
    </section>
    <section class="auth-panel">
        <div class="auth-form-wrap register-form-wrap">
            <div class="auth-mobile-brand">
                <a href="{{ route('register') }}" class="brand"><span class="brand-mark"><i class="bi bi-layers-half"></i></span><span>Kelas<span class="brand-accent">Qu</span></span></a>
            </div>
            <div class="form-heading">
                <span class="form-kicker">GABUNG BERSAMA KELASQU</span>
                <h2>Buat akun barumu</h2>
                <p>Isi data berikut untuk memulai perjalanan belajarmu.</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger auth-alert" role="alert"><i class="bi bi-exclamation-circle me-2"></i>{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('register.store') }}" class="auth-form">
                @csrf
                <div class="field-group">
                    <label for="name" class="form-label">Nama lengkap</label>
                    <div class="input-with-icon"><i class="bi bi-person"></i><input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Nama lengkapmu" autocomplete="name" maxlength="100" required autofocus></div>
                    @error('name')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="field-group">
                    <label for="email" class="form-label">Alamat email</label>
                    <div class="input-with-icon"><i class="bi bi-envelope"></i><input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="nama@email.com" autocomplete="email" required></div>
                    @error('email')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="field-group">
                    <label for="password" class="form-label">Kata sandi</label>
                    <div class="input-with-icon"><i class="bi bi-lock"></i><input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter" autocomplete="new-password" minlength="8" required><button type="button" class="password-toggle" aria-label="Tampilkan kata sandi" onclick="const field=document.getElementById('password'); field.type=field.type==='password'?'text':'password'"><i class="bi bi-eye"></i></button></div>
                    @error('password')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="field-group">
                    <label for="password_confirmation" class="form-label">Konfirmasi kata sandi</label>
                    <div class="input-with-icon"><i class="bi bi-shield-check"></i><input id="password_confirmation" name="password_confirmation" type="password" class="form-control" placeholder="Ulangi kata sandi" autocomplete="new-password" required></div>
                </div>
                <div class="form-check remember-check terms-check">
                    <input class="form-check-input" type="checkbox" id="terms" name="terms" value="1" {{ old('terms') ? 'checked' : '' }} required>
                    <label class="form-check-label" for="terms">Saya menyetujui <span>Syarat &amp; Ketentuan</span> KelasQu.</label>
                </div>
                <button class="btn btn-primary auth-submit" type="submit">Buat akun <i class="bi bi-arrow-right"></i></button>
            </form>
            <div class="auth-switch">Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></div>
        </div>
    </section>
</main>
@endsection
