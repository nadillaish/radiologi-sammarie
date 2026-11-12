{{-- Menu Sidebar Dokter Radiologi --}}
<a href="{{ route('dokter-radiologi.dashboard') }}"
   class="nav-item {{ request()->routeIs('dokter-radiologi.dashboard') ? 'active' : '' }}">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
    </svg>
    Dashboard
</a>

<a href="{{ route('dokter-radiologi.worklist.index') }}"
   class="nav-item {{ (request()->routeIs('dokter-radiologi.worklist.*') || (request()->routeIs('dokter-radiologi.examinations.show') && isset($examination) && $examination->status !== 'FINAL') || request()->routeIs('dokter-radiologi.interpretations.*')) ? 'active' : '' }}">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
    </svg>
    Worklist
</a>

<a href="{{ route('dokter-radiologi.history.index') }}"
   class="nav-item {{ (request()->routeIs('dokter-radiologi.history.*') || (request()->routeIs('dokter-radiologi.examinations.show') && isset($examination) && $examination->status === 'FINAL')) ? 'active' : '' }}">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    Riwayat Interpretasi
</a>

