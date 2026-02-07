<x-app-shell title="Reports" header="Reports">
    {{-- Header --}}
    <div class="bg-white rounded-xl border p-6">
        <div class="text-sm text-gray-500">Laporan</div>
        <div class="text-xl font-semibold">Laporan Arsip Dokumen</div>
        <div class="text-sm text-gray-600 mt-1">
            Rekap dokumen berdasarkan periode, kategori, sumber, dan lokasi fisik.
        </div>
    </div>

    {{-- Filter Panel --}}
    <div class="bg-white rounded-xl border p-6 mt-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Filter Data
            </h3>
        </div>

        <form action="{{ route('reports.index') }}" method="GET">
            {{-- Pertahankan status jika user mengklik card --}}
            @if (request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                {{-- 1. Tanggal --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 text-sm" />
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 text-sm" />
                </div>

                {{-- 2. Sumber --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Sumber Dokumen</label>
                    <select name="source" class="w-full rounded-lg border-gray-300 focus:border-blue-500 text-sm">
                        <option value="">Semua Sumber</option>
                        <option value="teller" {{ request('source') == 'teller' ? 'selected' : '' }}>Teller</option>
                        <option value="cs" {{ request('source') == 'cs' ? 'selected' : '' }}>Customer Service
                        </option>
                    </select>
                </div>

                {{-- 3. Kategori --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Kategori</label>
                    <select name="category" class="w-full rounded-lg border-gray-300 focus:border-blue-500 text-sm">
                        <option value="">Semua Kategori</option>
                        <option value="Slip Setoran" {{ request('category') == 'Slip Setoran' ? 'selected' : '' }}>Slip
                            Setoran</option>
                        <option value="Bukti Transfer" {{ request('category') == 'Bukti Transfer' ? 'selected' : '' }}>
                            Bukti Transfer</option>
                        <option value="Form Rekening" {{ request('category') == 'Form Rekening' ? 'selected' : '' }}>
                            Form Rekening</option>
                        <option value="Keluhan Nasabah"
                            {{ request('category') == 'Keluhan Nasabah' ? 'selected' : '' }}>Keluhan Nasabah</option>
                        <option value="Lainnya" {{ request('category') == 'Lainnya' ? 'selected' : '' }}>Lainnya
                        </option>
                    </select>
                </div>
            </div>

            {{-- 4. Lokasi Fisik (Grid Kecil) --}}
            <div class="grid grid-cols-3 gap-4 mb-4 border-t pt-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Lemari</label>
                    <select name="cabinet" class="w-full rounded-lg border-gray-300 focus:border-blue-500 text-sm">
                        <option value="">Semua</option>
                        @foreach (['A', 'B', 'C', 'D'] as $c)
                            <option value="{{ $c }}" {{ request('cabinet') == $c ? 'selected' : '' }}>
                                Lemari {{ $c }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Rak</label>
                    <select name="shelf" class="w-full rounded-lg border-gray-300 focus:border-blue-500 text-sm">
                        <option value="">Semua</option>
                        @foreach (range(1, 5) as $r)
                            <option value="{{ $r }}" {{ request('shelf') == $r ? 'selected' : '' }}>Rak
                                {{ $r }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Box</label>
                    <select name="box" class="w-full rounded-lg border-gray-300 focus:border-blue-500 text-sm">
                        <option value="">Semua</option>
                        @foreach (range(1, 10) as $b)
                            <option value="{{ $b }}" {{ request('box') == $b ? 'selected' : '' }}>Box
                                {{ $b }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex flex-col sm:flex-row gap-2 justify-end">
                <a href="{{ route('reports.index') }}"
                    class="px-4 py-2 rounded-lg border bg-white hover:bg-gray-50 text-sm text-center font-medium text-gray-700">
                    Reset Filter
                </a>
                <button type="submit"
                    class="px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm font-medium shadow-sm">
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    {{-- Ringkasan Report (Clickable Cards) --}}
    {{-- Menggunakan request()->except('status') agar filter form (tanggal/lokasi) tetap terbawa saat card diklik --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">

        {{-- Card TOTAL --}}
        <a href="{{ route('reports.index', request()->except('status')) }}"
            class="bg-white rounded-xl border p-4 cursor-pointer hover:shadow-md transition-all {{ !request('status') ? 'ring-2 ring-blue-500 border-blue-500' : '' }}">
            <div class="text-sm text-gray-500">Total Dokumen</div>
            <div class="text-2xl font-semibold mt-1">{{ $stats['total'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Sesuai filter (kecuali status)</div>
        </a>

        {{-- Card APPROVED --}}
        <a href="{{ route('reports.index', array_merge(request()->all(), ['status' => 'approved'])) }}"
            class="bg-white rounded-xl border p-4 cursor-pointer hover:shadow-md transition-all {{ request('status') == 'approved' ? 'ring-2 ring-green-500 border-green-500' : '' }}">
            <div class="text-sm text-gray-500">Approved</div>
            <div class="text-2xl font-semibold mt-1 text-green-600">{{ $stats['approved'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Sudah diverifikasi</div>
        </a>

        {{-- Card PENDING --}}
        <a href="{{ route('reports.index', array_merge(request()->all(), ['status' => 'pending'])) }}"
            class="bg-white rounded-xl border p-4 cursor-pointer hover:shadow-md transition-all {{ request('status') == 'pending' ? 'ring-2 ring-yellow-500 border-yellow-500' : '' }}">
            <div class="text-sm text-gray-500">Pending</div>
            <div class="text-2xl font-semibold mt-1 text-yellow-600">{{ $stats['pending'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Menunggu verifikasi</div>
        </a>

        {{-- Card REJECTED --}}
        <a href="{{ route('reports.index', array_merge(request()->all(), ['status' => 'rejected'])) }}"
            class="bg-white rounded-xl border p-4 cursor-pointer hover:shadow-md transition-all {{ request('status') == 'rejected' ? 'ring-2 ring-red-500 border-red-500' : '' }}">
            <div class="text-sm text-gray-500">Rejected</div>
            <div class="text-2xl font-semibold mt-1 text-red-600">{{ $stats['rejected'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Ditolak / Perlu revisi</div>
        </a>
    </div>

    {{-- Tabel Laporan + Export --}}
    <div class="bg-white rounded-xl border p-6 mt-6 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <h3 class="text-lg font-semibold flex items-center gap-2">
                Hasil Pencarian
                @if (request('status'))
                    <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-600 border font-normal">
                        Status: {{ ucfirst(request('status')) }}
                    </span>
                @endif
            </h3>

            {{-- Tombol Export (Mengirim parameter filter saat ini) --}}
            <div class="flex gap-2 justify-end">
                <a href="{{ route('reports.export.pdf', request()->query()) }}" target="_blank"
                    class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-red-200 bg-red-50 hover:bg-red-100 text-red-700 text-sm transition font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    PDF
                </a>
                <a href="{{ route('reports.export.excel', request()->query()) }}" target="_blank"
                    class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-green-200 bg-green-50 hover:bg-green-100 text-green-700 text-sm transition font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Excel
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b text-gray-500 bg-gray-50">
                        <th class="text-left py-3 px-4 rounded-tl-lg">No Dokumen</th>
                        <th class="text-left py-3 px-4">Kategori</th>
                        <th class="text-left py-3 px-4">Sumber</th>
                        <th class="text-left py-3 px-4">Tanggal</th>
                        <th class="text-left py-3 px-4">Lokasi Fisik</th>
                        <th class="text-left py-3 px-4 rounded-tr-lg">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($documents as $doc)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-3 px-4 font-medium text-gray-900">{{ $doc->document_number }}</td>
                            <td class="py-3 px-4">{{ $doc->category }}</td>
                            <td class="py-3 px-4">
                                @if ($doc->source == 'teller')
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                        Teller
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800 border border-orange-200">
                                        CS
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4">{{ \Carbon\Carbon::parse($doc->document_date)->format('d M Y') }}
                            </td>

                            {{-- KOLOM LOKASI YANG DIPERBAIKI --}}
                            <td class="py-3 px-4 text-gray-600">
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-1 bg-gray-100 rounded border text-xs font-medium">Lmr
                                        {{ $doc->cabinet }}</span>
                                    <span class="px-2 py-1 bg-gray-100 rounded border text-xs font-medium">Rak
                                        {{ $doc->shelf }}</span>
                                    <span class="px-2 py-1 bg-gray-100 rounded border text-xs font-medium">Box
                                        {{ $doc->box }}</span>
                                </div>
                            </td>

                            <td class="py-3 px-4">
                                @if ($doc->status === 'approved')
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700 border border-green-200">
                                        Approved
                                    </span>
                                @elseif($doc->status === 'rejected')
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700 border border-red-200">
                                        Rejected
                                    </span>
                                @else
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700 border border-yellow-200">
                                        Pending
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mb-3"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span class="font-medium">Tidak ada data ditemukan</span>
                                    <span class="text-xs mt-1">Coba sesuaikan filter pencarian Anda.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-shell>
