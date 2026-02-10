<x-app-shell title="Dashboard Teller" header="Teller">
    {{-- Ringkasan Global --}}
    <div class="bg-white rounded-xl border p-6 shadow-sm mb-6">
        <div class="flex justify-between items-start">
            <div>
                <div class="text-sm text-gray-500">Divisi</div>
                <div class="text-xl font-semibold">Dashboard Teller</div>
                <div class="text-sm text-gray-600 mt-1">
                    Monitoring dan upload dokumen Teller.
                </div>
            </div>
            {{-- Legend Status Umur --}}
            <div class="flex gap-4 text-xs font-medium">
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-green-500"></span> Aktif
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-yellow-500"></span> Tidak Aktif
                </div>
            </div>
        </div>

        {{-- Statistik Card --}}
        <div id="teller-stats">
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

                {{-- Card Masuk Hari Ini --}}
                <div onclick="applyFilter('approval', 'today')"
                    class="bg-white rounded-xl border p-4 cursor-pointer hover:shadow-lg transition-all duration-200 filter-card {{ request('approval') == 'today' ? 'active-filter' : '' }}">
                    <div class="text-sm text-gray-500">Masuk Hari Ini</div>
                    <div class="text-2xl font-semibold mt-1 text-blue-600">
                        {{ $stats['today'] }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alert --}}
    @if (session('success'))
        <div class="mt-4 p-4 bg-green-100 text-green-700 rounded-lg shadow-sm border border-green-200 mb-4">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="mt-4 p-4 bg-red-100 text-red-700 rounded-lg shadow-sm border border-red-200 mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Tombol Upload --}}
    <div class="mb-4">
        @if (auth()->user()->role !== 'supervisor')
            <button onclick="openModal('uploadModal')"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition shadow">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                        clip-rule="evenodd" />
                </svg>
                Upload Dokumen Teller
            </button>
        @endif
    </div>

    {{-- Tabel Data Global --}}
    <div id="teller-table">
        <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
            <div class="p-6 border-b flex flex-col md:flex-row justify-between items-center gap-4">
                <h3 class="text-lg font-semibold flex items-center gap-2">
                    <span id="table-title">Daftar Dokumen</span>
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
                            <th class="py-3 px-4 font-medium">Kategori</th>
                            <th class="py-3 px-4 font-medium">Tanggal</th>
                            <th class="py-3 px-4 font-medium">Lokasi</th>
                            <th class="py-3 px-4 font-medium">Status Arsip</th>
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

                            <tr class="border-b hover:bg-gray-50 transition {{ $rowClass }}">
                                <td class="py-3 px-4 font-medium">{{ $doc->document_number }}</td>
                                <td class="py-3 px-4">{{ $doc->category }}</td>
                                <td class="py-3 px-4">{{ \Carbon\Carbon::parse($doc->document_date)->format('d/m/Y') }}
                                </td>
                                <td class="py-3 px-4 text-gray-600">Lmr {{ $doc->cabinet }} / Rak {{ $doc->shelf }}
                                    /
                                    Box {{ $doc->box }}</td>

                                <td class="py-3 px-4">
                                    @if (!$doc->is_active)
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-[10px] font-bold bg-yellow-100 text-yellow-700 border border-yellow-200">
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
                                        class="text-blue-600 hover:text-blue-800 font-semibold hover:underline">
                                        DETAIL
                                    </button>
                                </td>
                            </tr>

                            {{-- MODAL DETAIL (Harus di dalam loop) --}}
                            <div id="detailModal-{{ $doc->id }}"
                                class="fixed inset-0 bg-black/50 hidden items-center justify-center p-4 z-50 backdrop-blur-sm transition-opacity">
                                <div
                                    class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto transform transition-all scale-100">
                                    <div
                                        class="p-5 border-b flex items-center justify-between bg-gray-50 rounded-t-2xl sticky top-0 z-10">
                                        <div>
                                            <div class="text-xs font-bold text-blue-600 uppercase tracking-wide">Detail
                                                Dokumen</div>
                                            <div class="text-xl font-bold text-gray-900">{{ $doc->document_number }}
                                            </div>
                                        </div>
                                        <button type="button" onclick="closeDetail('{{ $doc->id }}')"
                                            class="text-gray-400 hover:text-gray-600 hover:bg-gray-200 rounded-full p-2 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
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
                                                    <h4 class="font-bold">Dokumen Ditolak oleh Supervisor</h4>
                                                    <p class="mt-1 text-sm">{{ $doc->rejection_reason }}</p>
                                                    <p class="mt-2 text-xs text-red-600 font-semibold">Silakan Edit
                                                        untuk
                                                        upload ulang revisi, atau Hapus jika tidak diperlukan.</p>
                                                </div>
                                            </div>
                                        @elseif($doc->status == 'approved')
                                            <div
                                                class="p-4 bg-green-50 text-green-800 rounded-lg border border-green-200 mb-6 flex items-start gap-3">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-6 w-6 text-green-600 flex-shrink-0" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <div>
                                                    <h4 class="font-bold">Dokumen Disetujui</h4>
                                                    <p class="text-sm mt-1">Dokumen ini telah diverifikasi. Data tidak
                                                        dapat diubah lagi.</p>
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
                                                            target="_blank"
                                                            class="mt-2 inline-block text-sm text-blue-600 hover:underline">
                                                            Lihat Ukuran Penuh
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="text-gray-400 py-10 flex flex-col items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-2"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        Tidak ada file gambar
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="space-y-4">
                                                <div>
                                                    <label
                                                        class="text-xs font-bold text-gray-500 uppercase">Kategori</label>
                                                    <div class="font-medium text-gray-900">{{ $doc->category }}</div>
                                                </div>
                                                <div>
                                                    <label class="text-xs font-bold text-gray-500 uppercase">Tanggal
                                                        Dokumen</label>
                                                    <div class="font-medium text-gray-900">
                                                        {{ \Carbon\Carbon::parse($doc->document_date)->translatedFormat('l, d F Y') }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="space-y-4">
                                                <div>
                                                    <label class="text-xs font-bold text-gray-500 uppercase">Lokasi
                                                        Arsip</label>
                                                    <div class="font-medium text-gray-900 flex flex-wrap gap-2 mt-1">
                                                        <span
                                                            class="bg-gray-100 px-2 py-1 rounded border text-xs">Lemari
                                                            {{ $doc->cabinet }}</span>
                                                        <span class="bg-gray-100 px-2 py-1 rounded border text-xs">Rak
                                                            {{ $doc->shelf }}</span>
                                                        <span class="bg-gray-100 px-2 py-1 rounded border text-xs">Box
                                                            {{ $doc->box }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="md:col-span-2">
                                                <label
                                                    class="text-xs font-bold text-gray-500 uppercase">Keterangan</label>
                                                <div
                                                    class="p-3 bg-gray-50 rounded-lg border text-gray-700 text-sm mt-1">
                                                    {{ $doc->description ?? 'Tidak ada keterangan tambahan.' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-8 flex justify-end gap-3 border-t pt-5">
                                            <button onclick="closeDetail('{{ $doc->id }}')"
                                                class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition">
                                                Tutup
                                            </button>
                                            @if (auth()->user()->role !== 'supervisor')
                                                @if ($doc->status == 'rejected')
                                                    <form action="{{ route('documents.destroy', $doc->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini secara permanen?');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                            class="px-4 py-2 bg-red-100 text-red-700 border border-red-200 rounded-lg hover:bg-red-200 font-medium transition flex items-center gap-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                                viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd"
                                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                            Hapus
                                                        </button>
                                                    </form>
                                                @endif
                                                @if ($doc->status == 'pending' || $doc->status == 'rejected')
                                                    <button onclick="toggleEditMode('{{ $doc->id }}', true)"
                                                        class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 font-medium transition shadow-sm flex items-center gap-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                            viewBox="0 0 20 20" fill="currentColor">
                                                            <path
                                                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                        </svg>
                                                        {{ $doc->status == 'rejected' ? 'Revisi / Upload Ulang' : 'Edit Data' }}
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <div id="editSection{{ $doc->id }}" class="hidden">
                                        <form action="{{ route('documents.update', $doc->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf @method('PUT')
                                            <div class="p-6">
                                                <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                                    <div class="flex">
                                                        <div class="flex-shrink-0">
                                                            <svg class="h-5 w-5 text-yellow-400"
                                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                                fill="currentColor">
                                                                <path fill-rule="evenodd"
                                                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        </div>
                                                        <div class="ml-3">
                                                            <p class="text-sm text-yellow-700">
                                                                Anda sedang dalam mode edit. Menyimpan perubahan akan
                                                                mengubah status dokumen kembali menjadi
                                                                <strong>Pending</strong>.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-sm font-medium mb-1">Kategori</label>
                                                        <select name="category"
                                                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
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
                                                    <div>
                                                        <label class="block text-sm font-medium mb-1">Nomor
                                                            Dokumen</label>
                                                        <input type="text" name="document_number"
                                                            value="{{ $doc->document_number }}"
                                                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                                            required>
                                                    </div>
                                                    <div class="md:col-span-2">
                                                        <label class="block text-sm font-medium mb-1">Tanggal</label>
                                                        <input type="date" name="document_date"
                                                            value="{{ $doc->document_date }}"
                                                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                                            required>
                                                    </div>
                                                    <div class="md:col-span-2 text-center border-b pb-4 mb-2">
                                                        <div class="mb-2 text-sm text-gray-500 font-medium">File Saat
                                                            Ini
                                                        </div>
                                                        <img id="previewEdit{{ $doc->id }}"
                                                            src="{{ asset('storage/' . $doc->file_path) }}"
                                                            class="h-32 mx-auto rounded border shadow-sm mb-4 object-contain">
                                                        <label
                                                            class="block text-sm font-bold text-gray-700 mb-2">Upload
                                                            File Baru (Revisi)</label>
                                                        <input type="file" name="file_path"
                                                            onchange="updatePreview(event, 'previewEdit{{ $doc->id }}')"
                                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                                                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak
                                                            ingin
                                                            mengubah file.</p>
                                                    </div>
                                                    <div
                                                        class="md:col-span-2 border-t pt-2 mt-2 font-semibold text-gray-700">
                                                        Lokasi Fisik</div>
                                                    <div>
                                                        <label class="block text-sm font-medium mb-1">Lemari</label>
                                                        <select name="cabinet"
                                                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                                            @foreach (['A', 'B', 'C', 'D'] as $c)
                                                                <option value="{{ $c }}"
                                                                    {{ $doc->cabinet == $c ? 'selected' : '' }}>Lemari
                                                                    {{ $c }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium mb-1">Rak</label>
                                                        <select name="shelf"
                                                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                                            @foreach (range(1, 5) as $r)
                                                                <option value="{{ $r }}"
                                                                    {{ $doc->shelf == $r ? 'selected' : '' }}>Rak
                                                                    {{ $r }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium mb-1">Kotak</label>
                                                        <select name="box"
                                                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                                            @foreach (range(1, 10) as $b)
                                                                <option value="{{ $b }}"
                                                                    {{ $doc->box == $b ? 'selected' : '' }}>Kotak
                                                                    {{ $b }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="md:col-span-2">
                                                        <label
                                                            class="block text-sm font-medium mb-1">Keterangan</label>
                                                        <textarea name="description" rows="2"
                                                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">{{ $doc->description }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="p-5 border-t bg-gray-50 flex justify-end gap-2 rounded-b-2xl">
                                                <button type="button"
                                                    onclick="toggleEditMode('{{ $doc->id }}', false)"
                                                    class="px-4 py-2 border rounded-lg bg-white text-gray-700 hover:bg-gray-50 font-medium">
                                                    Batal
                                                </button>
                                                <button type="submit"
                                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium shadow-sm flex items-center gap-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Simpan & Ajukan Ulang
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr id="empty-row">
                                <td colspan="7" class="text-center py-12 text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-12 w-12 mx-auto text-gray-300 mb-3" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Belum ada dokumen yang sesuai dengan filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Links --}}
            <div class="p-4 border-t bg-gray-50">
                {{ $documents->links() }}
            </div>
        </div>
    </div>

    {{-- MODAL UPLOAD --}}
    <div id="uploadModal"
        class="fixed inset-0 bg-black/50 hidden items-center justify-center p-4 z-50 backdrop-blur-sm">
        <div class="bg-white w-full max-w-2xl rounded-2xl border shadow-2xl max-h-[90vh] overflow-y-auto">
            <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="source" value="teller">
                <div class="p-5 border-b flex items-center justify-between bg-gray-50 rounded-t-2xl">
                    <h3 class="text-lg font-bold text-gray-800">Upload Dokumen Teller</h3>
                    <button type="button" onclick="closeModal('uploadModal')"
                        class="text-gray-400 hover:text-gray-600 rounded-full p-1 hover:bg-gray-200 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Kategori</label>
                        <select name="category"
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                            required>
                            <option value="" selected disabled>Pilih Kategori...</option>
                            <option value="Slip Setoran">Slip Setoran</option>
                            <option value="Bukti Transfer">Bukti Transfer</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div><label class="block text-sm font-medium mb-1">No Dokumen</label><input type="text"
                            name="document_number"
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Contoh: DOC-001" required></div>
                    <div class="md:col-span-2"><label class="block text-sm font-medium mb-1">Tanggal</label><input
                            type="date" name="document_date"
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                            required></div>
                    <div class="md:col-span-2 flex flex-col items-center">
                        <div
                            class="w-full h-40 bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center mb-3 overflow-hidden relative group hover:border-blue-400 transition">
                            <img id="preview-upload-img" class="hidden h-full object-contain z-10">
                            <div id="preview-placeholder" class="text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-gray-400 mb-1"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                <span class="text-gray-500 text-sm font-medium">Klik untuk pilih file</span>
                                <span class="text-xs text-gray-400 block">(PDF/JPG/PNG Max 2MB)</span>
                            </div>
                            <input type="file" name="file_path"
                                onchange="previewImage(event, 'preview-upload-img')"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required>
                        </div>
                    </div>
                    <div class="md:col-span-2 border-t pt-2 mt-2 font-semibold text-gray-700">Lokasi Fisik</div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Lemari</label>
                        <select name="cabinet"
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                            required>
                            <option value="" selected disabled>Pilih lemari</option>
                            <option value="A">Lemari A</option>
                            <option value="B">Lemari B</option>
                            <option value="C">Lemari C</option>
                            <option value="D">Lemari D</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Rak</label>
                        <select name="shelf"
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                            required>
                            <option value="" selected disabled>Pilih rak</option>
                            @foreach (range(1, 5) as $i)
                                <option value="{{ $i }}">Rak {{ $i }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Kotak</label>
                        <select name="box"
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                            required>
                            <option value="" selected disabled>Pilih kotak</option>
                            @foreach (range(1, 10) as $i)
                                <option value="{{ $i }}">Kotak {{ $i }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2"><label class="block text-sm font-medium mb-1">Keterangan</label>
                        <textarea name="description" rows="2"
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Keterangan tambahan (opsional)"></textarea>
                    </div>
                </div>
                <div class="p-5 border-t bg-gray-50 flex justify-end gap-2 rounded-b-2xl">
                    <button type="button" onclick="closeModal('uploadModal')"
                        class="px-4 py-2 border rounded-lg bg-white text-gray-700 hover:bg-gray-50 font-medium">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium shadow-sm">Upload
                        Dokumen</button>
                </div>
            </form>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script>
        // Fungsi Filter Baru: Reload Halaman dengan Parameter URL
        function applyFilter(key, value) {
            const url = new URL(window.location.href);

            // Set parameter filter (approval atau lifecycle)
            url.searchParams.set(key, value);

            // Reset halaman ke 1 setiap kali filter berubah
            url.searchParams.delete('page');

            // Reload halaman dengan parameter baru
            window.location.href = url.toString();
        }

        // Fungsi Modal (Standard)
        function openModal(id) {
            const el = document.getElementById(id);
            if (el) {
                el.classList.remove('hidden');
                el.classList.add('flex');
            }
        }

        function closeModal(id) {
            const el = document.getElementById(id);
            if (el) {
                el.classList.add('hidden');
                el.classList.remove('flex');
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

        function previewImage(event, previewId) {
            const file = event.target.files[0];
            const img = document.getElementById(previewId);
            const placeholder = document.getElementById('preview-placeholder');
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                    if (placeholder) placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(file);
            }
        }

        function updatePreview(event, previewId) {
            const file = event.target.files[0];
            const img = document.getElementById(previewId);
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }

        // Add CSS for active filter
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
            // Update statistik setiap 5 detik
            activateRealtime('teller-stats', 5000);

            // Update tabel setiap 5 detik
            activateRealtime('teller-table', 5000);
        });
    </script>
</x-app-shell>
