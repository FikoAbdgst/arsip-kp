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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
        {{-- Card Total --}}
        <a href="{{ route('reports.index') }}" class="bg-white rounded-xl border p-4 hover:shadow-md transition">
            <div class="text-sm text-gray-500">Total Dokumen</div>
            <div class="text-2xl font-semibold mt-1">{{ $totalDocuments ?? 0 }}</div>
            <div class="text-xs text-gray-500 mt-1">Semua dokumen tersimpan</div>
        </a>

        {{-- Card Approved --}}
        <div class="bg-white rounded-xl border p-4">
            <div class="text-sm text-gray-500">Approved</div>
            <div class="text-2xl font-semibold mt-1">{{ $approvedDocuments ?? 0 }}</div>
            <div class="text-xs text-gray-500 mt-1">Sudah diverifikasi</div>
        </div>

        {{-- Card Rejected (Actionable) --}}
        {{-- Jika diklik user bisa melihat list rejected untuk di re-upload --}}
        <a href="{{ route('reports.index', ['status' => 'rejected']) }}"
            class="bg-white rounded-xl border p-4 hover:shadow-md transition border-red-200">
            <div class="text-sm text-gray-500 text-red-600">Rejected</div>
            <div class="text-2xl font-semibold mt-1 text-red-700">{{ $rejectedDocuments ?? 0 }}</div>
            <div class="text-xs text-gray-500 mt-1">Perlu upload ulang (Klik)</div>
        </a>

        {{-- Card Upload Hari Ini --}}
        <div class="bg-white rounded-xl border p-4">
            <div class="text-sm text-gray-500">Upload Hari Ini</div>
            {{-- Logic view composer atau pass variable from controller recommended here. Menggunakan placeholder logic --}}
            <div class="text-2xl font-semibold mt-1">
                {{ \App\Models\Document::whereDate('created_at', today())->count() }}
            </div>
            <div class="text-xs text-gray-500 mt-1">{{ now()->format('d M Y') }}</div>
        </div>
    </div>

    {{-- Dokumen Terbaru --}}
    <div class="bg-white rounded-xl border p-6 mt-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Dokumen Terbaru</h3>
            <a href="{{ route('reports.index') }}" class="text-sm text-blue-600 hover:underline">
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
                    {{-- Mengambil 5 data terbaru dari DB (Controller harus passing $latestDocuments atau kita query langsung di view jika darurat) --}}
                    @foreach (\App\Models\Document::latest()->take(5)->get() as $doc)
                        <tr class="border-b">
                            <td class="py-3 pr-4 font-medium">{{ $doc->document_number }}</td>
                            <td class="py-3 pr-4">{{ $doc->category }}</td>
                            <td class="py-3 pr-4 uppercase">{{ $doc->source }}</td>
                            <td class="py-3 pr-4">{{ \Carbon\Carbon::parse($doc->document_date)->format('d M Y') }}
                            </td>
                            <td class="py-3 pr-4">
                                @if ($doc->status === 'approved')
                                    <span
                                        class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">Approved</span>
                                @elseif($doc->status === 'rejected')
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">Rejected</span>
                                @else
                                    <span
                                        class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">Pending</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-shell>
