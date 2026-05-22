import React, { useState } from 'react';
import { motion, AnimatePresence } from 'motion/react';
import { 
  Lock, 
  Mail, 
  ArrowRight, 
  Wallet, 
  Eye, 
  EyeOff, 
  CheckCircle2,
  X
} from 'lucide-react';
import { cn } from '@/src/lib/utils';

interface LoginPageProps {
  onLogin: () => void;
}

export default function LoginPage({ onLogin }: LoginPageProps) {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [showPassword, setShowPassword] = useState(false);
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState('');
  const [showRegisterModal, setShowRegisterModal] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!email || !password) {
      setError('Email dan password harus diisi.');
      return;
    }
    
    setIsLoading(true);
    setError('');
    
    // Simulate API call
    setTimeout(() => {
      setIsLoading(false);
      onLogin();
    }, 1500);
  };

  return (
    <div className="min-h-screen bg-[#F8FAFC] flex items-center justify-center p-6 font-sans">
      <div className="w-full max-w-[1100px] grid grid-cols-1 md:grid-cols-2 bg-white rounded-[3rem] shadow-2xl overflow-hidden border border-slate-100 min-h-[700px]">
        
        {/* Left Side: Visual/Branding */}
        <div className="hidden md:flex flex-col justify-between p-12 bg-emerald-600 text-white relative overflow-hidden">
          {/* Abstract Decorations */}
          <div className="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl" />
          <div className="absolute bottom-0 left-0 w-96 h-96 bg-emerald-400/20 rounded-full translate-y-1/2 -translate-x-1/2 blur-3xl" />
          
          <div className="relative z-10">
            <div className="flex items-center gap-4 mb-12">
              <div className="bg-white/95 rounded-[14px] p-2.5 shadow-sm flex items-center justify-center">
                <img src="/dompetKuTP.png" alt="dompetKu Logo" className="w-8 h-8 object-contain" />
              </div>
              <span className="text-2xl font-black tracking-tighter italic">dompetKu</span>
            </div>
            
            <div className="space-y-6">
              <motion.h1 
                initial={{ opacity: 0, x: -20 }}
                animate={{ opacity: 1, x: 0 }}
                className="text-5xl font-black leading-[1.1] tracking-tight"
              >
                Kelola uangmu <br />
                <span className="text-emerald-200">lebih cerdas.</span>
              </motion.h1>
              <p className="text-lg text-emerald-50/80 font-medium leading-relaxed max-w-sm">
                Platform manajemen keuangan pribadi tercanggih untuk membantumu mencapai target finansial.
              </p>
            </div>
          </div>

          <div className="relative z-10 space-y-8">
            <div className="flex flex-col gap-4">
              {[
                "Lacak transaksi secara otomatis",
                "Buat budget dengan peringatan cerdas",
                "Pantau perkembangan target finansial"
              ].map((text, i) => (
                <motion.div 
                  initial={{ opacity: 0, y: 10 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ delay: 0.2 + i * 0.1 }}
                  key={i} 
                  className="flex items-center gap-3 bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/10"
                >
                  <div className="w-6 h-6 bg-emerald-400 rounded-full flex items-center justify-center flex-shrink-0">
                    <CheckCircle2 size={14} className="text-emerald-950" strokeWidth={3} />
                  </div>
                  <span className="text-sm font-bold text-white/90">{text}</span>
                </motion.div>
              ))}
            </div>
          </div>
        </div>

        {/* Right Side: Login Form */}
        <div className="flex flex-col justify-center p-8 lg:p-16 relative">
          <div className="max-w-md mx-auto w-full space-y-10">
            <div>
              <h2 className="text-4xl font-black text-slate-900 tracking-tight mb-3 italic">Selamat Datang!</h2>
              <p className="text-slate-400 font-medium">Masuk ke akun dompetKu untuk melanjutkan.</p>
            </div>

            <form onSubmit={handleSubmit} className="space-y-6">
              <div className="space-y-2">
                <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">ALAMAT EMAIL</label>
                <div className="relative group">
                  <div className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                    <Mail size={18} />
                  </div>
                  <input 
                    type="email" 
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    placeholder="nama@email.com" 
                    className="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 pl-12 pr-4 text-sm font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all"
                  />
                </div>
              </div>

              <div className="space-y-2">
                <div className="flex justify-between items-center ml-1">
                  <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest">PASSWORD</label>
                  <button type="button" className="text-[10px] font-black text-emerald-600 uppercase tracking-widest hover:text-emerald-700">Lupa Password?</button>
                </div>
                <div className="relative group">
                  <div className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                    <Lock size={18} />
                  </div>
                  <input 
                    type={showPassword ? "text" : "password"} 
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    placeholder="••••••••" 
                    className="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 pl-12 pr-12 text-sm font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none hover:bg-slate-100 transition-all"
                  />
                  <button 
                    type="button"
                    onClick={() => setShowPassword(!showPassword)}
                    className="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-navy-900 transition-colors"
                  >
                    {showPassword ? <EyeOff size={18} /> : <Eye size={18} />}
                  </button>
                </div>
              </div>

              {error && (
                <motion.p 
                  initial={{ opacity: 0, y: -10 }}
                  animate={{ opacity: 1, y: 0 }}
                  className="text-xs font-bold text-rose-500 bg-rose-50 p-3 rounded-xl border border-rose-100 flex items-center gap-2"
                >
                  <AlertTriangle size={14} /> {error}
                </motion.p>
              )}

              <button 
                type="submit"
                disabled={isLoading}
                className="w-full bg-emerald-500 hover:bg-emerald-600 text-white py-4 rounded-2xl font-black text-sm shadow-xl shadow-emerald-500/20 active:scale-[0.98] transition-all flex items-center justify-center gap-2 uppercase tracking-widest disabled:opacity-50 disabled:cursor-not-allowed group"
              >
                {isLoading ? (
                  <div className="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin" />
                ) : (
                  <>
                    Masuk Sekarang <ArrowRight size={18} className="group-hover:translate-x-1 transition-transform" />
                  </>
                )}
              </button>
            </form>

            <p className="text-center text-sm font-bold text-slate-400">
              Belum punya akun? <button type="button" onClick={() => setShowRegisterModal(true)} className="text-emerald-600 hover:text-emerald-700 underline underline-offset-4">Daftar Gratis</button>
            </p>
          </div>
          
          <div className="absolute bottom-8 left-0 right-0 text-center">
            <p className="text-[10px] font-black text-slate-200 uppercase tracking-[0.2em] pointer-events-none">DompetKu Finance © 2026</p>
          </div>
        </div>
      </div>

      <RegisterModal isOpen={showRegisterModal} onClose={() => setShowRegisterModal(false)} />
    </div>
  );
}

