@extends('layouts.main')
@section('title', 'Daftar Sekolah - PPDB V.2.0')

@section('content')
{{-- Styles moved to public/style.css --}}

<section class="page-content container">
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
                        <option value="">-- Pilih Jenjang --</option>
                        <option value="SD">Sekolah Dasar (SD)</option>
                        <option value="SMP">Sekolah Menengah Pertama (SMP)</option>
                        <option value="SMA">Sekolah Menengah Atas (SMA)</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="jalur-filter">Jalur Pendaftaran:</label>
                    <select id="jalur-filter" class="form-control">
                        <option value="">-- Pilih Jalur --</option>
                        <option value="Zonasi">Zonasi</option>
                        <option value="Afirmasi">Afirmasi</option>
                        <option value="Prestasi">Prestasi</option>
                        <option value="Jalur Tes">Jalur Tes (Khusus SMP/SMA)</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="daerah-filter">Kabupaten/Kota (Jawa Timur):</label>
                    <select id="daerah-filter" class="form-control">
                        <option value="">-- Pilih Kota/Kabupaten --</option>
                        <!-- Options will be populated by JS -->
                    </select>
                </div>
                
                <div class="filter-group" id="group-kecamatan" style="display: none;">
                    <label for="kecamatan-filter">Kecamatan:</label>
                    <select id="kecamatan-filter" class="form-control" disabled>
                        <option value="">-- Pilih Kecamatan --</option>
                    </select>
                </div>
            </div>
            
            <div id="jalur-description" style="margin-top: 15px; padding: 10px; background-color: #e3f2fd; border-left: 4px solid #2196f3; border-radius: 4px; display: none;">
                <!-- Description text will go here -->
            </div>

            <button id="btn-cari-zonasi" class="btn-primary" style="width: 100%; margin-top: 20px;">Cari Sekolah</button>
        </div>

        <div id="nama-search" class="search-content">
            <div class="filter-controls multi-col">
                <div class="filter-group group-main">
                    <label for="nama-sekolah-filter">Nama Sekolah (Wajib Jenjang):</label>
                    <input type="text" id="nama-sekolah-filter" placeholder="Contoh: SMAN 3 Surabaya" class="form-control">
                </div>
                <div class="filter-group group-side">
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
        const schoolApiUrl = '/api/schools-search';
        console.log('Fixed School API URL:', schoolApiUrl);
        const loadingStatus = document.getElementById('loading-status');

        // Data Wilayah Jawa Timur (Kota & Kabupaten beserta Kecamatan)
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
            "Kab. Probolinggo": ["Bantaran", "Banyuanyar", "Besuk", "Dringu", "Gading", "Gending", 'Kotaanyar', 'Kraksaan', 'Krejengan', 'Krucil', 'Kuripan', 'Leces', 'Lumbang', 'Maron', 'Paiton', 'Pajarakan', 'Pakuniran', 'Sukapura', 'Sumber', 'Sumberasih', 'Tegalsiwalan', 'Tiris', 'Tongas', 'Wonomerto'],
            "Kab. Sampang": ["Banyuates", "Camplong", "Jrengik", "Karangpenang", "Kedungdung", "Ketapang", "Omben", "Pangarengan", "Robatal", "Sampang", "Sokobanah", "Sreseh", "Tambelangan", "Torjun"],
            "Kab. Sidoarjo": ["Balongbendo", "Buduran", "Candi", "Gedangan", "Jabon", "Krembung", "Krian", "Porong", "Prambon", "Sedati", "Sidoarjo", "Sukodono", "Taman", "Tanggulangin", "Tarik", "Tulangan", "Waru", "Wonoayu"],
            "Kab. Situbondo": ["Arjasa", "Asembagus", "Banyuglugur", "Banyuputih", "Besuki", "Bungatan", "Jangkar", "Jatibanteng", "Kapongan", "Kendit", "Mangaran", "Mlandingan", "Panarukan", "Panji", "Situbondo", "Suboh", "Sumbermalang"],
            "Kab. Sumenep": ["Ambunten", "Arjasa", "Batang Batang", "Batuan", "Batuputih", "Bluto", "Dasuk", "Dungkek", "Ganding", "Gapura", "Gayam", "Giligenting", "Guluk-Guluk", "Kalianget", "Kangayan", "Kota Sumenep", "Lenteng", "Manding", "Masalembu", "Nonggunong", "Pasongsongan", "Pragaan", "Raas", "Rubaru", "Sapeken", "Saronggi", "Talango"],
            "Kab. Trenggalek": ["Bandungan", "Dongko", "Durenan", "Gandusari", "Kampak", "Karangan", "Munjungan", "Panggul", "Pogalan", "Pule", "Suruh", "Trenggalek", "Tugu", "Watulimo"],
            "Kab. Tuban": ["Bancar", "Bangilan", "Grabagan", "Jatirogo", "Jenu", "Kenduruan", "Kerek", "Merakurak", "Montong", "Palang", "Parengan", "Plumpang", "Rengel", "Semanding", "Senori", "Singgahan", "Soko", "Tambakboyo", "Tuban", "Widang"],
            "Kab. Tulungagung": ["Bandung", "Besuki", "Boyolangu", "Campurdarat", "Gondang", "Kalidawir", "Karangrejo", "Kauman", "Kedungwaru", "Ngantru", "Ngunut", "Pagerwojo", "Pakel", "Pucanglaban", "Rejotangan", "Sendang", "Sumbergempol", "Tanggunggunung", "Tulungagung"]
        };

        const pathDescriptions = {
            "Zonasi": "Jalur Zonasi diperuntukkan bagi calon peserta didik yang berdomisili di dalam wilayah zonasi yang ditetapkan pemerintah daerah. Prioritas adalah jarak tempat tinggal terdekat ke sekolah.",
            "Afirmasi": "Jalur Afirmasi diperuntukkan bagi calon peserta didik dari keluarga tidak mampu dan penyandang disabilitas.",
            "Mutasi": "Jalur Perpindahan Tugas Orang Tua/Wali diperuntukkan bagi calon peserta didik yang mengikuti kepindahan tugas orang tua/wali.",
            "Prestasi": "Jalur Prestasi diperuntukkan bagi calon peserta didik yang memiliki prestasi akademik maupun non-akademik.",
            "Jalur Tes": "Jalur Tes Seleksi Mandiri atau sejenisnya (Terutama untuk Jenjang Tertentu/Sekolah Tertentu)."
        };

        // Populate Kota/Kab Dropdown
        const daerahSelect = document.getElementById('daerah-filter');
        const kecamatanSelect = document.getElementById('kecamatan-filter');
        const jalurSelect = document.getElementById('jalur-filter');
        const groupKecamatan = document.getElementById('group-kecamatan');
        const jalurDescContainer = document.getElementById('jalur-description');

        if (daerahSelect) {
             Object.keys(wilayahJatim).sort().forEach(kota => {
                 const option = document.createElement('option');
                 option.value = kota;
                 option.textContent = kota;
                 daerahSelect.appendChild(option);
             });
        }

        // Event Listener for Jalur
        if (jalurSelect) {
            jalurSelect.addEventListener('change', function() {
                const selectedJalur = this.value;
                
                // Show Description
                if (selectedJalur && pathDescriptions[selectedJalur]) {
                    jalurDescContainer.style.display = 'block';
                    jalurDescContainer.innerHTML = `<strong>Keterangan ${selectedJalur}:</strong> ${pathDescriptions[selectedJalur]}`;
                } else {
                    jalurDescContainer.style.display = 'none';
                }

                // Show/Hide Kecamatan based on "Zonasi"
                if (selectedJalur === 'Zonasi') {
                    groupKecamatan.style.display = 'block';
                } else {
                    groupKecamatan.style.display = 'none';
                    // Reset selection when hiding
                    kecamatanSelect.value = "";
                }
            });
            
            // Trigger change event on load to ensure correct state if browser cached the value
            jalurSelect.dispatchEvent(new Event('change'));
        }

        // Event Listener for Daerah (Kota/Kab)
        if (daerahSelect) {
            daerahSelect.addEventListener('change', function() {
                const selectedKota = this.value;
                
                // Reset Kecamatan Dropdown
                kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
                kecamatanSelect.disabled = true;

                if (selectedKota && wilayahJatim[selectedKota]) {
                    kecamatanSelect.disabled = false;
                    wilayahJatim[selectedKota].sort().forEach(kec => {
                        const option = document.createElement('option');
                        option.value = kec;
                        option.textContent = kec;
                        kecamatanSelect.appendChild(option);
                    });
                } else {
                    console.log('Kota not found or no kecamatan data:', selectedKota);
                }
            });
        }

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
                e.preventDefault(); 
                
                const jenjang = document.getElementById('jenjang-filter').value;
                const jalur = document.getElementById('jalur-filter').value;
                const daerah = document.getElementById('daerah-filter').value;
                const kecamatan = document.getElementById('kecamatan-filter').value;
                
                if (!jenjang || !jalur || !daerah) {
                    displayError('<p>Mohon lengkapi <strong>Jenjang</strong>, <strong>Jalur</strong>, dan <strong>Kota/Kabupaten</strong>.</p>');
                    return;
                }
                
                if (jalur === 'Zonasi' && !kecamatan) {
                    displayError('<p>Untuk jalur Zonasi, mohon pilih <strong>Kecamatan</strong>.</p>');
                    return;
                }
                
                // Note: For now we pass daerah (City) to the API. 
                // If the API supported kecamatan, we would pass it too.
                // We append kecamatan to Display if selected.
                
                performSearch(jenjang, daerah, '', kecamatan); 
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

        function performSearch(jenjang, daerah, namaSekolah, kecamatan = '') {
            if (loadingStatus) {
                loadingStatus.style.display = 'block';
                loadingStatus.style.color = '#ff9800';
                loadingStatus.innerHTML = '<p>Mencari sekolah terdekat dari server...</p>';
            }
            
            console.log('Performing search:', { jenjang, daerah, namaSekolah, kecamatan, url: schoolApiUrl });

            // Use axios if available, otherwise fallback to fetch
            // Use axios if available, otherwise fallback to fetch
            if (window.axios) {
                console.log('Making Axios request to:', schoolApiUrl);
                window.axios.get(schoolApiUrl, {
                    params: {
                        jenjang: jenjang,
                        daerah: daerah, 
                        nama_sekolah: namaSekolah,
                        kecamatan: kecamatan
                    }
                })
                .then(response => {
                    console.log('Search response:', response.data);
                    handleResponse(response.data, jenjang, daerah, kecamatan);
                })
                .catch(error => {
                    console.error('Search error:', error);
                    handleError(error);
                });
            } else {
                // Fallback to fetch if axios is not ready
                console.warn('Axios not found, falling back to fetch');
                const params = new URLSearchParams({
                    jenjang: jenjang,
                    daerah: daerah,
                    nama_sekolah: namaSekolah || '',
                    kecamatan: kecamatan || ''
                });

                fetch(`${schoolApiUrl}?${params.toString()}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                .then(res => res.json())
                .then(data => handleResponse(data, jenjang, daerah, kecamatan))
                .catch(err => handleError(err));
            }
        }

        function handleResponse(data, jenjang, daerah, kecamatan) {
            if (loadingStatus) loadingStatus.style.display = 'none';
            if (data.status === 'success') {
                tampilkanHasilFilter(jenjang, daerah, data.schools, null, kecamatan);
            } else {
                tampilkanHasilFilter(jenjang, daerah, [], data.message, kecamatan);
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

        function createSummaryHTML(jenjang, daerah, count, kecamatan) {
            const maxChoice = jenjang === 'SD' ? 1 : (jenjang === 'SMP' ? 3 : 5);
            const jenjangFull = jenjang === 'SD' ? 'Sekolah Dasar (SD)' : (jenjang === 'SMP' ? 'Sekolah Menengah Pertama (SMP)' : 'Sekolah Menengah Atas (SMA)');
            
            let areaDisplay = daerah ? `Sekitar ${daerah}` : 'Hasil Pencarian';
            if (kecamatan) {
                areaDisplay += `, Kec. ${kecamatan}`;
            }

            return `
                <div class="school-summary-card">
                    <div class="summary-icon">🎓</div>
                    <div class="summary-content">
                        <h4 class="summary-subtitle">${jenjangFull}</h4>
                        <h2 class="summary-title">${count} Sekolah di ${areaDisplay}</h2>
                        <span class="max-choice-badge">Pilihan Maksimal: ${maxChoice} Sekolah</span>
                    </div>
                </div>
            `;
        }

        function createDetailsHTML(jenjang, daerah, schoolData, kecamatan) {
            const count = schoolData.length;
            if (count === 0) {
                return `<div class="empty-state">
                            <i class="fas fa-search" style="font-size: 3em; color: #cbd5e0; margin-bottom: 15px;"></i>
                            <h3>Tidak ada sekolah ditemukan untuk jenjang ${jenjang}.</h3>
                            <p>Coba ubah filter pencarian Anda.</p>
                        </div>`;
            }

            // Check if student is logged in and pending (passed from controller)
            const student = @json($student ?? null);
            const isPending = student && student.status_seleksi === 'Pending';
            const studentJenjang = student ? student.jenjang_tujuan : null;

            let listItems = schoolData.map((school, index) => {
                const encodedSchoolName = encodeURIComponent(school.nama_sekolah);
                
                let actionButton = '';
                if (isPending) {
                    if (school.jenjang === studentJenjang) {
                         actionButton = `<button class="btn-primary btn-update-school" data-school="${school.nama_sekolah}" style="width: 100%; margin-top: 10px;">Pilih Sekolah Ini</button>`;
                    } else {
                         actionButton = `<button class="btn-secondary" style="background-color: #ccc; cursor: not-allowed; width: 100%; margin-top: 10px;" disabled title="Jenjang tidak sesuai">Tidak Sesuai Jenjang</button>`;
                    }
                } else {
                    actionButton = `<a href="pendaftaran?sekolah=${encodedSchoolName}" class="btn-primary" style="display: block; text-align: center; margin-top: 10px; text-decoration: none;">Daftar Sekarang</a>`;
                }

                return `
                    <div class="school-card">
                        <div class="school-icon">🏫</div>
                        <div class="school-info">
                            <h4>${school.nama_sekolah}</h4>
                            <p class="school-location"><i class="fas fa-map-marker-alt"></i> ${school.kota_kab || ''}, Kec. ${school.kecamatan || '-'}</p>
                            <div class="school-meta">
                                <span><i class="fas fa-users"></i> Kuota: ${school.kuota || '-'}</span>
                            </div>
                            <p class="school-desc">${school.detail || 'Informasi umum sekolah tersedia.'}</p>
                        </div>
                        <div class="school-actions">
                            ${actionButton}
                            <a href="#" class="link-secondary" style="display: block; text-align: center; margin-top: 10px; font-size: 0.9em;">Lihat Peta Zonasi</a>
                        </div>
                    </div>
                `;
            }).join('');

            let titleArea = daerah || 'Pencarian';
            if (kecamatan) titleArea += `, Kec. ${kecamatan}`;

            return `
                <div class="results-header">
                    <h3>Daftar Sekolah Jenjang ${jenjang}</h3>
                    <span class="badge-count">${count} Sekolah Ditemukan</span>
                </div>
                <p>Menampilkan hasil di area <strong>${titleArea}</strong></p>
                <div class="school-card-grid">
                    ${listItems}
                </div>
            `;
        }
        
        // Add event listener for dynamic "Pilih Sekolah Ini" buttons
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('btn-update-school')) {
                e.preventDefault();
                const newSchool = e.target.getAttribute('data-school');
                if (confirm(`Apakah Anda yakin ingin mengganti sekolah tujuan ke ${newSchool}?`)) {
                    updateSchool(newSchool);
                }
            }
        });

        function updateSchool(newSchool) {
            if (loadingStatus) {
                loadingStatus.style.display = 'block';
                loadingStatus.innerHTML = '<p>Memproses perubahan sekolah...</p>';
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            fetch("{{ route('student.update.school') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ sekolah_baru: newSchool })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);
                    window.location.href = "{{ route('student.dashboard') }}";
                } else {
                    alert(data.message);
                    if (loadingStatus) loadingStatus.style.display = 'none';
                }
            })
            .catch(err => {
                console.error(err);
                alert('Terjadi kesalahan saat menghubungi server.');
                if (loadingStatus) loadingStatus.style.display = 'none';
            });
        }

        function tampilkanHasilFilter(jenjang, daerah, schoolData, errorMessage = null, kecamatan = '') {
            const summaryDiv = document.getElementById('school-summary');
            const detailsDiv = document.getElementById('school-details');
            
            if (summaryDiv) {
                summaryDiv.innerHTML = '';
                if (!errorMessage) {
                    summaryDiv.style.display = 'flex';
                    summaryDiv.innerHTML = createSummaryHTML(jenjang, daerah, schoolData.length, kecamatan);
                }
            }

            if (detailsDiv) {
                detailsDiv.innerHTML = '';
                detailsDiv.style.display = 'block';
                if (errorMessage) {
                    detailsDiv.innerHTML = `<h3 style="color:#d32f2f;">${errorMessage}</h3>`;
                } else {
                    detailsDiv.innerHTML = createDetailsHTML(jenjang, daerah, schoolData, kecamatan);
                }
            }
        }
    })();
</script>
@endsection


