<x-app-shell title="Aktivitas Sistem" header="Activity Log">
    {{-- Header --}}
    <div class="bg-white rounded-xl border p-6">
        <div class="text-sm text-gray-500">Pemberitahuan</div>
        <div class="text-xl font-semibold">Activity & Notifications</div>
        <div class="text-sm text-gray-600 mt-1">
            Riwayat aktivitas pengguna, upload dokumen, verifikasi, dan status sistem.
        </div>
    </div>

    @if (session('success'))
        <div class="mt-4 p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
    @endif

    {{-- Toolbar --}}
    <div class="bg-white rounded-xl border p-6 mt-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            {{-- Tabs Filter --}}
            <div class="flex flex-wrap gap-2">
                @php
                    $currentFilter = request('filter', 'all');
                    $tabs = [
                        ['id' => 'all', 'label' => 'Semua'],
                        ['id' => 'unread', 'label' => 'Unread'],
                        ['id' => 'info', 'label' => 'Info'],
                        ['id' => 'success', 'label' => 'Success'],
                        ['id' => 'warning', 'label' => 'Warning'],
                        ['id' => 'danger', 'label' => 'Danger'],
                    ];
                @endphp

                @foreach ($tabs as $t)
                    <a href="{{ route('activity.index', array_merge(request()->except('page'), ['filter' => $t['id']])) }}"
                        class="px-3 py-2 rounded-lg text-sm border transition-colors
                        {{ $currentFilter == $t['id'] ? 'bg-blue-50 border-blue-200 text-blue-700 font-medium' : 'bg-white hover:bg-gray-50 text-gray-600' }}">
                        {{ $t['label'] }}
                    </a>
                @endforeach
            </div>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row gap-2 sm:justify-end">
                <form action="{{ route('activity.readAll') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full sm:w-auto px-4 py-2 rounded-lg border bg-white hover:bg-gray-50 text-sm text-gray-700">
                        Tandai semua dibaca
                    </button>
                </form>
                <a href="{{ route('activity.index') }}"
                    class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm text-center">
                    Refresh
                </a>
            </div>
        </div>

        {{-- Search --}}
        <form action="{{ route('activity.index') }}" method="GET" class="mt-4">
            @if (request('filter'))
                <input type="hidden" name="filter" value="{{ request('filter') }}">
            @endif
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari aktivitas (contoh: SLP-021, approve, upload)..."
                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 pl-4 pr-10" />
                <button type="submit" class="absolute right-2 top-2 text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
        </form>
    </div>

    {{-- List Activity --}}
    @php
        $styles = [
            'info' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'dot' => 'bg-blue-600'],
            'success' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'dot' => 'bg-green-600'],
            'warning' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'dot' => 'bg-yellow-500'],
            'danger' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'dot' => 'bg-red-600'],
        ];
    @endphp

    <div class="mt-6 space-y-3" id="activity-table-container">
        @forelse($activities as $log)
            @php
                $type = $log->type;
                $s = $styles[$type] ?? $styles['info'];
            @endphp

            <div
                class="bg-white rounded-xl border p-5 flex gap-4 items-start {{ !$log->is_read ? 'border-l-4 border-l-blue-500 shadow-sm' : '' }}">
                {{-- Icon/dot --}}
                <div class="mt-1.5">
                    <div class="w-3 h-3 rounded-full {{ $s['dot'] }}"></div>
                </div>

                {{-- Content --}}
                <div class="flex-1">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span
                                class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-full {{ $s['bg'] }} {{ $s['text'] }}">
                                {{ $type }}
                            </span>

                            <div class="font-semibold text-gray-900">
                                {{ $log->action }}
                            </div>

                            @if (!$log->is_read)
                                <span
                                    class="text-[10px] px-2 py-0.5 rounded-full bg-blue-50 text-blue-600 border border-blue-100 font-medium">
                                    Baru
                                </span>
                            @endif
                        </div>

                        <div class="text-xs text-gray-500 whitespace-nowrap">
                            {{ $log->created_at->diffForHumans() }}
                        </div>
                    </div>

                    <div class="text-sm text-gray-600 mt-1 line-clamp-1">
                        {{ $log->details }}
                    </div>

                    <div class="text-xs text-gray-400 mt-1">
                        Oleh: {{ $log->user->name ?? 'System' }}
                    </div>

                    {{-- Actions Per Item --}}
                    <div class="mt-3 flex flex-wrap gap-2">
                        {{-- Tombol Lihat Detail (Memanggil Modal JS) --}}
                        <button type="button"
                            onclick="openActivityModal('{{ $log->action }}', {{ json_encode($log->details) }}, '{{ $log->user->name ?? 'System' }}', '{{ $log->created_at->format('d M Y, H:i') }}', '{{ $type }}')"
                            class="px-3 py-1.5 text-xs rounded-lg border bg-white hover:bg-gray-50 text-blue-600 font-medium">
                            Lihat detail
                        </button>

                        @if (!$log->is_read)
                            <form action="{{ route('activity.read', $log->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="px-3 py-1.5 text-xs rounded-lg border bg-white hover:bg-gray-50 text-gray-600">
                                    Tandai dibaca
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white rounded-xl border">
                <div class="text-gray-400 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Tidak ada aktivitas</h3>
                <p class="text-gray-500 text-sm">Belum ada notifikasi yang sesuai dengan filter ini.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $activities->links() }}
    </div>

    {{-- MODAL DETAIL ACTIVITY --}}
    <div id="activityModal"
        class="fixed inset-0 bg-black/50 hidden items-center justify-center p-4 z-50 backdrop-blur-sm transition-opacity">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl transform transition-all scale-100">
            {{-- Header Modal --}}
            <div class="p-5 border-b flex items-center justify-between bg-gray-50 rounded-t-2xl">
                <div>
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wide">Detail Aktivitas</div>
                    <div class="text-lg font-bold text-gray-900" id="modalAction">Action Title</div>
                </div>
                <button type="button" onclick="closeActivityModal()"
                    class="text-gray-400 hover:text-gray-600 rounded-full p-2 hover:bg-gray-200 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Body Modal --}}
            <div class="p-6 space-y-4">
                {{-- Badge Tipe --}}
                <div>
                    <span id="modalType" class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                        TYPE
                    </span>
                </div>

                {{-- Detail Konten --}}
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Keterangan / Detail</label>
                    <p id="modalDetails" class="text-gray-800 text-sm leading-relaxed whitespace-pre-wrap"></p>
                </div>

                {{-- Info Tambahan --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Dilakukan Oleh</label>
                        <div class="flex items-center gap-2">
                            <div
                                class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-xs font-bold">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <span id="modalUser" class="text-sm font-medium text-gray-900">User Name</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Waktu</label>
                        <span id="modalTime" class="text-sm font-medium text-gray-900">Date Time</span>
                    </div>
                </div>
            </div>

            {{-- Footer Modal --}}
            <div class="p-5 border-t bg-gray-50 rounded-b-2xl flex justify-end">
                <button type="button" onclick="closeActivityModal()"
                    class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium shadow-sm transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- SCRIPT MODAL --}}
    <script>
        function openActivityModal(action, details, user, time, type) {
            const modal = document.getElementById('activityModal');

            // Set Content
            document.getElementById('modalAction').innerText = action;
            document.getElementById('modalDetails').innerText = details;
            document.getElementById('modalUser').innerText = user;
            document.getElementById('modalTime').innerText = time;

            // Set Badge Style based on Type
            const badge = document.getElementById('modalType');
            badge.innerText = type;

            // Reset classes
            badge.className = 'px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider';

            if (type === 'danger') {
                badge.classList.add('bg-red-100', 'text-red-700');
            } else if (type === 'success') {
                badge.classList.add('bg-green-100', 'text-green-700');
            } else if (type === 'warning') {
                badge.classList.add('bg-yellow-100', 'text-yellow-700');
            } else {
                badge.classList.add('bg-blue-100', 'text-blue-700');
            }

            // Show Modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeActivityModal() {
            const modal = document.getElementById('activityModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        document.addEventListener('DOMContentLoaded', function() {
            activateRealtime('activity-table-container', 3000);
        });
    </script>
</x-app-shell>
