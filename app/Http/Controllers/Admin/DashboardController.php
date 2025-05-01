<?php

// app/Http/Controllers/Admin/DashboardController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\MataKuliah;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $mahasiswaCount = Mahasiswa::count();
        $dosenCount = Dosen::count();
        $mataKuliahCount = MataKuliah::count();
        
        return view('admin.dashboard', compact('mahasiswaCount', 'dosenCount', 'mataKuliahCount'));
    }
}