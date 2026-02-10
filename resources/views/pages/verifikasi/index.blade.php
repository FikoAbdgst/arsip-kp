<x-app-shell title="Verifikasi" header="Verifikasi Dokumen">
    {{-- Ringkasan --}}
    <div id="verifikasi-stats">
        <div class="bg-white rounded-xl border p-6 shadow-sm">
            <div class="flex justify-between items-start">
                <div>
                    <div class="text-sm text-gray-500 font-medium">Supervisor Area</div>
                    <div class="text-xl font-bold text-gray-900">Verifikasi Dokumen Pending</div>
                    <div class="text-sm text-gray-600 mt-1">
                        Review dokumen yang masuk. Pastikan data fisik dan digital sesuai sebelum melakukan Approval.
                    </div>
                </div>
                <div class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg font-semibold text-sm border border-blue-100">
                    {{ $pendingDocuments->count() }} Dokumen Menunggu
                </div>
            </div>
        </div>
    </div>

    {{-- Alert Success --}}
    @if (session('success'))
        <div class="mt-4 p-4 bg-green-100 text-green-700 rounded-lg border border-green-200 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Table Pending --}}
    <div id="verifikasi-table">
        <div class="bg-white rounded-xl border p-6 mt-6 shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 border-b">
                            <th class="text-left py-3 px-4 rounded-tl-lg font-semibold">Tanggal & User</th>
                            <th class="text-left py-3 px-4 font-semibold">Info Dokumen</th>
                            <th class="text-left py-3 px-4 font-semibold">Sumber</th>
                            <th class="text-left py-3 px-4 font-semibold">Lokasi Fisik</th>
                            <th class="text-center py-3 px-4 font-semibold">File</th>
                            <th class="text-center py-3 px-4 rounded-tr-lg font-semibold">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($pendingDocuments as $doc)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                {{-- Kolom Tanggal & User --}}
                                <td class="py-3 px-4 align-middle">
                                    <div class="font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($doc->created_at)->format('d M Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-0.5">
                                        Oleh: <span class="font-medium">{{ $doc->user->name ?? 'Unknown' }}</span>
                                    </div>
                                </td>

                                {{-- Kolom Info Dokumen --}}
                                <td class="py-3 px-4 align-middle">
                                    <div class="font-bold text-gray-800">{{ $doc->document_number }}</div>
                                    <div class="text-xs text-gray-500">{{ $doc->category }}</div>
                                    @if ($doc->source == 'cs')
                                        <div class="text-xs text-blue-600 font-medium mt-1">CIF: {{ $doc->cif }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Kolom Sumber --}}
                                <td class="py-3 px-4 align-middle">
                                    @if ($doc->source == 'teller')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                            Teller
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 border border-orange-200">
                                            CS
                                        </span>
                                    @endif
                                </td>

                                {{-- Kolom Lokasi --}}
                                <td class="py-3 px-4 align-middle text-gray-600">
                                    <div class="flex flex-col text-xs">
                                        <span class="font-medium">Lemari {{ $doc->cabinet }}</span>
                                        <span>Rak {{ $doc->shelf }} - Box {{ $doc->box }}</span>
                                    </div>
                                </td>

                                {{-- Kolom File --}}
                                <td class="py-3 px-4 align-middle text-center">
                                    <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 border border-blue-200 transition"
                                        title="Lihat File">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </td>

                                {{-- Kolom Aksi --}}
                                <td class="py-3 px-4 align-middle text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        {{-- Form Approve --}}
                                        <form action="{{ route('verification.approve', $doc->id) }}" method="POST"
                                            onsubmit="return confirm('Setujui dokumen ini?');">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg bg-green-600 text-white hover:bg-green-700 shadow-sm transition"
                                                title="Approve">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Approve
                                            </button>
                                        </form>

                                        {{-- Tombol Reject (Trigger Modal) --}}
                                        <button type="button"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg bg-red-600 text-white hover:bg-red-700 shadow-sm transition"
                                            onclick="openRejectModal('{{ $doc->id }}', '{{ $doc->document_number }}')"
                                            title="Reject">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Reject
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-12 text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mb-3"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="font-medium">Semua Bersih!</p>
                                        <p class="text-sm mt-1">Tidak ada dokumen pending yang perlu diverifikasi.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Reject Modal --}}
    <div id="rejectModal"
        class="fixed inset-0 bg-black/50 hidden items-center justify-center p-4 z-50 backdrop-blur-sm transition-opacity">
        <div class="bg-white w-full max-w-lg rounded-xl border shadow-2xl transform transition-all scale-100">
            {{-- Form Reject Dynamic Action --}}
            <form id="rejectForm" method="POST">
                @csrf
                <div class="p-5 border-b flex items-center justify-between bg-gray-50 rounded-t-xl">
                    <div>
                        <div class="text-xs font-bold text-red-600 uppercase tracking-wide">Konfirmasi Penolakan</div>
                        <div class="text-lg font-bold text-gray-800" id="rejectTitle">-</div>
                    </div>
                    <button type="button" class="text-gray-400 hover:text-gray-600 transition"
                        onclick="closeRejectModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div class="bg-red-50 text-red-700 p-3 rounded-lg text-sm border border-red-100">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        Dokumen yang ditolak akan dikembalikan ke status <strong>Rejected</strong> agar user dapat
                        memperbaikinya.
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Alasan Penolakan <span
                                class="text-red-500">*</span></label>
                        <textarea name="reason" rows="4"
                            class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500 shadow-sm" required
                            placeholder="Contoh: File hasil scan buram, Nomor dokumen tidak sesuai fisik, Salah kategori lemari..."></textarea>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 sm:justify-end pt-2">
                        <button type="button"
                            class="px-4 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 font-medium transition"
                            onclick="closeRejectModal()">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 font-medium shadow-sm transition flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                            Tolak Dokumen
                        </button>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Update counter dan tabel setiap 3 detik (lebih cepat karena penting)
            activateRealtime('verifikasi-stats', 3000);
            activateRealtime('verifikasi-table', 3000);
        });
    </script>
</x-app-shell>
