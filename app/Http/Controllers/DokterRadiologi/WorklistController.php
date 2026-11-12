<?php

namespace App\Http\Controllers\DokterRadiologi;

use App\Http\Controllers\Controller;
use App\Models\Examination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorklistController extends Controller
{
    public function index(Request $request)
    {
        // Dokter radiologi yang login HANYA melihat pemeriksaan yang ditugaskan kepadanya
        $query = Examination::with(['patient', 'radiographer', 'referringDoctor', 'radiologist'])
            ->where('radiologist_id', Auth::id())
            ->whereIn('status', ['MENUNGGU_INTERPRETASI', 'DRAFT_INTERPRETASI'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('examination_code', 'like', "%{$search}%")
                  ->orWhereHas('patient', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%")
                         ->orWhere('patient_code', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $examinations = $query->paginate(10)->withQueryString();

        return view('dokter-radiologi.worklist.index', compact('examinations'));
    }
}
