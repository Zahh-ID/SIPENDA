@extends('layouts.main')
@section('title', 'FAQ - PPDB V.2.0')

@section('content')
<section class="page-content container">
    <h2>Pertanyaan yang Sering Diajukan (FAQ)</h2>
    <p class="subtitle">Temukan jawaban atas pertanyaan umum seputar PPDB Jawa Timur.</p>

    <div class="faq-container">
        <details class="faq-item">
            <summary>Apa itu PPDB Online?</summary>
            <div class="faq-answer">
                <p>PPDB Online adalah sistem penerimaan peserta didik baru yang dilakukan secara daring (online) untuk jenjang SD, SMP, dan SMA di Jawa Timur. Sistem ini bertujuan untuk mempermudah masyarakat dalam melakukan pendaftaran sekolah secara transparan dan akuntabel.</p>
            </div>
        </details>

        <details class="faq-item">
            <summary>Bagaimana cara mendaftar?</summary>
            <div class="faq-answer">
                <p>Anda dapat mendaftar dengan membuat akun terlebih dahulu, kemudian login dan mengisi formulir pendaftaran sesuai dengan jalur yang Anda pilih (Zonasi, Prestasi, atau Afirmasi).</p>
            </div>
        </details>

        <details class="faq-item">
            <summary>Apa saja syarat pendaftaran?</summary>
            <div class="faq-answer">
                <p>Syarat umum meliputi memiliki NISN, Kartu Keluarga, dan dokumen pendukung lainnya sesuai jalur yang dipilih. Detail persyaratan dapat dilihat pada menu <a href="{{ route('info.jalur') }}">Info Jalur</a>.</p>
            </div>
        </details>

        <details class="faq-item">
            <summary>Kapan pengumuman hasil seleksi?</summary>
            <div class="faq-answer">
                <p>Pengumuman hasil seleksi akan diinformasikan sesuai dengan jadwal yang telah ditetapkan. Silakan cek menu <a href="{{ route('jadwal') }}">Jadwal</a> untuk informasi lebih lanjut.</p>
            </div>
        </details>
        
        <details class="faq-item">
            <summary>Apakah bisa mengganti pilihan sekolah?</summary>
            <div class="faq-answer">
                <p>Perubahan pilihan sekolah hanya dapat dilakukan selama masa pendaftaran masih dibuka dan data belum diverifikasi permanen oleh operator sekolah.</p>
            </div>
        </details>
    </div>
</section>
@endsection
