<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MahasiswaController extends Controller
{
    /**
     * Menampilkan daftar mahasiswa dengan filter dan paginasi.
     */
    public function index(Request $request)
    {
        $query = Mahasiswa::with(['user', 'kelas.tahunAjaran', 'kelas.prodi', 'prodi']);

        if ($request->filled('id_prodi_mahasiswa')) {
            $query->where('id_prodi', $request->id_prodi_mahasiswa);
        }
        if ($request->filled('id_kelas')) {
            $query->where('id_kelas', $request->id_kelas);
        }
        if ($request->filled('id_tahun_ajaran_kelas')) {
            $query->whereHas('kelas', function ($q) use ($request) {
                $q->where('id_tahun_ajaran', $request->id_tahun_ajaran_kelas);
            });
        }
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama', 'like', $searchTerm)
                  ->orWhere('nrp', 'like', $searchTerm)
                  ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', $searchTerm)
                                ->orWhere('email', 'like', $searchTerm);
                  });
            });
        }

        $mahasiswa = $query->latest('mahasiswa.created_at')->paginate(10)->withQueryString();
        $prodiList = Prodi::orderBy('nama_prodi')->get();
        $kelasList = Kelas::whereNotNull('id_tahun_ajaran')
                           ->whereNotNull('id_prodi')
                           ->with(['tahunAjaran', 'prodi'])
                           ->orderBy('nama_kelas')
                           ->get();
        $tahunAjaranList = TahunAjaran::orderBy('tahun_mulai', 'desc')->orderBy('semester', 'desc')->get();

        return view('admin.mahasiswa.index', compact('mahasiswa', 'prodiList', 'kelasList', 'tahunAjaranList'));
    }

    /**
     * Menampilkan form untuk membuat mahasiswa baru.
     */
    public function create()
    {
        $kelasList = Kelas::where('status', 'active')
                            ->whereNotNull('id_tahun_ajaran')
                            ->whereNotNull('id_prodi')
                            ->with(['tahunAjaran', 'prodi']) 
                            ->orderBy('nama_kelas')
                            ->get();
        $prodiModelList = Prodi::orderBy('nama_prodi')->get();

        return view('admin.mahasiswa.create', compact('kelasList', 'prodiModelList'));
    }

    /**
     * Menyimpan mahasiswa baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi dengan pendekatan sederhana - hanya username, email dibuat di controller
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:191|regex:/^[a-zA-Z0-9._-]+$/',
            'password' => 'required|string|min:8',
            'nrp' => 'required|string|max:50|unique:mahasiswa,nrp',
            'prodi' => 'required|string|max:255',
            'id_kelas' => 'required|exists:kelas,id_kelas'
        ]);

        // Buat email dari username + domain
        $email = $validatedData['username'] . '@it.student.pens.ac.id';
        
        // Validasi email tidak duplikat
        if (User::where('email', $email)->exists()) {
            return redirect()->back()->withInput()->withErrors(['username' => 'Username sudah digunakan. Email ' . $email . ' sudah terdaftar.']);
        }

        // Cari id_prodi berdasarkan nama_prodi
        $prodiModel = Prodi::where('nama_prodi', $validatedData['prodi'])->first();
        if (!$prodiModel) {
            return redirect()->back()->withInput()->withErrors(['prodi' => 'Program Studi tidak valid atau tidak ditemukan.']);
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $email,
                'password' => Hash::make($validatedData['password']),
            ]);

            $user->assignRole('mahasiswa'); 

            Mahasiswa::create([
                'user_id' => $user->id,
                'id_kelas' => $validatedData['id_kelas'],
                'nrp' => $validatedData['nrp'],
                'nama' => $validatedData['name'],
                'id_prodi' => $prodiModel->id_prodi,
            ]);

            DB::commit();
            return redirect()->route('admin.mahasiswa.index')->with('success', 'Mahasiswa berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating mahasiswa: ' . $e->getMessage(), ['exception' => $e, 'request_data' => $request->except('password', '_token')]);
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal menambahkan mahasiswa. Silakan coba lagi.']);
        }
    }

    /**
     * Menampilkan detail mahasiswa.
     */
    public function show($id_mahasiswa)
    {
        $mahasiswa = Mahasiswa::with(['user', 'kelas.tahunAjaran', 'kelas.prodi', 'prodi'])->findOrFail($id_mahasiswa);
        return view('admin.mahasiswa.show', compact('mahasiswa'));
    }

    /**
     * Menampilkan form untuk mengedit data mahasiswa.
     */
    public function edit($id_mahasiswa)
    {
        $mahasiswa = Mahasiswa::with(['user', 'prodi'])->findOrFail($id_mahasiswa);
        $kelasList = Kelas::where('status', 'active')
                            ->whereNotNull('id_tahun_ajaran')
                            ->whereNotNull('id_prodi')
                            ->with(['tahunAjaran', 'prodi'])
                            ->orderBy('nama_kelas')
                            ->get();
        $prodiModelList = Prodi::orderBy('nama_prodi')->get();
        
        $email_username_edit = '';
        if ($mahasiswa->user && $mahasiswa->user->email) {
            $email_username_edit = Str::before($mahasiswa->user->email, '@it.student.pens.ac.id');
        }
        $selected_prodi_nama = $mahasiswa->prodi->nama_prodi ?? '';

        return view('admin.mahasiswa.edit', compact('mahasiswa', 'kelasList', 'prodiModelList', 'email_username_edit', 'selected_prodi_nama'));
    }

    /**
     * Memperbarui data mahasiswa di database.
     */
    public function update(Request $request, $id_mahasiswa)
    {
        $mahasiswa = Mahasiswa::findOrFail($id_mahasiswa);
        $user = User::findOrFail($mahasiswa->user_id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:191|regex:/^[a-zA-Z0-9._-]+$/',
            'password' => 'nullable|string|min:8',
            'nrp' => [
                'required', 'string', 'max:50',
                Rule::unique('mahasiswa')->ignore($mahasiswa->id_mahasiswa, 'id_mahasiswa'),
            ],
            'prodi' => 'required|string|max:255',
            'id_kelas' => 'required|exists:kelas,id_kelas'
        ]);

        // Buat email dari username + domain
        $email = $validatedData['username'] . '@it.student.pens.ac.id';
        
        // Validasi email tidak duplikat (kecuali untuk user yang sedang diedit)
        if (User::where('email', $email)->where('id', '!=', $user->id)->exists()) {
            return redirect()->back()->withInput()->withErrors(['username' => 'Username sudah digunakan. Email ' . $email . ' sudah terdaftar.']);
        }

        // Cari id_prodi berdasarkan nama_prodi
        $prodiModel = Prodi::where('nama_prodi', $validatedData['prodi'])->first();
        if (!$prodiModel) {
            return redirect()->back()->withInput()->withErrors(['prodi' => 'Program Studi tidak valid atau tidak ditemukan.']);
        }

        DB::beginTransaction();
        try {
            $user->name = $validatedData['name'];
            $user->email = $email;
            if ($request->filled('password')) {
                $user->password = Hash::make($validatedData['password']);
            }
            $user->save();

            $mahasiswa->id_kelas = $validatedData['id_kelas'];
            $mahasiswa->nrp = $validatedData['nrp'];
            $mahasiswa->nama = $validatedData['name']; 
            $mahasiswa->id_prodi = $prodiModel->id_prodi;
            $mahasiswa->save();

            DB::commit();
            return redirect()->route('admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating mahasiswa: ' . $e->getMessage(), ['exception' => $e, 'request_data' => $request->except('password', '_token')]);
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal memperbarui data mahasiswa. Silakan coba lagi.']);
        }
    }

    /**
     * Menghapus data mahasiswa dari database.
     */
    public function destroy($id_mahasiswa)
    {
        $mahasiswa = Mahasiswa::findOrFail($id_mahasiswa);

        DB::beginTransaction();
        try {
            if ($mahasiswa->user) {
                $mahasiswa->user->delete();
            }
            $mahasiswa->delete();

            DB::commit();
            return redirect()->route('admin.mahasiswa.index')->with('success', 'Mahasiswa berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting mahasiswa: ' . $e->getMessage(), ['exception' => $e]);
            if (str_contains(strtolower($e->getMessage()), 'foreign key constraint fails')) {
                 return redirect()->route('admin.mahasiswa.index')->with('error', 'Gagal menghapus mahasiswa. Masih ada data lain yang terkait.');
            }
            return redirect()->route('admin.mahasiswa.index')->with('error', 'Terjadi kesalahan saat menghapus mahasiswa.');
        }
    }
}