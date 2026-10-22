@extends('layouts.app')

@section('title', 'Dashboard Dokter Radiologi')
@section('page-title', 'Dashboard Dokter Radiologi')
@section('page-subtitle', 'Worklist dan antrian interpretasi')

@section('sidebar-nav')
    <a href="{{ route('dokter-radiologi.dashboard') }}" class="nav-item active">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        Dashboard
    </a>
@endsection

@section('content')
<div class="card text-center py-16">
    <div class="max-w-md mx-auto">
        <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
            </svg>
        </div>
        <h2 class="text-xl font-bold text-gray-800 mb-3">Dashboard Dokter Radiologi</h2>
        <p class="text-gray-500 mb-4">
            Halaman ini akan dikembangkan oleh anggota tim lain untuk fitur <strong>Worklist Interpretasi</strong> dan penulisan expertise/hasil bacaan.
        </p>
        <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 text-left">
            <p class="text-sm font-medium text-blue-800 mb-2">Fitur yang akan datang:</p>
            <ul class="text-sm text-blue-700 space-y-1">
                <li>✦ Worklist pemeriksaan yang menunggu interpretasi</li>
                <li>✦ Penulisan draft expertise/hasil bacaan</li>
                <li>✦ Finalisasi laporan radiologi</li>
                <li>✦ Riwayat interpretasi</li>
            </ul>
        </div>
    </div>
</div>
@endsection
