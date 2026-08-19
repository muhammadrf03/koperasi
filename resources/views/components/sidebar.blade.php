<aside class="w-64 h-full bg-slate-950 text-slate-300 flex flex-col justify-between shrink-0 border-r border-slate-800/80 relative z-20 shadow-2xl">
    
    <!-- WRAPPER UTAMA MENU: Ditambahkan flex-1 & overflow-y-auto agar mengisi ruang kosong dan konsisten full height -->
    <div class="flex-1 overflow-y-auto custom-scrollbar">
        
        <!-- 1. HEADER SIDEBAR: Logo & Perusahaan -->
        <div class="p-5 bg-slate-900/80 backdrop-blur-md flex items-center gap-3 border-b border-slate-800/80 sticky top-0 z-10">
            <!-- Frame Logo Kreatindo -->
            <div class="w-11 h-11 bg-white/10 backdrop-blur-md p-2 rounded-2xl flex items-center justify-center shrink-0 border border-white/10 shadow-lg">
                <img src="/images/logo.png" alt="KOPERASI company logo, stylized emblem used as site branding placed in a rounded frame in the sidebar next to the text KOPERASI and PT. PUSAT KREATIF INDONESIA, neutral professional tone" class="w-full h-full object-contain rounded-lg">
            </div>

            <!-- Nama Perusahaan -->
            <div class="overflow-hidden">
                <h1 class="text-white font-extrabold text-sm leading-tight tracking-wider truncate">KOPERASI</h1>
                <p class="text-[9px] text-blue-400 font-semibold uppercase tracking-widest truncate">PT. PUSAT KREATIF INDONESIA</p>
            </div>
        </div>

        <!-- 2. USER PROFILE INFO BOX -->
        <div class="m-4 p-3 bg-gradient-to-r from-slate-900/90 to-indigo-950/40 rounded-2xl border border-slate-800/80 shadow-inner">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center font-bold text-white shadow-md text-xs shrink-0 border border-white/10">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name }}</p>
                    <span class="inline-flex items-center gap-1 text-[9px] px-2 py-0.5 mt-0.5 font-extrabold uppercase rounded-md bg-amber-400/10 text-amber-300 border border-amber-400/20 tracking-wider">
                        <i class="fas fa-shield-halved text-[8px]"></i> {{ Auth::user()->role }}
                    </span>
                </div>
            </div>
        </div>

        <!-- 3. NAVIGASI MENU -->
        <nav class="px-4 py-2 space-y-1.5 pb-6">
            <p class="px-3 text-[10px] font-extrabold text-slate-500 uppercase tracking-widest mb-2">Menu Utama</p>

            <!-- Menu Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 group {{ Request::is('dashboard') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-600/30 font-bold' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                <i class="fas fa-chart-pie w-5 text-center text-sm {{ Request::is('dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-blue-400' }} transition-colors"></i>
                <span>Dashboard</span>
            </a>

            <!-- Menu Kategori Barang (Dropdown) -->
            @php
                $sidebarCategories = \App\Models\Category::orderBy('name')->get();
                $categoryIcons = [
                    'Inventaris' => ['icon' => 'fa-boxes-stacked', 'color' => 'amber'],
                    'Buah'       => ['icon' => 'fa-apple-whole', 'color' => 'amber'],
                    'Sayur'      => ['icon' => 'fa-carrot', 'color' => 'emerald'],
                    'Minuman'    => ['icon' => 'fa-glass-water', 'color' => 'blue'],
                ];
                $defaultIcons = [
                    ['icon' => 'fa-cube', 'color' => 'violet'],
                    ['icon' => 'fa-tag', 'color' => 'rose'],
                    ['icon' => 'fa-box', 'color' => 'cyan'],
                    ['icon' => 'fa-layer-group', 'color' => 'orange'],
                ];
            @endphp
            <details class="group/dropdown" {{ Request::is('barang*') ? 'open' : '' }}>
                <summary class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-400 hover:bg-slate-900 hover:text-white transition-all duration-200 cursor-pointer list-none select-none">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-box w-5 text-center text-sm text-slate-400 group-hover/dropdown:text-indigo-400 transition-colors"></i>
                        <span>Kategori Barang</span>
                    </div>
                    <i class="fas fa-chevron-down text-[10px] text-slate-500 group-open/dropdown:rotate-180 transition-transform duration-200"></i>
                </summary>

                <div class="pl-9 pr-2 py-1.5 mt-1 space-y-1 border-l-2 border-slate-800/80 ml-5">
                    <!-- Semua Barang -->
                    <a href="{{ route('barang.index') }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs font-medium {{ Request::is('barang') ? 'text-amber-300 font-bold bg-amber-400/10' : 'text-slate-400 hover:text-white hover:bg-slate-900' }} transition-all">
                        <i class="fas fa-globe text-[10px] text-amber-400"></i> Semua Barang
                    </a>
                    @foreach($sidebarCategories as $cat)
                        @php
                            $ci = $categoryIcons[$cat->name] ?? $defaultIcons[$loop->index % count($defaultIcons)];
                            $color = $ci['color'];
                            $isActive = request()->route('category') == $cat->hash || request('category') == $cat->hash;
                        @endphp
                        <a href="{{ route('barang.show', $cat->hash) }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs font-medium {{ $isActive ? "text-{$color}-300 font-bold bg-{$color}-400/10" : 'text-slate-400 hover:text-white hover:bg-slate-900' }} transition-all">
                            <i class="fas {{ $ci['icon'] }} text-[10px] text-{{ $color }}-400"></i> {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </details>

            <!-- Menu Transaksi (In/Out) -->
            <a href="{{ route('transaksi.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 group {{ Request::is('transaksi*') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-600/30 font-bold' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                <i class="fas fa-right-left w-5 text-center text-sm {{ Request::is('transaksi*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-400' }} transition-colors"></i>
                <span>Transaksi In/Out</span>
            </a>

            <!-- Menu Khusus Superadmin -->
            @if(Auth::user()->role === 'superadmin')
                <a href="{{ route('users.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 group {{ Request::is('admin/users*') ? 'bg-gradient-to-r from-purple-600 to-indigo-600 text-white shadow-lg shadow-purple-600/30 font-bold' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                    <i class="fas fa-users-gear w-5 text-center text-sm {{ Request::is('admin/users*') ? 'text-white' : 'text-slate-400 group-hover:text-purple-400' }} transition-colors"></i>
                    <span>Manajemen Admin</span>
                </a>
            @endif
        </nav>
    </div>

    <!-- 4. FOOTER SIDEBAR: LOGOUT BUTTON (Ditambahkan shrink-0) -->
    <div class="p-4 border-t border-slate-800/80 bg-slate-950/80 shrink-0">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-red-500/10 hover:bg-red-600 text-red-400 hover:text-white rounded-xl text-xs font-bold transition-all duration-200 active:scale-95 shadow-xs cursor-pointer border border-red-500/20 hover:border-red-600">
                <i class="fas fa-arrow-right-from-bracket"></i> Keluar Aplikasi
            </button>
        </form>
    </div>
</aside>