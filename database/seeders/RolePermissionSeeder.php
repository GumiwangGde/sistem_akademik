<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {   

        // Permission 

        // Permission User
        Permission::create(['name' => 'tambah-user']);
        Permission::create(['name' => 'edit-user']);
        Permission::create(['name' => 'hapus-user']);
        Permission::create(['name' => 'lihat-user']);

        // Permission dosen
        Permission::create(['name' => 'tambah-dosen']);
        Permission::create(['name' => 'edit-dosen']);
        Permission::create(['name' => 'hapus-dosen']);
        Permission::create(['name' => 'lihat-dosen']);

        // Permission mahasiswa
        Permission::create(['name' => 'tambah-mahasiswa']);
        Permission::create(['name' => 'edit-mahasiswa']);
        Permission::create(['name' => 'hapus-mahasiswa']);
        Permission::create(['name' => 'lihat-mahasiswa']);

        // Permision matkul
        Permission::create(['name' => 'tambah-matkul']);
        Permission::create(['name' => 'edit-matkul']);
        Permission::create(['name' => 'hapus-matkul']);
        Permission::create(['name' => 'lihat-matkul']);

        // Permission kelas
        Permission::create(['name' => 'tambah-kelas']);
        Permission::create(['name' => 'edit-kelas']);
        Permission::create(['name' => 'hapus-kelas']);
        Permission::create(['name' => 'lihat-kelas']);

        // Permission frs
        Permission::create(['name' => 'tambah-frs']);
        Permission::create(['name' => 'edit-frs']);
        Permission::create(['name' => 'hapus-frs']);
        Permission::create(['name' => 'lihat-frs']);

        // Permission nilai
        Permission::create(['name' => 'tambah-nilai']);
        Permission::create(['name' => 'edit-nilai']);
        Permission::create(['name' => 'hapus-nilai']);
        Permission::create(['name' => 'lihat-nilai']);

        // Permission jadwal
        Permission::create(['name' => 'lihat-jadwal']);

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'dosen']);
        Role::create(['name' => 'mahasiswa']);

        // Permission Role admin

        $roleAdmin = Role::findByName('admin');
        $roleAdmin->givePermissionTo('tambah-user');
        $roleAdmin->givePermissionTo('lihat-user');
        $roleAdmin->givePermissionTo('hapus-user');

        $roleAdmin->givePermissionTo('tambah-dosen');
        $roleAdmin->givePermissionTo('edit-dosen');
        $roleAdmin->givePermissionTo('hapus-dosen');
        $roleAdmin->givePermissionTo('lihat-dosen');

        $roleAdmin->givePermissionTo('tambah-mahasiswa');
        $roleAdmin->givePermissionTo('edit-mahasiswa');
        $roleAdmin->givePermissionTo('hapus-mahasiswa');
        $roleAdmin->givePermissionTo('lihat-mahasiswa');

        $roleAdmin->givePermissionTo('tambah-matkul');
        $roleAdmin->givePermissionTo('edit-matkul');
        $roleAdmin->givePermissionTo('hapus-matkul');
        $roleAdmin->givePermissionTo('lihat-matkul');

        $roleAdmin->givePermissionTo('tambah-kelas');
        $roleAdmin->givePermissionTo('edit-kelas');
        $roleAdmin->givePermissionTo('hapus-kelas');
        $roleAdmin->givePermissionTo('lihat-kelas');

        // Permission role dosen

        $roleDosen = Role::findByName('dosen');
        $roleDosen->givePermissionTo('edit-frs');
        $roleDosen->givePermissionTo('lihat-jadwal');
        $roleDosen->givePermissionTo('edit-nilai');
        $roleDosen->givePermissionTo('lihat-nilai');

        // Permission role mahasiswa

        $roleMahasiswa = Role::findByName('mahasiswa');
        $roleMahasiswa->givePermissionTo('lihat-jadwal');
        $roleMahasiswa->givePermissionTo('lihat-nilai');
        $roleMahasiswa->givePermissionTo('lihat-frs');
    }
}
