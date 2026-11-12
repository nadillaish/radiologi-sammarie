<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    protected $fillable = [
        'patient_code',
        'name',
        'date_of_birth',
        'gender',
        'phone',
        'address',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * Semua pemeriksaan milik pasien ini.
     */
    public function examinations(): HasMany
    {
        return $this->hasMany(Examination::class);
    }

    /**
     * Generate kode pasien otomatis (format: P-YYYY-NNN).
     */
    public static function generatePatientCode(): string
    {
        $year = now()->format('Y');
        $prefix = "P-{$year}-";

        $lastPatient = self::where('patient_code', 'like', "{$prefix}%")
            ->orderByDesc('id')
            ->first();

        $nextNumber = 1;
        if ($lastPatient && preg_match('/P-\d{4}-(\d+)/', $lastPatient->patient_code, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        }

        $code = sprintf("P-%s-%03d", $year, $nextNumber);

        while (self::where('patient_code', $code)->exists()) {
            $nextNumber++;
            $code = sprintf("P-%s-%03d", $year, $nextNumber);
        }

        return $code;
    }
}
