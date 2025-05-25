<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Matakuliah; // Ini sekarang merepresentasikan Jadwal Kuliah
use App\Models\Kelas;
use App\Models\Ruang;
use App\Models\TahunAjaran;     // Model baru
use App\Models\Prodi;           // Model baru
use App\Models\MasterMatakuliah; // Model baru
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil jumlah pengguna berdasarkan domain email
        $total_admins = User::where('email', 'like', '%admin.pens.ac.id')->count();
        $total_lecturers = User::where('email', 'like', '%lecturer.pens.ac.id')->count();
        $total_students = User::where('email', 'like', '%student.pens.ac.id')->count();
        $total_users = $total_admins + $total_lecturers + $total_students;

        // Ambil jumlah data lainnya
        $total_dosen = Dosen::count();
        $total_mahasiswa = Mahasiswa::count();
        
        // Data terkait mata kuliah dengan arsitektur baru
        $total_master_matakuliah = MasterMatakuliah::count(); // Jumlah mata kuliah unik (master)
        $total_jadwal_kuliah = Matakuliah::count(); // Jumlah jadwal kuliah yang telah dibuat

        $total_kelas = Kelas::count();
        $total_ruang = Ruang::count();

        // Data untuk entitas baru
        $total_tahun_ajaran = TahunAjaran::count();
        $total_prodi = Prodi::count();

        // Kirim data ke view
        return view('admin.dashboard', compact(
            'total_users',
            'total_admins',
            'total_lecturers',
            'total_students',
            'total_dosen',
            'total_mahasiswa',
            'total_master_matakuliah', // Diperbarui
            'total_jadwal_kuliah',    // Nama baru untuk count dari tabel matakuliah (jadwal)
            'total_kelas',
            'total_ruang',
            'total_tahun_ajaran',     // Baru
            'total_prodi'             // Baru
        ));
    }
}
