<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use App\Models\Kelas;
use App\Models\Ruang;
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
        $total_matakuliah = Matakuliah::count();
        $total_kelas = Kelas::count();
        $total_ruang = Ruang::count();

        // Kirim data ke view
        return view('admin.dashboard', compact(
            'total_users',
            'total_admins',
            'total_lecturers',
            'total_students',
            'total_dosen',
            'total_mahasiswa',
            'total_matakuliah',
            'total_kelas',
            'total_ruang'
        ));
    }
}