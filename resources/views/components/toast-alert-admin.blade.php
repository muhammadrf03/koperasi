{{-- Component: Toast Notification Terpadu (Sukses / Error / Hapus + Urungkan) --}}
<div x-data="{ 
        showSuccess: {{ session('success') ? 'true' : 'false' }},
        showError: {{ session('error') ? 'true' : 'false' }},
        showDeletedItem: {{ session('deleted_item') ? 'true' : 'false' }},
        showDeletedUser: {{ session('deleted_user') ? 'true' : 'false' }}
     }" 
     class="fixed top-6 right-6 z-[99999] flex flex-col space-y-3 max-w-sm w-full pointer-events-none">

    {{-- 1. Pop-up Sukses (Warna Putih) --}}
    @if(session('success'))
        <div x-show="showSuccess" 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-x-20 scale-90"
             x-transition:enter-end="opacity-100 translate-x-0 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-x-0 scale-100"
             x-transition:leave-end="opacity-0 translate-x-20 scale-90"
             x-init="setTimeout(() => showSuccess = false, 4000)"
             class="pointer-events-auto bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border border-slate-200/80 dark:border-slate-700 p-4 rounded-2xl shadow-xl flex items-center justify-between gap-3">
            
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-100 dark:border-emerald-800/60 text-emerald-500 flex items-center justify-center shrink-0">
                    <i class="fas fa-check-circle text-base"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-900 dark:text-slate-100 leading-none mb-1">Berhasil!</h4>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-snug">{{ session('success') }}</p>
                </div>
            </div>

            <button @click="showSuccess = false" class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 transition p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 shrink-0" title="Tutup">
                <i class="fas fa-xmark text-xs"></i>
            </button>
        </div>
    @endif

    {{-- 2. Pop-up Error / Gagal (Warna Putih) --}}
    @if(session('error'))
        <div x-show="showError" 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-x-20 scale-90"
             x-transition:enter-end="opacity-100 translate-x-0 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-x-0 scale-100"
             x-transition:leave-end="opacity-0 translate-x-20 scale-90"
             x-init="setTimeout(() => showError = false, 4000)"
             class="pointer-events-auto bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border border-rose-200/80 dark:border-rose-800/60 p-4 rounded-2xl shadow-xl flex items-center justify-between gap-3">

            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/60 border border-rose-100 dark:border-rose-800/60 text-rose-500 flex items-center justify-center shrink-0">
                    <i class="fas fa-circle-exclamation text-base"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-900 dark:text-slate-100 leading-none mb-1">Gagal!</h4>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-snug">{{ session('error') }}</p>
                </div>
            </div>

            <button @click="showError = false" class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 transition p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 shrink-0" title="Tutup">
                <i class="fas fa-xmark text-xs"></i>
            </button>
        </div>
    @endif

    {{-- 3. Pop-up Barang Dihapus + Urungkan (Warna Hitam) --}}
    @if(session('deleted_item'))
        <div x-show="showDeletedItem" 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-x-20 scale-90"
             x-transition:enter-end="opacity-100 translate-x-0 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-x-0 scale-100"
             x-transition:leave-end="opacity-0 translate-x-20 scale-90"
             x-init="setTimeout(() => showDeletedItem = false, 8000)"
             class="pointer-events-auto bg-slate-950/95 backdrop-blur-md border border-slate-800 p-4 rounded-2xl shadow-xl flex items-center justify-between gap-3">
            
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="w-10 h-10 rounded-xl bg-rose-950/80 border border-rose-800/80 text-rose-400 flex items-center justify-center shrink-0">
                    <i class="fas fa-trash-can text-sm"></i>
                </div>
                <div class="overflow-hidden">
                    <h4 class="text-xs font-bold text-white leading-none mb-1">Barang Dihapus</h4>
                    <p class="text-[11px] text-slate-400 leading-snug truncate">
                        <span class="font-semibold text-slate-200">{{ session('deleted_item')['name'] }}</span> telah dihapus.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                @if(Route::has('barang.restore'))
                    <form action="{{ route('barang.restore', session('deleted_item')['id']) }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-500 text-white text-[10px] font-bold rounded-lg transition flex items-center gap-1 active:scale-95 cursor-pointer whitespace-nowrap shadow-md shadow-indigo-600/20">
                            <i class="fas fa-rotate-left text-[9px]"></i> Urungkan
                        </button>
                    </form>
                @endif

                <button @click="showDeletedItem = false" class="text-slate-500 hover:text-white transition p-1 rounded-lg hover:bg-slate-800" title="Tutup">
                    <i class="fas fa-xmark text-xs"></i>
                </button>
            </div>
        </div>
    @endif

    {{-- 4. Pop-up Akun Dihapus + Urungkan (Warna Hitam) --}}
    @if(session('deleted_user'))
        <div x-show="showDeletedUser" 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-x-20 scale-90"
             x-transition:enter-end="opacity-100 translate-x-0 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-x-0 scale-100"
             x-transition:leave-end="opacity-0 translate-x-20 scale-90"
             x-init="setTimeout(() => showDeletedUser = false, 8000)"
             class="pointer-events-auto bg-slate-950/95 backdrop-blur-md border border-slate-800 p-4 rounded-2xl shadow-xl flex items-center justify-between gap-3">

            <div class="flex items-center gap-3 overflow-hidden">
                <div class="w-10 h-10 rounded-xl bg-rose-950/80 border border-rose-800/80 text-rose-400 flex items-center justify-center shrink-0">
                    <i class="fas fa-trash-can text-sm"></i>
                </div>
                <div class="overflow-hidden">
                    <h4 class="text-xs font-bold text-white leading-none mb-1">Akun Dihapus</h4>
                    <p class="text-[11px] text-slate-400 leading-snug truncate">
                        <span class="font-semibold text-slate-200">{{ session('deleted_user')['name'] }}</span> telah dihapus.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                @if(Route::has('users.restore'))
                    <form action="{{ route('users.restore', session('deleted_user')['id']) }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-500 text-white text-[10px] font-bold rounded-lg transition flex items-center gap-1 active:scale-95 cursor-pointer whitespace-nowrap shadow-md shadow-indigo-600/20">
                            <i class="fas fa-rotate-left text-[9px]"></i> Urungkan
                        </button>
                    </form>
                @endif

                <button @click="showDeletedUser = false" class="text-slate-500 hover:text-white transition p-1 rounded-lg hover:bg-slate-800" title="Tutup">
                    <i class="fas fa-xmark text-xs"></i>
                </button>
            </div>
        </div>
    @endif

</div>
