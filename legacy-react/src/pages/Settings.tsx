import React, { useState } from 'react';
import { 
  ShieldCheck, 
  LogOut,
  Upload,
  Check,
  X,
  AlertTriangle,
  Globe
} from 'lucide-react';
import { motion, AnimatePresence } from 'motion/react';
import { cn } from '@/src/lib/utils';

export default function SettingsPage() {
  const [showDeleteModal, setShowDeleteModal] = useState(false);
  const [deleteConfirmText, setDeleteConfirmText] = useState('');
  const [isPermanentConfirmed, setIsPermanentConfirmed] = useState(false);

  const TIMEZONES = [
    'GMT+07:00 (Jakarta)',
    'GMT+08:00 (Makassar)',
    'GMT+09:00 (Jayapura)',
    'GMT+00:00 (London)',
  ];

  return (
    <div className="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500 max-w-4xl mx-auto pb-12">
      {/* Header Content */}
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-6 px-1">
        <div>
          <h1 className="text-3xl font-bold text-slate-900 tracking-tight">Pengaturan</h1>
          <p className="text-slate-500 mt-1">Personalisasi pengalaman aplikasimu.</p>
        </div>
        <div className="flex items-center gap-3">
          <button className="px-6 py-2.5 rounded-2xl font-bold text-sm text-slate-400 hover:text-slate-600 transition-all uppercase tracking-widest">Batalkan</button>
          <button className="px-6 py-2.5 rounded-2xl font-bold text-sm bg-emerald-500 text-white shadow-lg shadow-emerald-500/20 hover:bg-emerald-600 transition-all flex items-center gap-2 uppercase tracking-widest">
            <Check size={18} strokeWidth={3} /> Simpan Perubahan
          </button>
        </div>
      </div>

      {/* Settings Sections */}
      <div className="space-y-8">
        {/* 1) Card — Profil Saya */}
        <div className="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm space-y-8">
          <div className="flex flex-col sm:flex-row items-center gap-8">
            <div className="relative group">
              <div className="w-24 h-24 bg-emerald-100 rounded-[2rem] flex items-center justify-center border-4 border-white shadow-xl shadow-emerald-500/10 overflow-hidden cursor-pointer">
                 <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Felix" alt="Felix" />
                 <div className="absolute inset-0 bg-navy-900/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                     <span className="text-[10px] font-bold text-white uppercase tracking-widest">Ubah Foto</span>
                 </div>
              </div>
              <button className="absolute -bottom-2 -right-2 p-2.5 bg-navy-900 text-white rounded-xl shadow-lg hover:bg-emerald-500 transition-colors">
                <Upload size={16} />
              </button>
            </div>
            <div className="text-center sm:text-left">
              <h3 className="text-xl font-bold text-navy-900">Felix Wijaya</h3>
              <p className="text-slate-400 text-sm">Update foto profil dan identitas pribadimu.</p>
            </div>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 gap-8 pt-4">
            <div className="space-y-2">
              <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">NAMA LENGKAP</label>
              <input 
                type="text" 
                placeholder="Masukkan nama lengkap"
                defaultValue="Felix Wijaya" 
                className="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 px-4 text-sm font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all"
              />
            </div>
            <div className="space-y-2">
              <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">ALAMAT EMAIL</label>
              <input 
                type="email" 
                placeholder="Masukkan email"
                defaultValue="felix.wijaya@example.com" 
                className="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 px-4 text-sm font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all"
              />
            </div>
            <div className="space-y-2">
              <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">PEKERJAAN</label>
              <input 
                type="text" 
                placeholder="Masukkan pekerjaan"
                defaultValue="Senior UI/UX Designer & Freelancer" 
                className="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 px-4 text-sm font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all"
              />
            </div>
            <div className="space-y-2">
              <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">ZONA WAKTU</label>
              <div className="relative">
                <Globe className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
                <select className="w-full appearance-none bg-slate-50 border border-slate-100 rounded-2xl py-4 pl-12 pr-4 text-sm font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all cursor-pointer">
                  <option value="">Pilih zona waktu</option>
                  {TIMEZONES.map(tz => <option key={tz} value={tz}>{tz}</option>)}
                </select>
                <ChevronDown className="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" size={14} />
              </div>
              <p className="text-[10px] text-slate-400 font-medium ml-1">Dipakai untuk jadwal pengingat tagihan.</p>
            </div>
          </div>
        </div>

        {/* 3) Card — Keamanan Tingkat Lanjut (Danger Zone) */}
        <div className="p-8 rounded-[2.5rem] border border-rose-100 bg-rose-50/30 space-y-6">
           <div className="flex items-center gap-3 text-rose-500">
             <div className="p-2 bg-rose-500 text-white rounded-xl shadow-lg shadow-rose-500/20">
               <ShieldCheck size={20} />
             </div>
             <h3 className="text-lg font-bold tracking-tight">Keamanan Tingkat Lanjut</h3>
           </div>
           
           <div className="space-y-4">
             <p className="text-xs text-slate-500 leading-relaxed max-w-2xl font-medium">
               Kalau kamu menghapus akun, semua transaksi, wallet, budget, tagihan, dan goals akan hilang secara permanen. Tindakan ini tidak bisa dibatalkan. Pastikan kamu sudah export data terlebih dahulu.
             </p>
             
             <button 
              onClick={() => setShowDeleteModal(true)}
              className="group flex items-center gap-3 text-xs font-black text-rose-600 uppercase tracking-widest hover:text-rose-700 transition-all bg-white border border-rose-100 px-8 py-3.5 rounded-2xl hover:shadow-xl hover:shadow-rose-100 active:scale-95"
             >
               <LogOut size={16} />
               HAPUS SEMUA DATA & AKUN
             </button>
           </div>
        </div>
      </div>

      {/* Modal Konfirmasi — Hapus Semua Data & Akun */}
      <AnimatePresence>
        {showDeleteModal && (
          <>
            <motion.div
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              exit={{ opacity: 0 }}
              onClick={() => setShowDeleteModal(false)}
              className="fixed inset-0 bg-navy-950/40 backdrop-blur-sm z-[100]"
            />

            <div className="fixed inset-0 flex items-center justify-center z-[101] p-4 pointer-events-none">
              <motion.div
                initial={{ scale: 0.9, opacity: 0, y: 20 }}
                animate={{ scale: 1, opacity: 1, y: 0 }}
                exit={{ scale: 0.9, opacity: 0, y: 20 }}
                className="bg-white translate-z-0 w-full max-w-md rounded-[2.5rem] shadow-2xl pointer-events-auto overflow-hidden flex flex-col"
              >
                <div className="bg-rose-500 p-8 text-white relative">
                  <button 
                    onClick={() => setShowDeleteModal(false)}
                    className="absolute top-6 right-6 p-2 bg-white/20 hover:bg-white/30 rounded-xl transition-colors"
                  >
                    <X size={20} />
                  </button>
                  <div className="flex items-center gap-4">
                    <div className="p-2.5 bg-white/20 rounded-2xl backdrop-blur-md">
                      <AlertTriangle size={24} />
                    </div>
                    <div>
                      <h2 className="text-xl font-bold tracking-tight">Hapus semua data & akun?</h2>
                    </div>
                  </div>
                </div>

                <div className="p-8 space-y-6">
                  <p className="text-sm text-slate-500 font-medium leading-relaxed">
                    Tindakan ini <span className="font-bold text-rose-500 underline underline-offset-4">permanen dan tidak bisa dibatalkan</span>. Semua data kamu akan terhapus dari dompetKu.
                  </p>

                  <div className="space-y-4">
                    <div className="space-y-2">
                      <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">Ketik "HAPUS" untuk melanjutkan</label>
                      <input 
                        type="text" 
                        placeholder="HAPUS"
                        value={deleteConfirmText}
                        onChange={(e) => setDeleteConfirmText(e.target.value)}
                        className="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 px-4 text-sm font-bold text-navy-900 focus:ring-4 focus:ring-rose-500/10 outline-none"
                      />
                      {deleteConfirmText && deleteConfirmText !== 'HAPUS' && (
                        <p className="text-[10px] text-rose-500 font-bold ml-1">Ketik HAPUS dengan benar.</p>
                      )}
                    </div>

                    <label className="flex items-start gap-3 p-4 bg-slate-50 rounded-2xl cursor-pointer hover:bg-slate-100 transition-colors group">
                       <input 
                        type="checkbox" 
                        checked={isPermanentConfirmed}
                        onChange={(e) => setIsPermanentConfirmed(e.target.checked)}
                        className="mt-1 w-4 h-4 rounded border-slate-300 text-rose-500 focus:ring-rose-500 transition-all"
                       />
                       <span className="text-xs font-bold text-slate-600 transition-colors group-hover:text-navy-900">Aku paham tindakan ini permanen</span>
                    </label>
                  </div>
                </div>

                <div className="p-8 pt-0 flex gap-3">
                  <button 
                    onClick={() => setShowDeleteModal(false)}
                    className="flex-1 px-4 py-4 rounded-2xl text-sm font-bold text-slate-500 border border-slate-100 hover:bg-slate-50 transition-all uppercase tracking-widest"
                  >
                    Batal
                  </button>
                  <button 
                    disabled={deleteConfirmText !== 'HAPUS' || !isPermanentConfirmed}
                    className="flex-[1.5] bg-rose-500 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-rose-600 text-white px-4 py-4 rounded-2xl font-black text-sm shadow-xl shadow-rose-500/20 active:scale-95 transition-all flex items-center justify-center gap-2 uppercase tracking-widest"
                  >
                    Hapus Permanen
                  </button>
                </div>
              </motion.div>
            </div>
          </>
        )}
      </AnimatePresence>
    </div>
  );
}

function ChevronDown({ className, size }: { className?: string, size?: number }) {
  return (
    <svg 
      className={className} 
      width={size} 
      height={size} 
      viewBox="0 0 24 24" 
      fill="none" 
      stroke="currentColor" 
      strokeWidth="2.5" 
      strokeLinecap="round" 
      strokeLinejoin="round"
    >
      <path d="m6 9 6 6 6-6"/>
    </svg>
  );
}
