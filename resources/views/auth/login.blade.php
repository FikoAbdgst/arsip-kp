<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Sistem Arsip Digital</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-100">
    <div class="min-h-screen flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-4xl grid grid-cols-1 md:grid-cols-2 bg-white rounded-2xl shadow-lg overflow-hidden">

            {{-- Kiri: Branding --}}
            <div class="hidden md:flex flex-col justify-between p-10 bg-[#004B87] text-white">
                <div>
                    <div class="text-sm opacity-80">Bank BRI</div>
                    <div class="text-3xl font-semibold mt-2 leading-tight">
                        Sistem Arsip Digital
                    </div>
                    <div class="text-sm opacity-90 mt-4 leading-relaxed">
                        Kelola arsip digital + catat lokasi fisik untuk kebutuhan audit & compliance.
                    </div>

                    <div class="mt-6 text-xs opacity-80 leading-relaxed">
                        • Upload dokumen Teller / CS<br>
                        • Tracking lokasi fisik (Lemari → Rak → Kotak)<br>
                        • Verifikasi Supervisor + Audit Trail
                    </div>
                </div>

                <div class="text-xs opacity-80">
                    © {{ date('Y') }} BRI • Internal Use
                </div>
            </div>

            {{-- Kanan: Form --}}
            <div class="p-8 md:p-10">
                <div class="md:hidden mb-6">
                    <div class="text-sm text-gray-500">Bank BRI</div>
                    <div class="text-xl font-semibold">Sistem Arsip Digital</div>
                </div>

                <h2 class="text-2xl font-semibold text-gray-900">Login</h2>
                <p class="text-sm text-gray-500 mt-1">
                    Masuk menggunakan akun Admin atau Supervisor.
                </p>

                {{-- Status session (misal setelah reset password) --}}
                @if (session('status'))
                    <div class="mt-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="text-sm font-medium text-gray-700">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}"
                            class="mt-1 w-full rounded-lg border-gray-300 focus:border-[#0066B2] focus:ring-[#0066B2]"
                            required autofocus autocomplete="username">
                        @error('email')
                            <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <div class="flex items-center justify-between">
                            <label for="password" class="text-sm font-medium text-gray-700">Password</label>

                            @if (Route::has('password.request'))
                                <a class="text-sm text-[#0066B2] hover:underline"
                                href="{{ route('password.request') }}">
                                    Lupa password?
                                </a>
                            @endif
                        </div>

                        <input id="password" name="password" type="password"
                            class="mt-1 w-full rounded-lg border-gray-300 focus:border-[#0066B2] focus:ring-[#0066B2]"
                            required autocomplete="current-password">

                        @error('password')
                            <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Remember --}}
                    <div class="flex items-center justify-between">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="remember"
                                class="rounded border-gray-300 text-[#0066B2] focus:ring-[#0066B2]">
                            <span class="ms-2 text-sm text-gray-600">Ingat saya</span>
                        </label>
                    </div>

                    {{-- Button --}}
                    <button type="submit"
                            class="w-full inline-flex justify-center items-center px-4 py-2 rounded-lg bg-[#004B87] text-white text-sm font-medium hover:bg-[#003b6a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0066B2]">
                        Masuk
                    </button>

                    {{-- Demo info --}}
                    <div class="pt-4 border-t text-xs text-gray-500">
                        <div class="font-medium text-gray-700 mb-2">Akun demo:</div>
                        <div>Admin: <span class="font-mono">admin@bri.local</span></div>
                        <div>Supervisor: <span class="font-mono">supervisor@bri.local</span></div>
                        <div class="mt-1">Password: <span class="font-mono">password</span></div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</body>
</html>
