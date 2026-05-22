import React from 'react';
import { 
  ResponsiveContainer, 
  AreaChart, 
  Area, 
  XAxis, 
  YAxis, 
  CartesianGrid, 
  Tooltip,
  PieChart,
  Pie,
  Cell,
} from 'recharts';
import { MoreHorizontal } from 'lucide-react';

const LINE_DATA = [
  { name: 'Sen', income: 4000, expense: 2400 },
  { name: 'Sel', income: 3000, expense: 1398 },
  { name: 'Rab', income: 2000, expense: 9800 },
  { name: 'Kam', income: 2780, expense: 3908 },
  { name: 'Jum', income: 1890, expense: 4800 },
  { name: 'Sab', income: 2390, expense: 3800 },
  { name: 'Min', income: 3490, expense: 4300 },
];

const PIE_DATA = [
  { name: 'Makanan', value: 400, color: '#10b981' }, // Emerald
  { name: 'Transport', value: 300, color: '#3b82f6' }, // Blue
  { name: 'Hiburan', value: 300, color: '#f59e0b' }, // Amber
  { name: 'Tagihan', value: 200, color: '#ef4444' }, // Rose
];

const CustomTooltip = ({ active, payload, label }: any) => {
  if (active && payload && payload.length) {
    return (
      <div className="bg-white p-4 shadow-xl rounded-2xl border border-slate-100">
        <p className="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">{label}</p>
        <div className="space-y-1">
          <p className="text-emerald-500 font-bold flex items-center gap-2">
            <span className="w-2 h-2 rounded-full bg-emerald-500 inline-block" />
            Rp {payload[0].value.toLocaleString()}
          </p>
          <p className="text-rose-500 font-bold flex items-center gap-2">
            <span className="w-2 h-2 rounded-full bg-rose-500 inline-block" />
            Rp {payload[1].value.toLocaleString()}
          </p>
        </div>
      </div>
    );
  }
  return null;
};

export default function Charts() {
  return (
    <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div className="lg:col-span-2 bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm transition-all hover:shadow-md">
        <div className="flex items-center justify-between mb-8">
          <div>
            <h3 className="text-lg font-bold text-slate-900 tracking-tight">Analisis Grafik Keuangan</h3>
            <p className="text-sm text-slate-400">Pemasukan vs Pengeluaran</p>
          </div>
          <button className="p-2 hover:bg-slate-50 rounded-xl text-slate-400 transition-colors">
            <MoreHorizontal size={20} />
          </button>
        </div>
        
        <div className="h-[300px] w-full">
          <ResponsiveContainer width="100%" height="100%">
            <AreaChart data={LINE_DATA} margin={{ top: 10, right: 10, left: -20, bottom: 0 }}>
              <defs>
                <linearGradient id="colorIncome" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="5%" stopColor="#10b981" stopOpacity={0.1}/>
                  <stop offset="95%" stopColor="#10b981" stopOpacity={0}/>
                </linearGradient>
                <linearGradient id="colorExpense" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="5%" stopColor="#ef4444" stopOpacity={0.1}/>
                  <stop offset="95%" stopColor="#ef4444" stopOpacity={0}/>
                </linearGradient>
              </defs>
              <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#f1f5f9" />
              <XAxis 
                dataKey="name" 
                axisLine={false} 
                tickLine={false} 
                tick={{ fill: '#94a3b8', fontSize: 12, fontWeight: 500 }} 
                dy={10}
              />
              <YAxis 
                axisLine={false} 
                tickLine={false} 
                tick={{ fill: '#94a3b8', fontSize: 12 }} 
                tickFormatter={(value) => `${value/1000}k`}
              />
              <Tooltip content={<CustomTooltip />} />
              <Area 
                type="monotone" 
                dataKey="income" 
                stroke="#10b981" 
                strokeWidth={3}
                fillOpacity={1} 
                fill="url(#colorIncome)" 
              />
              <Area 
                type="monotone" 
                dataKey="expense" 
                stroke="#ef4444" 
                strokeWidth={3}
                fillOpacity={1} 
                fill="url(#colorExpense)" 
              />
            </AreaChart>
          </ResponsiveContainer>
        </div>
      </div>

      <div className="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm transition-all hover:shadow-md flex flex-col justify-between">
        <div className="flex items-center justify-between mb-4">
          <div>
            <h3 className="text-lg font-bold text-slate-900 tracking-tight">Kategori</h3>
            <p className="text-sm text-slate-400">Bulan Mei</p>
          </div>
          <button className="p-2 hover:bg-slate-50 rounded-xl text-slate-400 transition-colors">
            <MoreHorizontal size={20} />
          </button>
        </div>

        <div className="h-[200px] w-full flex items-center justify-center relative">
          <ResponsiveContainer width="100%" height="100%">
            <PieChart>
              <Pie
                data={PIE_DATA}
                cx="50%"
                cy="50%"
                innerRadius={65}
                outerRadius={85}
                paddingAngle={6}
                dataKey="value"
                stroke="none"
              >
                {PIE_DATA.map((entry, index) => (
                  <Cell key={`cell-${index}`} fill={entry.color} />
                ))}
              </Pie>
              <Tooltip />
            </PieChart>
          </ResponsiveContainer>
          <div className="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
            <span className="text-2xl font-black text-navy-900">45%</span>
            <span className="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Terbesar</span>
          </div>
        </div>
        
        <div className="mt-6 space-y-2">
          {PIE_DATA.map((item) => (
            <div key={item.name} className="flex items-center justify-between p-3.5 bg-slate-50/50 rounded-2xl hover:bg-slate-50 transition-colors group">
              <div className="flex items-center gap-3">
                <div className="w-2.5 h-2.5 rounded-full" style={{ backgroundColor: item.color }} />
                <span className="text-sm font-bold text-slate-600 group-hover:text-navy-900 transition-colors">{item.name}</span>
              </div>
              <span className="text-sm font-black text-navy-900 tabular-nums">
                {Math.round((item.value / 1200) * 100)}%
              </span>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}

