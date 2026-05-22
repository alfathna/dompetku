import React from 'react';
import { X, Wallet, Calendar, FileText, Check, Plus, Trash2, Edit3, Target, History, ChevronDown } from 'lucide-react';
import { motion, AnimatePresence } from 'motion/react';
import { cn } from '@/src/lib/utils';

interface ManageSavingModalProps {
  isOpen: boolean;
  onClose: () => void;
  goal: any;
}

export default function ManageSavingModal({ isOpen, onClose, goal }: ManageSavingModalProps) {
  if (!goal) return null;

  const percent = Math.min((goal.collected / goal.target) * 100, 100);

  const HISTORY = [
    { id: 1, date: '15 Mei 2026', wallet: 'BCA', amount: 500000, note: 'Setoran rutin' },
    { id: 2, date: '01 Mei 2026', wallet: 'Cash', amount: 200000, note: 'Sisa uang harian' },
  ];

  return (
    <AnimatePresence>
      {isOpen && (
        <>
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            onClick={onClose}
            className="fixed inset-0 bg-navy-950/40 backdrop-blur-sm z-[100]"
          />

          <div className="fixed inset-0 flex items-center justify-center z-[101] p-4 pointer-events-none">
            <motion.div
              initial={{ scale: 0.9, opacity: 0, y: 20 }}
              animate={{ scale: 1, opacity: 1, y: 0 }}
              exit={{ scale: 0.9, opacity: 0, y: 20 }}
              className="bg-white translate-z-0 w-full max-w-2xl rounded-[2.5rem] shadow-2xl pointer-events-auto overflow-hidden flex flex-col"
            >
              <div className="bg-navy-900 px-6 py-5 text-white relative">
                <button 
                  onClick={onClose}
                  className="absolute top-5 right-6 p-1.5 bg-white/10 hover:bg-white/25 rounded-lg transition-colors"
                >
                  <X size={18} />
                </button>
                <div className="flex items-center gap-4">
                  <div className={cn(
                    "w-12 h-12 rounded-xl border flex items-center justify-center shrink-0",
                    goal.color === 'emerald' ? "bg-emerald-500/10 border-emerald-500/20 text-emerald-400" :
                    goal.color === 'blue' ? "bg-blue-500/10 border-blue-500/20 text-blue-400" :
                    "bg-amber-500/10 border-amber-500/20 text-amber-400"
                  )}>
                    <Target size={24} strokeWidth={2.5} />
                  </div>
                  <div className="flex-1 min-w-0">
                    <h2 className="text-lg font-bold tracking-tight truncate">{goal.title}</h2>
                    <div className="flex items-center gap-3 mt-0.5 text-slate-400 text-[10px] font-bold uppercase tracking-widest">
                       <span className="flex items-center gap-1"><Calendar size={10} className="text-emerald-400" /> {goal.estimate}</span>
                       <span className="flex items-center gap-1"><Target size={10} className="text-blue-400" /> Rp {goal.target.toLocaleString()}</span>
                    </div>
                  </div>
                </div>

                <div className="mt-4 space-y-1.5">
                  <div className="flex justify-between text-[10px] font-black uppercase tracking-widest text-slate-400">
                    <span>Progress Tabungan</span>
                    <span className="text-emerald-400">{Math.round(percent)}%</span>
                  </div>
                  <div className="h-2 bg-white/10 rounded-full overflow-hidden">
                    <motion.div 
                       initial={{ width: 0 }}
                       animate={{ width: `${percent}%` }}
                       className="h-full bg-emerald-500 rounded-full shadow-[0_0_12px_rgba(16,185,129,0.5)]"
                    />
                  </div>
                  <div className="flex justify-between text-[10px] font-bold mt-1">
                    <span className="text-emerald-400">Rp {goal.collected.toLocaleString()}</span>
                    <span className="text-slate-500">Sisa: Rp {(goal.target - goal.collected).toLocaleString()}</span>
                  </div>
                </div>
              </div>

              <div className="flex-1 overflow-hidden flex flex-col md:flex-row">
                {/* Left Side: Add Saving Form */}
                <div className="flex-1 p-5 border-r border-slate-100 space-y-4 bg-slate-50/50">
                  <div className="flex items-center gap-2">
                    <div className="p-1 bg-emerald-500 text-white rounded">
                      <Plus size={12} strokeWidth={3} />
                    </div>
                    <h3 className="text-xs font-black text-navy-900 uppercase tracking-widest">Tambah Tabungan</h3>
                  </div>

                  <div className="space-y-3">
                    <div className="space-y-1">
                      <label className="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1 font-sans">Nominal Setoran*</label>
                      <div className="relative group">
                        <span className="absolute left-3.5 top-1/2 -translate-y-1/2 font-black text-slate-400 text-xs">Rp</span>
                        <input 
                          type="number" 
                          placeholder="0" 
                          className="w-full bg-white border border-slate-200 rounded-2xl py-2 pl-9 pr-4 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all"
                        />
                      </div>
                    </div>

                    <div className="space-y-1">
                      <label className="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1 font-sans">Dari Wallet*</label>
                      <div className="relative group">
                        <select className="w-full appearance-none bg-white border border-slate-200 rounded-2xl py-2 pl-4 pr-10 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none cursor-pointer">
                          <option value="">Pilih wallet</option>
                          <option value="cash">Cash</option>
                          <option value="bca">BCA Account</option>
                          <option value="ovo">OVO</option>
                        </select>
                        <ChevronDown size={14} className="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" />
                      </div>
                    </div>

                    <div className="space-y-1">
                      <label className="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1 font-sans">Tanggal*</label>
                      <input 
                        type="date" 
                        defaultValue={new Date().toISOString().split('T')[0]}
                        className="w-full bg-white border border-slate-200 rounded-2xl py-2 px-4 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none"
                      />
                    </div>

                    <div className="space-y-1">
                      <label className="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1 font-sans">Catatan (Opsional)</label>
                      <input 
                        type="text"
                        placeholder="Contoh: Setoran awal bulan" 
                        className="w-full bg-white border border-slate-200 rounded-2xl py-2 px-4 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none"
                      />
                    </div>

                    <button className="w-full bg-emerald-500 hover:bg-emerald-600 text-white py-2.5 rounded-xl font-black text-xs shadow-lg shadow-emerald-500/10 active:scale-95 transition-all flex items-center justify-center gap-2 uppercase tracking-widest mt-2">
                      <Check size={16} strokeWidth={3} /> Simpan Tabungan
                    </button>
                  </div>
                </div>

                {/* Right Side: Saving History */}
                <div className="flex-1 p-5 flex flex-col min-h-[300px]">
                  <div className="flex items-center gap-2 mb-3">
                    <div className="p-1 bg-navy-900 text-white rounded">
                      <History size={12} />
                    </div>
                    <h3 className="text-xs font-black text-navy-900 uppercase tracking-widest">Riwayat Setoran</h3>
                  </div>

                  <div className="flex-1 overflow-y-auto custom-scrollbar -mx-2 px-2 space-y-2.5 max-h-[250px]">
                    {HISTORY.length > 0 ? (
                      HISTORY.map((item) => (
                        <div key={item.id} className="p-3 bg-slate-50 border border-slate-100 rounded-2xl group hover:bg-white hover:shadow-md transition-all">
                          <div className="flex items-center justify-between mb-1">
                            <span className="text-[9px] font-black text-slate-400 uppercase tracking-widest">{item.date}</span>
                            <div className="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                              <button className="p-1 hover:bg-slate-100 text-slate-400 hover:text-navy-900 rounded transition-colors"><Edit3 size={12} /></button>
                              <button className="p-1 hover:bg-rose-50 text-slate-400 hover:text-rose-500 rounded transition-colors"><Trash2 size={12} /></button>
                            </div>
                          </div>
                          <div className="flex items-center justify-between">
                            <div className="flex items-center gap-2">
                              <div className="w-7 h-7 rounded-full bg-white flex items-center justify-center border border-slate-100 shrink-0">
                                <Wallet size={12} className="text-emerald-500" />
                              </div>
                              <div>
                                <p className="text-xs font-bold text-navy-900">Rp {item.amount.toLocaleString()}</p>
                                <p className="text-[9px] text-slate-400 font-bold uppercase tracking-tight">Via {item.wallet}</p>
                              </div>
                            </div>
                          </div>
                          {item.note && (
                            <p className="mt-1.5 text-[9px] text-slate-500 bg-white/50 p-1.5 rounded-lg border border-slate-100 italic">
                             "{item.note}"
                            </p>
                          )}
                        </div>
                      ))
                    ) : (
                      <div className="flex flex-col items-center justify-center h-full text-center p-4 space-y-3">
                        <div className="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center border border-slate-100 text-slate-200">
                          <History size={24} />
                        </div>
                        <div>
                          <p className="font-bold text-xs text-navy-900 text-center">Belum ada tabungan</p>
                          <p className="text-[10px] text-slate-400 mt-0.5">Tambahkan setoran tabungan pertama.</p>
                        </div>
                      </div>
                    )}
                  </div>
                </div>
              </div>
            </motion.div>
          </div>
        </>
      )}
    </AnimatePresence>
  );
}
