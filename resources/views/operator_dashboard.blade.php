@extends('layouts.main')
@section('title', 'Dasbor Operator - SIPENDA')

@section('styles')
    {{-- Styles moved to public/style.css --}}
@endsection

@section('content')
<section class="admin-dashboard container">
    <h2>🏫 Dasbor Operator Sekolah</h2>
    <p class="subtitle">Kelola Pendaftar untuk **{{ $sekolahTanggungJawab }}**.</p>
    
    <div style="display: flex; justify-content: flex-end;"> 
        <button class="btn-primary" onclick="submitApproval('{{ $sekolahTanggungJawab }}')" style="margin-bottom: 20px;">
            AJUKAN HASIL SELEKSI KE ADMIN DINAS
        </button>
    </div>

    <div class="stat-cards">
        <div class="stat-card">
            <h4>Total Pendaftar</h4>
            <p class="count" id="total-pendaftar">Memuat...</p>
        </div>
        <div class="stat-card">
            <h4>Perlu Verifikasi</h4>
            <p class="count" id="perlu-verifikasi">Memuat...</p>
        </div>
        <div class="stat-card" style="flex: 2; border-bottom-color: var(--secondary-color);">
            <h4>Link Administrasi Sekolah</h4>
            <div style="margin-top: 15px; display: flex; gap: 10px; align-items: center;">
                <div style="flex: 1; position: relative;">
                    <i class="fas fa-link" style="position: absolute; left: 15px; top: 15px; color: #a0aec0;"></i>
                    <input type="url" id="link-administrasi" placeholder="https://forms.google.com/..." class="form-control" style="padding-left: 40px; border-radius: 50px;">
                </div>
                <button onclick="saveLinkAdministrasi()" class="btn-primary" style="padding: 12px 25px; border-radius: 50px;">
                    Simpan <i class="fas fa-save" style="margin-left: 5px;"></i>
                </button>
            </div>
            <p style="font-size: 0.9em; color: #718096; margin-top: 10px; margin-left: 10px;">
                <i class="fas fa-info-circle"></i> Link ini akan muncul di dasbor siswa yang dinyatakan <strong>DITERIMA</strong>.
            </p>
        </div>
    </div>

    <div class="table-container">
        <h3>Daftar Pendaftar Siswa</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>NISN</th>
                    <th>Jalur</th>
                    <th>Dokumen</th>
                    <th>Jadwal Test</th>
                    <th>Status Seleksi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="pendaftar-table-body">
                </tbody>
        </table>
    </div>
</section>
@endsection

@section('scripts')
<script>
    window.sekolahTanggungJawab = '{{ $sekolahTanggungJawab }}';
    window.operatorPendaftarApi = '{{ route('operator.pendaftar.api') }}';

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.fetchDataPendaftar === 'function') {
            window.fetchDataPendaftar(); 
        }
        fetchLinkAdministrasi();
    });

    function fetchLinkAdministrasi() {
        axios.get('{{ route('operator.link.get') }}')
             .then(res => {
                 if (res.data.link) {
                     document.getElementById('link-administrasi').value = res.data.link;
                 }
             })
             .catch(console.error);
    }
    
    window.saveLinkAdministrasi = function() {
        const link = document.getElementById('link-administrasi').value;
        if (!link) { alert('Mohon isi link terlebih dahulu.'); return; }
        
        axios.post('{{ route('operator.link.update') }}', { link: link })
             .then(res => alert(res.data.message))
             .catch(err => alert('Gagal menyimpan link. Pastikan format URL benar (http/https).'));
    }
    
    window.submitApproval = function(sekolahName) {
        if (!confirm(`Apakah Anda yakin mengajukan hasil seleksi ${sekolahName} ke Admin Dinas?`)) {
            return;
        }
         
        axios.post(`/api/operator/ajukan/${encodeURIComponent(sekolahName)}`)
         .then(response => {
             alert(response.data.message);
         })
         .catch(error => {
             alert('Gagal mengajukan ke admin.');
             console.error('Error:', error);
         });
    }

</script>
@endsection