<?php

namespace App\Http\Controllers\Radiografer;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Simpan pasien baru (mendukung AJAX/JSON dan form POST biasa).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_code'  => ['nullable', 'string', 'max:50', 'unique:patients,patient_code'],
            'name'          => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date'],
            'gender'        => ['required', 'string', 'in:Laki-laki,Perempuan,L,P'],
            'phone'         => ['nullable', 'string', 'max:30'],
            'address'       => ['nullable', 'string', 'max:1000'],
        ], [
            'name.required'          => 'Nama pasien wajib diisi.',
            'date_of_birth.required' => 'Tanggal lahir pasien wajib diisi.',
            'date_of_birth.date'     => 'Format tanggal lahir tidak valid.',
            'gender.required'        => 'Jenis kelamin wajib dipilih.',
            'gender.in'              => 'Jenis kelamin harus Laki-laki atau Perempuan.',
            'patient_code.unique'    => 'Kode pasien sudah terdaftar.',
        ]);

        if (empty($validated['patient_code'])) {
            $validated['patient_code'] = Patient::generatePatientCode();
        }

        if ($validated['gender'] === 'L') {
            $validated['gender'] = 'Laki-laki';
        } elseif ($validated['gender'] === 'P') {
            $validated['gender'] = 'Perempuan';
        }

        $patient = Patient::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pasien baru berhasil didaftarkan.',
                'patient' => [
                    'id'            => $patient->id,
                    'patient_code'  => $patient->patient_code,
                    'name'          => $patient->name,
                    'date_of_birth' => $patient->date_of_birth ? $patient->date_of_birth->format('d/m/Y') : '-',
                    'gender'        => $patient->gender,
                    'phone'         => $patient->phone,
                    'address'       => $patient->address,
                ],
            ]);
        }

        return back()->with('success', "Pasien {$patient->name} ({$patient->patient_code}) berhasil didaftarkan.");
    }
}

