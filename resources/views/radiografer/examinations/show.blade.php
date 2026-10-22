@extends('layouts.app')

@section('title', 'Detail Pemeriksaan — ' . $examination->examination_code)

@section('page-title', 'Detail Pemeriksaan')
@section('page-subtitle', $examination->examination_code)

@section('sidebar-nav')
    <a href="{{ route('radiografer.examinations.index') }}" class="nav-item">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        Pemeriksaan
    </a>
@endsection

@section('content')
@php $badge = $examination->statusBadge(); @endphp

<div class="space-y-5">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('radiografer.examinations.index') }}" class="hover:text-blue-600 transition-colors">Pemeriksaan</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-700 font-medium">{{ $examination->examination_code }}</span>
    </div>

    {{-- Status Header Card --}}
    <div class="detail-status-card">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="font-mono text-lg font-bold text-gray-800">{{ $examination->examination_code }}</span>
                    <span class="status-badge {{ $badge['color'] }} text-sm">{{ $badge['label'] }}</span>
                </div>
                <p class="text-gray-600">{{ $examination->examination_type }}</p>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $examination->examination_date->format('d F Y') }} · {{ \Carbon\Carbon::parse($examination->examination_time)->format('H:i') }} WIB
                </p>
            </div>
            @if($examination->status === 'TERDAFTAR' && $examination->latestImaging)
                <form action="{{ route('radiografer.examinations.send-interpretation', $examination) }}" method="POST"
                    onsubmit="return confirm('Yakin ingin mengirim pemeriksaan ini untuk interpretasi? Status akan berubah menjadi Menunggu Interpretasi dan tidak dapat diubah kembali.')">
                    @csrf
                    <button type="submit" id="btn-kirim-interpretasi" class="btn-send-interpretation">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        Kirim untuk Interpretasi
                    </button>
                </form>
            @elseif($examination->status !== 'TERDAFTAR')
                <div class="flex items-center gap-2 text-green-600 bg-green-50 border border-green-200 rounded-lg px-4 py-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm font-medium">Sudah dikirim ke dokter radiologi</span>
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- Info Pemeriksaan --}}
        <div class="card">
            <h2 class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Informasi Pemeriksaan
            </h2>
            <dl class="detail-list">
                <div class="detail-row">
                    <dt>ID Pemeriksaan</dt>
                    <dd class="font-mono text-sm">{{ $examination->examination_code }}</dd>
                </div>
                <div class="detail-row">
                    <dt>Jenis Pemeriksaan</dt>
                    <dd>{{ $examination->examination_type }}</dd>
                </div>
                <div class="detail-row">
                    <dt>Tanggal</dt>
                    <dd>{{ $examination->examination_date->format('d F Y') }}</dd>
                </div>
                <div class="detail-row">
                    <dt>Jam</dt>
                    <dd>{{ \Carbon\Carbon::parse($examination->examination_time)->format('H:i') }} WIB</dd>
                </div>
                <div class="detail-row">
                    <dt>Status</dt>
                    <dd><span class="status-badge {{ $badge['color'] }}">{{ $badge['label'] }}</span></dd>
                </div>
            </dl>
        </div>

        {{-- Info Pasien & Petugas --}}
        <div class="card">
            <h2 class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Pasien & Petugas
            </h2>
            <dl class="detail-list">
                <div class="detail-row">
                    <dt>Kode Pasien</dt>
                    <dd class="font-mono text-sm">{{ $examination->patient->patient_code }}</dd>
                </div>
                <div class="detail-row">
                    <dt>Nama Pasien</dt>
                    <dd class="font-medium">{{ $examination->patient->name }}</dd>
                </div>
                <div class="detail-row">
                    <dt>Dokter Pengirim</dt>
                    <dd>{{ $examination->referringDoctor->name }}</dd>
                </div>
                <div class="detail-row">
                    <dt>Radiografer</dt>
                    <dd>{{ $examination->radiographer->name }}</dd>
                </div>
                <div class="detail-row">
                    <dt>Terdaftar pada</dt>
                    <dd>{{ $examination->created_at->format('d/m/Y H:i') }} WIB</dd>
                </div>
            </dl>
        </div>
    </div>

    {{-- Hasil Imaging --}}
    <div class="card">
        <h2 class="section-title">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Hasil Imaging
        </h2>

        {{-- File yang sudah ada --}}
        @if($examination->latestImaging)
            @php $img = $examination->latestImaging; @endphp
            <div class="imaging-preview-card mb-5">
                <div class="flex items-start gap-4">
                    <div class="imaging-file-icon">
                        @php $ext = strtolower(pathinfo($img->original_filename, PATHINFO_EXTENSION)); @endphp
                        @if(in_array($ext, ['jpg', 'jpeg', 'png']))
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-800">{{ $img->original_filename }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            Diunggah oleh {{ $img->uploader->name }} · {{ $img->created_at->diffForHumans() }}
                        </p>
                        <div class="flex items-center gap-2 mt-3">
                            <a href="{{ asset('storage/' . $img->file_path) }}" target="_blank" class="btn-outline-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Lihat File
                            </a>
                        </div>
                    </div>
                    {{-- Preview gambar jika image --}}
                    @if(in_array($ext, ['jpg', 'jpeg', 'png']))
                        <div class="imaging-thumbnail">
                            <img src="{{ asset('storage/' . $img->file_path) }}" alt="Preview imaging" class="w-24 h-24 object-cover rounded-lg border border-gray-200">
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="imaging-empty mb-5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-gray-500 text-sm">Belum ada file imaging yang diunggah</p>
            </div>
        @endif

        {{-- Form Upload (hanya jika status TERDAFTAR) --}}
        @if($examination->status === 'TERDAFTAR')
            <div class="upload-section">
                <h3 class="text-sm font-medium text-gray-700 mb-3">
                    {{ $examination->latestImaging ? 'Ganti File Imaging' : 'Upload Hasil Imaging' }}
                </h3>
                <form id="form-upload-imaging" action="{{ route('radiografer.examinations.imaging.store', $examination) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="upload-dropzone" id="upload-zone">
                        <input type="file" name="imaging_file" id="imaging_file" class="hidden" accept=".jpg,.jpeg,.png,.pdf"
                            onchange="handleFileSelect(this)">
                        <label for="imaging_file" class="cursor-pointer block">
                            <div class="upload-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                            </div>
                            <p class="upload-text">Klik untuk pilih file atau drag & drop</p>
                            <p class="upload-hint">JPG, JPEG, PNG, PDF — Maksimal 10 MB</p>
                        </label>
                        <div id="file-preview" class="hidden mt-3 text-sm text-blue-600 font-medium"></div>
                    </div>

                    @error('imaging_file')
                        <p class="form-error-msg mt-2">{{ $message }}</p>
                    @enderror

                    <div class="flex gap-3 mt-3">
                        <button type="submit" id="btn-upload-imaging" class="btn-primary" disabled>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            Unggah File
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="upload-disabled-notice">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Upload imaging tidak tersedia karena pemeriksaan sudah dikirim ke dokter radiologi.</span>
            </div>
        @endif
    </div>

</div>

<script>
function handleFileSelect(input) {
    const preview = document.getElementById('file-preview');
    const uploadBtn = document.getElementById('btn-upload-imaging');
    if (input.files && input.files[0]) {
        preview.textContent = '📎 ' + input.files[0].name;
        preview.classList.remove('hidden');
        if (uploadBtn) uploadBtn.removeAttribute('disabled');
    }
}
</script>
@endsection
