<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student; // <-- HARUS ADA
use App\Models\School;  // <-- DIPERLUKAN UNTUK FUNGSI getSchoolsApi
use App\Models\Operator; 
use App\Models\User;    
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;


class PPDBController extends Controller
{
    public function showRegistrationForm(Request $request)
    {
        $sekolahTujuan = $request->query('sekolah', '');
        return view('pendaftaran', compact('sekolahTujuan'));
    }

    public function showDaftarSekolah()
    {
        return view('daftar-sekolah');
    }
    
    
    public function dashboardStudent()
    {
        if (!Auth::guard('student')->check()) {
            return redirect()->route('login')->with('error', 'Akses ditolak.');
        }
        return view('dashboard-siswa');
    }
    
    public function registerStudent(Request $request)
{
    try {
        $validated = $request->validate([
            'nisn' => 'required|digits:10|unique:students,nisn',
            'nama_siswa' => 'required|string|max:255',
            'password_baru' => 'required|string|min:6',
            'jenjang' => 'required|string|in:SD,SMP,SMA',
            'sekolah-tujuan' => 'required|string|max:255|exists:schools,nama_sekolah',
            'jalur' => 'required|string|max:100',
            'alamat' => 'required|string',
            'scan_kk' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'scan_akta' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'scan_ijazah' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $scanKkPath = $request->file('scan_kk')->store('documents', 'public');
        $scanAktaPath = $request->file('scan_akta')->store('documents', 'public');
        $scanIjazahPath = $request->file('scan_ijazah')->store('documents', 'public');

        \App\Models\Student::create([
            'nisn' => $validated['nisn'],
            'nama_lengkap' => $validated['nama_siswa'],
            'password_hash' => \Illuminate\Support\Facades\Hash::make($validated['password_baru']),
            'jenjang_tujuan' => $validated['jenjang'],
            'sekolah_tujuan' => $validated['sekolah-tujuan'],
            'jalur_pendaftaran' => $validated['jalur'],
            'alamat' => $validated['alamat'],
            'scan_kk' => $scanKkPath,
            'scan_akta' => $scanAktaPath,
            'scan_ijazah' => $scanIjazahPath,
            'status_seleksi' => 'Pending',
            'status_approval' => 'Pending',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pendaftaran berhasil!',
            'nisn' => $validated['nisn']
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
         return response()->json([
            'status' => 'validation_error', 
            'message' => 'Data yang Anda masukkan tidak valid. Silakan periksa kembali.',
            'errors' => $e->errors(),
        ], 422);
    } catch (\Exception $e) {
         return response()->json([
            'status' => 'error',
            'message' => 'Gagal menyimpan data ke database. Detail: ' . $e->getMessage(),
        ], 500);
    }
}

public function logout(Request $request)
{
    if (Auth::guard('student')->check()) {
        Auth::guard('student')->logout();
    } elseif (Auth::guard('operator')->check()) {
        Auth::guard('operator')->logout();
    } elseif (Auth::guard('web')->check()) {
        Auth::guard('web')->logout();
    }
    
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
}

    public function getSchoolsApi(Request $request)
    {
        $jenjang = $request->jenjang; 
        $daerah = $request->daerah;
        $namaSekolah = $request->nama_sekolah;
        
        $schoolsQuery = School::where('jenjang', $jenjang);

        if (!empty($daerah)) {
            $schoolsQuery->where('kota_kab', 'LIKE', '%' . $daerah . '%');
        }

        if (!empty($namaSekolah)) {
            $schoolsQuery->where('nama_sekolah', 'LIKE', '%' . $namaSekolah . '%');
        }
        
        $schools = $schoolsQuery->limit(5)->get();

        $simulated_distance = ['1.2 km', '2.5 km', '3.8 km', '4.9 km', '6.1 km'];

        if ($schools->isNotEmpty()) {
            $schools = $schools->map(function ($school, $index) use ($simulated_distance, $daerah) {
                $school->jarak = $simulated_distance[$index % count($simulated_distance)];
                $school->detail = "Sekolah di zonasi {$daerah}";
                return $school;
            });
            return response()->json(['status' => 'success', 'schools' => $schools]);
        }
        
        return response()->json([
            'status' => 'not_found', 
            'schools' => [], 
            'message' => "Tidak ada sekolah {$jenjang} yang ditemukan di {$daerah}."
        ]);
    }
    
    public function updateSchool(Request $request)
    {
        if (!Auth::guard('student')->check()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'sekolah_baru' => 'required|string|exists:schools,nama_sekolah',
        ]);

        $student = Auth::guard('student')->user();

        if ($student->status_seleksi !== 'Pending') {
            return response()->json([
                'status' => 'error', 
                'message' => 'Tidak dapat mengganti sekolah karena status seleksi sudah diproses (' . $student->status_seleksi . ').'
            ], 403);
        }

        $student->sekolah_tujuan = $request->sekolah_baru;
        $student->save();

        return response()->json([
            'status' => 'success', 
            'message' => 'Sekolah tujuan berhasil diperbarui menjadi ' . $request->sekolah_baru
        ]);
    }

    public function getStudentDataApi(Request $request)
    {
        $nisn = $request->query('nisn');
    $student = Student::where('nisn', $nisn)->first([
        'nama_lengkap', 'nisn', 'jenjang_tujuan', 'sekolah_tujuan', 'jalur_pendaftaran', 
        'status_seleksi', 'status_approval', 'jadwal_test'
    ]);

        if ($student) {
            return response()->json(["status" => "success", "student" => $student]);
        } else {
            return response()->json(["status" => "error", "message" => "Data siswa tidak ditemukan."], 404);
        }
    }

    public function dashboardOperator()
    {
        $operator = Auth::guard('operator')->user();
        if (!$operator) { return redirect()->route('login'); }
        
        return view('operator_dashboard', [
            'sekolahTanggungJawab' => $operator->sekolah_tujuan,
        ]);
    }

    public function getPendaftarBySekolahApi()
    {
        $operator = Auth::guard('operator')->user();
        if (!$operator) { return response()->json([], 403); }

        $sekolah = $operator->sekolah_tujuan;
        
        $pendaftar = Student::where('sekolah_tujuan', $sekolah)
                            ->get([
                                'id', 'nisn', 'nama_lengkap', 'jalur_pendaftaran', 
                                'status_seleksi', 'status_approval', 'jadwal_test'
                            ]);

        return response()->json(['status' => 'success', 'pendaftar' => $pendaftar]);
    }

    public function updateSeleksiOperator(Request $request)
    {
        $operator = Auth::guard('operator')->user();
        if (!$operator) { return response()->json(['message' => 'Unauthorized'], 403); }

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'status_seleksi' => 'required|in:Pending,Diterima,Ditolak',
            'jadwal_test' => 'nullable|date',
        ]);
        
        $student = Student::findOrFail($validated['student_id']);
        
        if ($student->sekolah_tujuan !== $operator->sekolah_tujuan) {
            return response()->json(['message' => 'Akses ditolak untuk siswa ini.'], 403);
        }

        $student->status_seleksi = $validated['status_seleksi'];
        $student->jadwal_test = $validated['jadwal_test'];
        $student->status_approval = 'Pending'; 
        $student->save();

        return response()->json(['status' => 'success', 'message' => 'Status seleksi berhasil diperbarui.']);
    }

    public function ajukanKeAdmin(Request $request, $sekolah)
    {
        $operator = Auth::guard('operator')->user();
        if (!$operator || $operator->sekolah_tujuan !== urldecode($sekolah)) { return response()->json(['message' => 'Unauthorized'], 403); }
        
        Student::where('sekolah_tujuan', urldecode($sekolah))
               ->whereIn('status_seleksi', ['Diterima', 'Ditolak'])
               ->update(['status_approval' => 'Pending']); 

        return response()->json(['status' => 'success', 'message' => "Pengajuan hasil seleksi sekolah " . urldecode($sekolah) . " telah dikirim ke Admin Dinas."]);
    }


    public function dashboardAdmin()
    {
        if (!Auth::guard('web')->check()) { return redirect()->route('login'); }
        return view('admin_dashboard'); 
    }

    public function getPengajuanOperatorApi()
    {
        if (!Auth::guard('web')->check()) { return response()->json([], 403); }

        $pengajuan = Student::whereIn('status_seleksi', ['Diterima', 'Ditolak'])
                            ->where('status_approval', 'Pending')
                            ->orderBy('sekolah_tujuan', 'asc')
                            ->get([
                                'id', 'nisn', 'nama_lengkap', 'sekolah_tujuan', 
                                'status_seleksi', 'status_approval', 'jalur_pendaftaran', 'jadwal_test'
                            ]);
        
        return response()->json(['status' => 'success', 'pengajuan' => $pengajuan]);
    }

    public function updateApprovalAdmin(Request $request)
    {
        if (!Auth::guard('web')->check()) { return response()->json(['message' => 'Unauthorized'], 403); }

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'status_approval' => 'required|in:Approved,Rejected',
        ]);

