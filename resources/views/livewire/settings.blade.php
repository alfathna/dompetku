<div class="space-y-8 max-w-6xl mx-auto pb-12" x-data="{ 
    showDeleteModal: false, 
    deleteConfirmText: '', 
    isPermanentConfirmed: false 
}">
    <!-- Header Content -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 px-1">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Pengaturan</h1>
            <p class="text-slate-500 mt-1">Personalisasi pengalaman aplikasimu.</p>
        </div>
    </div>

    <!-- Settings Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
        
        <!-- Left Column: Profil -->
        <div class="space-y-8">
            <!-- 1) Card — Profil Saya -->
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm space-y-8 h-full">
            <div class="flex flex-col sm:flex-row items-center gap-8">
                <div class="relative group">
                    <div class="w-24 h-24 bg-emerald-100 rounded-[2rem] flex items-center justify-center border-4 border-white shadow-xl shadow-emerald-500/10 overflow-hidden cursor-pointer">
                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ auth()->user()->name }}" alt="Profile" />
                        <div class="absolute inset-0 bg-navy-900/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="text-[10px] font-bold text-white uppercase tracking-widest">Ubah Foto</span>
                        </div>
                    </div>
                </div>
                <div class="text-center sm:text-left">
                    <h3 class="text-xl font-bold text-navy-900">{{ auth()->user()->name }}</h3>
                    <p class="text-slate-400 text-sm">Update foto profil dan identitas pribadimu.</p>
                </div>
            </div>

            @if(session('profile_success'))
                <div class="flex items-center gap-3 p-4 bg-emerald-50 text-emerald-700 rounded-2xl text-sm font-bold">
                    <x-lucide-check-circle-2 class="w-5 h-5 text-emerald-500 flex-shrink-0" />
                    {{ session('profile_success') }}
                </div>
            @endif

            <form wire:submit="saveProfile">
                <div class="grid grid-cols-1 gap-8 pt-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">NAMA LENGKAP</label>
                        <input 
                            type="text" 
                            wire:model="name"
                            placeholder="Masukkan nama lengkap"
                            class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 px-4 text-sm font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all"
                        />
                        @error('name') <span class="text-xs font-bold text-rose-500 ml-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">ALAMAT EMAIL</label>
                        <input 
                            type="email" 
                            wire:model="email"
                            placeholder="Masukkan email"
                            class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 px-4 text-sm font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all"
                        />
                        @error('email') <span class="text-xs font-bold text-rose-500 ml-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">PEKERJAAN</label>
                        <input 
                            type="text" 
                            wire:model="job"
                            placeholder="Masukkan pekerjaan"
                            class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 px-4 text-sm font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all"
                        />
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">ZONA WAKTU</label>
                        <div class="relative">
                            <x-lucide-globe class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 w-[18px] h-[18px]" />
                            <select wire:model="timezone" class="w-full appearance-none bg-slate-50 border border-slate-100 rounded-2xl py-4 pl-12 pr-4 text-sm font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all cursor-pointer">
                                <option value="">Pilih zona waktu</option>
                                <option value="GMT+07:00 (Jakarta)">GMT+07:00 (Jakarta)</option>
                                <option value="GMT+08:00 (Makassar)">GMT+08:00 (Makassar)</option>
                                <option value="GMT+09:00 (Jayapura)">GMT+09:00 (Jayapura)</option>
                                <option value="GMT+00:00 (London)">GMT+00:00 (London)</option>
                            </select>
                            <x-lucide-chevron-down class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none w-3.5 h-3.5" />
                        </div>
                        <p class="text-[10px] text-slate-400 font-medium ml-1">Dipakai untuk jadwal pengingat tagihan.</p>
                    </div>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="submit" class="px-8 py-3 rounded-2xl font-bold text-sm bg-emerald-500 text-white shadow-lg shadow-emerald-500/20 hover:bg-emerald-600 transition-all flex items-center gap-2 uppercase tracking-widest active:scale-95">
                        <x-lucide-check class="w-[18px] h-[18px]" stroke-width="3" /> Simpan Profil
                    </button>
                </div>
            </form>
        </div>
        </div>

        <!-- Right Column: Keamanan -->
        <div class="space-y-8 flex flex-col">
            <!-- 2) Card — Ubah Password -->
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm space-y-6 flex-1">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-500 text-white rounded-xl shadow-lg shadow-blue-500/20">
                    <x-lucide-lock class="w-5 h-5" />
                </div>
                <h3 class="text-lg font-bold text-navy-900 tracking-tight">Ubah Password</h3>
            </div>

            @if(session('password_success'))
                <div class="flex items-center gap-3 p-4 bg-emerald-50 text-emerald-700 rounded-2xl text-sm font-bold">
                    <x-lucide-check-circle-2 class="w-5 h-5 text-emerald-500 flex-shrink-0" />
                    {{ session('password_success') }}
                </div>
            @endif

            <form wire:submit="updatePassword">
                <div class="grid grid-cols-1 gap-6">
                    <div class="space-y-2" x-data="{ show: false }">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">PASSWORD SAAT INI</label>
                        <div class="relative">
                            <input 
                                :type="show ? 'text' : 'password'" 
                                wire:model="current_password"
                                placeholder="••••••••"
                                class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 pl-4 pr-12 text-sm font-bold text-navy-900 focus:ring-4 focus:ring-blue-500/10 outline-none hover:bg-slate-100 transition-all"
                            />
                            <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600" title="Tampilkan/Sembunyikan">
                                <x-lucide-eye x-show="!show" class="w-5 h-5" />
                                <x-lucide-eye-off x-show="show" class="w-5 h-5" style="display: none;" />
                            </button>
                        </div>
                        @error('current_password') <span class="text-xs font-bold text-rose-500 ml-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-2" x-data="{ show: false }">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">PASSWORD BARU</label>
                        <div class="relative">
                            <input 
                                :type="show ? 'text' : 'password'" 
                                wire:model="new_password"
                                placeholder="Minimal 8 karakter"
                                class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 pl-4 pr-12 text-sm font-bold text-navy-900 focus:ring-4 focus:ring-blue-500/10 outline-none hover:bg-slate-100 transition-all"
                            />
                            <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600" title="Tampilkan/Sembunyikan">
                                <x-lucide-eye x-show="!show" class="w-5 h-5" />
                                <x-lucide-eye-off x-show="show" class="w-5 h-5" style="display: none;" />
                            </button>
                        </div>
                        @error('new_password') <span class="text-xs font-bold text-rose-500 ml-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-2" x-data="{ show: false }">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">KONFIRMASI PASSWORD</label>
                        <div class="relative">
                            <input 
                                :type="show ? 'text' : 'password'" 
                                wire:model="new_password_confirmation"
                                placeholder="Ulangi password baru"
                                class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 pl-4 pr-12 text-sm font-bold text-navy-900 focus:ring-4 focus:ring-blue-500/10 outline-none hover:bg-slate-100 transition-all"
                            />
                            <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600" title="Tampilkan/Sembunyikan">
                                <x-lucide-eye x-show="!show" class="w-5 h-5" />
                                <x-lucide-eye-off x-show="show" class="w-5 h-5" style="display: none;" />
                            </button>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="submit" class="px-8 py-3 rounded-2xl font-bold text-sm bg-blue-500 text-white shadow-lg shadow-blue-500/20 hover:bg-blue-600 transition-all flex items-center gap-2 uppercase tracking-widest active:scale-95">
                        <x-lucide-shield-check class="w-[18px] h-[18px]" /> Ubah Password
                    </button>
                </div>
            </form>
        </div>

        <!-- 3) Card — Keamanan Tingkat Lanjut (Danger Zone) -->
        <div class="p-8 rounded-[2.5rem] border border-rose-100 bg-rose-50/30 space-y-6">
            <div class="flex items-center gap-3 text-rose-500">
                <div class="p-2 bg-rose-500 text-white rounded-xl shadow-lg shadow-rose-500/20">
                    <x-lucide-shield-check class="w-5 h-5" />
                </div>
                <h3 class="text-lg font-bold tracking-tight">Keamanan Tingkat Lanjut</h3>
            </div>
            
            <div class="space-y-4">
                <p class="text-xs text-slate-500 leading-relaxed max-w-2xl font-medium">
                    Kalau kamu menghapus akun, semua transaksi, wallet, budget, tagihan, dan goals akan hilang secara permanen. Tindakan ini tidak bisa dibatalkan. Pastikan kamu sudah export data terlebih dahulu.
                </p>
                
                <button 
                    type="button"
                    @click="showDeleteModal = true"
                    class="group flex items-center gap-3 text-xs font-black text-rose-600 uppercase tracking-widest hover:text-rose-700 transition-all bg-white border border-rose-100 px-8 py-3.5 rounded-2xl hover:shadow-xl hover:shadow-rose-100 active:scale-95"
                >
                    <x-lucide-log-out class="w-4 h-4" />
                    HAPUS SEMUA DATA & AKUN
                </button>
            </div>
        </div>
        </div>
    </div>

    <!-- Modal Konfirmasi — Hapus Semua Data & Akun -->
    <div x-show="showDeleteModal" style="display: none;">
        <div 
            x-show="showDeleteModal" 
            x-transition.opacity.duration.300ms
            @click="showDeleteModal = false"
            class="fixed inset-0 bg-navy-950/40 backdrop-blur-sm z-[100]"
        ></div>

        <div class="fixed inset-0 flex items-center justify-center z-[101] p-4 pointer-events-none">
            <div 
                x-show="showDeleteModal" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl pointer-events-auto overflow-hidden flex flex-col"
            >
                <div class="bg-rose-500 p-8 text-white relative">
                    <button 
                        @click="showDeleteModal = false"
                        class="absolute top-6 right-6 p-2 bg-white/20 hover:bg-white/30 rounded-xl transition-colors"
                    >
                        <x-lucide-x class="w-5 h-5" />
                    </button>
                    <div class="flex items-center gap-4">
                        <div class="p-2.5 bg-white/20 rounded-2xl backdrop-blur-md">
                            <x-lucide-alert-triangle class="w-6 h-6" />
                        </div>
                        <div>
                            <h2 class="text-xl font-bold tracking-tight">Hapus semua data & akun?</h2>
                        </div>
                    </div>
                </div>

                <div class="p-8 space-y-6">
                    <p class="text-sm text-slate-500 font-medium leading-relaxed">
                        Tindakan ini <span class="font-bold text-rose-500 underline underline-offset-4">permanen dan tidak bisa dibatalkan</span>. Semua data kamu akan terhapus dari dompetKu.
                    </p>

                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">Ketik "HAPUS" untuk melanjutkan</label>
                            <input 
                                type="text" 
                                placeholder="HAPUS"
                                x-model="deleteConfirmText"
                                class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 px-4 text-sm font-bold text-navy-900 focus:ring-4 focus:ring-rose-500/10 outline-none"
                            />
                            <p x-show="deleteConfirmText !== '' && deleteConfirmText !== 'HAPUS'" class="text-[10px] text-rose-500 font-bold ml-1">Ketik HAPUS dengan benar.</p>
                        </div>

                        <label class="flex items-start gap-3 p-4 bg-slate-50 rounded-2xl cursor-pointer hover:bg-slate-100 transition-colors group">
                            <input 
                                type="checkbox" 
                                x-model="isPermanentConfirmed"
                                class="mt-1 w-4 h-4 rounded border-slate-300 text-rose-500 focus:ring-rose-500 transition-all"
                            />
                            <span class="text-xs font-bold text-slate-600 transition-colors group-hover:text-navy-900">Aku paham tindakan ini permanen</span>
                        </label>
                    </div>
                </div>

                <div class="p-8 pt-0 flex gap-3">
                    <button 
                        @click="showDeleteModal = false"
                        class="flex-1 px-4 py-4 rounded-2xl text-sm font-bold text-slate-500 border border-slate-100 hover:bg-slate-50 transition-all uppercase tracking-widest"
                    >
                        Batal
                    </button>
                    <button 
                        :disabled="deleteConfirmText !== 'HAPUS' || !isPermanentConfirmed"
                        wire:click="deleteAccount"
                        class="flex-[1.5] bg-rose-500 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-rose-600 text-white px-4 py-4 rounded-2xl font-black text-sm shadow-xl shadow-rose-500/20 active:scale-95 transition-all flex items-center justify-center gap-2 uppercase tracking-widest"
                    >
                        Hapus Permanen
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
