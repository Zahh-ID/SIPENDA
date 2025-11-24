@extends('layouts.main')
@section('title', 'Daftar Sekolah - PPDB V.2.0')

@section('content')
<section class="page-content container">
    <h2>🏫 Daftar Sekolah Tersedia</h2>
    <p class="subtitle">Pilih jenjang dan lokasi Anda, atau cari langsung berdasarkan nama sekolah.</p>
    
    <div class="form-container" style="max-width: 800px;">
        
        <div class="login-tab-container">
            <div class="login-tab active" data-tab="zonasi">Zonasi & Daerah</div>
            <div class="login-tab" data-tab="nama">Nama Sekolah</div>
        </div>
        
        <div id="zonasi-search" class="search-content active">
            <div class="filter-controls">
                <div class="filter-group">
                    <label for="jenjang-filter">Pilih Jenjang Sekolah:</label>
                    <select id="jenjang-filter" class="form-control">
                        <option value="">-- Jenjang --</option>
                        <option value="SD">Sekolah Dasar (SD)</option>
                        <option value="SMP">Sekolah Menengah Pertama (SMP)</option>
                        <option value="SMA">Sekolah Menengah Atas (SMA)</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="daerah-filter">Asal Daerah (Kota/Kab.):</label>
                    <input type="text" id="daerah-filter" placeholder="Contoh: Nganjuk" class="form-control">
                </div>
            </div>
            <button id="btn-cari-zonasi" class="btn-primary" style="width: 100%; margin-top: 20px;">Cari Berdasarkan Zonasi</button>
        </div>

        <div id="nama-search" class="search-content">
            <div class="filter-controls" style="flex-wrap: wrap;">
                <div class="filter-group" style="flex-basis: 65%;">
                    <label for="nama-sekolah-filter">Nama Sekolah (Wajib Jenjang):</label>
                    <input type="text" id="nama-sekolah-filter" placeholder="Contoh: SMAN 3 Surabaya" class="form-control">
                </div>
                <div class="filter-group" style="flex-basis: 30%;">
                    <label for="jenjang-nama-filter">Jenjang:</label>
                    <select id="jenjang-nama-filter" class="form-control">
                        <option value="">-- Jenjang --</option>
                        <option value="SD">SD</option>
                        <option value="SMP">SMP</option>
                        <option value="SMA">SMA</option>
                    </select>
                </div>
            </div>
            <button id="btn-cari-nama" class="btn-primary" style="width: 100%; margin-top: 20px;">Cari Berdasarkan Nama</button>
        </div>
    </div>
    
    <div id="loading-status" style="display: none; text-align: center; font-weight: 600; color: #ff9800; margin-top: 20px;">
        <p>Memproses data sekolah...</p>
    </div>

    <div id="school-summary"></div>

    <div id="school-details" style="margin-top: 40px;"></div>
    
</section>
@endsection

@section('scripts')
<script>
    window.schoolApiUrl = '{{ route('schools.api') }}';
</script>
@endsection


