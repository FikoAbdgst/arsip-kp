<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Arsip Digital' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 h-screen overflow-hidden">

    <div class="h-full flex">

        {{-- SIDEBAR --}}
        <aside class="w-64 bg-white border-r overflow-y-auto flex-shrink-0">
            <div class="p-6 border-b sticky top-0 bg-white z-10">
                <div class="font-semibold text-lg">ARSIP DIGITAL</div>
                <div class="text-xs text-gray-500">Perbankan</div>
            </div>

            @include('partials.sidebar')
        </aside>

        {{-- MAIN CONTENT --}}
        <div class="flex-1 flex flex-col h-full min-w-0">

            {{-- HEADER --}}
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

            {{-- PAGE CONTENT --}}
            <main class="flex-1 overflow-y-auto p-6 bg-gray-100">
                {{ $slot }}
            </main>
        </div>

    </div>

    {{-- SCRIPT REALTIME (WAJIB ADA DISINI) --}}
    <script>
        /**
         * Fungsi untuk melakukan update realtime pada elemen tertentu.
         * @param {string} containerId - ID dari div yang ingin di-refresh isinya.
         * @param {number} interval - Waktu dalam milidetik (default 3000ms / 3 detik).
         */
        function activateRealtime(containerId, interval = 3000) {
            console.log('Realtime activated for: ' + containerId); // Debugging line

            setInterval(function() {
                // Fetch URL halaman saat ini
                fetch(window.location.href, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        // Parse HTML string menjadi dokumen DOM
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');

                        // Ambil konten baru dari elemen yang ditargetkan
                        const newContent = doc.getElementById(containerId);
                        const currentContent = document.getElementById(containerId);

                        if (newContent && currentContent) {
                            // Bandingkan apakah isinya berubah agar tidak flicker jika sama
                            // Kita trim() untuk mengabaikan spasi kosong
                            if (newContent.innerHTML.trim() !== currentContent.innerHTML.trim()) {
                                currentContent.innerHTML = newContent.innerHTML;
                                console.log('Data updated realtime: ' + containerId);
                            }
                        }
                    })
                    .catch(err => console.error('Gagal mengambil update realtime:', err));
            }, interval);
        }
    </script>
</body>

</html>
