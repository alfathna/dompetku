<div class="min-h-screen bg-[#F8FAFC] flex items-center justify-center p-6 font-sans">
    <div class="w-full max-w-[1100px] grid grid-cols-1 md:grid-cols-2 bg-white rounded-[3rem] shadow-2xl overflow-hidden border border-slate-100 min-h-[700px]">
        
        <!-- Left Side: Visual/Branding -->
        <div class="hidden md:flex flex-col justify-between p-12 bg-emerald-600 text-white relative overflow-hidden">
            <!-- Abstract Decorations -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-emerald-400/20 rounded-full translate-y-1/2 -translate-x-1/2 blur-3xl"></div>
            
            <div class="relative z-10" x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)">
                <div class="flex items-center gap-4 mb-12">
                    <div class="bg-white/95 rounded-[14px] p-2.5 shadow-sm flex items-center justify-center">
                        <img src="{{ asset('dompetKuTP.png') }}" alt="dompetKu Logo" class="w-8 h-8 object-contain" />
                    </div>
                    <span class="text-2xl font-black tracking-tighter italic">dompetKu</span>
                </div>
                
                <div class="space-y-6"
                     x-show="show" 
                     x-transition:enter="transition ease-out duration-700"
                     x-transition:enter-start="opacity-0 -translate-x-8"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     style="display: none;">
                    <h1 class="text-5xl font-black leading-[1.1] tracking-tight">
                        Kelola uangmu <br />
                        <span class="text-emerald-200">lebih cerdas.</span>
                    </h1>
                    <p class="text-lg text-emerald-50/80 font-medium leading-relaxed max-w-sm">
                        Platform manajemen keuangan pribadi tercanggih untuk membantumu mencapai target finansial.
                    </p>
                </div>
            </div>

            <div class="relative z-10 space-y-8" x-data="{ show: false }" x-init="setTimeout(() => show = true, 300)">
                <div class="flex flex-col gap-4">
                    @foreach([
                        "Lacak transaksi secara otomatis",
                        "Buat budget dengan peringatan cerdas",
                        "Pantau perkembangan target finansial"
                    ] as $index => $text)
                        <div x-show="show" 
                             x-transition:enter="transition ease-out duration-500 delay-[{{ $index * 100 }}ms]"
                             x-transition:enter-start="opacity-0 translate-y-4"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             style="display: none;"
                             class="flex items-center gap-3 bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/10">
                            <div class="w-6 h-6 bg-emerald-400 rounded-full flex items-center justify-center flex-shrink-0">
                                <x-lucide-check-circle-2 class="w-3.5 h-3.5 text-emerald-950" stroke-width="3" />
                            </div>
                            <span class="text-sm font-bold text-white/90">{{ $text }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="flex flex-col justify-center p-8 lg:p-16 relative">
            <div class="max-w-md mx-auto w-full space-y-10">
                <div>
                    <h2 class="text-4xl font-black text-slate-900 tracking-tight mb-3 italic">Selamat Datang!</h2>
                    <p class="text-slate-400 font-medium">Masuk ke akun dompetKu untuk melanjutkan.</p>
                </div>

                <form wire:submit="login" class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">ALAMAT EMAIL</label>
                        <div class="relative group">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                                <x-lucide-mail class="w-[18px] h-[18px]" />
                            </div>
                            <input 
                                type="email" 
                                wire:model="email"
                                placeholder="nama@email.com" 
                                class="w-full bg-slate-50 border @error('email') border-rose-500 @else border-slate-100 @enderror rounded-2xl py-4 pl-12 pr-4 text-sm font-bold text-slate-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all"
                            />
                        </div>
                        @error('email') <span class="text-xs font-bold text-rose-500 ml-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between items-center ml-1">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">PASSWORD</label>
                            <button type="button" class="text-[10px] font-black text-emerald-600 uppercase tracking-widest hover:text-emerald-700">Lupa Password?</button>
                        </div>
                        <div class="relative group">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                                <x-lucide-lock class="w-[18px] h-[18px]" />
                            </div>
                            <input 
                                type="{{ $showPassword ? 'text' : 'password' }}" 
                                wire:model="password"
                                placeholder="••••••••" 
                                class="w-full bg-slate-50 border @error('password') border-rose-500 @else border-slate-100 @enderror rounded-2xl py-4 pl-12 pr-12 text-sm font-bold text-slate-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all"
                            />
                            <button 
                                type="button"
                                wire:click="togglePassword"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-900 transition-colors"
                            >
                                @if($showPassword)
                                    <x-lucide-eye-off class="w-[18px] h-[18px]" />
                                @else
                                    <x-lucide-eye class="w-[18px] h-[18px]" />
                                @endif
                            </button>
                        </div>
                        @error('password') <span class="text-xs font-bold text-rose-500 ml-1">{{ $message }}</span> @enderror
                    </div>

                    <button 
                        type="submit"
                        class="w-full bg-emerald-500 hover:bg-emerald-600 text-white py-4 rounded-2xl font-black text-sm shadow-xl shadow-emerald-500/20 active:scale-[0.98] transition-all flex items-center justify-center gap-2 uppercase tracking-widest group relative overflow-hidden"
                    >
                        <span wire:loading.remove wire:target="login" class="flex items-center justify-center gap-2">
                            Masuk Sekarang <x-lucide-arrow-right class="w-[18px] h-[18px] group-hover:translate-x-1 transition-transform" />
                        </span>
                        <span wire:loading wire:target="login">
                            <div class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                        </span>
                    </button>
                </form>

                <p class="text-center text-sm font-bold text-slate-400">
                    Belum punya akun? <button type="button" wire:click="toggleRegisterModal" class="text-emerald-600 hover:text-emerald-700 underline underline-offset-4">Daftar Gratis</button>
                </p>
            </div>
            
            <div class="absolute bottom-8 left-0 right-0 text-center">
                <p class="text-[10px] font-black text-slate-200 uppercase tracking-[0.2em] pointer-events-none">DompetKu Finance © {{ date('Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div 
        x-data="{ show: @entangle('showRegisterModal') }"
        x-show="show"
        x-cloak
        class="fixed inset-0 z-[100] flex items-center justify-center p-4"
    >
        <!-- Backdrop -->
        <div 
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm"
            wire:click="toggleRegisterModal"
        ></div>

        <!-- Modal Content -->
        <div 
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90 translate-y-8"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-90 translate-y-8"
            class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl relative z-[101] overflow-hidden p-8 space-y-8"
        >
            <div class="flex justify-between items-start">
                <div class="bg-white/95 rounded-[14px] p-2 shadow-sm flex items-center justify-center text-emerald-600 border border-slate-100">
                    <img src="{{ asset('dompetKuTP.png') }}" alt="dompetKu Logo" class="w-6 h-6 object-contain" />
                </div>
                <button wire:click="toggleRegisterModal" class="p-2 hover:bg-slate-100 rounded-xl transition-colors">
                    <x-lucide-x class="w-5 h-5 text-slate-400" />
                </button>
            </div>

            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight italic">Daftar Akun Baru</h2>
                <p class="text-sm text-slate-400 font-medium">Mulai perjalanan finansialmu hari ini.</p>
            </div>

            <form wire:submit="register" class="space-y-4">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">NAMA LENGKAP</label>
                    <input type="text" wire:model="registerName" placeholder="Masukkan nama lengkap" class="w-full bg-slate-50 border @error('registerName') border-rose-500 @else border-slate-100 @enderror rounded-2xl py-3.5 px-4 text-sm font-bold text-slate-900 focus:ring-4 focus:ring-emerald-500/10 outline-none" />
                    @error('registerName') <span class="text-xs font-bold text-rose-500 ml-1">{{ $message }}</span> @enderror
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">ALAMAT EMAIL</label>
                    <input type="email" wire:model="registerEmail" placeholder="nama@email.com" class="w-full bg-slate-50 border @error('registerEmail') border-rose-500 @else border-slate-100 @enderror rounded-2xl py-3.5 px-4 text-sm font-bold text-slate-900 focus:ring-4 focus:ring-emerald-500/10 outline-none" />
                    @error('registerEmail') <span class="text-xs font-bold text-rose-500 ml-1">{{ $message }}</span> @enderror
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">PASSWORD</label>
                    <input type="password" wire:model="registerPassword" placeholder="••••••••" class="w-full bg-slate-50 border @error('registerPassword') border-rose-500 @else border-slate-100 @enderror rounded-2xl py-3.5 px-4 text-sm font-bold text-slate-900 focus:ring-4 focus:ring-emerald-500/10 outline-none" />
                    @error('registerPassword') <span class="text-xs font-bold text-rose-500 ml-1">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white py-4 rounded-2xl font-black text-sm shadow-xl shadow-emerald-500/20 active:scale-[0.98] transition-all flex items-center justify-center gap-2 uppercase tracking-widest mt-4">
                    <span wire:loading.remove wire:target="register" class="flex items-center justify-center gap-2">
                        Daftar Sekarang <x-lucide-arrow-right class="w-[18px] h-[18px]" />
                    </span>
                    <span wire:loading wire:target="register">
                        <div class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                    </span>
                </button>
            </form>

            <p class="text-center text-xs font-bold text-slate-400">
                Sudah punya akun? <button wire:click="toggleRegisterModal" class="text-emerald-600 hover:text-emerald-700 underline underline-offset-4">Masuk</button>
            </p>
        </div>
    </div>
</div>
