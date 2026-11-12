<?php

namespace App\Http\Controllers\DokterRadiologi;

use App\Http\Controllers\Controller;
use App\Models\Examination;
use App\Models\Interpretation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InterpretationController extends Controller
{
    /**
     * Tampilkan detail pemeriksaan untuk dokter radiologi.
     */
    public function show(Examination $examination)
    {
        // Authorization: Hanya dokter radiologi yang ditugaskan yang dapat melihat pemeriksaan ini
        if ($examination->radiologist_id !== Auth::id()) {
            abort(403, 'Akses ditolak: Anda bukan dokter radiologi penanggung jawab pemeriksaan ini.');
        }

        $examination->load(['patient', 'radiographer', 'referringDoctor', 'radiologist', 'imagings.uploader', 'interpretation.radiologist']);

        return view('dokter-radiologi.examinations.show', compact('examination'));
    }

    /**
     * Tampilkan form buat/edit interpretasi.
     * Ditolak jika pemeriksaan sudah FINAL atau bukan dokter yang ditugaskan.
     */
    public function create(Examination $examination)
    {
        // Authorization: Validasi penugasan dokter radiologi
        if ($examination->radiologist_id !== Auth::id()) {
            abort(403, 'Akses ditolak: Anda tidak memiliki izin untuk membuat interpretasi pemeriksaan ini.');
        }

        // Tolak akses form jika laporan sudah difinalisasi
        if ($examination->interpretation && $examination->interpretation->status === 'FINAL') {
            return redirect()
                ->route('dokter-radiologi.examinations.show', $examination)
                ->with('error', 'Laporan ini sudah difinalisasi dan tidak dapat diedit kembali.');
        }

        if (!in_array($examination->status, ['MENUNGGU_INTERPRETASI', 'DRAFT_INTERPRETASI'])) {
            return redirect()
                ->route('dokter-radiologi.examinations.show', $examination)
                ->with('error', 'Interpretasi tidak dapat dibuat untuk pemeriksaan dengan status ini.');
        }

        $examination->load(['patient', 'referringDoctor', 'radiologist', 'interpretation']);

        return view('dokter-radiologi.interpretations.form', compact('examination'));
    }

    /**
     * Simpan atau update interpretasi sebagai DRAFT.
     * Ditolak di backend jika interpretation sudah FINAL atau bukan dokter yang ditugaskan.
     */
    public function storeDraft(Request $request, Examination $examination)
    {
        // Authorization: Validasi penugasan dokter radiologi
        if ($examination->radiologist_id !== Auth::id()) {
            abort(403, 'Akses ditolak: Anda tidak memiliki izin untuk menyimpan draft pemeriksaan ini.');
        }

        // Guard backend: tolak jika sudah FINAL
        if ($examination->interpretation && $examination->interpretation->status === 'FINAL') {
            return redirect()
                ->route('dokter-radiologi.examinations.show', $examination)
                ->with('error', 'Laporan ini sudah difinalisasi. Perubahan tidak dapat disimpan.');
        }

        $request->validate([
            'interpretation_text' => ['required', 'string', 'min:10'],
        ], [
            'interpretation_text.required' => 'Isi interpretasi wajib diisi.',
            'interpretation_text.min'      => 'Interpretasi minimal 10 karakter.',
        ]);

        if ($examination->interpretation) {
            $examination->interpretation->update([
                'interpretation_text' => $request->interpretation_text,
                'status'              => 'DRAFT',
            ]);
        } else {
            Interpretation::create([
                'examination_id'      => $examination->id,
                'radiologist_id'      => Auth::id(),
                'interpretation_text' => $request->interpretation_text,
                'status'              => 'DRAFT',
            ]);
        }

        $examination->update(['status' => 'DRAFT_INTERPRETASI']);

        return redirect()
            ->route('dokter-radiologi.examinations.show', $examination)
            ->with('success', 'Draft interpretasi berhasil disimpan. Status: Draft Interpretasi.');
    }

    /**
     * Finalisasi interpretasi.
     * Ditolak di backend jika sudah FINAL atau bukan dokter yang ditugaskan.
     */
    public function finalize(Request $request, Examination $examination)
    {
        // Authorization: Validasi penugasan dokter radiologi
        if ($examination->radiologist_id !== Auth::id()) {
            abort(403, 'Akses ditolak: Anda tidak memiliki izin untuk memfinalisasi pemeriksaan ini.');
        }

        // Guard backend: tolak double-finalisasi
        if ($examination->interpretation && $examination->interpretation->status === 'FINAL') {
            return redirect()
                ->route('dokter-radiologi.examinations.show', $examination)
                ->with('error', 'Laporan ini sudah difinalisasi sebelumnya.');
        }

        $request->validate([
            'interpretation_text' => ['required', 'string', 'min:10'],
        ], [
            'interpretation_text.required' => 'Isi interpretasi wajib diisi sebelum finalisasi.',
            'interpretation_text.min'      => 'Interpretasi minimal 10 karakter.',
        ]);

        if (!$examination->interpretation) {
            Interpretation::create([
                'examination_id'      => $examination->id,
                'radiologist_id'      => Auth::id(),
                'interpretation_text' => $request->interpretation_text,
                'status'              => 'FINAL',
                'finalized_at'        => now(),
            ]);
        } else {
            $examination->interpretation->update([
                'interpretation_text' => $request->interpretation_text,
                'status'              => 'FINAL',
                'finalized_at'        => now(),
            ]);
        }

        $examination->update(['status' => 'FINAL']);

        return redirect()
            ->route('dokter-radiologi.examinations.show', $examination)
            ->with('success', 'Laporan berhasil difinalisasi. Status pemeriksaan: FINAL.');
    }
}
