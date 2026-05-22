import React, { useState, useRef, useEffect } from 'react';
import { Search, Bell, ChevronDown, User, LogOut } from 'lucide-react';
import { motion, AnimatePresence } from 'motion/react';
import { cn } from '@/src/lib/utils';

interface TopbarProps {
  setActiveTab: (tab: string) => void;
  onLogout: () => void;
  isCollapsed: boolean;
}

export default function Topbar({ setActiveTab, onLogout, isCollapsed }: TopbarProps) {
  const [isDropdownOpen, setIsDropdownOpen] = useState(false);
  const dropdownRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    function handleClickOutside(event: MouseEvent) {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target as Node)) {
        setIsDropdownOpen(false);
      }
    }
    document.addEventListener("mousedown", handleClickOutside);
    return () => document.removeEventListener("mousedown", handleClickOutside);
  }, []);

  return (
    <header className={cn(
      "h-20 bg-white/80 backdrop-blur-md border-b border-slate-100 flex items-center justify-between px-8 fixed top-0 right-0 left-0 z-30 transition-all duration-300",
      isCollapsed ? "lg:left-20" : "lg:left-64"
    )}>
      <div className="flex-1 max-w-md hidden md:block">
        <div className="relative group">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-500 transition-colors" size={18} />
          <input 
            type="text" 
            placeholder="Cari transaksi, budget..." 
            className="w-full bg-slate-100 border-none rounded-xl py-2.5 pl-10 pr-4 text-sm focus:ring-2 focus:ring-emerald-500/20 transition-all outline-none"
          />
        </div>
      </div>

      <div className="flex items-center gap-4 ml-auto">
        <button className="p-2.5 text-slate-500 hover:bg-slate-50 rounded-xl relative transition-colors">
          <Bell size={20} />
          <span className="absolute top-2.5 right-2.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white" />
        </button>

        <div className="h-8 w-px bg-slate-100 mx-2" />

        <div className="relative" ref={dropdownRef}>
          <motion.button 
            whileHover={{ scale: 1.02 }}
            whileTap={{ scale: 0.98 }}
            onClick={() => setIsDropdownOpen(!isDropdownOpen)}
            className={cn(
              "flex items-center gap-3 p-1.5 pr-3 rounded-xl transition-all group",
              isDropdownOpen ? "bg-slate-50 shadow-sm" : "hover:bg-slate-50"
            )}
          >
            <div className="w-9 h-9 bg-emerald-100 rounded-full flex items-center justify-center border-2 border-white shadow-sm">
              <img 
                src="https://api.dicebear.com/7.x/avataaars/svg?seed=Felix" 
                alt="Avatar" 
                className="w-8 h-8 rounded-full"
              />
            </div>
            <div className="text-left hidden sm:block">
              <div className="text-sm font-semibold text-slate-900 group-hover:text-emerald-600 transition-colors tracking-tight">Felix Wijaya</div>
            </div>
            <ChevronDown 
              size={16} 
              className={cn(
                "text-slate-400 group-hover:text-slate-600 transition-all",
                isDropdownOpen && "rotate-180"
              )} 
            />
          </motion.button>

          <AnimatePresence>
            {isDropdownOpen && (
              <motion.div
                initial={{ opacity: 0, y: 10, scale: 0.95 }}
                animate={{ opacity: 1, y: 0, scale: 1 }}
                exit={{ opacity: 0, y: 10, scale: 0.95 }}
                className="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden z-50 p-1.5 origin-top-right"
              >
                <button 
                  onClick={() => {
                    setActiveTab('pengaturan');
                    setIsDropdownOpen(false);
                  }}
                  className="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 rounded-xl transition-all group"
                >
                  <div className="p-1.5 bg-emerald-100 text-emerald-600 rounded-lg group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                    <User size={16} />
                  </div>
                  Profil Saya
                </button>
                <div className="h-px bg-slate-50 my-1.5 mx-2" />
                <button 
                  onClick={() => {
                    setIsDropdownOpen(false);
                    onLogout();
                  }}
                  className="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-bold text-rose-500 hover:bg-rose-50 rounded-xl transition-all group"
                >
                  <div className="p-1.5 bg-rose-100 text-rose-600 rounded-lg group-hover:bg-rose-500 group-hover:text-white transition-colors">
                    <LogOut size={16} />
                  </div>
                  Logout Akun
                </button>
              </motion.div>
            )}
          </AnimatePresence>
        </div>
      </div>
    </header>
  );
}
