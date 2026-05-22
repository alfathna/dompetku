import React from 'react';
import { 
  TrendingUp, 
  TrendingDown, 
  Wallet, 
  CreditCard, 
  ArrowUpRight, 
  ArrowDownRight
} from 'lucide-react';
import { motion } from 'motion/react';
import { cn } from '@/src/lib/utils';

const SUMMARY_DATA = [
  {
    title: 'Total Saldo',
    amount: 'Rp 45.250.000',
    change: '+12%',
    isPositive: true,
    icon: Wallet,
    color: 'bg-emerald-500',
    lightColor: 'bg-emerald-50'
  }   ,
  {
    title: 'Pemasukan Bulan Ini',
    amount: 'Rp 12.400.000',
    change: '+8%',
    isPositive: true,
    icon: ArrowUpRight,
    color: 'bg-blue-500',
    lightColor: 'bg-blue-50'
  },
  {
    title: 'Pengeluaran Bulan Ini',
    amount: 'Rp 5.230.000',
    change: '-15%',
    isPositive: true, // Spending less is positive
    icon: ArrowDownRight,
    color: 'bg-rose-500',
    lightColor: 'bg-rose-50'
  },
  {
    title: 'Sisa Budget',
    amount: 'Rp 2.100.000',
    change: '-5%',
    isPositive: false,
    icon: CreditCard,
    color: 'bg-amber-500',
    lightColor: 'bg-amber-50'
  }
];

export default function SummaryCards() {
  return (
    <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
      {SUMMARY_DATA.map((item, index) => (
        <motion.div
          key={item.title}
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: index * 0.1 }}
          className="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 hover:shadow-md transition-all group"
        >
          <div className="flex justify-between items-start mb-4">
            <div className={cn("p-3 rounded-2xl transition-transform group-hover:scale-110", item.lightColor)}>
              <item.icon size={24} className={item.color.replace('bg-', 'text-')} />
            </div>
            <div className={cn(
              "flex items-center gap-1 text-xs font-bold px-2.5 py-1 rounded-full",
              item.isPositive ? "bg-emerald-100 text-emerald-600" : "bg-rose-100 text-rose-600"
            )}>
              {item.isPositive ? <TrendingUp size={12} /> : <TrendingDown size={12} />}
              {item.change}
            </div>
          </div>
          <div className="space-y-1">
            <h3 className="text-slate-500 text-sm font-medium">{item.title}</h3>
            <p className="text-2xl font-bold text-slate-900 tracking-tight">{item.amount}</p>
          </div>
          <div className="mt-4 pt-4 border-t border-slate-50">
            <p className="text-xs text-slate-400">vs bulan lalu <span className="font-semibold">Rp 4.200.000</span></p>
          </div>
        </motion.div>
      ))}
    </div>
  );
}
