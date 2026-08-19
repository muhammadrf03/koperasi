<header class="h-16 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-gray-100 dark:border-slate-800 px-6 sm:px-8 flex justify-between items-center shrink-0 shadow-sm sticky top-0 z-10 transition-all">
    <!-- 1. JUDUL HALAMAN DINAMIS + AKSEN BRANDING -->
    <div class="flex items-center gap-3">
        <!-- Ikon Badge Gradien Tema Kreatindo -->
        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 flex items-center justify-center shadow-md shadow-blue-500/30 shrink-0">
            <i class="fas fa-lines-leaning text-white text-sm"></i>
        </div>

        <!-- Judul Halaman Dinamis -->
        <h1 class="text-gray-800 dark:text-slate-100 font-extrabold text-[22px] tracking-tight flex items-center gap-2"
            style="font-family:'Bricolage Grotesque', system-ui, sans-serif">
            <span id="stroke-title-root"
                  data-title="{{ $__env->yieldContent('page_title', 'Manajemen Koperasi') }}">{{ $__env->yieldContent('page_title', 'Manajemen Koperasi') }}</span>
        </h1>
    </div>
    
    <!-- 2. STATUS SISTEM & INDIKATOR AKTIF -->
    <div class="flex items-center gap-3 sm:gap-6">
        <!-- Tanggal / Jam Realtime (Opsional Visual) -->
        <div class="hidden md:flex items-center gap-2 text-xs text-gray-500 dark:text-slate-400 bg-gray-50/80 dark:bg-slate-800/80 px-3 py-1.5 rounded-xl border border-gray-100 dark:border-slate-700 font-medium">
            <i class="fas fa-calendar-day text-blue-600 dark:text-blue-400"></i>
            <span>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
        </div>

        <!-- Tombol Toggle Dark Mode -->
        <button type="button" 
                x-data="{ isDark: document.documentElement.classList.contains('dark') }"
                @click="isDark = !isDark; document.documentElement.classList.toggle('dark'); localStorage.setItem('theme', isDark ? 'dark' : 'light')"
                :title="isDark ? 'Mode Terang' : 'Mode Gelap'"
                class="relative w-10 h-10 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/80 dark:bg-slate-800 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors flex items-center justify-center text-gray-600 dark:text-amber-300 shadow-xs">
            <i class="fas fa-sun hidden dark:inline"></i>
            <i class="fas fa-moon dark:hidden"></i>
        </button>

        <!-- Status Terproteksi dengan Pulsing Online Badge -->
        <div class="flex items-center gap-2.5 text-xs text-gray-600 dark:text-slate-300 font-semibold bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-slate-800 dark:to-slate-800 px-3.5 py-1.5 rounded-xl border border-blue-100 dark:border-slate-700 shadow-xs">
            <!-- Green Pulsing Dot -->
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>

            <div class="flex items-center gap-1.5 text-gray-700 dark:text-slate-200">
                <i class="fas fa-shield-halved text-blue-600 dark:text-blue-400 text-xs"></i>
                <span class="hidden sm:inline">Sistem Terproteksi</span>
            </div>
        </div>
    </div>
</header>