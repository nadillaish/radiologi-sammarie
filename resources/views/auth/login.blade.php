<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Sistem Radiologi RSIA SamMarie Basra</title>
    <meta name="description" content="Login ke Sistem Informasi Radiologi RSIA SamMarie Basra">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="login-page min-h-screen flex items-center justify-center p-4">

    <div class="login-card">
    {{-- Logo & Header --}}
    <div class="login-header text-center mb-8">

        <div class="flex justify-center mb-5">
            <img
                src="{{ asset('images/logo-sammarie.png') }}"
                alt="Logo RSIA SamMarie Basra"
                style="
                    width: 90px;
                    height: 90px;
                    object-fit: contain;
                    background: transparent;
                    border: none;
                    box-shadow: none;
                "
            >
        </div>

    <h1 class="text-2xl font-bold text-gray-800 mt-4">
        RSIA SamMarie Basra
    </h1>

    <p class="text-gray-500 text-sm mt-1">
        Sistem Informasi Radiologi
    </p>

    </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert-success mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Login Form --}}
        <form id="login-form" action="{{ route('login.post') }}" method="POST" class="space-y-5">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Masukkan email Anda"
                    class="form-input @error('email') form-input-error @enderror"
                    autocomplete="email"
                    autofocus
                >
                @error('email')
                    <p class="form-error-msg">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    placeholder="Masukkan password Anda"
                    class="form-input @error('password') form-input-error @enderror"
                    autocomplete="current-password"
                >
                @error('password')
                    <p class="form-error-msg">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input id="remember" type="checkbox" name="remember" class="w-4 h-4 text-blue-600 rounded border-gray-300">
                    <span class="text-sm text-gray-600">Ingat saya</span>
                </label>
            </div>

            <button type="submit" id="login-btn" class="btn-primary w-full">
                Masuk ke Sistem
            </button>
        </form>

        {{-- Footer --}}
        <p class="text-center text-xs text-gray-400 mt-6">
            © {{ date('Y') }} RSIA SamMarie Basra — v1.0
        </p>
    </div>

</body>
</html>
