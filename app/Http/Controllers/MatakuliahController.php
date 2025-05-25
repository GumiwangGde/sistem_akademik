<?php

namespace App\Http\Controllers;

use App\Models\Matakuliah; // Ini adalah model untuk Jadwal Kuliah
use App\Models\MasterMatakuliah;
use App\Models\Kelas;
use App\Models\Dosen;
use App\Models\Ruang;
use App\Models\TahunAjaran;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Bisa juga menggunakan logger() helper
use Illuminate\Validation\Rule;
use Illuminate\View\View; // Untuk return type hinting
use Illuminate\Http\RedirectResponse; // Untuk return type hinting

class MatakuliahController extends Controller
{
    /**
     * Menampilkan daftar semua jadwal kuliah.
     */
    public function index(Request $request): View
    {
        $query = Matakuliah::with([
            'masterMatakuliah.prodi',
            'tahunAjaran',
            'dosen.user',
            'kelas.prodi',
            'ruang'
        ]);

        if ($request->filled('id_tahun_ajaran')) {
            $query->where('id_tahun_ajaran', $request->id_tahun_ajaran);
        }
        if ($request->filled('id_master_mk')) {
            $query->where('id_master_mk', $request->id_master_mk);
        }
        if ($request->filled('id_kelas')) {
            $query->where('kelas_id', $request->id_kelas);
        }
        if ($request->filled('id_dosen')) {
            $query->where('id_dosen', $request->id_dosen);
        }
        if ($request->filled('id_prodi')) {
            $prodiId = $request->id_prodi;
            $query->where(function ($q) use ($prodiId) {
                $q->whereHas('masterMatakuliah', fn($masterQuery) => $masterQuery->where('id_prodi', $prodiId))
                  ->orWhereHas('kelas', fn($kelasQuery) => $kelasQuery->where('id_prodi', $prodiId));
            });
        }
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_mk', 'like', $searchTerm)
                  ->orWhere('kode_mk', 'like', $searchTerm)
                  ->orWhereHas('masterMatakuliah', fn($mq) => $mq->where('nama_mk', 'like', $searchTerm)->orWhere('kode_mk', 'like', $searchTerm))
                  ->orWhereHas('dosen.user', fn($dq) => $dq->where('name', 'like', $searchTerm))
                  ->orWhereHas('kelas', fn($kq) => $kq->where('nama_kelas', 'like', $searchTerm));
            });
        }

        $jadwalKuliah = $query->latest('matakuliah.created_at')->paginate(10)->withQueryString();

        $tahunAjaranList = TahunAjaran::orderBy('tahun_mulai', 'desc')->orderBy('semester', 'desc')->get();
        $masterMatakuliahList = MasterMatakuliah::orderBy('nama_mk')->get();
        $kelasList = Kelas::with(['tahunAjaran', 'prodi'])->orderBy('nama_kelas')->get();
        $dosenList = Dosen::with('user')->get()->mapWithKeys(fn($item_dosen) => [$item_dosen->id_dosen => ($item_dosen->user->name ?? $item_dosen->nidn) . ' (' . $item_dosen->nidn . ')'])->sort();
        $prodiList = Prodi::orderBy('nama_prodi')->get();

        return view('admin.matakuliah.index', compact(
            'jadwalKuliah', 'tahunAjaranList', 'masterMatakuliahList', 'kelasList', 'dosenList', 'prodiList'
        ));
    }

    /**
     * Menampilkan form untuk membuat jadwal kuliah baru.
     */
    public function create(): View
    {
        $masterMatakuliahList = MasterMatakuliah::with('prodi')->orderBy('nama_mk')->get();
        $tahunAjaranList = TahunAjaran::whereIn('status', ['aktif', 'direncanakan'])
                                      ->orderBy('tahun_mulai', 'desc')->orderBy('semester', 'desc')->get();
        $kelasList = Kelas::where('status', 'active')
                           ->whereNotNull('id_tahun_ajaran')->whereNotNull('id_prodi')
                           ->with(['tahunAjaran', 'prodi'])->orderBy('nama_kelas')->get();
        $dosenList = Dosen::with('user')->get()->mapWithKeys(fn($dosen) => [$dosen->id_dosen => ($dosen->user->name ?? $dosen->nidn) . ' (' . $dosen->nidn . ')'])->sort();
        $ruangList = Ruang::orderBy('nama_ruang')->get();

        return view('admin.matakuliah.create', compact(
            'masterMatakuliahList', 'tahunAjaranList', 'kelasList', 'dosenList', 'ruangList'
        ));
    }

    /**
     * Menyimpan jadwal kuliah baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'id_master_mk' => 'required|exists:master_matakuliah,id_master_mk',
            'id_tahun_ajaran' => 'required|exists:tahun_ajaran,id',
            'id_dosen' => 'required|exists:dosen,id_dosen',
            'kelas_id' => 'required|exists:kelas,id_kelas',
            'ruang_id' => 'required|exists:ruang,id',
            'kode_mk' => [ // Kode MK di jadwal, bisa jadi sama dengan master atau unik per jadwal
                'required', 'string', 'max:20',
                // Rule unique ini mengasumsikan kombinasi (master_mk, tahun_ajaran, kelas, kode_mk_jadwal) harus unik
                // Jika kode_mk di jadwal selalu sama dengan master, mungkin tidak perlu field ini di tabel jadwal
                Rule::unique('matakuliah')->where(function ($query) use ($request) {
                    return $query->where('id_master_mk', $request->id_master_mk)
                                 ->where('id_tahun_ajaran', $request->id_tahun_ajaran)
                                 ->where('kelas_id', $request->kelas_id);
                })
            ],
            'nama_mk' => 'required|string|max:255', // Nama MK di jadwal, bisa sama dengan master
            'sks' => 'required|integer|min:0|max:6',     // SKS di jadwal, bisa sama dengan master
            'semester' => 'required|string|max:20',  // Semester pelaksanaan, misal Ganjil/Genap atau 1-8
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'hari' => ['required', 'string', Rule::in(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'])],
        ]);

        // Opsional: Ambil nama_mk, sks, dari master jika tidak diisi di form atau untuk konsistensi
        // if (empty($validatedData['nama_mk']) || empty($validatedData['sks'])) {
        //     $master = MasterMatakuliah::find($validatedData['id_master_mk']);
        //     $validatedData['nama_mk'] = $validatedData['nama_mk'] ?: $master->nama_mk;
        //     $validatedData['sks'] = $validatedData['sks'] ?: ($master->sks_teori + $master->sks_praktek + $master->sks_lapangan);
        // }

        // Logika cek konflik jadwal (kompleks, di luar scope dasar)
        // ...

        DB::beginTransaction();
        try {
            Matakuliah::create($validatedData);
            DB::commit();
            return redirect()->route('admin.matakuliah.index') // Ganti 'admin.matakuliah.index' dengan nama route Anda
                         ->with('success', 'Jadwal kuliah berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Error creating scheduled course: ' . $e->getMessage(), ['exception' => $e, 'request_data' => $request->except('password','_token')]);
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan jadwal kuliah. Silakan coba lagi.');
        }
    }

    /**
     * Menampilkan form untuk mengedit jadwal kuliah.
     */
    public function edit($id_mk): View // id_mk adalah PK tabel matakuliah (jadwal)
    {
        $jadwalKuliah = Matakuliah::with(['masterMatakuliah', 'tahunAjaran', 'dosen.user', 'kelas.prodi', 'ruang'])
                                  ->findOrFail($id_mk);
        
        $masterMatakuliahList = MasterMatakuliah::with('prodi')->orderBy('nama_mk')->get();
        $tahunAjaranList = TahunAjaran::orderBy('tahun_mulai', 'desc')->orderBy('semester', 'desc')->get();
        $kelasList = Kelas::where('status', 'active')
                           ->whereNotNull('id_tahun_ajaran')->whereNotNull('id_prodi')
                           ->with(['tahunAjaran', 'prodi'])->orderBy('nama_kelas')->get();
        $dosenList = Dosen::with('user')->get()->mapWithKeys(fn($dosen) => [$dosen->id_dosen => ($dosen->user->name ?? $dosen->nidn) . ' (' . $dosen->nidn . ')'])->sort();
        $ruangList = Ruang::orderBy('nama_ruang')->get();
        
        return view('admin.matakuliah.edit', compact(
            'jadwalKuliah', 'masterMatakuliahList', 'tahunAjaranList', 'kelasList', 'dosenList', 'ruangList'
        ));
    }

    /**
     * Memperbarui jadwal kuliah di database.
     */
    public function update(Request $request, $id_mk): RedirectResponse
    {
        $jadwalKuliah = Matakuliah::findOrFail($id_mk);

        $validatedData = $request->validate([
            'id_master_mk' => 'required|exists:master_matakuliah,id_master_mk',
            'id_tahun_ajaran' => 'required|exists:tahun_ajaran,id',
            'id_dosen' => 'required|exists:dosen,id_dosen',
            'kelas_id' => 'required|exists:kelas,id_kelas',
            'ruang_id' => 'required|exists:ruang,id',
            'kode_mk' => [
                'required', 'string', 'max:20',
                Rule::unique('matakuliah')->ignore($jadwalKuliah->id_mk, 'id_mk')->where(function ($query) use ($request) {
                    return $query->where('id_master_mk', $request->id_master_mk)
                                 ->where('id_tahun_ajaran', $request->id_tahun_ajaran)
                                 ->where('kelas_id', $request->kelas_id);
                })
            ],
            'nama_mk' => 'required|string|max:255',
            'sks' => 'required|integer|min:0|max:6',
            'semester' => 'required|string|max:20',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'hari' => ['required', 'string', Rule::in(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'])],
        ]);

        // Logika cek konflik jadwal (serupa dengan di store, kecualikan $id_mk saat ini)
        // ...

        DB::beginTransaction();
        try {
            $jadwalKuliah->update($validatedData);
            DB::commit();
            return redirect()->route('admin.matakuliah.index')
                         ->with('success', 'Jadwal kuliah berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Error updating scheduled course: ' . $e->getMessage(), ['exception' => $e, 'request_data' => $request->except('password','_token')]);
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui jadwal kuliah. Silakan coba lagi.');
        }
    }

    /**
     * Menghapus jadwal kuliah dari database.
     */
    public function destroy($id_mk): RedirectResponse
    {
        $jadwalKuliah = Matakuliah::findOrFail($id_mk);

        // Opsional: Periksa keterkaitan data lain (misal FRS) sebelum menghapus
        // if ($jadwalKuliah->frs()->exists()) {
        //    return redirect()->route('admin.matakuliah.index')->with('error', 'Gagal menghapus. Jadwal masih terdaftar di FRS mahasiswa.');
        // }

        DB::beginTransaction();
        try {
            $jadwalKuliah->delete();
            DB::commit();
            return redirect()->route('admin.matakuliah.index')
                         ->with('success', 'Jadwal kuliah berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Error deleting scheduled course: ' . $e->getMessage(), ['exception' => $e]);
            $errorMessage = 'Terjadi kesalahan saat menghapus jadwal kuliah.';
            if (str_contains($e->getMessage(), 'foreign key constraint fails')) {
                 $errorMessage = 'Gagal menghapus jadwal kuliah. Masih ada data lain yang terkait (misalnya FRS atau Nilai).';
            }
            return redirect()->route('admin.matakuliah.index')->with('error', $errorMessage);
        }
    }
}