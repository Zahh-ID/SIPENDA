@extends('layouts.main')
@section('title', 'Daftar Sekolah - PPDB V.2.0')

@section('content')
<section class="page-content container mx-auto">
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold">🏫 Daftar Sekolah Tersedia</h2>
        <p class="text-lg text-gray-600 mt-2">Cari sekolah berdasarkan jenjang, daerah, atau nama.</p>
    </div>

    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            
            <div class="filter-group">
                <label for="jenjang-filter" class="block text-sm font-medium text-gray-700 mb-1">Jenjang Sekolah</label>
                <select id="jenjang-filter" class="form-control w-full">
                    <option value="">-- Pilih Jenjang --</option>
                    <option value="SD">Sekolah Dasar (SD)</option>
                    <option value="SMP">Sekolah Menengah Pertama (SMP)</option>
                    <option value="SMA">Sekolah Menengah Atas (SMA)</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="daerah-filter" class="block text-sm font-medium text-gray-700 mb-1">Asal Daerah (Kota/Kab.)</label>
                <input type="text" id="daerah-filter" placeholder="Contoh: Nganjuk" class="form-control w-full">
            </div>

            <div class="filter-group">
                <label for="nama-sekolah-filter" class="block text-sm font-medium text-gray-700 mb-1">Nama Sekolah</label>
                <input type="text" id="nama-sekolah-filter" placeholder="Contoh: SMAN 3 Surabaya" class="form-control w-full">
            </div>
        </div>
        
        <button id="btn-cari" class="btn-primary w-full mt-6">Cari Sekolah</button>
    </div>
    
    <div id="loading-status" style="display: none; text-align: center; font-weight: 600; color: #ff9800; margin-top: 20px;">
        <p>Memproses data sekolah...</p>
    </div>

    <div id="school-summary" class="mt-8"></div>

    <div id="school-details" style="margin-top: 40px;"></div>
    
</section>
@endsection

@section('scripts')

<script>

    window.schoolApiUrl = '{{ route('schools.api') }}';

</script>

@endsection


