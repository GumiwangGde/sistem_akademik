<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            RuangSeeder::class,
            MatakuliahSeeder::class,
            KelasSeeder::class,
            FrsSeeder::class,
            NilaiSeeder::class,
        ]);
    }
}