@extends('layouts.app')

@section('title', $pageTitle)
@section('page-heading', $pageTitle)

@section('content')
<div class="welcome-row">
    <div>
        <span class="section-kicker">{{ $eyebrow }}</span>
        <h1>{{ $title }}</h1>
        <p>{{ $description }}</p>
    </div>
</div>

<section class="content-card portal-empty-card">
    <div class="portal-empty-illustration"><i class="bi {{ $icon }}"></i></div>
    <span class="section-kicker">KELASQU</span>
    <h2>{{ $cardTitle }}</h2>
    <p>{{ $cardDescription }}</p>
    @if(auth()->user()->role === 'user')
        <a href="{{ route('dashboard') }}" class="btn btn-outline-soft">Kembali ke dashboard <i class="bi bi-arrow-right"></i></a>
    @endif
</section>
@endsection
