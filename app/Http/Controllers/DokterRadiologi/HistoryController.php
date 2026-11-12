<?php

namespace App\Http\Controllers\DokterRadiologi;

use App\Http\Controllers\Controller;
use App\Models\Examination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    /**
     * Tampilkan riwayat interpretasi FINAL milik dokter radiologi yang sedang login.
     * Fitur ini personal (bukan arsip global lintas dokter).
     */
    public function index(Request $request)
    {
        $userId = Auth::id();

        $query = Examination::with(['patient', 'radiographer', 'referringDoctor', 'radiologist', 'interpretation'])
            ->where('radiologist_id', $userId)
            ->where('status', 'FINAL')
            ->whereHas('interpretation', function ($q) use ($userId) {
                $q->where('radiologist_id', $userId)
                  ->where('status', 'FINAL');
            })
            ->orderByDesc('updated_at');

        // Filter pencarian: Nama Pasien, Kode Pasien, atau ID Pemeriksaan
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('examination_code', 'like', "%{$search}%")
                  ->orWhereHas('patient', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%")
                         ->orWhere('patient_code', 'like', "%{$search}%");
                  });
            });
        }

        // Filter jenis pemeriksaan
        if ($request->filled('examination_type') && $request->examination_type !== 'all') {
            $query->where('examination_type', $request->examination_type);
        }

        // Filter tanggal finalisasi
        if ($request->filled('finalized_date')) {
            $date = $request->finalized_date;
            $query->whereHas('interpretation', function ($q) use ($date) {
                $q->whereDate('finalized_at', $date);
            });
        }

        $examinations = $query->paginate(10)->withQueryString();

        // Opsi jenis pemeriksaan untuk dropdown filter
        $examinationTypes = Examination::where('radiologist_id', $userId)
            ->where('status', 'FINAL')
            ->distinct()
            ->pluck('examination_type');

        return view('dokter-radiologi.history.index', compact('examinations', 'examinationTypes'));
    }
}