        $student = Student::findOrFail($validated['student_id']);
        
        $student->status_approval = $validated['status_approval'];
        $student->save();
        
        return response()->json(['status' => 'success', 'message' => 'Approval berhasil diperbarui.']);
    }
    
        public function getAllPendaftarApi()
    
        {
    
            if (!Auth::guard('web')->check()) {
    
                return response()->json(["status" => "error", "message" => "Unauthorized."], 403);
    
            }
    
            
    
            $pendaftar = Student::get([
    
                'nisn', 'nama_lengkap', 'sekolah_tujuan', 'jalur_pendaftaran', 'status_seleksi'
    
            ]);
    
    
    
            return response()->json([
    
                'status' => 'success', 
    
                'pendaftar' => $pendaftar
    
            ]);
    
        }
    
    
    
        // Admin-only registration methods
    
        public function showOperatorRegistrationForm()
    
        {
    
            return view('auth.register-operator');
    
        }
    
    
    
        public function registerOperator(Request $request)
    
        {
    
            $request->validate([
    
                'nama_operator' => ['required', 'string', 'max:255'],
    
                'username' => ['required', 'string', 'max:255', 'unique:operators'],
    
                'sekolah_tujuan' => ['required', 'string', 'max:255'],
    
                'password' => ['required', 'string', 'min:8', 'confirmed'],
    
            ]);
    
    
    
            Operator::create([
    
                'nama_operator' => $request->nama_operator,
    
                'username' => $request->username,
    
                'sekolah_tujuan' => $request->sekolah_tujuan,
    
                'password_hash' => Hash::make($request->password),
    
            ]);
    
    
    
            return redirect()->route('admin.register.operator.form')->with('success', 'Operator registered successfully!');
    
        }
    
    
    
        public function showAdminRegistrationForm()
    
        {
    
            return view('auth.register-admin');
    
        }
    
    
    
        public function registerAdmin(Request $request)
    
        {
    
            $request->validate([
    
                'name' => ['required', 'string', 'max:255'],
    
                'username' => ['required', 'string', 'max:255', 'unique:users'],
    
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
    
                'password' => ['required', 'string', 'min:8', 'confirmed'],
    
            ]);
    
    
    
            User::create([
    
                'name' => $request->name,
    
                'username' => $request->username,
    
                'email' => $request->email,
    
                'password' => Hash::make($request->password),
    
            ]);
    
    
    
            return redirect()->route('admin.register.admin.form')->with('success', 'Admin registered successfully!');
    
        }
    
    }
    
    