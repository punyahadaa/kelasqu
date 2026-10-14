@extends('layouts.guest')

@section('title', 'Atur ulang kata sandi')

@section('content')
<main class="auth-layout">
    <section class="auth-showcase">
        <a href="{{ route('login') }}" class="brand brand-light"><span class="brand-mark"><i class="bi bi-layers-half"></i></span><span>Kelas<span class="brand-accent">Qu</span></span></a>
        <div class="showcase-content"><span class="eyebrow-pill"><span class="eyebrow-dot"></span> PEMULIHAN AKUN</span><h1>Buat kata sandi<br><em>yang baru.</em></h1><p>Pilih kata sandi kuat yang belum pernah kamu gunakan sebelumnya.</p></div>
        <div class="showcase-orbit orbit-one"></div><div class="showcase-orbit orbit-two"></div>
        <div class="showcase-footer">PENGETAHUAN MEMBUKA PELUANG</div>
    </section>
    <section class="auth-panel">
        <div class="auth-form-wrap">
            <div class="auth-mobile-brand"><a href="{{ route('login') }}" class="brand"><span class="brand-mark"><i class="bi bi-layers-half"></i></span><span>Kelas<span class="brand-accent">Qu</span></span></a></div>
            <div class="form-heading"><span class="form-kicker">KATA SANDI BARU</span><h2>Atur ulang kata sandi</h2><p>Gunakan minimal 8 karakter dan konfirmasi kata sandi barumu.</p></div>
            @if($errors->any())<div class="alert alert-danger auth-alert">{{ $errors->first() }}</div>@endif
            <form method="POST" action="{{ route('password.update') }}" class="auth-form">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="field-group"><label for="email" class="form-label">Alamat email</label><div class="input-with-icon"><i class="bi bi-envelope"></i><input id="email" name="email" type="email" class="form-control" value="{{ old('email', $email) }}" autocomplete="email" required autofocus></div></div>
                <div class="field-group"><label for="password" class="form-label">Kata sandi baru</label><div class="input-with-icon"><i class="bi bi-lock"></i><input id="password" name="password" type="password" class="form-control" autocomplete="new-password" minlength="8" required></div>@error('password')<div class="field-error">{{ $message }}</div>@enderror</div>
                <div class="field-group"><label for="password_confirmation" class="form-label">Konfirmasi kata sandi</label><div class="input-with-icon"><i class="bi bi-shield-check"></i><input id="password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" minlength="8" required></div></div>
                <button class="btn btn-primary auth-submit" type="submit">Simpan kata sandi baru <i class="bi bi-arrow-right"></i></button>
            </form>
        </div>
    </section>
</main>
@endsection
