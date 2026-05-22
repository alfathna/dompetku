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
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            @foreach($goals as $goal)
                @php
                    $percent = $goal->target_amount > 0 ? min(($goal->collected_amount / $goal->target_amount) * 100, 100) : 0;
                @endphp
                <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm flex flex-col justify-between group hover:shadow-lg transition-all hover:-translate-y-1">
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <div class="w-14 h-14 rounded-[1.25rem] border flex items-center justify-center transition-colors 
                                {{ $goal->color === 'emerald' ? 'bg-emerald-50 border-emerald-100 text-emerald-500 shadow-sm shadow-emerald-500/10' : ($goal->color === 'blue' ? 'bg-blue-50 border-blue-100 text-blue-500 shadow-sm shadow-blue-500/10' : ($goal->color === 'purple' ? 'bg-purple-50 border-purple-100 text-purple-500' : 'bg-amber-50 border-amber-100 text-amber-500 shadow-sm shadow-amber-500/10')) }}">
                                <x-lucide-target class="w-7 h-7" stroke-width="2.5" />
                            </div>
                            <div class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-tight 
                                {{ $goal->color === 'emerald' ? 'bg-emerald-50 text-emerald-600' : ($goal->color === 'blue' ? 'bg-blue-50 text-blue-600' : ($goal->color === 'purple' ? 'bg-purple-50 text-purple-600' : 'bg-amber-50 text-amber-600')) }}">
                                {{ round($percent) }}% Progress
                            </div>
                        </div>

                        <div>
                            <h3 class="text-xl font-bold text-navy-900 tracking-tight leading-tight">{{ $goal->title }}</h3>
                            <div class="mt-6 space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-400 font-medium">Terkumpul</span>
                                    <span class="font-bold text-emerald-500">Rp {{ number_format($goal->collected_amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="h-3 bg-slate-100 rounded-full overflow-hidden relative">
                                    <div class="absolute top-0 left-0 h-full rounded-full transition-all duration-1000 ease-out {{ $goal->color === 'emerald' ? 'bg-emerald-500 shadow-[0_0_12px_rgba(16,185,129,0.3)]' : ($goal->color === 'blue' ? 'bg-blue-500 shadow-[0_0_12px_rgba(59,130,246,0.3)]' : ($goal->color === 'purple' ? 'bg-purple-500' : 'bg-amber-500 shadow-[0_0_12px_rgba(245,158,11,0.3)]')) }}" style="width: {{ $percent }}%"></div>
                                </div>
                                <div class="flex justify-between text-sm font-semibold">
                                    <span class="text-slate-400 text-[10px] uppercase tracking-widest">Target</span>
                                    <span class="text-navy-900">Rp {{ number_format($goal->target_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 pt-6 border-t border-slate-50 grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold">Estimasi Selesai</p>
                            <p class="font-bold text-navy-900 flex items-center gap-2 text-sm">
                                <x-lucide-calendar class="w-3.5 h-3.5 text-emerald-500" /> {{ $goal->estimate_date ? \Carbon\Carbon::parse($goal->estimate_date)->format('M Y') : '-' }}
                            </p>
                        </div>
                        <div class="space-y-1 text-right">
                            <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold">Kapasitas Bulanan</p>
                            <p class="font-bold text-blue-600 flex items-center gap-2 text-sm justify-end">
                                <x-lucide-trending-up class="w-3.5 h-3.5" /> Rp {{ number_format($goal->monthly_capacity, 0, ',', '.') }} / bln
                            </p>
                        </div>
                    </div>

                    <button wire:click="openManageModal({{ $goal->id }})" class="mt-8 w-full py-4 bg-slate-50 hover:bg-emerald-50 text-slate-600 hover:text-emerald-600 font-bold rounded-2xl transition-all flex items-center justify-center gap-2 group-hover:bg-emerald-50 group-hover:text-emerald-600">
                        Kelola Tabungan <x-lucide-chevron-right class="w-[18px] h-[18px] group-hover:translate-x-1 transition-transform" />
                    </button>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Add Goal Modal -->
    @if($showAddModal)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
        <div class="bg-white w-full max-w-md rounded-[2.5rem] p-8 shadow-2xl relative">
            <button wire:click="$set('showAddModal', false)" class="absolute top-6 right-6 p-2 text-slate-400 hover:bg-slate-100 rounded-xl transition-colors">
                <x-lucide-x class="w-5 h-5" />
            </button>
            <h2 class="text-2xl font-bold text-slate-900 mb-6">Tambah Goal</h2>
            <form wire:submit="saveGoal" class="space-y-4">
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1 mb-1">Judul Goal</label>
                    <input type="text" wire:model="goalName" placeholder="Rumah KPR, Liburan..." class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-3 px-4 text-sm font-bold text-slate-900 focus:ring-4 focus:ring-emerald-500/10 outline-none" required />
                </div>
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1 mb-1">Target Nominal (Rp)</label>
                    <div x-data="{ raw: @entangle('goalTarget'), formatted: '' }" x-init="formatted = raw ? new Intl.NumberFormat('id-ID').format(raw) : ''; $watch('raw', val => { if(!val) formatted = ''; else if(val != String(formatted).replace(/\D/g, '')) formatted = new Intl.NumberFormat('id-ID').format(val); })">
                        <input type="text" x-model="formatted" @input="let val = String(formatted).replace(/\D/g, ''); raw = val ? parseInt(val) : null; formatted = val ? new Intl.NumberFormat('id-ID').format(val) : '';" placeholder="0" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-3 px-4 text-sm font-bold text-slate-900 focus:ring-4 focus:ring-emerald-500/10 outline-none" required />
                    </div>
                </div>
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1 mb-1">Kapasitas Nabung Bulanan (Rp)</label>
                    <div x-data="{ raw: @entangle('goalMonthly'), formatted: '' }" x-init="formatted = raw ? new Intl.NumberFormat('id-ID').format(raw) : ''; $watch('raw', val => { if(!val) formatted = ''; else if(val != String(formatted).replace(/\D/g, '')) formatted = new Intl.NumberFormat('id-ID').format(val); })">
                        <input type="text" x-model="formatted" @input="let val = String(formatted).replace(/\D/g, ''); raw = val ? parseInt(val) : null; formatted = val ? new Intl.NumberFormat('id-ID').format(val) : '';" placeholder="0" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-3 px-4 text-sm font-bold text-slate-900 focus:ring-4 focus:ring-emerald-500/10 outline-none" required />
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1 mb-1">Warna Tema</label>
                        <select wire:model="goalColor" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-3 px-4 text-sm font-bold text-slate-900 focus:ring-4 focus:ring-emerald-500/10 outline-none">
                            <option value="emerald">Emerald</option>
                            <option value="blue">Blue</option>
                            <option value="amber">Amber</option>
                            <option value="rose">Rose</option>
                            <option value="purple">Purple</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1 mb-1">Estimasi Capai</label>
                        <input type="date" wire:model="goalEstimate" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-3 px-4 text-sm font-bold text-slate-900 focus:ring-4 focus:ring-emerald-500/10 outline-none" required />
                    </div>
                </div>
                <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white py-4 rounded-2xl font-black text-sm shadow-xl mt-6">
                    Simpan Goal
                </button>
            </form>
        </div>
    </div>
    @endif

    <!-- Manage Goal Modal (Deposit) -->
    @if($showManageModal)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
        <div class="bg-white w-full max-w-md rounded-[2.5rem] p-8 shadow-2xl relative">
            <button wire:click="$set('showManageModal', false)" class="absolute top-6 right-6 p-2 text-slate-400 hover:bg-slate-100 rounded-xl transition-colors">
                <x-lucide-x class="w-5 h-5" />
            </button>
            <h2 class="text-2xl font-bold text-slate-900 mb-6">Setor ke Goal</h2>
            <form wire:submit="depositGoal" class="space-y-4">
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1 mb-1">Nominal Setor (Rp)</label>
                    <div x-data="{ raw: @entangle('depositAmount'), formatted: '' }" x-init="formatted = raw ? new Intl.NumberFormat('id-ID').format(raw) : ''; $watch('raw', val => { if(!val) formatted = ''; else if(val != String(formatted).replace(/\D/g, '')) formatted = new Intl.NumberFormat('id-ID').format(val); })">
                        <input type="text" x-model="formatted" @input="let val = String(formatted).replace(/\D/g, ''); raw = val ? parseInt(val) : null; formatted = val ? new Intl.NumberFormat('id-ID').format(val) : '';" placeholder="0" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-3 px-4 text-sm font-bold text-slate-900 focus:ring-4 focus:ring-emerald-500/10 outline-none" required />
                    </div>
                </div>
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1 mb-1">Dari Wallet (Opsional)</label>
                    <select wire:model="depositWallet" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-3 px-4 text-sm font-bold text-slate-900 focus:ring-4 focus:ring-emerald-500/10 outline-none">
                        <option value="">Pilih Wallet</option>
                        @foreach($wallets as $w)
                            <option value="{{ $w->id }}">{{ $w->name }} (Rp {{ number_format($w->balance, 0, ',', '.') }})</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white py-4 rounded-2xl font-black text-sm shadow-xl mt-6">
                    Setor Sekarang
                </button>
                @error('depositAmount') <span class="text-xs font-bold text-rose-500 block text-center mt-2">{{ $message }}</span> @enderror
            </form>
        </div>
    </div>
    @endif
</div>
