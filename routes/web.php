<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PPDBController;

Route::get('/', fn() => view('index'))->name('home');
Route::get('/info-jalur', fn() => view('info-jalur'))->name('info.jalur');
Route::get('/jadwal', fn() => view('jadwal'))->name('jadwal');
Route::get('/daftar-sekolah', [PPDBController::class, 'showDaftarSekolah'])->name('schools.index');
Route::post('/api/get_schools', [PPDBController::class, 'getSchoolsApi'])->name('schools.api');

Route::get('/login', fn() => view('auth.login'))->name('login');

Route::post('/logout', [PPDBController::class, 'logout'])->name('logout');

Route::get('/pendaftaran', [PPDBController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/pendaftaran/submit', [PPDBController::class, 'registerStudent'])->name('register.submit');

Route::middleware('auth:student')->group(function () {
    Route::get('/dashboard-siswa', [PPDBController::class, 'dashboardStudent'])->name('student.dashboard');
    Route::get('/api/student/data', [PPDBController::class, 'getStudentDataApi'])->name('student.data.api');
});

Route::middleware('auth:operator')->group(function () { 
    Route::get('/operator/dashboard', [PPDBController::class, 'dashboardOperator'])->name('operator.dashboard');
    Route::get('/api/operator/pendaftar', [PPDBController::class, 'getPendaftarBySekolahApi'])->name('operator.pendaftar.api');
    Route::post('/api/operator/seleksi', [PPDBController::class, 'updateSeleksiOperator'])->name('operator.seleksi.update');
    Route::post('/api/operator/ajukan/{sekolah}', [PPDBController::class, 'ajukanKeAdmin'])->name('operator.ajukan');
});

Route::middleware('auth:web')->group(function () { 
    Route::get('/admin/dashboard', [PPDBController::class, 'dashboardAdmin'])->name('admin.dashboard'); 
    Route::get('/api/admin/pengajuan', [PPDBController::class, 'getPengajuanOperatorApi'])->name('admin.pengajuan.api');
    Route::post('/api/admin/approval', [PPDBController::class, 'updateApprovalAdmin'])->name('admin.approval.update');
    Route::get('/api/admin/all_pendaftar', [PPDBController::class, 'getAllPendaftarApi'])->name('admin.pendaftar.api');
});