<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kerangka Sistem & Analisis Kode</title>
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --secondary: #ec4899;
            --bg-color: #f3f4f6;
            --card-bg: #ffffff;
            --text-main: #1f2937;
            --text-muted: #6b7280;
            --border: #e5e7eb;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            margin: 0;
            padding: 2rem;
            line-height: 1.6;
        }
        h1, h2, h3 {
            color: var(--text-main);
            margin-bottom: 1rem;
        }
        h1 { font-size: 2.5rem; text-align: center; color: var(--primary); margin-bottom: 2rem; }
        h2 { border-bottom: 2px solid var(--primary); padding-bottom: 0.5rem; margin-top: 3rem; }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .card {
            background: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
            font-size: 0.95rem;
        }
        th, td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border);
            text-align: left;
        }
        th {
            background-color: var(--primary);
            color: white;
            font-weight: 600;
        }
        tr:hover { background-color: #f9fafb; }
        
        /* Diagram Blocks */
        .diagram-container {
            background: #fafafa;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 1rem;
            overflow-x: auto;
            display: flex;
            justify-content: center;
        }
        
        /* Code Analysis */
        .issue-list {
            list-style: none;
            padding: 0;
        }
        .issue-item {
            background: #fff1f2;
            border-left: 4px solid var(--secondary);
            padding: 1rem;
            margin-bottom: 0.5rem;
            border-radius: 0 4px 4px 0;
        }
        .issue-title {
            font-weight: bold;
            color: #9d174d;
            display: block;
            margin-bottom: 0.25rem;
        }
        .issue-desc {
            font-size: 0.9rem;
            color: var(--text-muted);
        }
        
        .nav-back {
            display: inline-block;
            margin-bottom: 1rem;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }
        .nav-back:hover { text-decoration: underline; }

        /* Badge */
        .badge {
            display: inline-block;
            padding: 0.25em 0.6em;
            font-size: 0.75em;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.375rem;
            background-color: var(--secondary);
            color: white;
        }
        
        /* Download Button */
        .btn-download {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
            padding: 0.5rem 1rem;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
            text-decoration: none;
        }
        .btn-download:hover {
            background-color: var(--primary-dark);
        }
        .btn-download svg {
            width: 16px;
            height: 16px;
            fill: currentColor;
        }
    </style>
    <!-- Mermaid JS for Diagrams -->
    <script type="module">
        import mermaid from 'https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.esm.min.mjs';
        mermaid.initialize({ startOnLoad: true, theme: 'neutral' });
    </script>
</head>
<body>

<div class="container">
    <a href="{{ route('home') }}" class="nav-back">&larr; Kembali ke Beranda</a>
    
    <h1>Dokumentasi Sistem & Analisis</h1>

    <!-- 1. STRUCTURE ERD -->
    <section id="erd">
        <div class="card">
            <h2>1. Struktur Entity Relationship Diagram (ERD)</h2>
            <p>Berikut adalah struktur tabel database yang digunakan dalam aplikasi:</p>
            
            <h3>Tabel: Users (Admin Dinas)</h3>
            <table>
                <thead><tr><th>Kolom</th><th>Tipe Data</th><th>Keterangan</th></tr></thead>
                <tbody>
                    <tr><td>id</td><td>BigInt (PK)</td><td>Primary Key</td></tr>
                    <tr><td>name</td><td>String</td><td>Nama Admin</td></tr>
                    <tr><td>username</td><td>String</td><td>Username Login (Unique)</td></tr>
                    <tr><td>email</td><td>String</td><td>Email Admin (Unique)</td></tr>
                    <tr><td>password</td><td>String</td><td>Hashed Password</td></tr>
                    <tr><td>timestamps</td><td>Timestamp</td><td>Created_at, Updated_at</td></tr>
                </tbody>
            </table>

            <h3>Tabel: Schools (Sekolah)</h3>
            <table>
                <thead><tr><th>Kolom</th><th>Tipe Data</th><th>Keterangan</th></tr></thead>
                <tbody>
                    <tr><td>id</td><td>BigInt (PK)</td><td>Primary Key</td></tr>
                    <tr><td>npsn</td><td>String</td><td>Nomor Pokok Sekolah Nasional</td></tr>
                    <tr><td>nama_sekolah</td><td>String</td><td>Nama Sekolah (Unique)</td></tr>
                    <tr><td>jenjang</td><td>String</td><td>SD, SMP, SMA</td></tr>
                    <tr><td>kuota</td><td>Integer</td><td>Kuota Penerimaan</td></tr>
                    <tr><td>detail</td><td>Text</td><td>Deskripsi/Detail Sekolah</td></tr>
                    <tr><td>kota_kab</td><td>String</td><td>Kota/Kabupaten</td></tr>
                    <tr><td>kecamatan</td><td>String</td><td>Kecamatan</td></tr>
                    <tr><td>link_administrasi</td><td>String</td><td>Link Grup WA/Admin</td></tr>
                    <tr><td>timestamps</td><td>Timestamp</td><td>Created_at, Updated_at</td></tr>
                </tbody>
            </table>

            <h3>Tabel: Operators (Operator Sekolah)</h3>
            <table>
                <thead><tr><th>Kolom</th><th>Tipe Data</th><th>Keterangan</th></tr></thead>
                <tbody>
                    <tr><td>id</td><td>BigInt (PK)</td><td>Primary Key</td></tr>
                    <tr><td>username</td><td>String</td><td>Username Login</td></tr>
                    <tr><td>nama_operator</td><td>String</td><td>Nama Lengkap Operator</td></tr>
                    <tr><td>sekolah_tujuan</td><td>String</td><td>Nama Sekolah yang Dikelola (Harusnya FK)</td></tr>
                    <tr><td>password_hash</td><td>String</td><td>Hashed Password (Custom column)</td></tr>
                    <tr><td>timestamps</td><td>Timestamp</td><td>Created_at, Updated_at</td></tr>
                </tbody>
            </table>

            <h3>Tabel: Students (Pendaftar)</h3>
            <table>
                <thead><tr><th>Kolom</th><th>Tipe Data</th><th>Keterangan</th></tr></thead>
                <tbody>
                    <tr><td>id</td><td>BigInt (PK)</td><td>Primary Key</td></tr>
                    <tr><td>nisn</td><td>String</td><td>NISN (Unique)</td></tr>
                    <tr><td>nama_lengkap</td><td>String</td><td>Nama Siswa</td></tr>
                    <tr><td>jenjang_tujuan</td><td>String</td><td>Jenjang yg didaftar</td></tr>
                    <tr><td>sekolah_tujuan</td><td>String</td><td>Nama Sekolah Tujuan (Harusnya FK)</td></tr>
                    <tr><td>jalur_pendaftaran</td><td>String</td><td>Zonasi, Prestasi, Tes</td></tr>
                    <tr><td>alamat</td><td>Text</td><td>Alamat Domisili</td></tr>
                    <tr><td>kota_kab, kecamatan</td><td>String</td><td>Lokasi</td></tr>
                    <tr><td>password_hash</td><td>String</td><td>Hashed Password (Custom column)</td></tr>
                    <tr><td>status_seleksi</td><td>Enum</td><td>Pending, Diterima, Ditolak</td></tr>
                    <tr><td>status_approval</td><td>Enum</td><td>Pending, Approved, Rejected (Oleh Dinas)</td></tr>
                    <tr><td>jadwal_test</td><td>Date</td><td>Untuk Jalur Tes</td></tr>
                    <tr><td>scan_...</td><td>String</td><td>Path Dokumen (KK, Akta, Ijazah, Prestasi)</td></tr>
                </tbody>
            </table>
            
            <div class="diagram-container" id="diag-erd">
                <div class="mermaid">
                    erDiagram
                        USERS {
                            bigint id PK
                            string username
                            string name
                        }
                        SCHOOLS {
                            bigint id PK
                            string nama_sekolah UK
                            string jenjang
                        }
                        OPERATORS {
                            bigint id PK
                            string username
                            string sekolah_tujuan FK
                        }
                        STUDENTS {
                            bigint id PK
                            string nisn UK
                            string sekolah_tujuan FK
                            enum status_seleksi
                            enum status_approval
                        }
                        SCHOOLS ||--o{ OPERATORS : manages
                        SCHOOLS ||--o{ STUDENTS : receives
                        USERS ||--o{ STUDENTS : final_approval
                </div>
            </div>
            <button onclick="downloadDiagram('diag-erd', 'ERD_Sistemp_PPDB')" class="btn-download">
                <svg viewBox="0 0 24 24"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>
                Download ERD (PNG)
            </button>
        </div>
    </section>

    <!-- 2. DFD -->
    <section id="dfd">
        <div class="card">
            <h2>2. Data Flow Diagram (DFD)</h2>
            
            <h3>Level 0 (Context Diagram)</h3>
            <div class="diagram-container" id="diag-dfd0">
                <div class="mermaid">
                    flowchart LR
                        Siswa[Calon Siswa] <-->|Data Pendaftaran / Status| SIPENDA((Sistem PPDB))
                        Op[Operator Sekolah] <-->|Verifikasi Berkas / Hasil Seleksi| SIPENDA
                        Admin[Admin Dinas] <-->|Approval Akhir / Monitoring| SIPENDA
                </div>
            </div>
            <button onclick="downloadDiagram('diag-dfd0', 'DFD_Level_0')" class="btn-download">
                <svg viewBox="0 0 24 24"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>
                Download DFD Level 0 (PNG)
            </button>

            <h3>Level 1 (Process Breakdown)</h3>
            <div class="diagram-container" id="diag-dfd1">
                <div class="mermaid">
                    flowchart TD
                        S[Siswa] -->|1. Input Data & Berkas| P1(Proses Pendaftaran)
                        P1 --> DB[(Database)]
                        
                        DB -->|Data Pendaftar| P2(Proses Seleksi Operator)
                        Op[Operator] -->|2. Verifikasi & Update Status| P2
                        P2 --> DB
                        
                        DB -->|Siswa Diterima/Ditolak| P3(Proses Approval Admin)
                        Admin -->|3. Final Approval| P3
                        P3 --> DB
                        
                        DB -->|Lihat Pengumuman| S
                </div>
            </div>
            <button onclick="downloadDiagram('diag-dfd1', 'DFD_Level_1')" class="btn-download">
                <svg viewBox="0 0 24 24"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>
                Download DFD Level 1 (PNG)
            </button>
            
            <h3>Level 2 (Detail Pendaftaran & Seleksi)</h3>
            <div class="diagram-container" id="diag-dfd2">
                <div class="mermaid">
                    sequenceDiagram
                        participant Siswa
                        participant Sistem
                        participant Operator
                        
                        Siswa->>Sistem: Isi Form Pendaftaran
                        Sistem->>Sistem: Validasi Input (Dokumen, Zonasi)
                        Sistem-->>Siswa: Berhasil Daftar (Pending)
                        
                        Operator->>Sistem: Lihat Data Pendaftar
                        Operator->>Sistem: Cek Berkas (KK, Akta, dll)
                        alt Lolos Berkas
                            Operator->>Sistem: Update Status 'Diterima'/'Tes'
                            Sistem-->>Siswa: Status Berubah
                        else Tidak Lengkap
                            Operator->>Sistem: Update Status 'Ditolak'
                        end
                        
                        Operator->>Sistem: Ajukan ke Admin Dinas
                </div>
            </div>
            <button onclick="downloadDiagram('diag-dfd2', 'DFD_Level_2')" class="btn-download">
                <svg viewBox="0 0 24 24"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>
                Download DFD Level 2 (PNG)
            </button>
        </div>
    </section>

    <!-- 3. FLOWCHART -->
    <section id="flowchart">
        <div class="card">
            <h2>3. Flowchart Alur Sistem per Role</h2>
            
            <h3>A. Alur Siswa (Pendaftar)</h3>
            <div class="diagram-container" id="diag-flow-siswa">
                <div class="mermaid">
                    flowchart TD
                        Start([Mulai]) --> Landing[Halaman Utama]
                        Landing -->|Klik Daftar| Jalur["Pilih Jalur & Jenjang"]
                        Jalur --> FormReg[Isi Formulir Pendaftaran]
                        FormReg --> Upload["Upload Dokumen (KK, Akta, Ijazah, Prestasi)"]
                        Upload --> Submit[Submit Pendaftaran]
                        Submit --> Wait{Menunggu Verifikasi}
                        Wait -->|Cek Status Berkala| Dashboard[Dashboard Siswa]
                        Dashboard --> Status{"Status Seleksi?"}
                        Status -- "Diterima/Ditolak" --> Hasil[Lihat Hasil Akhir]
                        Status -- Pending --> Wait
                        Hasil --> End([Selesai])
                </div>
            </div>
            <button onclick="downloadDiagram('diag-flow-siswa', 'Flowchart_Siswa')" class="btn-download">
                <svg viewBox="0 0 24 24"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>
                Download Flowchart Siswa (PNG)
            </button>

            <h3>B. Alur Operator Sekolah</h3>
            <div class="diagram-container" id="diag-flow-op">
                <div class="mermaid">
                    flowchart TD
                        StartOp([Mulai]) --> LoginOp[Login Operator]
                        LoginOp --> DashOp[Dashboard Operator]
                        DashOp --> List[Lihat Data Pendaftar Masuk]
                        List --> Detail["Cek Detail & Dokumen Siswa"]
                        Detail --> Validasi{Validasi Berkas}
                        
                        Validasi -- "Lengkap/Layak" --> SetTerima["Set Status: Diterima"]
                        Validasi -- "Tidak Lengkap" --> SetTolak["Set Status: Ditolak"]
                        
                        SetTerima --> Pool[Menunggu Pengajuan]
                        SetTolak --> Pool
                        
                        Pool --> Ajukan[Ajukan ke Admin Dinas]
                        Ajukan --> EndOp(["Selesai / Menunggu Approval Dinas"])
                </div>
            </div>
            <button onclick="downloadDiagram('diag-flow-op', 'Flowchart_Operator')" class="btn-download">
                <svg viewBox="0 0 24 24"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>
                Download Flowchart Operator (PNG)
            </button>

            <h3>C. Alur Admin Dinas</h3>
            <div class="diagram-container" id="diag-flow-admin">
                <div class="mermaid">
                    flowchart TD
                        StartAdm([Mulai]) --> LoginAdm[Login Admin]
                        LoginAdm --> DashAdm[Dashboard Admin]
                        DashAdm --> Inbox[Lihat Pengajuan dari Operator]
                        Inbox --> Review[Review Data Siswa Terpilih]
                        
                        Review --> Approval{Final Approval}
                        Approval -- Approve --> FinalOK[Status Final: Approved]
                        Approval -- Reject --> FinalNO[Status Final: Rejected]
                        
                        FinalOK --> EndAdm([Selesai])
                        FinalNO --> EndAdm
                </div>
            </div>
            <button onclick="downloadDiagram('diag-flow-admin', 'Flowchart_Admin')" class="btn-download">
                <svg viewBox="0 0 24 24"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>
                Download Flowchart Admin (PNG)
            </button>
        </div>
    </section>

    <!-- 4. ANALISIS KODE -->
    <section id="analysis">
        <div class="card">
            <h2>4. Analisis & Kekurangan Sistem Saat Ini</h2>
            <p>Berdasarkan penelusuran kode (Code Review), berikut adalah beberapa kekurangan teknis yang ditemukan:</p>
            
            <ul class="issue-list">
                <li class="issue-item">
                    <span class="issue-title">1. Relasi Database Lemah (Foreign Keys)</span>
                    <span class="issue-desc">
                        Tabel <code>students</code> dan <code>operators</code> menggunakan kolom <code>sekolah_tujuan</code> bertipe <strong>String (Nama Sekolah)</strong> sebagai referensi ke tabel <code>schools</code>. 
                        Seharusnya menggunakan <code>school_id</code> (Foreign Key / BigInt) yang merujuk ke <code>id</code> di tabel schools. 
                        Jika nama sekolah diedit, relasi data akan rusak.
                    </span>
                </li>
                <li class="issue-item">
                    <span class="issue-title">2. Inkonsistensi Penamaan Kolom Password</span>
                    <span class="issue-desc">
                        Tabel user standar menggunakan kolom <code>password</code>, sedangkan tabel student dan operator menggunakan <code>password_hash</code>. 
                        Hal ini memaksa pembuatan fungsi akses khusus (override) pada model dan membingungkan developer baru.
                    </span>
                </li>
                <li class="issue-item">
                    <span class="issue-title">3. Controller Terlalu Besar (Fat Controller)</span>
                    <span class="issue-desc">
                        <code>PPDBController</code> menangani hampir semua logika: Autentikasi Siswa, Autentikasi Operator, Autentikasi Admin, Registrasi, Dashboard, Logic API, dan Approval.
                        Ini melanggar prinsip <em>Single Responsibility Principle</em>. Sebaiknya dipecah menjadi <code>AuthController</code>, <code>StudentController</code>, <code>OperatorController</code>, dll.
                    </span>
                </li>
                <li class="issue-item">
                    <span class="issue-title">4. Validasi Hardcoded & Duplikasi</span>
                    <span class="issue-desc">
                        Logika validasi (seperti validasi Siswa Pindahan/Zonasi) ditulis langsung di dalam method controller. 
                        Sebaiknya dipisahkan ke dalam <em>Form Request</em> classes agar lebih rapi dan reusable.
                    </span>
                </li>
                <li class="issue-item">
                    <span class="issue-title">5. Keamanan Upload File</span>
                    <span class="issue-desc">
                        Meskipun menggunakan <code>store()</code> yang aman, validasi MIME types sebaiknya lebih ketat. 
                        Saat ini tidak ada mekanisme untuk membersihkan file sampah (file yang diupload tapi pendaftaran gagal disave).
                    </span>
                </li>
                <li class="issue-item">
                    <span class="issue-title">6. Hardcoded Enum Values</span>
                    <span class="issue-desc">
                        Status seperti 'Pending', 'Diterima', 'Ditolak' ditulis manual sebagai string di banyak tempat di controller. 
                        Jika ada perubahan nama status, harus mengubah banyak file. Sebaiknya gunakan PHP Class Constants atau Enum.
                    </span>
                </li>
            </ul>
        </div>
    </section>


    <!-- html2canvas Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <script>
        window.downloadDiagram = function(containerId, fileName) {
            const container = document.getElementById(containerId);
            if (!container) {
                alert('Container not found: ' + containerId);
                return;
            }
            
            const mermaidDiv = container.querySelector('.mermaid');
            if (!mermaidDiv) {
                alert('Element .mermaid tidak ditemukan dalam container ini');
                return;
            }

            // Simpan style asli
            const originalDisplay = mermaidDiv.style.display;
            const originalPadding = mermaidDiv.style.padding;
            const originalBg = mermaidDiv.style.backgroundColor;

            // Set style sementara agar fit-content (menghilangkan whitespace)
            mermaidDiv.style.display = 'inline-block';
            mermaidDiv.style.padding = '10px';
            mermaidDiv.style.backgroundColor = '#ffffff';

            html2canvas(mermaidDiv, {
                backgroundColor: "#ffffff",
                scale: 3 // Sedikit lebih high-res agar tajam
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = fileName + '.png';
                link.href = canvas.toDataURL('image/png');
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                // Restore style
                mermaidDiv.style.display = originalDisplay;
                mermaidDiv.style.padding = originalPadding;
                mermaidDiv.style.backgroundColor = originalBg;
            }).catch(err => {
                console.error(err);
                
                // Restore style jika error
                mermaidDiv.style.display = originalDisplay;
                mermaidDiv.style.padding = originalPadding;
                mermaidDiv.style.backgroundColor = originalBg;
                
                alert('Gagal mendownload diagram: ' + err.message);
            });
        };
    </script>
</div>
</body>
</html>
