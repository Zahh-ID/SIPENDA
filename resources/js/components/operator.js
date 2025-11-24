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

window.fetchDataPendaftar = function() {
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
                
                pendaftarData.forEach((siswa, index) => {
                    if (siswa.status_seleksi === 'Pending') {
                        verifikasiCount++;
                    }
                    
                    const statusClass = siswa.status_seleksi.toLowerCase().replace(/\s+/g, '');
                    
                    const row = `
                        <tr>
                            <td data-label="No">${index + 1}</td>
                            <td data-label="Nama Siswa">${siswa.nama_lengkap}</td>
                            <td data-label="NISN">${siswa.nisn}</td>
                            <td data-label="Jalur">${siswa.jalur_pendaftaran}</td>
                            
                            <td data-label="Jadwal Test">
                                <div class="jadwal-control">
                                    <input type="date" id="jadwal-${siswa.id}" value="${siswa.jadwal_test || ''}" class="p-1 border rounded text-sm" style="max-width: 130px;">
                                    <button onclick="simpanJadwal(${siswa.id})" class="btn-simpan-jadwal">Simpan</button>
                                </div>
                            </td>
                            
                            <td data-label="Status Seleksi"><span class="status ${statusClass}">${siswa.status_seleksi}</span></td>
                            
                            <td data-label="Aksi" class="action-btns">
                                <button onclick="updateStatusOperator(${siswa.id}, 'Diterima', false)" class="btn-small bg-green-500 hover:bg-green-700">Terima</button>
                                <button onclick="updateStatusOperator(${siswa.id}, 'Ditolak', false)" class="btn-small bg-red-700 hover:bg-red-900">Tolak</button>
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
                pendaftarTableBody.innerHTML = '<tr><td colspan="7" style="text-align: center;">Belum ada pendaftar untuk sekolah ini.</td></tr>';
            }
        })
        .catch(error => {
            pendaftarTableBody.innerHTML = `<tr><td colspan="7" style="text-align: center; color: red;">Gagal memuat data: ${error.message || 'Terjadi kesalahan koneksi atau server.'}</td></tr>`;
            console.error('Axios Error:', error);
        });
}

window.simpanJadwal = function(studentId) {
    const jadwalTest = document.getElementById(`jadwal-${studentId}`).value;
    
    if (!jadwalTest) {
        alert('Mohon pilih tanggal jadwal test.');
        return;
    }
    
    updateStatusOperator(studentId, 'Pending', true, jadwalTest);
};

window.updateStatusOperator = function(studentId, newStatus, isJadwalOnly = false, customJadwal = null) {
    const currentJadwal = document.getElementById(`jadwal-${studentId}`).value;
    const jadwalToSubmit = customJadwal || currentJadwal;
    
    if (newStatus !== 'Pending' && !jadwalToSubmit) {
        alert('Jadwal Test harus diisi sebelum status Diterima/Ditolak.');
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
