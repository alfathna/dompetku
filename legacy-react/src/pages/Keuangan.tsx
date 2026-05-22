import React, { useState } from 'react';
import { 
  Search, 
  Filter, 
  Download, 
  Edit3, 
  Trash2, 
  ArrowUp, 
  ArrowDown,
  Wallet,
  CreditCard,
  Smartphone,
  Banknote,
  MoreVertical,
  CheckCircle2,
  Clock,
  ChevronRight
} from 'lucide-react';
import { motion, AnimatePresence } from 'motion/react';
import { cn } from '@/src/lib/utils';

// Subcomponents
import AddTransactionModal from '@/src/components/modals/AddTransactionModal';
import AddWalletModal from '@/src/components/modals/AddWalletModal';
import AddBudgetModal from '@/src/components/modals/AddBudgetModal';
import AddBillModal from '@/src/components/modals/AddBillModal';

const TABS = [
  { id: 'transaksi', label: 'Transaksi', icon: Clock },
  { id: 'wallet', label: 'Wallet', icon: Wallet },
  { id: 'budget', label: 'Budget', icon: CreditCard },
  { id: 'tagihan', label: 'Tagihan', icon: Smartphone },
];

export default function KeuanganPage() {
  const [activeTab, setActiveTab] = useState('transaksi');
  const [showAddModal, setShowAddModal] = useState(false);
  const [showAddWalletModal, setShowAddWalletModal] = useState(false);
  const [showAddBudgetModal, setShowAddBudgetModal] = useState(false);
  const [showAddBillModal, setShowAddBillModal] = useState(false);
  const [showPayConfirm, setShowPayConfirm] = useState<any>(null);

  return (
    <div className="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
      {/* Header */}
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
          <h1 className="text-3xl font-bold text-slate-900 tracking-tight">Kelola Keuangan</h1>
          <p className="text-slate-500 mt-1">Lacak transaksi, dompet, dan rencana belanjamu.</p>
        </div>
        <div className="flex items-center gap-2 bg-white p-1 rounded-2xl border border-slate-100 shadow-sm overflow-x-auto no-scrollbar">
          {TABS.map((tab) => (
            <button
              key={tab.id}
              onClick={() => setActiveTab(tab.id)}
              className={cn(
                "flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold transition-all whitespace-nowrap",
                activeTab === tab.id 
                  ? "bg-emerald-500 text-white shadow-lg shadow-emerald-500/20" 
                  : "text-slate-400 hover:text-slate-600 hover:bg-slate-50"
              )}
            >
              <tab.icon size={18} />
              {tab.label}
            </button>
          ))}
        </div>
      </div>

      {/* Dynamic Content */}
      <AnimatePresence mode="wait">
        {activeTab === 'transaksi' && <TransaksiTab onAdd={() => setShowAddModal(true)} />}
        {activeTab === 'wallet' && <WalletTab onAdd={() => setShowAddWalletModal(true)} />}
        {activeTab === 'budget' && <BudgetTab onAdd={() => setShowAddBudgetModal(true)} />}
        {activeTab === 'tagihan' && <TagihanTab onAdd={() => setShowAddBillModal(true)} onPay={(bill: any) => setShowPayConfirm(bill)} />}
      </AnimatePresence>

      {/* Modals */}
      <AddTransactionModal 
        isOpen={showAddModal} 
        onClose={() => setShowAddModal(false)} 
      />
      <AddWalletModal 
        isOpen={showAddWalletModal} 
        onClose={() => setShowAddWalletModal(false)} 
      />
      <AddBudgetModal 
        isOpen={showAddBudgetModal} 
        onClose={() => setShowAddBudgetModal(false)} 
      />
      <AddBillModal 
        isOpen={showAddBillModal} 
        onClose={() => setShowAddBillModal(false)} 
      />

      {/* Confirmation Dialog for Bill Payment */}
      <AnimatePresence>
        {showPayConfirm && (
          <>
            <motion.div
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              exit={{ opacity: 0 }}
              onClick={() => setShowPayConfirm(null)}
              className="fixed inset-0 bg-navy-950/40 backdrop-blur-sm z-[200]"
            />
            <div className="fixed inset-0 flex items-center justify-center z-[201] p-4 pointer-events-none">
              <motion.div
                initial={{ scale: 0.9, opacity: 0 }}
                animate={{ scale: 1, opacity: 1 }}
                exit={{ scale: 0.9, opacity: 0 }}
                className="bg-white w-full max-w-sm rounded-[2.5rem] shadow-2xl pointer-events-auto p-8 overflow-hidden relative"
              >
                <div className="text-center space-y-4">
                  <div className="w-16 h-16 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mx-auto">
                    <CheckCircle2 size={32} />
                  </div>
                  <div className="space-y-1">
                    <h3 className="text-xl font-bold text-navy-900 tracking-tight">Bayar Tagihan?</h3>
                    <p className="text-sm text-slate-500">
                      Pembayaran <span className="font-bold text-navy-900">{showPayConfirm.title}</span> sebesar <span className="font-bold text-navy-900">{showPayConfirm.amount}</span> akan membuat transaksi pengeluaran dan mengurangi saldo wallet.
                    </p>
                  </div>
                  <div className="grid grid-cols-2 gap-3 pt-4">
                    <button 
                      onClick={() => setShowPayConfirm(null)}
                      className="px-6 py-3 rounded-2xl text-sm font-bold text-slate-500 hover:bg-slate-50 transition-all uppercase tracking-widest"
                    >
                      Batal
                    </button>
                    <button 
                      onClick={() => {
                        // In a real app, logic to process payment would go here
                        setShowPayConfirm(null);
                      }}
                      className="bg-navy-900 hover:bg-emerald-600 text-white px-6 py-3 rounded-2xl font-black text-sm transition-all uppercase tracking-widest"
                    >
                      Bayar
                    </button>
                  </div>
                </div>
              </motion.div>
            </div>
          </>
        )}
      </AnimatePresence>
    </div>
  );
}

