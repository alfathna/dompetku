<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dompetku' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('dompetKuTP.png') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased text-slate-900 bg-slate-50 selection:bg-emerald-100 selection:text-emerald-900">
    <div x-data="{ isCollapsed: false }" class="min-h-screen flex">
        
        <!-- Sidebar Component -->
        <x-sidebar />
        
        <main class="flex-1 flex flex-col min-w-0">
            <!-- Topbar Component -->
            <x-topbar />
            
            <!-- Page Container -->
            <div :class="isCollapsed ? 'lg:ml-20' : 'lg:ml-64'" class="mt-20 p-4 md:p-8 ml-0 transition-all duration-300">
                <div class="max-w-7xl mx-auto">
                    <!-- Page Transition Container -->
                    <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 50)" 
                         x-show="show" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </main>
    </div>

    @livewireScripts
</body>
</html>
