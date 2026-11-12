@extends('layouts.app')

@section('title', 'Detail Pemeriksaan — ' . $examination->examination_code)

@section('page-title', 'Detail Pemeriksaan')
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
            <div class="flex flex-col sm:flex-row gap-2">
                @if(in_array($examination->status, ['MENUNGGU_INTERPRETASI', 'DRAFT_INTERPRETASI']))
                    <a href="{{ route('dokter-radiologi.interpretations.create', $examination) }}"
                        class="btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        {{ $examination->interpretation ? 'Edit Interpretasi' : 'Buat Interpretasi' }}
                    </a>
                @elseif($examination->status === 'FINAL')
                    <div class="flex items-center gap-2 text-green-600 bg-green-50 border border-green-200 rounded-lg px-4 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm font-medium">Laporan Final</span>
                    </div>
                @endif
            </div>
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
                    <dt>Dokter Radiologi</dt>
                    <dd class="font-medium text-blue-700">{{ $examination->radiologist?->name ?? 'Belum Ditugaskan' }}</dd>
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

        @if($examination->latestImaging)
            @php $img = $examination->latestImaging; @endphp
            <div class="imaging-preview-card">
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
                            <a href="{{ route('imaging.show', $img) }}" target="_blank" class="btn-outline-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Lihat File
                            </a>
                        </div>
                    </div>
                    @if(in_array($ext, ['jpg', 'jpeg', 'png']))
                        <div class="imaging-thumbnail">
                            <img src="{{ route('imaging.show', $img) }}" alt="Preview imaging" class="w-24 h-24 object-cover rounded-lg border border-gray-200">
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="imaging-empty">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-gray-500 text-sm">Belum ada file imaging yang diunggah</p>
            </div>
        @endif
    </div>

    {{-- Hasil Interpretasi --}}
    @if($examination->interpretation)
        <div class="card">
            <h2 class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Hasil Interpretasi / Expertise
            </h2>

            @php $interp = $examination->interpretation; @endphp

            @if($interp->status === 'DRAFT')
                <div class="flex items-center gap-2 mb-4 p-3 bg-amber-50 border border-amber-200 rounded-lg text-amber-700 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Interpretasi ini masih berstatus <strong>Draft</strong>. Klik "Edit Interpretasi" untuk melanjutkan atau finalisasi.</span>
                </div>
            @endif

            <dl class="detail-list mb-4">
                <div class="detail-row">
                    <dt>Dokter Radiologi</dt>
                    <dd class="font-medium">{{ $interp->radiologist->name }}</dd>
                </div>
                <div class="detail-row">
                    <dt>Status</dt>
                    <dd>
                        @if($interp->status === 'DRAFT')
                            <span class="status-badge badge-draft">Draft</span>
                        @else
                            <span class="status-badge badge-final">Final</span>
                        @endif
                    </dd>
                </div>
                @if($interp->finalized_at)
                <div class="detail-row">
                    <dt>Difinalisasi</dt>
                    <dd>{{ $interp->finalized_at->format('d/m/Y H:i') }} WIB</dd>
                </div>
                @endif
                <div class="detail-row">
                    <dt>Dibuat</dt>
                    <dd>{{ $interp->created_at->format('d/m/Y H:i') }} WIB</dd>
                </div>
            </dl>

            <div class="bg-slate-50 border border-slate-200 rounded-lg p-4">
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-2">Hasil Expertise</p>
                <div class="text-gray-800 text-sm leading-relaxed whitespace-pre-line">{{ $interp->interpretation_text }}</div>
            </div>
        </div>
    @endif

</div>
@endsection
