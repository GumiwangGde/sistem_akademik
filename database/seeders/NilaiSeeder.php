<?php
    
    namespace Database\Seeders;
    
    use App\Models\Nilai;
    use App\Models\FRS;
    use App\Models\Mahasiswa; // Untuk mencari FRS berdasarkan mahasiswa
    use App\Models\Matakuliah; // Untuk mencari FRS berdasarkan matakuliah
    use Illuminate\Database\Console\Seeds\WithoutModelEvents;
    use Illuminate\Database\Seeder;
    
    class NilaiSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         */
        public function run(): void
        {
            // Ambil FRS yang sudah disetujui untuk diisi nilai
            $frsAldoSDA = FRS::whereHas('mahasiswa', function ($query) {
                $query->where('nrp', '3123500001'); // Aldo
            })->whereHas('matakuliah', function ($query) {
                $query->where('kode_mk', 'IF231002'); // SDA
            })->where('status', 'disetujui')->first();
    
            if ($frsAldoSDA) {
                Nilai::updateOrCreate(
                    ['id_frs' => $frsAldoSDA->id_frs],
                    [
                        'nilai_angka' => 88.50,
                        'nilai_huruf' => 'A',
                        'status_penilaian' => 'sudah_dinilai',
                    ]
                );
            }
    
            // Contoh FRS lain yang disetujui tapi belum dinilai (record nilai akan dibuat oleh sistem/trigger FRS)
            // Namun, jika ingin memastikan record nilai ada untuk testing, bisa dibuat di sini
            $frsAldoPBO = FRS::whereHas('mahasiswa', function ($query) {
                $query->where('nrp', '3123500001'); // Aldo
            })->whereHas('matakuliah', function ($query) {
                $query->where('kode_mk', 'IF231001'); // PBO
            })->where('status', 'disetujui')->first(); // Pastikan ini disetujui dulu
    
            if ($frsAldoPBO) {
                Nilai::updateOrCreate(
                    ['id_frs' => $frsAldoPBO->id_frs],
                    [ // Nilai default saat FRS disetujui tapi belum diinput dosen
                        'nilai_angka' => null,
                        'nilai_huruf' => null,
                        'status_penilaian' => 'belum_dinilai',
                    ]
                );
            }
        }
    }
    