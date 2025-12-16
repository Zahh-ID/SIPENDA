@extends('layouts.main')
@section('title', 'Beranda - SIPENDA')

@section('scripts')

@endsection

@section('content')
<section id="home" class="hero full-height">
    <div class="video-overlay"></div>
    <video autoplay muted loop playsinline id="hero-video">
        <source src="{{ asset('assets/video/background.webm') }}" type="video/webm"> 
        Browser Anda tidak mendukung tag video.
    </video>
    <div class="hero-content">
        <h2>Selamat Datang di Sistem Pendaftaran Peserta Didik Baru 2024/2025</h2>
        <p>Akses cepat dan transparan untuk memilih sekolah di wilayah kami.</p>
        <br>
        <a href="{{ route('login') }}" class="btn-primary">MULAI PENDAFTARAN & LOGIN</a>
    </div>
</section>
@endsection