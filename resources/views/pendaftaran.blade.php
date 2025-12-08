@extends('layouts.main')
@section('title', 'Formulir Pendaftaran - PPDB V.2.0')

@section('content')
<section class="page-content container">
    <h2>✍️ Formulir Pendaftaran Calon Siswa</h2>
    <p class="subtitle">Lengkapi data di bawah untuk mendaftar ke sekolah tujuan Anda.</p>
    
    <div class="form-container">
        <form id="form-pendaftaran" onsubmit="submitPendaftaran(event)">
            @csrf
            
            <h3 id="target-sekolah">Target Sekolah: Belum Dipilih</h3>
            <input type="hidden" id="sekolah-tujuan" name="sekolah-tujuan" value="">

            <label for="nama-siswa">Nama Lengkap Siswa:</label>
            <input type="text" id="nama-siswa" name="nama_siswa" required placeholder="Nama sesuai Akta Lahir">
            
            <label for="nisn">NISN (Nomor Induk Siswa Nasional):</label>
            <input type="text" id="nisn" name="nisn" required placeholder="10 digit NISN Anda">

            <label for="jenjang">Jenjang Tujuan:</label>
            <select id="jenjang" name="jenjang" required>
                <option value="">-- Pilih Jenjang --</option>
                <option value="SD">SD</option>
                <option value="SMP">SMP</option>
                <option value="SMA">SMA</option>
            </select>

            <label for="jalur">Pilih Jalur Pendaftaran:</label>
            <select id="jalur" name="jalur" required>
                <option value="">-- Pilih Jalur --</option>
                <option value="Zonasi">Zonasi</option>
                <option value="Afirmasi">Afirmasi</option>
                <option value="Mutasi">Mutasi Orang Tua/Wali</option>
                <option value="Prestasi">Prestasi</option>
            </select>
            <label for="alamat">Alamat Tinggal (Sesuai KK):</label>
            <textarea id="alamat" name="alamat" rows="3" required placeholder="Alamat lengkap, RT/RW, Kelurahan"></textarea>
            
            <p style="margin-top: 20px; font-weight: 600;">Upload Dokumen Persyaratan</p>
            <label for="scan_kk">Scan Kartu Keluarga (KK):</label>
            <input type="file" id="scan_kk" name="scan_kk" accept=".jpg,.jpeg,.png,.pdf" required>
            
            <label for="scan_akta">Scan Akta Kelahiran:</label>
            <input type="file" id="scan_akta" name="scan_akta" accept=".jpg,.jpeg,.png,.pdf" required>
            
            <label for="scan_ijazah">Scan Ijazah / SKL:</label>
            <input type="file" id="scan_ijazah" name="scan_ijazah" accept=".jpg,.jpeg,.png,.pdf" required>
            
            <p style="margin-top: 20px; font-weight: 600;">Data Login (Untuk Cek Status)</p>
            <label for="password-baru">Buat Password:</label>
            <input type="password" id="password-baru" name="password_baru" required>


            <button type="submit" class="btn-primary" style="width: 100%; border-radius: 6px; margin-top: 30px;">KIRIM FORMULIR PENDAFTARAN</button>
            
            <div id="pendaftaran-message" style="margin-top: 20px; padding: 15px; border-radius: 6px; font-weight: 600;"></div>
        </form>
    </div>
