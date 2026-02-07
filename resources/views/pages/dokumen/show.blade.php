<x-app-shell title="Detail Dokumen" header="Detail Dokumen">
    @php
        // Dummy data (nanti diganti dari DB)
        $doc = [
            'no' => $no ?? 'SLP-021',
            'kategori' => 'Slip Setoran',
            'sumber' => 'Teller',
            'tanggal' => '2026-02-04',
            'status' => 'Pending', // Pending | Approved | Rejected
            'lokasi' => ['lemari' => 'A', 'rak' => 2, 'kotak' => 7],
            'uploader' => 'Admin',
            'uploaded_at' => '2026-02-04 09:15',
            'keterangan' => 'Dokumen transaksi teller shift pagi',
            'file' => 'dokumen_2026_02_SLP-021.pdf',
        ];

        $statusBadge = [
            'Approved' => ['bg' => 'bg-green-100', 'text' => 'text-green-700'],
            'Rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-700'],
            'Pending'  => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'],
        ][$doc['status']];
    @endphp

    {{-- Breadcrumb + header info --}}
    <div class="bg-white rounded-xl border p-6">
        <div class="text-sm text-gray-500">
            Dokumen / <span class="text-gray-700 font-medium">{{ $doc['no'] }}</span>
        </div>

        <div class="mt-2 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <div class="text-xl font-semibold">{{ $doc['no'] }}</div>
                <div class="text-sm text-gray-600 mt-1">
                    {{ $doc['kategori'] }} • {{ $doc['sumber'] }} • {{ $doc['tanggal'] }}
                </div>
            </div>

            <div class="flex items-center gap-2">
                <span class="px-3 py-1 text-xs rounded-full {{ $statusBadge['bg'] }} {{ $statusBadge['text'] }}">
                    {{ $doc['status'] }}
                </span>

                <button type="button"
                    class="px-4 py-2 rounded-lg border bg-white hover:bg-gray-50 text-sm">
                    Download File (Dummy)
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        {{-- Preview / File --}}
        <div class="lg:col-span-2 bg-white rounded-xl border p-6">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold">Preview File</h3>
                <span class="text-xs text-gray-500">{{ $doc['file'] }}</span>
            </div>

            <div class="mt-4 h-80 rounded-xl border border-dashed flex items-center justify-center text-sm text-gray-500">
                Placeholder preview (PDF/Image)
            </div>

            <div class="mt-4 text-xs text-gray-500">
                *Nanti bisa dibuat: preview PDF/image + tombol buka tab baru.
            </div>
        </div>

        {{-- Metadata --}}
        <div class="bg-white rounded-xl border p-6">
            <h3 class="text-lg font-semibold mb-4">Metadata</h3>

            <div class="space-y-3 text-sm">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Kategori</span>
                    <span class="font-medium">{{ $doc['kategori'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Sumber</span>
                    <span class="font-medium">{{ $doc['sumber'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Tanggal Dokumen</span>
                    <span class="font-medium">{{ $doc['tanggal'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Uploader</span>
                    <span class="font-medium">{{ $doc['uploader'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Uploaded at</span>
                    <span class="font-medium">{{ $doc['uploaded_at'] }}</span>
                </div>
            </div>

            <div class="mt-5 p-4 rounded-lg bg-gray-50 text-sm text-gray-700">
                <div class="font-medium mb-1">Keterangan</div>
                <div class="text-gray-600">{{ $doc['keterangan'] ?: '-' }}</div>
            </div>
        </div>
    </div>

    {{-- Lokasi fisik + riwayat --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        <div class="bg-white rounded-xl border p-6">
            <h3 class="text-lg font-semibold mb-4">Lokasi Penyimpanan Fisik</h3>

            <div class="grid grid-cols-3 gap-3 text-sm">
                <div class="p-4 rounded-xl bg-gray-50 border">
                    <div class="text-gray-500 text-xs">Lemari</div>
                    <div class="text-lg font-semibold mt-1">{{ $doc['lokasi']['lemari'] }}</div>
                </div>
                <div class="p-4 rounded-xl bg-gray-50 border">
                    <div class="text-gray-500 text-xs">Rak</div>
                    <div class="text-lg font-semibold mt-1">{{ $doc['lokasi']['rak'] }}</div>
                </div>
                <div class="p-4 rounded-xl bg-gray-50 border">
                    <div class="text-gray-500 text-xs">Kotak</div>
                    <div class="text-lg font-semibold mt-1">{{ $doc['lokasi']['kotak'] }}</div>
                </div>
            </div>

            <div class="mt-4 text-sm text-gray-600">
                Format: Lemari {{ $doc['lokasi']['lemari'] }} • Rak {{ $doc['lokasi']['rak'] }} • Kotak {{ $doc['lokasi']['kotak'] }}
            </div>
        </div>

        <div class="bg-white rounded-xl border p-6">
            <h3 class="text-lg font-semibold mb-4">Riwayat</h3>

            <div class="space-y-3 text-sm">
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 rounded-full bg-blue-600 mt-2"></div>
                    <div>
                        <div class="font-medium">Dokumen di-upload</div>
                        <div class="text-gray-600">oleh {{ $doc['uploader'] }} • {{ $doc['uploaded_at'] }}</div>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 rounded-full bg-yellow-500 mt-2"></div>
                    <div>
                        <div class="font-medium">Status Pending</div>
                        <div class="text-gray-600">Menunggu verifikasi Supervisor</div>
                    </div>
                </div>

                <div class="text-xs text-gray-500 mt-2">
                    *Nanti kalau sudah approve/reject, riwayat akan bertambah otomatis.
                </div>
            </div>
        </div>
    </div>

    {{-- Action area (khusus supervisor nanti) --}}
    <div class="bg-white rounded-xl border p-6 mt-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <div class="font-semibold">Aksi Verifikasi</div>
                <div class="text-sm text-gray-600">UI dulu. Nanti tombol ini muncul khusus role Supervisor.</div>
            </div>

            <div class="flex flex-wrap gap-2 justify-end">
                <a href="{{ route('verifikasi.index') }}"
                    class="px-4 py-2 rounded-lg border bg-white hover:bg-gray-50 text-sm">
                    Kembali
                </a>

                <button type="button"
                    class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 text-sm">
                    Approve (Dummy)
                </button>

                <button type="button"
                    class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 text-sm">
                    Reject (Dummy)
                </button>
            </div>
        </div>
    </div>
</x-app-shell>
