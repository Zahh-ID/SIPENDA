document.addEventListener('DOMContentLoaded', function() {

    if (!document.getElementById('zonasi-search')) return;

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

    // const schoolApiUrl = '{{ route('schools.api') }}';
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
        
        window.axios.post(window.schoolApiUrl, {
            jenjang: jenjang,
            daerah: daerah, 
            nama_sekolah: namaSekolah
        })
        .then(response => {
            const data = response.data;
            loadingStatus.style.display = 'none';
            if (data.status === 'success') {
                tampilkanHasilFilter(jenjang, daerah, data.schools);
            } else {
                tampilkanHasilFilter(jenjang, daerah, [], data.message);
            }
        })
        .catch(error => {
            let errorMessage = 'Gagal mengambil data dari server.';
            if (error.response && error.response.data && error.response.data.message) {
                errorMessage += ' Detail: ' + error.response.data.message;
            } else if (error.message) {
                errorMessage += ' Detail: ' + error.message;
            }
            displayError(errorMessage);
            console.error('Axios Error:', error);
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
    // Pasang class form-control pada input/select di form-container
    document.querySelectorAll('.form-container input[type="text"], .form-container select').forEach(el => {
        el.classList.add('form-control');
        el.style.padding = '12px';
        el.style.height = '50px';
        el.style.fontSize = '16px';
        el.style.width = '100%';
    });
});