<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Radiologi RSIA SamMarie') — RSIA SamMarie Basra</title>
    <meta name="description" content="Sistem Informasi Workflow Pelaporan Hasil Pemeriksaan Radiologi RSIA SamMarie Basra">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 min-h-screen font-sans">

    {{-- Sidebar --}}
    <div class="flex min-h-screen">
        <aside class="sidebar-nav w-64 min-h-screen flex flex-col">
        {{-- Logo/Brand --}}
        <div class="sidebar-brand p-5 border-b border-white/10">
            <div class="flex items-center gap-3">

                {{-- Logo RSIA SamMarie --}}
                <div class="brand-icon"
                    style="background: white;
                        width: 48px;
                        height: 48px;
                        padding: 5px;
                        border-radius: 10px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        flex-shrink: 0;">

                    <img
                        src="{{ asset('images/logo-sammarie.png') }}"
                        alt="Logo RSIA SamMarie Basra"
                        style="width: 100%;
                            height: 100%;
                            object-fit: contain;">
            </div>

            {{-- Nama Sistem --}}
            <div>
                <div class="text-white font-bold text-sm leading-tight">
                    RSIA SamMarie
            </div>
            <div class="text-blue-200 text-xs">
                Sistem Radiologi
            </div>
        </div>

    </div>
</div>

            {{-- User Info --}}
            <div class="px-4 py-4 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-white text-sm font-medium truncate">{{ Auth::user()->name }}</div>
                        <div class="sidebar-role-badge">
                            @if(Auth::user()->role === 'radiografer') Radiografer
                            @elseif(Auth::user()->role === 'dokter_radiologi') Dokter Radiologi
                            @else Dokter Pengirim
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-3 py-4 space-y-1">
                @yield('sidebar-nav')
            </nav>

            {{-- Logout --}}
            <div class="p-4 border-t border-white/10">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="sidebar-logout-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col min-w-0">
            {{-- Top Bar --}}
            <header class="top-header px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-gray-800 font-semibold text-lg">@yield('page-title', 'Dashboard')</h1>
                        <p class="text-gray-500 text-sm">@yield('page-subtitle', '')</p>
                    </div>
                    <div class="text-gray-500 text-sm">
                        {{ now()->isoFormat('dddd, D MMMM YYYY') }}
                    </div>
                </div>
            </header>

            {{-- Flash Messages --}}
            <div class="px-6 pt-2">
                @if(session('success'))
                    <div class="alert-success">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert-error">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
            </div>

            {{-- Page Content --}}
            <main class="flex-1 p-6">
                @yield('content')
            </main>

            {{-- Footer --}}
            <footer class="px-6 py-3 border-t border-gray-200 text-center text-xs text-gray-400">
                © {{ date('Y') }} RSIA SamMarie Basra — Sistem Informasi Radiologi v1.0
            </footer>
        </div>
    </div>

</body>
</html>
