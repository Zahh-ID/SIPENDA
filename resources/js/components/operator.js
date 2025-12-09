// operator.js

// PENTING: Jika Anda memanggil fetchDataPendaftar() di Blade, 
// Anda mungkin perlu menghapus listener DOMContentLoaded dari sini
// atau wrap fungsinya agar bisa dipanggil secara global.

// MENGHAPUS DOMContentLoaded WRAPPER LAMA AGAR FUNGSI BISA DIPANGGIL DARI BLADE
// HANYA JIKA ANDA INGIN MENGATUR PEMANGGILANNYA HANYA DI BLADE:

// function fetchDataPendaftar() { ... }
// function updateStatusOperator(studentId, newStatus) { ... }
// ... dll.

// ASUMSI: Fungsi-fungsi utama (fetchDataPendaftar, updateStatusOperator) di operator.js 
// sudah berada di global scope atau dipanggil dengan benar.

// operator.js (Fungsi fetchDataPendaftar yang diperbaiki)

window.fetchDataPendaftar = function () {
    const pendaftarTableBody = document.getElementById('pendaftar-table-body');
    const totalPendaftarCount = document.getElementById('total-pendaftar');
    const perluVerifikasiCount = document.getElementById('perlu-verifikasi');

    pendaftarTableBody.innerHTML = '<tr><td colspan="7" style="text-align: center;">Memuat data pendaftar...</td></tr>';

    axios.get(window.operatorPendaftarApi)
        .then(response => {
            const data = response.data;
            pendaftarTableBody.innerHTML = '';
            const pendaftarData = data.pendaftar || [];

            if (data.status === 'success' && pendaftarData.length > 0) {
                totalPendaftarCount.textContent = pendaftarData.length;

                let verifikasiCount = 0;
                let rowsHTML = '';

                // Remove old dynamic header injection code
                // const tableHeadRow = document.querySelector('.table-container table thead tr');
                // if (tableHeadRow && !tableHeadRow.innerHTML.includes('Dokumen')) { ... }

                pendaftarData.forEach((siswa, index) => {
                    if (siswa.status_seleksi === 'Pending') {
                        verifikasiCount++;
                    }

                    const statusClass = siswa.status_seleksi.toLowerCase().replace(/\s+/g, '');

                    // Generate Document Icons
                    const btnDocBase = "inline-flex items-center gap-2 px-4 py-2 rounded-lg transition-all duration-200 border text-sm font-semibold shadow-sm hover:shadow-md";
                    const btnDocBlue = `${btnDocBase} bg-blue-50 text-blue-700 border-blue-200 hover:bg-blue-100 hover:border-blue-300`;
                    const btnDocPurple = `${btnDocBase} bg-purple-50 text-purple-700 border-purple-200 hover:bg-purple-100 hover:border-purple-300`;
                    const btnDocGray = `${btnDocBase} bg-gray-50 text-gray-400 border-gray-200 cursor-not-allowed opacity-60`;
                    const btnDocRed = `${btnDocBase} bg-red-50 text-red-600 border-red-200 cursor-not-allowed`;

                    // Helper to create icon link
                    const createDocLink = (url, icon, title, label, variant = 'blue', isMissing = false, isRequired = false) => {
                        const activeClass = variant === 'purple' ? btnDocPurple : btnDocBlue;

                        if (url) {
                            return `<a href="/storage/${url}" target="_blank" class="${activeClass} no-underline" title="${title}">
                                <span class="text-base">${icon}</span> <span>${label}</span>
                            </a>`;
                        }
                        if (isRequired && isMissing) {
                            return `<span class="${btnDocRed}" title="${title} (Wajib tapi Belum Ada)">
                                <span class="text-base">⚠️</span> <span>${label}</span>
                            </span>`;
                        }
                        return `<span class="${btnDocGray}" title="${title} (Tidak Ada)">
                            <span class="text-base">${icon}</span> <span>${label}</span>
                        </span>`;
                    };

                    const kkLink = createDocLink(siswa.scan_kk, '📄', 'Kartu Keluarga', 'KK', 'blue');
                    const aktaLink = createDocLink(siswa.scan_akta, '👶', 'Akta Kelahiran', 'Akta', 'blue');
                    const ijazahLink = createDocLink(siswa.scan_ijazah, '🎓', 'Ijazah/SKL', 'Ijazah', 'blue');

                    let prestasiLink = '';
                    if (siswa.jalur_pendaftaran === 'Prestasi') {
                        prestasiLink = createDocLink(siswa.scan_prestasi, '🏆', 'Bukti Prestasi', 'Prestasi', 'purple', !siswa.scan_prestasi, true);
                    }

                    const row = `
                            <tr class="hover:bg-gray-50">
                                <td data-label="No" class="text-center text-gray-500 font-semibold">${index + 1}</td>
                                <td data-label="Nama Siswa" class="font-medium">
                                    <div class="text-gray-900 font-bold">${siswa.nama_lengkap}</div>
                                    <div class="text-xs text-gray-500 md:hidden">NISN: ${siswa.nisn}</div>
                                </td>
                                <td data-label="NISN" class="hidden md:table-cell text-gray-600 font-mono text-sm">${siswa.nisn}</td>
                                <td data-label="Jalur">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide ${siswa.jalur_pendaftaran === 'Prestasi' ? 'bg-purple-100 text-purple-700' :
                            siswa.jalur_pendaftaran === 'Afirmasi' ? 'bg-orange-100 text-orange-700' :
                                'bg-blue-100 text-blue-700'
                        }">
                                        ${siswa.jalur_pendaftaran}
                                    </span>
                                </td>
                                
                                <td data-label="Dokumen">
                                    <div class="flex flex-wrap items-center gap-3">
                                        ${kkLink}
                                        ${aktaLink}
                                        ${ijazahLink}
                                        ${prestasiLink}
                                    </div>
                                </td>

                                <td data-label="Jadwal Test">
                                    ${(siswa.jalur_pendaftaran === 'Prestasi' || (siswa.jalur_pendaftaran === 'Zonasi' && siswa.jenjang_tujuan !== 'SMA')) ?
                            '<span class="text-gray-400 text-xs italic bg-gray-100 px-2 py-1 rounded">-- Tidak Ada Tes --</span>' :
                            `<div class="jadwal-control flex items-center gap-2">
                                            <input type="date" id="jadwal-${siswa.id}" value="${siswa.jadwal_test || ''}" class="p-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-400 outline-none w-36">
                                            <button onclick="simpanJadwal(${siswa.id})" class="p-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm" title="Simpan Jadwal">
                                                💾
                                            </button>
                                        </div>`
                        }
                                </td>
                                
                                <td data-label="Status Seleksi">
                                    <span class="status ${statusClass} px-3 py-1 rounded-full text-xs font-bold uppercase">
                                        ${siswa.status_seleksi}
                                    </span>
                                </td>
                                
                                <td data-label="Aksi" class="action-btns">
                                    ${siswa.status_seleksi === 'Pending' ?
                            `<div class="flex gap-2">
                                            <button onclick="updateStatusOperator(${siswa.id}, 'Diterima', false, null, '${siswa.jalur_pendaftaran}', '${siswa.jenjang_tujuan}')" class="bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded-lg text-xs font-bold shadow-sm transition transform hover:-translate-y-0.5">TERIMA</button>
                                            <button onclick="updateStatusOperator(${siswa.id}, 'Ditolak', false, null, '${siswa.jalur_pendaftaran}', '${siswa.jenjang_tujuan}')" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-xs font-bold shadow-sm transition transform hover:-translate-y-0.5">TOLAK</button>
                                        </div>`
                            :
                            `<div class="flex items-center gap-2">
                                            <span class="text-xs font-medium text-gray-500">Selesai</span>
                                            <button onclick="updateStatusOperator(${siswa.id}, 'Pending', false, null, '${siswa.jalur_pendaftaran}', '${siswa.jenjang_tujuan}')" class="text-xs text-blue-600 hover:text-blue-800 underline font-semibold" title="Kembalikan ke Pending">Ubah</button>
                                        </div>`
                        }
                                </td>
                            </tr>
                        `;

                    rowsHTML += row;
                });

                pendaftarTableBody.innerHTML = rowsHTML;
                perluVerifikasiCount.textContent = verifikasiCount;
            } else {
                totalPendaftarCount.textContent = '0';
                perluVerifikasiCount.textContent = '0';
                pendaftarTableBody.innerHTML = '<tr><td colspan="8" style="text-align: center; padding: 40px; color: #666;">Belum ada pendaftar untuk sekolah ini.</td></tr>';
            }
        })
        .catch(error => {
            pendaftarTableBody.innerHTML = `<tr><td colspan="7" style="text-align: center; color: red;">Gagal memuat data: ${error.message || 'Terjadi kesalahan koneksi atau server.'}</td></tr>`;
            console.error('Axios Error:', error);
        });
}

