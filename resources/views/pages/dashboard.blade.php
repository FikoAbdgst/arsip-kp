<x-app-shell title="Dashboard Utama" header="Dashboard">
    {{-- Ringkasan Global --}}
    <div id="realtime-dashboard" class="bg-white rounded-xl border p-6 shadow-sm mb-6">
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

                {{-- Card Upload Hari Ini --}}
                <div onclick="applyFilter('approval', 'today')"
                    class="bg-white rounded-xl border p-4 cursor-pointer hover:shadow-lg transition-all duration-200 filter-card {{ request('approval') == 'today' ? 'active-filter' : '' }}">
                    <div class="text-sm text-gray-500">Upload Hari Ini</div>
                    <div class="text-2xl font-semibold mt-1 text-blue-600">
                        {{ $stats['today'] }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Alert (Hanya Success, Error dipindah ke field) --}}
        @if (session('success'))
            <div class="mt-4 p-4 bg-green-100 text-green-700 rounded-lg shadow-sm border border-green-200 mb-4">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        {{-- Tabel Data Global --}}
        <div id="dashboard-table">
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
                        <thead>
                            <tr class="border-b text-gray-500 bg-gray-50 text-left">
                                <th class="py-3 px-4 font-medium">No Dokumen</th>
                                <th class="py-3 px-4 font-medium">Sumber</th>
                                <th class="py-3 px-4 font-medium">Kategori</th>
                                <th class="py-3 px-4 font-medium">Status Umur</th>
                                <th class="py-3 px-4 font-medium">Approval</th>
                                <th class="py-3 px-4 font-medium">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="document-table-body">
                            {{-- GABUNGAN MAPPING KATEGORI CS & TELLER --}}
                            @php
                                $kategoriMap = [
                                    // CS
                                    'FPR' => 'Form pembukaan rekening (FPR)',
                                    'PDN' => 'Form perubahan data nasabah (PDN)',
                                    'FPTR' => 'Form penutupan rekening (FPTR)',
                                    'FPL' => 'Form Layanan kartu & digital banking (FPL)',
                                    // TELLER
                                    'TL-ST' => 'Transaksi setoran dan penarikan (TL-ST)',
                                    'TL-TP' => 'Transaksi transfer dan pembayaran (TL-TP)',
                                    'TL-GK' => 'Transaksi Giro, Kliring, Valuta (TL-GK)',
                                    'TL-LA' => 'Laporan dan Administrasi teller (TL-LA)',
                                ];
                            @endphp

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

                                    {{-- Menampilkan Nama Lengkap --}}
                                    <td class="py-3 px-4">{{ $kategoriMap[$doc->category] ?? $doc->category }}</td>

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

                                        {{-- MODAL DIPINDAHKAN KE DALAM TD --}}
                                        <div id="detailModal-{{ $doc->id }}"
                                            class="fixed inset-0 bg-black/50 hidden items-center justify-center p-4 z-50 backdrop-blur-sm transition-opacity text-left">
                                            <div
                                                class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto transform transition-all scale-100 font-normal">

                                                {{-- Header Modal --}}
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
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>

                                                {{-- MODE: READ-ONLY (DEFAULT) --}}
                                                <div id="viewSection{{ $doc->id }}" class="p-6">
                                                    @if ($doc->status == 'rejected')
                                                        <div
                                                            class="p-4 bg-red-50 text-red-800 rounded-lg border border-red-200 mb-6 flex items-start gap-3">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-6 w-6 text-red-600 flex-shrink-0"
                                                                fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                            </svg>
                                                            <div>
                                                                <h4 class="font-bold">Status: Ditolak</h4>
                                                                <p class="text-sm mt-1">Alasan:
                                                                    {{ $doc->rejection_reason }}
                                                                </p>
                                                                <p class="mt-2 text-xs text-red-600 font-semibold">
                                                                    Silakan Edit untuk upload ulang revisi, atau Hapus
                                                                    jika tidak diperlukan.
                                                                </p>
                                                            </div>
                                                        </div>
                                                    @elseif($doc->status == 'approved')
                                                        <div
                                                            class="p-4 bg-green-50 text-green-800 rounded-lg border border-green-200 mb-6 flex items-start gap-3">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-6 w-6 text-green-600 flex-shrink-0"
                                                                fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            <div>
                                                                <h4 class="font-bold">Dokumen Disetujui</h4>
                                                                <p class="text-sm mt-1">Dokumen ini telah diverifikasi.
                                                                    Data tidak dapat diubah lagi.</p>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <div class="mb-4">
                                                        <span class="text-xs font-bold text-gray-500 uppercase">Status
                                                            Arsip:</span>
                                                        @if ($doc->is_active)
                                                            <span
                                                                class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">Aktif</span>
                                                        @else
                                                            <span
                                                                class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">Non-Aktif</span>
                                                        @endif
                                                    </div>

                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                        <div
                                                            class="md:col-span-2 flex justify-center bg-gray-100 rounded-xl p-4 border border-dashed border-gray-300">
                                                            @if ($doc->file_path)
                                                                <div class="text-center">
                                                                    <img src="{{ asset('storage/' . $doc->file_path) }}"
                                                                        alt="Dokumen"
                                                                        class="max-h-64 object-contain rounded shadow-sm mx-auto">
                                                                    <a href="{{ asset('storage/' . $doc->file_path) }}"
                                                                        target="_blank" class="block">
                                                                        <div
                                                                            class="flex flex-col items-center justify-center h-40 w-full text-blue-500 hover:text-blue-700 transition">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                class="h-16 w-16 mb-2" fill="none"
                                                                                viewBox="0 0 24 24"
                                                                                stroke="currentColor">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    stroke-width="1.5"
                                                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                            </svg>
                                                                            <span
                                                                                class="text-sm font-medium underline">Lihat
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
                                                                    <div class="font-medium text-gray-900">
                                                                        {{ $doc->cif }}
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <label
                                                                    class="text-xs font-bold text-gray-500 uppercase">Kategori</label>
                                                                {{-- Nama Lengkap --}}
                                                                <div class="font-medium text-gray-900">
                                                                    {{ $kategoriMap[$doc->category] ?? $doc->category }}
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
                                                                <label
                                                                    class="text-xs font-bold text-gray-500 uppercase">Lokasi
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

                                                        @if (!$doc->trashed() && auth()->user()->role !== 'supervisor')
                                                            @if ($doc->status == 'rejected')
                                                                <form
                                                                    action="{{ route('documents.destroy', $doc->id) }}"
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
                                                                <button
                                                                    onclick="toggleEditMode('{{ $doc->id }}', true)"
                                                                    class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 font-medium transition shadow-sm">
                                                                    {{ $doc->status == 'rejected' ? 'Revisi' : 'Edit' }}
                                                                </button>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Konten Modal: Edit Form --}}
                                                <div id="editSection{{ $doc->id }}" class="hidden">
                                                    <form action="{{ route('documents.update', $doc->id) }}"
                                                        method="POST" enctype="multipart/form-data">
                                                        @csrf @method('PUT')
                                                        {{-- Kirim source agar controller tahu redirect kemana --}}
                                                        <input type="hidden" name="source"
                                                            value="{{ $doc->source }}">
                                                        <input type="hidden" name="form_selector"
                                                            value="edit-{{ $doc->id }}">

                                                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                                                            <div class="md:col-span-2 text-center border-b pb-4 mb-2">
                                                                <label
                                                                    class="block text-sm font-bold text-gray-700 mb-2">Ganti
                                                                    File (Opsional)</label>
                                                                <input type="file" name="file_path"
                                                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                                                            </div>

                                                            @if ($doc->source == 'cs')
                                                                <div><label
                                                                        class="block text-sm font-medium mb-1">CIF</label><input
                                                                        type="text" name="cif"
                                                                        value="{{ $doc->cif }}"
                                                                        class="w-full rounded-lg border-gray-300"
                                                                        required></div>
                                                                <div>
                                                                    <label
                                                                        class="block text-sm font-medium mb-1">Kategori</label>
                                                                    <select name="category"
                                                                        class="w-full rounded-lg border-gray-300">
                                                                        <option value="FPR"
                                                                            {{ $doc->category == 'FPR' ? 'selected' : '' }}>
                                                                            Form pembukaan rekening (FPR)</option>
                                                                        <option value="PDN"
                                                                            {{ $doc->category == 'PDN' ? 'selected' : '' }}>
                                                                            Form perubahan data nasabah (PDN)</option>
                                                                        <option value="FPTR"
                                                                            {{ $doc->category == 'FPTR' ? 'selected' : '' }}>
                                                                            Form penutupan rekening (FPTR)</option>
                                                                        <option value="FPL"
                                                                            {{ $doc->category == 'FPL' ? 'selected' : '' }}>
                                                                            Form Layanan kartu & digital banking (FPL)
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            @else
                                                                <div>
                                                                    <label
                                                                        class="block text-sm font-medium mb-1">Kategori</label>
                                                                    <select name="category"
                                                                        class="w-full rounded-lg border-gray-300">
                                                                        <option value="TL-ST"
                                                                            {{ $doc->category == 'TL-ST' ? 'selected' : '' }}>
                                                                            Transaksi setoran dan penarikan (TL-ST)
                                                                        </option>
                                                                        <option value="TL-TP"
                                                                            {{ $doc->category == 'TL-TP' ? 'selected' : '' }}>
                                                                            Transaksi transfer dan pembayaran (TL-TP)
                                                                        </option>
                                                                        <option value="TL-GK"
                                                                            {{ $doc->category == 'TL-GK' ? 'selected' : '' }}>
                                                                            Transaksi Giro, Kliring, Valuta (TL-GK)
                                                                        </option>
                                                                        <option value="TL-LA"
                                                                            {{ $doc->category == 'TL-LA' ? 'selected' : '' }}>
                                                                            Laporan dan Administrasi teller (TL-LA)
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            @endif

                                                            <div><label class="block text-sm font-medium mb-1">Nomor
                                                                    Dokumen</label><input type="text"
                                                                    name="document_number"
                                                                    value="{{ $doc->document_number }}"
                                                                    class="w-full rounded-lg border-gray-300" required>
                                                            </div>
                                                            <div><label
                                                                    class="block text-sm font-medium mb-1">Tanggal</label><input
                                                                    type="date" name="document_date"
                                                                    value="{{ $doc->document_date }}"
                                                                    class="w-full rounded-lg border-gray-300" required>
                                                            </div>

                                                            <div><label
                                                                    class="block text-sm font-medium mb-1">Lemari</label><select
                                                                    name="cabinet"
                                                                    class="w-full rounded-lg border-gray-300">
                                                                    @foreach (['A', 'B', 'C', 'D'] as $c)
                                                                        <option value="{{ $c }}"
                                                                            {{ $doc->cabinet == $c ? 'selected' : '' }}>
                                                                            Lemari {{ $c }}</option>
                                                                    @endforeach
                                                                </select></div>
                                                            <div><label
                                                                    class="block text-sm font-medium mb-1">Rak</label><select
                                                                    name="shelf"
                                                                    class="w-full rounded-lg border-gray-300">
                                                                    @foreach (range(1, 5) as $r)
                                                                        <option value="{{ $r }}"
                                                                            {{ $doc->shelf == $r ? 'selected' : '' }}>
                                                                            Rak {{ $r }}</option>
                                                                    @endforeach
                                                                </select></div>
                                                            <div><label
                                                                    class="block text-sm font-medium mb-1">Box</label><select
                                                                    name="box"
                                                                    class="w-full rounded-lg border-gray-300">
                                                                    @foreach (range(1, 10) as $b)
                                                                        <option value="{{ $b }}"
                                                                            {{ $doc->box == $b ? 'selected' : '' }}>
                                                                            Box {{ $b }}</option>
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
                                    </td>
                                </tr>
                            @empty
                                <tr id="empty-row">
                                    <td colspan="7" class="text-center py-12 text-gray-500">
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
                // Fungsi Filter
                function applyFilter(key, value) {
                    const url = new URL(window.location.href);
                    url.searchParams.set(key, value);
                    url.searchParams.delete('page');
                    window.location.href = url.toString();
                }

                function openModal(id) {
                    const el = document.getElementById(id);
                    if (el) {
                        el.classList.remove('hidden');
                        el.classList.add('flex');
                        el.classList.add('modal-is-open');
                    }
                }

                function closeModal(id) {
                    const el = document.getElementById(id);
                    if (el) {
                        el.classList.add('hidden');
                        el.classList.remove('flex');
                        el.classList.remove('modal-is-open');
                    }
                }

                function openDetail(id) {
                    openModal('detailModal-' + id);
                    toggleEditMode(id, false);
                }

                function closeDetail(id) {
                    closeModal('detailModal-' + id);
                    toggleEditMode(id, false);
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

                // --- SMART REALTIME REFRESH ---
                function startSmartRealtime() {
                    setInterval(() => {
                        const openModals = document.querySelectorAll('.fixed.inset-0:not(.hidden)');
                        if (openModals.length > 0) {
                            console.log('Refresh ditahan: User sedang membuka modal.');
                            return;
                        }

                        fetch(window.location.href, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(response => response.text())
                            .then(html => {
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(html, 'text/html');

                                // Update Stats (khusus dashboard utama kita pakai realtime-dashboard atau main container stats)
                                // Tapi di layout ini struktur ID-nya agak beda, kita cek elemen yang ada
                                // Sebaiknya bungkus stats di ID unik jika belum
                                const newStats = doc.getElementById('realtime-dashboard'); // ambil container stats
                                const currentStats = document.getElementById('realtime-dashboard');

                                if (newStats && currentStats) {
                                    // Kita hanya update angka-angkanya saja biar smooth atau replace innerHTML container stats
                                    // Untuk amannya replace content div stats
                                    // Catatan: di kode atas ID 'realtime-dashboard' membungkus header + stats
                                    // Jadi ini akan me-refresh header juga, tidak masalah.
                                    currentStats.innerHTML = newStats.innerHTML;
                                }

                                // Update Table
                                const newTable = doc.getElementById('dashboard-table');
                                const currentTable = document.getElementById('dashboard-table');
                                if (newTable && currentTable) {
                                    currentTable.innerHTML = newTable.innerHTML;
                                }
                            })
                            .catch(err => console.error('Gagal refresh:', err));

                    }, 5000);
                }

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

                document.addEventListener('DOMContentLoaded', function() {
                    let formSelector = "{{ old('form_selector') }}";
                    if (formSelector && formSelector.startsWith('edit-')) {
                        let id = formSelector.replace('edit-', '');
                        openDetail(id);
                        toggleEditMode(id, true);
                    }

                    startSmartRealtime();
                });
            </script>
</x-app-shell>
