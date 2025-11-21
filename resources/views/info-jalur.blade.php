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
    <p class="note">Catatan: Sisa kuota di luar minimum/maksimum yang ditentukan bersifat fleksibel.</p>
</section>
@endsection