<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Interpretation extends Model
{
    protected $fillable = [
        'examination_id',
        'radiologist_id',
        'interpretation_text',
        'status',
        'finalized_at',
    ];

    protected $casts = [
        'finalized_at' => 'datetime',
    ];

    /**
     * Pemeriksaan yang terkait dengan interpretasi ini.
     */
    public function examination(): BelongsTo
    {
        return $this->belongsTo(Examination::class);
    }

    /**
     * Dokter radiologi yang membuat interpretasi ini.
     */
    public function radiologist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'radiologist_id');
    }
}
