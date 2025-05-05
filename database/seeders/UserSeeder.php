<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'admin',
            'email' => 'adit@it.admin.pens.ac.id',
            'password' => bcrypt('12345678'),
        ]);

        $admin->assignRole('admin');

        $dosen = User::create([
            'name' => 'dosen',
            'email' => 'gugum@it.lecturer.pens.ac.id',
            'password' => bcrypt('12345678'),
        ]);

        $dosen->assignRole('dosen');

        $mahasiswa = User::create([
            'name' => 'mahasiswa',
            'email' => 'aldo@it.student.pens.ac.id',
            'password' => bcrypt('12345678'),
        ]);

        $mahasiswa->assignRole('mahasiswa');
    }
}
