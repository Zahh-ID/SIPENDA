// script.js - Interactivity for PPDB V.2.0 (Versi Multi-Page + Filter Lokasi)

document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.querySelector('.menu-toggle');
    const navLinks = document.querySelector('.nav-links');
    
    // --- Ambil CSRF Token dari tag meta (Pastikan meta tag CSRF ada di layout utama) ---
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // 1. Mobile Menu Toggle
    if (menuToggle && navLinks) {
        menuToggle.addEventListener('click', function() {
            navLinks.classList.toggle('active');
        });

        // Menutup menu saat tautan diklik 
        const links = navLinks.querySelectorAll('a');
        links.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 900) {
                    navLinks.classList.remove('active');
                }
            });
        });
    }

    // 2. DATA SEKOLAH (SIMULASI DIGANTI FUNGSI FETCH)
    window.allSchools = {}; 
    
    // 3. LOGIKA FILTER OLEH TOMBOL CARI
    const btnCariSekolah = document.getElementById('btn-cari-sekolah');
    const jenjangFilter = document.getElementById('jenjang-filter');
    const daerahFilter = document.getElementById('daerah-filter');
    const loadingStatus = document.getElementById('loading-status');
    
    // --- URL API Sekolah (Menggunakan hardcoded path karena kita tidak bisa menggunakan route helper di file JS) ---
    const schoolApiUrl = '/api/get_schools'; 

    if (btnCariSekolah) {
        btnCariSekolah.addEventListener('click', function() {
            const jenjang = jenjangFilter.value;
            const daerah = daerahFilter.value.trim();
            const namaSekolah = document.getElementById('nama-sekolah-filter').value.trim(); // 🔥 BARU

            // --- Logika Validasi (Pastikan minimal Jenjang dan salah satu Daerah/Nama Sekolah terisi) ---
            if (!jenjang) { /* ... error message ... */ return; }
            
            // Cek jika kedua filter pencarian (Daerah dan Nama Sekolah) kosong
            if (!daerah && !namaSekolah) {
                loadingStatus.style.display = 'block';
                loadingStatus.style.color = '#d32f2f';
                loadingStatus.innerHTML = '<p>Mohon masukkan Asal Daerah **atau** Nama Sekolah.</p>';
                setTimeout(() => { loadingStatus.style.display = 'none'; }, 2000);
                return;
            }

            loadingStatus.style.display = 'block';
            loadingStatus.style.color = '#ff9800';
            loadingStatus.innerHTML = '<p>Mencari sekolah terdekat dari server...</p>';
            
            // 🔥 MODIFIKASI BODY JSON
            fetch(schoolApiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken 
                },
                body: JSON.stringify({
                    jenjang: jenjang,
                    daerah: daerah, 
                    nama_sekolah: namaSekolah // 🔥 KIRIM INPUT BARU
                })
            })
            .then(response => {
                if (!response.ok) {
                    // Melemparkan error jika status bukan 2xx (misalnya 404, 500)
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                loadingStatus.style.display = 'none';
                if (data.status === 'success') {
                    // Jika sekolah ditemukan
                    tampilkanHasilFilter(jenjang, daerah, data.schools);
                } else {
                    // Tidak ada sekolah ditemukan
                    tampilkanHasilFilter(jenjang, daerah, [], data.message);
                }
            })
            .catch(error => {
                loadingStatus.style.display = 'block';
                loadingStatus.style.color = '#d32f2f';
                // Menampilkan pesan error yang lebih informatif (jika ada)
                let errorMessage = error.message || 'Gagal mengambil data dari server. Cek koneksi API.';
                loadingStatus.innerHTML = `<p>${errorMessage}</p>`;
                console.error('Fetch Error:', error);
            });
        });
    }

    // --- Helper Functions untuk Menampilkan Hasil (TIDAK BERUBAH) ---

    function createSummaryHTML(jenjang, daerah, count) {
        const maxChoice = jenjang === 'SD' ? 1 : (jenjang === 'SMP' ? 3 : 5);
        const jenjangFull = jenjang === 'SD' ? 'Sekolah Dasar (SD)' : (jenjang === 'SMP' ? 'Sekolah Menengah Pertama (SMP)' : 'Sekolah Menengah Atas (SMA)');
        
        return `
            <div class="school-count ${jenjang.toLowerCase()}">
                <h4>${jenjangFull}</h4>
                <p style="font-size: 2.5em; margin-bottom: 5px;">${count} Sekolah di Sekitar ${daerah}</p>
                <span class="max-choice">Pilihan Maks: ${maxChoice}</span>
            </div>
        `;
    }

    function createDetailsHTML(jenjang, daerah, schoolData) {
        const count = schoolData.length;
        if (count === 0) {
            return `<h3 style="margin-top: 0;">Tidak ada sekolah ditemukan untuk jenjang ${jenjang} di sekitar ${daerah}.</h3>`;
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
            <h3 style="margin-top: 0;">Daftar Sekolah Terdekat Jenjang ${jenjang} (${count} Sekolah)</h3>
            <div class="sekolah-detail">
                <h4>Sekolah di Zonasi ${daerah}</h4>
                <ul>${listItems}</ul>
            </div>
        `;
    }

    // 4. FUNGSI UTAMA UNTUK MENAMPILKAN HASIL KE DOM (TIDAK BERUBAH)
    function tampilkanHasilFilter(jenjang, daerah, schoolData) {
        const summaryDiv = document.getElementById('school-summary');
        const detailsDiv = document.getElementById('school-details');
        
        // Kosongkan konten sebelumnya
        summaryDiv.innerHTML = '';
        detailsDiv.innerHTML = '';

        const count = schoolData.length;

        // --- Tampilkan Ringkasan (Cards) ---
        summaryDiv.style.display = 'flex';
        const summaryClass = 'sekolah-summary ' + (count === 1 ? 'single-card' : '');
        summaryDiv.className = summaryClass;
        summaryDiv.innerHTML = createSummaryHTML(jenjang, daerah, count);

        // --- Tampilkan Rincian (List) ---
        detailsDiv.style.display = 'block';
        detailsDiv.innerHTML = createDetailsHTML(jenjang, daerah, schoolData);
    }
});