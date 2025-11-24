@extends('layouts.main')
@section('title', 'Dasbor Admin Dinas - PPDB V.2.0')

@section('styles')
    <style>
        .admin-dashboard { padding: 40px 0; }
        .table-container { 
            overflow-x: auto; 
            margin-top: 20px; 
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .table-container { 
            overflow-x: auto; 
            margin-top: 20px; 
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 0;
        }

        .table-container h3 {
            padding: 15px 15px 10px 15px;
            margin-top: 0;
            color: #1a237e;
        }

        .table-container table { 
            width: 100%; 
            border-collapse: collapse; 
        }
        .table-container th, .table-container td { 
            padding: 12px 15px; 
            border: 1px solid #ddd; 
            text-align: left; 
            vertical-align: middle;
        }
        .table-container tr:last-child td {
            border-bottom: none;
        }
        .action-btns { 
            display: flex; 
            gap: 5px; 
            white-space: nowrap;
        }
        .action-btns .btn-small {
            padding: 5px 10px;
            font-size: 0.9em;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }
        .bg-blue-500 { background-color: #2196f3; color: white; } /* ACC */
        .bg-red-700 { background-color: #d32f2f; color: white; } /* Tolak Global */
        
        .status { padding: 5px 10px; border-radius: 4px; font-weight: 600; }
        .status.diterima { background-color: #e8f5e9; color: #2e7d32; }
        .status.ditolak { background-color: #ffebee; color: #c62828; }
        
        @media (max-width: 768px) {
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
            .action-btns {
                justify-content: flex-end;
            }
        }
    </style>
@endsection

@section('content')
<section class="admin-dashboard container">
    <h2>👑 Dasbor Admin Dinas (Approval Global)</h2>
    <p class="subtitle">Verifikasi dan setujui hasil seleksi yang diajukan oleh Operator Sekolah.</p>

    <div class="table-container" style="margin-bottom: 40px;">
        <h3>Manajemen Pengguna</h3>
        <div style="padding: 15px;">
            <a href="{{ route('admin.register.operator.form') }}" class="btn-primary" style="margin-right: 15px;">Register New Operator</a>
            <a href="{{ route('admin.register.admin.form') }}" class="btn-primary">Register New Admin</a>
        </div>
    </div>

    <div class="table-container">
        <h3>Permintaan Approval Siswa</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Sekolah Asal Pengajuan</th>
                    <th>Nama Siswa (NISN)</th>
                    <th>Status Seleksi Operator</th>
                    <th>Jadwal Test</th> 
                    <th>Aksi Admin Dinas</th>
                </tr>
            </thead>
            <tbody id="approval-table-body">
                </tbody>
        </table>
    </div>
</section>
@endsection

@section('scripts')
<script>
    const csrfToken = '{{ csrf_token() }}';

    function fetchPengajuan() {
        const tableBody = document.getElementById('approval-table-body');
        tableBody.innerHTML = '<tr><td colspan="6" style="text-align: center;">Memuat permintaan approval...</td></tr>';

        fetch('/api/admin/pengajuan')
            .then(response => response.json())
            .then(data => {
                tableBody.innerHTML = '';
                const pengajuanData = data.pengajuan || [];
                
                if (data.status === 'success' && pengajuanData.length > 0) {
                    pengajuanData.forEach((siswa, index) => {
                        const statusClass = siswa.status_seleksi.toLowerCase().replace(/\s+/g, '');
    const row = `
        <tr>
            <td data-label="ID">${siswa.id}</td>
            <td data-label="Sekolah Asal Pengajuan"><strong>${siswa.sekolah_tujuan}</strong></td>
            <td data-label="Nama Siswa (NISN)">${siswa.nama_lengkap} (${siswa.nisn})</td>
            <td data-label="Status Seleksi Operator"><span class="status ${statusClass}">${siswa.status_seleksi}</span></td>
            <td data-label="Jadwal Test">${siswa.jadwal_test || 'N/A'}</td>

            <td data-label="Aksi Admin Dinas" class="action-btns">
                <button onclick="updateApprovalAdmin(${siswa.id}, 'Approved')" class="btn-small bg-blue-500 hover:bg-blue-700">ACC (Setujui)</button>
                <button onclick="updateApprovalAdmin(${siswa.id}, 'Rejected')" class="btn-small bg-red-700 hover:bg-red-900">Tolak Global</button>
            </td>
        </tr>
    `;
                        tableBody.innerHTML += row;
                    });
                } else {
                    tableBody.innerHTML = '<tr><td colspan="6" style="text-align: center;">Tidak ada permintaan approval yang masuk.</td></tr>';
                }
            });
    }

    window.updateApprovalAdmin = function(studentId, newApprovalStatus) {
        if (!confirm(`Yakin ${newApprovalStatus} status siswa ID ${studentId}?`)) { return; }
        
        fetch('/api/admin/approval', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({
                student_id: studentId,
                status_approval: newApprovalStatus,
                _token: csrfToken
            })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message || 'Approval berhasil dilakukan.');
            fetchPengajuan(); // Refresh tabel
        })
        .catch(error => {
            alert('Gagal memperbarui approval.');
            console.error('Error:', error);
        });
    };

    fetchPengajuan();
</script>
@endsection