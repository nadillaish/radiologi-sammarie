<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Imaging extends Model
{
    protected $fillable = [
        'examination_id',
        'file_path',
        'original_filename',
        'uploaded_by',
    ];

    /**
     * Pemeriksaan yang dimiliki imaging ini.
     */
    public function examination(): BelongsTo
    {
        return $this->belongsTo(Examination::class);
    }

    /**
     * User yang mengupload imaging ini.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