function AlertTriangle({ size, className }: { size?: number, className?: string }) {
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
      <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
      <path d="M12 9v4"/>
      <path d="M12 17h.01"/>
    </svg>
  );
}

function RegisterModal({ isOpen, onClose }: { isOpen: boolean, onClose: () => void }) {
  return (
    <AnimatePresence>
      {isOpen && (
        <>
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            onClick={onClose}
            className="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[100]"
          />
          <div className="fixed inset-0 flex items-center justify-center z-[101] p-4 pointer-events-none">
            <motion.div
              initial={{ scale: 0.9, opacity: 0, y: 20 }}
              animate={{ scale: 1, opacity: 1, y: 0 }}
              exit={{ scale: 0.9, opacity: 0, y: 20 }}
              className="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl pointer-events-auto overflow-hidden p-8 space-y-8"
            >
              <div className="flex justify-between items-start">
                <div className="bg-white/95 rounded-[14px] p-2 shadow-sm flex items-center justify-center text-emerald-600 border border-slate-100">
                  <img src="/dompetKuTP.png" alt="dompetKu Logo" className="w-6 h-6 object-contain" />
                </div>
                <button onClick={onClose} className="p-2 hover:bg-slate-100 rounded-xl transition-colors">
                  <X size={20} className="text-slate-400" />
                </button>
              </div>

              <div>
                <h2 className="text-2xl font-black text-slate-900 tracking-tight italic">Daftar Akun Baru</h2>
                <p className="text-sm text-slate-400 font-medium">Mulai perjalanan finansialmu hari ini.</p>
              </div>

              <div className="space-y-4">
                <div className="space-y-2">
                  <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">NAMA LENGKAP</label>
                  <input type="text" placeholder="Masukkan nama lengkap" className="w-full bg-slate-50 border border-slate-100 rounded-2xl py-3.5 px-4 text-sm font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none" />
                </div>
                <div className="space-y-2">
                  <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">ALAMAT EMAIL</label>
                  <input type="email" placeholder="nama@email.com" className="w-full bg-slate-50 border border-slate-100 rounded-2xl py-3.5 px-4 text-sm font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none" />
                </div>
                <div className="space-y-2">
                  <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">PASSWORD</label>
                  <input type="password" placeholder="••••••••" className="w-full bg-slate-50 border border-slate-100 rounded-2xl py-3.5 px-4 text-sm font-bold text-navy-900 focus:ring-4 focus:ring-emerald-500/10 outline-none" />
                </div>
              </div>

              <button className="w-full bg-emerald-500 hover:bg-emerald-600 text-white py-4 rounded-2xl font-black text-sm shadow-xl shadow-emerald-500/20 active:scale-[0.98] transition-all flex items-center justify-center gap-2 uppercase tracking-widest">
                Daftar Sekarang <ArrowRight size={18} />
              </button>

              <p className="text-center text-xs font-bold text-slate-400">
                Sudah punya akun? <button onClick={onClose} className="text-emerald-600 hover:text-emerald-700 underline underline-offset-4">Masuk</button>
              </p>
            </motion.div>
          </div>
        </>
      )}
    </AnimatePresence>
  );
}
