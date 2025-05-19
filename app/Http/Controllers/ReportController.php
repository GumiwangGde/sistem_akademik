<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use App\Models\Kelas;
use App\Models\Ruang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function download()
    {
        // Ambil data metrik sistem
        $data = [
            'total_pengguna' => User::count(),
            'total_admin' => User::where('email', 'like', '%admin.pens.ac.id')->count(),
            'total_dosen_user' => User::where('email', 'like', '%lecturer.pens.ac.id')->count(),
            'total_mahasiswa_user' => User::where('email', 'like', '%student.pens.ac.id')->count(),
            'total_dosen' => Dosen::count(),
            'total_mahasiswa' => Mahasiswa::count(),
            'total_matakuliah' => Matakuliah::count(),
            'total_kelas' => Kelas::count(),
            'total_ruang' => Ruang::count(),
            'tanggal' => now()->format('d F Y'),
        ];

        // Muat view untuk PDF
        $pdf = Pdf::loadView('reports.ringkasan_sistem', $data);

        // Unduh PDF dengan nama file
        return $pdf->download('laporan_ringkasan_sistem.pdf');
    }
}