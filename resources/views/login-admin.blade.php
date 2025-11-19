@extends('layouts.main')
@section('title', 'Login Admin - PPDB V.2.0')

@section('content')
<section class="page-content container" style="text-align: center; padding-top: 100px;">
    <h2>👑 Akses Sistem PPDB</h2>
    <p class="subtitle">Akses khusus untuk Admin Dinas (Approval Global).</p>
    
    <div class="form-container" style="max-width: 450px;">
        
        <div class="login-tab-container">
            <div class="login-tab" onclick="window.location.href='{{ route('login') }}'">Siswa / Orang Tua</div>
            <div class="login-tab" onclick="window.location.href='{{ route('login.operator') }}'">Operator Sekolah</div>
            <div class="login-tab active" onclick="window.location.href='{{ route('login.admin') }}'">Admin Dinas</div>
        </div>

        <div id="admin-form" class="login-content active">
            <form onsubmit="handleLoginAdmin(event)">
                @csrf
                <label for="email-admin">Email Admin Dinas:</label>
                <input type="email" id="email-admin" name="identifier" required placeholder="Contoh: pusat@dinas.id">
                
                <label for="password-admin">Password:</label>
                <input type="password" id="password-admin" name="password" required placeholder="Masukkan Password">

                <button type="submit" class="btn-primary" style="width: 100%; border-radius: 6px;">LOGIN SEBAGAI ADMIN DINAS</button>
            </form>
        </div>
        
        <div id="login-message-admin" style="margin-top: 20px; color: #d32f2f; font-weight: 500;"></div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    const csrfToken = document.querySelector('input[name="_token"]').value;
    const loginUrl = "{{ route('login.admin.submit') }}";

    function handleLoginAdmin(event) {
        event.preventDefault();
        const messageDiv = document.getElementById('login-message-admin');
        
        const loginData = {
            identifier: document.getElementById('email-admin').value,
            password: document.getElementById('password-admin').value,
            role: 'admin', 
            _token: csrfToken
        };
        
        messageDiv.style.color = '#ff9800';
        messageDiv.innerHTML = 'Memproses login...';

        fetch(loginUrl, {
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