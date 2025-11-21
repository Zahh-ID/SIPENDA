@extends('layouts.main')
@section('title', 'Dasbor Operator - PPDB V.2.0')

@section('styles')
    <style>
        .table-container td:nth-child(5) {
            padding: 8px;
        }
        
        .jadwal-control {
            display: flex; 
            gap: 5px;
            align-items: center; 
        }
        .btn-simpan-jadwal {
            padding: 3px 8px;
            font-size: 0.8em;
            background-color: #1a237e;
            color: white;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }
        .action-btns {
            display: flex;
            gap: 5px;
        }
        .action-btns button.btn-small {
            padding: 5px 8px;
            font-size: 0.9em;
            background-color: #4CAF50;
            color: white;
            border: none;
        }

        .action-btns button.bg-red-700 {
            background-color: #D32F2F; /* Tolak */
        }
        .admin-dashboard { padding: 40px 0; }
        .stat-cards { display: flex; gap: 20px; margin-bottom: 30px; }
        .stat-card { background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .stat-card p.count { font-size: 2em; font-weight: 700; color: #1a237e; }
        .table-container { overflow-x: auto; margin-top: 20px; }
        .table-container table { width: 100%; border-collapse: collapse; }
        .table-container th, .table-container td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        .status { padding: 5px 10px; border-radius: 4px; font-weight: 600; }
        .status.pending { background-color: #fff3e0; color: #e65100; }
        .status.diterima { background-color: #e8f5e9; color: #2e7d32; }
        .status.ditolak { background-color: #ffebee; color: #c62828; }
        .action-btns { display: flex; gap: 5px; }

        @media (max-width: 768px) {
            .stat-cards {
                flex-direction: column;
            }
            .table-container table thead {
                display: none;
            }
            .table-container table, .table-container table tbody, .table-container table tr, .table-container table td {
                display: block;
                width: 100%;
            }
            .table-container table tr {
                margin-bottom: 15px;
                border: 1px solid #ddd;
                border-radius: 5px;
                padding: 10px;
            }
            .table-container table td {
                text-align: right;
                padding-left: 50%;
                position: relative;
                border: none;
                padding-bottom: 5px;
            }
            .table-container table td::before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                text-align: left;
                font-weight: bold;
            }
            .action-btns, .jadwal-control {
                justify-content: flex-end;
            }
        }
    </style>
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
            <h4>Total Pendaftar di Sekolah Ini</h4>
            <p class="count" id="total-pendaftar">Memuat...</p>
        </div>
        <div class="stat-card">
            <h4>Perlu Diverifikasi</h4>
            <p class="count" id="perlu-verifikasi">Memuat...</p>
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
    window.globalCsrfToken = '{{ csrf_token() }}';
    window.operatorPendaftarApi = '{{ route('operator.pendaftar.api') }}';

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof fetchDataPendaftar === 'function') {
            // This function is defined in operator.js
            fetchDataPendaftar(true); // Passing true to indicate it's the initial load
        }
    });
    
    window.submitApproval = function(sekolahName) {
        if (!confirm(`Apakah Anda yakin mengajukan hasil seleksi ${sekolahName} ke Admin Dinas?`)) {
            return;
        }
         
        fetch(`/api/operator/ajukan/${encodeURIComponent(sekolahName)}`, {
             method: 'POST',
             headers: { 'X-CSRF-TOKEN': window.globalCsrfToken },
         })
         .then(response => response.json())
         .then(data => {
             alert(data.message);
         })
         .catch(error => {
             alert('Gagal mengajukan ke admin.');
             console.error('Error:', error);
         });
    }

</script>
<script src="{{ asset('operator.js') }}"></script>
@endsection