@php
    $link = "block px-3 py-2 rounded-lg text-sm hover:bg-gray-100";
    $isActive = fn($name) => request()->routeIs($name) ? "bg-gray-100 font-medium" : "";
@endphp

<nav class="p-4 space-y-1 text-sm">
    <a href="{{ route('dashboard') }}"
        class="{{ $link }} {{ $isActive('dashboard') }}">
        Dashboard
    </a>

    <a href="{{ route('teller.dashboard') }}"
        class="{{ $link }} {{ $isActive('teller.dashboard') }}">
        Teller
    </a>

    <a href="{{ route('cs.dashboard') }}"
        class="{{ $link }} {{ $isActive('cs.dashboard') }}">
        Customer Service
    </a>

    {{-- SUPERVISOR ONLY --}}
    @if(auth()->user()->role === 'supervisor')
        <a href="{{ route('verifikasi.index') }}"
            class="{{ $link }} {{ $isActive('verifikasi.index') }}">
            Verifikasi
        </a>
    @endif

    <a href="{{ route('reports.index') }}"
        class="{{ $link }} {{ $isActive('reports.index') }}">
        Reports
    </a>

    <a href="{{ route('notifications.index') }}"
        class="{{ $link }} {{ $isActive('notifications.index') }}">
        Notifications
        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs border">
            3
        </span>
    </a>
</nav>
