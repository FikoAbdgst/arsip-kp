<x-app-shell title="Upload Dokumen" header="Upload Dokumen">
    {{-- Header --}}
    <div class="bg-white rounded-xl border p-6">
        <div class="text-sm text-gray-500">Dokumen</div>
        <div class="text-xl font-semibold">Form Upload Dokumen Arsip</div>
        <div class="text-sm text-gray-600 mt-1">
            Isi metadata dokumen + lokasi fisik penyimpanan. (UI dulu)
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        {{-- Form --}}
        <div class="lg:col-span-2 bg-white rounded-xl border p-6">
            <h3 class="text-lg font-semibold mb-4">Data Dokumen</h3>

            <form class="space-y-4">
                {{-- Row 1 --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Kategori Dokumen</label>
                        <select id="kategori" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Pilih kategori</option>
                            <option value="SLP">Slip Setoran (SLP)</option>
                            <option value="TRF">Bukti Transfer (TRF)</option>
                            <option value="PNR">Bukti Penarikan (PNR)</option>
                            <option value="FRM">Form Rekening (FRM)</option>
                            <option value="KEL">Keluhan Nasabah (KEL)</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Prefix nomor bisa mengikuti kategori.</p>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Nomor Dokumen</label>
                        <input id="nomor_dokumen" type="text" placeholder="contoh: SLP-021"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
                        <p class="text-xs text-gray-500 mt-1">Boleh manual dulu. Nanti bisa auto-generate.</p>
                    </div>
                </div>

                {{-- Row 2 --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Tanggal Dokumen</label>
                        <input type="date" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Sumber Dokumen</label>
                        <select class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Pilih sumber</option>
                            <option value="Teller">Teller</option>
                            <option value="CS">Customer Service</option>
                        </select>
                    </div>
                </div>

                {{-- Upload --}}
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Upload File Dokumen</label>
                    <input type="file" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
                    <p class="text-xs text-gray-500 mt-1">
                        Format: PDF/DOC/DOCX/JPG/PNG • Maks 5MB
                    </p>
                </div>

                {{-- Lokasi fisik --}}
                <div class="border-t pt-4">
                    <h4 class="font-semibold mb-3">Lokasi Penyimpanan Fisik</h4>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Lemari</label>
                            <select id="lemari" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Pilih lemari</option>
                                <option value="A">Lemari A</option>
                                <option value="B">Lemari B</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Rak</label>
                            <select id="rak" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" disabled>
                                <option value="">Pilih rak</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Kotak</label>
                            <select id="kotak" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" disabled>
                                <option value="">Pilih kotak</option>
                            </select>
                        </div>
                    </div>

                    <p class="text-xs text-gray-500 mt-2">
                        Alur: pilih Lemari → Rak → Kotak. (UI + interaksi front-end dulu)
                    </p>
                </div>

                {{-- Keterangan --}}
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Keterangan (Opsional)</label>
                    <textarea rows="3" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        placeholder="contoh: Dokumen transaksi teller shift pagi"></textarea>
                </div>

                {{-- Buttons --}}
                <div class="flex flex-col sm:flex-row gap-2 sm:justify-end pt-2">
                    <a href="{{ route('dashboard') }}"
                    class="px-4 py-2 rounded-lg border bg-white hover:bg-gray-50 text-sm text-center">
                        Batal
                    </a>
                    <button type="button"
                        class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm">
                        Simpan (Dummy)
                    </button>
                </div>
            </form>
        </div>

        {{-- Side info --}}
        <div class="bg-white rounded-xl border p-6">
            <h3 class="text-lg font-semibold mb-3">Panduan Singkat</h3>

            <ol class="list-decimal pl-5 text-sm text-gray-700 space-y-2">
                <li>Pilih kategori untuk menentukan jenis dokumen.</li>
                <li>Isi nomor dokumen sesuai format (misal: SLP-021).</li>
                <li>Upload file digital untuk arsip server.</li>
                <li>Catat lokasi fisik dokumen (lemari → rak → kotak).</li>
                <li>Status awal akan <span class="font-medium">Pending</span> (menunggu verifikasi).</li>
            </ol>

            <div class="mt-6 p-4 rounded-lg bg-gray-50 text-sm text-gray-600">
                <div class="font-medium mb-1">Contoh lokasi</div>
                Lemari A • Rak 2 • Kotak 7
            </div>
        </div>
    </div>

    {{-- Script dropdown bertingkat (front-end only) --}}
    <script>
        const lemari = document.getElementById('lemari');
        const rak = document.getElementById('rak');
        const kotak = document.getElementById('kotak');

        function resetSelect(selectEl, placeholder) {
            selectEl.innerHTML = `<option value="">${placeholder}</option>`;
            selectEl.disabled = true;
        }

        // init
        resetSelect(rak, 'Pilih rak');
        resetSelect(kotak, 'Pilih kotak');

        lemari.addEventListener('change', () => {
            resetSelect(rak, 'Pilih rak');
            resetSelect(kotak, 'Pilih kotak');

            if (!lemari.value) return;

            // contoh rak 1-5
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

            // contoh kotak 1-10
            kotak.disabled = false;
            for (let i = 1; i <= 10; i++) {
                const opt = document.createElement('option');
                opt.value = i;
                opt.textContent = `Kotak ${i}`;
                kotak.appendChild(opt);
            }
        });

        // optional: auto isi prefix nomor dokumen
        const kategori = document.getElementById('kategori');
        const nomor = document.getElementById('nomor_dokumen');

        kategori.addEventListener('change', () => {
            if (!kategori.value) return;
            // kalau kosong, bantu isi prefix
            if (!nomor.value) nomor.value = `${kategori.value}-`;
        });
    </script>
</x-app-shell>
