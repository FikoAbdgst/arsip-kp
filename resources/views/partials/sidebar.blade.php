@php
    $link = 'block px-3 py-2 rounded-lg text-sm hover:bg-gray-100 transition-colors';
    // Helper function untuk cek active state
    $isActive = fn($name) => request()->routeIs($name) ? 'bg-gray-100 font-medium text-blue-600' : 'text-gray-600';
@endphp

<nav class="p-4 space-y-1 text-sm">
    {{-- Dashboard (Admin & Supervisor) --}}
    <a href="{{ route('dashboard') }}" class="{{ $link }} {{ $isActive('dashboard') }}">
        Dashboard
    </a>

    {{-- Menu Teller --}}
    <a href="{{ route('teller.index') }}" class="{{ $link }} {{ $isActive('teller.index') }}">
        Teller
    </a>

    {{-- Menu CS --}}
    <a href="{{ route('cs.index') }}" class="{{ $link }} {{ $isActive('cs.index') }}">
        Customer Service
    </a>

    {{-- Menu Supervisor Only --}}
    @if (auth()->user()->role === 'supervisor')
        <a href="{{ route('verification.index') }}" class="{{ $link }} {{ $isActive('verification.index') }}">
            Verifikasi
        </a>
    @endif

    {{-- Reports --}}
    <a href="{{ route('reports.index') }}" class="{{ $link }} {{ $isActive('reports.index') }}">
        Laporan
    </a>

    {{-- Activity Log (Opsional jika sudah dibuat) --}}
    @if (Route::has('activity.index'))
        <a href="{{ route('activity.index') }}" class="{{ $link }} {{ $isActive('activity.index') }}">
            Aktivitas
        </a>
    @endif
</nav>
