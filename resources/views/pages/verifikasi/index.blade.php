<x-app-shell title="Verifikasi" header="Verifikasi Dokumen">
    <div class="bg-white rounded-xl border p-6">
        <div class="text-sm text-gray-500">Supervisor</div>
        <div class="text-xl font-semibold">Verifikasi Dokumen Pending</div>
        <div class="text-sm text-gray-600 mt-1">
            Review dokumen yang di-upload Admin lalu lakukan approve atau reject.
        </div>
    </div>

    @if (session('success'))
        <div class="mt-4 p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
    @endif

    {{-- Table Pending --}}
    <div class="bg-white rounded-xl border p-6 mt-6">
        <h3 class="text-lg font-semibold mb-4">Daftar Dokumen Pending</h3>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b text-gray-500">
                        <th class="text-left py-3 pr-4">No Dokumen</th>
                        <th class="text-left py-3 pr-4">Kategori</th>
                        <th class="text-left py-3 pr-4">Sumber</th>
                        <th class="text-left py-3 pr-4">Lokasi Fisik</th>
                        <th class="text-left py-3 pr-4">File</th>
                        <th class="text-left py-3 pr-4">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($pendingDocuments as $doc)
                        <tr class="border-b">
                            <td class="py-3 pr-4 font-medium">{{ $doc->document_number }}</td>
                            <td class="py-3 pr-4">{{ $doc->category }}</td>
                            <td class="py-3 pr-4 uppercase">{{ $doc->source }}</td>
                            <td class="py-3 pr-4 text-gray-600">Lmr {{ $doc->cabinet }} / Rak {{ $doc->shelf }}</td>
                            <td class="py-3 pr-4">
                                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank"
                                    class="text-blue-600 hover:underline">Lihat</a>
                            </td>

                            <td class="py-3 pr-4">
                                <div class="flex flex-wrap gap-2">
                                    {{-- Form Approve --}}
                                    <form action="{{ route('verification.approve', $doc->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="px-3 py-2 text-sm rounded-lg bg-green-600 text-white hover:bg-green-700">
                                            Approve
                                        </button>
                                    </form>

                                    {{-- Tombol Reject (Trigger Modal) --}}
                                    <button type="button"
                                        class="px-3 py-2 text-sm rounded-lg bg-red-600 text-white hover:bg-red-700"
                                        onclick="openRejectModal('{{ $doc->id }}', '{{ $doc->document_number }}')">
                                        Reject
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-6 text-gray-500">Tidak ada dokumen pending saat
                                ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Reject Modal --}}
    <div id="rejectModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center p-4 z-50">
        <div class="bg-white w-full max-w-lg rounded-xl border shadow-lg">
            {{-- Form Reject Dynamic Action --}}
            <form id="rejectForm" method="POST">
                @csrf
                <div class="p-5 border-b flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-500">Reject Dokumen</div>
                        <div class="text-lg font-semibold" id="rejectTitle">-</div>
                    </div>
                    <button type="button" class="text-gray-500 hover:text-gray-700"
                        onclick="closeRejectModal()">✕</button>
                </div>

                <div class="p-5 space-y-4">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Alasan Reject</label>
                        <textarea name="reason" rows="4" class="w-full rounded-lg border-gray-300 focus:border-blue-500" required
                            placeholder="Contoh: File buram, salah lemari..."></textarea>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2 sm:justify-end">
                        <button type="button" class="px-4 py-2 rounded-lg border bg-white hover:bg-gray-50 text-sm"
                            onclick="closeRejectModal()">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 text-sm">Konfirmasi
                            Reject</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('rejectModal');
        const title = document.getElementById('rejectTitle');
        const form = document.getElementById('rejectForm');

        function openRejectModal(id, noDok) {
            title.textContent = noDok;
            // Set action form ke route reject dengan ID yg benar
            form.action = "/verification/" + id + "/reject";
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeRejectModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</x-app-shell>
