<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    protected $fillable = [
        'patient_code',
        'name',
    ];

    /**
     * Semua pemeriksaan milik pasien ini.
     */
    public function examinations(): HasMany
    {
        return $this->hasMany(Examination::class);
    }
}
