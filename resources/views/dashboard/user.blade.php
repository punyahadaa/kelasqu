@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-heading', 'Dashboard')

@section('content')
<div class="welcome-row">
    <div>
        <span class="section-kicker">RUANG BELAJARMU</span>
        <h1>Halo, {{ auth()->user()->name }} <span class="wave">✦</span></h1>
        <p>Senang kamu ada di sini. Setiap perjalanan hebat dimulai dari satu langkah.</p>
    </div>
    <div class="today-date"><i class="bi bi-calendar3"></i>{{ now()->translatedFormat('l, d F Y') }}</div>
</div>

<section class="learner-hero">
    <div class="learner-hero-content">
        <span class="hero-label"><i class="bi bi-stars"></i> BELAJAR DENGAN RITMEMU</span>
        <h2>Masa depanmu dimulai dari apa yang kamu pelajari hari ini.</h2>
        <p>KelasQu adalah ruang untuk menambah wawasan, mengeksplorasi minat, dan terus bertumbuh. Siap untuk memulai?</p>
        <div class="hero-member"><span class="member-check"><i class="bi bi-check2"></i></span> Akun belajarmu sudah aktif</div>
    </div>
    <div class="hero-art" aria-hidden="true">
        <div class="art-sun"></div>
        <div class="art-book book-back"></div>
        <div class="art-book book-front"><i class="bi bi-journal-bookmark"></i></div>
        <div class="art-spark spark-a">✦</div><div class="art-spark spark-b">✧</div>
    </div>
</section>

<div class="learner-grid">
    <section class="content-card journey-card">
        <div class="card-heading">
            <div><span class="section-kicker">LANGKAH PERTAMAMU</span><h2>Mulai perjalanan belajar</h2><p>Beberapa hal sederhana untuk memaksimalkan pengalamanmu.</p></div>
        </div>
        <div class="journey-list">
            <div class="journey-item"><span class="journey-number">01</span><div><strong>Kenali tujuan belajarmu</strong><p>Pikirkan keterampilan atau wawasan baru yang ingin kamu kuasai.</p></div><i class="bi bi-compass journey-symbol"></i></div>
            <div class="journey-item"><span class="journey-number">02</span><div><strong>Jelajahi dengan rasa ingin tahu</strong><p>Belajar adalah proses. Nikmati setiap hal baru yang kamu temukan.</p></div><i class="bi bi-lightbulb journey-symbol"></i></div>
            <div class="journey-item"><span class="journey-number">03</span><div><strong>Rayakan kemajuan kecil</strong><p>Konsistensi membawamu lebih jauh daripada kesempurnaan.</p></div><i class="bi bi-trophy journey-symbol"></i></div>
        </div>
    </section>
    <aside class="content-card profile-card">
        <span class="section-kicker">AKUN BELAJARMU</span>
        <div class="profile-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        <h2>{{ auth()->user()->name }}</h2>
        <p>{{ auth()->user()->email }}</p>
        <div class="profile-divider"></div>
        <div class="profile-detail"><span>Peran akun</span><strong>Siswa KelasQu</strong></div>
        <div class="profile-detail"><span>Bergabung sejak</span><strong>{{ auth()->user()->created_at->format('d M Y') }}</strong></div>
        <div class="profile-status"><span></span> Akun aktif</div>
    </aside>
</div>
@endsection
