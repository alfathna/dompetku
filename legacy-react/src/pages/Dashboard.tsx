import React from 'react';
import { motion } from 'motion/react';
import { Calendar } from 'lucide-react';
import SummaryCards from '@/src/components/SummaryCards';
import Charts from '@/src/components/Charts';

export default function DashboardPage() {
  const formattedDate = new Date().toLocaleDateString('id-ID', {
    weekday: 'long',
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  });

  return (
    <div className="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
      {/* Welcome Header */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 className="text-3xl font-bold text-slate-900 tracking-tight">Halo, Felix 👋</h1>
          <p className="text-slate-500 mt-1">Mari kelola keuanganmu hari ini dengan lebih bijak.</p>
        </div>
        
        {/* Date Widget */}
        <div className="flex items-center gap-3 bg-white px-5 py-3 rounded-2xl border border-slate-100 shadow-sm self-start sm:self-auto group hover:shadow-md transition-all duration-300">
          <div className="p-2 bg-emerald-50 text-emerald-600 rounded-xl group-hover:bg-emerald-500 group-hover:text-white transition-colors duration-300">
            <Calendar size={18} />
          </div>
          <span className="text-sm font-bold text-slate-800 tracking-tight">
            {formattedDate}
          </span>
        </div>
      </div>

      {/* Summary Cards */}
      <SummaryCards />

      {/* Main Charts */}
      <Charts />
    </div>
  );
}
