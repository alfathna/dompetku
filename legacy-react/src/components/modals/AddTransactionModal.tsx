import React, { useState, useRef } from 'react';
import { 
  X, 
  Calendar, 
  Tag, 
  Wallet, 
  CreditCard, 
  AlignLeft, 
  Check, 
  ArrowUp, 
  ArrowDown, 
  Plus, 
  Upload, 
  FileText,
  Search,
  ChevronDown
} from 'lucide-react';
import { motion, AnimatePresence } from 'motion/react';
import { cn } from '@/src/lib/utils';

interface AddTransactionModalProps {
  isOpen: boolean;
  onClose: () => void;
}

const CATEGORIES = {
  income: ['Gaji', 'Freelance', 'Bonus', 'Penjualan'],
  expense: ['Makanan', 'Transportasi', 'Hiburan', 'Belanja', 'Tagihan']
};

const WALLETS = ['Cash', 'BCA', 'OVO', 'Dana'];

export default function AddTransactionModal({ isOpen, onClose }: AddTransactionModalProps) {
  const [type, setType] = useState<'income' | 'expense'>('expense');
  const [nominal, setNominal] = useState('');
  const [category, setCategory] = useState('');
  const [isCategoryOpen, setIsCategoryOpen] = useState(false);
  const [dragActive, setDragActive] = useState(false);
  const fileInputRef = useRef<HTMLInputElement>(null);

  const formatRupiah = (value: string) => {
    const numberString = value.replace(/[^,\d]/g, '').toString();
    const split = numberString.split(',');
    const sisa = split[0].length % 3;
    let rupiah = split[0].substr(0, sisa);
    const ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
      const separator = sisa ? '.' : '';
      rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
    return value ? 'Rp ' + rupiah : '';
  };

  const handleNominalChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const value = e.target.value.replace(/[^0-9]/g, '');
    setNominal(value);
  };

  const handleDrag = (e: React.DragEvent) => {
    e.preventDefault();
    e.stopPropagation();
    if (e.type === "dragenter" || e.type === "dragover") {
      setDragActive(true);
    } else if (e.type === "dragleave") {
      setDragActive(false);
    }
  };

  const handleDrop = (e: React.DragEvent) => {
    e.preventDefault();
    e.stopPropagation();
    setDragActive(false);
    if (e.dataTransfer.files && e.dataTransfer.files[0]) {
      // Handle files
    }
  };

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
              className="bg-white translate-z-0 w-full max-w-xl rounded-[2.5rem] shadow-2xl pointer-events-auto overflow-hidden flex flex-col"
            >
              {/* Header */}
              <div className="bg-emerald-500 px-6 py-5 text-white relative flex items-center justify-between">
                <div className="flex items-center gap-3">
                  <div className="p-2 bg-white/20 rounded-xl backdrop-blur-md">
                    <Plus size={18} strokeWidth={3} />
                  </div>
                  <div>
                    <h2 className="text-lg font-bold tracking-tight">Tambah Transaksi</h2>
                    <p className="text-emerald-50 text-[10px] opacity-85">Catat pemasukan atau pengeluaran barumu.</p>
                  </div>
                </div>
                <button 
                  onClick={onClose}
                  className="p-1.5 bg-white/15 hover:bg-white/25 rounded-lg transition-colors"
                >
                  <X size={18} />
                </button>
              </div>

              {/* Form Content */}
              <div className="p-6 space-y-4 overflow-hidden">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                  {/* Segmented Button Tipe */}
                  <div className="space-y-1.5">
                    <label className="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Tipe Transaksi</label>
                    <div className="bg-slate-100 p-1 rounded-2xl flex relative border border-slate-200">
                      <motion.div 
                        layoutId="segmented-bg"
                        className={cn(
                          "absolute top-1 bottom-1 rounded-xl shadow-sm z-0",
                          type === 'expense' ? "bg-white left-1 right-[50%]" : "bg-white left-[50%] right-1"
                        )}
                        transition={{ type: "spring", bounce: 0.2, duration: 0.6 }}
                      />
                      <button 
                        onClick={() => { setType('expense'); setCategory(''); }}
                        className={cn(
                          "relative z-10 flex-1 py-1.5 px-3 rounded-xl flex items-center justify-center gap-1.5 text-xs font-bold transition-colors",
                          type === 'expense' ? "text-emerald-600" : "text-slate-400 hover:text-slate-500"
                        )}
                      >
                        <ArrowDown size={14} strokeWidth={3} /> Pengeluaran
                      </button>
                      <button 
                        onClick={() => { setType('income'); setCategory(''); }}
                        className={cn(
                          "relative z-10 flex-1 py-1.5 px-3 rounded-xl flex items-center justify-center gap-1.5 text-xs font-bold transition-colors",
                          type === 'income' ? "text-emerald-600" : "text-slate-400 hover:text-slate-500"
                        )}
                      >
                        <ArrowUp size={14} strokeWidth={3} /> Pemasukan
                      </button>
                    </div>
                  </div>

                  {/* Nama Transaksi */}
                  <div className="space-y-1.5">
                    <label className="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Nama Transaksi</label>
                    <div className="relative group">
                      <AlignLeft className="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-500 transition-colors" size={16} />
                      <input 
                        type="text" 
                        placeholder={type === 'income' ? "Gaji, Freelance, dll" : "Makanan, Belanja, dll"}
                        className="w-full bg-slate-50 border border-slate-100 rounded-2xl py-2.5 pl-10 pr-4 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500/50 focus:bg-white outline-none transition-all"
                      />
                    </div>
                  </div>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                  {/* Category Searchable Dropdown */}
                  <div className="space-y-1.5">
                    <label className="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Kategori</label>
                    <div className="relative">
                      <button 
                        onClick={() => setIsCategoryOpen(!isCategoryOpen)}
                        className="w-full bg-slate-50 border border-slate-100 rounded-2xl py-2.5 pl-10 pr-4 text-xs font-bold text-navy-900 flex items-center justify-between hover:bg-slate-100 transition-all outline-none text-left"
                      >
                        <Tag className="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" size={16} />
                        <span className="truncate">{category || "Pilih Kategori"}</span>
                        <ChevronDown size={14} className={cn("text-slate-400 transition-transform shadow-none", isCategoryOpen && "rotate-180")} />
                      </button>
                      
                      <AnimatePresence>
                        {isCategoryOpen && (
                          <motion.div 
                            initial={{ opacity: 0, y: 5 }}
                            animate={{ opacity: 1, y: 0 }}
                            exit={{ opacity: 0, y: 5 }}
                            className="absolute top-full left-0 right-0 mt-1 bg-white border border-slate-100 rounded-2xl shadow-xl z-50 p-1.5 overflow-hidden"
                          >
                            <div className="p-1.5 border-b border-slate-50 mb-1">
                               <div className="relative">
                                 <Search className="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400" size={12} />
                                 <input type="text" placeholder="Cari..." className="w-full bg-slate-50 rounded-lg py-1.5 pl-8 pr-3 text-[11px] outline-none focus:ring-2 focus:ring-emerald-500/10" />
                               </div>
                            </div>
                            <div className="max-h-36 overflow-y-auto custom-scrollbar space-y-0.5">
                              {CATEGORIES[type].map((cat) => (
                                <button
                                  key={cat}
                                  onClick={() => { setCategory(cat); setIsCategoryOpen(false); }}
                                  className="w-full flex items-center justify-between px-3 py-2 rounded-lg text-xs font-semibold hover:bg-emerald-50 hover:text-emerald-600 transition-all text-slate-600"
                                >
                                  {cat}
                                  {category === cat && <Check size={14} />}
                                </button>
                              ))}
                            </div>
                          </motion.div>
                        )}
                      </AnimatePresence>
                    </div>
                  </div>

                  {/* Wallet */}
                  <div className="space-y-1.5">
                    <label className="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Pilih Wallet</label>
                    <div className="relative">
                      <Wallet className="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" size={16} />
                      <select className="w-full appearance-none bg-slate-50 border border-slate-100 rounded-2xl py-2.5 pl-10 pr-8 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none cursor-pointer hover:bg-slate-100 transition-all">
                        {WALLETS.map(w => <option key={w}>{w}</option>)}
                      </select>
                      <ChevronDown size={14} className="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" />
                    </div>
                  </div>
                </div>

                {/* Nominal */}
                <div className="space-y-1.5">
                  <label className="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Nominal</label>
                  <div className="relative group">
                    <input 
                      type="text" 
                      value={formatRupiah(nominal)}
                      onChange={handleNominalChange}
                      placeholder="Rp 0" 
                      className="w-full bg-emerald-50/20 border-2 border-emerald-500/10 rounded-2xl py-3 px-5 text-xl font-black text-navy-900 focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 outline-none transition-all placeholder:text-slate-200"
                    />
                  </div>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                  {/* Tanggal */}
                  <div className="space-y-1.5">
                    <label className="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Tanggal</label>
                    <div className="relative">
                      <Calendar className="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" size={16} />
                      <input 
                        type="date" 
                        defaultValue="2026-05-18"
                        className="w-full bg-slate-50 border border-slate-100 rounded-2xl py-2.5 pl-10 pr-4 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all"
                      />
                    </div>
                  </div>
                  {/* Catatan Area */}
                  <div className="space-y-1.5">
                    <label className="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Catatan</label>
                    <div className="relative">
                      <FileText className="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" size={16} />
                      <input 
                        placeholder="Contoh: Beli snack sore" 
                        className="w-full bg-slate-50 border border-slate-100 rounded-2xl py-2.5 pl-10 pr-4 text-xs font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all"
                      />
                    </div>
                  </div>
                </div>

                {/* Compact Upload Bukti */}
                <div className="space-y-1.5">
                  <label className="text-[9px] font-black text-slate-400 uppercase tracking-widest block ml-1">Upload Bukti (Opsional)</label>
                  <div 
                    className={cn(
                      "border-2 border-dashed rounded-2xl p-3 flex items-center justify-between gap-3 transition-all cursor-pointer group",
                      dragActive ? "border-emerald-500 bg-emerald-50" : "border-slate-200 hover:border-emerald-300 hover:bg-slate-50/50"
                    )}
                    onDragEnter={handleDrag}
                    onDragLeave={handleDrag}
                    onDragOver={handleDrag}
                    onDrop={handleDrop}
                    onClick={() => fileInputRef.current?.click()}
                  >
                    <input ref={fileInputRef} type="file" className="hidden" />
                    <div className="flex items-center gap-2.5">
                      <div className="p-2 bg-white rounded-xl shadow-sm border border-slate-100 text-slate-400 group-hover:text-emerald-500 transition-all">
                        <Upload size={16} />
                      </div>
                      <div className="text-left">
                        <p className="text-xs font-bold text-navy-900">Pilih atau drag dokumen bukti</p>
                        <p className="text-[9px] text-slate-400">PDF, PNG, JPG (Maks. 5MB)</p>
                      </div>
                    </div>
                    <span className="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg">Cari File</span>
                  </div>
                </div>
              </div>

              {/* Footer Actions */}
              <div className="px-6 py-4.5 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 rounded-b-[2.5rem]">
                <button 
                  onClick={onClose}
                  className="px-5 py-2 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-200 transition-all uppercase tracking-widest active:scale-95"
                >
                  Batal
                </button>
                <button className="bg-emerald-500 hover:bg-emerald-600 text-white px-7 py-2.5 rounded-xl font-black text-xs shadow-xl shadow-emerald-500/10 active:scale-95 transition-all flex items-center gap-2 uppercase tracking-widest">
                  <Check size={16} strokeWidth={3} /> Simpan Transaksi
                </button>
              </div>
            </motion.div>
          </div>
        </>
      )}
    </AnimatePresence>
  );
}

