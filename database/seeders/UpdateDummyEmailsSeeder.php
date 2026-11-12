<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * UpdateDummyEmailsSeeder
 *
 * Memperbarui email akun dummy yang sudah ada di database
 * dari format generik @example.com menjadi format berbasis nama @sammarie.test.
 *
 * Aman dijalankan berulang kali (idempotent).
 * Tidak mengubah password, nama, atau role.
 *
 * Jalankan dengan:
 *   php artisan db:seed --class=UpdateDummyEmailsSeeder
 */
class UpdateDummyEmailsSeeder extends Seeder
{
    /**
     * Mapping email lama → email baru.
     * Key: email lama (case-insensitive), Value: email baru.
     */
    private array $emailMap = [
        // Radiografer
        'radiografer@example.com'       => 'budi.santoso@sammarie.test',

        // Dokter Radiologi (dari DatabaseSeeder lama)
        'radiolog@example.com'          => 'andi.wijaya@sammarie.test',
        'radiolog2@example.com'         => 'suhermi.ismail@sammarie.test',

        // Dokter Pengirim
        'dokter@example.com'            => 'siti.rahayu@sammarie.test',
        'dokter2@example.com'           => 'hendra.kusuma@sammarie.test',
        'dokter4@example.com'           => 'reza.pratama@sammarie.test',

        // Akun generik dari DokterRadiologiSeeder lama (jika masih ada)
        'dokter.radiologi@sammarie.test' => 'andi.wijaya@sammarie.test',
    ];

    public function run(): void
    {
        foreach ($this->emailMap as $oldEmail => $newEmail) {
            // Lewati jika email baru sudah ada (untuk menghindari duplikat)
            if (User::where('email', $newEmail)->exists()) {
                $this->command->line("  Skip: <{$newEmail}> sudah ada.");
                continue;
            }

            $updated = User::where('email', $oldEmail)->update(['email' => $newEmail]);

            if ($updated) {
                $this->command->info("  Updated: {$oldEmail} → {$newEmail}");
            } else {
                $this->command->line("  Not found: {$oldEmail} (mungkin sudah diperbarui sebelumnya)");
            }
        }

        $this->command->info('Email akun dummy berhasil diperbarui.');
    }
}
