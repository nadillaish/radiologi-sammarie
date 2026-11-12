<?php

namespace App\Models;

use App\Models\Interpretation;
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
        'radiologist_id',
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
     * Dokter radiologi penanggung jawab pemeriksaan ini.
     */
    public function radiologist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'radiologist_id');
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
     * Interpretasi untuk pemeriksaan ini.
     */
    public function interpretation(): HasOne
    {
        return $this->hasOne(Interpretation::class);
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

    /**
     * Carbon datetime gabungan dari examination_date dan examination_time.
     */
    public function getExaminationDateTimeAttribute(): ?\Carbon\Carbon
    {
        if (!$this->examination_date) {
            return null;
        }

        $dateStr = $this->examination_date->format('Y-m-d');
        $timeStr = $this->examination_time ?: '00:00:00';

        try {
            return \Carbon\Carbon::parse("{$dateStr} {$timeStr}");
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Hitung durasi (dalam menit) sejak waktu pemeriksaan sampai sekarang.
     * Mengembalikan 0 jika di masa depan atau data waktu tidak valid.
     */
    public function getElapsedMinutesAttribute(): int
    {
        $dt = $this->examination_date_time;
        if (!$dt) {
            return 0;
        }

        $now = now();
        if ($dt->isFuture()) {
            return 0;
        }

        return max(0, (int) $dt->diffInMinutes($now));
    }

    /**
     * Format teks durasi yang ramah pengguna.
     */
    public function getDurationTextAttribute(): string
    {
        $minutes = $this->elapsed_minutes;

        if ($minutes < 60) {
            return "{$minutes} menit";
        }

        if ($minutes < 1440) {
            $hours = intdiv($minutes, 60);
            $remMinutes = $minutes % 60;
            return $remMinutes > 0 ? "{$hours} jam {$remMinutes} mnt" : "{$hours} jam";
        }

        $days = intdiv($minutes, 1440);
        $remHours = intdiv($minutes % 1440, 60);
        return $remHours > 0 ? "{$days} hari {$remHours} jam" : "{$days} hari";
    }

    /**
     * Informasi SLA pemeriksaan:
     * - < 90 menit = Aman
     * - 90–119 menit = Mendekati SLA
     * - >= 120 menit = Melewati SLA
     */
    public function getSlaInfoAttribute(): array
    {
        $minutes = $this->elapsed_minutes;
        $durationText = $this->duration_text;

        if ($minutes >= 120) {
            return [
                'status'        => 'Melewati SLA',
                'color'         => 'badge-sla-danger',
                'bg_class'      => 'bg-rose-50 text-rose-700 border-rose-200',
                'dot_class'     => 'bg-rose-500',
                'minutes'       => $minutes,
                'duration_text' => $durationText,
            ];
        }

        if ($minutes >= 90) {
            return [
                'status'        => 'Mendekati SLA',
                'color'         => 'badge-sla-warning',
                'bg_class'      => 'bg-amber-50 text-amber-700 border-amber-200',
                'dot_class'     => 'bg-amber-500',
                'minutes'       => $minutes,
                'duration_text' => $durationText,
            ];
        }

        return [
            'status'        => 'Aman',
            'color'         => 'badge-sla-aman',
            'bg_class'      => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'dot_class'     => 'bg-emerald-500',
            'minutes'       => $minutes,
            'duration_text' => $durationText,
        ];
    }
}
