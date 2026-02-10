<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</body>
<script>
    /**
     * Fungsi untuk melakukan update realtime pada elemen tertentu.
     * @param {string} containerId - ID dari div yang ingin di-refresh isinya.
     * @param {number} interval - Waktu dalam milidetik (default 3000ms / 3 detik).
     */
    function activateRealtime(containerId, interval = 3000) {
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
                        if (newContent.innerHTML !== currentContent.innerHTML) {
                            currentContent.innerHTML = newContent.innerHTML;
                            console.log('Data updated realtime: ' + containerId);
                        }
                    }
                })
                .catch(err => console.error('Gagal mengambil update realtime:', err));
        }, interval);
    }
</script>

</html>
