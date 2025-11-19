@extends('layouts.main')
@section('title', 'Dasbor Siswa - PPDB V.2.0')

@section('styles')
    <style>
        .dashboard-card { 
            background-color: #fff; 
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.1); 
            max-width: 700px;
            margin: 30px auto;
        }
        .data-pendaftar { 
            list-style: none; 
            padding: 0; 
            margin: 20px 0;
        }
        .data-pendaftar li {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px dashed #eee;
            font-size: 1.1em;
        }
        .data-pendaftar li span:first-child { 
            font-weight: 600; 
            color: #1a237e;
        }
        .status-badge { 
            padding: 5px 10px; 
            border-radius: 5px; 
            font-weight: 700;
        }
        .status-badge.approved, .status-badge.diterima { background-color: #e8f5e9; color: #2e7d32; }
        .status-badge.pending { background-color: #fff3e0; color: #e65100; }
        .status-badge.rejected, .status-badge.ditolak { background-color: #ffebee; color: #c62828; }

        .jadwal-info {
            margin-top: 30px;
            padding: 15px;
            border-left: 5px solid #ff9800;
            background-color: #fffaf0;
            font-weight: 600;
        }
    </style>
@endsection

@section('content')
<section class="page-content container">
    <h2 id="welcome-message">Selamat Datang, Calon Siswa!</h2>
    <p class="subtitle">Ini adalah halaman dasbor Anda. Di sini Anda dapat melihat status pendaftaran dan jadwal kegiatan.</p>

    <div class="dashboard-card">
        <h3>📄 Ringkasan Data Pendaftaran Anda</h3>
        <div id="student-data-container">
            <p>Memuat data Anda...</p>
        </div>
        
        <div id="jadwal-test-container">
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
        const studentDataContainer = document.getElementById('student-data-container');
        const welcomeMessage = document.getElementById('welcome-message');
        const jadwalTestContainer = document.getElementById('jadwal-test-container');

        const loggedInNisn = sessionStorage.getItem('loggedInNisn');
        const studentDataUrl = "{{ route('student.data.api') }}";
        
        if (!loggedInNisn) {
            studentDataContainer.innerHTML = '<p style="color: red;">Sesi login tidak ditemukan. Harap login ulang.</p>';
            return;
        }

        fetch(`${studentDataUrl}?nisn=${loggedInNisn}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const student = data.student;
                    welcomeMessage.textContent = `Selamat Datang, ${student.nama_lengkap}!`;
                    
                    const finalStatus = (student.status_approval === 'Approved' && student.status_seleksi === 'Diterima') ? 'Diterima' : student.status_approval;
                    const statusClass = finalStatus.toLowerCase().replace(/\s+/g, '');
                    
                    studentDataContainer.innerHTML = `
                        <ul class="data-pendaftar">
                            <li><span>Nama Lengkap</span> <span>${student.nama_lengkap}</span></li>
                            <li><span>NISN</span> <span>${student.nisn}</span></li>
                            <li><span>Sekolah Tujuan</span> <span>${student.sekolah_tujuan}</span></li>
                            <li><span>Jalur Pendaftaran</span> <span>${student.jalur_pendaftaran}</span></li>
                            <li>
                                <span>Status Final</span> 
                                <span><div class="status-badge ${statusClass}">${finalStatus}</div></span>
                            </li>
                        </ul>
                    `;
                    
                    if (student.jadwal_test) {
                        const dateFormatted = new Date(student.jadwal_test).toLocaleDateString('id-ID', {
                            weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
                        });
                        
                        jadwalTestContainer.innerHTML = `
                            <div class="jadwal-info">
                                <p>🗓️ **Jadwal Test Anda:** ${dateFormatted}</p>
                                <p>Harap hadir di ${student.sekolah_diterima || student.sekolah_tujuan} tepat waktu.</p>
                            </div>
                        `;
                    } else if (finalStatus === 'Diterima') {
                        jadwalTestContainer.innerHTML = `
                            <div class="jadwal-info" style="border-left-color: #2e7d32;">
                                <p>✅ **SELAMAT!** Anda DITERIMA. Jadwal Daftar Ulang segera diumumkan.</p>
                            </div>
                        `;
                    } else {
                         jadwalTestContainer.innerHTML = '';
                    }

                } else {
                    studentDataContainer.innerHTML = `<p style="color: red;">${data.message}</p>`;
                }
            })
            .catch(error => {
                studentDataContainer.innerHTML = `<p style="color: red;">Terjadi kesalahan saat memuat data: ${error.message}</p>`;
                console.error('Fetch Error:', error);
            });
    });

</script>
@endsection