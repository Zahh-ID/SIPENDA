@extends('layouts.auth')
@section('title', 'Login - PPDB V.2.0')

@section('content')
<section class="page-content container auth-page">
    <div class="logo-area auth-logo-area">
        <a href="{{ route('home') }}" class="logo-link">
            <img src="{{ asset('assets/images/logo.svg') }}" alt="Logo PPDB" class="header-logo">
            <div class="logo-text-wrapper">
                <h1>SIPENDA</h1>
                <p class="logo-subtitle">Sistem Informasi PPDB Jawa Timur</p>
            </div>
        </a>
    </div>
    
    @livewire('login')

</section>
@endsection
