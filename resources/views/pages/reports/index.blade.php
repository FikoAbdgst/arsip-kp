<x-app-shell title="Reports" header="Reports">
    {{-- Header --}}
    <div class="bg-white rounded-xl border p-6">
        <div class="text-sm text-gray-500">Laporan</div>
        <div class="text-xl font-semibold">Laporan Arsip Dokumen</div>
        <div class="text-sm text-gray-600 mt-1">
            Rekap dokumen berdasarkan periode, kategori, sumber, dan status. (UI dulu)
        </div>
    </div>

    {{-- Filter Panel --}}
    <div class="bg-white rounded-xl border p-6 mt-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Filter Laporan</h3>
            <div class="text-xs text-gray-500">*Nanti filter ini akan mempengaruhi tabel & ringkasan</div>
        </div>

        <form class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Periode --}}
            <div>
                <label class="block text-sm text-gray-600 mb-1">Periode</label>
                <select class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option>Harian</option>
                    <option>Bulanan</option>
                    <option>Tahunan</option>
                    <option>Custom (Range)</option>
                </select>
            </div>

            {{-- Dari --}}
            <div>
                <label class="block text-sm text-gray-600 mb-1">Dari Tanggal</label>
                <input type="date" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
            </div>

            {{-- Sampai --}}
            <div>
                <label class="block text-sm text-gray-600 mb-1">Sampai Tanggal</label>
                <input type="date" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
            </div>

            {{-- Sumber --}}
            <div>
                <label class="block text-sm text-gray-600 mb-1">Sumber Dokumen</label>
                <select class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option>Semua</option>
                    <option>Teller</option>
                    <option>Customer Service</option>
                </select>
            </div>

            {{-- Kategori --}}
            <div>
                <label class="block text-sm text-gray-600 mb-1">Kategori Dokumen</label>
                <select class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option>Semua</option>
                    <option>Slip Setoran</option>
                    <option>Bukti Transfer</option>
                    <option>Bukti Penarikan</option>
                    <option>Form Rekening</option>
                    <option>Keluhan Nasabah</option>
                </select>
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-sm text-gray-600 mb-1">Status</label>
                <select class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option>Semua</option>
                    <option>Approved</option>
                    <option>Pending</option>
                    <option>Rejected</option>
                </select>
            </div>

            {{-- Search No Dokumen --}}
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-600 mb-1">Search No Dokumen</label>
                <input type="text" placeholder="contoh: SLP-021 / TRF-019"
                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
            </div>

            {{-- Buttons --}}
            <div class="lg:col-span-4 flex flex-col sm:flex-row gap-2 sm:justify-end mt-2">
                <button type="button"
                    class="px-4 py-2 rounded-lg border bg-white hover:bg-gray-50 text-sm">
                    Reset
                </button>
                <button type="button"
                    class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm">
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    {{-- Ringkasan Report --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
        <div class="bg-white rounded-xl border p-4">
            <div class="text-sm text-gray-500">Total Dokumen</div>
            <div class="text-2xl font-semibold mt-1">128</div>
            <div class="text-xs text-gray-500 mt-1">Hasil sesuai filter</div>
        </div>

        <div class="bg-white rounded-xl border p-4">
            <div class="text-sm text-gray-500">Approved</div>
            <div class="text-2xl font-semibold mt-1">110</div>
            <div class="text-xs text-gray-500 mt-1">Sudah diverifikasi</div>
        </div>

        <div class="bg-white rounded-xl border p-4">
            <div class="text-sm text-gray-500">Pending</div>
            <div class="text-2xl font-semibold mt-1">12</div>
            <div class="text-xs text-gray-500 mt-1">Menunggu supervisor</div>
        </div>

        <div class="bg-white rounded-xl border p-4">
            <div class="text-sm text-gray-500">Rejected</div>
            <div class="text-2xl font-semibold mt-1">6</div>
            <div class="text-xs text-gray-500 mt-1">Perlu upload ulang</div>
        </div>
    </div>

    {{-- Tabel Laporan + Export --}}
    <div class="bg-white rounded-xl border p-6 mt-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <h3 class="text-lg font-semibold">Hasil Laporan</h3>

            <div class="flex gap-2 justify-end">
                <button type="button"
                    class="px-3 py-2 rounded-lg border bg-white hover:bg-gray-50 text-sm">
                    Export PDF
                </button>
                <button type="button"
                    class="px-3 py-2 rounded-lg border bg-white hover:bg-gray-50 text-sm">
                    Export Excel
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b text-gray-500">
                        <th class="text-left py-3 pr-4">No Dokumen</th>
                        <th class="text-left py-3 pr-4">Kategori</th>
                        <th class="text-left py-3 pr-4">Sumber</th>
                        <th class="text-left py-3 pr-4">Tanggal</th>
                        <th class="text-left py-3 pr-4">Lokasi Fisik</th>
                        <th class="text-left py-3 pr-4">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ([
                        ['no' => 'SLP-021', 'kategori' => 'Slip Setoran', 'sumber' => 'Teller', 'tanggal' => '2026-02-04', 'lokasi' => 'Lemari A • Rak 2 • Kotak 7', 'status' => 'Approved'],
                        ['no' => 'TRF-019', 'kategori' => 'Bukti Transfer', 'sumber' => 'Teller', 'tanggal' => '2026-02-04', 'lokasi' => 'Lemari A • Rak 2 • Kotak 7', 'status' => 'Pending'],
                        ['no' => 'KEL-007', 'kategori' => 'Keluhan Nasabah', 'sumber' => 'CS', 'tanggal' => '2026-02-04', 'lokasi' => 'Lemari B • Rak 1 • Kotak 3', 'status' => 'Rejected'],
                        ['no' => 'FRM-002', 'kategori' => 'Form Rekening', 'sumber' => 'CS', 'tanggal' => '2026-02-03', 'lokasi' => 'Lemari B • Rak 1 • Kotak 3', 'status' => 'Approved'],
                    ] as $doc)
                    <tr class="border-b">
                        <td class="py-3 pr-4 font-medium">{{ $doc['no'] }}</td>
                        <td class="py-3 pr-4">{{ $doc['kategori'] }}</td>
                        <td class="py-3 pr-4">{{ $doc['sumber'] }}</td>
                        <td class="py-3 pr-4">{{ $doc['tanggal'] }}</td>
                        <td class="py-3 pr-4 text-gray-600">{{ $doc['lokasi'] }}</td>
                        <td class="py-3 pr-4">
                            @if($doc['status'] === 'Approved')
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                    Approved
                                </span>
                            @elseif($doc['status'] === 'Rejected')
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">
                                    Rejected
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">
                                    Pending
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Footer note --}}
        <div class="text-xs text-gray-500 mt-4">
            *Data masih dummy. Export PDF/Excel nanti akan mengambil hasil sesuai filter.
        </div>
    </div>
</x-app-shell>
