import React, { useState } from 'react';
import { 
  LayoutDashboard, 
  Wallet, 
  Target, 
  BarChart3, 
  Settings, 
  LogOut,
  ChevronLeft,
  ChevronRight,
  Menu,
  X
} from 'lucide-react';
import { motion, AnimatePresence } from 'motion/react';
import { cn } from '@/src/lib/utils';

interface SidebarProps {
  activeTab: string;
  setActiveTab: (tab: string) => void;
  onLogout: () => void;
  collapsed: boolean;
  setCollapsed: (collapsed: boolean) => void;
}

const MENU_ITEMS = [
  { id: 'dashboard', label: 'Dashboard', icon: LayoutDashboard },
  { id: 'keuangan', label: 'Keuangan', icon: Wallet },
  { id: 'goals', label: 'Goals', icon: Target },
  { id: 'statistik', label: 'Statistik', icon: BarChart3 },
  { id: 'pengaturan', label: 'Pengaturan', icon: Settings },
];

export default function Sidebar({ activeTab, setActiveTab, onLogout, collapsed, setCollapsed }: SidebarProps) {
  const [mobileOpen, setMobileOpen] = useState(false);

  return (
    <>
      {/* Mobile Toggle */}
      <div className="lg:hidden fixed top-4 left-4 z-50">
        <button 
          onClick={() => setMobileOpen(!mobileOpen)}
          className="p-2 bg-white rounded-lg shadow-md text-navy-900"
        >
          {mobileOpen ? <X size={24} /> : <Menu size={24} />}
        </button>
      </div>

      {/* Backdrop */}
      <AnimatePresence>
        {mobileOpen && (
          <motion.div 
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            onClick={() => setMobileOpen(false)}
            className="fixed inset-0 bg-black/50 z-40 lg:hidden backdrop-blur-sm"
          />
        )}
      </AnimatePresence>

      <aside 
        className={cn(
          "fixed top-0 left-0 h-full bg-navy-900 text-slate-300 transition-all duration-300 z-40 flex flex-col",
          collapsed ? "w-20" : "w-64",
          mobileOpen ? "translate-x-0" : "-translate-x-full lg:translate-x-0"
        )}
      >
        {/* Logo */}
        <div className="p-6 flex items-center gap-3">
          <div className="bg-white/95 rounded-[14px] p-2 shadow-sm flex items-center justify-center flex-shrink-0">
            <img src="/dompetKuTP.png" alt="dompetKu Logo" className="w-6 h-6 object-contain" />
          </div>
          {!collapsed && (
            <motion.span 
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              className="font-bold text-xl text-white tracking-tight"
            >
              dompet<span className="text-emerald-500">Ku</span>
            </motion.span>
          )}
        </div>

        {/* Menu Items */}
        <nav className="flex-1 px-3 mt-4 space-y-1">
          {MENU_ITEMS.map((item) => (
            <button
              key={item.id}
              onClick={() => {
                setActiveTab(item.id);
                setMobileOpen(false);
              }}
              className={cn(
                "w-full flex items-center gap-3 px-3 py-3 rounded-xl transition-all group relative",
                activeTab === item.id 
                  ? "bg-emerald-500 text-white shadow-lg shadow-emerald-500/20" 
                  : "hover:bg-white/5 hover:text-white"
              )}
            >
              <item.icon size={20} className={cn(
                "flex-shrink-0 transition-transform group-hover:scale-110",
                activeTab === item.id ? "text-white" : "text-slate-400 group-hover:text-emerald-400"
              )} />
              {!collapsed && (
                <span className="font-medium whitespace-nowrap">{item.label}</span>
              )}
              {activeTab === item.id && collapsed && (
                <div className="absolute right-0 w-1 h-6 bg-white rounded-l-full" />
              )}
            </button>
          ))}
        </nav>

        {/* Bottom Actions */}
        <div className="p-3 border-t border-white/5 space-y-1">
          <button 
            onClick={onLogout}
            className="w-full flex items-center gap-3 px-3 py-3 rounded-xl transition-all hover:bg-white/5 group"
          >
            <LogOut size={20} className="text-slate-400 group-hover:text-red-400" />
            {!collapsed && <span className="font-medium text-slate-400 group-hover:text-white">Logout</span>}
          </button>
          
          <button 
            onClick={() => setCollapsed(!collapsed)}
            className="hidden lg:flex w-full items-center gap-3 px-3 py-3 rounded-xl transition-all hover:bg-white/5"
          >
            {collapsed ? <ChevronRight size={20} /> : <ChevronLeft size={20} />}
            {!collapsed && <span className="font-medium">Collapse</span>}
          </button>
        </div>
      </aside>
    </>
  );
}
