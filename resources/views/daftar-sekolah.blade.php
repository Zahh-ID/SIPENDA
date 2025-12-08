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
    
    <div id="loading-status" style="display: none; text-align: center !important; font-weight: 600; color: #ff9800; margin-top: 20px;">
        <p>Memproses data sekolah...</p>
    </div>

    <div id="school-summary"></div>

    <div id="school-details" style="margin-top: 40px;"></div>
    
</section>
@endsection

@section('scripts')
<script>
    (function() {
        console.log('Search script loaded');
        const schoolApiUrl = '{{ route('schools.api') }}';
        const loadingStatus = document.getElementById('loading-status');

        // Tab Switching
        const tabs = document.querySelectorAll('.login-tab');
        if (tabs.length > 0) {
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    console.log('Tab clicked:', this.getAttribute('data-tab'));
                    const targetTab = this.getAttribute('data-tab');
                    
                    document.querySelectorAll('.login-tab').forEach(t => t.classList.remove('active'));
                    document.querySelectorAll('.search-content').forEach(c => c.classList.remove('active'));

                    this.classList.add('active');
                    const targetContent = document.getElementById(targetTab + '-search');
                    if (targetContent) targetContent.classList.add('active');
                    
                    if (loadingStatus) loadingStatus.style.display = 'none';
                    const summary = document.getElementById('school-summary');
                    const details = document.getElementById('school-details');
                    if (summary) summary.innerHTML = '';
                    if (details) details.innerHTML = '';
                });
            });
        }

        // Zonasi Search
        const btnCariZonasi = document.getElementById('btn-cari-zonasi');
        if (btnCariZonasi) {
            console.log('Button Zonasi found');
            btnCariZonasi.addEventListener('click', function(e) {
                e.preventDefault(); // Prevent default just in case
                console.log('Button Zonasi clicked');
                
                const jenjang = document.getElementById('jenjang-filter').value;
                const daerah = document.getElementById('daerah-filter').value.trim();
                
                if (!jenjang || !daerah) {
                    displayError('<p>Jenjang Sekolah dan Asal Daerah wajib diisi untuk pencarian Zonasi.</p>');
                    return;
                }
                
                performSearch(jenjang, daerah, '');
            });
        } else {
            console.error('Button Zonasi not found');
        }

        // Nama Search
        const btnCariNama = document.getElementById('btn-cari-nama');
        if (btnCariNama) {
            console.log('Button Nama found');
            btnCariNama.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Button Nama clicked');

                const jenjang = document.getElementById('jenjang-nama-filter').value;
                const namaSekolah = document.getElementById('nama-sekolah-filter').value.trim();

                if (!jenjang || !namaSekolah) {
                    displayError('<p>Jenjang dan Nama Sekolah wajib diisi untuk pencarian ini.</p>');
                    return;
                }

                performSearch(jenjang, '', namaSekolah);
            });
        } else {
            console.error('Button Nama not found');
        }

        function performSearch(jenjang, daerah, namaSekolah) {
            if (loadingStatus) {
                loadingStatus.style.display = 'block';
                loadingStatus.style.color = '#ff9800';
                loadingStatus.innerHTML = '<p>Mencari sekolah terdekat dari server...</p>';
            }
            
            console.log('Performing search:', { jenjang, daerah, namaSekolah, url: schoolApiUrl });

            // Use axios if available, otherwise fallback to fetch
            if (window.axios) {
                window.axios.post(schoolApiUrl, {
                    jenjang: jenjang,
                    daerah: daerah, 
                    nama_sekolah: namaSekolah
                })
                .then(response => {
                    console.log('Search response:', response.data);
                    handleResponse(response.data, jenjang, daerah);
                })
                .catch(error => {
                    console.error('Search error:', error);
                    handleError(error);
                });
            } else {
                // Fallback to fetch if axios is not ready
                console.warn('Axios not found, falling back to fetch');
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
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
                .then(res => res.json())
                .then(data => handleResponse(data, jenjang, daerah))
                .catch(err => handleError(err));
            }
        }

        function handleResponse(data, jenjang, daerah) {
            if (loadingStatus) loadingStatus.style.display = 'none';
            if (data.status === 'success') {
                tampilkanHasilFilter(jenjang, daerah, data.schools);
            } else {
                tampilkanHasilFilter(jenjang, daerah, [], data.message);
            }
        }

        function handleError(error) {
            let msg = 'Gagal mengambil data dari server.';
            if (error.response && error.response.data && error.response.data.message) {
                msg += ' ' + error.response.data.message;
            }
            displayError(msg);
        }
        
        function displayError(message) {
            if (loadingStatus) {
                loadingStatus.style.display = 'block';
                loadingStatus.style.color = '#d32f2f';
                loadingStatus.innerHTML = message;
            }
            const summary = document.getElementById('school-summary');
            const details = document.getElementById('school-details');
            if (summary) summary.innerHTML = '';
            if (details) details.innerHTML = '';
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
                        Kuota: ${school.kuota || '-'} Siswa - Keterangan: ${school.detail || 'Informasi umum.'}
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
            
            if (summaryDiv) {
                summaryDiv.innerHTML = '';
                if (!errorMessage) {
                    summaryDiv.style.display = 'flex';
                    summaryDiv.innerHTML = createSummaryHTML(jenjang, daerah, schoolData.length);
                }
            }

            if (detailsDiv) {
                detailsDiv.innerHTML = '';
                detailsDiv.style.display = 'block';
                if (errorMessage) {
                    detailsDiv.innerHTML = `<h3 style="color:#d32f2f;">${errorMessage}</h3>`;
                } else {
                    detailsDiv.innerHTML = createDetailsHTML(jenjang, daerah, schoolData);
                }
            }
        }
    })();
</script>
@endsection


