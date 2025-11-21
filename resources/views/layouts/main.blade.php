<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PPDB V.2.0')</title>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    @yield('styles')
</head>
<body>
    <header>
        <div class="header-main container">
            <div class="logo-area">
                <a href="{{ route('home') }}" class="logo-link">
                    <img src="{{ asset('assets/images/logo.svg') }}" alt="Logo PPDB" class="header-logo">
                    <div class="logo-text-wrapper">
                        <h1>SIPENDA</h1>
                        <p class="logo-subtitle">Sistem Informasi PPDB Jawa Timur</p>
                    </div>
                </a>
            </div>
            <nav>
                <button class="menu-toggle" aria-label="Toggle navigation">&#9776;</button>
                <ul class="nav-links">
                    <li><a href="{{ route('home') }}" class="{{ Request::is('/') ? 'active' : '' }}">Beranda</a></li>
                    <li><a href="{{ route('info.jalur') }}" class="{{ Request::is('info-jalur') ? 'active' : '' }}">Info Jalur</a></li>
                    <li><a href="{{ route('schools.index') }}" class="{{ Request::is('daftar-sekolah') ? 'active' : '' }}">Daftar Sekolah</a></li>
                    <li><a href="{{ route('jadwal') }}" class="{{ Request::is('jadwal') ? 'active' : '' }}">Jadwal</a></li>
                    @if(Auth::guard('student')->check() || Auth::guard('operator')->check() || Auth::guard('web')->check())
                        @if(Auth::guard('student')->check())
                            <li><a href="{{ route('student.dashboard') }}">Dasbor Siswa</a></li>
                        @elseif(Auth::guard('operator')->check())
                            <li><a href="{{ route('operator.dashboard') }}">Dasbor Operator</a></li>
                        @elseif(Auth::guard('web')->check())
                            <li><a href="{{ route('admin.dashboard') }}">Dasbor Admin</a></li>
                        @endif
                        <li><a href="#" onclick="document.getElementById('logout-form').submit();">Logout</a></li>
                    @else
                        <li><a href="{{ route('login') }}" class="btn-primary" style="padding: 5px 10px; margin-left: 10px; border-radius: 4px;">Login</a></li>
                    @endif
                </ul>
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 SIPENDA - Jawa Timur. Hak Cipta Masih Pending.</p>
            <div class="footer-links">
                <a href="#">FAQ</a> | <a href="#">Kontak Kami</a> | <a href="#">Kebijakan Privasi</a>
            </div>
        </div>
    </footer>
    
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    
    <script src="{{ asset('script.js') }}"></script>
    @yield('scripts')
</body>
</html>