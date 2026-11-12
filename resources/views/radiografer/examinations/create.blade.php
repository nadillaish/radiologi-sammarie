@extends('layouts.app')

@section('title', 'Tambah Pemeriksaan')

@section('page-title', 'Pendaftaran Pemeriksaan Baru')
@section('page-subtitle', 'Lengkapi data pasien dan rincian pemeriksaan radiologi')

@section('sidebar-nav')
    <a href="{{ route('radiografer.examinations.index') }}" class="nav-item">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        Pemeriksaan
    </a>
@endsection

@section('content')
<style>
    /* Styling Khusus Form Pendaftaran Pemeriksaan */
    .form-section-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 22px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        margin-bottom: 20px;
    }

    .form-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 14px;
        margin-bottom: 18px;
        border-bottom: 1px solid #f1f5f9;
        gap: 12px;
    }

    .form-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
    }

    .form-section-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: #eff6ff;
        color: #2563eb;
    }

    /* Segmented Tab Control */
    .segmented-control {
        display: inline-flex;
        align-items: center;
        background: #f1f5f9;
        padding: 4px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        gap: 4px;
    }

    .segmented-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 7px;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        background: transparent;
        color: #64748b;
    }

    .segmented-pill:hover {
        color: #1e293b;
    }

    .segmented-pill.active {
        background: #1d4ed8;
        color: #ffffff;
        box-shadow: 0 2px 4px rgba(29, 78, 216, 0.25);
    }

    /* Layout Grid */
    .form-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    @media (max-width: 640px) {
        .form-grid-2 {
            grid-template-columns: 1fr;
        }
        .form-section-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    /* Notice Box */
    .notice-box {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-left: 4px solid #3b82f6;
        border-radius: 8px;
        padding: 12px 14px;
        font-size: 13px;
        color: #475569;
        line-height: 1.5;
    }

    /* Selected Patient Preview */
    .patient-selected-card {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 10px;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-top: 12px;
    }

    .patient-selected-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 8px;
        border-radius: 999px;
        background: #dcfce7;
        color: #15803d;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 4px;
    }

    .btn-quick-save {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        background: linear-gradient(135deg, #059669, #047857);
        color: white;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.15s;
        box-shadow: 0 2px 4px rgba(5, 150, 105, 0.25);
    }

    .btn-quick-save:hover {
        background: linear-gradient(135deg, #047857, #065f46);
        box-shadow: 0 4px 8px rgba(5, 150, 105, 0.35);
        transform: translateY(-1px);
    }
</style>

<div class="max-w-3xl">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-5">
        <a href="{{ route('radiografer.examinations.index') }}" class="hover:text-blue-600 transition-colors">Pemeriksaan</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-800 font-medium">Pendaftaran Baru</span>
    </div>

    <form id="form-tambah-pemeriksaan" action="{{ route('radiografer.examinations.store') }}" method="POST">
        @csrf

        {{-- ════════════════════════════════════════════════════════════════
             BAGIAN 1: DATA PASIEN
        ════════════════════════════════════════════════════════════════ --}}
        <div class="form-section-card">
            <div class="form-section-header">
                <div class="form-section-title">
                    <span class="form-section-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </span>
                    <div>
                        <span class="block text-gray-800">Identitas Pasien <span class="text-red-500">*</span></span>
                        <span class="text-xs font-normal text-gray-500">Pilih dari rekam medis yang sudah ada atau daftarkan baru</span>
                    </div>
                </div>

                {{-- Segmented Pill Switcher --}}
                <div class="segmented-control">
                    <button type="button" id="tab-btn-existing" class="segmented-pill active" onclick="setPatientMode('existing')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Pasien Terdaftar
                    </button>
                    <button type="button" id="tab-btn-new" class="segmented-pill" onclick="setPatientMode('new')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        + Pasien Baru
                    </button>
                </div>
                {{-- Hidden input for form submission --}}
                <input type="hidden" name="patient_mode" id="patient_mode_input" value="{{ old('patient_mode', 'existing') }}">
            </div>

            {{-- Pesan Alert AJAX --}}
            <div id="patient-ajax-alert" style="display: none; margin-bottom: 16px;"></div>

            {{-- ── TAB 1: PILIH PASIEN TERDAFTAR ── --}}
            <div id="panel-patient-existing" style="display: block;">
                {{-- Input Cari Pasien --}}
                <div class="form-group" style="margin-bottom: 14px;">
                    <label for="patient_search_input" class="form-label text-xs uppercase tracking-wider text-gray-500 font-bold">
                        Pencarian Rekam Medis
                    </label>
                    <div style="position: relative;">
                        <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input
                            id="patient_search_input"
                            type="text"
                            placeholder="Ketik nama pasien atau kode (misal: Dewi atau P-2024)..."
                            class="form-input"
                            style="padding-left: 38px;"
                            oninput="filterPatients(this.value)"
                        >
                    </div>
                </div>

                {{-- Dropdown Pilihan Pasien --}}
                <div class="form-group">
                    <label for="patient_id" class="form-label text-xs uppercase tracking-wider text-gray-500 font-bold">
                        Pilih Pasien <span class="text-red-500">*</span>
                    </label>
                    <select id="patient_id" name="patient_id" class="form-input @error('patient_id') form-input-error @enderror" onchange="onPatientSelected(this.value)">
                        <option value="">— Klik untuk memilih pasien —</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}"
                                    data-name="{{ $patient->name }}"
                                    data-code="{{ $patient->patient_code }}"
                                    data-gender="{{ $patient->gender ?? '-' }}"
                                    data-dob="{{ $patient->date_of_birth ? $patient->date_of_birth->format('d/m/Y') : '-' }}"
                                    {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                {{ $patient->patient_code }} — {{ $patient->name }}
                            </option>
                        @endforeach
                    </select>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 4px;">
                        <span class="text-xs text-gray-400" id="patient-match-info">
                            Tersedia {{ count($patients) }} pasien terdaftar.
                        </span>
                    </div>
                    @error('patient_id')
                        <p class="form-error-msg" style="margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Preview Card Pasien Terpilih --}}
                <div id="selected-patient-card" class="patient-selected-card" style="display: none;">
                    <div>
                        <div class="patient-selected-badge">✓ Pasien Terpilih</div>
                        <div class="font-bold text-gray-900 text-base" id="sp-name">-</div>
                        <div class="text-xs text-gray-600 font-mono mt-0.5" id="sp-code">-</div>
                        <div class="text-xs text-gray-500 mt-1 flex gap-3">
                            <span id="sp-gender">Jenis Kelamin: -</span>
                            <span>•</span>
                            <span id="sp-dob">Tgl Lahir: -</span>
                        </div>
                    </div>
                    <button type="button" onclick="clearSelectedPatient()" class="btn-secondary" style="padding: 6px 12px; font-size: 12px; color: #dc2626; border-color: #fecaca; background: #fef2f2;">
                        Ganti Pasien
                    </button>
                </div>
            </div>

            {{-- ── TAB 2: TAMBAH PASIEN BARU ── --}}
            <div id="panel-patient-new" style="display: none;">
                <div class="notice-box" style="margin-bottom: 18px;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <span class="font-semibold text-gray-800">Pendaftaran Pasien Baru</span>
                        <p class="text-xs text-gray-500 mt-0.5">Lengkapi data diri pasien. Setelah didaftarkan, pasien akan otomatis terpilih untuk pemeriksaan ini.</p>
                    </div>
                </div>

                <div class="form-grid-2" style="margin-bottom: 14px;">
                    {{-- Kode Pasien --}}
                    <div class="form-group">
                        <label for="new_patient_code" class="form-label">
                            Kode Pasien
                            <span class="text-xs text-gray-400 font-normal">(Otomatis)</span>
                        </label>
                        <input
                            id="new_patient_code"
                            type="text"
                            name="new_patient_code"
                            value="{{ old('new_patient_code', $newPatientCode) }}"
                            class="form-input"
                            style="background: #f8fafc; font-family: monospace; color: #475569;"
                            readonly
                        >
                        @error('new_patient_code')
                            <p class="form-error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nama Pasien --}}
                    <div class="form-group">
                        <label for="new_patient_name" class="form-label">
                            Nama Lengkap Pasien <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="new_patient_name"
                            type="text"
                            name="new_patient_name"
                            value="{{ old('new_patient_name') }}"
                            placeholder="Contoh: Siti Aminah"
                            class="form-input @error('new_patient_name') form-input-error @enderror"
                        >
                        @error('new_patient_name')
                            <p class="form-error-msg">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="form-grid-2" style="margin-bottom: 14px;">
                    {{-- Tanggal Lahir --}}
                    <div class="form-group">
                        <label for="new_patient_dob" class="form-label">
                            Tanggal Lahir <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="new_patient_dob"
                            type="date"
                            name="new_patient_dob"
                            value="{{ old('new_patient_dob') }}"
                            class="form-input @error('new_patient_dob') form-input-error @enderror"
                        >
                        @error('new_patient_dob')
                            <p class="form-error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div class="form-group">
                        <label for="new_patient_gender" class="form-label">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <select id="new_patient_gender" name="new_patient_gender" class="form-input @error('new_patient_gender') form-input-error @enderror">
                            <option value="">— Pilih Jenis Kelamin —</option>
                            <option value="Perempuan" {{ old('new_patient_gender') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            <option value="Laki-laki" {{ old('new_patient_gender') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        </select>
                        @error('new_patient_gender')
                            <p class="form-error-msg">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="form-grid-2" style="margin-bottom: 18px;">
                    {{-- Telepon --}}
                    <div class="form-group">
                        <label for="new_patient_phone" class="form-label">
                            Nomor Telepon <span class="text-xs text-gray-400 font-normal">(Opsional)</span>
                        </label>
                        <input
                            id="new_patient_phone"
                            type="text"
                            name="new_patient_phone"
                            value="{{ old('new_patient_phone') }}"
                            placeholder="Contoh: 08123456789"
                            class="form-input @error('new_patient_phone') form-input-error @enderror"
                        >
                        @error('new_patient_phone')
                            <p class="form-error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Alamat --}}
                    <div class="form-group">
                        <label for="new_patient_address" class="form-label">
                            Alamat <span class="text-xs text-gray-400 font-normal">(Opsional)</span>
                        </label>
                        <input
                            id="new_patient_address"
                            type="text"
                            name="new_patient_address"
                            value="{{ old('new_patient_address') }}"
                            placeholder="Contoh: Jl. Basuki Rahmat No. 12"
                            class="form-input @error('new_patient_address') form-input-error @enderror"
                        >
                        @error('new_patient_address')
                            <p class="form-error-msg">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Action Quick Register Button --}}
                <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 12px; border-top: 1px dashed #e2e8f0;">
                    <span class="text-xs text-gray-500">
                        Atau langsung simpan bersama pemeriksaan via tombol di bawah.
                    </span>
                    <button type="button" id="btn-save-patient-ajax" onclick="saveNewPatientAjax()" class="btn-quick-save">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Daftarkan & Pilih Pasien
                    </button>
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════════════════════════════════════
             BAGIAN 2: DETAIL PEMERIKSAAN
        ════════════════════════════════════════════════════════════════ --}}
        <div class="form-section-card">
            <div class="form-section-header">
                <div class="form-section-title">
                    <span class="form-section-icon" style="background: #fdf2f8; color: #b52d91;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </span>
                    <div>
                        <span class="block text-gray-800">Detail Rujukan & Pemeriksaan <span class="text-red-500">*</span></span>
                        <span class="text-xs font-normal text-gray-500">Tentukan dokter pengirim, modalitas pemeriksaan, dan dokter radiologi penanggung jawab</span>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                {{-- 1. Dokter Pengirim --}}
                <div class="form-group">
                    <label for="referring_doctor_id" class="form-label">
                        Dokter Pengirim <span class="text-red-500">*</span>
                    </label>
                    <select id="referring_doctor_id" name="referring_doctor_id" class="form-input @error('referring_doctor_id') form-input-error @enderror">
                        <option value="">— Pilih Dokter Pengirim —</option>
                        @foreach($referringDoctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ old('referring_doctor_id') == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->name }}
                            </option>
                        @endforeach
                    </select>
                    <span class="text-xs text-gray-500 mt-1 block">Dokter yang meminta atau merujuk pasien untuk pemeriksaan radiologi.</span>
                    @error('referring_doctor_id')
                        <p class="form-error-msg" style="margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 2. Jenis Pemeriksaan Radiologi --}}
                <div class="form-group">
                    <label for="examination_type" class="form-label">
                        Jenis Pemeriksaan Radiologi <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="examination_type"
                        type="text"
                        name="examination_type"
                        value="{{ old('examination_type') }}"
                        placeholder="Ketik atau pilih dari daftar (contoh: USG Abdomen, Rontgen Thorax PA)"
                        class="form-input @error('examination_type') form-input-error @enderror"
                        list="examination-types"
                        oninput="onExamTypeInput(this.value)"
                        onchange="onExamTypeInput(this.value)"
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
                        <option value="HSG">
                        <option value="Bone Survey">
                    </datalist>
                    <span class="text-xs text-gray-400 mt-1 block">Ketik atau pilih jenis pemeriksaan untuk memfilter dokter spesialis terkait.</span>
                    @error('examination_type')
                        <p class="form-error-msg" style="margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 3. Dokter Radiologi Penanggung Jawab --}}
                <div class="form-group">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <label for="radiologist_id" class="form-label">
                            Dokter Radiologi Penanggung Jawab <span class="text-red-500">*</span>
                        </label>
                        <button type="button" id="btn-show-all-rads" onclick="resetRadiologistFilter()" style="display: none; background: none; border: none; color: #1d4ed8; text-decoration: underline; cursor: pointer; font-size: 11px; font-weight: 600;">
                            Tampilkan Semua Dokter
                        </button>
                    </div>
                    <select id="radiologist_id" name="radiologist_id" class="form-input @error('radiologist_id') form-input-error @enderror">
                        <option value="">— Pilih Dokter Radiologi —</option>
                        @foreach($radiologists as $rad)
                            <option value="{{ $rad->id }}"
                                    data-categories="{{ $rad->serviceCategories->pluck('name')->join(', ') }}"
                                    {{ old('radiologist_id') == $rad->id ? 'selected' : '' }}>
                                {{ $rad->name }} {{ $rad->serviceCategories->isNotEmpty() ? '(' . $rad->serviceCategories->pluck('name')->join(', ') . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                    <span class="text-xs text-gray-500 mt-1 block">Dokter radiologi yang ditugaskan untuk membaca hasil pemeriksaan dan memberikan expertise.</span>
                    <div id="radiologist-filter-badge" style="display: none; margin-top: 6px; padding: 6px 10px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 6px; font-size: 12px; color: #1e40af;">
                        <span id="radiologist-filter-badge-text">Difilter berdasarkan kategori</span>
                    </div>
                    @error('radiologist_id')
                        <p class="form-error-msg" style="margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 4. Tanggal dan Jam --}}
                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="examination_date" class="form-label">Tanggal Pelaksanaan <span class="text-red-500">*</span></label>
                        <input
                            id="examination_date"
                            type="date"
                            name="examination_date"
                            value="{{ old('examination_date', now()->format('Y-m-d')) }}"
                            class="form-input @error('examination_date') form-input-error @enderror"
                        >
                        @error('examination_date')
                            <p class="form-error-msg" style="margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="examination_time" class="form-label">Jam Pelaksanaan <span class="text-red-500">*</span></label>
                        <input
                            id="examination_time"
                            type="time"
                            name="examination_time"
                            value="{{ old('examination_time', now()->format('H:i')) }}"
                            class="form-input @error('examination_time') form-input-error @enderror"
                        >
                        @error('examination_time')
                            <p class="form-error-msg" style="margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- 5. Radiografer Petugas --}}
                <div class="form-group">
                    <label class="form-label">Radiografer Penanggung Jawab</label>
                    <input type="text" value="{{ Auth::user()->name }}" class="form-input" style="background: #f8fafc; color: #64748b; cursor: not-allowed;" readonly>
                    <span class="text-xs text-gray-400 mt-1">Otomatis terhubung dengan akun radiografer yang sedang aktif bertugas.</span>
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════════════════════════════════════
             ACTION BUTTONS
        ════════════════════════════════════════════════════════════════ --}}
        <div style="display: flex; align-items: center; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-top: 16px;">
            <a href="{{ route('radiografer.examinations.index') }}" class="btn-secondary" style="padding: 10px 20px;">
                Batal
            </a>
            <button type="submit" id="btn-simpan-pemeriksaan" class="btn-primary" style="padding: 10px 24px; font-size: 14px; background: linear-gradient(135deg, #1d4ed8, #0f2744);">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Simpan & Daftarkan Pemeriksaan
            </button>
        </div>
    </form>
</div>

<script>
    // Data list pasien master
    let patientsData = [
        @foreach($patients as $patient)
            {
                id: {{ $patient->id }},
                name: @json($patient->name),
                code: @json($patient->patient_code),
                gender: @json($patient->gender ?? '-'),
                dob: @json($patient->date_of_birth ? $patient->date_of_birth->format('d/m/Y') : '-')
            },
        @endforeach
    ];

    // Master Data Dokter Radiologi & Kategori
    const radiologistsMaster = [
        @foreach($radiologists as $rad)
            {
                id: {{ $rad->id }},
                name: @json($rad->name),
                categories: @json($rad->serviceCategories->pluck('name')->all())
            },
        @endforeach
    ];

    const categoriesMaster = [
        @foreach($categories as $cat)
            {
                id: {{ $cat->id }},
                name: @json($cat->name),
                rawKeywords: @json($cat->keywords)
            },
        @endforeach
    ];

    let manualShowAllRadiologists = false;

    // Ganti Mode Pasien (existing vs new)
    function setPatientMode(mode) {
        const btnExisting = document.getElementById('tab-btn-existing');
        const btnNew = document.getElementById('tab-btn-new');
        const modeInput = document.getElementById('patient_mode_input');
        const panelExisting = document.getElementById('panel-patient-existing');
        const panelNew = document.getElementById('panel-patient-new');

        modeInput.value = mode;

        if (mode === 'existing') {
            btnExisting.classList.add('active');
            btnNew.classList.remove('active');
            panelExisting.style.display = 'block';
            panelNew.style.display = 'none';
        } else {
            btnNew.classList.add('active');
            btnExisting.classList.remove('active');
            panelNew.style.display = 'block';
            panelExisting.style.display = 'none';
        }
    }

    // Filter Pasien Searchable
    function filterPatients(keyword) {
        const select = document.getElementById('patient_id');
        const matchInfo = document.getElementById('patient-match-info');
        const currentSelected = select.value;
        const q = (keyword || '').toLowerCase().trim();

        // Kosongkan options kecuali default
        select.innerHTML = '<option value="">— Klik untuk memilih pasien —</option>';

        let count = 0;
        patientsData.forEach(p => {
            const matchName = p.name.toLowerCase().includes(q);
            const matchCode = p.code.toLowerCase().includes(q);

            if (!q || matchName || matchCode) {
                const opt = document.createElement('option');
                opt.value = p.id;
                opt.textContent = `${p.code} — ${p.name}`;
                opt.dataset.name = p.name;
                opt.dataset.code = p.code;
                opt.dataset.gender = p.gender;
                opt.dataset.dob = p.dob;

                if (p.id == currentSelected) {
                    opt.selected = true;
                }
                select.appendChild(opt);
                count++;
            }
        });

        if (q) {
            matchInfo.textContent = `Ditemukan ${count} pasien sesuai pencarian "${keyword}".`;
        } else {
            matchInfo.textContent = `Tersedia ${count} pasien terdaftar.`;
        }
    }

    // Event ketika pasien dipilih di select
    function onPatientSelected(id) {
        const card = document.getElementById('selected-patient-card');
        const select = document.getElementById('patient_id');

        if (!id) {
            card.style.display = 'none';
            return;
        }

        const opt = select.options[select.selectedIndex];
        if (opt) {
            document.getElementById('sp-name').textContent = opt.dataset.name || '-';
            document.getElementById('sp-code').textContent = opt.dataset.code || '-';
            document.getElementById('sp-gender').textContent = 'Jenis Kelamin: ' + (opt.dataset.gender || '-');
            document.getElementById('sp-dob').textContent = 'Tgl Lahir: ' + (opt.dataset.dob || '-');
            card.style.display = 'flex';
        }
    }

    function clearSelectedPatient() {
        document.getElementById('patient_id').value = '';
        document.getElementById('selected-patient-card').style.display = 'none';
        document.getElementById('patient_search_input').value = '';
        filterPatients('');
    }

    // AJAX Simpan Pasien Baru & Langsung Pilih
    async function saveNewPatientAjax() {
        const alertBox = document.getElementById('patient-ajax-alert');
        const btn = document.getElementById('btn-save-patient-ajax');

        const name = document.getElementById('new_patient_name').value.trim();
        const code = document.getElementById('new_patient_code').value.trim();
        const dob = document.getElementById('new_patient_dob').value;
        const gender = document.getElementById('new_patient_gender').value;
        const phone = document.getElementById('new_patient_phone').value.trim();
        const address = document.getElementById('new_patient_address').value.trim();

        // Validasi frontend dasar
        if (!name || !dob || !gender) {
            alertBox.style.display = 'block';
            alertBox.innerHTML = `
                <div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 12px 14px; border-radius: 8px; font-size: 13px; font-weight: 500;">
                    <strong>Perhatian:</strong> Nama pasien, tanggal lahir, dan jenis kelamin wajib diisi.
                </div>
            `;
            return;
        }

        btn.disabled = true;
        btn.textContent = 'Menyimpan...';

        try {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const response = await fetch("{{ route('radiografer.patients.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    patient_code: code,
                    name: name,
                    date_of_birth: dob,
                    gender: gender,
                    phone: phone,
                    address: address
                })
            });

            const result = await response.json();

            if (!response.ok) {
                let errText = result.message || 'Gagal menyimpan pasien.';
                if (result.errors) {
                    errText = Object.values(result.errors).flat().join('<br>');
                }
                alertBox.style.display = 'block';
                alertBox.innerHTML = `
                    <div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 12px 14px; border-radius: 8px; font-size: 13px;">
                        ${errText}
                    </div>
                `;
                btn.disabled = false;
                btn.textContent = 'Daftarkan & Pilih Pasien';
                return;
            }

            // Sukses tersimpan
            const newPatient = result.patient;

            // Tambahkan ke dataset
            patientsData.unshift({
                id: newPatient.id,
                name: newPatient.name,
                code: newPatient.patient_code,
                gender: newPatient.gender,
                dob: newPatient.date_of_birth
            });

            // Tampilkan alert sukses
            alertBox.style.display = 'block';
            alertBox.innerHTML = `
                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; padding: 12px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Pasien baru <strong>${newPatient.patient_code} — ${newPatient.name}</strong> berhasil didaftarkan dan langsung terpilih!</span>
                </div>
            `;

            // Switch ke mode existing dan pilih pasien tersebut
            setPatientMode('existing');
            filterPatients('');
            document.getElementById('patient_id').value = newPatient.id;
            onPatientSelected(newPatient.id);

            // Bersihkan form new patient
            document.getElementById('new_patient_name').value = '';
            document.getElementById('new_patient_dob').value = '';
            document.getElementById('new_patient_gender').value = '';
            document.getElementById('new_patient_phone').value = '';
            document.getElementById('new_patient_address').value = '';

        } catch (error) {
            alertBox.style.display = 'block';
            alertBox.innerHTML = `
                <div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 12px 14px; border-radius: 8px; font-size: 13px;">
                    Terjadi kesalahan sistem: ${error.message}
                </div>
            `;
        } finally {
            btn.disabled = false;
            btn.textContent = 'Daftarkan & Pilih Pasien';
        }
    }

    // ── Logika Filter Dokter Radiologi Berdasarkan Jenis Pemeriksaan ──
    function resetRadiologistFilter() {
        manualShowAllRadiologists = true;
        renderRadiologistOptions(radiologistsMaster, null, true);
    }

    function onExamTypeInput(examType) {
        manualShowAllRadiologists = false;
        filterRadiologistsByExamType(examType);
    }

    function filterRadiologistsByExamType(examType) {
        if (manualShowAllRadiologists) return;

        const trimmed = (examType || '').trim().toLowerCase();
        const badge = document.getElementById('radiologist-filter-badge');
        const badgeText = document.getElementById('radiologist-filter-badge-text');
        const btnShowAll = document.getElementById('btn-show-all-rads');

        if (!trimmed) {
            renderRadiologistOptions(radiologistsMaster, null, false);
            badge.style.display = 'none';
            btnShowAll.style.display = 'none';
            return;
        }

        // Cari kategori yang cocok
        const matchedCategoryNames = [];
        categoriesMaster.forEach(cat => {
            const catLower = cat.name.toLowerCase();
            if (trimmed.includes(catLower) || catLower.includes(trimmed)) {
                matchedCategoryNames.push(cat.name);
                return;
            }
            if (cat.rawKeywords) {
                const kws = cat.rawKeywords.split(',').map(k => k.trim().toLowerCase());
                for (let kw of kws) {
                    if (kw && (trimmed.includes(kw) || kw.includes(trimmed))) {
                        matchedCategoryNames.push(cat.name);
                        break;
                    }
                }
            }
        });

        const uniqueCategories = [...new Set(matchedCategoryNames)];

        if (uniqueCategories.length > 0) {
            // Filter dokter yang memiliki setidaknya salah satu kategori yang cocok
            const filteredDocs = radiologistsMaster.filter(doc => {
                return doc.categories.some(c => uniqueCategories.includes(c));
            });

            if (filteredDocs.length > 0) {
                renderRadiologistOptions(filteredDocs, uniqueCategories, false);
                badge.style.display = 'block';
                badge.style.background = '#eff6ff';
                badge.style.borderColor = '#bfdbfe';
                badge.style.color = '#1e40af';
                badgeText.innerHTML = `✓ Menampilkan <strong>${filteredDocs.length} dokter radiologi</strong> yang melayani kategori: <em>${uniqueCategories.join(', ')}</em>.`;
                btnShowAll.style.display = 'inline';
                return;
            }
        }

        // Jika tidak ada kategori yang cocok atau dokter tidak ditemukan
        renderRadiologistOptions(radiologistsMaster, null, false);
        badge.style.display = 'block';
        badge.style.background = '#fefce8';
        badge.style.borderColor = '#fef08a';
        badge.style.color = '#854d0e';
        badgeText.innerHTML = `ℹ️ Jenis pemeriksaan tidak terikat kategori layanan spesifik. Menampilkan seluruh ${radiologistsMaster.length} dokter radiologi.`;
        btnShowAll.style.display = 'none';
    }

    function renderRadiologistOptions(docs, matchedCats, isManualAll) {
        const select = document.getElementById('radiologist_id');
        const currentSelected = select.value;
        const btnShowAll = document.getElementById('btn-show-all-rads');
        const badge = document.getElementById('radiologist-filter-badge');
        const badgeText = document.getElementById('radiologist-filter-badge-text');

        select.innerHTML = '<option value="">— Pilih Dokter Radiologi —</option>';

        docs.forEach(doc => {
            const opt = document.createElement('option');
            opt.value = doc.id;
            const catLabel = doc.categories.length > 0 ? ` (${doc.categories.join(', ')})` : '';
            opt.textContent = doc.name + catLabel;
            if (doc.id == currentSelected) {
                opt.selected = true;
            }
            select.appendChild(opt);
        });

        if (isManualAll) {
            badge.style.display = 'block';
            badge.style.background = '#f8fafc';
            badge.style.borderColor = '#e2e8f0';
            badge.style.color = '#475569';
            badgeText.innerHTML = `Menampilkan seluruh ${docs.length} dokter radiologi (mode manual).`;
            btnShowAll.style.display = 'none';
        }
    }

    // Inisialisasi awal saat load
    document.addEventListener('DOMContentLoaded', () => {
        const initialMode = "{{ old('patient_mode', 'existing') }}";
        setPatientMode(initialMode);

        const currentPatientId = "{{ old('patient_id') }}";
        if (currentPatientId) {
            onPatientSelected(currentPatientId);
        }

        // Inisialisasi filter dokter jika sudah ada input jenis pemeriksaan
        const examTypeInput = document.getElementById('examination_type');
        if (examTypeInput && examTypeInput.value) {
            filterRadiologistsByExamType(examTypeInput.value);
        }

        const currentRadId = "{{ old('radiologist_id') }}";
        if (currentRadId) {
            document.getElementById('radiologist_id').value = currentRadId;
        }
    });
</script>
@endsection
