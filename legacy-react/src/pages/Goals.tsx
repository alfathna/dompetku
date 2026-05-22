import React, { useState } from 'react';
import { Target, Calendar, ChevronRight, TrendingUp } from 'lucide-react';
import { motion } from 'motion/react';
import { cn } from '@/src/lib/utils';
import AddGoalModal from '@/src/components/modals/AddGoalModal';
import ManageSavingModal from '@/src/components/modals/ManageSavingModal';

export default function GoalsPage() {
  const [showAddModal, setShowAddModal] = useState(false);
  const [manageGoal, setManageGoal] = useState<any>(null);

  const GOALS = [
    { 
      id: 1,
      title: 'Laptop Baru (ROG Zephyrus)', 
      target: 25000000, 
      collected: 18500000, 
      estimate: 'Juli 2026', 
      color: 'emerald'
    },
    { 
      id: 2,
      title: 'Dana Darurat 6 Bulan', 
      target: 60000000, 
      collected: 42000000, 
      estimate: 'Desember 2026', 
      color: 'blue'
    },
    { 
      id: 3,
      title: 'Liburan ke Jepang', 
      target: 35000000, 
      collected: 12000000, 
      estimate: 'Maret 2027', 
      color: 'amber'
    },
  ];

  return (
    <div className="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
          <h1 className="text-3xl font-bold text-slate-900 tracking-tight">Financial Goals</h1>
          <p className="text-slate-500 mt-1">Wujudkan impianmu dengan perencanaan matang.</p>
        </div>
        <button 
          onClick={() => setShowAddModal(true)}
          className="bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-emerald-500/20 active:scale-95 transition-all flex items-center gap-2"
        >
          Tambah Goals
        </button>
      </div>

      <div className="grid grid-cols-1 xl:grid-cols-3 gap-6">
        {GOALS.map((goal, idx) => {
          const percent = Math.min((goal.collected / goal.target) * 100, 100);
          return (
            <motion.div
              key={idx}
              initial={{ opacity: 0, scale: 0.9 }}
              animate={{ opacity: 1, scale: 1 }}
              transition={{ delay: idx * 0.1 }}
              whileHover={{ y: -6 }}
              className="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm flex flex-col justify-between"
            >
              <div className="space-y-6">
                <div className="flex items-center justify-between">
                  <div className={cn(
                    "w-14 h-14 rounded-[1.25rem] border flex items-center justify-center transition-colors",
                    goal.color === 'emerald' ? "bg-emerald-50 border-emerald-100 text-emerald-500 shadow-sm shadow-emerald-500/10" :
                    goal.color === 'blue' ? "bg-blue-50 border-blue-100 text-blue-500 shadow-sm shadow-blue-500/10" : 
                    "bg-amber-50 border-amber-100 text-amber-500 shadow-sm shadow-amber-500/10"
                  )}>
                    <Target size={28} strokeWidth={2.5} />
                  </div>
                  <div className={cn(
                    "px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-tight",
                    goal.color === 'emerald' ? "bg-emerald-50 text-emerald-600" :
                    goal.color === 'blue' ? "bg-blue-50 text-blue-600" : "bg-amber-50 text-amber-600"
                  )}>
                    {Math.round(percent)}% Progress
                  </div>
                </div>

                <div>
                  <h3 className="text-xl font-bold text-navy-900 tracking-tight leading-tight">{goal.title}</h3>
                  <div className="mt-6 space-y-2">
                    <div className="flex justify-between text-sm">
                      <span className="text-slate-400 font-medium">Terkumpul</span>
                      <span className="font-bold text-emerald-500">Rp {goal.collected.toLocaleString()}</span>
                    </div>
                    <div className="h-3 bg-slate-100 rounded-full overflow-hidden">
                      <motion.div 
                        initial={{ width: 0 }}
                        animate={{ width: `${percent}%` }}
                        transition={{ duration: 1.5, ease: 'easeOut', delay: 0.5 }}
                        className={cn(
                          "h-full rounded-full shadow-[0_0_12px_rgba(16,185,129,0.3)]",
                          goal.color === 'emerald' ? "bg-emerald-500" :
                          goal.color === 'blue' ? "bg-blue-500" : "bg-amber-500"
                        )} 
                      />
                    </div>
                    <div className="flex justify-between text-sm font-semibold">
                      <span className="text-slate-400 text-[10px] uppercase tracking-widest">Target</span>
                      <span className="text-navy-900">Rp {goal.target.toLocaleString()}</span>
                    </div>
                  </div>
                </div>
              </div>

              <div className="mt-10 pt-6 border-t border-slate-50 grid grid-cols-2 gap-4">
                <div className="space-y-1">
                  <p className="text-[10px] text-slate-400 uppercase tracking-widest font-bold">Estimasi Selesai</p>
                  <p className="font-bold text-navy-900 flex items-center gap-2 text-sm">
                    <Calendar size={14} className="text-emerald-500" /> {goal.estimate}
                  </p>
                </div>
                <div className="space-y-1 text-right">
                  <p className="text-[10px] text-slate-400 uppercase tracking-widest font-bold">Kapasitas Bulanan</p>
                  <p className="font-bold text-blue-600 flex items-center gap-2 text-sm justify-end">
                    <TrendingUp size={14} /> Rp 2.500.000 / bln
                  </p>
                </div>
              </div>

              <button 
                onClick={() => setManageGoal(goal)}
                className="mt-8 w-full py-4 bg-slate-50 hover:bg-emerald-50 text-slate-600 hover:text-emerald-600 font-bold rounded-2xl transition-all flex items-center justify-center gap-2 group"
              >
                Kelola Tabungan <ChevronRight size={18} className="group-hover:translate-x-1 transition-transform" />
              </button>
            </motion.div>
          );
        })}
      </div>

      <AddGoalModal 
        isOpen={showAddModal} 
        onClose={() => setShowAddModal(false)} 
      />

      <ManageSavingModal 
        isOpen={!!manageGoal}
        onClose={() => setManageGoal(null)}
        goal={manageGoal}
      />
    </div>
  );
}
