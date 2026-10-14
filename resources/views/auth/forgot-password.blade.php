@extends('layouts.guest')

@section('title', 'Lupa kata sandi')

@section('content')
<main class="auth-layout">
    <section class="auth-showcase">
        <a href="{{ route('login') }}" class="brand brand-light"><span class="brand-mark"><i class="bi bi-layers-half"></i></span><span>Kelas<span class="brand-accent">Qu</span></span></a>
        <div class="showcase-content">
            <span class="eyebrow-pill"><span class="eyebrow-dot"></span> AKUNMU TETAP AMAN</span>
            <h1>Satu langkah<br>untuk <em>kembali.</em></h1>
            <p>Masukkan email yang terhubung dengan akunmu. Kami akan mengirimkan tautan untuk membuat kata sandi baru.</p>
        </div>
        <div class="showcase-orbit orbit-one"></div><div class="showcase-orbit orbit-two"></div>
        <div class="showcase-footer">PENGETAHUAN MEMBUKA PELUANG</div>
    </section>
    <section class="auth-panel">
        <div class="auth-form-wrap">
            <div class="auth-mobile-brand"><a href="{{ route('login') }}" class="brand"><span class="brand-mark"><i class="bi bi-layers-half"></i></span><span>Kelas<span class="brand-accent">Qu</span></span></a></div>
            <div class="form-heading"><span class="form-kicker">PEMULIHAN AKUN</span><h2>Lupa kata sandi?</h2><p>Kami akan mengirim tautan reset ke alamat emailmu.</p></div>
            @if(session('status'))<div class="alert alert-success auth-alert">{{ session('status') }}</div>@endif
            @if($errors->any())<div class="alert alert-danger auth-alert">{{ $errors->first() }}</div>@endif
            <form method="POST" action="{{ route('password.email') }}" class="auth-form">
                @csrf
                <div class="field-group"><label for="email" class="form-label">Alamat email</label><div class="input-with-icon"><i class="bi bi-envelope"></i><input id="email" name="email" type="email" class="form-control" value="{{ old('email') }}" placeholder="nama@email.com" autocomplete="email" required autofocus></div></div>
                <button class="btn btn-primary auth-submit" type="submit">Kirim tautan reset <i class="bi bi-arrow-right"></i></button>
            </form>
            <div class="auth-switch"><a href="{{ route('login') }}"><i class="bi bi-arrow-left"></i> Kembali ke login</a></div>
        </div>
    </section>
</main>
@endsection