window.simpanJadwal = function (studentId) {
    const jadwalInput = document.getElementById(`jadwal-${studentId}`);
    if (!jadwalInput) return; // Should not happen if button exists

    const jadwalTest = jadwalInput.value;

    if (!jadwalTest) {
        alert('Mohon pilih tanggal jadwal test.');
        return;
    }

    // Pass dummy values for jalur/jenjang as they aren't critical for JUST updating the schedule, 
    // but the function signature expects them.  Or we can refactor updateStatusOperator.
    // For now, let's keep it simple and assume standard validation holds.
    updateStatusOperator(studentId, 'Pending', true, jadwalTest);
};

window.updateStatusOperator = function (studentId, newStatus, isJadwalOnly = false, customJadwal = null, jalur = '', jenjang = '') {
    // Determine if test is required based on the passed params (from the button click)
    // Or we could query the DOM/data if we had it globally. passing is easier.

    let isTestRequired = true;
    if (jalur === 'Prestasi') isTestRequired = false;
    if (jalur === 'Zonasi' && jenjang !== 'SMA') isTestRequired = false;

    // Override: If isJadwalOnly is true, we ARE saving a schedule, so we treat it as required contextually
    // Actually simplicity: if we are saving schedule (isJadwalOnly), we just take the value.

    let jadwalToSubmit = customJadwal;

    if (!jadwalToSubmit && isTestRequired) {
        // Try to get from input if it exists
        const inputEl = document.getElementById(`jadwal-${studentId}`);
        if (inputEl) {
            jadwalToSubmit = inputEl.value;
        }
    }

    if (newStatus !== 'Pending' && isTestRequired && !jadwalToSubmit) {
        alert('Jadwal Test harus diisi untuk jalur/jenjang ini sebelum status Diterima/Ditolak.');
        return;
    }

    if (!isJadwalOnly && !confirm(`Yakin mengubah status siswa ID ${studentId} menjadi ${newStatus}?`)) {
        return;
    }

    axios.post('/api/operator/seleksi', {
        student_id: studentId,
        status_seleksi: newStatus,
        jadwal_test: jadwalToSubmit,
    })
        .then(response => {
            alert(response.data.message || 'Data berhasil disimpan.');
            fetchDataPendaftar();
        })
        .catch(error => {
            alert('Gagal memperbarui status.');
            console.error('Axios Error:', error);
        });
};

// ... (logic submitApproval sudah dipindahkan ke Blade)
