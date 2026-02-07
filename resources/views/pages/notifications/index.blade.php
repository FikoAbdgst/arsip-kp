<x-app-shell title="Notifications" header="Notifications">
    {{-- Header --}}
    <div class="bg-white rounded-xl border p-6">
        <div class="text-sm text-gray-500">Pemberitahuan</div>
        <div class="text-xl font-semibold">Notifications</div>
        <div class="text-sm text-gray-600 mt-1">
            Notifikasi internal untuk upload dokumen, verifikasi, dan reminder.
        </div>
    </div>

    {{-- Toolbar --}}
    <div class="bg-white rounded-xl border p-6 mt-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            {{-- Tabs --}}
            <div class="flex flex-wrap gap-2">
                @php
                    $tabs = [
                        ['label' => 'Semua', 'active' => true],
                        ['label' => 'Unread', 'active' => false],
                        ['label' => 'Info', 'active' => false],
                        ['label' => 'Success', 'active' => false],
                        ['label' => 'Warning', 'active' => false],
                        ['label' => 'Danger', 'active' => false],
                    ];
                @endphp

                @foreach($tabs as $t)
                    <button type="button"
                        class="px-3 py-2 rounded-lg text-sm border
                        {{ $t['active'] ? 'bg-gray-100 font-medium' : 'bg-white hover:bg-gray-50' }}">
                        {{ $t['label'] }}
                    </button>
                @endforeach
            </div>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row gap-2 sm:justify-end">
                <button type="button"
                    class="px-4 py-2 rounded-lg border bg-white hover:bg-gray-50 text-sm">
                    Tandai semua dibaca
                </button>
                <button type="button"
                    class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm">
                    Refresh
                </button>
            </div>
        </div>

        {{-- Search --}}
        <div class="mt-4">
            <input type="text" placeholder="Cari notifikasi (contoh: SLP-021, approve, rejected)..."
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
        </div>
    </div>

    {{-- List --}}
    @php
        // Dummy notifications
        $items = [
            [
                'type' => 'info',
                'title' => 'Dokumen baru menunggu verifikasi',
                'desc' => 'SLP-021 (Slip Setoran) diupload oleh Admin. Status: Pending.',
                'time' => '2 menit lalu',
                'unread' => true,
            ],
            [
                'type' => 'success',
                'title' => 'Dokumen berhasil di-approve',
                'desc' => 'FRM-002 (Form Rekening) telah di-approve oleh Supervisor.',
                'time' => '1 jam lalu',
                'unread' => true,
            ],
            [
                'type' => 'danger',
                'title' => 'Dokumen di-reject',
                'desc' => 'KEL-007 (Keluhan Nasabah) di-reject. Catatan: file tidak sesuai.',
                'time' => 'kemarin',
                'unread' => false,
            ],
            [
                'type' => 'warning',
                'title' => 'Reminder: dokumen pending > 24 jam',
                'desc' => 'Ada dokumen Teller yang masih Pending lebih dari 24 jam.',
                'time' => '2 hari lalu',
                'unread' => false,
            ],
        ];

        $typeStyle = [
            'info' =>    ['bg' => 'bg-blue-100',   'text' => 'text-blue-700',   'dot' => 'bg-blue-600',   'label' => 'INFO'],
            'success' => ['bg' => 'bg-green-100',  'text' => 'text-green-700',  'dot' => 'bg-green-600',  'label' => 'SUCCESS'],
            'warning' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'dot' => 'bg-yellow-500', 'label' => 'WARNING'],
            'danger' =>  ['bg' => 'bg-red-100',    'text' => 'text-red-700',    'dot' => 'bg-red-600',    'label' => 'DANGER'],
        ];
    @endphp

    <div class="mt-6 space-y-3">
        @foreach($items as $n)
            @php $s = $typeStyle[$n['type']]; @endphp

            <div class="bg-white rounded-xl border p-5 flex gap-4 items-start">
                {{-- Icon/dot --}}
                <div class="mt-1">
                    <div class="w-3 h-3 rounded-full {{ $s['dot'] }}"></div>
                </div>

                {{-- Content --}}
                <div class="flex-1">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-1 text-xs rounded-full {{ $s['bg'] }} {{ $s['text'] }}">
                                {{ $s['label'] }}
                            </span>

                            <div class="font-semibold">
                                {{ $n['title'] }}
                            </div>

                            @if($n['unread'])
                                <span class="text-xs px-2 py-0.5 rounded-full border bg-gray-50">
                                    Unread
                                </span>
                            @endif
                        </div>

                        <div class="text-xs text-gray-500">
                            {{ $n['time'] }}
                        </div>
                    </div>

                    <div class="text-sm text-gray-600 mt-1">
                        {{ $n['desc'] }}
                    </div>

                    {{-- Actions --}}
                    <div class="mt-3 flex flex-wrap gap-2">
                        <button type="button"
                            class="px-3 py-2 text-sm rounded-lg border bg-white hover:bg-gray-50">
                            Lihat detail
                        </button>
                        <button type="button"
                            class="px-3 py-2 text-sm rounded-lg border bg-white hover:bg-gray-50">
                            Tandai dibaca
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Pagination dummy --}}
    <div class="mt-6 flex items-center justify-between text-sm text-gray-500">
        <div>Menampilkan 1–4 dari 12 notifikasi</div>
        <div class="flex gap-2">
            <button class="px-3 py-2 rounded-lg border bg-white hover:bg-gray-50">Prev</button>
            <button class="px-3 py-2 rounded-lg border bg-white hover:bg-gray-50">Next</button>
        </div>
    </div>
</x-app-shell>
