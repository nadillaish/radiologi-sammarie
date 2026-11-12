@extends('layouts.app')

@section('title', 'Riwayat Interpretasi Saya')

@section('page-title', 'Riwayat Interpretasi Saya')
@section('page-subtitle', 'Daftar seluruh laporan hasil interpretasi radiologi yang telah difinalisasi')

@section('sidebar-nav')
    @include('dokter-radiologi.partials.sidebar')
@endsection

@push('styles')
<style>
    .filter-grid {
        display: grid;
        grid-template-columns: repeat(1, minmax(0, 1fr));
        gap: 0.75rem;
        align-items: end;
    }
    @media (min-width: 768px) {
        .filter-grid {
            grid-template-columns: 2fr 1.25fr 1.25fr;
        }
    }
</style>
@endpush

@section('content')
<div class="space-y-5">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    {{-- Filter & Search Form --}}
    <div class="card">
        <form id="filter-history-form" action="{{ route('dokter-radiologi.history.index') }}" method="GET" class="filter-grid">
            {{-- Search Pasien / ID --}}
            <div>
                <label for="search" class="form-label">Cari Riwayat</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input id="search" type="text" name="search" value="{{ request('search') }}"
                        placeholder="Nama pasien, kode pasien, atau ID pemeriksaan..."
                        class="form-input pl-10">
                </div>
            </div>

            {{-- Filter Jenis Pemeriksaan --}}
            <div>
                <label for="examination_type" class="form-label">Jenis Pemeriksaan</label>
                <select id="examination_type" name="examination_type" class="form-input">
                    <option value="all">Semua Jenis</option>
                    @foreach($examinationTypes as $type)
                        <option value="{{ $type }}" {{ request('examination_type') === $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Tanggal Finalisasi --}}
            <div>
                <label for="finalized_date" class="form-label">Tanggal Finalisasi</label>
                <div class="flex gap-2">
                    <input id="finalized_date" type="date" name="finalized_date" value="{{ request('finalized_date') }}" class="form-input">
                    <button type="submit" class="btn-primary shrink-0" title="Terapkan Filter">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                    </button>
                    @if(request('search') || (request('examination_type') && request('examination_type') !== 'all') || request('finalized_date'))
                        <a href="{{ route('dokter-radiologi.history.index') }}" class="btn-secondary shrink-0" title="Reset Filter">Reset</a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Tabel Riwayat --}}
    <div class="card p-0">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <div>
                <h2 class="font-semibold text-gray-800">Daftar Laporan Final</h2>
                <p class="text-sm text-gray-500">Menampilkan {{ $examinations->total() }} laporan hasil interpretasi</p>
            </div>
            <div class="text-xs text-gray-400 bg-gray-50 px-3 py-1.5 rounded-md border border-gray-100">
                Mode Read-Only (Laporan Final Terkunci)
            </div>
        </div>

        @if($examinations->count() > 0)
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
                        @foreach($examinations as $exam)
                            <tr>
                                <td>
                                    <span class="font-mono text-xs text-gray-600 font-semibold">{{ $exam->examination_code }}</span>
                                </td>
                                <td>
                                    <div class="font-medium text-gray-800">{{ $exam->patient->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $exam->patient->patient_code }}</div>
                                </td>
                                <td class="text-gray-700 font-medium">{{ $exam->examination_type }}</td>
                                <td>
                                    <div class="text-gray-700">{{ $exam->examination_date?->format('d/m/Y') ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ $exam->examination_time ? \Carbon\Carbon::parse($exam->examination_time)->format('H:i') . ' WIB' : '-' }}</div>
                                </td>
                                <td>
                                    @if($exam->interpretation?->finalized_at)
                                        <div class="text-gray-800 font-medium">{{ $exam->interpretation->finalized_at->format('d/m/Y') }}</div>
                                        <div class="text-xs text-emerald-600 font-semibold">{{ $exam->interpretation->finalized_at->format('H:i') }} WIB</div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="status-badge badge-final">Final</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('dokter-radiologi.examinations.show', $exam) }}"
                                        class="btn-outline-sm"
                                        title="Buka laporan final dalam mode read-only">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Lihat Laporan
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($examinations->hasPages())
                <div class="px-5 py-4 border-t border-gray-100">
                    {{ $examinations->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <p class="empty-title">Belum ada riwayat laporan final</p>
                <p class="empty-subtitle">
                    @if(request('search') || request('examination_type') || request('finalized_date'))
                        Tidak ditemukan laporan final yang cocok dengan kriteria filter pencarian Anda.
                    @else
                        Laporan pemeriksaan yang telah Anda finalisasi akan otomatis tercatat di sini.
                    @endif
                </p>
            </div>
        @endif
    </div>

</div>
@endsection

