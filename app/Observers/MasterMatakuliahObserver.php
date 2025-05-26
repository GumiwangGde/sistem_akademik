<?php

namespace App\Observers;

use App\Models\MasterMatakuliah;

class MasterMatakuliahObserver
{
    public function updated(MasterMatakuliah $masterMatakuliah): void
    {   
        // dd('OBSERVER DIPANGGIL');

        if ($masterMatakuliah->wasChanged('kode_mk')) {
            // dd('KODE MK BERUBAH DAN KONDISI TERPENUHI');
            $newKodeMk = $masterMatakuliah->kode_mk;
            $masterMatakuliah->jadwalKuliah()->update([
                'kode_mk' => $newKodeMk
            ]);
        }
    }
}