<div class="space-y-8 pb-12" x-data="{ chartRange: 'daily' }">
    <!-- Header Content -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Statistik Keuangan</h1>
            <p class="text-slate-500 mt-1">Analisis mendalam pola transaksi dan kebiasaan belanjamu.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2 bg-white px-4 py-2.5 rounded-2xl border border-slate-100 shadow-sm text-sm font-bold text-slate-600 cursor-pointer hover:bg-slate-50 transition-colors">
                <x-lucide-calendar class="w-4 h-4 text-emerald-500" /> {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </div>
        </div>
    </div>

    @php
        $isNewUser = ($incomeThisMonth == 0 && $expenseThisMonth == 0 && $incomeLastMonth == 0 && $expenseLastMonth == 0);
        
        $incDiff = $incomeThisMonth - $incomeLastMonth;
        $incPercent = $incomeLastMonth > 0 ? round(($incDiff / $incomeLastMonth) * 100) : ($incomeThisMonth > 0 ? 100 : 0);
        $incIsPositive = $incDiff >= 0;

        $expDiff = $expenseThisMonth - $expenseLastMonth;
        $expPercent = $expenseLastMonth > 0 ? round(($expDiff / $expenseLastMonth) * 100) : ($expenseThisMonth > 0 ? 100 : 0);
        $expIsPositive = $expDiff <= 0; // for expense, less is positive (good)
    @endphp

    <!-- SECTION 1 — Insight Perbandingan -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Pemasukan -->
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center justify-between group hover:shadow-md transition-all hover:-translate-y-1">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-2.5 py-1 bg-blue-50 text-blue-600 font-bold text-[10px] uppercase tracking-widest rounded-lg">Bulan Ini</span>
                    <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Pemasukan</h4>
                </div>
                <p class="text-2xl font-black text-navy-900">Rp {{ number_format($incomeThisMonth, 0, ',', '.') }}</p>
            </div>
            <div class="text-right">
                <div class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl font-bold text-xs mb-1 {{ $incIsPositive ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                    @if($incIsPositive) <x-lucide-trending-up class="w-3.5 h-3.5" /> @else <x-lucide-trending-down class="w-3.5 h-3.5" /> @endif
                    {{ $incIsPositive ? '+' : '' }}{{ $incPercent }}%
                </div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">vs Bulan Lalu</p>
            </div>
        </div>

        <!-- Pengeluaran -->
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center justify-between group hover:shadow-md transition-all hover:-translate-y-1">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-2.5 py-1 bg-rose-50 text-rose-600 font-bold text-[10px] uppercase tracking-widest rounded-lg">Bulan Ini</span>
                    <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Pengeluaran</h4>
                </div>
                <p class="text-2xl font-black text-navy-900">Rp {{ number_format($expenseThisMonth, 0, ',', '.') }}</p>
            </div>
            <div class="text-right">
                <div class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl font-bold text-xs mb-1 {{ $expIsPositive ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                    @if($expDiff > 0) <x-lucide-trending-up class="w-3.5 h-3.5" /> @else <x-lucide-trending-down class="w-3.5 h-3.5" /> @endif
                    {{ $expDiff > 0 ? '+' : '' }}{{ $expPercent }}%
                </div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">vs Bulan Lalu</p>
            </div>
        </div>
    </div>

    <!-- SECTION 2 — Charts (Area & Pie) -->
    <div class="grid grid-cols-1 gap-6">
        
        <!-- Area Chart: Pemasukan vs Pengeluaran -->
        <div class="w-full bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm space-y-8 relative">
            @if($mostWastefulDay)
                <div class="absolute top-8 right-8 text-right hidden md:block">
                    <p class="text-[10px] font-black text-rose-400 uppercase tracking-widest mb-1">Hari Paling Boros</p>
                    <p class="text-xs font-bold text-navy-900">{{ \Carbon\Carbon::parse($mostWastefulDay->date)->translatedFormat('d F') }} <span class="text-slate-400 font-medium">(Rp {{ number_format($mostWastefulDay->total, 0, ',', '.') }})</span></p>
                </div>
            @endif

            <div class="space-y-4">
                <h3 class="text-xl font-bold text-navy-900">Pemasukan vs Pengeluaran</h3>
                
                <div class="flex p-1 bg-slate-50 rounded-2xl border border-slate-100 w-fit">
                    <button @click="chartRange = 'daily'; updateAreaChart()" :class="chartRange === 'daily' ? 'bg-white text-navy-900 shadow-sm' : 'text-slate-400 hover:text-slate-600'" class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all">Harian</button>
                    <button @click="chartRange = 'monthly'; updateAreaChart()" :class="chartRange === 'monthly' ? 'bg-white text-navy-900 shadow-sm' : 'text-slate-400 hover:text-slate-600'" class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all">Bulanan</button>
                </div>
            </div>

            <div class="h-[300px] w-full" x-data="areaChartData()" x-init="initChart()">
                <canvas id="areaChart"></canvas>
            </div>
        </div>

        <!-- Donut Chart: Kategori -->
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm flex flex-col">
            <h3 class="text-xl font-bold text-navy-900 mb-6">Kategori Pengeluaran</h3>

            <div class="h-[200px] w-full flex items-center justify-center relative mb-8" x-data="donutChartData()" x-init="initChart()">
                <canvas id="donutChart"></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none mt-2">
                    <span class="text-2xl font-black text-navy-900">
                        @php
                            $biggestPercent = 0;
                            if(count($categoryData) > 0 && $expenseThisMonth > 0) {
                                $biggestPercent = round(($categoryData[0]->value / $expenseThisMonth) * 100);
                            }
                        @endphp
                        {{ $isNewUser ? '0%' : $biggestPercent . '%' }}
                    </span>
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Terbesar</span>
                </div>
            </div>

            <div class="space-y-2 flex-1 overflow-y-auto pr-2 custom-scrollbar">
                @php
                    $colors = ['#ef4444', '#f59e0b', '#10b981', '#3b82f6', '#8b5cf6', '#ec4899', '#14b8a6'];
                    if (count($categoryData) === 0) {
                        $catList = [['name' => 'Belum Ada Data', 'value' => 0, 'color' => '#cbd5e1']];
                    } else {
                        $catList = [];
                        foreach($categoryData as $idx => $cat) {
                            $catList[] = [
                                'name' => $cat->name,
                                'value' => $cat->value,
                                'color' => $colors[$idx % count($colors)]
                            ];
                        }
                    }
                @endphp
                @foreach($catList as $item)
                    <div class="flex items-center justify-between p-3.5 bg-slate-50/50 rounded-2xl hover:bg-slate-50 transition-colors group">
                        <div class="flex items-center gap-3">
                            <div class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $item['color'] }}"></div>
                            <span class="text-sm font-bold text-slate-600 group-hover:text-navy-900 transition-colors">{{ $item['name'] }}</span>
                        </div>
                        <span class="text-sm font-black text-navy-900 tabular-nums">
                            Rp {{ number_format($item['value'], 0, ',', '.') }}
                        </span>
                    </div>
                @endforeach
            </div>
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
            @php
                $dbBudgets = auth()->user()->budgets;
                $activeBudgets = [];
                foreach($dbBudgets as $b) {
                    $spent = auth()->user()->transactions()
                        ->where('type', 'expense')
                        ->where('category', $b->category)
                        ->whereMonth('transaction_date', now()->month)
                        ->whereYear('transaction_date', now()->year)
                        ->sum('amount');
                    
                    $usage = $b->limit_amount > 0 ? round(($spent / $b->limit_amount) * 100) : 0;
                    $remaining = $b->limit_amount - $spent;
                    
                    $status = 'safe';
                    if ($usage >= 100) $status = 'over';
                    elseif ($usage >= 80) $status = 'risk';
                    
                    $activeBudgets[] = [
                        'name' => $b->category,
                        'usage' => $usage,
                        'remaining' => $remaining,
                        'status' => $status
                    ];
                }
            @endphp
            @if(count($activeBudgets) === 0)
                <div class="col-span-3 py-12 text-center bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                    <p class="text-slate-400 font-bold text-sm">Belum ada budget aktif di periode ini.</p>
                </div>
            @else
                @foreach($activeBudgets as $budget)
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
                                <div class="h-1.5 bg-slate-200/50 rounded-full overflow-hidden" x-data="{ showBar: false }" x-init="setTimeout(() => showBar = true, 50)">
                                    <div class="h-full rounded-full transition-all duration-1000 ease-out {{ $budget['status'] === 'safe' ? 'bg-emerald-500' : ($budget['status'] === 'risk' ? 'bg-amber-500' : 'bg-rose-500') }}" :style="'width: ' + (showBar ? {{ min($budget['usage'], 100) }} : 0) + '%'"></div>
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
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('areaChartData', () => ({
            chartInstance: null,
            dailyData: @json($dailyData),
            monthlyData: @json($monthlyData),
            initChart() {
                const ctx = document.getElementById('areaChart').getContext('2d');
                
                // Gradients
                let gradientIncome = ctx.createLinearGradient(0, 0, 0, 300);
                gradientIncome.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
                gradientIncome.addColorStop(1, 'rgba(16, 185, 129, 0)');
                
                let gradientExpense = ctx.createLinearGradient(0, 0, 0, 300);
                gradientExpense.addColorStop(0, 'rgba(239, 68, 68, 0.2)');
                gradientExpense.addColorStop(1, 'rgba(239, 68, 68, 0)');

                this.chartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: this.dailyData.labels,
                        datasets: [
                            {
                                label: 'Pemasukan',
                                data: this.dailyData.income,
                                borderColor: '#10b981',
                                backgroundColor: gradientIncome,
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 0,
                                pointHoverRadius: 6
                            },
                            {
                                label: 'Pengeluaran',
                                data: this.dailyData.expense,
                                borderColor: '#ef4444',
                                backgroundColor: gradientExpense,
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 0,
                                pointHoverRadius: 6
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#fff',
                                titleColor: '#94a3b8',
                                bodyColor: '#0f172a',
                                borderColor: '#f1f5f9',
                                borderWidth: 1,
                                padding: 12,
                                boxPadding: 6,
                                usePointStyle: true,
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            label += 'Rp ' + context.parsed.y.toLocaleString();
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { color: '#94a3b8', font: { weight: '500' } }
                            },
                            y: {
                                border: { display: false },
                                grid: { color: '#f1f5f9', borderDash: [3, 3] },
                                ticks: { 
                                    color: '#94a3b8',
                                    callback: function(value) {
                                        return (value / 1000) + 'k';
                                    }
                                }
                            }
                        }
                    }
                });

                this.$parent.updateAreaChart = () => {
                    const dataObj = this.$parent.chartRange === 'daily' ? this.dailyData : this.monthlyData;
                    this.chartInstance.data.labels = dataObj.labels;
                    this.chartInstance.data.datasets[0].data = dataObj.income;
                    this.chartInstance.data.datasets[1].data = dataObj.expense;
                    this.chartInstance.update();
                }
            }
        }));

        Alpine.data('donutChartData', () => ({
            catList: @json($catList),
            initChart() {
                const ctx = document.getElementById('donutChart').getContext('2d');
                
                const labels = this.catList.map(item => item.name);
                const data = this.catList.map(item => item.value > 0 ? item.value : 1);
                const bgColors = this.catList.map(item => item.color);

                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: bgColors,
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '75%',
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        if(context.label === 'Belum Ada Data') return ' Belum Ada Transaksi';
                                        return ' ' + context.label + ': Rp ' + (context.raw).toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }));
    });
</script>
