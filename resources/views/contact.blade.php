@extends('layouts.main')
@section('title', 'Kontak Kami - SIPENDA')

@section('content')
<section class="page-content container">
    <h2>Hubungi Kami</h2>
    <p class="subtitle">Kami siap membantu Anda jika mengalami kendala dalam proses PPDB.</p>

    <div class="contact-wrapper">
        <div class="contact-card">
            <h3>Informasi Kontak</h3>
            
            <div class="contact-info-item">
                <div class="contact-icon">📍</div>
                <div class="contact-details">
                    <h4>Alamat Kantor:</h4>
                    <p>Jl. Genteng Kali No. 33, Surabaya<br>Jawa Timur, Indonesia 60275</p>
                </div>
            </div>
            
            <div class="contact-info-item">
                <div class="contact-icon">✉️</div>
                <div class="contact-details">
                    <h4>Email:</h4>
                    <p><a href="mailto:info@ppdbjatim.net">info@ppdbjatim.net</a></p>
                </div>
            </div>
            
            <div class="contact-info-item">
                <div class="contact-icon">📞</div>
                <div class="contact-details">
                    <h4>Telepon / WhatsApp:</h4>
                    <p>(031) 123-4567 / 0812-3456-7890</p>
                </div>
            </div>
            
            <div class="contact-info-item">
                <div class="contact-icon">🕒</div>
                <div class="contact-details">
                    <h4>Jam Operasional:</h4>
                    <p>Senin - Jumat: 08.00 - 16.00 WIB</p>
                </div>
            </div>
        </div>

        <div class="contact-card">
            <h3>Kirim Pesan</h3>
            <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Pesan Anda telah terkirim! (Simulasi)');">
                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="subject">Subjek</label>
                    <input type="text" id="subject" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="message">Pesan</label>
                    <textarea id="message" rows="5" class="form-control" required></textarea>
                </div>
                <button type="submit" class="btn-primary" style="width: 100%; margin-top: 20px;">Kirim Pesan</button>
            </form>
        </div>
    </div>
</section>
@endsection
