/**
 * @license
 * SPDX-License-Identifier: Apache-2.0
 */

import React, { useState } from 'react';
import Sidebar from '@/src/components/Sidebar';
import Topbar from '@/src/components/Topbar';
import DashboardPage from '@/src/pages/Dashboard';
import KeuanganPage from '@/src/pages/Keuangan';
import GoalsPage from '@/src/pages/Goals';
import StatisticsPage from '@/src/pages/Statistik';
import SettingsPage from '@/src/pages/Settings';
import LoginPage from '@/src/pages/Login';
import { motion, AnimatePresence } from 'motion/react';
import { Plus } from 'lucide-react';
import AddTransactionModal from '@/src/components/modals/AddTransactionModal';
import { cn } from '@/src/lib/utils';

export default function App() {
  const [activeTab, setActiveTab] = useState('dashboard');
  const [showAddModal, setShowAddModal] = useState(false);
  const [isLoggedIn, setIsLoggedIn] = useState(false); // Start at login page
  const [isCollapsed, setIsCollapsed] = useState(false);

  const handleLogout = () => {
    setIsLoggedIn(false);
  };

  const handleLogin = () => {
    setIsLoggedIn(true);
    setActiveTab('dashboard');
  };

  if (!isLoggedIn) {
    return <LoginPage onLogin={handleLogin} />;
  }

  const renderContent = () => {
    switch (activeTab) {
      case 'dashboard':
        return <DashboardPage />;
      case 'keuangan':
        return <KeuanganPage />;
      case 'goals':
        return <GoalsPage />;
      case 'statistik':
        return <StatisticsPage />;
      case 'pengaturan':
        return <SettingsPage />;
      default:
        return <DashboardPage />;
    }
  };

  return (
    <div className="min-h-screen bg-slate-50 flex font-sans selection:bg-emerald-100 selection:text-emerald-900">
      <Sidebar 
        activeTab={activeTab} 
        setActiveTab={setActiveTab} 
        onLogout={handleLogout} 
        collapsed={isCollapsed}
        setCollapsed={setIsCollapsed}
      />
      
      <main className="flex-1 flex flex-col min-w-0">
        <Topbar setActiveTab={setActiveTab} onLogout={handleLogout} isCollapsed={isCollapsed} />
        
        {/* Page Container */}
        <div className={cn("mt-20 p-4 md:p-8 ml-0 transition-all duration-300", isCollapsed ? "lg:ml-20" : "lg:ml-64")}>
          <div className="max-w-7xl mx-auto">
            <AnimatePresence mode="wait">
              <motion.div
                key={activeTab}
                initial={{ opacity: 0, y: 10 }}
                animate={{ opacity: 1, y: 0 }}
                exit={{ opacity: 0, y: -10 }}
                transition={{ duration: 0.3, ease: "easeOut" }}
              >
                {renderContent()}
              </motion.div>
            </AnimatePresence>
          </div>
        </div>

      </main>

      <AddTransactionModal 
        isOpen={showAddModal} 
        onClose={() => setShowAddModal(false)}
      />
    </div>
  );
}
