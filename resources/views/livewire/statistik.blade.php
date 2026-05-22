<div class="space-y-8 pb-12" x-data="{ trendType: @entangle('trendType') }">
    <!-- Header Content -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Statistik Keuangan</h1>
            <p class="text-slate-500 mt-1">Analisis mendalam pola transaksi dan kebiasaan belanjamu.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2 bg-white px-4 py-2.5 rounded-2xl border border-slate-100 shadow-sm text-sm font-bold text-slate-600 cursor-pointer hover:bg-slate-50 transition-colors">
                <x-lucide-calendar class="w-4 h-4 text-emerald-500" /> {{ \Carbon\Carbon::now()->translatedFormat('F Y') }} <x-lucide-chevron-down class="w-3.5 h-3.5" />
            </div>
            <div class="flex items-center gap-2 bg-white px-4 py-2.5 rounded-2xl border border-slate-100 shadow-sm text-sm font-bold text-slate-600 cursor-pointer hover:bg-slate-50 transition-colors">
                <x-lucide-pie-chart class="w-4 h-4 text-blue-500" /> Semua Wallet <x-lucide-chevron-down class="w-3.5 h-3.5" />
            </div>
            <button class="flex items-center gap-2 px-6 py-2.5 bg-navy-900 text-white rounded-2xl text-sm font-bold hover:bg-emerald-600 transition-all shadow-lg shadow-navy-900/10">
                <x-lucide-download class="w-[18px] h-[18px]" /> Export Data
            </button>
        </div>
    </div>

    @php
        $isNewUser = ($incomeThisMonth == 0 && $expenseThisMonth == 0);
    @endphp

    <!-- SECTION 1 — Insight Ringkas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @if($isNewUser)
            <div class="col-span-3 bg-white p-8 rounded-[2rem] border border-slate-100 border-dashed text-center">
                <p class="text-slate-400 font-bold text-sm">Belum cukup data untuk menampilkan insight bulan ini.</p>
            </div>
        @else
            @php
                $insights = [
                    ['title' => 'Pengeluaran Bulan Ini', 'value' => 'Rp ' . number_format($expenseThisMonth, 0, ',', '.'), 'description' => 'Bulan ini'],
                    ['title' => 'Kategori dominan', 'value' => $biggestExpense, 'description' => 'Menghabiskan Rp ' . number_format($biggestExpenseValue, 0, ',', '.')],
                    ['title' => 'Pemasukan Bulan Ini', 'value' => 'Rp ' . number_format($incomeThisMonth, 0, ',', '.'), 'description' => 'Bulan ini'],
                ];
            @endphp
            @foreach($insights as $insight)
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm relative overflow-hidden group hover:shadow-md transition-all hover:-translate-y-1">
                    <div class="absolute top-0 right-0 p-6 opacity-[0.03] text-navy-900 pointer-events-none group-hover:scale-110 transition-transform">
                        <x-lucide-trending-up class="w-20 h-20" />
                    </div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 font-bold text-[10px] uppercase tracking-widest rounded-lg">Insight</span>
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $insight['title'] }}</h4>
                    </div>
                    <p class="text-2xl font-black text-navy-900 mb-1 relative z-10">{{ $insight['value'] }}</p>
                    <p class="text-[11px] text-slate-500 leading-tight font-medium relative z-10">{{ $insight['description'] }}</p>
                </div>
            @endforeach
        @endif
    </div>

    <!-- SECTION 2 — Tren 6 Bulan -->
    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm space-y-8">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-1">
                <h3 class="text-xl font-bold text-navy-900">Tren 6 Bulan Terakhir</h3>
                <div class="flex items-center gap-3">
                    <span class="text-sm text-slate-400 font-medium">Rata-rata: {{ $isNewUser ? 'Rp 0' : 'Rp 5.100.000' }} / bln</span>
                    @if(!$isNewUser)
                        <div class="flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 font-bold text-[10px]">
                            <x-lucide-trending-up class="w-2.5 h-2.5" /> +4.5%
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="flex p-1 bg-slate-50 rounded-2xl border border-slate-100">
                <button @click="trendType = 'expense'; updateChart()" :class="trendType === 'expense' ? 'bg-white text-navy-900 shadow-sm' : 'text-slate-400 hover:text-slate-600'" class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all">Pengeluaran</button>
                <button @click="trendType = 'income'; updateChart()" :class="trendType === 'income' ? 'bg-white text-navy-900 shadow-sm' : 'text-slate-400 hover:text-slate-600'" class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all">Pemasukan</button>
                <button @click="trendType = 'net'; updateChart()" :class="trendType === 'net' ? 'bg-white text-navy-900 shadow-sm' : 'text-slate-400 hover:text-slate-600'" class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all">Net</button>
            </div>
        </div>

        <div class="h-[350px] w-full" x-data="barChartData({{ $isNewUser ? 'true' : 'false' }})" x-init="initChart()">
            <canvas id="barChart"></canvas>
        </div>

        <div class="pt-4 border-t border-slate-50">
            <p class="text-[11px] text-slate-400 font-medium text-center">Bandingkan perubahan antar bulan untuk melihat pola belanjamu.</p>
        </div>
    </div>

    <!-- SECTION 3 — Budget Performance -->
    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-navy-900 text-white rounded-xl">
                    <x-lucide-layout-grid class="w-[18px] h-[18px]" />
                </div>
                <h3 class="text-lg font-bold text-navy-900 tracking-tight">Kinerja Budget</h3>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @if($isNewUser)
                <div class="col-span-3 py-12 text-center bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                    <p class="text-slate-400 font-bold text-sm">Belum ada budget aktif di periode ini.</p>
                </div>
            @else
                @php
                    $dbBudgets = auth()->user()->budgets()->take(3)->get();
                    $budgets = [];
                    foreach($dbBudgets as $b) {
                        $spent = auth()->user()->transactions()
                            ->where('type', 'expense')
                            ->where('category', $b->title)
                            ->whereMonth('transaction_date', now()->month)
                            ->sum('amount');
                        
                        $usage = $b->limit_amount > 0 ? round(($spent / $b->limit_amount) * 100) : 0;
                        $remaining = $b->limit_amount - $spent;
                        
                        $status = 'safe';
                        if ($usage >= 100) $status = 'over';
                        elseif ($usage >= 80) $status = 'risk';
                        
                        $budgets[] = [
                            'name' => $b->title,
                            'usage' => $usage,
                            'remaining' => $remaining,
                            'status' => $status
                        ];
                    }
                @endphp
                @if(count($budgets) === 0)
                    <div class="col-span-3 py-12 text-center bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                        <p class="text-slate-400 font-bold text-sm">Belum ada budget aktif di periode ini.</p>
                    </div>
                @else
                    @foreach($budgets as $budget)
                        <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100 group hover:bg-white hover:shadow-lg transition-all text-left hover:-translate-y-1">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-sm font-bold text-slate-700">{{ $budget['name'] }}</span>
                                <div class="flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest 
                                    {{ $budget['status'] === 'safe' ? 'bg-emerald-50 text-emerald-600' : ($budget['status'] === 'risk' ? 'bg-amber-50 text-amber-600' : 'bg-rose-50 text-rose-600') }}">
                                    @if($budget['status'] === 'safe')
                                        <x-lucide-check-circle-2 class="w-3.5 h-3.5" /> Aman
                                    @elseif($budget['status'] === 'risk')
                                        <x-lucide-alert-circle class="w-3.5 h-3.5" /> Berisiko
                                    @else
                                        <x-lucide-x-circle class="w-3.5 h-3.5" /> Overlimit
                                    @endif
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <div class="flex justify-between text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">
                                        <span>Terpakai</span>
                                        <span class="{{ $budget['usage'] >= 100 ? 'text-rose-500' : ($budget['usage'] >= 80 ? 'text-amber-500' : 'text-emerald-500') }}">{{ $budget['usage'] }}%</span>
                                    </div>
                                    <div class="h-1.5 bg-slate-200/50 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full {{ $budget['status'] === 'safe' ? 'bg-emerald-500' : ($budget['status'] === 'risk' ? 'bg-amber-500' : 'bg-rose-500') }}" style="width: {{ min($budget['usage'], 100) }}%"></div>
                                    </div>
                                </div>
                                
                                <div class="flex justify-between items-end">
                                    <div>
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Sisa Anggaran</p>
                                        <p class="font-bold text-navy-900 tracking-tight">Rp {{ number_format(abs($budget['remaining']), 0, ',', '.') }}</p>
                                    </div>
                                    @if($budget['status'] === 'risk')
                                        <div class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded-lg">
                                            Berisiko over
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('barChartData', (isNewUser) => ({
            chartInstance: null,
            initChart() {
                const ctx = document.getElementById('barChart').getContext('2d');
                
                const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'];
                
                // MOCK DATA
                const dataSets = isNewUser ? {
                    expense: [0, 0, 0, 0, 0, 0],
                    income: [0, 0, 0, 0, 0, 0],
                    net: [0, 0, 0, 0, 0, 0]
                } : {
                    expense: [4500000, 5200000, 4800000, 6100000, 5230000, 0],
                    income: [10000000, 10000000, 10500000, 10000000, 12000000, 0],
                    net: [5500000, 4800000, 5700000, 3900000, 6770000, 0],
                };

                this.chartInstance = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: dataSets[this.trendType],
                            backgroundColor: labels.map(m => m === 'Mei' ? '#10b981' : '#e2e8f0'),
                            borderRadius: 12,
                            borderSkipped: false,
                            barPercentage: 0.5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#0f172a',
                                titleColor: '#94a3b8',
                                bodyColor: '#ffffff',
                                borderColor: 'rgba(255,255,255,0.1)',
                                borderWidth: 1,
                                padding: 12,
                                boxPadding: 6,
                                displayColors: false,
                                callbacks: {
                                    label: function(context) {
                                        return 'Rp ' + context.parsed.y.toLocaleString();
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { color: '#94a3b8', font: { weight: '500' } },
                                border: { display: false }
                            },
                            y: {
                                border: { display: false },
                                grid: { color: '#f1f5f9', borderDash: [3, 3] },
                                ticks: { 
                                    color: '#94a3b8',
                                    callback: function(value) {
                                        return 'Rp ' + (value / 1000000) + 'jt';
                                    }
                                }
                            }
                        }
                    }
                });

                // Alpine function to update chart on tab click
                this.$parent.updateChart = () => {
                    this.chartInstance.data.datasets[0].data = dataSets[this.$parent.trendType];
                    this.chartInstance.update();
                }
            }
        }));
    });
</script>
