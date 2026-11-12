@extends('layouts.app')

@section('title', 'Dashboard Dokter Radiologi')

@section('page-title', 'Dashboard Dokter Radiologi')
@section('page-subtitle', 'Ringkasan pekerjaan interpretasi radiologi')

@section('sidebar-nav')
    @include('dokter-radiologi.partials.sidebar')
@endsection

@push('styles')
<style>
    /* ── Dashboard Grid & Layout ── */
    .dashboard-space {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    /* ── 3 KPI Cards Row ── */
    .kpi-row {
        display: grid;
        grid-template-columns: repeat(1, minmax(0, 1fr));
        gap: 1.25rem;
    }
    @media (min-width: 768px) {
        .kpi-row {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }
    .kpi-card-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 0.875rem;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        text-decoration: none;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
        position: relative;
        overflow: hidden;
    }
    .kpi-card-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
    }
    .kpi-card-box::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 5px;
    }
    .kpi-amber::before { background: #f59e0b; }
    .kpi-purple::before { background: #8b5cf6; }
    .kpi-emerald::before { background: #10b981; }

    .kpi-icon-container {
        width: 3.25rem;
        height: 3.25rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .kpi-amber .kpi-icon-container { background: #fffbeb; color: #d97706; }
    .kpi-purple .kpi-icon-container { background: #f5f3ff; color: #7c3aed; }
    .kpi-emerald .kpi-icon-container { background: #ecfdf5; color: #059669; }

    .kpi-header-label {
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        margin-bottom: 0.35rem;
    }
    .kpi-amber .kpi-header-label { color: #b45309; }
    .kpi-purple .kpi-header-label { color: #7c3aed; }
    .kpi-emerald .kpi-header-label { color: #047857; }

    .kpi-number-stat {
        font-size: 2.25rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1;
        margin: 0;
    }
    .kpi-sub-hint {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 0.35rem;
    }

    /* ── Section Container Card ── */
    .dashboard-panel {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 0.875rem;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }
    .dashboard-panel-header {
        padding: 1.125rem 1.25rem;
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
    }
    .dashboard-panel-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0;
    }
    .dashboard-panel-subtitle {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 0.15rem;
        margin-bottom: 0;
    }

    /* ── SLA Header Pills ── */
    .sla-pill-group {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.5rem;
    }
    .sla-pill-item {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.3rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        line-height: 1;
    }
    .sla-pill-dot-circle {
        width: 0.5rem;
        height: 0.5rem;
        border-radius: 9999px;
        display: inline-block;
    }
    .sla-pill-item.pill-aman {
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }
    .sla-pill-item.pill-aman .sla-pill-dot-circle { background: #10b981; }

    .sla-pill-item.pill-warning {
        background: #fffbeb;
        color: #92400e;
        border: 1px solid #fde68a;
    }
    .sla-pill-item.pill-warning .sla-pill-dot-circle { background: #f59e0b; }

    .sla-pill-item.pill-danger {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fca5a5;
    }
    .sla-pill-item.pill-danger .sla-pill-dot-circle { background: #ef4444; }

    /* ── Alert Box for Urgent SLA ── */
    .alert-urgent-box {
        background: #fff1f2;
        border-bottom: 1px solid #fecdd3;
        padding: 0.875rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #9f1239;
        font-size: 0.8125rem;
    }
    .alert-urgent-icon-wrap {
        width: 2rem;
        height: 2rem;
        border-radius: 9999px;
        background: #ffe4e6;
        color: #e11d48;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* ── Safe SLA State ── */
    .safe-state-box {
        padding: 2.25rem 1.5rem;
        text-align: center;
        background: #ffffff;
    }
    .safe-state-icon-wrap {
        width: 3.25rem;
        height: 3.25rem;
        border-radius: 9999px;
        background: #ecfdf5;
        color: #059669;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.75rem auto;
    }

    /* ── SLA Badges in Table Rows ── */
    .sla-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.625rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
        white-space: nowrap;
    }
    .sla-status-badge.badge-sla-aman {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }
    .sla-status-badge.badge-sla-warning {
        background: #fffbeb;
        color: #b45309;
        border: 1px solid #fde68a;
    }
    .sla-status-badge.badge-sla-danger {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fca5a5;
    }
    .sla-status-badge .sla-dot {
        width: 0.375rem;
        height: 0.375rem;
        border-radius: 9999px;
        background: currentColor;
    }
</style>
@endpush

@section('content')
<div class="dashboard-space">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- 1. BARIS KPI UTAMA (3 Kolom Berjajar)                         --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="kpi-row">
        {{-- KPI 1: Menunggu Interpretasi --}}
        <a href="{{ route('dokter-radiologi.worklist.index', ['status' => 'MENUNGGU_INTERPRETASI']) }}"
           class="kpi-card-box kpi-amber">
            <div>
                <div class="kpi-header-label">Menunggu Interpretasi</div>
                <div class="kpi-number-stat">{{ $menungguCount }}</div>
                <div class="kpi-sub-hint">Pemeriksaan antrean baru</div>
            </div>
            <div class="kpi-icon-container">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </a>

        {{-- KPI 2: Draft Interpretasi --}}
        <a href="{{ route('dokter-radiologi.worklist.index', ['status' => 'DRAFT_INTERPRETASI']) }}"
           class="kpi-card-box kpi-purple">
            <div>
                <div class="kpi-header-label">Draft Interpretasi</div>
                <div class="kpi-number-stat">{{ $draftCount }}</div>
                <div class="kpi-sub-hint">Tersimpan belum final</div>
            </div>
            <div class="kpi-icon-container">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
        </a>

        {{-- KPI 3: Selesai Hari Ini --}}
        <a href="{{ route('dokter-radiologi.history.index', ['finalized_date' => now()->toDateString()]) }}"
           class="kpi-card-box kpi-emerald">
            <div>
                <div class="kpi-header-label">Selesai Hari Ini</div>
                <div class="kpi-number-stat">{{ $selesaiHariIniCount }}</div>
                <div class="kpi-sub-hint">Laporan final hari ini</div>
            </div>
            <div class="kpi-icon-container">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </a>
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- 2. SECTION: PEMERIKSAAN MENDEKATI SLA                       --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="dashboard-panel">
        {{-- Header Panel SLA --}}
        <div class="dashboard-panel-header" style="background: #f8fafc;">
            <div>
                <h2 class="dashboard-panel-title">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Pemeriksaan Mendekati SLA
                </h2>
                <p class="dashboard-panel-subtitle">Target penyelesaian laporan maksimal 2 jam (120 menit) setelah pemeriksaan</p>
            </div>

            {{-- 3 SLA Status Pills --}}
            <div class="sla-pill-group">
                <span class="sla-pill-item pill-aman">
                    <span class="sla-pill-dot-circle"></span>
                    Aman (&lt; 90 mnt): <strong class="ml-1">{{ $slaCounts['aman'] }}</strong>
                </span>
                <span class="sla-pill-item pill-warning">
                    <span class="sla-pill-dot-circle"></span>
                    Mendekati SLA (90–119 mnt): <strong class="ml-1">{{ $slaCounts['mendekati'] }}</strong>
                </span>
                <span class="sla-pill-item pill-danger">
                    <span class="sla-pill-dot-circle"></span>
                    Melewati SLA (&ge; 120 mnt): <strong class="ml-1">{{ $slaCounts['melewati'] }}</strong>
                </span>
            </div>
        </div>

        {{-- Konten SLA: Tabel jika ada pemeriksaan >= 90 min, atau Banner Aman jika tidak ada --}}
        @if($slaUrgentExaminations->count() > 0)
            <div class="alert-urgent-box">
                <div class="alert-urgent-icon-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <strong>Perhatian:</strong> Terdapat <strong>{{ $slaUrgentExaminations->count() }}</strong> pemeriksaan yang membutuhkan tindakan segera karena mendekati atau telah melewati batas SLA 2 jam.
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID Pemeriksaan</th>
                            <th>Pasien</th>
                            <th>Jenis Pemeriksaan</th>
                            <th>Tanggal & Jam</th>
                            <th>Durasi Berjalan</th>
                            <th>Indikator SLA</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($slaUrgentExaminations as $exam)
                            @php
                                $sla = $exam->sla_info;
                            @endphp
                            <tr class="hover:bg-rose-50/20">
                                <td>
                                    <span class="font-mono text-xs font-bold text-gray-700">{{ $exam->examination_code }}</span>
                                </td>
                                <td>
                                    <div class="font-semibold text-gray-800">{{ $exam->patient->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $exam->patient->patient_code }}</div>
                                </td>
                                <td class="text-gray-700">{{ $exam->examination_type }}</td>
                                <td>
                                    <div class="text-gray-800 font-medium">{{ $exam->examination_date?->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $exam->examination_time ? \Carbon\Carbon::parse($exam->examination_time)->format('H:i') . ' WIB' : '-' }}</div>
                                </td>
                                <td>
                                    <span class="font-bold text-sm text-gray-900">{{ $sla['duration_text'] }}</span>
                                </td>
                                <td>
                                    <span class="sla-status-badge {{ $sla['color'] }}">
                                        <span class="sla-dot"></span>
                                        {{ $sla['status'] }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('dokter-radiologi.interpretations.create', $exam) }}"
                                        class="btn-primary text-xs py-1.5 px-3">
                                        {{ $exam->status === 'DRAFT_INTERPRETASI' ? 'Lanjutkan Draft' : 'Interpretasi Sekarang' }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            {{-- Kondisi Aman --}}
            <div class="safe-state-box">
                <div class="safe-state-icon-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-gray-800 mb-1">Status SLA Terkendali (Aman)</h3>
                <p class="text-xs text-gray-500 max-w-lg mx-auto">
                    Saat ini tidak ada pemeriksaan yang mendekati (90–119 menit) atau melewati (&ge; 120 menit) batas waktu SLA 2 jam.
                    @if($activeExaminations->count() > 0)
                        Semua {{ $activeExaminations->count() }} antrean pemeriksaan aktif Anda dalam status <strong>Aman (&lt; 90 menit)</strong>.
                    @else
                        Tidak ada antrean pemeriksaan aktif yang sedang menunggu.
                    @endif
                </p>
            </div>
        @endif
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- 3. SECTION: WORKLIST AKTIF DI DASHBOARD                      --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="dashboard-panel">
        <div class="dashboard-panel-header">
            <div>
                <h2 class="dashboard-panel-title">Worklist Aktif</h2>
                <p class="dashboard-panel-subtitle">Maksimal 5 pemeriksaan terbaru yang perlu diinterpretasi</p>
            </div>
            <a href="{{ route('dokter-radiologi.worklist.index') }}" class="btn-secondary text-xs py-1.5 px-3">
                Lihat Semua Worklist
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        @if($worklistPreview->count() > 0)
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID Pemeriksaan</th>
                            <th>Pasien</th>
                            <th>Jenis Pemeriksaan</th>
                            <th>Tanggal/Jam</th>
                            <th>Status</th>
                            <th>Durasi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($worklistPreview as $exam)
                            @php
                                $badge = $exam->statusBadge();
                                $sla = $exam->sla_info;
                            @endphp
                            <tr>
                                <td>
                                    <span class="font-mono text-xs font-bold text-gray-700">{{ $exam->examination_code }}</span>
                                </td>
                                <td>
                                    <div class="font-semibold text-gray-800">{{ $exam->patient->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $exam->patient->patient_code }}</div>
                                </td>
                                <td class="text-gray-700 font-medium">{{ $exam->examination_type }}</td>
                                <td>
                                    <div class="text-gray-800 font-medium">{{ $exam->examination_date?->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $exam->examination_time ? \Carbon\Carbon::parse($exam->examination_time)->format('H:i') . ' WIB' : '-' }}</div>
                                </td>
                                <td>
                                    <span class="status-badge {{ $badge['color'] }}">{{ $badge['label'] }}</span>
                                </td>
                                <td>
                                    <div class="text-xs text-gray-800 font-bold">{{ $exam->duration_text }}</div>
                                    <span class="sla-status-badge {{ $sla['color'] }} text-[10px] py-0.5 px-2 mt-0.5">
                                        <span class="sla-dot"></span>
                                        {{ $sla['status'] }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        <a href="{{ route('dokter-radiologi.examinations.show', $exam) }}"
                                            class="btn-outline-sm text-xs py-1 px-2.5">
                                            Lihat Detail
                                        </a>
                                        @if($exam->status === 'DRAFT_INTERPRETASI')
                                            <a href="{{ route('dokter-radiologi.interpretations.create', $exam) }}"
                                                class="btn-primary text-xs py-1 px-2.5">
                                                Lanjutkan Draft
                                            </a>
                                        @elseif($exam->status === 'MENUNGGU_INTERPRETASI')
                                            <a href="{{ route('dokter-radiologi.interpretations.create', $exam) }}"
                                                class="btn-primary text-xs py-1 px-2.5">
                                                Interpretasi
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state py-8">
                <div class="empty-icon mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <p class="empty-title text-sm">Tidak ada pemeriksaan dalam worklist aktif</p>
                <p class="empty-subtitle text-xs">Semua tugas interpretasi telah difinalisasi.</p>
            </div>
        @endif
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- 4. SECTION: RIWAYAT INTERPRETASI SAYA                       --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="dashboard-panel">
        <div class="dashboard-panel-header">
            <div>
                <h2 class="dashboard-panel-title">Riwayat Interpretasi Saya</h2>
                <p class="dashboard-panel-subtitle">Maksimal 5 laporan final terbaru milik Anda</p>
            </div>
            <a href="{{ route('dokter-radiologi.history.index') }}" class="btn-secondary text-xs py-1.5 px-3">
                Lihat Semua Riwayat
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        @if($historyPreview->count() > 0)
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID Pemeriksaan</th>
                            <th>Pasien</th>
                            <th>Jenis Pemeriksaan</th>
                            <th>Tanggal Pemeriksaan</th>
                            <th>Tanggal Finalisasi</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($historyPreview as $exam)
                            <tr>
                                <td>
                                    <span class="font-mono text-xs font-bold text-gray-700">{{ $exam->examination_code }}</span>
                                </td>
                                <td>
                                    <div class="font-semibold text-gray-800">{{ $exam->patient->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $exam->patient->patient_code }}</div>
                                </td>
                                <td class="text-gray-700 font-medium">{{ $exam->examination_type }}</td>
                                <td>
                                    <div class="text-gray-800 font-medium">{{ $exam->examination_date?->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $exam->examination_time ? \Carbon\Carbon::parse($exam->examination_time)->format('H:i') . ' WIB' : '-' }}</div>
                                </td>
                                <td>
                                    @if($exam->interpretation?->finalized_at)
                                        <div class="text-gray-800 font-semibold">{{ $exam->interpretation->finalized_at->format('d/m/Y') }}</div>
                                        <div class="text-xs text-emerald-600 font-bold">{{ $exam->interpretation->finalized_at->format('H:i') }} WIB</div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="status-badge badge-final">Final</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('dokter-radiologi.examinations.show', $exam) }}"
                                        class="btn-outline-sm text-xs py-1 px-3"
                                        title="Buka laporan final (Read-Only)">
                                        Lihat Laporan
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state py-8">
                <div class="empty-icon mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <p class="empty-title text-sm">Belum ada riwayat laporan final</p>
                <p class="empty-subtitle text-xs">Pemeriksaan yang telah Anda finalisasi akan ditampilkan di sini.</p>
            </div>
        @endif
    </div>

</div>
@endsection
