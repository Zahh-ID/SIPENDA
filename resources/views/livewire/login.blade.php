<div class="form-container" style="max-width: 450px;">
    <div class="login-tab-container">
        <div class="login-tab @if($loginType === 'siswa') active @endif" wire:click="setLoginType('siswa')">Siswa / Orang Tua</div>
        <div class="login-tab @if($loginType === 'operator') active @endif" wire:click="setLoginType('operator')">Operator Sekolah</div>
        <div class="login-tab @if($loginType === 'admin') active @endif" wire:click="setLoginType('admin')">Admin Dinas</div>
    </div>

    <div class="login-content active">
        <form wire:submit.prevent="login">
            @if ($loginType === 'siswa')
                <label for="nisn-siswa">Nomor Peserta / NISN:</label>
                <input type="text" id="nisn-siswa" wire:model="identifier" required placeholder="Masukkan NISN atau Nomor Peserta">
            @elseif ($loginType === 'operator')
                <label for="username-operator">Username Operator (Sekolah):</label>
                <input type="text" id="username-operator" wire:model="identifier" required placeholder="Contoh: op_nganjuk_sd1">
            @elseif ($loginType === 'admin')
                <label for="email-admin">Email Admin Dinas:</label>
                <input type="email" id="email-admin" wire:model="identifier" required placeholder="Contoh: pusat@dinas.id">
            @endif

            <label for="password">Password:</label>
            <input type="password" id="password" wire:model="password" required placeholder="Masukkan Password">

            @if ($error)
                <div style="margin-top: 10px; color: #d32f2f; font-weight: 500;">{{ $error }}</div>
            @endif

            <button type="submit" class="btn-primary" style="width: 100%; border-radius: 6px; margin-top: 15px;">
                LOGIN SEBAGAI {{ strtoupper($loginType) }}
            </button>

            @if ($loginType === 'siswa')
                <p style="font-size: 0.9em; margin-top: 15px; margin-bottom: 15px;">Belum punya akun? <a href="{{ route('register.form') }}">Daftar Akun Baru di sini</a></p>
            @endif
        </form>
    </div>
</div>

