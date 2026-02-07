<x-app-shell title="Verifikasi" header="Verifikasi Dokumen">
    {{-- Header --}}
    <div class="bg-white rounded-xl border p-6">
        <div class="text-sm text-gray-500">Supervisor</div>
        <div class="text-xl font-semibold">Verifikasi Dokumen Pending</div>
        <div class="text-sm text-gray-600 mt-1">
            Review dokumen yang di-upload Admin lalu lakukan approve atau reject.
        </div>
    </div>

    {{-- Filter + Quick Stats --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        {{-- Filter --}}
        <div class="lg:col-span-2 bg-white rounded-xl border p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Filter</h3>
                <span class="text-xs text-gray-500">UI dulu (belum fungsi)</span>
            </div>

            <form class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Sumber</label>
                    <select class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option>Semua</option>
                        <option>Teller</option>
                        <option>Customer Service</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Kategori</label>
                    <select class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option>Semua</option>
                        <option>Slip Setoran</option>
                        <option>Bukti Transfer</option>
                        <option>Bukti Penarikan</option>
                        <option>Form Rekening</option>
                        <option>Keluhan Nasabah</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Tanggal</label>
                    <input type="date" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-600 mb-1">Search No Dokumen</label>
                    <input type="text" placeholder="contoh: SLP-021 / TRF-019"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
                </div>

                <div class="flex items-end justify-end gap-2">
                    <button type="button" class="px-4 py-2 rounded-lg border bg-white hover:bg-gray-50 text-sm">
                        Reset
                    </button>
                    <button type="button" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm">
                        Terapkan
                    </button>
                </div>
            </form>
        </div>

        {{-- Quick info --}}
        <div class="bg-white rounded-xl border p-6">
            <h3 class="text-lg font-semibold mb-4">Ringkasan</h3>

            <div class="space-y-3 text-sm">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Pending</span>
                    <span class="font-semibold">12</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Approved hari ini</span>
                    <span class="font-semibold">8</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Rejected hari ini</span>
                    <span class="font-semibold">1</span>
                </div>
            </div>

            <div class="mt-5 p-4 rounded-lg bg-gray-50 text-sm text-gray-600">
                Tips: Klik <b>Detail</b> untuk cek file + lokasi fisik, lalu approve/reject.
            </div>
        </div>
    </div>

    {{-- Table Pending --}}
    @php
        $rows = [
            [
                'no' => 'SLP-021',
                'kategori' => 'Slip Setoran',
                'sumber' => 'Teller',
                'tanggal' => '2026-02-04',
                'lokasi' => 'Lemari A • Rak 2 • Kotak 7',
                'uploader' => 'Admin',
                'status' => 'Pending',
            ],
            [
                'no' => 'TRF-019',
                'kategori' => 'Bukti Transfer',
                'sumber' => 'Teller',
                'tanggal' => '2026-02-04',
                'lokasi' => 'Lemari A • Rak 2 • Kotak 7',
                'uploader' => 'Admin',
                'status' => 'Pending',
            ],
            [
                'no' => 'KEL-007',
                'kategori' => 'Keluhan Nasabah',
                'sumber' => 'CS',
                'tanggal' => '2026-02-04',
                'lokasi' => 'Lemari B • Rak 1 • Kotak 3',
                'uploader' => 'Admin',
                'status' => 'Pending',
            ],
        ];
    @endphp

    <div class="bg-white rounded-xl border p-6 mt-6">
        {{-- Header + Bulk Actions --}}
        <div class="flex flex-col gap-3 mb-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <h3 class="text-lg font-semibold">Daftar Dokumen Pending</h3>

                <div class="text-sm text-gray-500">
                    Menampilkan {{ count($rows) }} dokumen (dummy)
                </div>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="text-sm text-gray-600">
                    Dipilih: <span id="selectedCount" class="font-semibold">0</span>
                </div>

                <div class="flex flex-wrap gap-2 sm:justify-end">
                    <button id="btnApproveSelected" type="button"
                        class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 text-sm disabled:opacity-40 disabled:cursor-not-allowed"
                        disabled>
                        Approve Selected
                    </button>

                    <button id="btnApproveAll" type="button"
                        class="px-4 py-2 rounded-lg border bg-white hover:bg-gray-50 text-sm">
                        Approve All
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b text-gray-500">
                        <th class="text-left py-3 pr-4 w-10">
                            <input id="checkAll" type="checkbox"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
                        </th>
                        <th class="text-left py-3 pr-4">No Dokumen</th>
                        <th class="text-left py-3 pr-4">Kategori</th>
                        <th class="text-left py-3 pr-4">Sumber</th>
                        <th class="text-left py-3 pr-4">Tanggal</th>
                        <th class="text-left py-3 pr-4">Lokasi Fisik</th>
                        <th class="text-left py-3 pr-4">Uploader</th>
                        <th class="text-left py-3 pr-4">Status</th>
                        <th class="text-left py-3 pr-4">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($rows as $r)
                        <tr class="border-b">
                            <td class="py-3 pr-4">
                                <input type="checkbox"
                                    class="rowCheck rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    value="{{ $r['no'] }}" />
                            </td>

                            <td class="py-3 pr-4 font-medium">{{ $r['no'] }}</td>
                            <td class="py-3 pr-4">{{ $r['kategori'] }}</td>
                            <td class="py-3 pr-4">{{ $r['sumber'] }}</td>
                            <td class="py-3 pr-4">{{ $r['tanggal'] }}</td>
                            <td class="py-3 pr-4 text-gray-600">{{ $r['lokasi'] }}</td>
                            <td class="py-3 pr-4">{{ $r['uploader'] }}</td>

                            <td class="py-3 pr-4">
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">
                                    Pending
                                </span>
                            </td>

                            <td class="py-3 pr-4">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('dokumen.show', $r['no']) }}"
                                            class="px-3 py-2 text-sm rounded-lg border bg-white hover:bg-gray-50">
                                                    Detail
                                    </a>

                                    <button type="button"
                                        class="px-3 py-2 text-sm rounded-lg bg-green-600 text-white hover:bg-green-700">
                                        Approve
                                    </button>

                                    <button type="button"
                                        class="px-3 py-2 text-sm rounded-lg bg-red-600 text-white hover:bg-red-700"
                                        onclick="openRejectModal('{{ $r['no'] }}')">
                                        Reject
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="text-xs text-gray-500 mt-4">
            *Approve/Reject masih UI, nanti disambung ke database + log aktivitas + notifikasi.
        </div>
    </div>

    {{-- Reject Modal (Tailwind) --}}
    <div id="rejectModal"
        class="fixed inset-0 bg-black/40 hidden items-center justify-center p-4 z-50">
        <div class="bg-white w-full max-w-lg rounded-xl border shadow-lg">
            <div class="p-5 border-b flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500">Reject Dokumen</div>
                    <div class="text-lg font-semibold" id="rejectTitle">-</div>
                </div>
                <button type="button" class="text-gray-500 hover:text-gray-700" onclick="closeRejectModal()">
                    ✕
                </button>
            </div>

            <div class="p-5 space-y-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Catatan Reject</label>
                    <textarea id="rejectNote" rows="4"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        placeholder="contoh: file blur / nomor dokumen tidak sesuai / lokasi fisik belum diisi"></textarea>
                    <p class="text-xs text-gray-500 mt-1">
                        Catatan ini nanti akan dikirim ke Admin melalui notifikasi.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-2 sm:justify-end">
                    <button type="button"
                        class="px-4 py-2 rounded-lg border bg-white hover:bg-gray-50 text-sm"
                        onclick="closeRejectModal()">
                        Batal
                    </button>

                    <button type="button"
                        class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 text-sm">
                        Konfirmasi Reject (Dummy)
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ===== Reject Modal =====
        const modal = document.getElementById('rejectModal');
        const title = document.getElementById('rejectTitle');
        const note = document.getElementById('rejectNote');

        function openRejectModal(noDok) {
            title.textContent = noDok;
            note.value = '';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeRejectModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeRejectModal();
        });

        // ===== Bulk Approve UI =====
        const checkAll = document.getElementById('checkAll');
        const rowChecks = () => document.querySelectorAll('.rowCheck');
        const selectedCount = document.getElementById('selectedCount');
        const btnApproveSelected = document.getElementById('btnApproveSelected');
        const btnApproveAll = document.getElementById('btnApproveAll');

        function updateSelectedUI() {
            const checked = Array.from(rowChecks()).filter(ch => ch.checked);
            selectedCount.textContent = checked.length;
            btnApproveSelected.disabled = checked.length === 0;

            const all = rowChecks().length;
            if (all === 0) {
                checkAll.checked = false;
                checkAll.indeterminate = false;
                return;
            }

            if (checked.length === 0) {
                checkAll.checked = false;
                checkAll.indeterminate = false;
            } else if (checked.length === all) {
                checkAll.checked = true;
                checkAll.indeterminate = false;
            } else {
                checkAll.checked = false;
                checkAll.indeterminate = true;
            }
        }

        checkAll?.addEventListener('change', () => {
            rowChecks().forEach(ch => ch.checked = checkAll.checked);
            updateSelectedUI();
        });

        document.addEventListener('change', (e) => {
            if (e.target.classList.contains('rowCheck')) {
                updateSelectedUI();
            }
        });

        btnApproveSelected?.addEventListener('click', () => {
            const docs = Array.from(rowChecks()).filter(ch => ch.checked).map(ch => ch.value);
            alert('Approve selected (dummy): ' + docs.join(', '));
        });

        btnApproveAll?.addEventListener('click', () => {
            const docs = Array.from(rowChecks()).map(ch => ch.value);
            alert('Approve ALL (dummy): ' + docs.join(', '));
        });

        updateSelectedUI();
    </script>
</x-app-shell>
