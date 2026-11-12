<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DokterRadiologiSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = [
            [
                'name'     => 'dr. Andi Wijaya, Sp.Rad',
                'email'    => 'andi.wijaya@sammarie.test',
                'password' => Hash::make('password123'),
                'role'     => 'dokter_radiologi',
            ],
            [
                'name'     => 'dr. Suhermi Ismail, Sp.Rad',
                'email'    => 'suhermi.ismail@sammarie.test',
                'password' => Hash::make('password123'),
                'role'     => 'dokter_radiologi',
            ],
            // 4 Dokter Radiologi Tambahan:
            [
                'name'     => 'dr. Farhan Maulana, Sp.Rad',
                'email'    => 'farhan.maulana@sammarie.test',
                'password' => Hash::make('password123'),
                'role'     => 'dokter_radiologi',
            ],
            [
                'name'     => 'dr. Nadia Pratiwi, Sp.Rad',
                'email'    => 'nadia.pratiwi@sammarie.test',
                'password' => Hash::make('password123'),
                'role'     => 'dokter_radiologi',
            ],
            [
                'name'     => 'dr. Dimas Setiawan, Sp.Rad',
                'email'    => 'dimas.setiawan@sammarie.test',
                'password' => Hash::make('password123'),
                'role'     => 'dokter_radiologi',
            ],
            [
                'name'     => 'dr. Ratna Sari, Sp.Rad',
                'email'    => 'ratna.sari@sammarie.test',
                'password' => Hash::make('password123'),
                'role'     => 'dokter_radiologi',
            ],
        ];

        foreach ($doctors as $doc) {
            User::firstOrCreate(
                ['email' => $doc['email']],
                $doc
            );
        }
    }
}
