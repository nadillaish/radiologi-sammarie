<?php

namespace App\Http\Controllers\Radiografer;

use App\Http\Controllers\Controller;
use App\Models\Examination;
use App\Models\Imaging;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ImagingController extends Controller
{
    /**
     * Upload file hasil imaging untuk pemeriksaan.
     */
    public function store(Request $request, Examination $examination)
    {
        $request->validate([
            'imaging_file' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:10240', // 10 MB
            ],
        ], [
            'imaging_file.required' => 'File imaging wajib dipilih.',
            'imaging_file.file'     => 'Upload harus berupa file.',
            'imaging_file.mimes'    => 'Format file yang diizinkan: JPG, JPEG, PNG, PDF.',
            'imaging_file.max'      => 'Ukuran file maksimal 10 MB.',
        ]);

        $file = $request->file('imaging_file');
        $filename = 'IMG-' . $examination->examination_code . '-' . now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs("imagings/{$examination->id}", $filename, 'public');

        // Hapus imaging lama jika ada (ganti file)
        if ($examination->latestImaging) {
            $oldImaging = $examination->latestImaging;
            Storage::disk('public')->delete($oldImaging->file_path);
            $oldImaging->delete();
        }

        Imaging::create([
            'examination_id'    => $examination->id,
            'file_path'         => $path,
            'original_filename' => $file->getClientOriginalName(),
            'uploaded_by'       => Auth::id(),
        ]);

        return redirect()
            ->route('radiografer.examinations.show', $examination)
            ->with('success', 'File imaging berhasil diunggah.');
    }

    /**
     * Kirim pemeriksaan untuk interpretasi.
     * Status berubah dari TERDAFTAR → MENUNGGU_INTERPRETASI.
     */
    public function sendForInterpretation(Examination $examination)
    {
        if (!$examination->latestImaging) {
            return redirect()
                ->route('radiografer.examinations.show', $examination)
                ->with('error', 'Tidak dapat mengirim untuk interpretasi: file imaging belum diunggah.');
        }

        if ($examination->status !== 'TERDAFTAR') {
            return redirect()
                ->route('radiografer.examinations.show', $examination)
                ->with('error', 'Pemeriksaan ini sudah dikirim untuk interpretasi sebelumnya.');
        }

        $examination->update(['status' => 'MENUNGGU_INTERPRETASI']);

        return redirect()
            ->route('radiografer.examinations.show', $examination)
            ->with('success', 'Pemeriksaan berhasil dikirim untuk interpretasi. Status: Menunggu Interpretasi.');
    }
}
