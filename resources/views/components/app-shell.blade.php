@props(['title' => 'Arsip Digital', 'header' => ''])

@include('layouts.app-shell', [
    'title' => $title,
    'header' => $header,
    'slot' => $slot
])
