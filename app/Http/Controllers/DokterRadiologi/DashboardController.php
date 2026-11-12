<?php

namespace App\Http\Controllers\DokterRadiologi;

use App\Http\Controllers\Controller;
use App\Models\Examination;
use App\Models\Interpretation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard Dokter Radiologi yang sedang login.
     */
    public function index()
    {
        $userId = Auth::id();

        // 1. KPI Cards (Hanya data pemeriksaan milik dokter yang sedang login)
        $menungguCount = Examination::where('radiologist_id', $userId)
            ->where('status', 'MENUNGGU_INTERPRETASI')
            ->count();

        $draftCount = Examination::where('radiologist_id', $userId)
            ->where('status', 'DRAFT_INTERPRETASI')
            ->count();

        $selesaiHariIniCount = Examination::where('radiologist_id', $userId)
            ->where('status', 'FINAL')
            ->whereHas('interpretation', function ($q) use ($userId) {
                $q->where('radiologist_id', $userId)
                  ->whereDate('finalized_at', today());
            })
            ->count();

        // 2. Pemeriksaan aktif (Non-Final) untuk analisis SLA & Worklist
        $activeExaminations = Examination::with(['patient', 'radiographer', 'referringDoctor', 'interpretation'])
            ->where('radiologist_id', $userId)
            ->whereIn('status', ['MENUNGGU_INTERPRETASI', 'DRAFT_INTERPRETASI'])
            ->orderBy('examination_date', 'asc')
            ->orderBy('examination_time', 'asc')
            ->get();

        // Filter pemeriksaan yang mendekati atau melewati SLA (>= 90 menit)
        $slaUrgentExaminations = $activeExaminations->filter(function ($exam) {
            return $exam->elapsed_minutes >= 90;
        })->sortByDesc('elapsed_minutes')->values();

        // Breakdown hitungan SLA
        $slaCounts = [
            'aman'      => $activeExaminations->filter(fn($e) => $e->elapsed_minutes < 90)->count(),
            'mendekati' => $activeExaminations->filter(fn($e) => $e->elapsed_minutes >= 90 && $e->elapsed_minutes < 120)->count(),
            'melewati'  => $activeExaminations->filter(fn($e) => $e->elapsed_minutes >= 120)->count(),
        ];

        // 3. Worklist Aktif Preview (Maksimal 5 terbaru yang belum final)
        $worklistPreview = Examination::with(['patient', 'radiographer', 'referringDoctor', 'interpretation'])
            ->where('radiologist_id', $userId)
            ->whereIn('status', ['MENUNGGU_INTERPRETASI', 'DRAFT_INTERPRETASI'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // 4. Riwayat Interpretasi Saya Preview (Maksimal 5 terbaru yang sudah FINAL)
        $historyPreview = Examination::with(['patient', 'radiographer', 'referringDoctor', 'interpretation'])
            ->where('radiologist_id', $userId)
            ->where('status', 'FINAL')
            ->whereHas('interpretation', function ($q) use ($userId) {
                $q->where('radiologist_id', $userId)->where('status', 'FINAL');
            })
            ->orderByDesc('updated_at')
            ->limit(5)
            ->get();

        return view('dokter-radiologi.dashboard', compact(
            'menungguCount',
            'draftCount',
            'selesaiHariIniCount',
            'activeExaminations',
            'slaUrgentExaminations',
            'slaCounts',
            'worklistPreview',
            'historyPreview'
        ));
    }
}

