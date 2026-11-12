<?php

namespace App\Http\Controllers\Radiografer;

use App\Http\Controllers\Controller;
use App\Models\Examination;
use App\Models\Patient;
use App\Models\RadiologyServiceCategory;
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
        $query = Examination::with(['patient', 'radiographer', 'referringDoctor', 'radiologist'])
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
        $patients         = Patient::orderBy('name')->get();
        $referringDoctors = User::where('role', 'dokter_pengirim')->orderBy('name')->get();
        $radiologists     = User::where('role', 'dokter_radiologi')->with('serviceCategories')->orderBy('name')->get();
        $categories       = RadiologyServiceCategory::orderBy('name')->get();
        $newPatientCode   = Patient::generatePatientCode();

        return view('radiografer.examinations.create', compact('patients', 'referringDoctors', 'radiologists', 'categories', 'newPatientCode'));
    }

    /**
     * Simpan pemeriksaan baru (mendukung pasien existing atau tambah pasien baru langsung).
     */
    public function store(Request $request)
    {
        $patientMode = $request->input('patient_mode', 'existing');

        $rules = [
            'referring_doctor_id' => ['required', 'exists:users,id'],
            'radiologist_id'      => ['required', 'exists:users,id'],
            'examination_type'    => ['required', 'string', 'max:255'],
            'examination_date'    => ['required', 'date'],
            'examination_time'    => ['required', 'date_format:H:i'],
        ];

        $messages = [
            'referring_doctor_id.required' => 'Dokter pengirim wajib dipilih.',
            'referring_doctor_id.exists'   => 'Dokter pengirim tidak ditemukan.',
            'radiologist_id.required'      => 'Dokter radiologi penanggung jawab wajib dipilih.',
            'radiologist_id.exists'        => 'Dokter radiologi tidak ditemukan.',
            'examination_type.required'    => 'Jenis pemeriksaan wajib diisi.',
            'examination_date.required'    => 'Tanggal pemeriksaan wajib diisi.',
            'examination_date.date'        => 'Format tanggal tidak valid.',
            'examination_time.required'    => 'Jam pemeriksaan wajib diisi.',
            'examination_time.date_format' => 'Format jam tidak valid (HH:MM).',
        ];

        if ($patientMode === 'new') {
            $rules['new_patient_code']    = ['nullable', 'string', 'max:50', 'unique:patients,patient_code'];
            $rules['new_patient_name']    = ['required', 'string', 'max:255'];
            $rules['new_patient_dob']     = ['required', 'date'];
            $rules['new_patient_gender']  = ['required', 'string', 'in:Laki-laki,Perempuan,L,P'];
            $rules['new_patient_phone']   = ['nullable', 'string', 'max:30'];
            $rules['new_patient_address'] = ['nullable', 'string', 'max:1000'];

            $messages['new_patient_name.required']   = 'Nama pasien baru wajib diisi.';
            $messages['new_patient_dob.required']    = 'Tanggal lahir pasien baru wajib diisi.';
            $messages['new_patient_dob.date']        = 'Format tanggal lahir tidak valid.';
            $messages['new_patient_gender.required'] = 'Jenis kelamin pasien baru wajib dipilih.';
            $messages['new_patient_gender.in']       = 'Jenis kelamin harus Laki-laki atau Perempuan.';
            $messages['new_patient_code.unique']     = 'Kode pasien sudah terdaftar.';
        } else {
            $rules['patient_id'] = ['required', 'exists:patients,id'];
            $messages['patient_id.required'] = 'Pasien wajib dipilih.';
            $messages['patient_id.exists']   = 'Pasien tidak ditemukan.';
        }

        $validated = $request->validate($rules, $messages);

        // Validasi role radiologist_id harus dokter_radiologi
        $radiologistUser = User::find($validated['radiologist_id']);
        if (!$radiologistUser || $radiologistUser->role !== 'dokter_radiologi') {
            return back()->withInput()->withErrors(['radiologist_id' => 'User yang dipilih bukan dokter radiologi.']);
        }

        if ($patientMode === 'new') {
            $patientCode = !empty($validated['new_patient_code']) ? $validated['new_patient_code'] : Patient::generatePatientCode();
            $gender = in_array($validated['new_patient_gender'], ['L', 'Laki-laki']) ? 'Laki-laki' : 'Perempuan';

            $patient = Patient::create([
                'patient_code'  => $patientCode,
                'name'          => $validated['new_patient_name'],
                'date_of_birth' => $validated['new_patient_dob'],
                'gender'        => $gender,
                'phone'         => $validated['new_patient_phone'] ?? null,
                'address'       => $validated['new_patient_address'] ?? null,
            ]);

            $patientId = $patient->id;
        } else {
            $patientId = $validated['patient_id'];
        }

        $examinationCode = 'EX-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));

        Examination::create([
            'examination_code'    => $examinationCode,
            'patient_id'          => $patientId,
            'radiographer_id'     => Auth::id(),
            'referring_doctor_id' => $validated['referring_doctor_id'],
            'radiologist_id'      => $validated['radiologist_id'],
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
        $examination->load(['patient', 'radiographer', 'referringDoctor', 'radiologist', 'imagings.uploader']);

        return view('radiografer.examinations.show', compact('examination'));
    }
}
