<x-app-shell title="Upload Dokumen" header="Upload Dokumen">
    {{-- Header --}}
    <div class="bg-white rounded-xl border p-6">
        <div class="text-sm text-gray-500">Dokumen</div>
        <div class="text-xl font-semibold">Form Upload Dokumen Arsip</div>
        <div class="text-sm text-gray-600 mt-1">
            Isi metadata dokumen + lokasi fisik penyimpanan.
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        {{-- Form --}}
        <div class="lg:col-span-2 bg-white rounded-xl border p-6">
            <h3 class="text-lg font-semibold mb-4">Data Dokumen</h3>

            <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                {{-- Row 1: Sumber & Kategori (Posisi Sumber dipindah ke atas agar alur logis) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- 1. Sumber Dokumen --}}
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Sumber Dokumen</label>
                        <select id="sumber_dokumen" name="source"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Pilih sumber</option>
                            <option value="teller">Teller</option>
                            <option value="cs">Customer Service (CS)</option>
                        </select>
                    </div>

                    {{-- 2. Kategori Dokumen (Isi dinamis by JS) --}}
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Kategori Dokumen</label>
                        <select id="kategori" name="category"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            disabled>
                            <option value="">Pilih sumber dahulu</option>
                        </select>
                    </div>
                </div>

                {{-- Row 2: Nomor & Tanggal --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- 3. Nomor Dokumen (Otomatis) --}}
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Nomor Dokumen</label>
                        {{-- Input dibuat readonly agar user tidak salah edit format, tapi tetap bisa submit --}}
                        <input id="nomor_dokumen" name="document_number" type="text" placeholder="Otomatis terisi..."
                            class="w-full rounded-lg border-gray-300 bg-gray-100 cursor-not-allowed focus:border-blue-500 focus:ring-blue-500"
                            readonly />
                        <p class="text-xs text-gray-500 mt-1">Nomor digenerate otomatis berdasarkan kategori.</p>
                    </div>

                    {{-- 4. Tanggal --}}
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Tanggal Dokumen</label>
                        <input type="date" name="document_date"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
                    </div>
                </div>

                {{-- Khusus CS: CIF (Opsional/Muncul jika CS) --}}
                <div id="cif_field" class="hidden">
                    <label class="block text-sm text-gray-600 mb-1">Nomor CIF</label>
                    <input type="text" name="cif" placeholder="Masukkan nomor CIF Nasabah"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
                </div>

                {{-- Upload --}}
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Upload File Dokumen</label>
                    <input type="file" name="file_path"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
                    <p class="text-xs text-gray-500 mt-1">
                        Format: PDF/JPG/PNG • Maks 2MB
                    </p>
                </div>

                {{-- Lokasi fisik --}}
                <div class="border-t pt-4">
                    <h4 class="font-semibold mb-3">Lokasi Penyimpanan Fisik</h4>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Lemari</label>
                            <select id="lemari" name="cabinet"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Pilih lemari</option>
                                <option value="A">Lemari A</option>
                                <option value="B">Lemari B</option>
                                <option value="C">Lemari C</option>
                                <option value="D">Lemari D</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Rak</label>
                            <select id="rak" name="shelf"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                disabled>
                                <option value="">Pilih rak</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Kotak</label>
                            <select id="kotak" name="box"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                disabled>
                                <option value="">Pilih kotak</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Keterangan --}}
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Keterangan (Opsional)</label>
                    <textarea name="description" rows="3"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Tambahkan catatan jika perlu..."></textarea>
                </div>

                {{-- Buttons --}}
                <div class="flex flex-col sm:flex-row gap-2 sm:justify-end pt-2">
                    <a href="{{ route('dashboard') }}"
                        class="px-4 py-2 rounded-lg border bg-white hover:bg-gray-50 text-sm text-center">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm">
                        Simpan Dokumen
                    </button>
                </div>
            </form>
        </div>

        {{-- Side info --}}
        <div class="bg-white rounded-xl border p-6 h-fit">
            <h3 class="text-lg font-semibold mb-3">Panduan Singkat</h3>
            <ol class="list-decimal pl-5 text-sm text-gray-700 space-y-2">
                <li>Pilih <b>Sumber Dokumen</b> (Teller/CS) terlebih dahulu.</li>
                <li>Pilih <b>Kategori</b>, nomor dokumen akan muncul otomatis.</li>
                <li>Upload file digital.</li>
                <li>Tentukan lokasi penyimpanan fisik.</li>
            </ol>
        </div>
    </div>

    {{-- JAVASCRIPT LOGIC --}}
    <script>
        // Data Kategori sesuai permintaan
        const categoriesData = {
            'cs': [{
                    code: 'FPR',
                    name: 'Form pembukaan rekening (FPR)'
                },
                {
                    code: 'PDN',
                    name: 'Form perubahan data nasabah (PDN)'
                },
                {
                    code: 'FPTR',
                    name: 'Form penutupan rekening (FPTR)'
                },
                {
                    code: 'FPL',
                    name: 'Form Layanan kartu & digital banking (FPL)'
                }
            ],
            'teller': [{
                    code: 'TL-ST',
                    name: 'Transaksi setoran dan penarikan (TL-ST)'
                },
                {
                    code: 'TL-TP',
                    name: 'Transaksi transfer dan pembayaran (TL-TP)'
                },
                {
                    code: 'TL-GK',
                    name: 'Transaksi Giro, Kliring, Valuta (TL-GK)'
                },
                {
                    code: 'TL-LA',
                    name: 'Laporan dan Administrasi teller (TL-LA)'
                }
            ]
        };

        // Elements
        const sourceSelect = document.getElementById('sumber_dokumen');
        const categorySelect = document.getElementById('kategori');
        const docNumberInput = document.getElementById('nomor_dokumen');
        const cifField = document.getElementById('cif_field');

        // 1. Logic Ganti Sumber (Teller/CS)
        sourceSelect.addEventListener('change', function() {
            const source = this.value;

            // Reset Dropdown Kategori
            categorySelect.innerHTML = '<option value="">Pilih kategori</option>';
            categorySelect.disabled = true;
            docNumberInput.value = '';

            // Toggle Field CIF (Hanya untuk CS)
            if (source === 'cs') {
                cifField.classList.remove('hidden');
            } else {
                cifField.classList.add('hidden');
            }

            if (source && categoriesData[source]) {
                categorySelect.disabled = false;

                // Populate options
                categoriesData[source].forEach(cat => {
                    const option = document.createElement('option');
                    option.value = cat.code; // Value yang dikirim ke DB adalah KODE (e.g., TL-ST)
                    option.textContent = cat.name;
                    categorySelect.appendChild(option);
                });
            }
        });

        // 2. Logic Ganti Kategori -> Auto Generate Number (AJAX)
        categorySelect.addEventListener('change', function() {
            const categoryCode = this.value;

            if (!categoryCode) {
                docNumberInput.value = '';
                return;
            }

            // Tampilkan loading/placeholder
            docNumberInput.value = 'Mengambil nomor...';

            // Fetch ke Backend
            fetch(`{{ route('documents.generate-number') }}?prefix=${categoryCode}`)
                .then(response => response.json())
                .then(data => {
                    if (data.number) {
                        docNumberInput.value = data.number;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    docNumberInput.value = 'Gagal mengambil nomor';
                });
        });

        // --- Logic Lemari/Rak (Existing Code) ---
        const lemari = document.getElementById('lemari');
        const rak = document.getElementById('rak');
        const kotak = document.getElementById('kotak');

        function resetSelect(selectEl, placeholder) {
            selectEl.innerHTML = `<option value="">${placeholder}</option>`;
            selectEl.disabled = true;
        }

        lemari.addEventListener('change', () => {
            resetSelect(rak, 'Pilih rak');
            resetSelect(kotak, 'Pilih kotak');
            if (!lemari.value) return;
            rak.disabled = false;
            for (let i = 1; i <= 5; i++) {
                const opt = document.createElement('option');
                opt.value = i;
                opt.textContent = `Rak ${i}`;
                rak.appendChild(opt);
            }
        });

        rak.addEventListener('change', () => {
            resetSelect(kotak, 'Pilih kotak');
            if (!rak.value) return;
            kotak.disabled = false;
            for (let i = 1; i <= 10; i++) {
                const opt = document.createElement('option');
                opt.value = i;
                opt.textContent = `Kotak ${i}`;
                kotak.appendChild(opt);
            }
        });
    </script>
</x-app-shell>
