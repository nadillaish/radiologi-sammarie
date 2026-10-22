<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Examination extends Model
{
    protected $fillable = [
        'examination_code',
        'patient_id',
        'radiographer_id',
        'referring_doctor_id',
        'examination_type',
        'examination_date',
        'examination_time',
        'status',
    ];

    protected $casts = [
        'examination_date' => 'date',
    ];

    /**
     * Pasien yang menjalani pemeriksaan ini.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Radiografer yang menangani pemeriksaan ini.
     */
    public function radiographer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'radiographer_id');
    }

    /**
     * Dokter pengirim yang merujuk pemeriksaan ini.
     */
    public function referringDoctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referring_doctor_id');
    }

    /**
     * Semua file imaging untuk pemeriksaan ini.
     */
    public function imagings(): HasMany
    {
        return $this->hasMany(Imaging::class);
    }

    /**
     * Imaging terbaru (yang aktif digunakan).
     */
    public function latestImaging(): HasOne
    {
        return $this->hasOne(Imaging::class)->latestOfMany();
    }

    /**
     * Label badge status dengan warna Tailwind.
     */
    public function statusBadge(): array
    {
        return match ($this->status) {
            'TERDAFTAR'              => ['label' => 'Terdaftar', 'color' => 'badge-terdaftar'],
            'MENUNGGU_INTERPRETASI'  => ['label' => 'Menunggu Interpretasi', 'color' => 'badge-menunggu'],
            'DRAFT_INTERPRETASI'     => ['label' => 'Draft Interpretasi', 'color' => 'badge-draft'],
            'FINAL'                  => ['label' => 'Final', 'color' => 'badge-final'],
            default                  => ['label' => $this->status, 'color' => 'badge-default'],
        };
    }
}
