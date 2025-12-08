@extends('layouts.main')
@section('title', 'Kebijakan Privasi - PPDB V.2.0')

@section('content')
<section class="page-content container">
    <h2>Kebijakan Privasi</h2>
    <p class="subtitle">Komitmen kami untuk melindungi data pribadi Anda.</p>

    <div class="privacy-card">
        <div style="text-align: center;">
            <span class="last-updated">Terakhir diperbarui: 1 Desember 2025</span>
        </div>

        <div class="policy-section">
            <h3>1. Pendahuluan</h3>
            <p>Selamat datang di SIPENDA (Sistem Informasi PPDB Jawa Timur). Kami menghargai privasi Anda dan berkomitmen untuk melindungi data pribadi yang Anda bagikan kepada kami. Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi Anda.</p>
        </div>

        <div class="policy-section">
            <h3>2. Informasi yang Kami Kumpulkan</h3>
            <p>Kami mengumpulkan informasi yang Anda berikan secara langsung saat mendaftar, seperti:</p>
            <ul>
                <li>Nama lengkap, NISN, dan data kependudukan.</li>
                <li>Alamat email, nomor telepon, dan alamat rumah.</li>
                <li>Nilai rapor dan dokumen akademik lainnya.</li>
            </ul>
        </div>

        <div class="policy-section">
            <h3>3. Penggunaan Informasi</h3>
            <p>Informasi yang kami kumpulkan digunakan semata-mata untuk keperluan proses Penerimaan Peserta Didik Baru (PPDB), termasuk:</p>
            <ul>
                <li>Verifikasi data pendaftaran.</li>
                <li>Proses seleksi dan pemeringkatan.</li>
                <li>Komunikasi terkait status pendaftaran Anda.</li>
            </ul>
        </div>

        <div class="policy-section">
            <h3>4. Keamanan Data</h3>
            <p>Kami menerapkan langkah-langkah keamanan teknis dan organisasional yang sesuai untuk melindungi data pribadi Anda dari akses, penggunaan, atau pengungkapan yang tidak sah.</p>
        </div>

        <div class="policy-section">
            <h3>5. Berbagi Informasi</h3>
            <p>Kami tidak akan menjual atau menyewakan data pribadi Anda kepada pihak ketiga. Data Anda hanya akan dibagikan kepada instansi terkait (Dinas Pendidikan dan Sekolah Tujuan) untuk keperluan proses PPDB.</p>
        </div>

        <div class="policy-section">
            <h3>6. Hubungi Kami</h3>
            <p>Jika Anda memiliki pertanyaan tentang Kebijakan Privasi ini, silakan hubungi kami melalui halaman <a href="{{ route('contact') }}">Kontak Kami</a>.</p>
        </div>
    </div>
</section>
@endsection
