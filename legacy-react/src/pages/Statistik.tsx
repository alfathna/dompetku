import React, { useState } from 'react';
import { 
  Download, 
  ChevronDown, 
  TrendingUp, 
  Calendar as CalendarIcon,
  PieChart as PieIcon,
  CheckCircle2,
  AlertCircle,
  XCircle,
  LayoutGrid
} from 'lucide-react';
import { 
  ResponsiveContainer, 
  BarChart, 
  Bar, 
  XAxis, 
  YAxis, 
  CartesianGrid, 
  Tooltip, 
  Cell
} from 'recharts';
import { motion } from 'motion/react';
import { cn } from '@/src/lib/utils';

// --- MOCK DATA ---
const MONTHLY_TRENDS = [
  { month: 'Jan', income: 10000000, expense: 4500000, net: 5500000 },
  { month: 'Feb', income: 10000000, expense: 5200000, net: 4800000 },
  { month: 'Mar', income: 10500000, expense: 4800000, net: 5700000 },
  { month: 'Apr', income: 10000000, expense: 6100000, net: 3900000 },
  { month: 'Mei', income: 12000000, expense: 5230000, net: 6770000 },
  { month: 'Jun', income: 0, expense: 0, net: 0 },
];

const BUDGET_PERFORMANCE = [
  { name: 'Belanja Bulanan', usage: 92, remaining: 120000, status: 'risk', color: 'amber' },
  { name: 'Makan Luar', usage: 105, remaining: -50000, status: 'over', color: 'rose' },
  { name: 'Transportasi', usage: 45, remaining: 800000, status: 'safe', color: 'emerald' },
];

const INSIGHTS = [
  { 
    title: 'Pengeluaran naik', 
    value: '8%', 
    description: 'naik dari bulan lalu (+Rp 420.000)',
    type: 'trend'
  },
  { 
    title: 'Kategori dominan', 
    value: '33%', 
    description: 'Makanan & Minuman menyumbang porsi terbesar',
    type: 'category'
  },
  { 
    title: 'Budget berisiko', 
    value: 'Rp 120k', 
    description: 'Belanja bulanan akan overlimit jika pola berlanjut',
    type: 'risk'
  }
];

