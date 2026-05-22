import React from 'react';
import { X, Target, Tag, Calendar, CreditCard, Info, Check, Bell, ChevronDown } from 'lucide-react';
import { motion, AnimatePresence } from 'motion/react';
import { cn } from '@/src/lib/utils';

interface AddBudgetModalProps {
  isOpen: boolean;
  onClose: () => void;
}

export default function AddBudgetModal({ isOpen, onClose }: AddBudgetModalProps) {
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
              className="bg-white translate-z-0 w-full max-w-lg rounded-[2.5rem] shadow-2xl pointer-events-auto overflow-hidden flex flex-col"
            >
              <div className="bg-emerald-500 px-6 py-5 text-white relative flex items-center justify-between">
                <div className="flex items-center gap-3">
                  <div className="p-2 bg-white/20 rounded-xl backdrop-blur-md">
                    <Target size={18} />
                  </div>
                  <div>
                    <h2 className="text-lg font-bold tracking-tight">Tambah Budget</h2>
                    <p className="text-emerald-50 text-[10px] opacity-80">Atur limit belanja agar pengeluaran terkontrol.</p>
                  </div>
                </div>
                <button 
                  onClick={onClose}
                  className="p-1.5 bg-white/15 hover:bg-white/25 rounded-lg transition-colors"
                >
                  <X size={18} />
                </button>
              </div>

              <div className="p-6 space-y-4 overflow-hidden">
                <div className="space-y-1.5">
                  <label className="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Kategori*</label>
                  <div className="relative group">
                    <Tag className="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" size={16} />
                    <select className="w-full appearance-none bg-slate-50 border border-slate-100 rounded-2xl py-2.5 pl-10 pr-8 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all cursor-pointer">
                      <option value="">Pilih kategori</option>
                      <option value="makanan">Makanan</option>
                      <option value="transportasi">Transportasi</option>
                      <option value="hiburan">Hiburan</option>
                      <option value="belanja">Belanja</option>
                      <option value="tagihan">Tagihan</option>
                    </select>
                    <ChevronDown size={14} className="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" />
                  </div>
                </div>

                <div className="grid grid-cols-2 gap-4">
                  <div className="space-y-1.5">
                    <label className="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Periode*</label>
                    <div className="relative group">
                      <Calendar className="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" size={16} />
                      <select className="w-full appearance-none bg-slate-50 border border-slate-100 rounded-2xl py-2.5 pl-10 pr-8 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all cursor-pointer">
                        <option value="bulanan">Bulanan</option>
                        <option value="mingguan">Mingguan</option>
                        <option value="custom">Custom</option>
                      </select>
                      <ChevronDown size={14} className="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" />
                    </div>
                  </div>
                  <div className="space-y-1.5">
                    <label className="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Bulan*</label>
                    <div className="relative group">
                      <Calendar className="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" size={16} />
                      <select className="w-full appearance-none bg-slate-50 border border-slate-100 rounded-2xl py-2.5 pl-10 pr-8 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all cursor-pointer">
                        <option value="5">Mei</option>
                        <option value="6">Juni</option>
                        <option value="7">Juli</option>
                      </select>
                      <ChevronDown size={14} className="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" />
                    </div>
                  </div>
                </div>

                <div className="space-y-1.5">
                  <label className="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Total Limit Budget*</label>
                  <div className="relative group">
                    <CreditCard className="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" size={16} />
                    <input 
                      type="number" 
                      placeholder="Rp 0" 
                      className="w-full bg-slate-50 border border-slate-100 rounded-2xl py-2.5 pl-10 pr-4 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all"
                    />
                  </div>
                </div>

                <div className="flex items-center justify-between p-3.5 bg-slate-50 rounded-2xl border border-slate-100">
                  <div className="flex items-center gap-2.5">
                    <Bell className="text-emerald-500 shrink-0" size={16} />
                    <div>
                      <p className="text-xs font-bold text-navy-900">Ingatkan saat mendekati limit</p>
                      <p className="text-[8px] text-slate-400 font-bold uppercase tracking-widest">Normal: 80%</p>
                    </div>
                  </div>
                  <button className="w-9 h-5 rounded-full bg-emerald-500 relative transition-all">
                    <div className="absolute top-0.5 right-0.5 w-4 h-4 bg-white rounded-full shadow-md" />
                  </button>
                </div>

                <div className="space-y-1.5">
                  <label className="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Catatan (Opsional)</label>
                  <div className="relative group">
                    <Info className="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" size={16} />
                    <input 
                      type="text"
                      placeholder="Contoh: Maksimal makan di luar bulan ini" 
                      className="w-full bg-slate-50 border border-slate-100 rounded-2xl py-2.5 pl-10 pr-4 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all"
                    />
                  </div>
                </div>
              </div>

              <div className="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 rounded-b-[2.5rem]">
                <button 
                  onClick={onClose}
                  className="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-200 transition-all uppercase tracking-widest active:scale-95"
                >
                  Batal
                </button>
                <button className="bg-emerald-500 hover:bg-emerald-600 text-white px-7 py-2.5 rounded-xl font-black text-xs shadow-xl shadow-emerald-500/10 active:scale-95 transition-all flex items-center gap-2 uppercase tracking-widest">
                  <Check size={16} strokeWidth={3} /> Simpan Budget
                </button>
              </div>
            </motion.div>
          </div>
        </>
      )}
    </AnimatePresence>
  );
}
