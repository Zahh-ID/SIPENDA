@extends('layouts.main')
@section('title', 'Jadwal PPDB - PPDB V.2.0')

@section('content')
<section class="page-content container">
    <h2>🗓️ Jadwal Pelaksanaan PPDB 2024/2025</h2>
    <p class="subtitle">Catat tanggal-tanggal penting agar tidak terlewat. Semua waktu dalam WIB.</p>
    
    <div class="timeline-container">
        <!-- 1. Sosialisasi (Left) -->
        <div class="timeline-item left">
            <div class="timeline-content">
                <div class="timeline-icon">📢</div>
                <div class="timeline-date">01-15 Mei</div>
                <h4 class="timeline-title">Sosialisasi & Pengumuman</h4>
                <p class="timeline-desc">Informasi detail jalur, kuota, dan persyaratan pendaftaran.</p>
                <span class="timeline-badge">Tahap 1</span>
            </div>
        </div>

        <!-- 2. Pendaftaran (Right) -->
        <div class="timeline-item right">
             <div class="timeline-content">
                <div class="timeline-icon">📝</div>
                <div class="timeline-date">20-25 Mei</div>
                <h4 class="timeline-title">Pendaftaran Online</h4>
                <p class="timeline-desc">Pengisian formulir pendaftaran, pemilihan sekolah, dan unggah dokumen.</p>
                <div style="font-size: 0.85em; color: #e53e3e; font-weight: 600; margin-top: 5px;">🕗 08:00 - 16:00 WIB</div>
                <span class="timeline-badge">Tahap 2</span>
            </div>
        </div>

        <!-- 3. Verifikasi (Left) -->
        <div class="timeline-item left">
             <div class="timeline-content">
                <div class="timeline-icon">🔍</div>
                <div class="timeline-date">26-30 Mei</div>
                <h4 class="timeline-title">Verifikasi Dokumen</h4>
                <p class="timeline-desc">Proses verifikasi berkas oleh operator sekolah dan dinas terkait.</p>
                <span class="timeline-badge">Tahap 3</span>
            </div>
        </div>

        <!-- 4. Pengumuman (Right) -->
        <div class="timeline-item right">
             <div class="timeline-content">
                <div class="timeline-icon">🎉</div>
                <div class="timeline-date">10 Jun</div>
                <h4 class="timeline-title">Pengumuman Hasil</h4>
                <p class="timeline-desc">Hasil seleksi dapat diakses secara online di halaman Hasil Seleksi.</p>
                <div style="font-size: 0.85em; color: #e53e3e; font-weight: 600; margin-top: 5px;">🕙 Pukul 10:00 WIB</div>
                <span class="timeline-badge">Tahap 4</span>
            </div>
        </div>

        <!-- 5. Daftar Ulang (Left) -->
        <div class="timeline-item left">
             <div class="timeline-content">
                <div class="timeline-icon">🏫</div>
                <div class="timeline-date">11-15 Jun</div>
                <h4 class="timeline-title">Daftar Ulang</h4>
                <p class="timeline-desc">Wajib dilakukan bagi peserta didik yang dinyatakan diterima.</p>
                <span class="timeline-badge">Tahap 5</span>
            </div>
        </div>
    </div>
</section>
@endsection