export default function StatisticsPage() {
  const [trendType, setTrendType] = useState<'expense' | 'income' | 'net'>('expense');

  const getStatusIcon = (status: string) => {
    switch (status) {
      case 'safe': return <CheckCircle2 size={14} className="text-emerald-500" />;
      case 'risk': return <AlertCircle size={14} className="text-amber-500" />;
      case 'over': return <XCircle size={14} className="text-rose-500" />;
      default: return null;
    }
  };

  const getStatusLabel = (status: string) => {
    switch (status) {
      case 'safe': return 'Aman';
      case 'risk': return 'Berisiko';
      case 'over': return 'Overlimit';
      default: return '';
    }
  };

  return (
    <div className="space-y-8 pb-12 animate-in fade-in slide-in-from-bottom-4 duration-500">
      {/* Header Content */}
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
          <h1 className="text-3xl font-bold text-slate-900 tracking-tight">Statistik Keuangan</h1>
          <p className="text-slate-500 mt-1">Analisis mendalam pola transaksi dan kebiasaan belanjamu.</p>
        </div>
        <div className="flex flex-wrap items-center gap-3">
          <div className="flex items-center gap-2 bg-white px-4 py-2.5 rounded-2xl border border-slate-100 shadow-sm text-sm font-bold text-slate-600 cursor-pointer hover:bg-slate-50 transition-colors">
            <CalendarIcon size={16} className="text-emerald-500" /> Mei 2026 <ChevronDown size={14} />
          </div>
          <div className="flex items-center gap-2 bg-white px-4 py-2.5 rounded-2xl border border-slate-100 shadow-sm text-sm font-bold text-slate-600 cursor-pointer hover:bg-slate-50 transition-colors">
            <PieIcon size={16} className="text-blue-500" /> Semua Wallet <ChevronDown size={14} />
          </div>
          <button className="flex items-center gap-2 px-6 py-2.5 bg-navy-900 text-white rounded-2xl text-sm font-bold hover:bg-emerald-600 transition-all shadow-lg shadow-navy-900/10">
            <Download size={18} /> Export Data
          </button>
        </div>
      </div>

      {/* SECTION 1 — Insight Ringkas */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        {INSIGHTS.length > 0 ? (
          INSIGHTS.map((insight, idx) => (
            <motion.div 
              key={idx}
              initial={{ opacity: 0, y: 10 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ delay: idx * 0.1 }}
              className="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm relative overflow-hidden"
            >
              <div className="absolute top-0 right-0 p-6 opacity-[0.03] text-navy-900 pointer-events-none">
                <TrendingUp size={80} />
              </div>
              <div className="flex items-center gap-2 mb-3">
                <span className="px-2.5 py-1 bg-emerald-50 text-emerald-600 font-bold text-[10px] uppercase tracking-widest rounded-lg">Insight</span>
                <h4 className="text-[10px] font-black text-slate-400 uppercase tracking-widest">{insight.title}</h4>
              </div>
              <p className="text-2xl font-black text-navy-900 mb-1">{insight.value}</p>
              <p className="text-[11px] text-slate-500 leading-tight font-medium">{insight.description}</p>
            </motion.div>
          ))
        ) : (
          <div className="col-span-3 bg-white p-8 rounded-[2rem] border border-slate-100 border-dashed text-center">
            <p className="text-slate-400 font-bold text-sm">Belum cukup data untuk menampilkan insight bulan ini.</p>
          </div>
        )}
      </div>

      {/* SECTION 2 — Tren 6 Bulan */}
      <div className="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm space-y-8">
        <div className="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
          <div className="space-y-1">
            <h3 className="text-xl font-bold text-navy-900">Tren 6 Bulan Terakhir</h3>
            <div className="flex items-center gap-3">
              <span className="text-sm text-slate-400 font-medium">Rata-rata: Rp 5.100.000 / bln</span>
              <div className="flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 font-bold text-[10px]">
                <TrendingUp size={10} /> +4.5%
              </div>
            </div>
          </div>
          
          <div className="flex p-1 bg-slate-50 rounded-2xl border border-slate-100">
            {(['expense', 'income', 'net'] as const).map((type) => (
              <button
                key={type}
                onClick={() => setTrendType(type)}
                className={cn(
                  "px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all",
                  trendType === type 
                    ? "bg-white text-navy-900 shadow-sm" 
                    : "text-slate-400 hover:text-slate-600"
                )}
              >
                {type === 'expense' ? 'Pengeluaran' : type === 'income' ? 'Pemasukan' : 'Net'}
              </button>
            ))}
          </div>
        </div>

        <div className="h-[350px] w-full">
          <ResponsiveContainer width="100%" height="100%">
            <BarChart data={MONTHLY_TRENDS} margin={{ top: 20, right: 30, left: 20, bottom: 0 }}>
              <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#f1f5f9" />
              <XAxis 
                dataKey="month" 
                axisLine={false} 
                tickLine={false} 
                tick={{ fill: '#94a3b8', fontSize: 13, fontWeight: 500 }} 
                dy={15}
              />
              <YAxis 
                axisLine={false} 
                tickLine={false} 
                tick={{ fill: '#94a3b8', fontSize: 11 }} 
                tickFormatter={(val) => `Rp ${val / 1000000}jt`}
              />
              <Tooltip 
                cursor={{ fill: '#f8fafc' }}
                content={({ active, payload }) => {
                  if (active && payload?.[0]) {
                    const data = payload[0].payload;
                    const val = data[trendType === 'expense' ? 'expense' : trendType === 'income' ? 'income' : 'net'];
                    return (
                      <div className="bg-navy-900 text-white p-4 rounded-2xl shadow-xl border border-white/10">
                        <p className="text-[10px] uppercase font-black text-slate-400 tracking-widest mb-1">{data.month}</p>
                        <p className="font-bold text-lg">Rp {val.toLocaleString()}</p>
                      </div>
                    )
                  }
                  return null;
                }}
              />
              <Bar 
                dataKey={trendType === 'expense' ? 'expense' : trendType === 'income' ? 'income' : 'net'} 
                radius={[12, 12, 12, 12]} 
                barSize={45}
              >
                {MONTHLY_TRENDS.map((entry, index) => (
                  <Cell 
                    key={`cell-${index}`} 
                    fill={entry.month === 'Mei' ? '#10b981' : '#e2e8f0'} 
                    style={{ 
                      filter: entry.month === 'Mei' ? 'drop-shadow(0 8px 12px rgba(16,185,129,0.25))' : 'none',
                      opacity: entry.month === 'Jun' ? 0.3 : 1
                    }}
                  />
                ))}
              </Bar>
            </BarChart>
          </ResponsiveContainer>
        </div>

        <div className="pt-4 border-t border-slate-50">
          <p className="text-[11px] text-slate-400 font-medium text-center">Bandingkan perubahan antar bulan untuk melihat pola belanjamu.</p>
        </div>
      </div>

      {/* SECTION 5 — Budget Performance */}
      <div className="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
        <div className="flex items-center justify-between mb-8">
          <div className="flex items-center gap-3">
            <div className="p-2 bg-navy-900 text-white rounded-xl">
              <LayoutGrid size={18} />
            </div>
            <h3 className="text-lg font-bold text-navy-900 tracking-tight">Kinerja Budget</h3>
          </div>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          {BUDGET_PERFORMANCE.length > 0 ? (
            BUDGET_PERFORMANCE.map((budget, i) => (
              <div key={i} className="p-6 bg-slate-50 rounded-3xl border border-slate-100 group hover:bg-white hover:shadow-lg transition-all text-left">
                <div className="flex items-center justify-between mb-4">
                  <span className="text-sm font-bold text-slate-700">{budget.name}</span>
                  <div className={cn(
                    "flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest",
                    budget.status === 'safe' ? "bg-emerald-50 text-emerald-600" :
                    budget.status === 'risk' ? "bg-amber-50 text-amber-600" : "bg-rose-50 text-rose-600"
                  )}>
                    {getStatusIcon(budget.status)}
                    {getStatusLabel(budget.status)}
                  </div>
                </div>
                
                <div className="space-y-4">
                  <div>
                    <div className="flex justify-between text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">
                      <span>Terpakai</span>
                      <span className={cn(
                        budget.usage >= 100 ? "text-rose-500" : budget.usage >= 80 ? "text-amber-500" : "text-emerald-500"
                      )}>{budget.usage}%</span>
                    </div>
                    <div className="h-1.5 bg-slate-200/50 rounded-full overflow-hidden">
                      <motion.div 
                        initial={{ width: 0 }}
                        animate={{ width: `${Math.min(budget.usage, 100)}%` }}
                        className={cn(
                          "h-full rounded-full",
                          budget.status === 'safe' ? "bg-emerald-500" :
                          budget.status === 'risk' ? "bg-amber-500" : "bg-rose-500"
                        )}
                      />
                    </div>
                  </div>
                  
                  <div className="flex justify-between items-end">
                    <div>
                      <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Sisa Anggaran</p>
                      <p className="font-bold text-navy-900 tracking-tight">Rp {Math.abs(budget.remaining).toLocaleString()}</p>
                    </div>
                    {budget.status === 'risk' && (
                      <div className="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded-lg">
                        Berisiko over
                      </div>
                    )}
                  </div>
                </div>
              </div>
            ))
          ) : (
            <div className="col-span-3 py-12 text-center bg-slate-50 rounded-3xl border border-dashed border-slate-200">
              <p className="text-slate-400 font-bold text-sm">Belum ada budget aktif di periode ini.</p>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
