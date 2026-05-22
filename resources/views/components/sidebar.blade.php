<div x-data="{ mobileOpen: false }">
    <!-- Mobile Toggle -->
    <div class="lg:hidden fixed top-4 left-4 z-50">
        <button 
            @click="mobileOpen = !mobileOpen"
            class="p-2 bg-white rounded-lg shadow-md text-navy-900"
        >
            <template x-if="mobileOpen">
                <x-lucide-x class="w-6 h-6" />
            </template>
            <template x-if="!mobileOpen">
                <x-lucide-menu class="w-6 h-6" />
            </template>
        </button>
    </div>

    <!-- Backdrop -->
    <div 
        x-show="mobileOpen"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="mobileOpen = false"
        class="fixed inset-0 bg-black/50 z-40 lg:hidden backdrop-blur-sm"
        style="display: none;"
    ></div>

    <aside 
        :class="{
            'w-20': isCollapsed,
            'w-64': !isCollapsed,
            'translate-x-0': mobileOpen,
            '-translate-x-full lg:translate-x-0': !mobileOpen
        }"
        class="fixed top-0 left-0 h-full bg-navy-900 text-slate-300 transition-all duration-300 z-40 flex flex-col"
    >
        <!-- Logo -->
        <div class="p-6 flex items-center gap-3">
            <div class="bg-white/95 rounded-[14px] p-2 shadow-sm flex items-center justify-center flex-shrink-0">
                <img src="{{ asset('dompetKuTP.png') }}" alt="dompetKu Logo" class="w-6 h-6 object-contain" />
            </div>
            <span x-show="!isCollapsed" class="font-bold text-xl text-white tracking-tight transition-opacity duration-300">
                dompet<span class="text-emerald-500">Ku</span>
            </span>
        </div>

        <!-- Menu Items -->
        <nav class="flex-1 px-3 mt-4 space-y-1">
            @php
                $menuItems = [
                    ['id' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'layout-dashboard', 'route' => 'dashboard'],
                    ['id' => 'keuangan', 'label' => 'Keuangan', 'icon' => 'wallet', 'route' => 'keuangan'],
                    ['id' => 'goals', 'label' => 'Goals', 'icon' => 'target', 'route' => 'goals'],
                    ['id' => 'statistik', 'label' => 'Statistik', 'icon' => 'bar-chart-3', 'route' => 'statistik'],
                    ['id' => 'notifications', 'label' => 'Notifikasi', 'icon' => 'bell', 'route' => 'notifications'],
                    ['id' => 'settings', 'label' => 'Pengaturan', 'icon' => 'settings', 'route' => 'settings'],
                ];
            @endphp

            @foreach($menuItems as $item)
                <a
                    href="{{ route($item['route']) }}"
                    class="w-full flex items-center gap-3 px-3 py-3 rounded-xl transition-all group relative {{ request()->routeIs($item['route']) ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20' : 'hover:bg-white/5 hover:text-white' }}"
                >
                    <x-dynamic-component :component="'lucide-' . $item['icon']" class="w-5 h-5 flex-shrink-0 transition-transform group-hover:scale-110 {{ request()->routeIs($item['route']) ? 'text-white' : 'text-slate-400 group-hover:text-emerald-400' }}" />
                    <span x-show="!isCollapsed" class="font-medium whitespace-nowrap">{{ $item['label'] }}</span>
                    @if(request()->routeIs($item['route']))
                        <div x-show="isCollapsed" class="absolute right-0 w-1 h-6 bg-white rounded-l-full" style="display: none;"></div>
                    @endif
                </a>
            @endforeach
        </nav>

        <!-- Bottom Actions -->
        <div class="p-3 border-t border-white/5 space-y-1">
            <a 
                href="{{ route('logout') }}"
                class="w-full flex items-center gap-3 px-3 py-3 rounded-xl transition-all hover:bg-white/5 group"
            >
                <x-lucide-log-out class="w-5 h-5 text-slate-400 group-hover:text-red-400" />
                <span x-show="!isCollapsed" class="font-medium text-slate-400 group-hover:text-white">Logout</span>
            </a>
            
            <button 
                @click="isCollapsed = !isCollapsed"
                class="hidden lg:flex w-full items-center gap-3 px-3 py-3 rounded-xl transition-all hover:bg-white/5 text-slate-400 hover:text-white"
            >
                <template x-if="isCollapsed">
                    <x-lucide-chevron-right class="w-5 h-5" />
                </template>
                <template x-if="!isCollapsed">
                    <x-lucide-chevron-left class="w-5 h-5" />
                </template>
                <span x-show="!isCollapsed" class="font-medium">Collapse</span>
            </button>
        </div>
    </aside>
</div>