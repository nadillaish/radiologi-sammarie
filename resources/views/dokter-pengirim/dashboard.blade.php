@extends('layouts.app')

@section('title', 'Dashboard Dokter Pengirim')
@section('page-title', 'Dashboard Dokter Pengirim')
@section('page-subtitle', 'Pantau status pemeriksaan rujukan')

@section('sidebar-nav')
    <a href="{{ route('dokter-pengirim.dashboard') }}" class="nav-item active">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        Dashboard
    </a>
@endsection

@section('content')
<div class="card text-center py-16">
    <div class="max-w-md mx-auto">
        <div class="w-20 h-20 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </div>
        <h2 class="text-xl font-bold text-gray-800 mb-3">Dashboard Dokter Pengirim</h2>
        <p class="text-gray-500 mb-4">
            Halaman ini akan dikembangkan oleh anggota tim lain untuk fitur <strong>monitoring status pemeriksaan</strong> yang dirujuk.
        </p>
        <div class="bg-teal-50 border border-teal-100 rounded-lg p-4 text-left">
            <p class="text-sm font-medium text-teal-800 mb-2">Fitur yang akan datang:</p>
            <ul class="text-sm text-teal-700 space-y-1">
                <li>✦ Riwayat pemeriksaan yang dirujuk</li>
                <li>✦ Status terkini setiap pemeriksaan</li>
                <li>✦ Melihat hasil laporan radiologi final</li>
            </ul>
        </div>
    </div>
</div>
@endsection