function TransaksiTab({ onAdd }: { onAdd: () => void }) {
  return (
    <motion.div 
      initial={{ opacity: 0, x: 20 }}
      animate={{ opacity: 1, x: 0 }}
      exit={{ opacity: 0, x: -20 }}
      className="space-y-6"
    >
      <div className="flex flex-wrap items-center justify-between gap-4">
        <div className="flex items-center gap-3 flex-1 min-w-[280px]">
          <div className="relative flex-1 max-w-sm">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
            <input 
              type="text" 
              placeholder="Cari transaksi..." 
              className="w-full bg-white border border-slate-200 rounded-2xl py-2.5 pl-10 pr-4 text-sm focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all"
            />
          </div>
          <button className="p-3 bg-white border border-slate-200 rounded-2xl text-slate-500 hover:bg-slate-50 transition-colors">
            <Filter size={18} />
          </button>
        </div>
        <div className="flex items-center gap-3">
          <button className="flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-600 hover:bg-slate-50 transition-all">
            <Download size={18} /> Export
          </button>
          <button 
            onClick={onAdd}
            className="flex items-center gap-2 px-6 py-2.5 bg-emerald-500 text-white rounded-2xl text-sm font-bold shadow-lg shadow-emerald-500/20 hover:bg-emerald-600 transition-all"
          >
            Tambah Transaksi
          </button>
        </div>
      </div>

      <div className="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
        <div className="overflow-x-auto custom-scrollbar">
          <table className="w-full text-left border-collapse">
            <thead>
              <tr className="bg-slate-50/50">
                <th className="px-8 py-5 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Transaksi</th>
                <th className="px-6 py-5 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-center">Kategori</th>
                <th className="px-6 py-5 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-center">Dompet</th>
                <th className="px-6 py-5 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-center">Tipe</th>
                <th className="px-6 py-5 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Tanggal</th>
                <th className="px-6 py-5 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Nominal</th>
                <th className="px-8 py-5 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-50">
              {[
                { name: 'Makan Bakso', cat: 'Makanan', wallet: 'Cash', type: 'expense', date: '18 Mei 2026', amount: '-Rp 45.000' },
                { name: 'Spotify Premium', cat: 'Hiburan', wallet: 'OVO', type: 'expense', date: '17 Mei 2026', amount: '-Rp 89.000' },
                { name: 'Gaji Freelance', cat: 'Freelance', wallet: 'BCA', type: 'income', date: '16 Mei 2026', amount: '+Rp 5.500.000' },
                { name: 'Bonus Tahunan', cat: 'Bonus', wallet: 'BCA', type: 'income', date: '15 Mei 2026', amount: '+Rp 2.000.000' },
              ].map((item, idx) => (
                <tr key={idx} className="hover:bg-slate-50/50 transition-colors group">
                  <td className="px-8 py-5">
                    <span className="font-semibold text-slate-900">{item.name}</span>
                  </td>
                  <td className="px-6 py-5 text-center">
                    <span className="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider">{item.cat}</span>
                  </td>
                  <td className="px-6 py-5 text-center">
                    <span className="text-sm font-medium text-slate-500">{item.wallet}</span>
                  </td>
                  <td className="px-6 py-5 text-center">
                    <div className="flex justify-center">
                      {item.type === 'income' ? (
                        <div className="bg-emerald-50 text-emerald-600 p-1 rounded-lg"><ArrowUp size={14} /></div>
                      ) : (
                        <div className="bg-rose-50 text-rose-600 p-1 rounded-lg"><ArrowDown size={14} /></div>
                      )}
                    </div>
                  </td>
                  <td className="px-6 py-5">
                    <span className="text-sm text-slate-500">{item.date}</span>
                  </td>
                  <td className="px-6 py-5 font-bold tabular-nums">
                    <span className={item.type === 'income' ? "text-emerald-500" : "text-rose-500"}>
                      {item.amount}
                    </span>
                  </td>
                  <td className="px-8 py-5 text-right">
                    <div className="flex justify-end gap-2">
                       <button className="p-2 hover:bg-white rounded-xl shadow-sm border border-transparent hover:border-slate-100 text-slate-400 hover:text-emerald-500"><Edit3 size={16} /></button>
                       <button className="p-2 hover:bg-white rounded-xl shadow-sm border border-transparent hover:border-slate-100 text-slate-400 hover:text-rose-500"><Trash2 size={16} /></button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </motion.div>
  );
}

function WalletTab({ onAdd }: { onAdd: () => void }) {
  const WALLETS = [
    { title: 'Cash', balance: 'Rp 1.250.000', icon: Banknote, color: 'emerald', txCount: 12 },
    { title: 'BCA Account', balance: 'Rp 42.400.000', icon: CreditCard, color: 'navy', txCount: 45 },
    { title: 'OVO Wallet', balance: 'Rp 850.000', icon: Smartphone, color: 'purple', txCount: 28 },
    { title: 'Dana', balance: 'Rp 750.000', icon: Smartphone, color: 'blue', txCount: 15 },
  ];

  return (
    <motion.div 
      initial={{ opacity: 0, scale: 0.95 }}
      animate={{ opacity: 1, scale: 1 }}
      exit={{ opacity: 0, scale: 0.95 }}
      className="space-y-6"
    >
      <div className="flex justify-end">
        <button 
          onClick={onAdd}
          className="flex items-center gap-2 px-6 py-2.5 bg-emerald-500 text-white rounded-2xl text-sm font-bold shadow-lg shadow-emerald-500/20 hover:bg-emerald-600 transition-all"
        >
          Tambah Wallet
        </button>
      </div>
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {WALLETS.map((wallet, idx) => (
          <div key={idx} className="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm group hover:shadow-xl transition-all relative overflow-hidden">
            <div className={cn("absolute top-0 right-0 w-24 h-24 rounded-full -mr-12 -mt-12 group-hover:scale-150 transition-transform", 
              wallet.color === 'emerald' ? 'bg-emerald-500/5' : 
              wallet.color === 'navy' ? 'bg-navy-900/5' : 
              wallet.color === 'purple' ? 'bg-purple-500/5' : 'bg-blue-500/5'
            )} />
            <div className="flex items-center gap-4 mb-8">
              <div className={cn("p-3 rounded-2xl", 
                wallet.color === 'emerald' ? 'bg-emerald-500/10 text-emerald-600' : 
                wallet.color === 'navy' ? 'bg-navy-900/10 text-navy-900' : 
                wallet.color === 'purple' ? 'bg-purple-500/10 text-purple-600' : 'bg-blue-500/10 text-blue-600'
              )}>
                <wallet.icon size={24} />
              </div>
              <h3 className="font-bold text-slate-900">{wallet.title}</h3>
            </div>
            <div className="space-y-1">
              <p className="text-xs text-slate-400 font-bold uppercase tracking-wider">Total Saldo</p>
              <p className="text-2xl font-bold text-navy-900 tracking-tight">{wallet.balance}</p>
            </div>
            <div className="mt-6 flex items-center justify-between">
              <p className="text-xs text-slate-500 font-medium">{wallet.txCount} Transaksi</p>
              <button className="text-emerald-500 font-bold text-xs hover:underline flex items-center gap-1">Detail <ChevronRight size={14} /></button>
            </div>
          </div>
        ))}
      </div>
    </motion.div>
  );
}

function BudgetTab({ onAdd }: { onAdd: () => void }) {
  const BUDGETS = [
    { title: 'Makanan', limit: 3000000, used: 2400000, color: 'emerald' },
    { title: 'Transportasi', limit: 1500000, used: 1200000, color: 'blue' },
    { title: 'Hiburan', limit: 1000000, used: 950000, color: 'amber' },
    { title: 'Tagihan', limit: 2000000, used: 2200000, color: 'rose' },
  ];

  return (
    <motion.div 
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      exit={{ opacity: 0, y: -20 }}
      className="space-y-6"
    >
      <div className="flex justify-end">
        <button 
          onClick={onAdd}
          className="flex items-center gap-2 px-6 py-2.5 bg-emerald-500 text-white rounded-2xl text-sm font-bold shadow-lg shadow-emerald-500/20 hover:bg-emerald-600 transition-all"
        >
          Tambah Budget
        </button>
      </div>
      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
        {BUDGETS.map((item, idx) => {
          const percent = Math.min((item.used / item.limit) * 100, 100);
          const over = item.used > item.limit;
          const color = over ? 'rose' : percent > 85 ? 'amber' : item.color;
          
          return (
            <div key={idx} className="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm space-y-6">
              <div className="flex items-center justify-between">
                <div>
                  <h4 className="text-xl font-bold text-navy-900">{item.title}</h4>
                  <p className="text-sm text-slate-500">Masa berlaku: 31 Mei 2026</p>
                </div>
                <div className="flex gap-2">
                  <button className="p-2 hover:bg-slate-50 rounded-xl text-slate-400 transition-colors"><Edit3 size={18} /></button>
                  <button className="p-2 hover:bg-slate-50 rounded-xl text-slate-400 transition-colors"><MoreVertical size={18} /></button>
                </div>
              </div>

              <div className="space-y-4">
                <div className="flex items-end justify-between">
                  <div>
                    <p className="text-3xl font-black text-navy-900">Rp {item.used.toLocaleString()}</p>
                    <p className="text-sm text-slate-400 mt-1">Sisa limit: Rp {(item.limit - item.used).toLocaleString()}</p>
                  </div>
                  <div className={cn("px-4 py-1.5 rounded-xl text-sm font-bold uppercase tracking-tight", 
                    color === 'rose' ? 'bg-rose-50 text-rose-600' : color === 'amber' ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600'
                  )}>
                    {Math.round(percent)}% Terpakai
                  </div>
                </div>

                <div className="h-3 bg-slate-100 rounded-full overflow-hidden">
                  <motion.div 
                    initial={{ width: 0 }}
                    animate={{ width: `${percent}%` }}
                    transition={{ duration: 1, ease: 'easeOut' }}
                    className={cn("h-full rounded-full",
                      color === 'rose' ? 'bg-rose-500' : color === 'amber' ? 'bg-amber-500' : 'bg-emerald-500'
                    )}
                  />
                </div>

                <div className="flex justify-between items-center pt-2 border-t border-slate-50 text-sm font-semibold">
                  <span className="text-slate-400 uppercase tracking-widest text-[10px]">Total Limit Budget</span>
                  <span className="text-navy-900">Rp {item.limit.toLocaleString()}</span>
                </div>
              </div>
              
              {over && (
                <div className="bg-rose-50 p-4 rounded-2xl flex items-center gap-3 text-rose-600 border border-rose-100 animate-pulse">
                  <Smartphone size={20} />
                  <p className="text-xs font-bold leading-tight uppercase tracking-tight">Warning: Budget Overlimit Rp {(item.used - item.limit).toLocaleString()}</p>
                </div>
              )}
            </div>
          );
        })}
      </div>
    </motion.div>
  );
}

function TagihanTab({ onAdd, onPay }: { onAdd: () => void, onPay: (bill: any) => void }) {
  const BILLS = [
    { title: 'WiFi Home Fiber', date: '20 Mei', amount: 'Rp 350.000', status: 'Pending', icon: Smartphone },
    { title: 'Netflix Premium', date: '22 Mei', amount: 'Rp 189.000', status: 'Paid', icon: CheckCircle2 },
    { title: 'Listrik & Token', date: '25 Mei', amount: 'Rp 500.000', status: 'Pending', icon: Smartphone },
  ];

  return (
    <motion.div 
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      exit={{ opacity: 0, y: -20 }}
      className="space-y-6"
    >
      <div className="flex justify-end">
        <button 
          onClick={onAdd}
          className="flex items-center gap-2 px-6 py-2.5 bg-emerald-500 text-white rounded-2xl text-sm font-bold shadow-lg shadow-emerald-500/20 hover:bg-emerald-600 transition-all"
        >
          Tambah Tagihan
        </button>
      </div>
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        {BILLS.map((bill, idx) => (
          <div key={idx} className="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm space-y-6 relative group overflow-hidden">
            <div className={cn("absolute top-4 right-4", bill.status === 'Paid' ? 'text-emerald-500' : 'text-rose-500')}>
              <bill.icon size={22} />
            </div>
            <div className="pt-2">
              <span className={`px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest ${bill.status === 'Paid' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600'}`}>
                {bill.status}
              </span>
              <h4 className="mt-4 font-bold text-lg text-navy-900 leading-tight">{bill.title}</h4>
              <p className="text-sm text-slate-400 mt-1">Jatuh tempo: {bill.date}</p>
            </div>
            <div className="flex items-center justify-between items-end">
              <p className="text-2xl font-black text-navy-900 tracking-tight">{bill.amount}</p>
              <button 
                onClick={() => bill.status === 'Pending' && onPay(bill)}
                className={cn("px-4 py-2 rounded-xl text-xs font-bold transition-all", 
                  bill.status === 'Paid' ? 'bg-slate-100 text-slate-400 cursor-default' : 'bg-navy-900 text-white hover:bg-emerald-600 active:scale-95'
                )}
              >
                {bill.status === 'Paid' ? 'Sudah Bayar' : 'Bayar Sekarang'}
              </button>
            </div>
          </div>
        ))}
      </div>
    </motion.div>
  );
}
