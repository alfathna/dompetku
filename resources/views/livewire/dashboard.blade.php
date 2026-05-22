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
            
            // Calculate remaining budget
            $totalBudgetLimit = auth()->user()->budgets()->sum('limit_amount');
            // used budget is just expense this month for categories that have budgets? Or just total expense vs total budget
            $sisaBudget = max(0, $totalBudgetLimit - $expenseThisMonth);

            $summaries = [
                ['title' => 'Total Saldo', 'amount' => 'Rp ' . number_format($totalBalance, 0, ',', '.'), 'change' => 'Total aset', 'isPositive' => true, 'icon' => 'wallet', 'color' => 'text-emerald-500', 'bg' => 'bg-emerald-50'],
                ['title' => 'Pemasukan Bulan Ini', 'amount' => 'Rp ' . number_format($incomeThisMonth, 0, ',', '.'), 'change' => 'Bulan ini', 'isPositive' => true, 'icon' => 'arrow-up-right', 'color' => 'text-blue-500', 'bg' => 'bg-blue-50'],
                ['title' => 'Pengeluaran Bulan Ini', 'amount' => 'Rp ' . number_format($expenseThisMonth, 0, ',', '.'), 'change' => 'Bulan ini', 'isPositive' => true, 'icon' => 'arrow-down-right', 'color' => 'text-rose-500', 'bg' => 'bg-rose-50'],
                ['title' => 'Sisa Budget', 'amount' => 'Rp ' . number_format($sisaBudget, 0, ',', '.'), 'change' => 'Sisa limit', 'isPositive' => false, 'icon' => 'credit-card', 'color' => 'text-amber-500', 'bg' => 'bg-amber-50'],
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

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Line Chart -->
        <div class="lg:col-span-2 bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 tracking-tight">Analisis Grafik Keuangan</h3>
                    <p class="text-sm text-slate-400">Pemasukan vs Pengeluaran</p>
                </div>
                <button class="p-2 hover:bg-slate-50 rounded-xl text-slate-400 transition-colors">
                    <x-lucide-more-horizontal class="w-5 h-5" />
                </button>
            </div>
            
            <div class="h-[300px] w-full" x-data="lineChartData({{ $isNewUser ? 'true' : 'false' }})" x-init="initChart()">
                <canvas id="lineChart"></canvas>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm transition-all hover:shadow-md flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 tracking-tight">Kategori</h3>
                    <p class="text-sm text-slate-400">Bulan {{ \Carbon\Carbon::now()->translatedFormat('F') }}</p>
                </div>
                <button class="p-2 hover:bg-slate-50 rounded-xl text-slate-400 transition-colors">
                    <x-lucide-more-horizontal class="w-5 h-5" />
                </button>
            </div>

            <div class="h-[200px] w-full flex items-center justify-center relative" x-data="pieChartData({{ $isNewUser ? 'true' : 'false' }})" x-init="initChart()">
                <canvas id="pieChart"></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none mt-2">
                    <span class="text-2xl font-black text-navy-900">{{ $isNewUser ? '0%' : '45%' }}</span>
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Terbesar</span>
                </div>
            </div>
            
            <div class="mt-6 space-y-2">
                @php
                    $colors = ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6'];
                    $realPieData = [];
                    $totalExpense = 0;
                    if (!$isNewUser) {
                        $expenses = auth()->user()->transactions()
                            ->where('type', 'expense')
                            ->whereMonth('transaction_date', \Carbon\Carbon::now()->month)
                            ->selectRaw('category as name, SUM(amount) as value')
                            ->groupBy('category')
                            ->orderByDesc('value')
                            ->take(5)
                            ->get();
                        
                        foreach($expenses as $index => $exp) {
                            $realPieData[] = [
                                'name' => $exp->name ?? 'Lainnya',
                                'value' => $exp->value,
                                'color' => $colors[$index % count($colors)]
                            ];
                            $totalExpense += $exp->value;
                        }
                    }

                    if (empty($realPieData)) {
                        $pieData = [['name' => 'Belum Ada Data', 'value' => 0, 'color' => '#cbd5e1']];
                    } else {
                        $pieData = $realPieData;
                    }
                @endphp
                @foreach($pieData as $item)
                    <div class="flex items-center justify-between p-3.5 bg-slate-50/50 rounded-2xl hover:bg-slate-50 transition-colors group">
                        <div class="flex items-center gap-3">
                            <div class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $item['color'] }}"></div>
                            <span class="text-sm font-bold text-slate-600 group-hover:text-navy-900 transition-colors">{{ $item['name'] }}</span>
                        </div>
                        <span class="text-sm font-black text-navy-900 tabular-nums">
                            {{ empty($realPieData) ? '0%' : round(($item['value'] / $totalExpense) * 100) . '%' }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('lineChartData', (isNewUser) => ({
            initChart() {
                const ctx = document.getElementById('lineChart').getContext('2d');
                
                // Gradient Income
                let gradientIncome = ctx.createLinearGradient(0, 0, 0, 300);
                gradientIncome.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
                gradientIncome.addColorStop(1, 'rgba(16, 185, 129, 0)');
                
                // Gradient Expense
                let gradientExpense = ctx.createLinearGradient(0, 0, 0, 300);
                gradientExpense.addColorStop(0, 'rgba(239, 68, 68, 0.2)');
                gradientExpense.addColorStop(1, 'rgba(239, 68, 68, 0)');

                const incomeData = isNewUser ? [0, 0, 0, 0, 0, 0, 0] : [4000, 3000, 2000, 2780, 1890, 2390, 3490];
                const expenseData = isNewUser ? [0, 0, 0, 0, 0, 0, 0] : [2400, 1398, 9800, 3908, 4800, 3800, 4300];

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                        datasets: [
                            {
                                label: 'Pemasukan',
                                data: incomeData,
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
                                data: expenseData,
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
            }
        }));

        Alpine.data('pieChartData', (isNewUser) => ({
            pieDataRaw: @json($pieData),
            initChart() {
                const ctx = document.getElementById('pieChart').getContext('2d');
                
                const labels = this.pieDataRaw.map(item => item.name);
                const data = this.pieDataRaw.map(item => item.value > 0 ? item.value : 1);
                const bgColors = this.pieDataRaw.map(item => item.color);

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
