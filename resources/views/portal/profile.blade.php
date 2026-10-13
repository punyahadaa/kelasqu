@extends('layouts.app')

@section('title', 'Profil')
@section('page-heading', 'Profil')

@section('content')
<div class="welcome-row">
    <div>
        <span class="section-kicker">AKUN KELASQU</span>
        <h1>Profil saya</h1>
        <p>Perbarui informasi akun dan kata sandimu.</p>
    </div>
</div>
@if(session('status'))
    <div class="alert alert-success profile-alert"><i class="bi bi-check-circle me-2"></i>{{ session('status') }}</div>
@endif
<div class="profile-edit-grid">
    <section class="content-card profile-summary-card">
        <div class="profile-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        <h2>{{ auth()->user()->name }}</h2>
        <p>{{ auth()->user()->email }}</p>
        <span class="profile-status"><span></span>{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Siswa KelasQu' }}</span>
        <div class="profile-divider"></div>
        <div class="profile-detail"><span>Bergabung sejak</span><strong>{{ auth()->user()->created_at->format('d M Y') }}</strong></div>
    </section>
    <section class="content-card profile-form-card">
        <div class="card-heading">
            <div><span class="section-kicker">INFORMASI PRIBADI</span><h2>Ubah profil</h2><p>Perbarui nama dan email akunmu.</p></div>
        </div>
        <form method="POST" action="{{ route('profile.update') }}" class="profile-form">
            @csrf
            @method('PUT')
            <div class="field-group">
                <label for="name" class="form-label">Nama lengkap</label>
                <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', auth()->user()->name) }}" maxlength="100" required>
                @error('name')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="field-group">
                <label for="email" class="form-label">Alamat email</label>
                <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', auth()->user()->email) }}" required>
                @error('email')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="profile-password-heading"><span class="section-kicker">KEAMANAN</span><p>Kosongkan jika tidak ingin mengganti kata sandi.</p></div>
            <div class="field-group">
                <label for="password" class="form-label">Kata sandi baru</label>
                <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password" minlength="8">
                @error('password')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="field-group">
                <label for="password_confirmation" class="form-label">Konfirmasi kata sandi baru</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password">
            </div>
            <button class="btn btn-primary profile-save" type="submit"><i class="bi bi-check2"></i> Simpan perubahan</button>
        </form>
    </section>
</div>
@endsection