</section>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const params = new URLSearchParams(window.location.search);
        const sekolah = params.get('sekolah');
        
        const sekolahTujuanInput = document.getElementById('sekolah-tujuan');
        const targetSekolahH3 = document.getElementById('target-sekolah');
        const jenjangSelect = document.getElementById('jenjang');

        if (sekolah) {
            const decodedSekolah = decodeURIComponent(sekolah);
            targetSekolahH3.innerHTML = `Target Sekolah: <strong>${decodedSekolah}</strong>`;
            sekolahTujuanInput.value = decodedSekolah;
            
            if (decodedSekolah.toUpperCase().includes('SDN') || decodedSekolah.toUpperCase().includes('SD')) {
                jenjangSelect.value = 'SD';
            } else if (decodedSekolah.toUpperCase().includes('SMPN') || decodedSekolah.toUpperCase().includes('SMP')) {
                jenjangSelect.value = 'SMP';
            } else if (decodedSekolah.toUpperCase().includes('SMAN') || decodedSekolah.toUpperCase().includes('SMA')) {
                jenjangSelect.value = 'SMA';
            }
        } else {
             targetSekolahH3.innerHTML = `Target Sekolah: <strong style="color: #d32f2f;">BELUM DIPILIH</strong>. Pilih di <a href="{{ route('schools.index') }}" style="color: #ff9800; text-decoration: underline;">Daftar Sekolah</a>.`;
             sekolahTujuanInput.value = '';
        }
    });
    function submitPendaftaran(event) {
        event.preventDefault();
        const form = document.getElementById('form-pendaftaran');
        const messageDiv = document.getElementById('pendaftaran-message');
        const sekolahTujuan = document.getElementById('sekolah-tujuan').value.trim();
        const csrfToken = document.querySelector('input[name="_token"]').value;

        if (!sekolahTujuan) {
            messageDiv.style.backgroundColor = '#ffebee';
            messageDiv.style.color = '#d32f2f';
            messageDiv.innerHTML = 'Mohon pilih **Sekolah Tujuan** terlebih dahulu dari halaman **Daftar Sekolah**.';
            return;
        }
        
        const formData = new FormData();
        formData.append('_token', csrfToken);
        formData.append('nama_siswa', document.getElementById('nama-siswa').value);
        formData.append('nisn', document.getElementById('nisn').value);
        formData.append('password_baru', document.getElementById('password-baru').value);
        formData.append('jenjang', document.getElementById('jenjang').value);
        formData.append('sekolah-tujuan', sekolahTujuan);
        formData.append('jalur', document.getElementById('jalur').value);
        formData.append('alamat', document.getElementById('alamat').value);
        
        // Append files
        const kkFile = document.getElementById('scan_kk').files[0];
        const aktaFile = document.getElementById('scan_akta').files[0];
        const ijazahFile = document.getElementById('scan_ijazah').files[0];

        if (kkFile) formData.append('scan_kk', kkFile);
        if (aktaFile) formData.append('scan_akta', aktaFile);
        if (ijazahFile) formData.append('scan_ijazah', ijazahFile);

        messageDiv.style.backgroundColor = '#e0f7fa';
        messageDiv.style.color = '#006064';
        messageDiv.innerHTML = 'Sedang memproses pendaftaran dan mengupload dokumen...';

        fetch("{{ route('register.submit') }}", {
            method: 'POST',
            body: formData 
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(errorData => { 
                    throw new Error(errorData.message || 'Gagal Mendaftar. Cek kembali data Anda.'); 
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                messageDiv.style.backgroundColor = '#e8f5e9';
                messageDiv.style.color = '#2e7d32';
                messageDiv.innerHTML = `
                    ${data.message} <br>
                    Nomor Peserta Anda: **${registrationData.nisn}**. <br>
                    Silakan cek status seleksi pada tanggal pengumuman.
                `;
                form.reset(); 
            } else {
                messageDiv.style.backgroundColor = '#ffebee';
                messageDiv.style.color = '#d32f2f';
                messageDiv.innerHTML = `Gagal Mendaftar. ${data.message}`;
            }
        })
        .catch(error => {
            messageDiv.style.backgroundColor = '#fff3e0';
            messageDiv.style.color = '#e65100';
            messageDiv.innerHTML = `Terjadi kesalahan koneksi ke server. Mohon coba lagi. (Detail: ${error.message})`;
            console.error('Error:', error);
        });
    }
</script>
@endsection