<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page_title', 'Sistem Manajemen Koperasi')</title>

    <link rel="icon" type="image/png" href="/images/logo.png">
    <link rel="apple-touch-icon" href="/images/logo.png">

    <!-- Tailwind CSS v4 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Dark Mode: Terapkan kelas 'dark' sebelum render (cegah flash) -->
    <script>
        (function () {
            const stored = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (stored === 'dark' || (!stored && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    
    <!-- Alpine.js untuk kontrol state Sidebar Mobile & Dropdown -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Font Bricolage Grotesque (judul navbar) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,200..800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Dark Mode: aktifkan variant dark berbasis class -->
    <style type="text/tailwindcss">
        @custom-variant dark (&:where(.dark, .dark *));
    </style>

    <style>
        [x-cloak] { display: none !important; }

        /* Custom Scrollbar Halus untuk Sidebar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.6);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(51, 65, 85, 0.8);
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(71, 85, 105, 1);
        }
    </style>
</head>
<body class="bg-slate-100 dark:bg-slate-950 font-sans antialiased h-full overflow-hidden text-slate-800 dark:text-slate-200" 
      x-data="{ sidebarOpen: false }">

    <!-- CONTAINER UTAMA LAYOUT -->
    <div class="flex h-screen w-screen overflow-hidden">
        
        <!-- 1. BACKDROP OVERLAY FOR MOBILE SIDEBAR -->
        <div x-show="sidebarOpen" 
             x-cloak
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false" 
             class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-xs md:hidden">
        </div>

        <!-- 2. SIDEBAR CONTAINER (WRAPPER KUNCI H-FULL & SLIDE ANIMATION) -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 h-full transform transition-transform duration-300 ease-in-out md:static md:translate-x-0 shrink-0"
             :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            
            <!-- CALL SIDEBAR COMPONENT -->
            @include('components.sidebar')
        </div>

        <!-- 3. KONTEN UTAMA SISI KANAN -->
        <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
            
            <!-- HEADER MOBILE TOGGLE & NAVBAR -->
            <header class="sticky top-0 z-30 flex items-center bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 shrink-0">
                <!-- Tombol Hamburger (Hanya muncul di layar kecil / mobile) -->
                <button @click="sidebarOpen = !sidebarOpen" 
                        type="button" 
                        class="p-4 text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 focus:outline-none md:hidden shrink-0"
                        aria-label="Toggle Sidebar">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>

                <!-- CALL NAVBAR COMPONENT -->
                <div class="flex-1 min-w-0">
                    @include('components.navbar')
                </div>
            </header>

            <!-- KANVAS KONTEN HALAMAN -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto">
                @yield('content')
            </main>

            <!-- CALL FOOTER COMPONENT -->
            <footer class="shrink-0">
                @include('components.footer')
            </footer>
            
        </div>
    </div>

    <!-- Toast Notification (Sukses / Error / Hapus + Urungkan) -->
    @include('components.toast-alert-admin')

    <!-- Stroke Title Animation (judul navbar) -->
    @viteReactRefresh
    @vite(['resources/js/stroke-title-entry.ts'])

</body>
</html>