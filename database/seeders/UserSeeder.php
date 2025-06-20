<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin BVF',
                'email' => 'admin@example.com',
                'password' => Hash::make('12345678'),
                'roles' => 'Admin',
                'phone' => '081234567890',
                'nik' => '1234567890123456',
                'place_of_birth' => 'Jakarta',
                'date_of_birth' => '1990-01-01',
                'gender' => 'L',
                'religion' => 'Islam',
                'provinces' => 'DKI Jakarta',
                'provincesId' => '31',
                'regencies' => 'Jakarta Selatan',
                'regenciesId' => '3171',
                'districts' => 'Setiabudi',
                'districtsId' => '317110',
                'villages' => 'Setiabudi',
                'villagesId' => '31711020',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Mahasiswa ASQ',
                'email' => 'mahasiswa@example.com',
                'password' => Hash::make('12345678'),
                'roles' => 'Mahasiswa',
                'phone' => '081298765432',
                'nik' => '6543210987654321',
                'place_of_birth' => 'Bandung',
                'date_of_birth' => '1995-05-15',
                'gender' => 'P',
                'religion' => 'Kristen',
                'provinces' => 'Jawa Barat',
                'provincesId' => '32',
                'regencies' => 'Kota Bandung',
                'regenciesId' => '3273',
                'districts' => 'Bandung Wetan',
                'districtsId' => '327301',
                'villages' => 'Tamansari',
                'villagesId' => '32730120',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}