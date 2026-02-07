<x-app-shell title="Dashboard" header="Dashboard">
    {{-- Ringkasan --}}
    <div class="bg-white rounded-xl border p-6">
        <div class="text-sm text-gray-500">Ringkasan</div>
        <div class="text-xl font-semibold">Dashboard Arsip Digital</div>
        <div class="text-sm text-gray-600 mt-1">
            Monitoring dokumen arsip (digital + lokasi fisik).
        </div>
    </div>

    {{-- 4 kartu statistik --}}
    @php
        // sementara angka dummy dulu (nanti ganti dari database)
        $cards = [
            ['label' => 'Total Dokumen', 'value' => 128, 'desc' => 'Semua dokumen tersimpan'],
            ['label' => 'Approved', 'value' => 110, 'desc' => 'Sudah diverifikasi'],
            ['label' => 'Rejected', 'value' => 6, 'desc' => 'Perlu upload ulang'],
            ['label' => 'Upload Hari Ini', 'value' => 12, 'desc' => now()->format('d M Y')],
        ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
        @foreach($cards as $c)
            <div class="bg-white rounded-xl border p-4">
                <div class="text-sm text-gray-500">{{ $c['label'] }}</div>
                <div class="text-2xl font-semibold mt-1">{{ $c['value'] }}</div>
                <div class="text-xs text-gray-500 mt-1">{{ $c['desc'] }}</div>
            </div>
        @endforeach
    </div>

    {{-- Dokumen Terbaru --}}
    <div class="bg-white rounded-xl border p-6 mt-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Dokumen Terbaru</h3>
            <a href="#" class="text-sm text-blue-600 hover:underline">
                Lihat semua
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b text-gray-500">
                        <th class="text-left py-3 pr-4">No Dokumen</th>
                        <th class="text-left py-3 pr-4">Kategori</th>
                        <th class="text-left py-3 pr-4">Sumber</th>
                        <th class="text-left py-3 pr-4">Tanggal</th>
                        <th class="text-left py-3 pr-4">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ([
                        ['no' => 'SLP-021', 'kategori' => 'Slip Setoran', 'sumber' => 'Teller', 'tanggal' => '2026-02-04', 'status' => 'Approved'],
                        ['no' => 'TRF-019', 'kategori' => 'Bukti Transfer', 'sumber' => 'Teller', 'tanggal' => '2026-02-04', 'status' => 'Pending'],
                        ['no' => 'PNR-010', 'kategori' => 'Penarikan', 'sumber' => 'CS', 'tanggal' => '2026-02-03', 'status' => 'Rejected'],
                        ['no' => 'FRM-002', 'kategori' => 'Form Rekening', 'sumber' => 'CS', 'tanggal' => '2026-02-03', 'status' => 'Approved'],
                        ['no' => 'KEL-007', 'kategori' => 'Keluhan Nasabah', 'sumber' => 'CS', 'tanggal' => '2026-02-02', 'status' => 'Pending'],
                    ] as $doc)
                    <tr class="border-b">
                        <td class="py-3 pr-4 font-medium">{{ $doc['no'] }}</td>
                        <td class="py-3 pr-4">{{ $doc['kategori'] }}</td>
                        <td class="py-3 pr-4">{{ $doc['sumber'] }}</td>
                        <td class="py-3 pr-4">{{ $doc['tanggal'] }}</td>
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

        <div class="text-xs text-gray-500 mt-4">
            *Data di atas masih dummy. Nanti diganti dari database + filter status.
        </div>
    </div>
</x-app-shell>
