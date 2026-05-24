<div class="space-y-8 pb-10">
    <!-- Welcome Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Halo, {{ explode(' ', auth()->user()->name)[0] }} 👋</h1>
            <p class="text-slate-500 mt-1">Mari kelola keuanganmu hari ini dengan lebih bijak.</p>
        </div>
        
        <!-- Date Widget -->
        <div class="flex items-center gap-3 bg-white px-5 py-3 rounded-2xl border border-slate-100 shadow-sm self-start sm:self-auto group hover:shadow-md transition-all duration-300">
            <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl group-hover:bg-emerald-500 group-hover:text-white transition-colors duration-300">
                <x-lucide-calendar class="w-[18px] h-[18px]" />
            </div>
            <span class="text-sm font-bold text-slate-800 tracking-tight">
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </span>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        @php
            $isNewUser = ($totalBalance == 0 && $incomeThisMonth == 0 && $expenseThisMonth == 0);
            // Calculate remaining this month
            $sisaBulanIni = $incomeThisMonth - $expenseThisMonth;

            $summaries = [
                ['title' => 'Total Saldo', 'amount' => 'Rp ' . number_format($totalBalance, 0, ',', '.'), 'change' => 'Total aset', 'isPositive' => true, 'icon' => 'wallet', 'color' => 'text-emerald-500', 'bg' => 'bg-emerald-50'],
                ['title' => 'Pemasukan Bulan Ini', 'amount' => 'Rp ' . number_format($incomeThisMonth, 0, ',', '.'), 'change' => 'Bulan ini', 'isPositive' => true, 'icon' => 'arrow-up-right', 'color' => 'text-blue-500', 'bg' => 'bg-blue-50'],
                ['title' => 'Pengeluaran Bulan Ini', 'amount' => 'Rp ' . number_format($expenseThisMonth, 0, ',', '.'), 'change' => 'Bulan ini', 'isPositive' => false, 'icon' => 'arrow-down-right', 'color' => 'text-rose-500', 'bg' => 'bg-rose-50'],
                ['title' => 'Sisa Bulan Ini', 'amount' => 'Rp ' . number_format($sisaBulanIni, 0, ',', '.'), 'change' => 'Dari pemasukan', 'isPositive' => $sisaBulanIni >= 0, 'icon' => 'piggy-bank', 'color' => 'text-amber-500', 'bg' => 'bg-amber-50'],
            ];
        @endphp

        @foreach($summaries as $item)
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 hover:shadow-md transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 rounded-2xl transition-transform group-hover:scale-110 {{ $item['bg'] }}">
                        <x-dynamic-component :component="'lucide-' . $item['icon']" class="w-6 h-6 {{ $item['color'] }}" />
                    </div>
                    <div class="flex items-center gap-1 text-xs font-bold px-2.5 py-1 rounded-full {{ $item['isPositive'] ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }}">
                        {{ $item['change'] }}
                    </div>
                </div>
                <div class="space-y-1">
                    <h3 class="text-slate-500 text-sm font-medium">{{ $item['title'] }}</h3>
                    <p class="text-2xl font-bold text-slate-900 tracking-tight">{{ $item['amount'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Transaksi Terakhir -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-slate-900 tracking-tight">Transaksi Terakhir</h3>
                <p class="text-sm text-slate-400 mt-1">Aktivitas keuangan terbaru Anda.</p>
            </div>
            <a href="{{ route('keuangan') }}" class="p-2 hover:bg-slate-50 rounded-xl text-slate-400 hover:text-emerald-500 transition-colors">
                <x-lucide-arrow-right class="w-5 h-5" />
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Transaksi</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-center">Kategori</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-center">Tipe</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-center">Tanggal</th>
                        <th class="px-8 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @if(count($recentTransactions) === 0)
                        <tr>
                            <td colspan="5" class="px-8 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <x-lucide-receipt class="w-12 h-12 mb-3 text-slate-200" />
                                    <p class="font-bold text-slate-500">Belum ada transaksi</p>
                                    <p class="text-sm">Catat aktivitas pertama Anda di menu Keuangan.</p>
                                </div>
                            </td>
                        </tr>
                    @else
                        @foreach($recentTransactions as $tx)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-8 py-4 font-semibold text-slate-900">{{ $tx->title }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">{{ $tx->category ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center">
                                        @if($tx->type === 'income')
                                            <div class="bg-emerald-50 text-emerald-600 p-1.5 rounded-lg" title="Pemasukan"><x-lucide-arrow-up class="w-3.5 h-3.5" /></div>
                                        @elseif($tx->type === 'saving')
                                            <div class="bg-blue-50 text-blue-600 p-1.5 rounded-lg" title="Tabungan"><x-lucide-target class="w-3.5 h-3.5" /></div>
                                        @else
                                            <div class="bg-rose-50 text-rose-600 p-1.5 rounded-lg" title="Pengeluaran"><x-lucide-arrow-down class="w-3.5 h-3.5" /></div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center text-sm font-medium text-slate-500">
                                    {{ \Carbon\Carbon::parse($tx->transaction_date)->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-8 py-4 font-bold text-right tabular-nums {{ $tx->type === 'income' ? 'text-emerald-500' : ($tx->type === 'saving' ? 'text-blue-500' : 'text-rose-500') }}">
                                    {{ $tx->type === 'income' ? '+' : '-' }}Rp {{ number_format($tx->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
