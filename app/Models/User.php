<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Pemeriksaan yang ditangani oleh radiografer ini.
     */
    public function examinationsAsRadiographer(): HasMany
    {
        return $this->hasMany(Examination::class, 'radiographer_id');
    }

    /**
     * Pemeriksaan yang dirujuk oleh dokter pengirim ini.
     */
    public function examinationsAsReferringDoctor(): HasMany
    {
        return $this->hasMany(Examination::class, 'referring_doctor_id');
    }

    /**
     * Imaging yang di-upload oleh user ini.
     */
    public function imagings(): HasMany
    {
        return $this->hasMany(Imaging::class, 'uploaded_by');
    }

    /**
     * Helper: apakah user adalah radiografer?
     */
    public function isRadiografer(): bool
    {
        return $this->role === 'radiografer';
    }

    /**
     * Helper: apakah user adalah dokter radiologi?
     */
    public function isDokterRadiologi(): bool
    {
        return $this->role === 'dokter_radiologi';
    }

    /**
     * Helper: apakah user adalah dokter pengirim?
     */
    public function isDokterPengirim(): bool
    {
        return $this->role === 'dokter_pengirim';
    }
}
