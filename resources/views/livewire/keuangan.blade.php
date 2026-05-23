<div class="space-y-8 pb-10" x-data="{ 
    activeTab: @entangle('activeTab')
}">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Kelola Keuangan</h1>
            <p class="text-slate-500 mt-1">Lacak transaksi, dompet, dan rencana belanjamu.</p>
        </div>
        <div class="flex items-center gap-2 bg-white p-1 rounded-2xl border border-slate-100 shadow-sm overflow-x-auto no-scrollbar">
            @php
                $tabs = [
                    ['id' => 'transaksi', 'label' => 'Transaksi', 'icon' => 'clock'],
                    ['id' => 'wallet', 'label' => 'Wallet', 'icon' => 'wallet'],
                    ['id' => 'budget', 'label' => 'Budget', 'icon' => 'credit-card'],
                    ['id' => 'tagihan', 'label' => 'Tagihan', 'icon' => 'smartphone'],
                ];
            @endphp
            @foreach($tabs as $tab)
                <button
                    @click="activeTab = '{{ $tab['id'] }}'"
                    :class="activeTab === '{{ $tab['id'] }}' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20' : 'text-slate-400 hover:text-slate-600 hover:bg-slate-50'"
                    class="flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold transition-all whitespace-nowrap"
                >
                    <x-dynamic-component :component="'lucide-' . $tab['icon']" class="w-[18px] h-[18px]" />
                    {{ $tab['label'] }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- TRANSAKSI TAB -->
    <div x-show="activeTab === 'transaksi'" x-transition.opacity.duration.300ms style="display: none;">
        <div class="space-y-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-3 flex-1">
                    <div class="relative max-w-xs w-full sm:w-auto">
                        <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 w-[18px] h-[18px]" />
                        <input type="text" wire:model.live="searchTx" placeholder="Cari transaksi..." class="w-full bg-white border border-slate-200 rounded-2xl py-2.5 pl-10 pr-4 text-sm focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all" />
                    </div>
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <input type="date" wire:model.live="filterStartDate" class="flex-1 sm:flex-none bg-white border border-slate-200 rounded-2xl py-2.5 px-4 text-sm focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all" title="Tanggal Mulai">
                        <span class="text-slate-400">-</span>
                        <input type="date" wire:model.live="filterEndDate" class="flex-1 sm:flex-none bg-white border border-slate-200 rounded-2xl py-2.5 px-4 text-sm focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all" title="Tanggal Akhir">
                    </div>
                    <select wire:model.live="sortNominal" class="w-full sm:w-auto bg-white border border-slate-200 rounded-2xl py-2.5 px-4 text-sm focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all cursor-pointer">
                        <option value="">Urutan Default</option>
                        <option value="asc">Nominal Terendah</option>
                        <option value="desc">Nominal Tertinggi</option>
                    </select>
                    <button wire:click="resetFilters" class="p-2.5 bg-white border border-slate-200 rounded-2xl text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition-all flex items-center justify-center" title="Reset Filter">
                        <x-lucide-refresh-cw class="w-[18px] h-[18px]" />
                    </button>
                </div>
                <div class="flex items-center gap-3 w-full xl:w-auto justify-end">
                    <button class="flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-600 hover:bg-slate-50 transition-all flex-1 xl:flex-none">
                        <x-lucide-download class="w-[18px] h-[18px]" /> Export
                    </button>
                    <button wire:click="$set('showAddModal', true)" class="flex items-center justify-center gap-2 px-6 py-2.5 bg-emerald-500 text-white rounded-2xl text-sm font-bold shadow-lg shadow-emerald-500/20 hover:bg-emerald-600 transition-all flex-1 xl:flex-none whitespace-nowrap">
                        Tambah Transaksi
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-8 py-5 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Transaksi</th>
                                <th class="px-6 py-5 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-center">Kategori</th>
                                <th class="px-6 py-5 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-center">Dompet</th>
                                <th class="px-6 py-5 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-center">Tipe</th>
                                <th class="px-6 py-5 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Tanggal</th>
                                <th class="px-6 py-5 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Nominal</th>
                                <th class="px-8 py-5 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @if(count($transactions) === 0)
                                <tr>
                                    <td colspan="7" class="px-8 py-10 text-center">
                                        <div class="flex flex-col items-center justify-center text-slate-400">
                                            <x-lucide-receipt class="w-12 h-12 mb-3 text-slate-200" />
                                            <p class="font-bold text-slate-500">Belum ada transaksi</p>
                                            <p class="text-sm">Mulai catat pengeluaran dan pemasukanmu.</p>
                                        </div>
                                    </td>
                                </tr>
                            @else
                                @foreach($transactions as $item)
                                    <tr class="hover:bg-slate-50/50 transition-colors group">
                                        <td class="px-8 py-5 font-semibold text-slate-900">{{ $item->title }}</td>
                                        <td class="px-6 py-5 text-center"><span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider">{{ $item->category ?? '-' }}</span></td>
                                        <td class="px-6 py-5 text-center text-sm font-medium text-slate-500">{{ $item->wallet->name ?? '-' }}</td>
                                        <td class="px-6 py-5 text-center">
                                            <div class="flex justify-center">
                                                @if($item->type === 'income')
                                                    <div class="bg-emerald-50 text-emerald-600 p-1 rounded-lg"><x-lucide-arrow-up class="w-3.5 h-3.5" /></div>
                                                @else
                                                    <div class="bg-rose-50 text-rose-600 p-1 rounded-lg"><x-lucide-arrow-down class="w-3.5 h-3.5" /></div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 text-sm text-slate-500">{{ \Carbon\Carbon::parse($item->transaction_date)->format('d M Y') }}</td>
                                        <td class="px-6 py-5 font-bold tabular-nums {{ $item->type === 'income' ? 'text-emerald-500' : 'text-rose-500' }}">
                                            {{ $item->type === 'income' ? '+' : '-' }}Rp {{ number_format($item->amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-8 py-5 text-right">
                                            <div class="flex justify-end gap-2">
                                                <button wire:click="confirmDelete('transaction', {{ $item->id }}, '{{ addslashes($item->title) }}')" class="p-2 hover:bg-white rounded-xl shadow-sm border border-transparent hover:border-slate-100 text-slate-400 hover:text-rose-500"><x-lucide-trash-2 class="w-4 h-4" /></button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                @if($transactions->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
                    {{ $transactions->links(data: ['scrollTo' => false]) }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- WALLET TAB -->
    <div x-show="activeTab === 'wallet'" x-transition.opacity.duration.300ms style="display: none;">
        <div class="space-y-6">
            <div class="flex justify-end gap-3">
                <button wire:click="$set('showTransferModal', true)" class="flex items-center gap-2 px-6 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-2xl text-sm font-bold shadow-sm hover:bg-slate-50 transition-all">
                    <x-lucide-arrow-right-left class="w-4 h-4 text-emerald-500" /> Transfer Saldo
                </button>
                <button wire:click="$set('showAddWalletModal', true)" class="flex items-center gap-2 px-6 py-2.5 bg-emerald-500 text-white rounded-2xl text-sm font-bold shadow-lg shadow-emerald-500/20 hover:bg-emerald-600 transition-all">
                    Tambah Wallet
                </button>
            </div>
            @if(count($wallets) === 0)
                <div class="bg-white rounded-[2rem] border border-slate-100 p-10 text-center">
                    <div class="flex flex-col items-center justify-center text-slate-400">
                        <x-lucide-wallet class="w-12 h-12 mb-3 text-slate-200" />
                        <p class="font-bold text-slate-500">Belum ada Wallet</p>
                        <p class="text-sm">Tambahkan wallet pertama Anda.</p>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($wallets as $wallet)
                        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm group hover:shadow-xl transition-all relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-24 h-24 rounded-full -mr-12 -mt-12 group-hover:scale-150 transition-transform 
                                {{ $wallet->color === 'emerald' ? 'bg-emerald-500/5' : ($wallet->color === 'navy' ? 'bg-navy-900/5' : ($wallet->color === 'purple' ? 'bg-purple-500/5' : 'bg-blue-500/5')) }}">
                            </div>
                            <div class="flex items-center gap-4 mb-8 relative z-10">
                                @php
                                    $icon = 'wallet';
                                    if ($wallet->type === 'cash') $icon = 'banknote';
                                    elseif ($wallet->type === 'bank') $icon = 'landmark';
                                    elseif ($wallet->type === 'e-wallet') $icon = 'smartphone';
                                    elseif ($wallet->type === 'lainnya') $icon = 'layout-grid';
                                @endphp
                                <div class="p-3 rounded-2xl 
                                    {{ $wallet->color === 'emerald' ? 'bg-emerald-500/10 text-emerald-600' : ($wallet->color === 'navy' ? 'bg-navy-900/10 text-navy-900' : ($wallet->color === 'purple' ? 'bg-purple-500/10 text-purple-600' : 'bg-blue-500/10 text-blue-600')) }}">
                                    <x-dynamic-component :component="'lucide-' . $icon" class="w-6 h-6" />
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-900 leading-tight">{{ $wallet->name }}</h3>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $wallet->type ?? 'Lainnya' }}</p>
                                </div>
                            </div>
                            <div class="space-y-1 relative z-10">
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Total Saldo</p>
                                <p class="text-2xl font-bold text-navy-900 tracking-tight">Rp {{ number_format($wallet->balance, 0, ',', '.') }}</p>
                            </div>
                            <div class="mt-6 flex items-center justify-between relative z-10">
                                <p class="text-xs text-slate-500 font-medium">{{ $wallet->transactions()->count() }} Transaksi</p>
                                <div class="flex gap-1">
                                    <button wire:click="editWallet({{ $wallet->id }})" class="p-2 hover:bg-white rounded-xl shadow-sm border border-transparent hover:border-slate-100 text-slate-400 hover:text-emerald-500 transition-colors">
                                        <x-lucide-pencil class="w-4 h-4" />
                                    </button>
                                    <button wire:click="confirmDelete('wallet', {{ $wallet->id }}, '{{ addslashes($wallet->name) }}')" class="p-2 hover:bg-white rounded-xl shadow-sm border border-transparent hover:border-slate-100 text-slate-400 hover:text-rose-500 transition-colors">
                                        <x-lucide-trash-2 class="w-4 h-4" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- BUDGET TAB -->
    <div x-show="activeTab === 'budget'" x-transition.opacity.duration.300ms style="display: none;">
        <div class="space-y-6">
            <div class="flex justify-end">
                <button wire:click="$set('showAddBudgetModal', true)" class="flex items-center gap-2 px-6 py-2.5 bg-emerald-500 text-white rounded-2xl text-sm font-bold shadow-lg shadow-emerald-500/20 hover:bg-emerald-600 transition-all">
                    Tambah Budget
                </button>
            </div>
            
            @if(count($budgets) === 0)
                <div class="bg-white rounded-[2rem] border border-slate-100 p-10 text-center">
                    <div class="flex flex-col items-center justify-center text-slate-400">
                        <x-lucide-credit-card class="w-12 h-12 mb-3 text-slate-200" />
                        <p class="font-bold text-slate-500">Belum ada Budget</p>
                        <p class="text-sm">Buat budget untuk mengontrol pengeluaran bulananmu.</p>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($budgets as $item)
                        @php
                            $txQuery = \App\Models\Transaction::where('user_id', auth()->id())
                                ->where('type', 'expense')
                                ->where('category', $item->category);
                            
                            \Carbon\Carbon::setLocale('id');
                            if ($item->period === 'weekly') {
                                $start = \Carbon\Carbon::now()->startOfWeek();
                                $end = \Carbon\Carbon::now()->endOfWeek();
                                $txQuery->whereBetween('transaction_date', [$start, $end]);
                                $periodText = "Masa berlaku: " . $start->translatedFormat('d M') . " - " . $end->translatedFormat('d M Y');
                            } else {
                                $start = \Carbon\Carbon::now()->startOfMonth();
                                $end = \Carbon\Carbon::now()->endOfMonth();
                                $txQuery->whereBetween('transaction_date', [$start, $end]);
                                $periodText = "Masa berlaku: " . $end->translatedFormat('d F Y');
                            }
                            
                            $used = $txQuery->sum('amount');
                            
                            $percent = $item->limit_amount > 0 ? min(($used / $item->limit_amount) * 100, 100) : 0;
                            $over = $used >= $item->limit_amount;
                            $near = $used >= ($item->limit_amount * 0.8);
                            
                            // Color scheme based on image
                            $colorClass = 'emerald';
                            $bgClass = 'bg-emerald-50 text-emerald-600';
                            $barClass = 'bg-emerald-500';
                            
                            if ($over) {
                                $colorClass = 'rose';
                                $bgClass = 'bg-rose-50 text-rose-600';
                                $barClass = 'bg-rose-500';
                            } elseif ($near) {
                                $colorClass = 'amber';
                                $bgClass = 'bg-amber-50 text-amber-600';
                                $barClass = 'bg-amber-500';
                            }
                        @endphp
                        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm space-y-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h4 class="text-lg font-bold text-navy-900">{{ $item->category }}</h4>
                                    <p class="text-xs text-slate-500 mt-1">{{ $periodText }}</p>
                                </div>
                                <div class="flex items-center gap-1">
                                    <button wire:click="editBudget({{ $item->id }})" class="p-2 text-slate-400 hover:bg-slate-50 hover:text-emerald-500 rounded-xl transition-colors">
                                        <x-lucide-pencil class="w-4 h-4" />
                                    </button>
                                    <button wire:click="confirmDelete('budget', {{ $item->id }}, '{{ addslashes($item->category) }}')" class="p-2 text-slate-400 hover:bg-slate-50 hover:text-rose-500 rounded-xl transition-colors">
                                        <x-lucide-trash-2 class="w-4 h-4" />
                                    </button>
                                </div>
                            </div>

                            <div class="pt-2">
                                <p class="text-3xl font-black text-navy-900 tracking-tight">Rp {{ number_format($used, 0, ',', '.') }}</p>
                                <p class="text-xs text-slate-400 mt-1">Sisa limit: Rp {{ number_format(max(0, $item->limit_amount - $used), 0, ',', '.') }}</p>
                            </div>

                            <div class="space-y-2 pt-2">
                                <div class="flex justify-end">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest {{ $bgClass }}">
                                        {{ round($percent) }}% TERPAKAI
                                    </span>
                                </div>
                                <div class="h-3 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full {{ $barClass }}" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>

                            <div class="flex justify-between items-center pt-4">
                                <span class="text-slate-400 uppercase tracking-widest text-[9px] font-black">TOTAL LIMIT BUDGET</span>
                                <span class="text-navy-900 font-bold text-sm">Rp {{ number_format($item->limit_amount, 0, ',', '.') }}</span>
                            </div>
                            
                            @if($over)
                                <div class="bg-rose-50 p-4 rounded-2xl flex items-center gap-3 text-rose-600 mt-4">
                                    <x-lucide-smartphone class="w-4 h-4" />
                                    <p class="text-[10px] font-bold uppercase tracking-widest">Warning: Budget Overlimit Rp {{ number_format($used - $item->limit_amount, 0, ',', '.') }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- TAGIHAN TAB -->
    <div x-show="activeTab === 'tagihan'" x-transition.opacity.duration.300ms style="display: none;">
        <div class="space-y-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-2 bg-slate-50 p-1 rounded-xl">
                    <button wire:click="$set('filterBill', 'Semua')" class="px-4 py-1.5 rounded-lg text-sm font-bold transition-all {{ $filterBill === 'Semua' ? 'bg-white text-navy-900 shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">Semua</button>
                    <button wire:click="$set('filterBill', 'Belum Dibayar')" class="px-4 py-1.5 rounded-lg text-sm font-bold transition-all {{ $filterBill === 'Belum Dibayar' ? 'bg-white text-navy-900 shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">Belum Dibayar</button>
                    <button wire:click="$set('filterBill', 'Sudah Dibayar')" class="px-4 py-1.5 rounded-lg text-sm font-bold transition-all {{ $filterBill === 'Sudah Dibayar' ? 'bg-white text-navy-900 shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">Sudah Dibayar</button>
                </div>
                <button wire:click="$set('showAddBillModal', true)" class="flex items-center gap-2 px-6 py-2.5 bg-emerald-500 text-white rounded-2xl text-sm font-bold shadow-lg shadow-emerald-500/20 hover:bg-emerald-600 transition-all">
                    Tambah Tagihan
                </button>
            </div>
            @if(count($bills) === 0)
                <div class="bg-white rounded-[2rem] border border-slate-100 p-10 text-center">
                    <div class="flex flex-col items-center justify-center text-slate-400">
                        <x-lucide-smartphone class="w-12 h-12 mb-3 text-slate-200" />
                        <p class="font-bold text-slate-500">Belum ada Tagihan</p>
                        <p class="text-sm">Catat tagihan rutinmu agar tidak terlewat.</p>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($bills as $bill)
                        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm space-y-6 relative group overflow-hidden">
                            <div class="absolute top-4 right-4 flex gap-1">
                                <button wire:click="editBill({{ $bill->id }})" class="p-1.5 text-slate-400 hover:text-emerald-500 hover:bg-slate-50 rounded-lg transition-colors">
                                    <x-lucide-pencil class="w-4 h-4" />
                                </button>
                                <button wire:click="confirmDelete('bill', {{ $bill->id }}, '{{ addslashes($bill->title) }}')" class="p-1.5 text-slate-400 hover:text-rose-500 hover:bg-slate-50 rounded-lg transition-colors">
                                    <x-lucide-trash-2 class="w-4 h-4" />
                                </button>
                                <div class="ml-2 {{ $bill->status === 'Paid' ? 'text-emerald-500 bg-emerald-50 rounded-full p-1' : 'text-rose-500 bg-rose-50 rounded-full p-1' }}">
                                    <x-dynamic-component :component="'lucide-' . ($bill->icon ?: 'tag')" class="w-5 h-5" />
                                </div>
                            </div>
                            <div class="pt-2">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest {{ $bill->status === 'Paid' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                    {{ $bill->status }}
                                </span>
                                <h4 class="mt-4 font-bold text-lg text-navy-900 leading-tight">{{ $bill->title }}</h4>
                                <p class="text-sm text-slate-400 mt-1">Jatuh tempo: {{ \Carbon\Carbon::parse($bill->due_date)->format('d M') }}</p>
                                @if($bill->wallet)
                                    <p class="text-[11px] font-bold text-slate-400 mt-2 flex items-center gap-1">
                                        <x-lucide-wallet class="w-3.5 h-3.5" /> {{ $bill->wallet->name }}
                                    </p>
                                @endif
                            </div>
                            <div class="flex items-end justify-between">
                                <p class="text-2xl font-black text-navy-900 tracking-tight">Rp {{ number_format($bill->amount, 0, ',', '.') }}</p>
                                @if($bill->status === 'Paid')
                                    <button disabled class="px-4 py-2 rounded-xl text-xs font-bold transition-all bg-slate-100 text-slate-400 cursor-default">
                                        Sudah Bayar
                                    </button>
                                @else
                                    <button wire:click="confirmPayBill({{ $bill->id }})" class="px-4 py-2 rounded-xl text-xs font-bold transition-all bg-emerald-500 text-white hover:bg-emerald-600 active:scale-95 shadow-lg shadow-emerald-500/20">
                                        Bayar Sekarang
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- MODALS -->
    
    @if($showAddModal)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-navy-950/40 backdrop-blur-sm" wire:click="$set('showAddModal', false)"></div>
        <div class="bg-white translate-z-0 w-full max-w-xl rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="bg-emerald-500 px-6 py-5 text-white relative flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-white/20 rounded-xl backdrop-blur-md">
                        <x-lucide-plus class="w-[18px] h-[18px] stroke-[3px]" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold tracking-tight">Tambah Transaksi</h2>
                        <p class="text-emerald-50 text-[10px] opacity-85">Catat pemasukan atau pengeluaran barumu.</p>
                    </div>
                </div>
                <button wire:click="$set('showAddModal', false)" class="p-1.5 bg-white/15 hover:bg-white/25 rounded-lg transition-colors">
                    <x-lucide-x class="w-[18px] h-[18px]" />
                </button>
            </div>

            <!-- Form Content -->
            <form wire:submit="saveTransaction" class="flex flex-col">
                <div class="p-6 space-y-4 overflow-hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Segmented Button Tipe -->
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Tipe Transaksi</label>
                            <div class="bg-slate-100 p-1 rounded-2xl flex relative border border-slate-200">
                                <button type="button" wire:click="$set('txType', 'expense')" class="relative z-10 flex-1 py-1.5 px-3 rounded-xl flex items-center justify-center gap-1.5 text-xs font-bold transition-colors {{ $txType === 'expense' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-400 hover:text-slate-500' }}">
                                    <x-lucide-arrow-down class="w-3.5 h-3.5 stroke-[3px]" /> Pengeluaran
                                </button>
                                <button type="button" wire:click="$set('txType', 'income')" class="relative z-10 flex-1 py-1.5 px-3 rounded-xl flex items-center justify-center gap-1.5 text-xs font-bold transition-colors {{ $txType === 'income' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-400 hover:text-slate-500' }}">
                                    <x-lucide-arrow-up class="w-3.5 h-3.5 stroke-[3px]" /> Pemasukan
                                </button>
                            </div>
                        </div>

                        <!-- Nama Transaksi -->
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Nama Transaksi</label>
                            <div class="relative group">
                                <x-lucide-align-left class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-500 transition-colors w-4 h-4" />
                                <input type="text" wire:model="txName" placeholder="{{ $txType === 'income' ? 'Gaji, Freelance, dll' : 'Makanan, Belanja, dll' }}" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-2.5 pl-10 pr-4 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500/50 focus:bg-white outline-none transition-all" required />
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Category Searchable Dropdown with Alpine -->
                        <div class="space-y-1.5" x-data="{ 
                            open: false, 
                            search: '', 
                            get categories() { return this.$wire.txType === 'income' ? ['Gaji', 'Freelance', 'Bonus', 'Penjualan', 'Lainnya'] : ['Makanan', 'Transportasi', 'Hiburan', 'Tagihan', 'Kesehatan', 'Pendidikan', 'Belanja', 'Lainnya']; }
                        }">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Kategori</label>
                            <div class="relative">
                                <button @click="open = !open" type="button" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-2.5 pl-10 pr-4 text-xs font-bold text-navy-900 flex items-center justify-between hover:bg-slate-100 transition-all outline-none text-left">
                                    <x-lucide-tag class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" />
                                    <span class="truncate" x-text="$wire.txCategory || 'Pilih Kategori'"></span>
                                    <x-lucide-chevron-down class="text-slate-400 transition-transform shadow-none w-3.5 h-3.5" x-bind:class="open ? 'rotate-180' : ''" />
                                </button>
                                
                                <div x-show="open" @click.away="open = false" x-transition class="absolute top-full left-0 right-0 mt-1 bg-white border border-slate-100 rounded-2xl shadow-xl z-50 p-1.5 overflow-hidden" style="display: none;">
                                    <div class="p-1.5 border-b border-slate-50 mb-1">
                                        <div class="relative">
                                            <x-lucide-search class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 w-3 h-3" />
                                            <input type="text" x-model="search" placeholder="Cari..." class="w-full bg-slate-50 rounded-lg py-1.5 pl-8 pr-3 text-[11px] outline-none focus:ring-2 focus:ring-emerald-500/10" />
                                        </div>
                                    </div>
                                    <div class="max-h-36 overflow-y-auto custom-scrollbar space-y-0.5">
                                        <template x-for="cat in categories.filter(c => c.toLowerCase().includes(search.toLowerCase()))" :key="cat">
                                            <button type="button" @click="$wire.set('txCategory', cat); open = false" class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-xs font-semibold hover:bg-emerald-50 hover:text-emerald-600 transition-all text-slate-600">
                                                <span x-text="cat"></span>
                                                <x-lucide-check x-show="$wire.txCategory === cat" class="w-3.5 h-3.5 text-emerald-500" />
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            @error('txCategory') <span class="text-rose-500 text-[10px] ml-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Pilih Wallet -->
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Pilih Wallet</label>
                            <div class="relative">
                                <x-lucide-wallet class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" />
                                <select wire:model="txWallet" class="w-full appearance-none bg-slate-50 border border-slate-100 rounded-2xl py-2.5 pl-10 pr-8 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none cursor-pointer hover:bg-slate-100 transition-all" required>
                                    <option value="">Pilih Wallet</option>
                                    @foreach($wallets as $w)
                                        <option value="{{ $w->id }}">{{ $w->name }}</option>
                                    @endforeach
                                </select>
                                <x-lucide-chevron-down class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none w-3.5 h-3.5" />
                            </div>
                        </div>
                    </div>

                    <!-- Nominal -->
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Nominal</label>
                        <div class="relative group">
                            <div x-data="{ raw: @entangle('txAmount'), formatted: '' }" x-init="formatted = raw ? new Intl.NumberFormat('id-ID').format(raw) : ''; $watch('raw', val => { if(!val) formatted = ''; else if(val != String(formatted).replace(/\D/g, '')) formatted = new Intl.NumberFormat('id-ID').format(val); })">
                                <input type="text" x-model="formatted" @input="let val = String(formatted).replace(/\D/g, ''); raw = val ? parseInt(val) : null; formatted = val ? new Intl.NumberFormat('id-ID').format(val) : '';" placeholder="Rp 0" class="w-full bg-emerald-50/20 border-2 border-emerald-500/10 rounded-2xl py-3 px-5 text-xl font-black text-navy-900 focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 outline-none transition-all placeholder:text-slate-200" required />
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Tanggal -->
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Tanggal</label>
                            <div class="relative">
                                <x-lucide-calendar class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" />
                                <input type="date" wire:model="txDate" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-2.5 pl-10 pr-4 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all" required />
                            </div>
                        </div>

                        <!-- Catatan Area -->
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Catatan</label>
                            <div class="relative">
                                <x-lucide-file-text class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" />
                                <input type="text" wire:model="txNotes" placeholder="Contoh: Beli snack sore" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-2.5 pl-10 pr-4 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all" />
                            </div>
                        </div>
                    </div>

                    <!-- Upload Bukti -->
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Upload Bukti (Opsional)</label>
                        <div class="border-2 border-dashed border-slate-200 hover:border-emerald-300 hover:bg-slate-50/50 rounded-2xl p-3 flex items-center justify-between gap-3 transition-all cursor-pointer group">
                            <input type="file" class="hidden" />
                            <div class="flex items-center gap-2.5">
                                <div class="p-2 bg-white rounded-xl shadow-sm border border-slate-100 text-slate-400 group-hover:text-emerald-500 transition-all">
                                    <x-lucide-upload class="w-4 h-4" />
                                </div>
                                <div class="text-left">
                                    <p class="text-xs font-bold text-navy-900">Pilih atau drag dokumen bukti</p>
                                    <p class="text-[9px] text-slate-400">PDF, PNG, JPG (Maks. 5MB)</p>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg">Cari File</span>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 rounded-b-[2.5rem]">
                    <button type="button" wire:click="$set('showAddModal', false)" class="px-5 py-2 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-200 transition-all uppercase tracking-widest active:scale-95">
                        Batal
                    </button>
                    <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white px-7 py-2.5 rounded-xl font-black text-xs shadow-xl shadow-emerald-500/10 active:scale-95 transition-all flex items-center gap-2 uppercase tracking-widest">
                        <x-lucide-check class="w-4 h-4 stroke-[3px]" /> Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Add Wallet Modal -->
    @if($showAddWalletModal)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-navy-950/40 backdrop-blur-sm" wire:click="$set('showAddWalletModal', false)"></div>
        <div class="bg-white translate-z-0 w-full max-w-lg rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden flex flex-col">
            <div class="bg-emerald-500 px-6 py-5 text-white relative flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-white/20 rounded-xl backdrop-blur-md">
                        <x-lucide-wallet class="w-[18px] h-[18px]" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold tracking-tight">Tambah Wallet</h2>
                        <p class="text-emerald-50 text-[10px] opacity-80">Buat dompet baru untuk memisahkan uangmu.</p>
                    </div>
                </div>
                <button wire:click="$set('showAddWalletModal', false)" class="p-1.5 bg-white/15 hover:bg-white/25 rounded-lg transition-colors">
                    <x-lucide-x class="w-[18px] h-[18px]" />
                </button>
            </div>

            <form wire:submit="saveWallet" class="flex flex-col">
                <div class="p-6 space-y-4 overflow-hidden">
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Nama Wallet*</label>
                        <div class="relative group">
                            <x-lucide-smartphone class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" />
                            <input type="text" wire:model="walletName" placeholder="Contoh: Cash, BCA, OVO, Dana" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-2.5 pl-10 pr-4 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all" required />
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Jenis Wallet*</label>
                        <div class="relative group">
                            <x-lucide-tag class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" />
                            <select wire:model="walletType" class="w-full appearance-none bg-slate-50 border border-slate-100 rounded-2xl py-2.5 pl-10 pr-8 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all cursor-pointer" required>
                                <option value="">Pilih jenis</option>
                                <option value="cash">Cash</option>
                                <option value="bank">Bank</option>
                                <option value="e-wallet">E-Wallet</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                            <x-lucide-chevron-down class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none w-3.5 h-3.5" />
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Saldo Awal (Opsional)</label>
                        <div class="relative group">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 font-black text-slate-400 text-xs">Rp</span>
                            <div x-data="{ raw: @entangle('walletBalance'), formatted: '' }" x-init="formatted = raw ? new Intl.NumberFormat('id-ID').format(raw) : ''; $watch('raw', val => { if(!val) formatted = ''; else if(val != String(formatted).replace(/\D/g, '')) formatted = new Intl.NumberFormat('id-ID').format(val); })">
                                <input type="text" x-model="formatted" @input="let val = String(formatted).replace(/\D/g, ''); raw = val ? parseInt(val) : null; formatted = val ? new Intl.NumberFormat('id-ID').format(val) : '';" placeholder="0" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-2.5 pl-10 pr-4 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 rounded-b-[2.5rem]">
                    <button type="button" wire:click="$set('showAddWalletModal', false)" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-200 transition-all uppercase tracking-widest active:scale-95">
                        Batal
                    </button>
                    <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white px-7 py-2.5 rounded-xl font-black text-xs shadow-xl shadow-emerald-500/10 active:scale-95 transition-all flex items-center gap-2 uppercase tracking-widest">
                        <x-lucide-check class="w-4 h-4 stroke-[3px]" /> Simpan Wallet
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Edit Wallet Modal -->
    @if($showEditWalletModal)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-navy-950/40 backdrop-blur-sm" wire:click="$set('showEditWalletModal', false)"></div>
        <div class="bg-white translate-z-0 w-full max-w-lg rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden flex flex-col">
            <div class="bg-emerald-500 px-6 py-5 text-white relative flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-white/20 rounded-xl backdrop-blur-md">
                        <x-lucide-wallet class="w-[18px] h-[18px]" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold tracking-tight">Edit Wallet</h2>
                        <p class="text-emerald-50 text-[10px] opacity-80">Ubah informasi dompetmu.</p>
                    </div>
                </div>
                <button wire:click="$set('showEditWalletModal', false)" class="p-1.5 bg-white/15 hover:bg-white/25 rounded-lg transition-colors">
                    <x-lucide-x class="w-[18px] h-[18px]" />
                </button>
            </div>

            <form wire:submit="updateWallet" class="flex flex-col">
                <div class="p-6 space-y-4 overflow-hidden">
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Nama Wallet*</label>
                        <div class="relative group">
                            <x-lucide-smartphone class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" />
                            <input type="text" wire:model="editWalletName" placeholder="Contoh: Cash, BCA, OVO, Dana" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-2.5 pl-10 pr-4 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all" required />
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Jenis Wallet*</label>
                        <div class="relative group">
                            <x-lucide-tag class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" />
                            <select wire:model="editWalletType" class="w-full appearance-none bg-slate-50 border border-slate-100 rounded-2xl py-2.5 pl-10 pr-8 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all cursor-pointer" required>
                                <option value="">Pilih jenis</option>
                                <option value="cash">Cash</option>
                                <option value="bank">Bank</option>
                                <option value="e-wallet">E-Wallet</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                            <x-lucide-chevron-down class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none w-3.5 h-3.5" />
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Saldo Saat Ini (Tidak dapat diubah manual)</label>
                        <div class="flex items-center gap-2">
                            <div class="relative group flex-1">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 font-black text-slate-400 text-xs">Rp</span>
                                <input type="number" wire:model="editWalletBalance" disabled class="w-full bg-slate-100 border border-slate-200 rounded-2xl py-2.5 pl-10 pr-4 text-xs font-bold text-slate-500 cursor-not-allowed" />
                            </div>
                            <button type="button" wire:click="$set('showTopupModal', true)" class="shrink-0 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 px-4 py-2.5 rounded-2xl font-bold text-xs transition-colors flex items-center gap-1">
                                <x-lucide-plus class="w-4 h-4" /> Top Up
                            </button>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 rounded-b-[2.5rem]">
                    <button type="button" wire:click="$set('showEditWalletModal', false)" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-200 transition-all uppercase tracking-widest active:scale-95">
                        Batal
                    </button>
                    <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white px-7 py-2.5 rounded-xl font-black text-xs shadow-xl shadow-emerald-500/10 active:scale-95 transition-all flex items-center gap-2 uppercase tracking-widest">
                        <x-lucide-check class="w-4 h-4 stroke-[3px]" /> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Add Budget Modal -->
    @if($showAddBudgetModal)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-navy-950/40 backdrop-blur-sm" wire:click="$set('showAddBudgetModal', false)"></div>
        <div class="bg-white translate-z-0 w-full max-w-lg rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden flex flex-col">
            <div class="bg-emerald-500 px-6 py-5 text-white relative flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-white/20 rounded-xl backdrop-blur-md">
                        <x-lucide-target class="w-[18px] h-[18px]" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold tracking-tight">Tambah Budget</h2>
                        <p class="text-emerald-50 text-[10px] opacity-80">Atur limit belanja agar pengeluaran terkontrol.</p>
                    </div>
                </div>
                <button wire:click="$set('showAddBudgetModal', false)" class="p-1.5 bg-white/15 hover:bg-white/25 rounded-lg transition-colors">
                    <x-lucide-x class="w-[18px] h-[18px]" />
                </button>
            </div>

            <form wire:submit="saveBudget" class="flex flex-col">
                <div class="p-6 space-y-4 overflow-hidden">
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Kategori*</label>
                        <div class="relative group">
                            <x-lucide-tag class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" />
                            <select wire:model="budgetCategory" class="w-full appearance-none bg-slate-50 border border-slate-100 rounded-2xl py-2.5 pl-10 pr-8 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all cursor-pointer" required>
                                <option value="">Pilih kategori</option>
                                <option value="Makanan">Makanan</option>
                                <option value="Transportasi">Transportasi</option>
                                <option value="Hiburan">Hiburan</option>
                                <option value="Tagihan">Tagihan</option>
                                <option value="Kesehatan">Kesehatan</option>
                                <option value="Pendidikan">Pendidikan</option>
                                <option value="Belanja">Belanja</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                            <x-lucide-chevron-down class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none w-3.5 h-3.5" />
                        </div>
                        @error('budgetCategory') <span class="text-rose-500 text-[10px] ml-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Periode*</label>
                        <div class="relative group">
                            <x-lucide-calendar class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" />
                            <select wire:model="budgetPeriod" class="w-full appearance-none bg-slate-50 border border-slate-100 rounded-2xl py-2.5 pl-10 pr-8 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all cursor-pointer" required>
                                <option value="monthly">Bulanan</option>
                                <option value="weekly">Mingguan</option>
                            </select>
                            <x-lucide-chevron-down class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none w-3.5 h-3.5" />
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Total Limit Budget*</label>
                        <div class="relative group">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 font-black text-slate-400 text-xs">Rp</span>
                            <div x-data="{ raw: @entangle('budgetLimit'), formatted: '' }" x-init="formatted = raw ? new Intl.NumberFormat('id-ID').format(raw) : ''; $watch('raw', val => { if(!val) formatted = ''; else if(val != String(formatted).replace(/\D/g, '')) formatted = new Intl.NumberFormat('id-ID').format(val); })">
                                <input type="text" x-model="formatted" @input="let val = String(formatted).replace(/\D/g, ''); raw = val ? parseInt(val) : null; formatted = val ? new Intl.NumberFormat('id-ID').format(val) : '';" placeholder="0" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-2.5 pl-10 pr-4 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all" required />
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <x-lucide-bell class="w-5 h-5 text-emerald-500" />
                            <div>
                                <h4 class="text-xs font-bold text-navy-900 tracking-tight">Ingatkan saat mendekati limit</h4>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">NORMAL: 80%</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="budgetNotify" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Catatan (Opsional)</label>
                        <div class="relative group">
                            <x-lucide-info class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" />
                            <input type="text" wire:model="budgetNote" placeholder="Contoh: Maksimal makan di luar bulan ini" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-2.5 pl-10 pr-4 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all" />
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 rounded-b-[2.5rem]">
                    <button type="button" wire:click="$set('showAddBudgetModal', false)" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-200 transition-all uppercase tracking-widest active:scale-95">
                        Batal
                    </button>
                    <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white px-7 py-2.5 rounded-xl font-black text-xs shadow-xl shadow-emerald-500/10 active:scale-95 transition-all flex items-center gap-2 uppercase tracking-widest">
                        <x-lucide-check class="w-4 h-4 stroke-[3px]" /> Simpan Budget
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Edit Budget Modal -->
    @if($showEditBudgetModal)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-navy-950/40 backdrop-blur-sm" wire:click="$set('showEditBudgetModal', false)"></div>
        <div class="bg-white translate-z-0 w-full max-w-lg rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden flex flex-col">
            <div class="bg-emerald-500 px-6 py-5 text-white relative flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-white/20 rounded-xl backdrop-blur-md">
                        <x-lucide-target class="w-[18px] h-[18px]" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold tracking-tight">Edit Budget</h2>
                        <p class="text-emerald-50 text-[10px] opacity-80">Ubah pengaturan budget Anda.</p>
                    </div>
                </div>
                <button wire:click="$set('showEditBudgetModal', false)" class="p-1.5 bg-white/15 hover:bg-white/25 rounded-lg transition-colors">
                    <x-lucide-x class="w-[18px] h-[18px]" />
                </button>
            </div>

            <form wire:submit="updateBudget" class="flex flex-col">
                <div class="p-6 space-y-4 overflow-hidden">
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Kategori (Tidak dapat diubah)</label>
                        <div class="relative group">
                            <x-lucide-tag class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" />
                            <input type="text" wire:model="budgetCategory" disabled class="w-full bg-slate-100 border border-slate-200 rounded-2xl py-2.5 pl-10 pr-4 text-xs font-bold text-slate-500 cursor-not-allowed" />
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Periode*</label>
                        <div class="relative group">
                            <x-lucide-calendar class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" />
                            <select wire:model="budgetPeriod" class="w-full appearance-none bg-slate-50 border border-slate-100 rounded-2xl py-2.5 pl-10 pr-8 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all cursor-pointer" required>
                                <option value="monthly">Bulanan</option>
                                <option value="weekly">Mingguan</option>
                            </select>
                            <x-lucide-chevron-down class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none w-3.5 h-3.5" />
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Total Limit Budget*</label>
                        <div class="relative group">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 font-black text-slate-400 text-xs">Rp</span>
                            <div x-data="{ raw: @entangle('budgetLimit'), formatted: '' }" x-init="formatted = raw ? new Intl.NumberFormat('id-ID').format(raw) : ''; $watch('raw', val => { if(!val) formatted = ''; else if(val != String(formatted).replace(/\D/g, '')) formatted = new Intl.NumberFormat('id-ID').format(val); })">
                                <input type="text" x-model="formatted" @input="let val = String(formatted).replace(/\D/g, ''); raw = val ? parseInt(val) : null; formatted = val ? new Intl.NumberFormat('id-ID').format(val) : '';" placeholder="0" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-2.5 pl-10 pr-4 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all" required />
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <x-lucide-bell class="w-5 h-5 text-emerald-500" />
                            <div>
                                <h4 class="text-xs font-bold text-navy-900 tracking-tight">Ingatkan saat mendekati limit</h4>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">NORMAL: 80%</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="budgetNotify" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Catatan (Opsional)</label>
                        <div class="relative group">
                            <x-lucide-info class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" />
                            <input type="text" wire:model="budgetNote" placeholder="Contoh: Maksimal makan di luar bulan ini" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-2.5 pl-10 pr-4 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all" />
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 rounded-b-[2.5rem]">
                    <button type="button" wire:click="$set('showEditBudgetModal', false)" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-200 transition-all uppercase tracking-widest active:scale-95">
                        Batal
                    </button>
                    <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white px-7 py-2.5 rounded-xl font-black text-xs shadow-xl shadow-emerald-500/10 active:scale-95 transition-all flex items-center gap-2 uppercase tracking-widest">
                        <x-lucide-check class="w-4 h-4 stroke-[3px]" /> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Add Bill Modal -->
    @if($showAddBillModal)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" wire:click="$set('showAddBillModal', false)"></div>
        <div class="bg-white translate-z-0 w-full max-w-2xl rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden flex flex-col">
            <div class="bg-emerald-500 px-6 py-5 text-white relative flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-white/20 rounded-xl backdrop-blur-md">
                        <x-lucide-smartphone class="w-[18px] h-[18px]" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold tracking-tight">Tambah Tagihan</h2>
                        <p class="text-emerald-50 text-[10px] opacity-80">Catat tagihan agar tidak telat bayar.</p>
                    </div>
                </div>
                <button wire:click="$set('showAddBillModal', false)" class="p-1.5 bg-white/15 hover:bg-white/25 rounded-lg transition-colors">
                    <x-lucide-x class="w-[18px] h-[18px]" />
                </button>
            </div>

            <form wire:submit="saveBill" class="flex flex-col">
                <div class="p-6 space-y-5 overflow-y-auto max-h-[70vh]">
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Nama Tagihan*</label>
                        <div class="relative group">
                            <x-lucide-smartphone class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" />
                            <input type="text" wire:model="billName" placeholder="Contoh: WiFi, Listrik, Netflix" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-3 pl-10 pr-4 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all" required />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Kategori Transaksi*</label>
                            <div class="relative group">
                                <x-lucide-tag class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" />
                                <select wire:model.live="billCategory" class="w-full appearance-none bg-slate-50 border border-slate-100 rounded-2xl py-3 pl-10 pr-8 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all cursor-pointer" required>
                                    <option value="Listrik">Listrik</option>
                                    <option value="Air">Air</option>
                                    <option value="Internet">Internet</option>
                                    <option value="Cicilan">Cicilan</option>
                                    <option value="Langganan">Langganan</option>
                                    <option value="Sewa">Sewa</option>
                                    <option value="Asuransi">Asuransi</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                                <x-lucide-chevron-down class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none w-3.5 h-3.5" />
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Bayar dari Wallet*</label>
                            <div class="relative group">
                                <x-lucide-wallet class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" />
                                <select wire:model="billWallet" class="w-full appearance-none bg-slate-50 border border-slate-100 rounded-2xl py-3 pl-10 pr-8 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all cursor-pointer" required>
                                    <option value="">Pilih wallet</option>
                                    @foreach(auth()->user()->wallets as $w)
                                        <option value="{{ $w->id }}">{{ $w->name }}</option>
                                    @endforeach
                                </select>
                                <x-lucide-chevron-down class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none w-3.5 h-3.5" />
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Nominal Tagihan*</label>
                            <div class="relative group">
                                <x-lucide-credit-card class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" />
                                <span class="absolute left-10 top-1/2 -translate-y-1/2 font-black text-slate-400 text-xs">Rp</span>
                                <div x-data="{ raw: @entangle('billAmount'), formatted: '' }" x-init="formatted = raw ? new Intl.NumberFormat('id-ID').format(raw) : ''; $watch('raw', val => { if(!val) formatted = ''; else if(val != String(formatted).replace(/\D/g, '')) formatted = new Intl.NumberFormat('id-ID').format(val); })">
                                    <input type="text" x-model="formatted" @input="let val = String(formatted).replace(/\D/g, ''); raw = val ? parseInt(val) : null; formatted = val ? new Intl.NumberFormat('id-ID').format(val) : '';" placeholder="0" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-3 pl-[3.5rem] pr-4 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all" required />
                                </div>
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Jatuh Tempo*</label>
                            <div class="relative group">
                                <x-lucide-calendar class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4 z-10 pointer-events-none" />
                                <input type="date" wire:model="billDate" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-3 pl-10 pr-4 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all relative z-0" required />
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <x-lucide-bell class="w-5 h-5 text-amber-500" />
                                <div>
                                    <h4 class="text-xs font-bold text-navy-900 tracking-tight">Pengingat</h4>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">3 HARI SEBELUM</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="billReminder" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                            </label>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <x-lucide-repeat class="w-5 h-5 text-blue-500" />
                                <div>
                                    <h4 class="text-xs font-bold text-navy-900 tracking-tight">Ulangi</h4>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">BULANAN</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="billRepeat" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Catatan (Opsional)</label>
                        <div class="relative group">
                            <x-lucide-info class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" />
                            <input type="text" wire:model="billNote" placeholder="Bayar sebelum jam 5 sore" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-3 pl-10 pr-4 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all" />
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 rounded-b-[2.5rem]">
                    <button type="button" wire:click="$set('showAddBillModal', false)" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-200 transition-all uppercase tracking-widest active:scale-95">
                        Batal
                    </button>
                    <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white px-7 py-2.5 rounded-xl font-black text-xs shadow-xl shadow-emerald-500/10 active:scale-95 transition-all flex items-center gap-2 uppercase tracking-widest">
                        <x-lucide-check class="w-4 h-4 stroke-[3px]" /> Simpan Tagihan
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
    @if($showEditBillModal)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" wire:click="$set('showEditBillModal', false)"></div>
        <div class="bg-white translate-z-0 w-full max-w-2xl rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden flex flex-col">
            <div class="bg-emerald-500 px-6 py-5 text-white relative flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-white/20 rounded-xl backdrop-blur-md">
                        <x-lucide-pencil class="w-[18px] h-[18px]" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold tracking-tight">Edit Tagihan</h2>
                        <p class="text-emerald-50 text-[10px] opacity-80">Perbarui informasi tagihan Anda.</p>
                    </div>
                </div>
                <button wire:click="$set('showEditBillModal', false)" class="p-1.5 bg-white/15 hover:bg-white/25 rounded-lg transition-colors">
                    <x-lucide-x class="w-[18px] h-[18px]" />
                </button>
            </div>

            <form wire:submit="updateBill" class="flex flex-col">
                <div class="p-6 space-y-5 overflow-y-auto max-h-[70vh]">
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Nama Tagihan*</label>
                        <div class="relative group">
                            <x-lucide-smartphone class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" />
                            <input type="text" wire:model="billName" placeholder="Contoh: WiFi, Listrik, Netflix" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-3 pl-10 pr-4 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all" required />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Kategori Transaksi*</label>
                            <div class="relative group">
                                <x-lucide-tag class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" />
                                <select wire:model.live="billCategory" class="w-full appearance-none bg-slate-50 border border-slate-100 rounded-2xl py-3 pl-10 pr-8 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all cursor-pointer" required>
                                    <option value="Listrik">Listrik</option>
                                    <option value="Air">Air</option>
                                    <option value="Internet">Internet</option>
                                    <option value="Cicilan">Cicilan</option>
                                    <option value="Langganan">Langganan</option>
                                    <option value="Sewa">Sewa</option>
                                    <option value="Asuransi">Asuransi</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                                <x-lucide-chevron-down class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none w-3.5 h-3.5" />
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Bayar dari Wallet*</label>
                            <div class="relative group">
                                <x-lucide-wallet class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" />
                                <select wire:model="billWallet" class="w-full appearance-none bg-slate-50 border border-slate-100 rounded-2xl py-3 pl-10 pr-8 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all cursor-pointer" required>
                                    <option value="">Pilih wallet</option>
                                    @foreach(auth()->user()->wallets as $w)
                                        <option value="{{ $w->id }}">{{ $w->name }}</option>
                                    @endforeach
                                </select>
                                <x-lucide-chevron-down class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none w-3.5 h-3.5" />
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Nominal Tagihan*</label>
                            <div class="relative group">
                                <x-lucide-credit-card class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" />
                                <span class="absolute left-10 top-1/2 -translate-y-1/2 font-black text-slate-400 text-xs">Rp</span>
                                <div x-data="{ raw: @entangle('billAmount'), formatted: '' }" x-init="formatted = raw ? new Intl.NumberFormat('id-ID').format(raw) : ''; $watch('raw', val => { if(!val) formatted = ''; else if(val != String(formatted).replace(/\D/g, '')) formatted = new Intl.NumberFormat('id-ID').format(val); })">
                                    <input type="text" x-model="formatted" @input="let val = String(formatted).replace(/\D/g, ''); raw = val ? parseInt(val) : null; formatted = val ? new Intl.NumberFormat('id-ID').format(val) : '';" placeholder="0" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-3 pl-[3.5rem] pr-4 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all" required />
                                </div>
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Jatuh Tempo*</label>
                            <div class="relative group">
                                <x-lucide-calendar class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4 z-10 pointer-events-none" />
                                <input type="date" wire:model="billDate" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-3 pl-10 pr-4 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all relative z-0" required />
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <x-lucide-bell class="w-5 h-5 text-amber-500" />
                                <div>
                                    <h4 class="text-xs font-bold text-navy-900 tracking-tight">Pengingat</h4>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">3 HARI SEBELUM</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="billReminder" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                            </label>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <x-lucide-repeat class="w-5 h-5 text-blue-500" />
                                <div>
                                    <h4 class="text-xs font-bold text-navy-900 tracking-tight">Ulangi</h4>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">BULANAN</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="billRepeat" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Catatan (Opsional)</label>
                        <div class="relative group">
                            <x-lucide-info class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" />
                            <input type="text" wire:model="billNote" placeholder="Bayar sebelum jam 5 sore" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-3 pl-10 pr-4 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all" />
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 rounded-b-[2.5rem]">
                    <button type="button" wire:click="$set('showEditBillModal', false)" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-200 transition-all uppercase tracking-widest active:scale-95">
                        Batal
                    </button>
                    <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white px-7 py-2.5 rounded-xl font-black text-xs shadow-xl shadow-emerald-500/10 active:scale-95 transition-all flex items-center gap-2 uppercase tracking-widest">
                        <x-lucide-check class="w-4 h-4 stroke-[3px]" /> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Pay Confirmation Modal -->
    @if($showPayConfirmModal)
    <div class="fixed inset-0 z-[110] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" wire:click="$set('showPayConfirmModal', false)"></div>
        <div class="bg-white translate-z-0 w-full max-w-sm rounded-[2rem] shadow-2xl relative z-10 overflow-hidden flex flex-col items-center p-8 text-center animate-in zoom-in-95 duration-200">
            <div class="w-16 h-16 bg-amber-50 rounded-[1.25rem] flex items-center justify-center mb-6">
                <x-dynamic-component :component="'lucide-' . $payBillIcon" class="w-8 h-8 text-amber-500" />
            </div>
            <h2 class="text-xl font-bold text-navy-900 tracking-tight mb-2">Konfirmasi Pembayaran</h2>
            <p class="text-slate-500 text-sm leading-relaxed mb-4">
                Apakah kamu yakin ingin membayar tagihan <strong class="text-navy-900">{{ $payBillTitle }}</strong> sebesar <strong class="text-emerald-500 font-black">Rp {{ number_format($payBillAmount, 0, ',', '.') }}</strong>?
            </p>

            @error('payError')
                <div class="w-full bg-rose-50 border border-rose-200 text-rose-600 rounded-xl p-3 text-xs font-bold mb-4 text-left flex items-start gap-2">
                    <x-lucide-alert-circle class="w-4 h-4 shrink-0 mt-0.5" />
                    <span>{{ $message }}</span>
                </div>
            @enderror
            
            <div class="flex items-center gap-3 w-full mt-4">
                <button wire:click="$set('showPayConfirmModal', false)" class="flex-1 py-3.5 rounded-2xl text-xs font-bold text-slate-500 hover:bg-slate-50 transition-colors uppercase tracking-widest active:scale-95">
                    Batal
                </button>
                <button wire:click="executePayBill" class="flex-1 bg-emerald-500 hover:bg-emerald-600 text-white py-3.5 rounded-2xl font-black text-xs shadow-xl shadow-emerald-500/20 active:scale-95 transition-all uppercase tracking-widest">
                    Bayar Sekarang
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteConfirmModal)
    <div class="fixed inset-0 z-[110] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" wire:click="$set('showDeleteConfirmModal', false)"></div>
        <div class="bg-white translate-z-0 w-full max-w-sm rounded-[2rem] shadow-2xl relative z-10 overflow-hidden flex flex-col items-center p-8 text-center animate-in zoom-in-95 duration-200">
            <div class="w-16 h-16 bg-rose-50 rounded-[1.25rem] flex items-center justify-center mb-6">
                <x-lucide-trash-2 class="w-8 h-8 text-rose-500" />
            </div>
            <h2 class="text-xl font-bold text-navy-900 tracking-tight mb-2">Konfirmasi Hapus</h2>
            <p class="text-slate-500 text-sm leading-relaxed mb-8">
                Apakah kamu yakin ingin menghapus <strong class="text-navy-900">{{ $deleteTitle }}</strong>? Tindakan ini tidak dapat dibatalkan.
            </p>
            
            <div class="flex items-center gap-3 w-full">
                <button wire:click="$set('showDeleteConfirmModal', false)" class="flex-1 py-3.5 rounded-2xl text-xs font-bold text-slate-500 hover:bg-slate-50 transition-colors uppercase tracking-widest active:scale-95">
                    Batal
                </button>
                <button wire:click="executeDelete" class="flex-1 bg-rose-500 hover:bg-rose-600 text-white py-3.5 rounded-2xl font-black text-xs shadow-xl shadow-rose-500/20 active:scale-95 transition-all uppercase tracking-widest">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Transfer Modal -->
    @if($showTransferModal)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" wire:click="$set('showTransferModal', false)"></div>
        <div class="bg-white translate-z-0 w-full max-w-md rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden flex flex-col">
            <div class="bg-emerald-500 px-6 py-5 text-white relative flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-white/20 rounded-xl backdrop-blur-md">
                        <x-lucide-arrow-right-left class="w-[18px] h-[18px]" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold tracking-tight">Transfer Saldo</h2>
                        <p class="text-emerald-50 text-[10px] opacity-80">Pindahkan dana antar wallet Anda.</p>
                    </div>
                </div>
                <button wire:click="$set('showTransferModal', false)" class="p-1.5 bg-white/15 hover:bg-white/25 rounded-lg transition-colors">
                    <x-lucide-x class="w-[18px] h-[18px]" />
                </button>
            </div>

            <form wire:submit="executeTransfer" class="flex flex-col">
                <div class="p-6 space-y-5">
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Dari Wallet*</label>
                        <div class="relative group">
                            <x-lucide-log-out class="absolute left-3.5 top-1/2 -translate-y-1/2 text-rose-400 w-4 h-4" />
                            <select wire:model="transferFromId" class="w-full appearance-none bg-slate-50 border border-slate-100 rounded-2xl py-3 pl-10 pr-8 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all cursor-pointer" required>
                                <option value="">Pilih asal dana</option>
                                @foreach(auth()->user()->wallets as $w)
                                    <option value="{{ $w->id }}">{{ $w->name }} (Rp {{ number_format($w->balance, 0, ',', '.') }})</option>
                                @endforeach
                            </select>
                            <x-lucide-chevron-down class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none w-3.5 h-3.5" />
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Ke Wallet Tujuan*</label>
                        <div class="relative group">
                            <x-lucide-log-in class="absolute left-3.5 top-1/2 -translate-y-1/2 text-emerald-400 w-4 h-4" />
                            <select wire:model="transferToId" class="w-full appearance-none bg-slate-50 border border-slate-100 rounded-2xl py-3 pl-10 pr-8 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all cursor-pointer" required>
                                <option value="">Pilih tujuan dana</option>
                                @foreach(auth()->user()->wallets as $w)
                                    <option value="{{ $w->id }}">{{ $w->name }} (Rp {{ number_format($w->balance, 0, ',', '.') }})</option>
                                @endforeach
                            </select>
                            <x-lucide-chevron-down class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none w-3.5 h-3.5" />
                        </div>
                        @error('transferToId') <span class="text-rose-500 text-[10px] ml-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Nominal Transfer*</label>
                        <div class="relative group">
                            <x-lucide-credit-card class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" />
                            <span class="absolute left-10 top-1/2 -translate-y-1/2 font-black text-slate-400 text-xs">Rp</span>
                            <div x-data="{ raw: @entangle('transferAmount'), formatted: '' }" x-init="formatted = raw ? new Intl.NumberFormat('id-ID').format(raw) : ''; $watch('raw', val => { if(!val) formatted = ''; else if(val != String(formatted).replace(/\D/g, '')) formatted = new Intl.NumberFormat('id-ID').format(val); })">
                                <input type="text" x-model="formatted" @input="let val = String(formatted).replace(/\D/g, ''); raw = val ? parseInt(val) : null; formatted = val ? new Intl.NumberFormat('id-ID').format(val) : '';" placeholder="0" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-3 pl-[3.5rem] pr-4 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all" required />
                            </div>
                        </div>
                        @error('transferAmount') <span class="text-rose-500 text-[10px] ml-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Catatan (Opsional)</label>
                        <div class="relative group">
                            <x-lucide-info class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" />
                            <input type="text" wire:model="transferNote" placeholder="Cth: Pindah dana darurat" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-3 pl-10 pr-4 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all" />
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 rounded-b-[2.5rem]">
                    <button type="button" wire:click="$set('showTransferModal', false)" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-200 transition-all uppercase tracking-widest active:scale-95">
                        Batal
                    </button>
                    <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white px-7 py-2.5 rounded-xl font-black text-xs shadow-xl shadow-emerald-500/10 active:scale-95 transition-all flex items-center gap-2 uppercase tracking-widest">
                        <x-lucide-check class="w-4 h-4 stroke-[3px]" /> Transfer Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Top Up Modal (Opened from Edit Wallet) -->
    @if($showTopupModal)
    <div class="fixed inset-0 z-[120] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="$set('showTopupModal', false)"></div>
        <div class="bg-white translate-z-0 w-full max-w-sm rounded-[2rem] shadow-2xl relative z-10 overflow-hidden flex flex-col items-center p-8 text-center animate-in zoom-in-95 duration-200">
            <div class="w-16 h-16 bg-emerald-50 rounded-[1.25rem] flex items-center justify-center mb-6">
                <x-lucide-plus-circle class="w-8 h-8 text-emerald-500" />
            </div>
            <h2 class="text-xl font-bold text-navy-900 tracking-tight mb-2">Top Up Saldo</h2>
            <p class="text-slate-500 text-sm leading-relaxed mb-6">
                Masukkan nominal yang ingin ditambahkan ke dompet ini.
            </p>
            
            <form wire:submit="executeTopup" class="w-full flex flex-col gap-5">
                <div class="space-y-1.5 text-left">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Nominal Top Up*</label>
                    <div class="relative group">
                        <x-lucide-coins class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" />
                        <span class="absolute left-10 top-1/2 -translate-y-1/2 font-black text-slate-400 text-xs">Rp</span>
                        <div x-data="{ raw: @entangle('topupAmount'), formatted: '' }" x-init="formatted = raw ? new Intl.NumberFormat('id-ID').format(raw) : ''; $watch('raw', val => { if(!val) formatted = ''; else if(val != String(formatted).replace(/\D/g, '')) formatted = new Intl.NumberFormat('id-ID').format(val); })">
                            <input type="text" x-model="formatted" @input="let val = String(formatted).replace(/\D/g, ''); raw = val ? parseInt(val) : null; formatted = val ? new Intl.NumberFormat('id-ID').format(val) : '';" placeholder="0" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-3 pl-[3.5rem] pr-4 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all" required autofocus />
                        </div>
                    </div>
                    @error('topupAmount') <span class="text-rose-500 text-[10px] ml-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="flex items-center gap-3 w-full mt-2">
                    <button type="button" wire:click="$set('showTopupModal', false)" class="flex-1 py-3.5 rounded-2xl text-xs font-bold text-slate-500 hover:bg-slate-50 transition-colors uppercase tracking-widest active:scale-95">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 bg-emerald-500 hover:bg-emerald-600 text-white py-3.5 rounded-2xl font-black text-xs shadow-xl shadow-emerald-500/20 active:scale-95 transition-all uppercase tracking-widest">
                        Konfirmasi
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
