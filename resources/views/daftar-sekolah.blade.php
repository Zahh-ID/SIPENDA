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
    document.addEventListener('DOMContentLoaded', function() {
        
        document.querySelectorAll('.login-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                const targetTab = this.getAttribute('data-tab');
                
                document.querySelectorAll('.login-tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.search-content').forEach(c => c.classList.remove('active'));

                this.classList.add('active');
                document.getElementById(targetTab + '-search').classList.add('active');
                
                document.getElementById('loading-status').style.display = 'none';
                document.getElementById('school-summary').innerHTML = '';
                document.getElementById('school-details').innerHTML = '';
            });
        });

        const schoolApiUrl = '{{ route('schools.api') }}';
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const loadingStatus = document.getElementById('loading-status');
        
        document.getElementById('btn-cari-zonasi').addEventListener('click', function() {
            const jenjang = document.getElementById('jenjang-filter').value;
            const daerah = document.getElementById('daerah-filter').value.trim();
            
            if (!jenjang || !daerah) {
                displayError('<p>Jenjang Sekolah dan Asal Daerah wajib diisi untuk pencarian Zonasi.</p>');
                return;
            }
            
            performSearch(jenjang, daerah, '');
        });

        document.getElementById('btn-cari-nama').addEventListener('click', function() {
            const jenjang = document.getElementById('jenjang-nama-filter').value;
            const namaSekolah = document.getElementById('nama-sekolah-filter').value.trim();

            if (!jenjang || !namaSekolah) {
                displayError('<p>Jenjang dan Nama Sekolah wajib diisi untuk pencarian ini.</p>');
                return;
            }

            performSearch(jenjang, '', namaSekolah);
        });


        function performSearch(jenjang, daerah, namaSekolah) {
            loadingStatus.style.display = 'block';
            loadingStatus.style.color = '#ff9800';
            loadingStatus.innerHTML = '<p>Mencari sekolah terdekat dari server...</p>';
            
            fetch(schoolApiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken 
                },
                body: JSON.stringify({
                    jenjang: jenjang,
                    daerah: daerah, 
                    nama_sekolah: namaSekolah
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                loadingStatus.style.display = 'none';
                if (data.status === 'success') {
                    tampilkanHasilFilter(jenjang, daerah, data.schools);
                } else {
                    tampilkanHasilFilter(jenjang, daerah, [], data.message);
                }
            })
            .catch(error => {
                displayError('Gagal mengambil data dari server. Detail: ' + (error.message || 'Error koneksi.'));
                console.error('Fetch Error:', error);
            });
        }
        
        function displayError(message) {
            loadingStatus.style.display = 'block';
            loadingStatus.style.color = '#d32f2f';
            loadingStatus.innerHTML = message;
            document.getElementById('school-summary').innerHTML = '';
            document.getElementById('school-details').innerHTML = '';
        }

        function createSummaryHTML(jenjang, daerah, count) {
            const maxChoice = jenjang === 'SD' ? 1 : (jenjang === 'SMP' ? 3 : 5);
            const jenjangFull = jenjang === 'SD' ? 'Sekolah Dasar (SD)' : (jenjang === 'SMP' ? 'Sekolah Menengah Pertama (SMP)' : 'Sekolah Menengah Atas (SMA)');
            
            const areaDisplay = daerah ? `Sekitar ${daerah}` : 'Hasil Pencarian';

            return `
                <div class="school-count ${jenjang.toLowerCase()}">
                    <h4>${jenjangFull}</h4>
                    <p style="font-size: 2.5em; margin-bottom: 5px;">${count} Sekolah di ${areaDisplay}</p>
                    <span class="max-choice">Pilihan Maks: ${maxChoice}</span>
                </div>
            `;
        }

        function createDetailsHTML(jenjang, daerah, schoolData) {
            const count = schoolData.length;
            if (count === 0) {
                return `<h3 style="margin-top: 0;">Tidak ada sekolah ditemukan untuk jenjang ${jenjang}.</h3>`;
            }

            let listItems = schoolData.map((school, index) => {
                const order = index + 1;
                const encodedSchoolName = encodeURIComponent(school.nama_sekolah);
                return `
                    <li>
                        <strong>${order}. ${school.nama_sekolah}</strong> - Jarak Terdekat: ${school.jarak || 'N/A'} <br>
                        Kuota: ${school.kuota} Siswa - Keterangan: ${school.detail || 'Informasi umum.'}
                        <div>
                            <a href="#" style="margin-right: 10px;">Lihat Peta Zonasi</a>
                            <a href="pendaftaran?sekolah=${encodedSchoolName}" class="btn-small">Daftar Sekarang</a>
                        </div>
                    </li>
                `;
            }).join('');

            return `
                <h3 style="margin-top: 0;">Daftar Sekolah Jenjang ${jenjang} (${count} Sekolah)</h3>
                <div class="sekolah-detail">
                    <h4>Hasil di Area ${daerah || 'Pencarian'}</h4>
                    <ul>${listItems}</ul>
                </div>
            `;
        }
        
        function tampilkanHasilFilter(jenjang, daerah, schoolData, errorMessage = null) {
            const summaryDiv = document.getElementById('school-summary');
            const detailsDiv = document.getElementById('school-details');
            
            summaryDiv.innerHTML = '';
            detailsDiv.innerHTML = '';

            const count = schoolData.length;
            
            if (errorMessage) {
                detailsDiv.innerHTML = `<h3 style="color:#d32f2f;">${errorMessage}</h3>`;
                return;
            }

            summaryDiv.style.display = 'flex';
            summaryDiv.innerHTML = createSummaryHTML(jenjang, daerah, count);

            detailsDiv.style.display = 'block';
            detailsDiv.innerHTML = createDetailsHTML(jenjang, daerah, schoolData);
        }
    });

    // Pasang class form-control pada input/select di form-container
    document.querySelectorAll('.form-container input[type="text"], .form-container select').forEach(el => {
        el.classList.add('form-control');
        el.style.padding = '12px';
        el.style.height = '50px';
        el.style.fontSize = '16px';
        el.style.width = '100%';
    });
</script>
@endsection