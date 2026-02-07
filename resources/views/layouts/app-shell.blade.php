<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Arsip Digital' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">
<div class="min-h-screen flex">

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-white border-r">
        <div class="p-6 border-b">
            <div class="font-semibold text-lg">ARSIP DIGITAL</div>
            <div class="text-xs text-gray-500">Perbankan</div>
        </div>

        @include('partials.sidebar')
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="flex-1">
        {{-- HEADER --}}
        <header class="bg-white border-b p-6">
            <div class="flex items-center justify-between gap-4">
                <div class="text-xl font-semibold">{{ $header ?? '-' }}</div>

                <div class="flex items-center gap-3">
                    <div class="text-right leading-tight">
                        <div class="text-sm font-medium text-gray-800">
                            {{ auth()->user()->name ?? 'User' }}
                        </div>
                        <div class="text-xs text-gray-500">
                            Role: {{ auth()->user()->role ?? '-' }}
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="px-3 py-2 rounded-lg text-sm border bg-white hover:bg-red-50 hover:text-red-600">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        {{-- PAGE CONTENT --}}
        <main class="p-6">
            {{ $slot }}
        </main>
    </div>

</div>
</body>
</html>
