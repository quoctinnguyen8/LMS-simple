@props([
    'ogTitle' => null,
    'ogDescription' => null,
    'ogImage' => null,
])
@if ($ogTitle)
    <meta property="og:title" content="{{ $ogTitle }}">
@endif
@if ($ogDescription)
    <meta property="og:description" content="{{ $ogDescription }}">
@endif
@if ($ogImage)
    <meta property="og:image" content="{{ $ogImage }}">
@endif