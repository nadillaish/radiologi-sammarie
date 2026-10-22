<?php

namespace Database\Seeders;

use App\Models\Examination;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ─── Users ────────────────────────────────────────────────────────────
        $radiografer = User::create([
            'name'     => 'Budi Santoso',
            'email'    => 'radiografer@example.com',
            'password' => Hash::make('password123'),
            'role'     => 'radiografer',
        ]);

        $dokterRadiologi = User::create([
            'name'     => 'dr. Andi Wijaya, Sp.Rad',
            'email'    => 'radiolog@example.com',
            'password' => Hash::make('password123'),
            'role'     => 'dokter_radiologi',
        ]);

        $dokterPengirim1 = User::create([
            'name'     => 'dr. Siti Rahayu, Sp.OG',
            'email'    => 'dokter@example.com',
            'password' => Hash::make('password123'),
            'role'     => 'dokter_pengirim',
        ]);

        $dokterPengirim2 = User::create([
            'name'     => 'dr. Hendra Kusuma, Sp.A',
            'email'    => 'dokter2@example.com',
            'password' => Hash::make('password123'),
            'role'     => 'dokter_pengirim',
        ]);

        // ─── Patients (Data Dummy) ────────────────────────────────────────────
        $patients = [
            ['patient_code' => 'P-2024-001', 'name' => 'Dewi Kartika'],
            ['patient_code' => 'P-2024-002', 'name' => 'Rini Wulandari'],
            ['patient_code' => 'P-2024-003', 'name' => 'Bayi Ny. Sari Indah'],
            ['patient_code' => 'P-2024-004', 'name' => 'Ahmad Fauzan'],
            ['patient_code' => 'P-2024-005', 'name' => 'Nurul Hidayah'],
        ];

        foreach ($patients as $patientData) {
            Patient::create($patientData);
        }

        $p1 = Patient::where('patient_code', 'P-2024-001')->first();
        $p2 = Patient::where('patient_code', 'P-2024-002')->first();
        $p3 = Patient::where('patient_code', 'P-2024-003')->first();
        $p4 = Patient::where('patient_code', 'P-2024-004')->first();

        // ─── Examinations (Data Dummy) ────────────────────────────────────────
        Examination::create([
            'examination_code'    => 'EX-20241015-DEMO01',
            'patient_id'          => $p1->id,
            'radiographer_id'     => $radiografer->id,
            'referring_doctor_id' => $dokterPengirim1->id,
            'examination_type'    => 'USG Abdomen',
            'examination_date'    => '2024-10-15',
            'examination_time'    => '08:30:00',
            'status'              => 'MENUNGGU_INTERPRETASI',
        ]);

        Examination::create([
            'examination_code'    => 'EX-20241016-DEMO02',
            'patient_id'          => $p2->id,
            'radiographer_id'     => $radiografer->id,
            'referring_doctor_id' => $dokterPengirim1->id,
            'examination_type'    => 'Rontgen Thorax PA',
            'examination_date'    => '2024-10-16',
            'examination_time'    => '09:00:00',
            'status'              => 'TERDAFTAR',
        ]);

        Examination::create([
            'examination_code'    => 'EX-20241016-DEMO03',
            'patient_id'          => $p3->id,
            'radiographer_id'     => $radiografer->id,
            'referring_doctor_id' => $dokterPengirim2->id,
            'examination_type'    => 'USG Fetomaternal',
            'examination_date'    => '2024-10-16',
            'examination_time'    => '10:15:00',
            'status'              => 'TERDAFTAR',
        ]);

        Examination::create([
            'examination_code'    => 'EX-20241017-DEMO04',
            'patient_id'          => $p4->id,
            'radiographer_id'     => $radiografer->id,
            'referring_doctor_id' => $dokterPengirim2->id,
            'examination_type'    => 'CT Scan Kepala Tanpa Kontras',
            'examination_date'    => '2024-10-17',
            'examination_time'    => '11:00:00',
            'status'              => 'TERDAFTAR',
        ]);
    }
}
