// admin.js

window.fetchPengajuan = function () {
    const tableBody = document.getElementById('approval-table-body');
    if (!tableBody) return; // Guard clause

    tableBody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 20px;">Memuat permintaan approval...</td></tr>';

    axios.get('/api/admin/pengajuan')
        .then(response => {
            tableBody.innerHTML = '';
            const data = response.data;
            const pengajuanData = data.pengajuan || [];

            if (data.status === 'success' && pengajuanData.length > 0) {
                pengajuanData.forEach((siswa, index) => {
                    const statusClass = siswa.status_seleksi.toLowerCase().replace(/\s+/g, '');
                    const row = `
                        <tr>
                            <td data-label="ID">${siswa.id}</td>
                            <td data-label="Sekolah Asal Pengajuan"><strong>${siswa.sekolah_tujuan}</strong></td>
                            <td data-label="Nama Siswa (NISN)">
                                <div style="font-weight: 600;">${siswa.nama_lengkap}</div>
                                <div style="font-size: 0.85em; color: #666;">${siswa.nisn}</div>
                            </td>
                            <td data-label="Status Seleksi Operator"><span class="status ${statusClass}">${siswa.status_seleksi}</span></td>
                            <td data-label="Jadwal Test">${siswa.jadwal_test || '<span style="color:#aaa;">-</span>'}</td>

                            <td data-label="Aksi Admin Dinas" class="action-btns">
                                <button onclick="updateApprovalAdmin(${siswa.id}, 'Approved')" class="btn-small bg-blue-500 hover:bg-blue-700" title="Setujui">✔ ACC</button>
                                <button onclick="updateApprovalAdmin(${siswa.id}, 'Rejected')" class="btn-small bg-red-700 hover:bg-red-900" title="Tolak">✖ Tolak</button>
                            </td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            } else {
                tableBody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 30px; color: #666;">Tidak ada permintaan approval yang masuk saat ini.</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error fetching pengajuan:', error);
            tableBody.innerHTML = '<tr><td colspan="6" style="text-align: center; color: red; padding: 20px;">Gagal memuat data. Silakan coba lagi.</td></tr>';
        });
};

window.updateApprovalAdmin = function (studentId, newApprovalStatus) {
    const action = newApprovalStatus === 'Approved' ? 'menyetujui' : 'menolak';
    if (!confirm(`Apakah Anda yakin ingin ${action} siswa ID ${studentId}?`)) { return; }

    axios.post('/api/admin/approval', {
        student_id: studentId,
        status_approval: newApprovalStatus,
    })
        .then(response => {
            alert(response.data.message || 'Approval berhasil dilakukan.');
            fetchPengajuan(); // Refresh tabel
        })
        .catch(error => {
            alert('Gagal memperbarui approval.');
            console.error('Error:', error);
        });
};

window.approveAllAdmin = function () {
    if (!confirm('Apakah Anda yakin ingin menyetujui (ACC) SEMUA permintaan yang pending saat ini?')) { return; }

    axios.post('/api/admin/approval/all')
        .then(response => {
            alert(response.data.message);
            fetchPengajuan(); // Refresh table
        })
        .catch(error => {
            alert('Gagal melakukan approval massal.');
            console.error('Error:', error);
        });
};
