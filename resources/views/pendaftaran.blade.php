@extends('layouts.main')
@section('title', 'Formulir Pendaftaran - SIPENDA')

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
            <label for="kota_kab">Kota/Kabupaten (Sesuai KK):</label>
            <select id="kota_kab" name="kota_kab" required>
                <option value="">-- Pilih Kota/Kabupaten --</option>
                <!-- Opsi akan diisi via JS -->
            </select>

            <label for="kecamatan">Kecamatan (Sesuai KK):</label>
            <select id="kecamatan" name="kecamatan" required disabled>
                <option value="">-- Pilih Kota/Kab Terlebih Dahulu --</option>
            </select>

            <label for="alamat">Detail Alamat (Jalan, RT/RW):</label>
            <textarea id="alamat" name="alamat" rows="2" required placeholder="Contoh: Jl. Mawar No. 10, RT 01 RW 02"></textarea>
            
            <p style="margin-top: 20px; font-weight: 600;">Upload Dokumen Persyaratan</p>
            <div class="file-input-group">
                <label for="scan_kk">Scan Kartu Keluarga (KK):</label>
                <input type="file" id="scan_kk" name="scan_kk" accept=".jpg,.jpeg,.png,.pdf" required>
            </div>
            
            <div class="file-input-group">
                <label for="scan_akta">Scan Akta Kelahiran:</label>
                <input type="file" id="scan_akta" name="scan_akta" accept=".jpg,.jpeg,.png,.pdf" required>
            </div>
            
            <div class="file-input-group">
                <label for="scan_ijazah">Scan Ijazah / SKL:</label>
                <input type="file" id="scan_ijazah" name="scan_ijazah" accept=".jpg,.jpeg,.png,.pdf" required>
            </div>

            <div class="file-input-group" id="container-prestasi" style="display: none;">
                <label for="scan_prestasi">Bukti Prestasi (Sertifikat/Piagam):</label>
                <input type="file" id="scan_prestasi" name="scan_prestasi" accept=".jpg,.jpeg,.png,.pdf">
                <small style="color: #666; display: block; margin-top: 5px;">*Wajib diupload jika memilih jalur Prestasi.</small>
            </div>
            
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
        // Data Wilayah Jawa Timur
        const wilayahJatim = {
            "Kota Surabaya": ["Asemrowo", "Benowo", "Bubutan", "Bulak", "Dukuh Pakis", "Gayungan", "Genteng", "Gubeng", "Gunung Anyar", "Jambangan", "Karang Pilang", "Kenjeran", "Krembangan", "Lakarsantri", "Mulyorejo", "Pabean Cantian", "Pakal", "Rungkut", "Sambikerep", "Sawahan", "Semampir", "Simokerto", "Sukolilo", "Sukomanunggal", "Tambaksari", "Tandes", "Tegalsari", "Tenggilis Mejoyo", "Wiyung", "Wonocolo", "Wonokromo"],
            "Kota Malang": ["Blimbing", "Kedungkandang", "Klojen", "Lowokwaru", "Sukun"],
            "Kota Madiun": ["Kartoharjo", "Manguharjo", "Taman"],
            "Kota Kediri": ["Kota", "Mojoroto", "Pesantren"],
            "Kota Mojokerto": ["Kranggan", "Magersari", "Prajurit Kulon"],
            "Kota Blitar": ["Kepanjenkidul", "Sananwetan", "Sukorejo"],
            "Kota Pasuruan": ["Bugul Kidul", "Gadingrejo", "Panggungrejo", "Purworejo"],
            "Kota Probolinggo": ["Kademangan", "Kanigaran", "Kedopok", "Mayangan", "Wonoasih"],
            "Kota Batu": ["Batu", "Bumiaji", "Junrejo"],
            "Kab. Bangkalan": ["Arosbaya", "Bangkalan", "Blega", "Burneh", "Galis", "Geger", "Kamal", "Klampis", "Kokop", "Konang", "Kwanyar", "Labang", "Modung", "Sepulu", "Socah", "Tanah Merah", "Tanjung Bumi", "Tragah"],
            "Kab. Banyuwangi": ["Bangorejo", "Banyuwangi", "Blimbingsari", "Cluring", "Gambiran", "Genteng", "Giri", "Glagah", "Glenmore", "Kabat", "Kalibaru", "Kalipuro", "Licin", "Muncar", "Pesanggaran", "Purwoharjo", "Rogojampi", "Sempu", "Siliragung", "Singojuruh", "Songgon", "Srono", "Tegaldlimo", "Tegalsari", "Wongsorejo"],
            "Kab. Blitar": ["Bakung", "Binangun", "Doko", "Gandusari", "Garum", "Kademangan", "Kanigoro", "Kesamben", "Nglegok", "Panggungrejo", "Ponggok", "Sanankulon", "Selorejo", "Selopuro", "Srengat", "Sutojayan", "Talun", "Udanawu", "Wates", "Wlingi", "Wonodadi", "Wonotirto"],
            "Kab. Bojonegoro": ["Balen", "Baureno", "Bojonegoro", "Bubulan", "Dander", "Gayam", "Gondang", "Kadungadem", "Kalitidu", "Kanor", "Kapas", "Kasiman", "Kedungadem", "Kepohbaru", "Malo", "Margomulyo", "Ngambon", "Ngasem", "Ngraho", "Padangan", "Purwosari", "Sekar", "Sugihwaras", "Sukosewu", "Sumberejo", "Tambakrejo", "Temayang", "Trucuk"],
            "Kab. Bondowoso": ["Binakal", "Bondowoso", "Botolinggo", "Cermee", "Curahdami", "Grujugan", "Jambesari Darus Sholah", "Klabang", "Maesan", "Pakem", "Prajekan", "Pujer", "Sempol (Ijen)", "Sukosari", "Sumberwringin", "Taman Krocok", "Tamanan", "Tapen", "Tegalampel", "Tenggarang", "Tlogosari", "Wonosari", "Wringin"],
            "Kab. Gresik": ["Balongpanggang", "Benjeng", "Bungah", "Cerme", "Driyorejo", "Duduksampeyan", "Dukun", "Gresik", "Kebomas", "Kedamean", "Manyar", "Menganti", "Panceng", "Sangkapura", "Sidayu", "Tambak", "Ujungpangkah", "Wringinanom"],
            "Kab. Jember": ["Ajung", "Ambulu", "Arjasa", "Balung", "Bangsalsari", "Gumukmas", "Jelbuk", "Jenggawah", "Jombang", "Kalisat", "Kaliwates", "Kencong", "Ledokombo", "Mayang", "Mumbulsari", "Pakusari", "Panti", "Patrang", "Puger", "Rambipuji", "Semboro", "Silo", "Sukorambi", "Sukowono", "Sumberbaru", "Sumberjambe", "Sumbersari", "Tanggul", "Tempurejo", "Umbulsari", "Wuluhan"],
            "Kab. Jombang": ["Bandar Kedungmulyo", "Bareng", "Diwek", "Gudo", "Jogoroto", "Jombang", "Kabuh", "Kesamben", "Kudu", "Megaluh", "Mojoagung", "Mojowarno", "Ngoro", "Ngusikan", "Perak", "Peterongan", "Plandaan", "Ploso", "Sumobito", "Tembelang", "Wonosalam"],
            "Kab. Kediri": ["Badas", "Banyakan", "Gampengrejo", "Grogol", "Gurah", "Kandangan", "Kandat", "Kayen Kidul", "Kepung", "Kras", "Kunjang", "Mojo", "Ngadiluwih", "Ngancar", "Ngasem", "Pagu", "Papar", "Pare", "Plemahan", "Plosoklaten", "Puncu", "Purwoasri", "Ringinrejo", "Semen", "Tarokan", "Wates"],
            "Kab. Lamongan": ["Babat", "Bluluk", "Brondong", "Deket", "Glagah", "Kalitengah", "Karangbinangun", "Karanggeneng", "Kedungpring", "Kembangbahu", "Lamongan", "Laren", "Maduran", "Mantup", "Modo", "Ngimbang", "Paciran", "Pucuk", "Sambeng", "Sarirejo", "Sekaran", "Solokuro", "Sugio", "Sukodadi", "Sukorame", "Tikung", "Turi"],
            "Kab. Lumajang": ["Candipuro", "Gucialit", "Jatiroto", "Kedungjajang", "Klakah", "Kunir", "Lumajang", "Padang", "Pasirian", "Pasrujambe", "Pronojiwo", "Randuagung", "Ranuyoso", "Rowokangkung", "Senduro", "Sukodono", "Sumbersuko", "Tekung", "Tempeh", "Tempursari", "Yosowilangun"],
            "Kab. Madiun": ["Balerejo", "Dagangan", "Dolopo", "Geger", "Gemarang", "Jiwan", "Kare", "Kebonagung", "Madiun", "Mejayan", "Pilangkenceng", "Saradan", "Sawahan", "Wonoasri", "Wungu"],
            "Kab. Magetan": ["Barat", "Bendo", "Karangrejo", "Karas", "Kartoharjo", "Kawedanan", "Lembeyan", "Magetan", "Maospati", "Ngariboyo", "Nguntoronadi", "Panekan", "Parang", "Plaosan", "Poncol", "Sidorejo", "Sukomoro", "Takeran"],
            "Kab. Malang": ["Ampelgading", "Bantur", "Bululawang", "Dampit", "Dau", "Donomulyo", "Gedangan", "Gondanglegi", "Jabung", "Kalipare", "Karangploso", "Kasembon", "Kepanjen", "Kromengan", "Lawang", "Ngajum", "Ngantang", "Pagak", "Pagelaran", "Pakis", "Pakisaji", "Poncokusumo", "Pujon", "Singosari", "Sumbermanjing Wetan", "Sumberpucung", "Tajinan", "Tirtoyudo", "Tumpang", "Turen", "Wagir", "Wajak", "Wonosari"],
            "Kab. Mojokerto": ["Bangsal", "Dawarblandong", "Dlanggu", "Gedeg", "Gondang", "Jatirejo", "Jetis", "Kemlagi", "Kutorejo", "Mojoanyar", "Mojosari", "Ngoro", "Pacet", "Pungging", "Puri", "Sooko", "Trawas", "Trowulan"],
            "Kab. Nganjuk": ["Bagor", "Baron", "Berbek", "Gondang", "Jatikalen", "Kertosono", "Lengkong", "Loceret", "Nganjuk", "Ngetos", "Ngluyu", "Ngronggot", "Pace", "Patianrowo", "Prambon", "Rejoso", "Sawahan", "Sukomoro", "Tanjunganom", "Wilangan"],
            "Kab. Ngawi": ["Bringin", "Geneng", "Gerih", "Jogorogo", "Karanganyar", "Karangjati", "Kasreman", "Kedunggalar", "Kendal", "Kwadungan", "Mantingan", "Ngawi", "Ngrambe", "Padas", "Pangkur", "Paron", "Pitu", "Sine", "Widodaren"],
            "Kab. Pacitan": ["Arjosari", "Bandar", "Donorojo", "Kebonagung", "Nawangan", "Ngadirojo", "Pacitan", "Pringkuku", "Punung", "Sudimoro", "Tegalombo", "Tulakan"],
            "Kab. Pamekasan": ["Batu Marmar", "Galis", "Kadur", "Larangan", "Pademawu", "Pakong", "Palengaan", "Pamekasan", "Pasean", "Pegantenan", "Proppo", "Tlanakan", "Waru"],
            "Kab. Pasuruan": ["Bangil", "Beji", "Gempol", "Gondang Wetan", "Grati", "Kejayan", "Kraton", "Lekok", "Lumbang", "Nguling", "Pandaan", "Pasrepan", "Pohjentrek", "Prigen", "Purwodadi", "Purwosari", "Puspo", "Rejoso", "Rembang", "Sukorejo", "Tosari", "Tutur", "Winongan", "Wonorejo"],
            "Kab. Ponorogo": ["Babadan", "Badegan", "Balong", "Bungkal", "Jambon", "Jenangan", "Jetis", "Kauman", "Mlarak", "Ngebel", "Ngrayun", "Ponorogo", "Pudak", "Pulung", "Sambit", "Sampung", "Sawoo", "Siman", "Slahung", "Sooko", "Sukorejo"],
            "Kab. Probolinggo": ["Bantaran", "Banyuanyar", "Besuk", "Dringu", "Gading", "Gending", "Kotaanyar", "Kraksaan", "Krejengan", "Krucil", "Kuripan", "Leces", "Lumbang", "Maron", "Paiton", "Pajarakan", "Pakuniran", "Sukapura", "Sumber", "Sumberasih", "Tegalsiwalan", "Tiris", "Tongas", "Wonomerto"],
            "Kab. Sampang": ["Banyuates", "Camplong", "Jrengik", "Karangpenang", "Kedungdung", "Ketapang", "Omben", "Pangarengan", "Robatal", "Sampang", "Sokobanah", "Sreseh", "Tambelangan", "Torjun"],
            "Kab. Sidoarjo": ["Balongbendo", "Buduran", "Candi", "Gedangan", "Jabon", "Krembung", "Krian", "Porong", "Prambon", "Sedati", "Sidoarjo", "Sukodono", "Taman", "Tanggulangin", "Tarik", "Tulangan", "Waru", "Wonoayu"],
            "Kab. Situbondo": ["Arjasa", "Asembagus", "Banyuglugur", "Banyuputih", "Besuki", "Bungatan", "Jangkar", "Jatibanteng", "Kapongan", "Kendit", "Mangaran", "Mlandingan", "Panarukan", "Panji", "Situbondo", "Suboh", "Sumbermalang"],
            "Kab. Sumenep": ["Ambunten", "Arjasa", "Batang Batang", "Batuan", "Batuputih", "Bluto", "Dasuk", "Dungkek", "Ganding", "Gapura", "Gayam", "Giligenting", "Guluk-Guluk", "Kalianget", "Kangayan", "Kota Sumenep", "Lenteng", "Manding", "Masalembu", "Nonggunong", "Pasongsongan", "Pragaan", "Raas", "Rubaru", "Sapeken", "Saronggi", "Talango"],
            "Kab. Trenggalek": ["Bandungan", "Dongko", "Durenan", "Gandusari", "Kampak", "Karangan", "Munjungan", "Panggul", "Pogalan", "Pule", "Suruh", "Trenggalek", "Tugu", "Watulimo"],
            "Kab. Tuban": ["Bancar", "Bangilan", "Grabagan", "Jatirogo", "Jenu", "Kenduruan", "Kerek", "Merakurak", "Montong", "Palang", "Parengan", "Plumpang", "Rengel", "Semanding", "Senori", "Singgahan", "Soko", "Tambakboyo", "Tuban", "Widang"],
            "Kab. Tulungagung": ["Bandung", "Besuki", "Boyolangu", "Campurdarat", "Gondang", "Kalidawir", "Karangrejo", "Kauman", "Kedungwaru", "Ngantru", "Ngunut", "Pagerwojo", "Pakel", "Pucanglaban", "Rejotangan", "Sendang", "Sumbergempol", "Tanggunggunung", "Tulungagung"]
        };

        const kotaKabSelect = document.getElementById('kota_kab');
        const kecamatanSelect = document.getElementById('kecamatan');

        // Populate Kota/Kabupaten Options
        Object.keys(wilayahJatim).forEach(kota => {
            const option = document.createElement('option');
            option.value = kota;
            option.textContent = kota;
            kotaKabSelect.appendChild(option);
        });

        // Event Listener for Kota/Kabupaten Change
        kotaKabSelect.addEventListener('change', function() {
            const selectedKota = this.value;
            
            // Allow re-enabling if valid
            kecamatanSelect.disabled = !selectedKota;
            kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';

            if (selectedKota && wilayahJatim[selectedKota]) {
                wilayahJatim[selectedKota].forEach(kecamatan => {
                    const option = document.createElement('option');
                    option.value = kecamatan;
                    option.textContent = kecamatan;
                    kecamatanSelect.appendChild(option);
                });
            }
        });

        const params = new URLSearchParams(window.location.search);
        const sekolah = params.get('sekolah');
        
        const sekolahTujuanInput = document.getElementById('sekolah-tujuan');
        const targetSekolahH3 = document.getElementById('target-sekolah');
        const jenjangSelect = document.getElementById('jenjang');
        const jalurSelect = document.getElementById('jalur');
        const containerPrestasi = document.getElementById('container-prestasi');
        const inputPrestasi = document.getElementById('scan_prestasi');

        function updateJalurOptions() {
            const jenjang = jenjangSelect.value;
            const currentJalur = jalurSelect.value;
            
            // Clear existing options
            jalurSelect.innerHTML = '<option value="">-- Pilih Jalur --</option>';
            
            if (jenjang === 'SD') {
                addOption(jalurSelect, 'Zonasi', 'Zonasi');
            } else if (jenjang === 'SMP' || jenjang === 'SMA') {
                addOption(jalurSelect, 'Zonasi', 'Zonasi');
                addOption(jalurSelect, 'Prestasi', 'Prestasi');
                addOption(jalurSelect, 'Jalur Tes', 'Jalur Tes');
            } else {
                // Default options if no jenjang selected (or keep empty)
            }
            
            // Restore previous selection if valid
            if (Array.from(jalurSelect.options).some(option => option.value === currentJalur)) {
                jalurSelect.value = currentJalur;
            } else {
                jalurSelect.value = '';
            }
            updatePrestasiVisibility();
        }

        function addOption(select, value, text) {
            const option = document.createElement('option');
            option.value = value;
            option.textContent = text;
            select.appendChild(option);
        }

        function updatePrestasiVisibility() {
            if (jalurSelect.value === 'Prestasi') {
                containerPrestasi.style.display = 'block';
                inputPrestasi.required = true;
            } else {
                containerPrestasi.style.display = 'none';
                inputPrestasi.required = false;
                inputPrestasi.value = ''; // Reset file input
            }
        }

        jenjangSelect.addEventListener('change', updateJalurOptions);
        jalurSelect.addEventListener('change', updatePrestasiVisibility);

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
            // Trigger update after setting value
            updateJalurOptions();
        } else {
             targetSekolahH3.innerHTML = `Target Sekolah: <strong style="color: #d32f2f;">BELUM DIPILIH</strong>. Pilih di <a href="{{ route('schools.index') }}" style="color: #ff9800; text-decoration: underline;">Daftar Sekolah</a>.`;
             sekolahTujuanInput.value = '';
             updateJalurOptions(); // Initialize based on empty or default
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
        formData.append('kota_kab', document.getElementById('kota_kab').value);
        formData.append('kecamatan', document.getElementById('kecamatan').value);
        formData.append('alamat', document.getElementById('alamat').value);
        
        // Append files
        const kkFile = document.getElementById('scan_kk').files[0];
        const aktaFile = document.getElementById('scan_akta').files[0];
        const ijazahFile = document.getElementById('scan_ijazah').files[0];
        const prestasiFile = document.getElementById('scan_prestasi').files[0];

        if (kkFile) formData.append('scan_kk', kkFile);
        if (aktaFile) formData.append('scan_akta', aktaFile);
        if (ijazahFile) formData.append('scan_ijazah', ijazahFile);
        if (prestasiFile) formData.append('scan_prestasi', prestasiFile);

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
                    let detailedErrors = '';
                    if (errorData.errors) {
                        detailedErrors = '<ul style="text-align:left; margin-top:5px; padding-left: 20px;">' +
                            Object.values(errorData.errors).flat().map(err => `<li>${err}</li>`).join('') +
                            '</ul>';
                    }
                    
                    // Throw an error with the formatted message
                    const error = new Error((errorData.message || 'Gagal Mendaftar. Cek kembali data Anda.'));
                    error.details = detailedErrors; // Attach details to error object
                    throw error;
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
                    Nomor Peserta Anda: **${data.nisn}**. <br>
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
            messageDiv.style.backgroundColor = '#ffebee';
            messageDiv.style.color = '#d32f2f';
            
            // Check if we have attached details (validation error)
            if (error.details) {
                messageDiv.innerHTML = `<strong>${error.message}</strong>${error.details}`;
            } else {
                // Fallback for network errors or other unexpected errors
                messageDiv.innerHTML = `Terjadi kesalahan. (Detail: ${error.message})`;
            }
            console.error('Error:', error);
        });
    }
</script>
@endsection