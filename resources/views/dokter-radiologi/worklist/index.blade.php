@extends('layouts.app')

@section('title', 'Worklist Pemeriksaan')

@section('page-title', 'Worklist Pemeriksaan')
@section('page-subtitle', 'Daftar pemeriksaan yang menunggu interpretasi radiologi')

@section('sidebar-nav')
    @include('dokter-radiologi.partials.sidebar')
@endsection

@section('content')
<div class="space-y-5">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    {{-- Filter & Search --}}
    <div class="card">
        <form id="filter-form" action="{{ route('dokter-radiologi.worklist.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-end">
            <div class="flex-1">
                <label for="search" class="form-label">Cari Pemeriksaan</label>
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
            <div class="w-full sm:w-52">
                <label for="status" class="form-label">Filter Status</label>
                <select id="status" name="status" class="form-input">
                    <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="MENUNGGU_INTERPRETASI" {{ request('status') === 'MENUNGGU_INTERPRETASI' ? 'selected' : '' }}>Menunggu Interpretasi</option>
                    <option value="DRAFT_INTERPRETASI" {{ request('status') === 'DRAFT_INTERPRETASI' ? 'selected' : '' }}>Draft Interpretasi</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter
                </button>
                @if(request('search') || (request('status') && request('status') !== 'all'))
                    <a href="{{ route('dokter-radiologi.worklist.index') }}" class="btn-secondary">Reset</a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="card p-0">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <div>
                <h2 class="font-semibold text-gray-800">Worklist Interpretasi</h2>
                <p class="text-sm text-gray-500">Total: {{ $examinations->total() }} pemeriksaan</p>
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
                            <th>Radiografer</th>
                            <th>Dokter Pengirim</th>
                            <th>Tanggal/Jam</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($examinations as $exam)
                            @php $badge = $exam->statusBadge(); @endphp
                            <tr>
                                <td>
                                    <span class="font-mono text-xs text-gray-600">{{ $exam->examination_code }}</span>
                                </td>
                                <td>
                                    <div class="font-medium text-gray-800">{{ $exam->patient->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $exam->patient->patient_code }}</div>
                                </td>
                                <td class="text-gray-700">{{ $exam->examination_type }}</td>
                                <td class="text-gray-700">{{ $exam->radiographer->name }}</td>
                                <td class="text-gray-700">{{ $exam->referringDoctor->name }}</td>
                                <td>
                                    <div class="text-gray-700">{{ $exam->examination_date->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($exam->examination_time)->format('H:i') }} WIB</div>
                                </td>
                                <td>
                                    <span class="status-badge {{ $badge['color'] }}">{{ $badge['label'] }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('dokter-radiologi.examinations.show', $exam) }}"
                                        class="btn-outline-sm">
                                        Detail
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <p class="empty-title">Tidak ada pemeriksaan dalam worklist</p>
                <p class="empty-subtitle">
                    @if(request('search') || request('status'))
                        Tidak ada hasil yang cocok dengan pencarian Anda.
                    @else
                        Belum ada pemeriksaan yang menunggu interpretasi.
                    @endif
                </p>
            </div>
        @endif
    </div>

</div>
@endsection
