@extends('layouts.main')
@section('title', 'Kontak Kami - PPDB V.2.0')

@section('content')
<section class="page-content container">
    <h2>Hubungi Kami</h2>
    <p class="subtitle">Kami siap membantu Anda jika mengalami kendala dalam proses PPDB.</p>

    <div class="contact-wrapper">
        <div class="contact-card">
            <h3 style="color: #004d40; margin-top: 0; margin-bottom: 25px;">Informasi Kontak</h3>
            
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
            <h3 style="color: #004d40; margin-top: 0; margin-bottom: 25px;">Kirim Pesan</h3>
            <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Pesan Anda telah terkirim! (Simulasi)');">
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="name" style="display: block; margin-bottom: 5px;">Nama Lengkap</label>
                    <input type="text" id="name" class="form-control" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px;">
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="email" style="display: block; margin-bottom: 5px;">Email</label>
                    <input type="email" id="email" class="form-control" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px;">
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="subject" style="display: block; margin-bottom: 5px;">Subjek</label>
                    <input type="text" id="subject" class="form-control" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px;">
                </div>
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="message" style="display: block; margin-bottom: 5px;">Pesan</label>
                    <textarea id="message" rows="5" class="form-control" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px;"></textarea>
                </div>
                <button type="submit" class="btn-primary" style="width: 100%;">Kirim Pesan</button>
            </form>
        </div>
    </div>
</section>
@endsection
