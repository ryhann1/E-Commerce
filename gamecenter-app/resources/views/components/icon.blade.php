@props(['name', 'size' => 20])
@php
    $paths = [
        'home' => 'M3 11l9-8 9 8M5 9.5V20h5v-6h4v6h5V9.5',
        'box' => 'M21 8l-9-5-9 5v8l9 5 9-5zM3 8l9 5 9-5M12 13v8',
        'receipt' => 'M6 3h12v18l-3-2-3 2-3-2-3 2zM9 8h6M9 12h6',
        'cart' => 'M3 4h2l2.4 11h10.2L20 7H6M9 20h.01M17 20h.01',
        'user' => 'M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM4 21a8 8 0 0 1 16 0',
        'users' => 'M9 11a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7zM2 20a7 7 0 0 1 14 0M17 4a3.5 3.5 0 0 1 0 7M22 20a6 6 0 0 0-4-5.6',
        'grid' => 'M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM13 13h7v7h-7z',
        'mail' => 'M3 6h18v12H3zM3 7l9 7 9-7',
        'lock' => 'M6 11h12v9H6zM8 11V8a4 4 0 0 1 8 0v3',
        'shield' => 'M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z',
        'search' => 'M11 18a7 7 0 1 0 0-14 7 7 0 0 0 0 14zM20 20l-4-4',
        'arrow' => 'M5 12h14M13 6l6 6-6 6',
        'logout' => 'M9 4H5v16h4M16 8l4 4-4 4M20 12H9',
        'plus' => 'M12 5v14M5 12h14',
        'check' => 'M5 12l5 5L20 7',
        'edit' => 'M4 20h4L19 9l-4-4L4 16zM13 7l4 4',
        'trash' => 'M4 7h16M10 11v6M14 11v6M6 7l1 13h10l1-13M9 7V4h6v3',
        'tag' => 'M3 12V4h8l10 10-8 8zM7.5 8.5h.01',
        'store' => 'M4 9l1-5h14l1 5M4 9h16v11H4zM9 20v-6h6v6',
        'phone' => 'M8 2h8a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zM11 18h2',
        'tablet' => 'M6 2h12a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1zM11 18h2',
        'laptop' => 'M4 5h16v11H4zM2 19h20',
        'headphone' => 'M4 15v-3a8 8 0 0 1 16 0v3M4 15h3v5H4zM17 15h3v5h-3z',
        'speaker' => 'M7 3h10v18H7zM12 8h.01M12 15a2.5 2.5 0 1 0 .01 0',
        'watch' => 'M8 6l1-4h6l1 4M8 18l1 4h6l1-4M7 6h10v12H7zM12 10v2l1.5 1',
        'camera' => 'M3 7h4l2-3h6l2 3h4v13H3zM12 17a4 4 0 1 0 .01 0',
        'plug' => 'M9 2v5M15 2v5M7 7h10v4a5 5 0 0 1-10 0zM12 16v6',
    ];
@endphp
<svg width="{{ $size }}" height="{{ $size }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" {{ $attributes }}><path d="{{ $paths[$name] ?? $paths['box'] }}"/></svg>
