<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Login - DompetKu' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('dompetKuTP.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased text-slate-900 bg-slate-50 selection:bg-emerald-100 selection:text-emerald-900">
    {{ $slot }}

    @livewireScripts
</body>
</html>
