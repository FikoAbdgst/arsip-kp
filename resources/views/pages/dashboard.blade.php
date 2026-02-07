<x-app-shell title="Dashboard Utama" header="Dashboard">
    {{-- Ringkasan Global --}}
    <div class="bg-white rounded-xl border p-6 shadow-sm mb-6">
        <div class="flex justify-between items-start">
            <div>
                <div class="text-sm text-gray-500">Overview</div>
                <div class="text-xl font-semibold">Dashboard Utama</div>
                <div class="text-sm text-gray-600 mt-1">
                    Monitoring dokumen operasional. Sistem akan menghapus permanen dokumen yang berusia lebih dari 5
                    tahun.
                </div>
            </div>
            {{-- Legend Status Umur --}}
            <div class="flex gap-4 text-xs font-medium">
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-green-500"></span> Aktif (< 4 Tahun) </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-yellow-500"></span> Tidak Aktif
                        </div>
                </div>
            </div>

            {{-- Statistik Card --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
                {{-- Card Total --}}
                <div onclick="applyFilter('approval', 'all')"
                    class="bg-white rounded-xl border p-4 cursor-pointer hover:shadow-lg transition-all duration-200 filter-card {{ request('approval', 'all') == 'all' ? 'active-filter' : '' }}">
                    <div class="text-sm text-gray-500">Total Dokumen</div>
                    <div class="text-2xl font-semibold mt-1">{{ $stats['total'] }}</div>
                </div>

                {{-- Card Approved --}}
                <div onclick="applyFilter('approval', 'approved')"
                    class="bg-white rounded-xl border p-4 cursor-pointer hover:shadow-lg transition-all duration-200 filter-card {{ request('approval') == 'approved' ? 'active-filter' : '' }}">
                    <div class="text-sm text-gray-500">Approved</div>
                    <div class="text-2xl font-semibold mt-1 text-green-600">
                        {{ $stats['approved'] }}
                    </div>
                </div>

                {{-- Card Rejected --}}
                <div onclick="applyFilter('approval', 'rejected')"
                    class="bg-white rounded-xl border p-4 cursor-pointer hover:shadow-lg transition-all duration-200 filter-card {{ request('approval') == 'rejected' ? 'active-filter' : '' }}">
                    <div class="text-sm text-gray-500">Rejected</div>
                    <div class="text-2xl font-semibold mt-1 text-red-600">
                        {{ $stats['rejected'] }}
                    </div>
                </div>

                {{-- Card Hari Ini --}}
                <div onclick="applyFilter('approval', 'today')"
                    class="bg-white rounded-xl border p-4 cursor-pointer hover:shadow-lg transition-all duration-200 filter-card {{ request('approval') == 'today' ? 'active-filter' : '' }}">
                    <div class="text-sm text-gray-500">Masuk Hari Ini</div>
                    <div class="text-2xl font-semibold mt-1 text-blue-600">
                        {{ $stats['today'] }}
                    </div>
                </div>
            </div>

            {{-- Tabel Data Global --}}
            <div class="bg-white rounded-xl border mt-6 shadow-sm overflow-hidden">

                <div class="p-6 border-b flex flex-col md:flex-row justify-between items-center gap-4">
                    <h3 class="text-lg font-semibold flex items-center gap-2">
                        <span id="table-title">Semua Dokumen</span>
                        <span class="text-sm font-normal text-gray-500">
                            (Total: {{ $documents->total() }} data)
                        </span>
                    </h3>

                    {{-- Tab Filter Lifecycle (Aktif vs Tidak Aktif) --}}
                    <div class="flex bg-gray-100 p-1 rounded-lg">
                        <button onclick="applyFilter('lifecycle', 'all')"
                            class="px-4 py-1.5 text-sm font-medium rounded-md transition-all {{ request('lifecycle', 'all') == 'all' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                            Semua
                        </button>
                        <button onclick="applyFilter('lifecycle', 'active')"
                            class="px-4 py-1.5 text-sm font-medium rounded-md transition-all {{ request('lifecycle') == 'active' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                            Aktif
                        </button>
                        <button onclick="applyFilter('lifecycle', 'inactive')"
                            class="px-4 py-1.5 text-sm font-medium rounded-md transition-all {{ request('lifecycle') == 'inactive' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-yellow-700' }}">
                            Tidak Aktif
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-5 border-b">
                            <tr class="text-gray-500 text-left">
                                <th class="py-3 px-4 font-medium">No Dokumen</th>
                                <th class="py-3 px-4 font-medium">Sumber</th>
                                <th class="py-3 px-4 font-medium">Kategori</th>
                                <th class="py-3 px-4 font-medium">Status Umur</th>
                                <th class="py-3 px-4 font-medium">Approval</th>
                                <th class="py-3 px-4 font-medium">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="document-table-body">
                            @forelse($documents as $doc)
                                @php
                                    $rowClass = !$doc->is_active
                                        ? 'bg-yellow-50/50 text-gray-700'
                                        : 'bg-white text-gray-900';
                                @endphp

                                <tr class="border-b transition hover:bg-gray-50 {{ $rowClass }}">

                                    <td class="py-3 px-4 font-medium">
                                        {{ $doc->document_number }}
                                        <div class="text-[10px] font-normal opacity-70 mt-0.5">
                                            Tgl: {{ \Carbon\Carbon::parse($doc->document_date)->format('d/m/Y') }}
                                        </div>
                                    </td>

                                    <td class="py-3 px-4">
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $doc->source == 'teller' ? 'bg-purple-100 text-purple-800' : 'bg-orange-100 text-orange-800' }}">
                                            {{ strtoupper($doc->source) }}
                                        </span>
                                    </td>

                                    <td class="py-3 px-4">{{ $doc->category }}</td>

                                    <td class="py-3 px-4">
                                        @if (!$doc->is_active)
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-[10px] font-bold bg-yellow-100 text-yellow-700 border border-yellow-200"
                                                title="Dokumen sudah tidak aktif">
                                                TIDAK AKTIF
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700 border border-green-200">
                                                AKTIF
                                            </span>
                                        @endif
                                    </td>

                                    <td class="py-3 px-4">
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full
                                {{ $doc->status === 'approved'
                                    ? 'bg-green-100 text-green-700 border border-green-200'
                                    : ($doc->status === 'rejected'
                                        ? 'bg-red-100 text-red-700 border border-red-200'
                                        : 'bg-yellow-100 text-yellow-700 border border-yellow-200') }}">
                                            {{ ucfirst($doc->status) }}
                                        </span>
                                    </td>

                                    <td class="py-3 px-4">
                                        <button onclick="openDetail('{{ $doc->id }}')"
                                            class="text-blue-600 hover:text-blue-800 font-semibold text-xs uppercase">Detail</button>
                                    </td>
                                </tr>

                                {{-- MODAL DETAIL --}}
                                {{-- PENTING: ID Modal harus unik per loop --}}
                                <div id="detailModal-{{ $doc->id }}"
                                    class="fixed inset-0 bg-black/50 hidden items-center justify-center p-4 z-50 backdrop-blur-sm transition-opacity">
                                    <div
                                        class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto transform transition-all scale-100">

                                        <div
                                            class="p-5 border-b flex items-center justify-between bg-gray-50 rounded-t-2xl sticky top-0 z-10">
                                            <div>
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span
                                                        class="text-xs font-bold text-gray-500 uppercase tracking-wide">
                                                        {{ strtoupper($doc->source) }}
                                                    </span>
                                                    @if ($doc->trashed())
                                                        <span
                                                            class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700">SAMPAH</span>
                                                    @elseif(!$doc->is_active)
                                                        <span
                                                            class="px-2 py-0.5 rounded text-[10px] font-bold bg-gray-200 text-gray-600">TIDAK
                                                            AKTIF</span>
                                                    @endif
                                                </div>
                                                <div class="text-xl font-bold text-gray-900">
                                                    {{ $doc->document_number }}
                                                </div>
                                            </div>
                                            <button type="button" onclick="closeDetail('{{ $doc->id }}')"
                                                class="text-gray-400 hover:text-gray-600 hover:bg-gray-200 rounded-full p-2 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>

                                        {{-- Konten Modal: Read Only --}}
                                        <div id="viewSection{{ $doc->id }}" class="p-6">
                                            @if ($doc->status == 'rejected')
                                                <div
                                                    class="p-4 bg-red-50 text-red-800 rounded-lg border border-red-200 mb-6 flex items-start gap-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-6 w-6 text-red-600 flex-shrink-0" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                    </svg>
                                                    <div>
                                                        <h4 class="font-bold">Status: Ditolak</h4>
                                                        <p class="text-sm mt-1">Alasan: {{ $doc->rejection_reason }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div
                                                    class="md:col-span-2 flex justify-center bg-gray-100 rounded-xl p-4 border border-dashed border-gray-300">
                                                    @if ($doc->file_path)
                                                        <div class="text-center">
                                                            <a href="{{ asset('storage/' . $doc->file_path) }}"
                                                                target="_blank" class="block">
                                                                <div
                                                                    class="flex flex-col items-center justify-center h-40 w-full text-blue-500 hover:text-blue-700 transition">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="h-16 w-16 mb-2" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="1.5"
                                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                    </svg>
                                                                    <span class="text-sm font-medium underline">Lihat
                                                                        Dokumen</span>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    @else
                                                        <span class="text-gray-400 py-10">Tidak ada file</span>
                                                    @endif
                                                </div>

                                                <div class="space-y-4">
                                                    @if ($doc->source == 'cs')
                                                        <div>
                                                            <label
                                                                class="text-xs font-bold text-gray-500 uppercase">CIF</label>
                                                            <div class="font-medium text-gray-900">{{ $doc->cif }}
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <label
                                                            class="text-xs font-bold text-gray-500 uppercase">Kategori</label>
                                                        <div class="font-medium text-gray-900">{{ $doc->category }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label
                                                            class="text-xs font-bold text-gray-500 uppercase">Diunggah
                                                            Oleh</label>
                                                        <div class="font-medium text-gray-900">
                                                            {{ $doc->user->name ?? 'User Terhapus' }}</div>
                                                    </div>
                                                </div>

                                                <div class="space-y-4">
                                                    <div>
                                                        <label class="text-xs font-bold text-gray-500 uppercase">Lokasi
                                                            Arsip</label>
                                                        <div
                                                            class="font-medium text-gray-900 flex flex-wrap gap-2 mt-1">
                                                            <span
                                                                class="bg-gray-100 px-2 py-1 rounded border text-xs">Lemari
                                                                {{ $doc->cabinet }}</span>
                                                            <span
                                                                class="bg-gray-100 px-2 py-1 rounded border text-xs">Rak
                                                                {{ $doc->shelf }}</span>
                                                            <span
                                                                class="bg-gray-100 px-2 py-1 rounded border text-xs">Box
                                                                {{ $doc->box }}</span>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label
                                                            class="text-xs font-bold text-gray-500 uppercase">Tanggal
                                                            Dokumen</label>
                                                        <div class="font-medium text-gray-900">
                                                            {{ \Carbon\Carbon::parse($doc->document_date)->translatedFormat('d F Y') }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="md:col-span-2">
                                                    <label
                                                        class="text-xs font-bold text-gray-500 uppercase">Keterangan</label>
                                                    <div
                                                        class="p-3 bg-gray-50 rounded-lg border text-gray-700 text-sm mt-1">
                                                        {{ $doc->description ?? '-' }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-8 flex justify-end gap-3 pt-5 border-t">
                                                <button onclick="closeDetail('{{ $doc->id }}')"
                                                    class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition">
                                                    Tutup
                                                </button>

                                                {{-- Fitur Hapus Permanen (jika sudah di tong sampah) --}}
                                                @if ($doc->trashed() && auth()->user()->role !== 'supervisor')
                                                    <form action="{{ route('documents.destroy', $doc->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Hapus permanen dokumen ini? Data tidak bisa dikembalikan.');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition shadow-sm">
                                                            Hapus Permanen
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- Fitur Hapus/Edit (jika belum di tong sampah) --}}
                                                @if (!$doc->trashed() && auth()->user()->role !== 'supervisor')
                                                    @if ($doc->status == 'rejected')
                                                        <form action="{{ route('documents.destroy', $doc->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Yakin hapus dokumen ini?');">
                                                            @csrf @method('DELETE')
                                                            <button type="submit"
                                                                class="px-4 py-2 bg-red-100 text-red-700 border border-red-200 rounded-lg hover:bg-red-200 font-medium transition">
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    @endif
                                                    @if ($doc->status == 'pending' || $doc->status == 'rejected')
                                                        <button onclick="toggleEditMode('{{ $doc->id }}', true)"
                                                            class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 font-medium transition shadow-sm">
                                                            {{ $doc->status == 'rejected' ? 'Revisi' : 'Edit' }}
                                                        </button>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Konten Modal: Edit Form --}}
                                        <div id="editSection{{ $doc->id }}" class="hidden">
                                            <form action="{{ route('documents.update', $doc->id) }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf @method('PUT')
                                                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div class="md:col-span-2 text-center border-b pb-4 mb-2">
                                                        <label class="block text-sm font-bold text-gray-700 mb-2">Ganti
                                                            File
                                                            (Opsional)
                                                        </label>
                                                        <input type="file" name="file_path"
                                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                                                    </div>
                                                    @if ($doc->source == 'cs')
                                                        <div><label
                                                                class="block text-sm font-medium mb-1">CIF</label><input
                                                                type="text" name="cif"
                                                                value="{{ $doc->cif }}"
                                                                class="w-full rounded-lg border-gray-300" required>
                                                        </div>
                                                        <div><label
                                                                class="block text-sm font-medium mb-1">Kategori</label>
                                                            <select name="category"
                                                                class="w-full rounded-lg border-gray-300">
                                                                <option value="Form Rekening"
                                                                    {{ $doc->category == 'Form Rekening' ? 'selected' : '' }}>
                                                                    Form Rekening</option>
                                                                <option value="Keluhan Nasabah"
                                                                    {{ $doc->category == 'Keluhan Nasabah' ? 'selected' : '' }}>
                                                                    Keluhan Nasabah</option>
                                                                <option value="Lainnya"
                                                                    {{ $doc->category == 'Lainnya' ? 'selected' : '' }}>
                                                                    Lainnya
                                                                </option>
                                                            </select>
                                                        </div>
                                                    @else
                                                        <div><label
                                                                class="block text-sm font-medium mb-1">Kategori</label>
                                                            <select name="category"
                                                                class="w-full rounded-lg border-gray-300">
                                                                <option value="Slip Setoran"
                                                                    {{ $doc->category == 'Slip Setoran' ? 'selected' : '' }}>
                                                                    Slip Setoran</option>
                                                                <option value="Bukti Transfer"
                                                                    {{ $doc->category == 'Bukti Transfer' ? 'selected' : '' }}>
                                                                    Bukti Transfer</option>
                                                                <option value="Lainnya"
                                                                    {{ $doc->category == 'Lainnya' ? 'selected' : '' }}>
                                                                    Lainnya
                                                                </option>
                                                            </select>
                                                        </div>
                                                    @endif
                                                    <div><label class="block text-sm font-medium mb-1">Nomor
                                                            Dokumen</label><input type="text"
                                                            name="document_number"
                                                            value="{{ $doc->document_number }}"
                                                            class="w-full rounded-lg border-gray-300" required></div>
                                                    <div><label
                                                            class="block text-sm font-medium mb-1">Tanggal</label><input
                                                            type="date" name="document_date"
                                                            value="{{ $doc->document_date }}"
                                                            class="w-full rounded-lg border-gray-300" required></div>
                                                    <div><label
                                                            class="block text-sm font-medium mb-1">Lemari</label><select
                                                            name="cabinet" class="w-full rounded-lg border-gray-300">
                                                            @foreach (['A', 'B', 'C', 'D'] as $c)
                                                                <option value="{{ $c }}"
                                                                    {{ $doc->cabinet == $c ? 'selected' : '' }}>
                                                                    {{ $c }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div><label
                                                            class="block text-sm font-medium mb-1">Rak</label><select
                                                            name="shelf" class="w-full rounded-lg border-gray-300">
                                                            @foreach (range(1, 5) as $r)
                                                                <option value="{{ $r }}"
                                                                    {{ $doc->shelf == $r ? 'selected' : '' }}>
                                                                    {{ $r }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div><label
                                                            class="block text-sm font-medium mb-1">Box</label><select
                                                            name="box" class="w-full rounded-lg border-gray-300">
                                                            @foreach (range(1, 10) as $b)
                                                                <option value="{{ $b }}"
                                                                    {{ $doc->box == $b ? 'selected' : '' }}>
                                                                    {{ $b }}</option>
                                                            @endforeach
                                                        </select></div>
                                                    <div class="md:col-span-2"><label
                                                            class="block text-sm font-medium mb-1">Keterangan</label>
                                                        <textarea name="description" rows="2" class="w-full rounded-lg border-gray-300">{{ $doc->description }}</textarea>
                                                    </div>
                                                </div>
                                                <div
                                                    class="p-5 border-t bg-gray-50 flex justify-end gap-2 rounded-b-2xl">
                                                    <button type="button"
                                                        onclick="toggleEditMode('{{ $doc->id }}', false)"
                                                        class="px-4 py-2 border rounded-lg bg-white text-gray-700 hover:bg-gray-50 font-medium">Batal</button>
                                                    <button type="submit"
                                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium shadow-sm">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr id="empty-row">
                                    <td colspan="6" class="text-center py-12 text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-12 w-12 mx-auto text-gray-300 mb-3" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Belum ada dokumen masuk.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div id="no-results" class="hidden text-center py-12 text-gray-500">
                        Data tidak ditemukan pada filter ini.
                    </div>
                </div>

                {{-- LINK PAGINATION --}}
                <div class="p-4 border-t bg-gray-50">
                    {{ $documents->links() }}
                </div>
            </div>

            {{-- SCRIPTS --}}
            <script>
                // Fungsi Filter Server-side
                function applyFilter(key, value) {
                    const url = new URL(window.location.href);

                    // Set parameter filter
                    url.searchParams.set(key, value);

                    // Reset page ke 1 setiap kali filter berubah
                    url.searchParams.delete('page');

                    // Reload halaman
                    window.location.href = url.toString();
                }

                // Fungsi Modal & Detail
                function openDetail(id) {
                    const modal = document.getElementById('detailModal-' + id);
                    if (modal) {
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                        toggleEditMode(id, false);
                    }
                }

                function closeDetail(id) {
                    const modal = document.getElementById('detailModal-' + id);
                    if (modal) {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    }
                }

                function toggleEditMode(id, showEdit) {
                    const viewSection = document.getElementById('viewSection' + id);
                    const editSection = document.getElementById('editSection' + id);
                    if (showEdit) {
                        viewSection.classList.add('hidden');
                        editSection.classList.remove('hidden');
                    } else {
                        viewSection.classList.remove('hidden');
                        editSection.classList.add('hidden');
                    }
                }

                // CSS untuk Active Filter
                const style = document.createElement('style');
                style.textContent = `
        .active-filter {
            border-color: #3b82f6 !important;
            border-width: 2px !important;
            background: linear-gradient(to bottom right, #eff6ff, #ffffff) !important;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.1), 0 2px 4px -1px rgba(59, 130, 246, 0.06) !important;
        }
    `;
                document.head.appendChild(style);
            </script>
</x-app-shell>
