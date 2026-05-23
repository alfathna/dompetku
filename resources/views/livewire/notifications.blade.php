<div>
    <div class="mb-8">
        <h1 class="text-2xl font-black text-navy-900 mb-2">Notifikasi</h1>
        <p class="text-sm font-semibold text-slate-400">Kelola semua pemberitahuan dan peringatan budget Anda.</p>
    </div>

    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-2 bg-slate-50 p-1 rounded-xl">
                <button wire:click="setFilter('semua')" class="px-4 py-1.5 rounded-lg text-sm font-bold transition-all {{ $filter === 'semua' ? 'bg-white text-navy-900 shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">Semua ({{ $countAll }})</button>
                <button wire:click="setFilter('belum_dibaca')" class="px-4 py-1.5 rounded-lg text-sm font-bold transition-all {{ $filter === 'belum_dibaca' ? 'bg-white text-navy-900 shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">Belum Dibaca ({{ $countUnread }})</button>
                <button wire:click="setFilter('dibaca')" class="px-4 py-1.5 rounded-lg text-sm font-bold transition-all {{ $filter === 'dibaca' ? 'bg-white text-navy-900 shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">Dibaca ({{ $countRead }})</button>
            </div>
            @if($notifications->whereNull('read_at')->count() > 0)
            <button wire:click="markAllAsRead" class="text-xs font-bold text-emerald-500 hover:text-emerald-600 transition-colors bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded-lg">
                Tandai Semua Dibaca
            </button>
            @endif
        </div>

        <div class="space-y-4">
            @forelse($notifications as $notification)
                <div class="flex items-start justify-between p-4 rounded-2xl border transition-all {{ $notification->read_at ? 'bg-slate-50 border-slate-100' : 'bg-orange-50/30 border-orange-100' }}">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ $notification->read_at ? 'bg-slate-100 text-slate-400' : 'bg-orange-100 text-orange-500' }}">
                            @if(isset($notification->data['due_date']))
                                <x-lucide-bell-ring class="w-5 h-5" />
                            @else
                                <x-lucide-alert-triangle class="w-5 h-5" />
                            @endif
                        </div>
                        <div>
                            <h3 class="text-sm font-bold {{ $notification->read_at ? 'text-slate-600' : 'text-navy-900' }}">
                                {{ $notification->data['message'] }}
                            </h3>
                            @if(isset($notification->data['percentage']))
                                <p class="text-xs font-semibold text-slate-400 mt-1">
                                    Terpakai {{ $notification->data['percentage'] }}% (Rp {{ number_format($notification->data['usedAmount'], 0, ',', '.') }})
                                </p>
                            @elseif(isset($notification->data['due_date']))
                                <p class="text-xs font-semibold text-slate-400 mt-1">
                                    Nominal: Rp {{ number_format($notification->data['amount'], 0, ',', '.') }} • Jatuh Tempo: {{ \Carbon\Carbon::parse($notification->data['due_date'])->format('d M') }}
                                </p>
                            @endif
                            <p class="text-[10px] font-semibold text-slate-300 mt-2">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2 transition-opacity">
                        @if(is_null($notification->read_at))
                        <button wire:click="markAsRead('{{ $notification->id }}')" class="p-2 bg-white text-emerald-500 hover:bg-emerald-50 rounded-xl shadow-sm border border-slate-100 transition-all" title="Tandai Dibaca">
                            <x-lucide-check class="w-4 h-4" />
                        </button>
                        @endif
                        <button wire:click="deleteNotification('{{ $notification->id }}')" class="p-2 bg-white text-rose-500 hover:bg-rose-50 rounded-xl shadow-sm border border-slate-100 transition-all" title="Hapus Notifikasi">
                            <x-lucide-trash-2 class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-center py-10">
                    <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-4">
                        <x-lucide-bell class="w-8 h-8 text-slate-300" />
                    </div>
                    <h3 class="text-sm font-bold text-navy-900 mb-1">Belum ada notifikasi</h3>
                    <p class="text-xs font-semibold text-slate-400">Semua pemberitahuan akan muncul di sini.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
