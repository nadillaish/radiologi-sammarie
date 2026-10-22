@extends('layouts.app')

@section('title', 'Tambah Pemeriksaan')

@section('page-title', 'Tambah Pemeriksaan')
@section('page-subtitle', 'Daftarkan pemeriksaan radiologi baru')

@section('sidebar-nav')
    <a href="{{ route('radiografer.examinations.index') }}" class="nav-item">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        Pemeriksaan
    </a>
@endsection

@section('content')
<div class="max-w-2xl">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-5">
        <a href="{{ route('radiografer.examinations.index') }}" class="hover:text-blue-600 transition-colors">Pemeriksaan</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-700 font-medium">Tambah Baru</span>
    </div>

    <div class="card">
        <div class="border-b border-gray-100 pb-4 mb-6">
            <h2 class="font-semibold text-gray-800">Formulir Pendaftaran Pemeriksaan</h2>
            <p class="text-sm text-gray-500 mt-1">Isi semua field yang bertanda <span class="text-red-500">*</span> dengan benar</p>
        </div>

        <form id="form-tambah-pemeriksaan" action="{{ route('radiografer.examinations.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Pasien --}}
            <div class="form-group">
                <label for="patient_id" class="form-label">Pasien <span class="text-red-500">*</span></label>
                <select id="patient_id" name="patient_id" class="form-input @error('patient_id') form-input-error @enderror">
                    <option value="">— Pilih Pasien —</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                            {{ $patient->patient_code }} — {{ $patient->name }}
                        </option>
                    @endforeach
                </select>
                @error('patient_id')
                    <p class="form-error-msg">{{ $message }}</p>
                @enderror
            </div>

            {{-- Dokter Pengirim --}}
            <div class="form-group">
                <label for="referring_doctor_id" class="form-label">Dokter Pengirim <span class="text-red-500">*</span></label>
                <select id="referring_doctor_id" name="referring_doctor_id" class="form-input @error('referring_doctor_id') form-input-error @enderror">
                    <option value="">— Pilih Dokter Pengirim —</option>
                    @foreach($referringDoctors as $doctor)
                        <option value="{{ $doctor->id }}" {{ old('referring_doctor_id') == $doctor->id ? 'selected' : '' }}>
                            {{ $doctor->name }}
                        </option>
                    @endforeach
                </select>
                @error('referring_doctor_id')
                    <p class="form-error-msg">{{ $message }}</p>
                @enderror
            </div>

            {{-- Jenis Pemeriksaan --}}
            <div class="form-group">
                <label for="examination_type" class="form-label">Jenis Pemeriksaan <span class="text-red-500">*</span></label>
                <input
                    id="examination_type"
                    type="text"
                    name="examination_type"
                    value="{{ old('examination_type') }}"
                    placeholder="Contoh: USG Abdomen, Rontgen Thorax PA, CT Scan Kepala"
                    class="form-input @error('examination_type') form-input-error @enderror"
                    list="examination-types"
                >
                <datalist id="examination-types">
                    <option value="USG Abdomen">
                    <option value="USG Fetomaternal">
                    <option value="USG Mammae">
                    <option value="USG Pelvis">
                    <option value="Rontgen Thorax PA">
                    <option value="Rontgen Thorax AP">
                    <option value="Rontgen Abdomen">
                    <option value="Rontgen Pelvis">
                    <option value="Rontgen Extremitas">
                    <option value="CT Scan Kepala Tanpa Kontras">
                    <option value="CT Scan Kepala Dengan Kontras">
                    <option value="CT Scan Abdomen">
                    <option value="MRI Kepala">
                    <option value="MRI Spine">
                    <option value="Mammografi">
                    <option value="Bone Survey">
                </datalist>
                @error('examination_type')
                    <p class="form-error-msg">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tanggal dan Jam (2 kolom) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="form-group">
                    <label for="examination_date" class="form-label">Tanggal Pemeriksaan <span class="text-red-500">*</span></label>
                    <input
                        id="examination_date"
                        type="date"
                        name="examination_date"
                        value="{{ old('examination_date', now()->format('Y-m-d')) }}"
                        class="form-input @error('examination_date') form-input-error @enderror"
                    >
                    @error('examination_date')
                        <p class="form-error-msg">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="examination_time" class="form-label">Jam Pemeriksaan <span class="text-red-500">*</span></label>
                    <input
                        id="examination_time"
                        type="time"
                        name="examination_time"
                        value="{{ old('examination_time', now()->format('H:i')) }}"
                        class="form-input @error('examination_time') form-input-error @enderror"
                    >
                    @error('examination_time')
                        <p class="form-error-msg">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Radiografer (read-only) --}}
            <div class="form-group">
                <label class="form-label">Radiografer</label>
                <input type="text" value="{{ Auth::user()->name }}" class="form-input bg-gray-50 text-gray-500 cursor-not-allowed" readonly>
                <p class="text-xs text-gray-400 mt-1">Otomatis terisi berdasarkan akun yang sedang login.</p>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                <button type="submit" id="btn-simpan-pemeriksaan" class="btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Pemeriksaan
                </button>
                <a href="{{ route('radiografer.examinations.index') }}" class="btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>

</div>
@endsection
