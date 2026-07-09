<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Smart Digital Land Record System' }}</title>

    @include('home.partials.styles')
</head>
<body>
    @include('home.partials.header')

    <main>
        @yield('content')
    </main>

    @include('home.partials.footer')
    @include('home.partials.scripts')
</body>
</html>
