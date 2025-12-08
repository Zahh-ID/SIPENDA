@extends('layouts.main')
@section('title', 'Info Jalur & Kuota - PPDB V.2.0')

@section('content')
<section class="page-content container">
    <h2>📝 Kuota dan Jalur Pendaftaran</h2>
    <p class="subtitle">Rincian kuota minimal dan maksimal untuk setiap jenjang pendidikan, sesuai peraturan.</p>
    
    <div class="jalur-grid">
        <div class="jalur-card sd">
            <h3>SD (Sekolah Dasar)</h3>
            <ul>
                <li><span class="jalur-name">Zonasi:</span> <span class="jalur-quota">Min. 70%</span></li>
                <li><span class="jalur-name">Afirmasi:</span> <span class="jalur-quota">Min. 15%</span></li>
                <li><span class="jalur-name">Mutasi:</span> <span class="jalur-quota">Max. 5%</span></li>
                <li><span class="jalur-name">Prestasi:</span> <span class="jalur-quota">Sisa Kuota</span></li>
            </ul>
        </div>
        <div class="jalur-card smp">
            <h3>SMP (Sekolah Menengah Pertama)</h3>
            <ul>
                <li><span class="jalur-name">Zonasi:</span> <span class="jalur-quota">Min. 40%</span></li>
                <li><span class="jalur-name">Afirmasi:</span> <span class="jalur-quota">Min. 20%</span></li>
                <li><span class="jalur-name">Mutasi:</span> <span class="jalur-quota">Max. 5%</span></li>
                <li><span class="jalur-name">Prestasi:</span> <span class="jalur-quota">Min. 25%</span></li>
            </ul>
        </div>
        <div class="jalur-card sma">
            <h3>SMA (Sekolah Menengah Atas)</h3>
            <ul>
                <li><span class="jalur-name">Zonasi:</span> <span class="jalur-quota">Min. 30%</span></li>
                <li><span class="jalur-name">Afirmasi:</span> <span class="jalur-quota">Min. 30%</span></li>
                <li><span class="jalur-name">Mutasi:</span> <span class="jalur-quota">Max. 5%</span></li>
                <li><span class="jalur-name">Prestasi:</span> <span class="jalur-quota">Min. 30%</span></li>
            </ul>
        </div>
    </div>
    </div>
    
    <div class="requirements-section" style="margin-top: 50px; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
        <h3 style="color: #1a237e; border-bottom: 2px solid #ff9800; padding-bottom: 10px; display: inline-block;">📄 Persyaratan Dokumen</h3>
        <p>Calon peserta didik baru wajib melampirkan dokumen berikut saat pendaftaran:</p>
        <ul style="list-style: none; padding: 0;">
            <li style="margin-bottom: 10px; display: flex; align-items: center;">
                <span style="color: #2e7d32; margin-right: 10px;">✔</span> Scan Kartu Keluarga (KK) Asli
            </li>
            <li style="margin-bottom: 10px; display: flex; align-items: center;">
                <span style="color: #2e7d32; margin-right: 10px;">✔</span> Scan Akta Kelahiran Asli
            </li>
            <li style="margin-bottom: 10px; display: flex; align-items: center;">
                <span style="color: #2e7d32; margin-right: 10px;">✔</span> Scan Ijazah / Surat Keterangan Lulus (SKL)
            </li>
        </ul>
        <p class="note">Catatan: Dokumen diupload dalam format JPG/PNG/PDF dengan ukuran maksimal 2MB per file.</p>
    </div>

    <p class="note" style="margin-top: 20px;">Catatan: Sisa kuota di luar minimum/maksimum yang ditentukan bersifat fleksibel.</p>
</section>
@endsection