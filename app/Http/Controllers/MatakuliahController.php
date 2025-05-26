<?php

namespace App\Http\Controllers; // Asumsi controller ini ada di App\Http\Controllers

use App\Models\Matakuliah; 
use App\Models\MasterMatakuliah;
use App\Models\Kelas;
use App\Models\Dosen;
use App\Models\Ruang;
use App\Models\TahunAjaran;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 
use Illuminate\Validation\Rule;
use Illuminate\View\View; 
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon; // Untuk perbandingan waktu

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
            'kelas.prodi', // Prodi dari kelas tempat jadwal diadakan
            'ruang'
        ]);

        if ($request->filled('id_tahun_ajaran')) {
            $query->where('id_tahun_ajaran', $request->id_tahun_ajaran);
        }
        if ($request->filled('id_master_mk')) {
            $query->where('id_master_mk', $request->id_master_mk);
        }
        if ($request->filled('kelas_id')) { // Menggunakan kelas_id sesuai model & migrasi
            $query->where('kelas_id', $request->kelas_id);
        }
        if ($request->filled('id_dosen')) {
            $query->where('id_dosen', $request->id_dosen);
        }
        if ($request->filled('id_prodi')) { // Filter berdasarkan prodi dari Master MK atau Prodi dari Kelas
            $prodiId = $request->id_prodi;
            $query->where(function ($q) use ($prodiId) {
                $q->whereHas('masterMatakuliah', fn($masterQuery) => $masterQuery->where('id_prodi', $prodiId))
                  ->orWhereHas('kelas', fn($kelasQuery) => $kelasQuery->where('id_prodi', $prodiId));
            });
        }
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_mk', 'like', $searchTerm) // Nama MK di jadwal
                  ->orWhere('kode_mk', 'like', $searchTerm) // Kode MK di jadwal
                  ->orWhereHas('masterMatakuliah', fn($mq) => $mq->where('nama_mk', 'like', $searchTerm)->orWhere('kode_mk', 'like', $searchTerm))
                  ->orWhereHas('dosen.user', fn($dq) => $dq->where('name', 'like', $searchTerm))
                  ->orWhereHas('kelas', fn($kq) => $kq->where('nama_kelas', 'like', $searchTerm));
            });
        }

        $jadwalKuliah = $query->latest('matakuliah.created_at')->paginate(10)->withQueryString();

        $tahunAjaranList = TahunAjaran::orderBy('tahun_mulai', 'desc')->orderBy('semester', 'desc')->get();
        $masterMatakuliahList = MasterMatakuliah::orderBy('nama_mk')->get();
        $kelasList = Kelas::with(['tahunAjaran', 'prodi'])->orderBy('nama_kelas')->get();
        $dosenList = Dosen::with('user')->get()->mapWithKeys(fn($item_dosen) => [$item_dosen->id_dosen => ($item_dosen->user->name ?? $item_dosen->nidn ?? $item_dosen->nama) . ' (' . ($item_dosen->nidn ?? 'NIDN Kosong') . ')'])->sort();
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
        $kelasList = Kelas::where('status', 'active') // Di model Kelas Anda 'active' bukan 'aktif'
                                ->whereNotNull('id_tahun_ajaran')->whereNotNull('id_prodi')
                                ->with(['tahunAjaran', 'prodi'])->orderBy('nama_kelas')->get();
        $dosenList = Dosen::with('user')->get()->mapWithKeys(fn($dosen) => [$dosen->id_dosen => ($dosen->user->name ?? $dosen->nidn ?? $dosen->nama) . ' (' . ($dosen->nidn ?? 'NIDN Kosong') . ')'])->sort();
        $ruangList = Ruang::orderBy('nama_ruang')->get();

        return view('admin.matakuliah.create', compact(
            'masterMatakuliahList', 'tahunAjaranList', 'kelasList', 'dosenList', 'ruangList'
        ));
    }

    /**
     * Fungsi helper untuk memeriksa konflik jadwal.
     * @param int $tahunAjaranId
     * @param string $hari
     * @param string $jamMulai
     * @param string $jamSelesai
     * @param int|null $dosenId
     * @param int|null $ruangId
     * @param int|null $kelasId
     * @param int|null $kecualiJadwalId (untuk kasus update, agar tidak konflik dengan dirinya sendiri)
     * @return array ['konflik' => bool, 'pesan' => string]
     */
    private function cekKonflikJadwal($tahunAjaranId, $hari, $jamMulai, $jamSelesai, $dosenId = null, $ruangId = null, $kelasId = null, $kecualiJadwalId = null): array
    {
        $query = Matakuliah::where('id_tahun_ajaran', $tahunAjaranId)
            ->where('hari', $hari)
            // Logika overlap waktu:
            // (StartA < EndB) and (EndA > StartB)
            ->where(function ($q) use ($jamMulai, $jamSelesai) {
                $q->where('jam_mulai', '<', $jamSelesai)
                  ->where('jam_selesai', '>', $jamMulai);
            });

        if ($kecualiJadwalId) {
            $query->where('id_mk', '!=', $kecualiJadwalId);
        }

        // Cek konflik dosen
        if ($dosenId) {
            $konflikDosen = (clone $query)->where('id_dosen', $dosenId)->exists();
            if ($konflikDosen) {
                $dosen = Dosen::with('user')->find($dosenId);
                $namaDosen = $dosen->user->name ?? $dosen->nidn ?? $dosen->nama ?? "ID {$dosenId}";
                return ['konflik' => true, 'pesan' => "Dosen {$namaDosen} sudah memiliki jadwal lain pada hari dan jam tersebut."];
            }
        }

        // Cek konflik ruangan
        if ($ruangId) {
            $konflikRuang = (clone $query)->where('ruang_id', $ruangId)->exists();
            if ($konflikRuang) {
                $ruang = Ruang::find($ruangId);
                $namaRuang = $ruang->nama_ruang ?? "ID {$ruangId}";
                return ['konflik' => true, 'pesan' => "Ruangan {$namaRuang} sudah terpakai pada hari dan jam tersebut."];
            }
        }
        
        // Cek konflik kelas
        if ($kelasId) {
            $konflikKelas = (clone $query)->where('kelas_id', $kelasId)->exists();
            if ($konflikKelas) {
                $kelas = Kelas::find($kelasId);
                $namaKelas = $kelas->nama_kelas ?? "ID {$kelasId}";
                return ['konflik' => true, 'pesan' => "Kelas {$namaKelas} sudah memiliki jadwal lain pada hari dan jam tersebut."];
            }
        }

        return ['konflik' => false, 'pesan' => ''];
    }


    /**
     * Menyimpan jadwal kuliah baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'id_master_mk' => 'required|exists:master_matakuliah,id_master_mk',
            'id_tahun_ajaran' => 'required|exists:tahun_ajaran,id', // PK tabel tahun_ajaran adalah 'id'
            'id_dosen' => 'required|exists:dosen,id_dosen',
            'kelas_id' => 'required|exists:kelas,id_kelas', // FK di matakuliah, PK di kelas
            'ruang_id' => 'required|exists:ruang,id',     // PK tabel ruang adalah 'id'
            // 'kode_mk' diisi otomatis dari master atau tidak perlu jika tidak override
            // 'nama_mk' diisi otomatis dari master atau tidak perlu jika tidak override
            // 'sks' diisi otomatis dari master atau tidak perlu jika tidak override
            'semester' => 'required|string|max:50', // Semester pelaksanaan, misal Ganjil, Genap, atau 1, 2, 3
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'hari' => ['required', 'string', Rule::in(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'])],
        ]);

        // Mengambil data dari MasterMatakuliah
        $masterMk = MasterMatakuliah::find($validatedData['id_master_mk']);
        if (!$masterMk) { // Seharusnya tidak terjadi karena validasi 'exists'
            return redirect()->back()->withInput()->with('error', 'Master Mata Kuliah tidak ditemukan.');
        }

        // Mengisi data jadwal yang berasal dari master
        $validatedData['kode_mk'] = $masterMk->kode_mk; // Kode MK diambil dari master
        $validatedData['nama_mk'] = $masterMk->nama_mk; // Nama MK diambil dari master
        $validatedData['sks'] = ($masterMk->sks_teori ?? 0) + ($masterMk->sks_praktek ?? 0) + ($masterMk->sks_lapangan ?? 0); // SKS diambil dari master

        // Pengecekan Konflik Jadwal
        $konflik = $this->cekKonflikJadwal(
            $validatedData['id_tahun_ajaran'],
            $validatedData['hari'],
            $validatedData['jam_mulai'],
            $validatedData['jam_selesai'],
            $validatedData['id_dosen'],
            $validatedData['ruang_id'],
            $validatedData['kelas_id']
        );

        if ($konflik['konflik']) {
            return redirect()->back()->withInput()->with('error', $konflik['pesan']);
        }
        
        // Validasi bahwa Master Mata Kuliah yang sama tidak dijadwalkan dua kali
        // untuk kelas, tahun ajaran, hari, dan jam yang sama (mencegah duplikasi absolut)
        // Validasi ini bisa lebih spesifik, misal: apakah MK yang sama boleh ada di hari sama tapi jam beda?
        // Untuk sekarang, kita anggap satu master MK hanya boleh muncul sekali per kelas per tahun ajaran.
        // Jika Anda ingin mengizinkan master MK yang sama dijadwalkan beberapa kali (misal sesi Teori & Praktikum),
        // maka Anda perlu kriteria pembeda lain (misalnya tipe sesi, atau biarkan dosen & jam yang membedakan).
        // Untuk saat ini, kita hilangkan Rule::unique yang kompleks pada 'kode_mk' karena kode_mk, nama_mk, sks diambil dari master.
        // Validasi konflik waktu di atas sudah lebih relevan.

        // Validasi tambahan: Master Matakuliah yang sama tidak boleh dijadwalkan lebih dari satu kali
        // untuk kombinasi tahun ajaran dan kelas yang sama. Ini mencegah satu MK dijadwalkan berkali-kali
        // di kelas yang sama pada semester yang sama (kecuali memang itu desainnya, misal untuk pertemuan berbeda).
        // Jika Anda ingin mengizinkan ini (misalnya, MK yang sama diajar oleh dosen berbeda di kelas yang sama),
        // maka validasi ini bisa di-skip atau disesuaikan.
        $jadwalSudahAda = Matakuliah::where('id_master_mk', $validatedData['id_master_mk'])
                                    ->where('id_tahun_ajaran', $validatedData['id_tahun_ajaran'])
                                    ->where('kelas_id', $validatedData['kelas_id'])
                                    ->exists();
        if ($jadwalSudahAda) {
             // Jika Anda ingin tetap mengizinkan MK yang sama dijadwalkan (misal beda dosen/hari/jam),
             // maka jangan tampilkan error ini. Pengecekan konflik waktu di atas akan lebih berperan.
             // Untuk sekarang, kita beri peringatan.
            // return redirect()->back()->withInput()->with('error', 'Master Mata Kuliah ini sudah pernah dijadwalkan untuk kelas dan tahun ajaran yang sama.');
        }


        DB::beginTransaction();
        try {
            Matakuliah::create($validatedData);
            DB::commit();
            return redirect()->route('admin.matakuliah.index')
                             ->with('success', 'Jadwal kuliah berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat membuat jadwal kuliah: ' . $e->getMessage(), ['exception' => $e, 'request_data' => $request->all()]);
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan jadwal kuliah. Error: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form untuk mengedit jadwal kuliah.
     */
    public function edit($id_mk): View
    {
        $jadwalKuliah = Matakuliah::with(['masterMatakuliah', 'tahunAjaran', 'dosen.user', 'kelas.prodi', 'ruang'])
                                    ->findOrFail($id_mk);
        
        $masterMatakuliahList = MasterMatakuliah::with('prodi')->orderBy('nama_mk')->get();
        $tahunAjaranList = TahunAjaran::orderBy('tahun_mulai', 'desc')->orderBy('semester', 'desc')->get();
        $kelasList = Kelas::where('status', 'active') // Sesuaikan dengan nilai status kelas Anda
                                ->whereNotNull('id_tahun_ajaran')->whereNotNull('id_prodi')
                                ->with(['tahunAjaran', 'prodi'])->orderBy('nama_kelas')->get();
        $dosenList = Dosen::with('user')->get()->mapWithKeys(fn($dosen) => [$dosen->id_dosen => ($dosen->user->name ?? $dosen->nidn ?? $dosen->nama) . ' (' . ($dosen->nidn ?? 'NIDN Kosong') . ')'])->sort();
        $ruangList = Ruang::orderBy('nama_ruang')->get();
        
        return view('admin.matakuliah.edit', compact(
            'jadwalKuliah', 'masterMatakuliahList', 'tahunAjaranList', 'kelasList', 'dosenList', 'ruangList'
        ));
    }

    /**
     * Memperbarui data jadwal kuliah di database.
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
            // 'kode_mk', 'nama_mk', 'sks' akan diambil dari master
            'semester' => 'required|string|max:50',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'hari' => ['required', 'string', Rule::in(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'])],
        ]);

        $masterMk = MasterMatakuliah::find($validatedData['id_master_mk']);
        if (!$masterMk) {
            return redirect()->back()->withInput()->with('error', 'Master Mata Kuliah tidak ditemukan.');
        }
        $validatedData['kode_mk'] = $masterMk->kode_mk;
        $validatedData['nama_mk'] = $masterMk->nama_mk;
        $validatedData['sks'] = ($masterMk->sks_teori ?? 0) + ($masterMk->sks_praktek ?? 0) + ($masterMk->sks_lapangan ?? 0);

        // Pengecekan Konflik Jadwal, kecuali untuk jadwal yang sedang diedit
        $konflik = $this->cekKonflikJadwal(
            $validatedData['id_tahun_ajaran'],
            $validatedData['hari'],
            $validatedData['jam_mulai'],
            $validatedData['jam_selesai'],
            $validatedData['id_dosen'],
            $validatedData['ruang_id'],
            $validatedData['kelas_id'],
            $jadwalKuliah->id_mk // ID jadwal yang sedang diedit, untuk di-exclude dari pengecekan
        );

        if ($konflik['konflik']) {
            return redirect()->back()->withInput()->with('error', $konflik['pesan']);
        }

        // Validasi tambahan jika diperlukan: Master Matakuliah yang sama tidak boleh dijadwalkan lebih dari satu kali
        // untuk kombinasi tahun ajaran dan kelas yang sama, kecuali untuk record yang sedang diedit.
        $jadwalSudahAda = Matakuliah::where('id_master_mk', $validatedData['id_master_mk'])
                                    ->where('id_tahun_ajaran', $validatedData['id_tahun_ajaran'])
                                    ->where('kelas_id', $validatedData['kelas_id'])
                                    ->where('id_mk', '!=', $jadwalKuliah->id_mk) // Kecuali dirinya sendiri
                                    ->exists();
        if ($jadwalSudahAda) {
            // Jika Anda ingin tetap mengizinkan, hapus error ini.
            // return redirect()->back()->withInput()->with('error', 'Master Mata Kuliah ini sudah pernah dijadwalkan untuk kelas dan tahun ajaran yang sama (untuk jadwal lain).');
        }

        DB::beginTransaction();
        try {
            $jadwalKuliah->update($validatedData);
            DB::commit();
            return redirect()->route('admin.matakuliah.index')
                             ->with('success', 'Jadwal kuliah berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat update jadwal kuliah: ' . $e->getMessage(), ['exception' => $e, 'request_data' => $request->all()]);
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui jadwal kuliah. Error: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus data jadwal kuliah.
     */
    public function destroy($id_mk): RedirectResponse
    {
        $jadwalKuliah = Matakuliah::findOrFail($id_mk);

        DB::beginTransaction();
        try {
            // Periksa apakah jadwal ini ada di FRS mahasiswa (opsional, tergantung aturan bisnis)
            if ($jadwalKuliah->frs()->exists()) {
                 DB::rollBack();
                 return redirect()->route('admin.matakuliah.index')
                                ->with('error', 'Gagal menghapus jadwal. Jadwal ini sudah ada di FRS mahasiswa.');
            }

            $jadwalKuliah->delete();
            DB::commit();
            return redirect()->route('admin.matakuliah.index')
                             ->with('success', 'Jadwal kuliah berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menghapus jadwal kuliah: ' . $e->getMessage(), ['exception' => $e]);
            $errorMessage = 'Terjadi kesalahan saat menghapus jadwal kuliah.';
            if (str_contains(strtolower($e->getMessage()), 'foreign key constraint fails')) {
                 $errorMessage = 'Gagal menghapus jadwal kuliah. Masih ada data lain yang terkait (misalnya FRS atau Nilai). Pastikan tidak ada FRS yang mengambil jadwal ini.';
            }
            return redirect()->route('admin.matakuliah.index')->with('error', $errorMessage);
        }
    }

    /**
     * Menampilkan detail jadwal kuliah.
     */
    public function show($id_mk): View // id_mk adalah PK dari tabel matakuliah (jadwal)
    {
        $jadwalKuliah = Matakuliah::with(['dosen.user', 'kelas.prodi', 'kelas.tahunAjaran', 'ruang', 'masterMatakuliah.prodi', 'tahunAjaran'])->findOrFail($id_mk);
        return view('admin.matakuliah.show', compact('jadwalKuliah'));
    }
}
