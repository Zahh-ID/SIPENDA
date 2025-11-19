@extends('layouts.main')
@section('title', 'Login Siswa - PPDB V.2.0')

@section('content')
<section class="page-content container" style="text-align: center; padding-top: 100px;">
    <h2>🔑 Akses Sistem PPDB</h2>
    <p class="subtitle">Masuk sebagai Calon Siswa / Orang Tua.</p>
    
    <div class="form-container" style="max-width: 450px;">
        
        <div class="login-tab-container">
            <div class="login-tab active" onclick="window.location.href='{{ route('login') }}'">Siswa / Orang Tua</div>
            <div class="login-tab" onclick="window.location.href='{{ route('login.operator') }}'">Operator Sekolah</div>
            <div class="login-tab" onclick="window.location.href='{{ route('login.admin') }}'">Admin Dinas</div>
        </div>

        <div class="login-content active">
            <form id="siswa-form" onsubmit="handleLogin(event, 'siswa')">
                @csrf
                <label for="nisn-siswa">Nomor Peserta / NISN:</label>
                <input type="text" id="nisn-siswa" name="identifier" required placeholder="Masukkan NISN atau Nomor Peserta">
                
                <label for="password-siswa">Password:</label>
                <input type="password" id="password-siswa" name="password" required placeholder="Password Anda">

                <button type="submit" class="btn-primary" style="width: 100%; border-radius: 6px; margin-bottom: 15px;">LOGIN SEBAGAI SISWA</button>
                
                <p style="font-size: 0.9em; margin-bottom: 15px;">Belum punya akun? <a href="{{ route('register.form') }}">Daftar Akun Baru di sini</a></p>
                <div id="login-message-siswa" style="margin-top: 10px; color: #d32f2f; font-weight: 500;"></div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    const csrfToken = document.querySelector('input[name="_token"]').value; 
    const loginUrl = "{{ route('login.siswa.submit') }}";

    function handleLogin(event, role) {
        event.preventDefault();
        const messageDiv = document.getElementById('login-message-siswa');
        
        const loginData = {
            identifier: document.getElementById('nisn-siswa').value,
            password: document.getElementById('password-siswa').value,
            role: role,
            _token: csrfToken
        };
        
        messageDiv.style.color = '#ff9800';
        messageDiv.innerHTML = 'Memproses login...';

        fetch(loginUrl, { // Panggil route Siswa
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(loginData)
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(errorData => {
                    throw new Error(errorData.message || 'Otentikasi Gagal.');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                messageDiv.style.color = '#2e7d32';
                messageDiv.innerHTML = data.message;
                
                // Simpan NISN di session storage untuk Dasbor Siswa
                sessionStorage.setItem('loggedInNisn', data.identifier);
                
                setTimeout(() => { window.location.href = data.redirect; }, 1000); 
            } else {
                messageDiv.style.color = '#d32f2f';
                messageDiv.innerHTML = data.message;
            }
        })
        .catch(error => {
            messageDiv.style.color = '#e65100';
            messageDiv.innerHTML = 'Terjadi kesalahan koneksi atau otentikasi gagal. Detail: ' + error.message;
            console.error('Error:', error);
        });
    }
</script>
@endsection