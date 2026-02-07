<x-app-shell title="Dashboard CS" header="Customer Service">
    {{-- Ringkasan --}}
    <div class="bg-white rounded-xl border p-6">
        <div class="text-sm text-gray-500">Divisi</div>
        <div class="text-xl font-semibold">Dashboard Customer Service</div>
        <div class="text-sm text-gray-600 mt-1">Monitoring dan upload dokumen Customer Service.</div>
    </div>

    {{-- Statistik dengan Filter --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
        <div onclick="filterDocuments('all')" id="card-all"
            class="bg-white rounded-xl border p-4 cursor-pointer hover:shadow-lg transition-all duration-200 filter-card active-filter">
            <div class="text-sm text-gray-500">Total Dokumen</div>
            <div class="text-2xl font-semibold mt-1">{{ $documents->count() }}</div>
        </div>
        <div onclick="filterDocuments('approved')" id="card-approved"
            class="bg-white rounded-xl border p-4 cursor-pointer hover:shadow-lg transition-all duration-200 filter-card">
            <div class="text-sm text-gray-500">Approved</div>
            <div class="text-2xl font-semibold mt-1 text-green-600">
                {{ $documents->where('status', 'approved')->count() }}</div>
        </div>

        {{-- UBAHAN: Filter Rejected (Sebelumnya Pending) --}}
        <div onclick="filterDocuments('rejected')" id="card-rejected"
            class="bg-white rounded-xl border p-4 cursor-pointer hover:shadow-lg transition-all duration-200 filter-card">
            <div class="text-sm text-gray-500">Rejected</div>
            <div class="text-2xl font-semibold mt-1 text-red-600">
                {{ $documents->where('status', 'rejected')->count() }}</div>
        </div>

        <div onclick="filterDocuments('today')" id="card-today"
            class="bg-white rounded-xl border p-4 cursor-pointer hover:shadow-lg transition-all duration-200 filter-card">
            <div class="text-sm text-gray-500">Upload Hari Ini</div>
            <div class="text-2xl font-semibold mt-1 text-blue-600">
                {{ $documents->filter(fn($d) => $d->created_at->isToday())->count() }}</div>
        </div>
    </div>

    {{-- Alert --}}
    @if (session('success'))
        <div class="mt-4 p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="mt-4 p-4 bg-red-100 text-red-700 rounded-lg">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Tombol Upload --}}
    <div class="mt-6">
        @if (auth()->user()->role !== 'supervisor')
            <button onclick="openModal('uploadModalCs')"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                + Upload Dokumen CS
            </button>
        @endif
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-xl border p-6 mt-4">
        <h3 class="text-lg font-semibold mb-4">
            <span id="table-title">Semua Dokumen CS</span>
            <span id="filtered-count" class="text-sm font-normal text-gray-500"></span>
        </h3>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b text-gray-500">
                        <th class="text-left py-3 pr-4">No Dokumen</th>
                        <th class="text-left py-3 pr-4">CIF</th>
                        <th class="text-left py-3 pr-4">Kategori</th>
                        <th class="text-left py-3 pr-4">Tanggal</th>
                        <th class="text-left py-3 pr-4">Status</th>
                        <th class="text-left py-3 pr-4">Aksi</th>
                    </tr>
                </thead>
                <tbody id="document-table-body">
                    @forelse($documents as $doc)
                        <tr class="border-b hover:bg-gray-50 document-row" data-status="{{ $doc->status }}"
                            data-date="{{ $doc->created_at->format('Y-m-d') }}"
                            data-today="{{ $doc->created_at->isToday() ? 'yes' : 'no' }}">
                            <td class="py-3 pr-4 font-medium">{{ $doc->document_number }}</td>
                            <td class="py-3 pr-4">{{ $doc->cif }}</td>
                            <td class="py-3 pr-4">{{ $doc->category }}</td>
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
                            <td class="py-3 pr-4">
                                <button onclick="openDetail('{{ $doc->id }}')"
                                    class="text-blue-600 hover:underline font-semibold">Detail</button>
                            </td>
                        </tr>

                        {{-- MODAL DETAIL (VIEW & EDIT) --}}
                        <div id="detailModal-{{ $doc->id }}"
                            class="fixed inset-0 bg-black/40 hidden items-center justify-center p-4 z-50">
                            <div
                                class="bg-white w-full max-w-2xl rounded-xl border shadow-lg max-h-[90vh] overflow-y-auto">

                                {{-- Header --}}
                                <div class="p-5 border-b flex items-center justify-between bg-gray-50 rounded-t-xl">
                                    <div>
                                        <div class="text-sm text-gray-500">Detail Dokumen</div>
                                        <div class="text-lg font-semibold">{{ $doc->document_number }}</div>
                                    </div>
                                    <button type="button" onclick="closeDetail('{{ $doc->id }}')"
                                        class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                                </div>

                                {{-- MODE: READ-ONLY (DEFAULT) --}}
                                <div id="viewSection{{ $doc->id }}" class="p-6">
                                    {{-- Status Info --}}
                                    @if ($doc->status == 'rejected')
                                        <div
                                            class="p-3 bg-red-50 text-red-700 rounded border border-red-200 mb-4 text-sm">
                                            <strong>Ditolak:</strong> {{ $doc->rejection_reason }}
                                        </div>
                                    @endif

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        {{-- Image Display --}}
                                        <div
                                            class="md:col-span-2 flex justify-center bg-gray-100 rounded-lg p-2 border">
                                            @if ($doc->file_path)
                                                <img src="{{ asset('storage/' . $doc->file_path) }}" alt="Dokumen"
                                                    class="max-h-64 object-contain rounded">
                                            @else
                                                <span class="text-gray-400">Tidak ada gambar</span>
                                            @endif
                                        </div>

                                        <div>
                                            <label class="text-xs text-gray-500">CIF</label>
                                            <div class="font-medium">{{ $doc->cif }}</div>
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500">Kategori</label>
                                            <div class="font-medium">{{ $doc->category }}</div>
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500">Tanggal</label>
                                            <div class="font-medium">{{ $doc->document_date }}</div>
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500">Lemari</label>
                                            <div class="font-medium">Lemari {{ $doc->cabinet }}</div>
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500">Rak & Box</label>
                                            <div class="font-medium">Rak {{ $doc->shelf }} - Box {{ $doc->box }}
                                            </div>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="text-xs text-gray-500">Keterangan</label>
                                            <div class="font-medium">{{ $doc->description ?? '-' }}</div>
                                        </div>
                                    </div>

                                    <div class="mt-6 flex justify-end gap-2 border-t pt-4">
                                        <button onclick="closeDetail('{{ $doc->id }}')"
                                            class="px-4 py-2 bg-white border rounded-lg text-gray-700 hover:bg-gray-50">Tutup</button>
                                        @if (auth()->user()->role !== 'supervisor')
                                            <button onclick="toggleEditMode('{{ $doc->id }}', true)"
                                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Edit
                                                Data</button>
                                        @endif
                                    </div>
                                </div>

                                {{-- MODE: EDIT FORM (HIDDEN BY DEFAULT) --}}
                                <div id="editSection{{ $doc->id }}" class="hidden">
                                    <form action="{{ route('documents.update', $doc->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="source" value="cs">

                                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                                            {{-- Preview Image Edit --}}
                                            <div class="md:col-span-2 text-center">
                                                <img id="previewEdit{{ $doc->id }}"
                                                    src="{{ asset('storage/' . $doc->file_path) }}"
                                                    class="max-h-48 mx-auto rounded border mb-2">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Ganti File
                                                    (Opsional)
                                                </label>
                                                <input type="file" name="file_path"
                                                    onchange="updatePreview(event, 'previewEdit{{ $doc->id }}')"
                                                    class="w-full text-sm text-gray-500">
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium mb-1">CIF</label>
                                                <input type="text" name="cif" value="{{ $doc->cif }}"
                                                    class="w-full rounded-lg border-gray-300" required>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium mb-1">Kategori</label>
                                                <select name="category" class="w-full rounded-lg border-gray-300"
                                                    required>
                                                    <option value="" disabled>Pilih Kategori...</option>
                                                    <option value="Form Rekening"
                                                        {{ $doc->category == 'Form Rekening' ? 'selected' : '' }}>Form
                                                        Rekening</option>
                                                    <option value="Keluhan Nasabah"
                                                        {{ $doc->category == 'Keluhan Nasabah' ? 'selected' : '' }}>
                                                        Keluhan Nasabah</option>
                                                    <option value="Lainnya"
                                                        {{ $doc->category == 'Lainnya' ? 'selected' : '' }}>Lainnya
                                                    </option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium mb-1">Tanggal</label>
                                                <input type="date" name="document_date"
                                                    value="{{ $doc->document_date }}"
                                                    class="w-full rounded-lg border-gray-300" required>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium mb-1">Nomor Dokumen</label>
                                                <input type="text" name="document_number"
                                                    value="{{ $doc->document_number }}"
                                                    class="w-full rounded-lg border-gray-300" required>
                                            </div>

                                            {{-- Lokasi Dropdowns --}}
                                            <div>
                                                <label class="block text-sm font-medium mb-1">Lemari</label>
                                                <select name="cabinet" class="w-full rounded-lg border-gray-300">
                                                    <option value="" disabled>Pilih Lemari...</option>
                                                    @foreach (['A', 'B', 'C', 'D'] as $c)
                                                        <option value="{{ $c }}"
                                                            {{ $doc->cabinet == $c ? 'selected' : '' }}>Lemari
                                                            {{ $c }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium mb-1">Rak</label>
                                                <select name="shelf" class="w-full rounded-lg border-gray-300">
                                                    <option value="" disabled>Pilih Rak...</option>
                                                    @foreach (range(1, 5) as $r)
                                                        <option value="{{ $r }}"
                                                            {{ $doc->shelf == $r ? 'selected' : '' }}>Rak
                                                            {{ $r }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium mb-1">Kotak</label>
                                                <select name="box" class="w-full rounded-lg border-gray-300">
                                                    <option value="" disabled>Pilih Kotak...</option>
                                                    @foreach (range(1, 10) as $b)
                                                        <option value="{{ $b }}"
                                                            {{ $doc->box == $b ? 'selected' : '' }}>Kotak
                                                            {{ $b }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="md:col-span-2">
                                                <label class="block text-sm font-medium mb-1">Keterangan</label>
                                                <textarea name="description" rows="2" class="w-full rounded-lg border-gray-300">{{ $doc->description }}</textarea>
                                            </div>
                                        </div>

                                        <div class="p-5 border-t bg-gray-50 flex justify-end gap-2">
                                            <button type="button"
                                                onclick="toggleEditMode('{{ $doc->id }}', false)"
                                                class="px-4 py-2 border rounded-lg bg-white">Batal</button>
                                            <button type="submit"
                                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan
                                                Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr class="border-b hover:bg-gray-50 document-row" data-status="{{ $doc->status ?? '' }}"
                            data-date="{{ isset($doc) ? $doc->created_at->format('Y-m-d') : '' }}"
                            data-today="{{ isset($doc) && $doc->created_at->isToday() ? 'yes' : 'no' }}">
                            <td colspan="6" id="empty-row" class="text-center py-6 text-gray-500">Belum ada
                                dokumen.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{-- Pesan jika tidak ada hasil filter --}}
            <div id="no-results" class="hidden text-center py-6 text-gray-500">
                Tidak ada dokumen yang sesuai dengan filter.
            </div>
        </div>
    </div>

    {{-- MODAL UPLOAD CS --}}
    <div id="uploadModalCs" class="fixed inset-0 bg-black/40 hidden items-center justify-center p-4 z-50">
        <div class="bg-white w-full max-w-2xl rounded-xl border shadow-lg max-h-[90vh] overflow-y-auto">
            <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="source" value="cs">

                <div class="p-5 border-b flex items-center justify-between">
                    <h3 class="text-lg font-semibold">Upload Dokumen CS</h3>
                    <button type="button" onclick="closeModal('uploadModalCs')"
                        class="text-2xl text-gray-500">&times;</button>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Preview Image Upload --}}
                    <div class="md:col-span-2 flex flex-col items-center">
                        <div
                            class="w-full h-40 bg-gray-100 border border-dashed rounded-lg flex items-center justify-center mb-2 overflow-hidden">
                            <img id="preview-upload-cs" class="hidden h-full object-contain">
                            <span id="preview-placeholder-cs" class="text-gray-400 text-sm">Preview Gambar</span>
                        </div>
                        <input type="file" name="file_path" onchange="previewImage(event, 'preview-upload-cs')"
                            class="w-full text-sm text-gray-500" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">CIF</label>
                        <input type="text" name="cif" class="w-full rounded-lg border-gray-300" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Kategori</label>
                        <select name="category" class="w-full rounded-lg border-gray-300" required>
                            <option value="" selected disabled>Pilih Kategori...</option>
                            <option value="Form Rekening">Form Rekening</option>
                            <option value="Keluhan Nasabah">Keluhan Nasabah</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Tanggal</label>
                        <input type="date" name="document_date" class="w-full rounded-lg border-gray-300"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">No Dokumen</label>
                        <input type="text" name="document_number" class="w-full rounded-lg border-gray-300"
                            required>
                    </div>

                    <div class="md:col-span-2 border-t pt-2 mt-2 font-semibold text-gray-700">Lokasi Fisik</div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Lemari</label>
                        <select name="cabinet" class="w-full rounded-lg border-gray-300" required>
                            <option value="" selected disabled>Pilih Lemari...</option>
                            <option value="A">Lemari A</option>
                            <option value="B">Lemari B</option>
                            <option value="C">Lemari C</option>
                            <option value="D">Lemari D</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Rak</label>
                        <select name="shelf" class="w-full rounded-lg border-gray-300" required>
                            <option value="" selected disabled>Pilih Rak...</option>
                            @foreach (range(1, 5) as $i)
                                <option value="{{ $i }}">Rak {{ $i }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Kotak</label>
                        <select name="box" class="w-full rounded-lg border-gray-300" required>
                            <option value="" selected disabled>Pilih Kotak...</option>
                            @foreach (range(1, 10) as $i)
                                <option value="{{ $i }}">Kotak {{ $i }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">Keterangan</label>
                        <input type="text" name="description" class="w-full rounded-lg border-gray-300">
                    </div>
                </div>

                <div class="p-5 border-t bg-gray-50 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('uploadModalCs')"
                        class="px-4 py-2 border rounded-lg bg-white">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Upload</button>
                </div>
            </form>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script>
        // State untuk filter yang aktif
        let currentFilter = 'all';

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
            // Pastikan selalu ke mode view saat pertama dibuka
            toggleEditMode(id, false);
        }

        function closeDetail(id) {
            closeModal('detailModal-' + id);
            // Reset ke mode view saat ditutup
            toggleEditMode(id, false);
        }

        // Fungsi utama untuk toggle antara view dan edit
        function toggleEditMode(id, showEdit) {
            const viewSection = document.getElementById('viewSection' + id);
            const editSection = document.getElementById('editSection' + id);

            if (showEdit) {
                // Tampilkan form edit, sembunyikan view
                viewSection.classList.add('hidden');
                editSection.classList.remove('hidden');
            } else {
                // Tampilkan view, sembunyikan form edit
                viewSection.classList.remove('hidden');
                editSection.classList.add('hidden');
            }
        }

        // Fungsi untuk preview gambar saat upload
        function previewImage(event, previewId) {
            const file = event.target.files[0];
            const img = document.getElementById(previewId);
            const placeholder = document.getElementById('preview-placeholder-cs');

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

        // Fungsi untuk preview gambar di form edit
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
        // FUNGSI FILTER DOKUMEN
        function filterDocuments(filter) {
            currentFilter = filter;
            const rows = document.querySelectorAll('.document-row');
            const emptyRow = document.getElementById('empty-row');
            const noResults = document.getElementById('no-results');
            const tableTitle = document.getElementById('table-title');
            const filteredCount = document.getElementById('filtered-count');

            // Update active card styling
            document.querySelectorAll('.filter-card').forEach(card => {
                card.classList.remove('active-filter');
            });
            document.getElementById('card-' + filter).classList.add('active-filter');

            let visibleCount = 0;
            const today = new Date().toISOString().split('T')[0];

            rows.forEach(row => {
                let shouldShow = false;

                switch (filter) {
                    case 'all':
                        shouldShow = true;
                        tableTitle.textContent = 'Semua Dokumen Customer Service';
                        break;
                    case 'approved':
                        shouldShow = row.dataset.status === 'approved';
                        tableTitle.textContent = 'Dokumen Approved';
                        break;
                        // UBAHAN: Case Pending diganti Rejected
                    case 'rejected':
                        shouldShow = row.dataset.status === 'rejected';
                        tableTitle.textContent = 'Dokumen Ditolak';
                        break;
                    case 'today':
                        shouldShow = row.dataset.today === 'yes';
                        tableTitle.textContent = 'Upload Hari Ini';
                        break;
                }

                if (shouldShow) {
                    row.classList.remove('hidden');
                    visibleCount++;
                } else {
                    row.classList.add('hidden');
                }
            });

            // Update counter
            if (filter !== 'all') {
                filteredCount.textContent = `(${visibleCount} dokumen)`;
            } else {
                filteredCount.textContent = '';
            }

            // Handle empty state
            if (emptyRow) {
                // Pastikan empty row dihandle dengan benar
                // Jika tidak ada data sama sekali (sejak awal), emptyRow tetap muncul jika visibleCount 0 di 'all'
                // Jika ada data tapi terfilter semua, emptyRow harus hidden, noResults muncul
                if (rows.length === 1 && rows[0].id === 'empty-row') {
                    // Kasus khusus: tabel memang kosong dari database
                    emptyRow.classList.remove('hidden');
                    noResults.classList.add('hidden');
                } else {
                    emptyRow.classList.add('hidden');
                }
            }

            if (visibleCount === 0) {
                // Cek lagi jika ini bukan empty row bawaan
                if (!(rows.length === 1 && rows[0].id === 'empty-row')) {
                    noResults.classList.remove('hidden');
                }
            } else {
                noResults.classList.add('hidden');
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
    </script>
</x-app-shell>
