<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIPENDA')</title>
    <link rel="icon" href="{{ asset('assets/images/logo.svg') }}">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @yield('styles')
</head>
<body style="background-color: #f4f7f9;">

    <main>
        @yield('content')
    </main>

    @livewireScripts
    @yield('scripts')
</body>
</html>
