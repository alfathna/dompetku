<div class="space-y-8 pb-10">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Financial Goals</h1>
            <p class="text-slate-500 mt-1">Wujudkan impianmu dengan perencanaan matang.</p>
        </div>
        <button wire:click="$set('showAddModal', true)" class="bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-emerald-500/20 active:scale-95 transition-all flex items-center gap-2">
            Tambah Goals
        </button>
    </div>

    @if(count($goals) === 0)
        <div class="bg-white rounded-[2rem] border border-slate-100 p-16 text-center">
            <div class="flex flex-col items-center justify-center text-slate-400">
                <div class="w-20 h-20 bg-slate-50 rounded-[2rem] flex items-center justify-center mb-6 border border-slate-100">
                    <x-lucide-target class="w-10 h-10 text-slate-300" />
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-2">Belum ada Financial Goals</h3>
                <p class="text-slate-500 max-w-md mx-auto">Mulai rencanakan impian besarmu hari ini. Baik itu membeli rumah, liburan, atau dana darurat, catat dan capai targetmu.</p>
                
                <button wire:click="$set('showAddModal', true)" class="mt-8 bg-navy-900 hover:bg-emerald-600 text-white px-8 py-3 rounded-2xl font-black text-sm transition-all uppercase tracking-widest shadow-lg shadow-navy-900/20 hover:shadow-emerald-500/30">
                    Mulai Rencana Pertamamu
                </button>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($goals as $goal)
                @php
                    $percent = $goal->target_amount > 0 ? min(($goal->collected_amount / $goal->target_amount) * 100, 100) : 0;
                    // Color logic based on progress
                    if ($percent >= 80) {
                        $progressColor = 'bg-emerald-500';
                        $progressBg = 'bg-emerald-50 text-emerald-600';
                    } elseif ($percent >= 40) {
                        $progressColor = 'bg-blue-500';
                        $progressBg = 'bg-blue-50 text-blue-600';
                    } else {
                        $progressColor = 'bg-amber-500';
                        $progressBg = 'bg-amber-50 text-amber-600';
                    }
                @endphp
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm space-y-6 relative group hover:shadow-lg transition-all">
                    <div class="absolute top-4 right-4 flex gap-1 z-10">
                        <button wire:click="editGoal({{ $goal->id }})" class="p-1.5 text-slate-400 hover:text-emerald-500 hover:bg-slate-50 rounded-lg transition-colors">
                            <x-lucide-pencil class="w-4 h-4" />
                        </button>
                        <button wire:click="confirmDelete({{ $goal->id }})" class="p-1.5 text-slate-400 hover:text-rose-500 hover:bg-slate-50 rounded-lg transition-colors">
                            <x-lucide-trash-2 class="w-4 h-4" />
                        </button>
                    </div>
                    
                    <div class="flex justify-between items-center pt-2">
                        <div class="w-12 h-12 rounded-full {{ $progressBg }} flex items-center justify-center">
                            <x-lucide-target class="w-6 h-6" />
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $progressBg }}">
                            {{ number_format($percent, 0) }}% PROGRESS
                        </span>
                    </div>
                    
                    <h3 class="font-bold text-lg text-navy-900 leading-tight pr-12">{{ $goal->title }}</h3>
                    
                    <div>
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-xs font-bold text-slate-400 uppercase">Terkumpul</span>
                            <span class="text-sm font-bold {{ str_replace('bg-', 'text-', $progressBg) }}">Rp {{ number_format($goal->collected_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                            <div class="{{ $progressColor }} h-2.5 rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Target</span>
                            <span class="text-xs font-bold text-navy-900">Rp {{ number_format($goal->target_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 border-t border-slate-100 pt-4">
                        <div>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Estimasi Selesai</p>
                            <p class="text-xs font-bold text-navy-900 flex items-center gap-1 mt-1">
                                <x-lucide-calendar class="w-3.5 h-3.5 text-emerald-500" />
                                {{ \Carbon\Carbon::parse($goal->estimate_date)->translatedFormat('M Y') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Kapasitas Bulanan</p>
                            <p class="text-xs font-bold text-blue-600 flex items-center justify-end gap-1 mt-1">
                                <x-lucide-trending-up class="w-3.5 h-3.5" />
                                Rp {{ number_format($goal->monthly_capacity, 0, ',', '.') }} / bln
                            </p>
                        </div>
                    </div>
                    
                    <button wire:click="openManageModal({{ $goal->id }})" class="w-full mt-2 py-3 bg-slate-50 hover:bg-slate-100 text-slate-600 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2">
                        Kelola Tabungan <x-lucide-chevron-right class="w-4 h-4" />
                    </button>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Delete Confirm Modal -->
    @if($showDeleteConfirmModal)
    <div class="fixed inset-0 z-[120] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-navy-950/40 backdrop-blur-sm" wire:click="$set('showDeleteConfirmModal', false)"></div>
        <div class="bg-white translate-z-0 w-full max-w-sm rounded-[2rem] shadow-2xl relative z-10 overflow-hidden flex flex-col items-center p-8 text-center animate-in zoom-in-95 duration-200">
            <div class="w-16 h-16 bg-rose-50 rounded-[1.25rem] flex items-center justify-center mb-6">
                <x-lucide-alert-triangle class="w-8 h-8 text-rose-500" />
            </div>
            <h2 class="text-xl font-bold text-navy-900 tracking-tight mb-2">Hapus Goal?</h2>
            <p class="text-slate-500 text-sm leading-relaxed mb-8">
                Apakah Anda yakin ingin menghapus goal ini? Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="flex items-center gap-3 w-full">
                <button wire:click="$set('showDeleteConfirmModal', false)" class="flex-1 py-3.5 rounded-2xl text-xs font-bold text-slate-500 hover:bg-slate-50 transition-colors uppercase tracking-widest active:scale-95">
                    Batal
                </button>
                <button wire:click="deleteGoal" class="flex-1 bg-rose-500 hover:bg-rose-600 text-white py-3.5 rounded-2xl font-black text-xs shadow-xl shadow-rose-500/20 active:scale-95 transition-all uppercase tracking-widest">
                    Hapus
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Add/Edit Goal Modal -->
    @if($showAddModal)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-navy-950/40 backdrop-blur-sm" wire:click="$set('showAddModal', false)"></div>
        <div class="bg-white translate-z-0 w-full max-w-xl rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="bg-emerald-500 px-6 py-5 text-white relative flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-white/20 rounded-xl backdrop-blur-md">
                        <x-lucide-target class="w-[18px] h-[18px] stroke-[3px]" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold tracking-tight">{{ $goalId ? 'Edit Goals' : 'Tambah Goals' }}</h2>
                        <p class="text-emerald-50 text-[10px] opacity-85">Rencanakan target tabungan barumu.</p>
                    </div>
                </div>
                <button wire:click="$set('showAddModal', false)" class="p-1.5 bg-white/15 hover:bg-white/25 rounded-lg transition-colors">
                    <x-lucide-x class="w-[18px] h-[18px]" />
                </button>
            </div>

            <!-- Form Content -->
            <form wire:submit="saveGoal" class="flex flex-col">
                <div class="p-6 space-y-5 overflow-hidden">
                    <!-- Nama Goals -->
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Nama Goals*</label>
                        <div class="relative group">
                            <x-lucide-target class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-500 transition-colors w-4 h-4" />
                            <input type="text" wire:model="goalName" placeholder="Contoh: Laptop Baru, Liburan, dll" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-2.5 pl-10 pr-4 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500/50 focus:bg-white outline-none transition-all" required />
                        </div>
                        @error('goalName') <span class="text-rose-500 text-[10px] ml-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Target Dana -->
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Target Dana*</label>
                            <div class="relative group">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 font-black text-slate-400 group-focus-within:text-emerald-500 text-xs">Rp</span>
                                <div x-data="{ raw: @entangle('goalTargetAmount'), formatted: '' }" x-init="formatted = raw ? new Intl.NumberFormat('id-ID').format(raw) : ''; $watch('raw', val => { if(!val) formatted = ''; else if(val != String(formatted).replace(/\D/g, '')) formatted = new Intl.NumberFormat('id-ID').format(val); })">
                                    <input type="text" x-model="formatted" @input="let val = String(formatted).replace(/\D/g, ''); raw = val ? parseInt(val) : null; formatted = val ? new Intl.NumberFormat('id-ID').format(val) : '';" placeholder="0" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-2.5 pl-[2.25rem] pr-4 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500/50 focus:bg-white outline-none transition-all" required />
                                </div>
                            </div>
                            @error('goalTargetAmount') <span class="text-rose-500 text-[10px] ml-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Kapasitas Bulanan -->
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Kapasitas Bulanan*</label>
                            <div class="relative group">
                                <x-lucide-trending-up class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-500 transition-colors w-4 h-4" />
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 font-bold text-slate-400 text-[10px]">/ BLN</span>
                                <div x-data="{ raw: @entangle('goalMonthlyCapacity'), formatted: '' }" x-init="formatted = raw ? new Intl.NumberFormat('id-ID').format(raw) : ''; $watch('raw', val => { if(!val) formatted = ''; else if(val != String(formatted).replace(/\D/g, '')) formatted = new Intl.NumberFormat('id-ID').format(val); })">
                                    <input type="text" x-model="formatted" @input="let val = String(formatted).replace(/\D/g, ''); raw = val ? parseInt(val) : null; formatted = val ? new Intl.NumberFormat('id-ID').format(val) : '';" placeholder="0" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-2.5 pl-10 pr-12 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500/50 focus:bg-white outline-none transition-all" required />
                                </div>
                            </div>
                            @error('goalMonthlyCapacity') <span class="text-rose-500 text-[10px] ml-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Estimasi Selesai -->
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Estimasi Selesai*</label>
                        <div class="relative group">
                            <x-lucide-calendar class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-500 transition-colors w-4 h-4" />
                            <input type="date" wire:model="goalEstimateDate" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-2.5 pl-10 pr-4 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500/50 focus:bg-white outline-none transition-all" required />
                        </div>
                        @error('goalEstimateDate') <span class="text-rose-500 text-[10px] ml-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="bg-emerald-50 p-4 rounded-2xl flex items-start gap-3 text-emerald-700">
                        <div class="bg-white px-2 py-1 rounded text-xs font-bold text-emerald-600 mt-0.5">Tips</div>
                        <p class="text-xs font-medium leading-relaxed">Kapasitas bulanan akan membantumu menabung teratur demi meraih impianmu tepat waktu.</p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 rounded-b-[2.5rem]">
                    <button type="button" wire:click="$set('showAddModal', false)" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-200 transition-all uppercase tracking-widest active:scale-95">
                        Batal
                    </button>
                    <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white px-7 py-2.5 rounded-xl font-black text-xs shadow-xl shadow-emerald-500/10 active:scale-95 transition-all flex items-center gap-2 uppercase tracking-widest">
                        <x-lucide-check class="w-4 h-4 stroke-[3px]" /> Simpan Goals
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Manage Goal Savings Modal -->
    @if($showManageModal)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-navy-950/40 backdrop-blur-sm" wire:click="$set('showManageModal', false)"></div>
        <div class="bg-white translate-z-0 w-full max-w-xl rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="bg-emerald-500 px-6 py-5 text-white relative flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-white/20 rounded-xl backdrop-blur-md">
                        <x-lucide-plus class="w-[18px] h-[18px] stroke-[3px]" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold tracking-tight">Kelola Tabungan</h2>
                        <p class="text-emerald-50 text-[10px] opacity-85">Tambah nominal setoran untuk goals {{ $goalName }}.</p>
                    </div>
                </div>
                <button wire:click="$set('showManageModal', false)" class="p-1.5 bg-white/15 hover:bg-white/25 rounded-lg transition-colors">
                    <x-lucide-x class="w-[18px] h-[18px]" />
                </button>
            </div>

            <!-- Form Content -->
            <form wire:submit="depositGoal" class="flex flex-col">
                <div class="p-6 space-y-5 overflow-hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nominal Setoran -->
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Nominal Setoran*</label>
                            <div class="relative group">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 font-black text-slate-400 group-focus-within:text-emerald-500 text-xs">Rp</span>
                                <div x-data="{ raw: @entangle('depositAmount'), formatted: '' }" x-init="formatted = raw ? new Intl.NumberFormat('id-ID').format(raw) : ''; $watch('raw', val => { if(!val) formatted = ''; else if(val != String(formatted).replace(/\D/g, '')) formatted = new Intl.NumberFormat('id-ID').format(val); })">
                                    <input type="text" x-model="formatted" @input="let val = String(formatted).replace(/\D/g, ''); raw = val ? parseInt(val) : null; formatted = val ? new Intl.NumberFormat('id-ID').format(val) : '';" placeholder="0" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-2.5 pl-[2.25rem] pr-4 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500/50 focus:bg-white outline-none transition-all" required />
                                </div>
                            </div>
                            @error('depositAmount') <span class="text-rose-500 text-[10px] ml-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Dari Wallet -->
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Dari Wallet*</label>
                            <div class="relative group">
                                <x-lucide-wallet class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-500 transition-colors w-4 h-4" />
                                <select wire:model="depositWallet" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-2.5 pl-10 pr-4 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500/50 focus:bg-white outline-none transition-all appearance-none cursor-pointer" required>
                                    <option value="">Pilih wallet</option>
                                    @foreach($wallets as $wallet)
                                        <option value="{{ $wallet->id }}">{{ $wallet->name }} (Rp {{ number_format($wallet->balance, 0, ',', '.') }})</option>
                                    @endforeach
                                </select>
                                <x-lucide-chevron-down class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4 pointer-events-none" />
                            </div>
                            @error('depositWallet') <span class="text-rose-500 text-[10px] ml-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Tanggal -->
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Tanggal*</label>
                        <div class="relative group">
                            <x-lucide-calendar class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-500 transition-colors w-4 h-4" />
                            <input type="date" wire:model="depositDate" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-2.5 pl-10 pr-4 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500/50 focus:bg-white outline-none transition-all" required />
                        </div>
                        @error('depositDate') <span class="text-rose-500 text-[10px] ml-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Catatan -->
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Catatan (Opsional)</label>
                        <div class="relative group">
                            <x-lucide-align-left class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-500 transition-colors w-4 h-4" />
                            <input type="text" wire:model="depositNotes" placeholder="Contoh: Setoran awal bulan" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-2.5 pl-10 pr-4 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500/50 focus:bg-white outline-none transition-all" />
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 rounded-b-[2.5rem]">
                    <button type="button" wire:click="$set('showManageModal', false)" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-200 transition-all uppercase tracking-widest active:scale-95">
                        Batal
                    </button>
                    <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white px-7 py-2.5 rounded-xl font-black text-xs shadow-xl shadow-emerald-500/10 active:scale-95 transition-all flex items-center gap-2 uppercase tracking-widest">
                        <x-lucide-check class="w-4 h-4 stroke-[3px]" /> Simpan Tabungan
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
