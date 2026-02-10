@php
    $link = 'block px-3 py-2 rounded-lg text-sm hover:bg-gray-100 transition-colors flex items-center justify-between';
    // Helper function untuk cek active state
    $isActive = fn($name) => request()->routeIs($name) ? 'bg-gray-100 font-medium text-blue-600' : 'text-gray-600';
@endphp

{{-- 1. Tambahkan ID 'sidebar-navigation' di sini --}}
<nav id="sidebar-navigation" class="p-4 space-y-1 text-sm">

    {{-- Dashboard --}}
    <a href="{{ route('dashboard') }}" class="{{ $link }} {{ $isActive('dashboard') }}">
        <span>Dashboard</span>
    </a>

    {{-- Menu Teller --}}
    <a href="{{ route('teller.index') }}" class="{{ $link }} {{ $isActive('teller.index') }}">
        <span>Teller</span>
    </a>

    {{-- Menu CS --}}
    <a href="{{ route('cs.index') }}" class="{{ $link }} {{ $isActive('cs.index') }}">
        <span>Customer Service</span>
    </a>

    {{-- Menu Supervisor Only --}}
    @if (auth()->user()->role === 'supervisor')
        <a href="{{ route('verification.index') }}" class="{{ $link }} {{ $isActive('verification.index') }}">
            <span>Verifikasi</span>

            {{-- BADGE JUMLAH PENDING (Realtime) --}}
            {{-- Variabel $pendingCount dikirim dari AppServiceProvider --}}
            @if (isset($pendingCount) && $pendingCount > 0)
                <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm ">
                    {{ $pendingCount }}
                </span>
            @endif
        </a>
    @endif

    {{-- Reports --}}
    <a href="{{ route('reports.index') }}" class="{{ $link }} {{ $isActive('reports.index') }}">
        <span>Laporan</span>
    </a>

    {{-- Activity Log --}}
    @if (Route::has('activity.index'))
        <a href="{{ route('activity.index') }}" class="{{ $link }} {{ $isActive('activity.index') }}">
            <span>Aktivitas</span>
        </a>
    @endif
</nav>

{{-- 2. Tambahkan Script Pemicu Realtime Khusus Sidebar --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Memanggil fungsi global activateRealtime yang sudah ada di app-shell.blade.php
        // Interval 5000ms (5 detik) agar tidak terlalu memberatkan server karena ini hanya badge
        if (typeof activateRealtime === 'function') {
            activateRealtime('sidebar-navigation', 5000);
        }
    });
</script>
