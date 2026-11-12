<?php

namespace App\Http\Controllers;

use App\Models\Imaging;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ImagingServeController extends Controller
{
    /**
     * Serve file imaging dengan autentikasi.
     * Hanya Radiografer dan Dokter Radiologi yang diizinkan melihat.
     */
    public function show(Imaging $imaging)
    {
        $role = Auth::user()->role;

        if (!in_array($role, ['radiografer', 'dokter_radiologi'])) {
            abort(403, 'Anda tidak memiliki akses untuk melihat file imaging ini.');
        }

        // Authorization khusus dokter radiologi: hanya boleh melihat imaging pemeriksaan yang ditugaskan kepadanya
        if ($role === 'dokter_radiologi') {
            if ($imaging->examination && $imaging->examination->radiologist_id && $imaging->examination->radiologist_id !== Auth::id()) {
                abort(403, 'Anda tidak memiliki akses untuk melihat file imaging pemeriksaan ini karena ditugaskan ke dokter lain.');
            }
        }

        $path = storage_path('app/public/' . $imaging->file_path);

        if (!file_exists($path)) {
            abort(404, 'File imaging tidak ditemukan.');
        }

        $mimeType = mime_content_type($path);

        return response()->file($path, [
            'Content-Type'        => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
            'Cache-Control'       => 'private, no-store',
        ]);
    }
}

