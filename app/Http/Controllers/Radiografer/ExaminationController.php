<?php

namespace App\Http\Controllers\Radiografer;

use App\Http\Controllers\Controller;
use App\Models\Examination;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ExaminationController extends Controller
{
    /**
     * Daftar pemeriksaan.
     */
    public function index(Request $request)
    {
        $query = Examination::with(['patient', 'radiographer', 'referringDoctor'])
            ->orderBy('created_at', 'desc');

        // Filter pencarian berdasarkan nama/ID pasien atau ID pemeriksaan
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

        // Filter berdasarkan status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $examinations = $query->paginate(10)->withQueryString();

        return view('radiografer.examinations.index', compact('examinations'));
    }

    /**
     * Tampilkan form tambah pemeriksaan.
     */
    public function create()
    {
        $patients       = Patient::orderBy('name')->get();
        $referringDoctors = User::where('role', 'dokter_pengirim')->orderBy('name')->get();

        return view('radiografer.examinations.create', compact('patients', 'referringDoctors'));
    }

    /**
     * Simpan pemeriksaan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'          => ['required', 'exists:patients,id'],
            'referring_doctor_id' => ['required', 'exists:users,id'],
            'examination_type'    => ['required', 'string', 'max:255'],
            'examination_date'    => ['required', 'date'],
            'examination_time'    => ['required', 'date_format:H:i'],
        ], [
            'patient_id.required'          => 'Pasien wajib dipilih.',
            'patient_id.exists'            => 'Pasien tidak ditemukan.',
            'referring_doctor_id.required' => 'Dokter pengirim wajib dipilih.',
            'referring_doctor_id.exists'   => 'Dokter pengirim tidak ditemukan.',
            'examination_type.required'    => 'Jenis pemeriksaan wajib diisi.',
            'examination_date.required'    => 'Tanggal pemeriksaan wajib diisi.',
            'examination_date.date'        => 'Format tanggal tidak valid.',
            'examination_time.required'    => 'Jam pemeriksaan wajib diisi.',
            'examination_time.date_format' => 'Format jam tidak valid (HH:MM).',
        ]);

        $examinationCode = 'EX-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));

        Examination::create([
            'examination_code'    => $examinationCode,
            'patient_id'          => $validated['patient_id'],
            'radiographer_id'     => Auth::id(),
            'referring_doctor_id' => $validated['referring_doctor_id'],
            'examination_type'    => $validated['examination_type'],
            'examination_date'    => $validated['examination_date'],
            'examination_time'    => $validated['examination_time'],
            'status'              => 'TERDAFTAR',
        ]);

        return redirect()
            ->route('radiografer.examinations.index')
            ->with('success', 'Pemeriksaan berhasil didaftarkan.');
    }

    /**
     * Detail pemeriksaan.
     */
    public function show(Examination $examination)
    {
        $examination->load(['patient', 'radiographer', 'referringDoctor', 'imagings.uploader']);

        return view('radiografer.examinations.show', compact('examination'));
    }
}
