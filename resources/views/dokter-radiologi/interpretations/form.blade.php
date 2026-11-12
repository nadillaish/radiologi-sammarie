@extends('layouts.app')

@section('title', ($examination->interpretation ? 'Edit' : 'Buat') . ' Interpretasi — ' . $examination->examination_code)

@section('page-title', $examination->interpretation ? 'Edit Interpretasi' : 'Buat Interpretasi')
@section('page-subtitle', $examination->examination_code)

@section('sidebar-nav')
    @include('dokter-radiologi.partials.sidebar')
@endsection

@section('content')
@php $badge = $examination->statusBadge(); @endphp

<div class="space-y-5">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('dokter-radiologi.worklist.index') }}" class="hover:text-blue-600 transition-colors">Worklist</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <a href="{{ route('dokter-radiologi.examinations.show', $examination) }}" class="hover:text-blue-600 transition-colors">{{ $examination->examination_code }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-700 font-medium">{{ $examination->interpretation ? 'Edit Interpretasi' : 'Buat Interpretasi' }}</span>
    </div>

    {{-- Info Ringkas Pemeriksaan --}}
    <div class="detail-status-card">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <span class="font-mono text-base font-bold text-gray-800">{{ $examination->examination_code }}</span>
                    <span class="status-badge {{ $badge['color'] }}">{{ $badge['label'] }}</span>
                </div>
                <p class="text-gray-600 text-sm">{{ $examination->examination_type }}</p>
                <p class="text-xs text-gray-500 mt-1">
                    Pasien: <strong>{{ $examination->patient->name }}</strong>
                    ({{ $examination->patient->patient_code }})
                    · Dokter Pengirim: {{ $examination->referringDoctor->name }}
                </p>
            </div>
        </div>
    </div>

    {{-- Form Interpretasi --}}
    <div class="card">
        <div class="border-b border-gray-100 pb-4 mb-6">
            <h2 class="font-semibold text-gray-800">
                {{ $examination->interpretation ? 'Edit Hasil Interpretasi / Expertise' : 'Isi Hasil Interpretasi / Expertise' }}
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Dokter Radiologi: <strong>{{ Auth::user()->name }}</strong>
                @if($examination->interpretation && $examination->interpretation->status === 'DRAFT')
                    · <span class="text-amber-600">Draft tersimpan pada {{ $examination->interpretation->updated_at->format('d/m/Y H:i') }} WIB</span>
                @endif
            </p>
        </div>

        {{-- Draft form --}}
        <form id="form-draft"
              action="{{ route('dokter-radiologi.interpretations.store-draft', $examination) }}"
              method="POST">
            @csrf
            <div class="form-group">
                <label for="interpretation_text" class="form-label">
                    Hasil Expertise / Interpretasi <span class="text-red-500">*</span>
                </label>
                <textarea
                    id="interpretation_text"
                    name="interpretation_text"
                    rows="14"
                    placeholder="Tuliskan hasil pembacaan dan expertise pemeriksaan radiologi di sini..."
                    class="form-input font-mono text-sm leading-relaxed @error('interpretation_text') form-input-error @enderror"
                    style="resize: vertical;"
                >{{ old('interpretation_text', $examination->interpretation?->interpretation_text) }}</textarea>
                @error('interpretation_text')
                    <p class="form-error-msg">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-400 mt-1">Minimal 10 karakter. Anda dapat menyimpan sebagai draft dan melanjutkan nanti.</p>
            </div>

            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" id="btn-simpan-draft" class="btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    Simpan Draft
                </button>

                <button type="button" id="btn-finalisasi"
                    onclick="confirmFinalize()"
                    class="btn-secondary" style="background:#15803d;color:white;border-color:#15803d;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Finalisasi Laporan
                </button>

                <a href="{{ route('dokter-radiologi.examinations.show', $examination) }}" class="btn-secondary">
                    Kembali
                </a>
            </div>
        </form>

        {{-- Hidden form untuk finalisasi --}}
        <form id="form-finalize"
              action="{{ route('dokter-radiologi.interpretations.finalize', $examination) }}"
              method="POST"
              class="hidden">
            @csrf
            <input type="hidden" name="interpretation_text" id="finalize_text">
        </form>
    </div>
</div>

<script>
function confirmFinalize() {
    const text = document.getElementById('interpretation_text').value.trim();
    if (!text || text.length < 10) {
        alert('Isi interpretasi wajib diisi minimal 10 karakter sebelum finalisasi.');
        return;
    }
    const confirmed = confirm(
        'Apakah Anda yakin ingin memfinalisasi laporan?\n\n' +
        'Setelah difinalisasi, laporan dianggap selesai dan tidak dapat diedit kembali.'
    );
    if (confirmed) {
        document.getElementById('finalize_text').value = text;
        document.getElementById('form-finalize').submit();
    }
}
</script>
@endsection
