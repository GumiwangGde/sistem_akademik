<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Matakuliah;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DosenController extends Controller
{
    /**
     * Menampilkan daftar semua dosen.
     */
    public function index(Request $request)
    {
        $query = Dosen::with('user');

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nidn', 'like', $searchTerm)
                  ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', $searchTerm)
                                ->orWhere('email', 'like', $searchTerm);
                  });
            });
        }

        $dosen = $query->latest('created_at')->paginate(10)->withQueryString();
        $tahunAjaranAktif = TahunAjaran::where('status', 'aktif')->first();

        return view('admin.dosen.index', compact('dosen', 'tahunAjaranAktif'));
    }

    /**
     * Menampilkan form untuk membuat dosen baru.
     */
    public function create()
    {
        return view('admin.dosen.create');
    }

    /**
     * Menyimpan dosen baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi dengan pendekatan sederhana - hanya username, email dibuat di controller
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:191|regex:/^[a-zA-Z0-9._-]+$/',
            'password' => 'required|string|min:8',
            'nidn' => 'required|string|max:255|unique:dosen,nidn',
            'is_dosen_wali' => 'sometimes|boolean',
        ]);

        // Buat email dari username + domain
        $email = $validatedData['username'] . '@it.lecturer.pens.ac.id';
        
        // Validasi email tidak duplikat
        if (User::where('email', $email)->exists()) {
            return redirect()->back()->withInput()->withErrors(['username' => 'Username sudah digunakan. Email ' . $email . ' sudah terdaftar.']);
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $email,
                'password' => Hash::make($validatedData['password']),
            ]);

            $user->assignRole('dosen');

            Dosen::create([
                'user_id' => $user->id,
                'nidn' => $validatedData['nidn'],
                'is_dosen_wali' => $request->boolean('is_dosen_wali'),
            ]);

            DB::commit();
            return redirect()->route('admin.dosen.index')->with('success', 'Dosen berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating dosen: ' . $e->getMessage(), ['exception' => $e, 'request_data' => $request->except('password', '_token')]);
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal menambahkan dosen. Silakan coba lagi.']);
        }
    }

    /**
     * Menampilkan detail dosen.
     */
    public function show($id_dosen)
    {
        $dosen = Dosen::with([
            'user',
            'kelasWali.tahunAjaran',
            'kelasWali.prodi',
            'jadwalKuliah.masterMatakuliah',
            'jadwalKuliah.tahunAjaran',
            'jadwalKuliah.kelas',
            'jadwalKuliah.ruang'
        ])->findOrFail($id_dosen);

        $tahunAjaranAktif = TahunAjaran::where('status', 'aktif')->first();

        return view('admin.dosen.show', compact('dosen', 'tahunAjaranAktif'));
    }

    /**
     * Menampilkan form untuk mengedit data dosen.
     */
    public function edit($id_dosen)
    {
        $dosen = Dosen::with('user')->findOrFail($id_dosen);

        // Ekstrak username dari email
        $email_username_edit = '';
        if ($dosen->user && $dosen->user->email) {
            $email_username_edit = Str::before($dosen->user->email, '@it.lecturer.pens.ac.id');
        }

        $tahunAjaranAktif = TahunAjaran::where('status', 'aktif')->first();
        $kelasPerwalianAktif = [];
        $jadwalMengajarAktif = [];

        if ($tahunAjaranAktif) {
            $kelasPerwalianAktif = $dosen->kelasWali()
                                      ->where('id_tahun_ajaran', $tahunAjaranAktif->id)
                                      ->with('prodi')
                                      ->get();

            $jadwalMengajarAktif = $dosen->jadwalKuliah()
                                       ->where('id_tahun_ajaran', $tahunAjaranAktif->id)
                                       ->with(['masterMatakuliah', 'kelas.prodi', 'ruang'])
                                       ->get();
        }

        return view('admin.dosen.edit', compact(
            'dosen',
            'email_username_edit',
            'tahunAjaranAktif',
            'kelasPerwalianAktif',
            'jadwalMengajarAktif'
        ));
    }

    /**
     * Memperbarui data dosen di database.
     */
    public function update(Request $request, $id_dosen)
    {
        $dosen = Dosen::findOrFail($id_dosen);
        $user = User::findOrFail($dosen->user_id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:191|regex:/^[a-zA-Z0-9._-]+$/',
            'password' => 'nullable|string|min:8',
            'nidn' => [
                'required', 'string', 'max:255',
                Rule::unique('dosen')->ignore($dosen->id_dosen, 'id_dosen'),
            ],
            'is_dosen_wali' => 'sometimes|boolean',
        ]);

        // Buat email dari username + domain
        $email = $validatedData['username'] . '@it.lecturer.pens.ac.id';
        
        // Validasi email tidak duplikat (kecuali untuk user yang sedang diedit)
        if (User::where('email', $email)->where('id', '!=', $user->id)->exists()) {
            return redirect()->back()->withInput()->withErrors(['username' => 'Username sudah digunakan. Email ' . $email . ' sudah terdaftar.']);
        }

        DB::beginTransaction();
        try {
            $user->name = $validatedData['name'];
            $user->email = $email;
            if ($request->filled('password')) {
                $user->password = Hash::make($validatedData['password']);
            }
            $user->save();

            $dosen->nidn = $validatedData['nidn'];
            $dosen->is_dosen_wali = $request->boolean('is_dosen_wali');
            $dosen->save();

            DB::commit();
            return redirect()->route('admin.dosen.index')->with('success', 'Data dosen berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating dosen: ' . $e->getMessage(), ['exception' => $e, 'request_data' => $request->except('password', '_token')]);
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal memperbarui data dosen. Silakan coba lagi.']);
        }
    }

    /**
     * Menghapus data dosen dari database.
     */
    public function destroy($id_dosen)
    {
        $dosen = Dosen::findOrFail($id_dosen);

        DB::beginTransaction();
        try {
            if ($dosen->user) {
                $dosen->user->delete();
            }
            $dosen->delete();

            DB::commit();
            return redirect()->route('admin.dosen.index')->with('success', 'Dosen berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting dosen: ' . $e->getMessage(), ['exception' => $e]);
            if (str_contains(strtolower($e->getMessage()), 'foreign key constraint fails')) {
                return redirect()->route('admin.dosen.index')->with('error', 'Gagal menghapus dosen. Masih ada data lain yang terkait.');
            }
            return redirect()->route('admin.dosen.index')->with('error', 'Terjadi kesalahan saat menghapus dosen.');
        }
    }
}