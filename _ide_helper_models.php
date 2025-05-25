<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id_dosen
 * @property int $user_id
 * @property string $nidn
 * @property int $is_dosen_wali
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Dosen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dosen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dosen query()
 * @method static \Illuminate\Database\Eloquent\Builder|Dosen whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dosen whereIdDosen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dosen whereIsDosenWali($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dosen whereNidn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dosen whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dosen whereUserId($value)
 */
	class Dosen extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id_frs
 * @property int $id_mahasiswa
 * @property int $id_mk
 * @property int|null $id_tahun_ajaran
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Matakuliah $jadwalKuliah
 * @property-read \App\Models\Mahasiswa $mahasiswa
 * @property-read \App\Models\Nilai|null $nilai
 * @property-read \App\Models\TahunAjaran|null $tahunAjaran
 * @method static \Illuminate\Database\Eloquent\Builder|FRS newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FRS newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FRS query()
 * @method static \Illuminate\Database\Eloquent\Builder|FRS whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FRS whereIdFrs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FRS whereIdMahasiswa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FRS whereIdMk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FRS whereIdTahunAjaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FRS whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FRS whereUpdatedAt($value)
 */
	class FRS extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id_kelas
 * @property string $nama_kelas
 * @property string $status
 * @property int|null $id_dosen_wali
 * @property int|null $id_tahun_ajaran
 * @property int|null $id_prodi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Dosen|null $dosenWali
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Matakuliah> $jadwalKuliah
 * @property-read int|null $jadwal_kuliah_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Mahasiswa> $mahasiswa
 * @property-read int|null $mahasiswa_count
 * @property-read \App\Models\Prodi|null $prodi
 * @property-read \App\Models\TahunAjaran|null $tahunAjaran
 * @method static \Illuminate\Database\Eloquent\Builder|Kelas newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Kelas newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Kelas query()
 * @method static \Illuminate\Database\Eloquent\Builder|Kelas whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kelas whereIdDosenWali($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kelas whereIdKelas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kelas whereIdProdi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kelas whereIdTahunAjaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kelas whereNamaKelas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kelas whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kelas whereUpdatedAt($value)
 */
	class Kelas extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id_mahasiswa
 * @property int $user_id
 * @property int $id_kelas
 * @property string $nrp
 * @property string $nama
 * @property int|null $id_prodi
 * @property int|null $id_prodi_new
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FRS> $frs
 * @property-read int|null $frs_count
 * @property-read \App\Models\Kelas $kelas
 * @property-read \App\Models\Prodi|null $prodi
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Mahasiswa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mahasiswa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mahasiswa query()
 * @method static \Illuminate\Database\Eloquent\Builder|Mahasiswa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mahasiswa whereIdKelas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mahasiswa whereIdMahasiswa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mahasiswa whereIdProdi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mahasiswa whereIdProdiNew($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mahasiswa whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mahasiswa whereNrp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mahasiswa whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mahasiswa whereUserId($value)
 */
	class Mahasiswa extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id_master_mk
 * @property string $kode_mk
 * @property string $nama_mk
 * @property int $sks_teori
 * @property int $sks_praktek
 * @property int $sks_lapangan
 * @property int|null $sks_total
 * @property int|null $semester_default
 * @property int|null $id_prodi
 * @property string|null $deskripsi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Matakuliah> $jadwalKuliah
 * @property-read int|null $jadwal_kuliah_count
 * @property-read \App\Models\Prodi|null $prodi
 * @method static \Illuminate\Database\Eloquent\Builder|MasterMatakuliah newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MasterMatakuliah newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MasterMatakuliah query()
 * @method static \Illuminate\Database\Eloquent\Builder|MasterMatakuliah whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterMatakuliah whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterMatakuliah whereIdMasterMk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterMatakuliah whereIdProdi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterMatakuliah whereKodeMk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterMatakuliah whereNamaMk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterMatakuliah whereSemesterDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterMatakuliah whereSksLapangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterMatakuliah whereSksPraktek($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterMatakuliah whereSksTeori($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterMatakuliah whereSksTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterMatakuliah whereUpdatedAt($value)
 */
	class MasterMatakuliah extends \Eloquent {}
}

namespace App\Models{
/**
 * Model ini merepresentasikan tabel 'matakuliah' yang sekarang berfungsi sebagai 'Jadwal Kuliah'.
 *
 * @property int $id_mk
 * @property int $id_dosen
 * @property int $kelas_id
 * @property string $kode_mk
 * @property string $nama_mk
 * @property int $sks
 * @property string $semester
 * @property string $jam_mulai
 * @property string $jam_selesai
 * @property string $hari
 * @property int $ruang_id
 * @property int|null $id_tahun_ajaran
 * @property int|null $id_master_mk
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Dosen $dosen
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FRS> $frs
 * @property-read int|null $frs_count
 * @property-read \App\Models\Kelas $kelas
 * @property-read \App\Models\MasterMatakuliah|null $masterMatakuliah
 * @property-read \App\Models\Ruang $ruang
 * @property-read \App\Models\TahunAjaran|null $tahunAjaran
 * @method static \Illuminate\Database\Eloquent\Builder|Matakuliah newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Matakuliah newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Matakuliah query()
 * @method static \Illuminate\Database\Eloquent\Builder|Matakuliah whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matakuliah whereHari($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matakuliah whereIdDosen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matakuliah whereIdMasterMk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matakuliah whereIdMk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matakuliah whereIdTahunAjaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matakuliah whereJamMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matakuliah whereJamSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matakuliah whereKelasId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matakuliah whereKodeMk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matakuliah whereNamaMk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matakuliah whereRuangId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matakuliah whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matakuliah whereSks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matakuliah whereUpdatedAt($value)
 */
	class Matakuliah extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id_nilai
 * @property int $id_frs
 * @property string|null $nilai_angka
 * @property string|null $nilai_huruf
 * @property string $status_penilaian
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\FRS $frs
 * @method static \Illuminate\Database\Eloquent\Builder|Nilai newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Nilai newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Nilai query()
 * @method static \Illuminate\Database\Eloquent\Builder|Nilai whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nilai whereIdFrs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nilai whereIdNilai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nilai whereNilaiAngka($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nilai whereNilaiHuruf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nilai whereStatusPenilaian($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nilai whereUpdatedAt($value)
 */
	class Nilai extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id_prodi
 * @property string $kode_prodi
 * @property string $nama_prodi
 * @property string|null $jenjang
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Kelas> $kelas
 * @property-read int|null $kelas_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Mahasiswa> $mahasiswa
 * @property-read int|null $mahasiswa_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MasterMatakuliah> $masterMatakuliah
 * @property-read int|null $master_matakuliah_count
 * @method static \Illuminate\Database\Eloquent\Builder|Prodi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Prodi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Prodi query()
 * @method static \Illuminate\Database\Eloquent\Builder|Prodi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Prodi whereIdProdi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Prodi whereJenjang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Prodi whereKodeProdi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Prodi whereNamaProdi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Prodi whereUpdatedAt($value)
 */
	class Prodi extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $nama_ruang
 * @property int $kapasitas
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Matakuliah> $matakuliah
 * @property-read int|null $matakuliah_count
 * @method static \Illuminate\Database\Eloquent\Builder|Ruang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ruang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ruang query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ruang whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ruang whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ruang whereKapasitas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ruang whereNamaRuang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ruang whereUpdatedAt($value)
 */
	class Ruang extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $kode_tahun_ajaran
 * @property string $nama_tahun_ajaran
 * @property string $semester
 * @property string $tahun_mulai
 * @property string $tahun_selesai
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $tanggal_mulai_perkuliahan
 * @property \Illuminate\Support\Carbon|null $tanggal_selesai_perkuliahan
 * @property \Illuminate\Support\Carbon|null $tanggal_mulai_frs
 * @property \Illuminate\Support\Carbon|null $tanggal_selesai_frs
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FRS> $frs
 * @property-read int|null $frs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Matakuliah> $jadwalKuliah
 * @property-read int|null $jadwal_kuliah_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Kelas> $kelas
 * @property-read int|null $kelas_count
 * @method static \Illuminate\Database\Eloquent\Builder|TahunAjaran newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TahunAjaran newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TahunAjaran query()
 * @method static \Illuminate\Database\Eloquent\Builder|TahunAjaran whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TahunAjaran whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TahunAjaran whereKodeTahunAjaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TahunAjaran whereNamaTahunAjaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TahunAjaran whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TahunAjaran whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TahunAjaran whereTahunMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TahunAjaran whereTahunSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TahunAjaran whereTanggalMulaiFrs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TahunAjaran whereTanggalMulaiPerkuliahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TahunAjaran whereTanggalSelesaiFrs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TahunAjaran whereTanggalSelesaiPerkuliahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TahunAjaran whereUpdatedAt($value)
 */
	class TahunAjaran extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property mixed $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Dosen|null $dosen
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutRole($roles, $guard = null)
 */
	class User extends \Eloquent {}
}

