<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PPDBController;

Route::get('/', fn() => view('index'))->name('home');
Route::get('/info-jalur', fn() => view('info-jalur'))->name('info.jalur');
Route::get('/jadwal', fn() => view('jadwal'))->name('jadwal');
Route::get('/daftar-sekolah', [PPDBController::class, 'showDaftarSekolah'])->name('schools.index');
Route::get('/faq', fn() => view('faq'))->name('faq');
Route::get('/kontak', fn() => view('contact'))->name('contact');
Route::get('/kebijakan-privasi', fn() => view('privacy'))->name('privacy');


Route::get('/login', fn() => view('auth.login'))->name('login');

Route::post('/logout', [PPDBController::class, 'logout'])->name('logout');

Route::get('/pendaftaran', [PPDBController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/pendaftaran/submit', [PPDBController::class, 'registerStudent'])->name('register.submit');
Route::get('/api/schools-search', [PPDBController::class, 'getSchoolsApi'])->name('schools.api');

Route::middleware('auth:student')->group(function () {
    Route::get('/dashboard-siswa', [PPDBController::class, 'dashboardStudent'])->name('student.dashboard');
    Route::get('/api/student/data', [PPDBController::class, 'getStudentDataApi'])->name('student.data.api');
    Route::post('/api/student/update-sekolah', [PPDBController::class, 'updateSchool'])->name('student.update.school');
});

Route::middleware('auth:operator')->group(function () { 
    Route::get('/operator/dashboard', [PPDBController::class, 'dashboardOperator'])->name('operator.dashboard');
    Route::get('/api/operator/pendaftar', [PPDBController::class, 'getPendaftarBySekolahApi'])->name('operator.pendaftar.api');
    Route::post('/api/operator/seleksi', [PPDBController::class, 'updateSeleksiOperator'])->name('operator.seleksi.update');
    Route::post('/api/operator/ajukan/{sekolah}', [PPDBController::class, 'ajukanKeAdmin'])->name('operator.ajukan');
    Route::post('/api/operator/link-administrasi', [PPDBController::class, 'updateLinkAdministrasi'])->name('operator.link.update');
    Route::get('/api/operator/link-administrasi', [PPDBController::class, 'getLinkAdministrasiApi'])->name('operator.link.get');
});

Route::middleware('auth:web')->group(function () { 
    Route::get('/admin/dashboard', [PPDBController::class, 'dashboardAdmin'])->name('admin.dashboard'); 
    Route::get('/api/admin/pengajuan', [PPDBController::class, 'getPengajuanOperatorApi'])->name('admin.pengajuan.api');
    Route::post('/api/admin/approval', [PPDBController::class, 'updateApprovalAdmin'])->name('admin.approval.update');
    Route::post('/api/admin/approval/all', [PPDBController::class, 'approveAllAdmin'])->name('admin.approval.all');
    Route::get('/api/admin/all_pendaftar', [PPDBController::class, 'getAllPendaftarApi'])->name('admin.pendaftar.api');

    // Secret Registration Routes
    Route::get('/admin/register/operator', [PPDBController::class, 'showOperatorRegistrationForm'])->name('admin.register.operator.form');
    Route::post('/admin/register/operator', [PPDBController::class, 'registerOperator'])->name('admin.register.operator.submit');
    Route::get('/admin/register/admin', [PPDBController::class, 'showAdminRegistrationForm'])->name('admin.register.admin.form');
    Route::post('/admin/register/admin', [PPDBController::class, 'registerAdmin'])->name('admin.register.admin.submit');
});