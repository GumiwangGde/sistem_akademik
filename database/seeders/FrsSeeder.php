<?php
    
    namespace Database\Seeders;
    
    use App\Models\FRS;
    use App\Models\Mahasiswa;
    use App\Models\Matakuliah;
    use Illuminate\Database\Console\Seeds\WithoutModelEvents;
    use Illuminate\Database\Seeder;
    
    class FrsSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         */
        public function run(): void
        {
            $mahasiswaAldo = Mahasiswa::where('nrp', '3123500001')->first();
            $mahasiswaRina = Mahasiswa::where('nrp', '3123600002')->first();
    
            $mkPBO = Matakuliah::where('kode_mk', 'IF231001')->first();
            $mkSDA = Matakuliah::where('kode_mk', 'IF231002')->first();
            $mkSisDig = Matakuliah::where('kode_mk', 'TK232002')->first();
    
            // FRS untuk Aldo
            if ($mahasiswaAldo && $mkPBO) {
                FRS::firstOrCreate(
                    ['id_mahasiswa' => $mahasiswaAldo->id_mahasiswa, 'id_mk' => $mkPBO->id_mk],
                    ['status' => 'pending']
                );
            }
            if ($mahasiswaAldo && $mkSDA) {
                FRS::firstOrCreate(
                    ['id_mahasiswa' => $mahasiswaAldo->id_mahasiswa, 'id_mk' => $mkSDA->id_mk],
                    ['status' => 'disetujui'] // Contoh sudah disetujui
                );
            }
    
            // FRS untuk Rina
            if ($mahasiswaRina && $mkSisDig) {
                FRS::firstOrCreate(
                    ['id_mahasiswa' => $mahasiswaRina->id_mahasiswa, 'id_mk' => $mkSisDig->id_mk],
                    ['status' => 'pending']
                );
            }
             // Rina juga ambil PBO (contoh)
            if ($mahasiswaRina && $mkPBO) {
                FRS::firstOrCreate(
                    ['id_mahasiswa' => $mahasiswaRina->id_mahasiswa, 'id_mk' => $mkPBO->id_mk],
                    ['status' => 'ditolak'] // Contoh ditolak
                );
            }
        }
    }
    