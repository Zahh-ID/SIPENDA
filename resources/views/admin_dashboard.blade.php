@extends('layouts.main')
@section('title', 'Dasbor Admin Dinas - PPDB V.2.0')

@section('styles')
    {{-- Styles moved to public/style.css --}}
@endsection

@section('content')
<section class="admin-dashboard container">
    <h2>👑 Dasbor Admin Dinas (Approval Global)</h2>
    <p class="subtitle">Verifikasi dan setujui hasil seleksi yang diajukan oleh Operator Sekolah.</p>

    <div class="table-container" style="margin-bottom: 40px;">
        <h3>Manajemen Pengguna</h3>
        <div style="padding: 15px;">
            <a href="{{ route('admin.register.operator.form') }}" class="btn-primary" style="margin-right: 15px;">Register New Operator</a>
            <a href="{{ route('admin.register.admin.form') }}" class="btn-primary">Register New Admin</a>
        </div>
    </div>

    <div class="table-container">
        <div style="display: flex; justify-content: space-between; align-items: center; padding-right: 15px;">
            <h3>Permintaan Approval Siswa</h3>
            <button onclick="approveAllAdmin()" class="btn-small bg-blue-500 hover:bg-blue-700" style="padding: 8px 15px; font-size: 14px;">✔ ACC Semua</button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Sekolah Asal Pengajuan</th>
                    <th>Nama Siswa (NISN)</th>
                    <th>Status Seleksi Operator</th>
                    <th>Jadwal Test</th> 
                    <th>Aksi Admin Dinas</th>
                </tr>
            </thead>
            <tbody id="approval-table-body">
                </tbody>
        </table>
    </div>
</section>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.fetchPengajuan === 'function') {
            window.fetchPengajuan();
        } else {
             // Fallback or retry if script hasn't loaded yet
             setTimeout(() => {
                if (typeof window.fetchPengajuan === 'function') {
                    window.fetchPengajuan();
                }
             }, 500);
        }
    });
</script>
@endsection