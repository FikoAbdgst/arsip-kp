<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Arsip Digital' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

{{-- UBAH: Tambahkan h-screen dan overflow-hidden pada body --}}

<body class="bg-gray-100 h-screen overflow-hidden">

    {{-- UBAH: Container utama menggunakan h-full agar mengisi layar --}}
    <div class="h-full flex">

        {{-- SIDEBAR: Tambahkan overflow-y-auto agar sidebar bisa di-scroll sendiri jika menunya panjang --}}
        <aside class="w-64 bg-white border-r overflow-y-auto flex-shrink-0">
            <div class="p-6 border-b sticky top-0 bg-white z-10">
                <div class="font-semibold text-lg">ARSIP DIGITAL</div>
                <div class="text-xs text-gray-500">Perbankan</div>
            </div>

            @include('partials.sidebar')
        </aside>

        {{-- MAIN CONTENT WRAPPER: Flex Column agar Header tetap di atas --}}
        <div class="flex-1 flex flex-col h-full min-w-0">

            {{-- HEADER: flex-shrink-0 agar tidak mengecil saat discroll --}}
            <header class="bg-white border-b p-6 flex-shrink-0">
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

            {{-- PAGE CONTENT: overflow-y-auto ada di sini agar hanya area ini yang discroll --}}
            <main class="flex-1 overflow-y-auto p-6 bg-gray-100">
                {{ $slot }}
            </main>
        </div>

    </div>
</body>

</html>
