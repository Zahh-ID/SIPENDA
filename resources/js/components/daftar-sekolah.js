document.addEventListener('DOMContentLoaded', function() {

    // Exit if the main search button isn't on the page
    if (!document.getElementById('btn-cari')) return;

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const loadingStatus = document.getElementById('loading-status');
    
    document.getElementById('btn-cari').addEventListener('click', function() {
        const jenjang = document.getElementById('jenjang-filter').value;
        const daerah = document.getElementById('daerah-filter').value.trim();
        const namaSekolah = document.getElementById('nama-sekolah-filter').value.trim();
        
        if (!jenjang) {
            displayError('<p>Jenjang Sekolah wajib diisi untuk memulai pencarian.</p>');
            return;
        }
        
        performSearch(jenjang, daerah, namaSekolah);
    });

    function performSearch(jenjang, daerah, namaSekolah) {
        loadingStatus.style.display = 'block';
        loadingStatus.style.color = '#ff9800';
        loadingStatus.innerHTML = '<p>Mencari sekolah...</p>';
        
        // Clear previous results
        document.getElementById('school-summary').innerHTML = '';
        document.getElementById('school-details').innerHTML = '';

        fetch(window.schoolApiUrl, {
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
                // Try to parse error response as JSON, otherwise throw a generic error
                return response.json().then(err => { throw err; }).catch(() => { throw new Error('Server returned an error.'); });
            }
            return response.json();
        })
        .then(data => {
            loadingStatus.style.display = 'none';
            if (data.status === 'success' || data.status === 'not_found') {
                tampilkanHasilFilter(jenjang, daerah || 'Pencarian', data.schools, data.message);
            } else {
                displayError(data.message || 'Terjadi kesalahan yang tidak diketahui.');
            }
        })
        .catch(error => {
            displayError('Gagal mengambil data dari server. Detail: ' + (error.message || 'Error koneksi.'));
            console.error('Fetch Error:', error);
        });
    }
    
    function displayError(message) {
        loadingStatus.style.display = 'block';
        loadingStatus.style.color = '#d32f2f'; // Error color
        loadingStatus.innerHTML = message;
        document.getElementById('school-summary').innerHTML = '';
        document.getElementById('school-details').innerHTML = '';
    }

    function createSummaryHTML(jenjang, daerah, count) {
        const maxChoice = jenjang === 'SD' ? 1 : (jenjang === 'SMP' ? 3 : 5);
        const jenjangFull = jenjang === 'SD' ? 'Sekolah Dasar (SD)' : (jenjang === 'SMP' ? 'Sekolah Menengah Pertama (SMP)' : 'Sekolah Menengah Atas (SMA)');
        
        const areaDisplay = daerah ? `di ${daerah}` : '';

        return `
            <div class="school-count ${jenjang.toLowerCase()} w-full text-center bg-blue-50 p-6 rounded-lg">
                <h4 class="text-xl font-semibold text-blue-800">${jenjangFull}</h4>
                <p class="text-5xl font-bold my-2 text-blue-900">${count}</p>
                <p class="text-lg text-gray-700">Sekolah ditemukan ${areaDisplay}</p>
                <span class="inline-block bg-blue-200 text-blue-800 text-sm font-semibold px-3 py-1 rounded-full mt-2">Pilihan Maksimal: ${maxChoice}</span>
            </div>
        `;
    }

    function createDetailsHTML(jenjang, daerah, schoolData) {
        if (!schoolData || schoolData.length === 0) {
            return ''; // Summary will show the main message
        }

        let listItems = schoolData.map((school, index) => {
            const encodedSchoolName = encodeURIComponent(school.nama_sekolah);
            return `
                <li class="border-b border-gray-200 py-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <strong class="text-lg font-semibold text-gray-800">${index + 1}. ${school.nama_sekolah}</strong>
                            <p class="text-gray-600">
                                Jarak: ${school.jarak || 'N/A'} | Kuota: ${school.kuota || 'N/A'} Siswa
                            </p>
                        </div>
                        <a href="pendaftaran?sekolah=${encodedSchoolName}" class="btn-small btn-primary whitespace-nowrap">Daftar</a>
                    </div>
                </li>
            `;
        }).join('');

        return `
            <div class="bg-white p-6 rounded-lg shadow-md mt-6">
                <h3 class="text-2xl font-bold mb-4">Detail Sekolah (${schoolData.length} Hasil)</h3>
                <ul>${listItems}</ul>
            </div>
        `;
    }
    
    function tampilkanHasilFilter(jenjang, daerah, schoolData, message = null) {
        const summaryDiv = document.getElementById('school-summary');
        const detailsDiv = document.getElementById('school-details');
        
        summaryDiv.innerHTML = '';
        detailsDiv.innerHTML = '';

        const count = schoolData ? schoolData.length : 0;

        summaryDiv.innerHTML = createSummaryHTML(jenjang, daerah, count);

        if (count > 0) {
            detailsDiv.innerHTML = createDetailsHTML(jenjang, daerah, schoolData);
        } else if (message) {
            detailsDiv.innerHTML = `<div class="text-center mt-6"><p class="text-lg text-gray-500">${message}</p></div>`;
        }
    }
});
