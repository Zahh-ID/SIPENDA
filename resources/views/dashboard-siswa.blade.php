@extends('layouts.main')
@section('title', 'Dasbor Siswa - SIPENDA')

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

        <div id="change-school-section" style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px; display: none;">
            <h4 style="color: #1a237e;">🏫 Ganti Sekolah Tujuan</h4>
            <p style="font-size: 0.9em; color: #666;">Anda dapat mengganti sekolah tujuan selama status seleksi masih <strong>Pending</strong>.</p>
            
            <div style="margin-top: 15px;">
                <p>Klik tombol di bawah ini untuk mencari dan memilih sekolah baru.</p>
                <a href="{{ route('schools.index') }}" class="btn-primary" style="display: inline-block; padding: 10px 20px; font-size: 0.9em; text-decoration: none; color: white;">Cari & Ganti Sekolah</a>
            </div>
            <p id="change-school-message" style="margin-top: 10px; font-size: 0.9em;"></p>
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

        const loggedInNisn = "{{ Auth::guard('student')->user()->nisn }}";
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
                    
                    if (finalStatus === 'Diterima') {
                        let adminLinkHtml = '';
                        if (student.link_administrasi) {
                             adminLinkHtml = `
                                <div style="margin-top: 15px;">
                                    <p>Silakan klik tombol di bawah untuk melanjutkan proses administrasi sekolah:</p>
                                    <a href="${student.link_administrasi}" target="_blank" class="btn-primary" style="display: inline-block; padding: 10px 20px; color: white; text-decoration: none; border-radius: 5px;">
                                        📄 Lanjut Administrasi Sekolah
                                    </a>
                                </div>
                             `;
                        }

                        jadwalTestContainer.innerHTML = `
                            <div class="jadwal-info" style="border-left-color: #2e7d32; background-color: #e8f5e9;">
                                <p>✅ **SELAMAT!** Anda DITERIMA di <strong>${student.sekolah_tujuan}</strong>.</p>
                                ${adminLinkHtml}
                            </div>
                        `;
                    } else if (finalStatus === 'Ditolak' || student.status_seleksi === 'Ditolak') {
                         jadwalTestContainer.innerHTML = `
                            <div class="jadwal-info" style="border-left-color: #c62828; background-color: #ffebee;">
                                <p>⚠️ Maaf, Anda dinyatakan <strong>TIDAK DITERIMA</strong> di sekolah pilihan Anda.</p>
                                <p>Tetap semangat dan coba lagi di kesempatan lain.</p>
                            </div>
                        `;
                    } else if (student.jadwal_test) {
                        const dateFormatted = new Date(student.jadwal_test).toLocaleDateString('id-ID', {
                            weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
                        });
                        
                        jadwalTestContainer.innerHTML = `
                            <div class="jadwal-info">
                                <p>🗓️ **Jadwal Test Anda:** ${dateFormatted}</p>
                                <p>Harap hadir di ${student.sekolah_diterima || student.sekolah_tujuan} tepat waktu.</p>
                            </div>
                        `;
                    } else {
                         jadwalTestContainer.innerHTML = '';
                    }

                    // Show Change School section only if status is Pending
                    if (student.status_seleksi === 'Pending') {
                        document.getElementById('change-school-section').style.display = 'block';
                    }

                } else {
                    studentDataContainer.innerHTML = `<p style="color: red;">${data.message}</p>`;
                }
            })
            .catch(error => {
                studentDataContainer.innerHTML = `<p style="color: red;">Terjadi kesalahan saat memuat data: ${error.message}</p>`;
                console.error('Fetch Error:', error);
            });

        // Handle Change School
        document.getElementById('btn-change-school').addEventListener('click', function() {
            const newSchool = document.getElementById('new-school-name').value.trim();
            const messageEl = document.getElementById('change-school-message');
            
            if (!newSchool) {
                messageEl.style.color = 'red';
                messageEl.textContent = 'Nama sekolah tidak boleh kosong.';
                return;
            }

            messageEl.style.color = 'blue';
            messageEl.textContent = 'Memproses...';

            fetch("{{ route('student.update.school') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ sekolah_baru: newSchool })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    messageEl.style.color = 'green';
                    messageEl.textContent = data.message;
                    setTimeout(() => location.reload(), 1500);
                } else {
                    messageEl.style.color = 'red';
                    messageEl.textContent = data.message;
                }
            })
            .catch(err => {
                messageEl.style.color = 'red';
                messageEl.textContent = 'Terjadi kesalahan sistem.';
                console.error(err);
            });
        });
    });

</script>
@endsection