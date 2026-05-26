<header 
    :class="isCollapsed ? 'lg:left-20' : 'lg:left-64'"
    class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-100 flex items-center justify-between px-8 fixed top-0 right-0 left-0 z-30 transition-all duration-300"
>
    <div class="flex-1 max-w-md hidden md:block">
        <!-- Search removed as requested -->
    </div>

    <div class="flex items-center gap-4 ml-auto">
        @php
            $unreadNotifications = auth()->check() ? auth()->user()->unreadNotifications()->latest()->get() : collect();
            $latestNotif = $unreadNotifications->first();
            $unreadCount = $unreadNotifications->count();
        @endphp

        <div x-data="{ showNotif: false }" class="relative" @click.away="showNotif = false">
            <button @click="showNotif = !showNotif" class="p-2.5 text-slate-500 hover:bg-slate-50 rounded-xl relative transition-colors">
                <x-lucide-bell class="w-5 h-5" />
                @if($unreadCount > 0)
                    <span class="absolute top-2.5 right-2.5 w-2 h-2 bg-rose-500 rounded-full border-2 border-white"></span>
                @endif
            </button>

            <div x-show="showNotif" 
                x-transition:enter="transition ease-out duration-200 origin-top-right"
                x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150 origin-top-right"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                class="absolute right-0 mt-2 w-72 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden z-50 p-2"
                style="display: none;">
                
                <div class="px-3 py-2 border-b border-slate-50 flex justify-between items-center">
                    <h4 class="text-xs font-bold text-slate-900">Notifikasi</h4>
                    @if($unreadCount > 0)
                        <span class="text-[10px] bg-rose-100 text-rose-600 px-2 py-0.5 rounded-full font-bold">{{ $unreadCount }} Baru</span>
                    @endif
                </div>
                
                <div class="max-h-64 overflow-y-auto">
                    @if(!$latestNotif)
                        <div class="px-3 py-6 text-center flex flex-col items-center">
                            <x-lucide-bell-off class="w-8 h-8 text-slate-200 mb-2" />
                            <p class="text-xs font-bold text-slate-400">Belum ada notifikasi.</p>
                        </div>
                    @else
                        <a href="{{ route('notifications') }}" class="block p-3 hover:bg-slate-50 rounded-xl transition-colors cursor-pointer border-b border-slate-50 flex items-start gap-3">
                            <div class="p-2 rounded-xl {{ isset($latestNotif->data['percentage']) && $latestNotif->data['percentage'] >= 100 ? 'bg-rose-100 text-rose-600' : 'bg-amber-100 text-amber-600' }}">
                                @if(isset($latestNotif->data['due_date']))
                                    <x-lucide-bell-ring class="w-4 h-4 text-amber-600" />
                                @else
                                    <x-lucide-alert-triangle class="w-4 h-4" />
                                @endif
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-900 leading-tight">{{ $latestNotif->data['message'] }}</p>
                                @if(isset($latestNotif->data['percentage']))
                                    <p class="text-[10px] text-slate-500 mt-1">
                                        Terpakai {{ $latestNotif->data['percentage'] }}% (Rp {{ number_format($latestNotif->data['usedAmount'], 0, ',', '.') }})
                                    </p>
                                @elseif(isset($latestNotif->data['due_date']))
                                    <p class="text-[10px] text-slate-500 mt-1">
                                        Nominal: Rp {{ number_format($latestNotif->data['amount'], 0, ',', '.') }}
                                    </p>
                                @endif
                                <p class="text-[9px] text-slate-400 mt-1">{{ $latestNotif->created_at->diffForHumans() }}</p>
                            </div>
                        </a>
                    @endif
                </div>
                
                <div class="p-2 border-t border-slate-50">
                    <a href="{{ route('notifications') }}" class="block w-full py-2 text-center text-xs font-bold text-emerald-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">
                        Lihat semua notifikasi
                    </a>
                </div>
            </div>
        </div>

        <div class="h-8 w-px bg-slate-100 mx-2"></div>

        <div x-data="{ isDropdownOpen: false }" class="relative" @click.away="isDropdownOpen = false">
            <button 
                @click="isDropdownOpen = !isDropdownOpen"
                :class="isDropdownOpen ? 'bg-slate-50 shadow-sm' : 'hover:bg-slate-50'"
                class="flex items-center gap-3 p-1.5 pr-3 rounded-xl transition-all group"
            >
                <div class="w-9 h-9 bg-emerald-100 rounded-full flex items-center justify-center border-2 border-white shadow-sm overflow-hidden">
                    <img 
                        src="{{ auth()->user()->profile_photo_url }}" 
                        alt="Avatar" 
                        class="w-full h-full object-cover"
                    />
                </div>
                <div class="text-left hidden sm:block">
                    <div class="text-sm font-semibold text-slate-900 group-hover:text-emerald-600 transition-colors tracking-tight">{{ auth()->user()->name ?? 'User' }}</div>
                </div>
                <x-lucide-chevron-down 
                    class="w-4 h-4 text-slate-400 group-hover:text-slate-600 transition-all duration-200"
                    x-bind:class="isDropdownOpen ? 'rotate-180' : ''"
                />
            </button>

            <div
                x-show="isDropdownOpen"
                x-transition:enter="transition ease-out duration-200 origin-top-right"
                x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150 origin-top-right"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden z-50 p-1.5"
                style="display: none;"
            >
                <a 
                    href="{{ route('settings') }}"
                    class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 rounded-xl transition-all group"
                >
                    <div class="p-1.5 bg-emerald-100 text-emerald-600 rounded-lg group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                        <x-lucide-user class="w-4 h-4" />
                    </div>
                    Profil Saya
                </a>
                <div class="h-px bg-slate-50 my-1.5 mx-2"></div>
                <a 
                    href="{{ route('logout') }}"
                    class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-bold text-rose-500 hover:bg-rose-50 rounded-xl transition-all group"
                >
                    <div class="p-1.5 bg-rose-100 text-rose-600 rounded-lg group-hover:bg-rose-500 group-hover:text-white transition-colors">
                        <x-lucide-log-out class="w-4 h-4" />
                    </div>
                    Logout Akun
                </a>
            </div>
        </div>
    </div>
</header>