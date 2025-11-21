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

function fetchDataPendaftar() {
    const pendaftarTableBody = document.getElementById('pendaftar-table-body');
    const totalPendaftarCount = document.getElementById('total-pendaftar');
    const perluVerifikasiCount = document.getElementById('perlu-verifikasi');
    
    pendaftarTableBody.innerHTML = '<tr><td colspan="7" style="text-align: center;">Memuat data pendaftar...</td></tr>';

    fetch(window.operatorPendaftarApi) 
        .then(response => {
            if (!response.ok) {
                throw new Error('Gagal mengambil data dari server. Status: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            pendaftarTableBody.innerHTML = '';
            const pendaftarData = data.pendaftar || [];
            
            if (data.status === 'success' && pendaftarData.length > 0) {
                totalPendaftarCount.textContent = pendaftarData.length;
                
                let verifikasiCount = 0;
                let rowsHTML = ''; // Inisialisasi string untuk menampung semua baris
                
                pendaftarData.forEach((siswa, index) => {
                    if (siswa.status_seleksi === 'Pending') {
                        verifikasiCount++;
                    }
                    
                    const statusClass = siswa.status_seleksi.toLowerCase().replace(/\s+/g, '');
                    
                    // 🔥 PERBAIKAN: Template String HTML yang Bersih
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
                    
                    rowsHTML += row; // Tambahkan baris ke akumulator
                });
                
                pendaftarTableBody.innerHTML = rowsHTML; // Masukkan semua baris ke DOM setelah loop selesai
                perluVerifikasiCount.textContent = verifikasiCount;
                
            } else {
                totalPendaftarCount.textContent = '0';
                perluVerifikasiCount.textContent = '0';
                pendaftarTableBody.innerHTML = '<tr><td colspan="7" style="text-align: center;">Belum ada pendaftar untuk sekolah ini.</td></tr>';
            }
        })
        .catch(error => {
            pendaftarTableBody.innerHTML = `<tr><td colspan="7" style="text-align: center; color: red;">Gagal memuat data: ${error.message || 'Terjadi kesalahan koneksi atau server.'}</td></tr>`;
            console.error('Fetch Error:', error);
        });
}

// Tambahkan fungsi baru ini
window.simpanJadwal = function(studentId) {
    const jadwalTest = document.getElementById(`jadwal-${studentId}`).value;
    
    if (!jadwalTest) {
        alert('Mohon pilih tanggal jadwal test.');
        return;
    }
    
    // Panggil updateStatusOperator dengan status='Pending' (hanya update jadwal)
    updateStatusOperator(studentId, 'Pending', true, jadwalTest);
};


// Modifikasi fungsi updateStatusOperator agar lebih fleksibel
window.updateStatusOperator = function(studentId, newStatus, isJadwalOnly = false, customJadwal = null) {
    const currentJadwal = document.getElementById(`jadwal-${studentId}`).value;
    const jadwalToSubmit = customJadwal || currentJadwal;
    
    // Aturan: Jika statusnya Diterima/Ditolak, jadwal harus diisi.
    if (newStatus !== 'Pending' && !jadwalToSubmit) {
        alert('Jadwal Test harus diisi sebelum status Diterima/Ditolak.');
        return;
    }
    
    // Konfirmasi hanya jika bukan mode simpan jadwal
    if (!isJadwalOnly && !confirm(`Yakin mengubah status siswa ID ${studentId} menjadi ${newStatus}?`)) {
         return;
    }

    fetch('/api/operator/seleksi', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.globalCsrfToken },
        body: JSON.stringify({
            student_id: studentId,
            status_seleksi: newStatus,
            jadwal_test: jadwalToSubmit,
            _token: window.globalCsrfToken
        })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message || 'Data berhasil disimpan.');
        fetchDataPendaftar();
    })
    .catch(error => {
        alert('Gagal memperbarui status.');
        console.error('Error:', error);
    });
};
// ... (logic submitApproval sudah dipindahkan ke Blade)