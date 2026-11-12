<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RadiologyServiceCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
        'keywords',
    ];

    /**
     * Dokter radiologi dalam kategori layanan ini.
     */
    public function radiologists(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'radiologist_service_category',
            'category_id',
            'radiologist_id'
        )->withTimestamps();
    }
}

