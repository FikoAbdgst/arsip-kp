<x-app-shell title="Dashboard Teller" header="Teller">
    {{-- Ringkasan --}}
    <div class="bg-white rounded-xl border p-6">
        <div class="text-sm text-gray-500">Divisi</div>
        <div class="text-xl font-semibold">Dashboard Teller</div>
        <div class="text-sm text-gray-600 mt-1">
            Monitoring dan upload dokumen Teller.
        </div>
    </div>

    {{-- Statistik Teller --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
        <div class="bg-white rounded-xl border p-4">
            <div class="text-sm text-gray-500">Total Dokumen Teller</div>
            <div class="text-2xl font-semibold mt-1">78</div>
            <div class="text-xs text-gray-500 mt-1">Semua dokumen Teller</div>
        </div>

        <div class="bg-white rounded-xl border p-4">
            <div class="text-sm text-gray-500">Approved</div>
            <div class="text-2xl font-semibold mt-1">65</div>
            <div class="text-xs text-gray-500 mt-1">Sudah diverifikasi</div>
        </div>

        <div class="bg-white rounded-xl border p-4">
            <div class="text-sm text-gray-500">Pending</div>
            <div class="text-2xl font-semibold mt-1">10</div>
            <div class="text-xs text-gray-500 mt-1">Menunggu supervisor</div>
        </div>

        <div class="bg-white rounded-xl border p-4">
            <div class="text-sm text-gray-500">Upload Hari Ini</div>
            <div class="text-2xl font-semibold mt-1">3</div>
            <div class="text-xs text-gray-500 mt-1">{{ now()->format('d M Y') }}</div>
        </div>
    </div>

    {{-- Tombol Upload --}}
    @if(auth()->user()->role !== 'supervisor')
    <a href="{{ route('dokumen.create') }}"
        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
        + Upload Dokumen Teller
    </a>
@endif


    </div>

    {{-- Dokumen Teller Terbaru --}}
    <div class="bg-white rounded-xl border p-6 mt-4">
        <h3 class="text-lg font-semibold mb-4">Dokumen Teller Terbaru</h3>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b text-gray-500">
                        <th class="text-left py-3 pr-4">No Dokumen</th>
                        <th class="text-left py-3 pr-4">Kategori</th>
                        <th class="text-left py-3 pr-4">Tanggal</th>
                        <th class="text-left py-3 pr-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ([
                        ['no' => 'SLP-021', 'kategori' => 'Slip Setoran', 'tanggal' => '2026-02-04', 'status' => 'Approved'],
                        ['no' => 'TRF-019', 'kategori' => 'Bukti Transfer', 'tanggal' => '2026-02-04', 'status' => 'Pending'],
                        ['no' => 'SLP-020', 'kategori' => 'Slip Setoran', 'tanggal' => '2026-02-03', 'status' => 'Approved'],
                    ] as $doc)
                    <tr class="border-b">
                        <td class="py-3 pr-4 font-medium">{{ $doc['no'] }}</td>
                        <td class="py-3 pr-4">{{ $doc['kategori'] }}</td>
                        <td class="py-3 pr-4">{{ $doc['tanggal'] }}</td>
                        <td class="py-3 pr-4">
                            @if($doc['status'] === 'Approved')
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                    Approved
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
    </div>
</x-app-shell>
