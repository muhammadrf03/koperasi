@extends('layouts.app')

@section('page_title', 'Dashboard Utama')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center p-2 border border-slate-200 dark:border-slate-700 shrink-0">
                <img src="/images/logo.png" alt="KREATINDO" class="w-full h-full object-contain rounded-lg">
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">PT. PUSAT KREATIF INDONESIA</p>
                <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mt-0.5 flex items-center gap-2">
                    Selamat Datang Kembali, {{ auth()->user()->name ?? 'Super Admin' }} 👋
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Ringkasan performa dan aktivitas inventaris koperasi hari ini.</p>
            </div>
        </div>
        <div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-800/60">
                <i class="fas fa-shield-halved"></i> Role: {{ strtoupper(auth()->user()->role ?? 'SUPERADMIN') }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 [perspective:1200px]">
        <div data-stat-card class="group relative bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-lg hover:border-slate-300 dark:hover:border-white/20 flex items-center gap-4 overflow-hidden [transform-style:preserve-3d]">
            <div data-stat-icon class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-100 dark:border-white/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xl shrink-0 transition-shadow duration-300 group-hover:shadow-[0_0_18px_rgba(99,102,241,0.45)]">
                <i class="fas fa-boxes-stacked"></i>
            </div>
            <div class="min-w-0">
                <p data-stat-label class="flex items-center gap-2 text-[11px] font-semibold text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                    <span data-stat-bar class="w-[3px] h-[18px] rounded-full bg-indigo-500 dark:bg-indigo-400 shadow-[0_0_12px_rgba(99,102,241,0.35)] shrink-0"></span>
                    TOTAL BARANG
                </p>
                <h3 data-stat-num class="text-2xl font-bold text-slate-950 dark:text-white mt-1">{{ $totalBarang }}</h3>
            </div>
        </div>
        <div data-stat-card class="group relative bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-lg hover:border-slate-300 dark:hover:border-white/20 flex items-center gap-4 overflow-hidden [transform-style:preserve-3d]">
            <div data-stat-icon class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-100 dark:border-white/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl shrink-0 transition-shadow duration-300 group-hover:shadow-[0_0_18px_rgba(16,185,129,0.45)]">
                <i class="fas fa-arrow-down"></i>
            </div>
            <div class="min-w-0">
                <p data-stat-label class="flex items-center gap-2 text-[11px] font-semibold text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                    <span data-stat-bar class="w-[3px] h-[18px] rounded-full bg-emerald-500 dark:bg-emerald-400 shadow-[0_0_12px_rgba(16,185,129,0.35)] shrink-0"></span>
                    BARANG MASUK
                </p>
                <h3 data-stat-num class="text-2xl font-bold text-slate-950 dark:text-white mt-1">{{ $barangMasuk }}</h3>
            </div>
        </div>
        <div data-stat-card class="group relative bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-lg hover:border-slate-300 dark:hover:border-white/20 flex items-center gap-4 overflow-hidden [transform-style:preserve-3d]">
            <div data-stat-icon class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-950/60 border border-amber-100 dark:border-white/10 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xl shrink-0 transition-shadow duration-300 group-hover:shadow-[0_0_18px_rgba(245,158,11,0.45)]">
                <i class="fas fa-arrow-up-right-from-square"></i>
            </div>
            <div class="min-w-0">
                <p data-stat-label class="flex items-center gap-2 text-[11px] font-semibold text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                    <span data-stat-bar class="w-[3px] h-[18px] rounded-full bg-amber-500 dark:bg-amber-400 shadow-[0_0_12px_rgba(245,158,11,0.35)] shrink-0"></span>
                    BARANG KELUAR
                </p>
                <h3 data-stat-num class="text-2xl font-bold text-slate-950 dark:text-white mt-1">{{ $barangKeluar }}</h3>
            </div>
        </div>
        <div data-stat-card class="group relative bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm dark:shadow-lg hover:border-slate-300 dark:hover:border-white/20 flex items-center gap-4 overflow-hidden [transform-style:preserve-3d]">
            <div data-stat-icon class="w-12 h-12 rounded-xl bg-purple-50 dark:bg-purple-950/60 border border-purple-100 dark:border-white/10 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xl shrink-0 transition-shadow duration-300 group-hover:shadow-[0_0_18px_rgba(139,92,246,0.45)]">
                <i class="fas fa-users"></i>
            </div>
            <div class="min-w-0">
                <p data-stat-label class="flex items-center gap-2 text-[11px] font-semibold text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                    <span data-stat-bar class="w-[3px] h-[18px] rounded-full bg-purple-500 dark:bg-purple-400 shadow-[0_0_12px_rgba(139,92,246,0.35)] shrink-0"></span>
                    PENGGUNA AKTIF
                </p>
                <h3 data-stat-num class="text-2xl font-bold text-slate-950 dark:text-white mt-1">{{ $penggunaAktif }}</h3>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 pb-3 border-b border-slate-100 dark:border-slate-800">
            <div>
                <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                    <i class="fas fa-chart-bar text-indigo-500 dark:text-indigo-400"></i> Grafik Transaksi Barang In / Out
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Perbandingan volume barang masuk dan barang keluar berdasarkan periode</p>
            </div>
            <div class="flex items-center gap-2 text-xs">
                <div
                    id="dashboard-filters-root"
                    data-chart-data='@json($chartData)'
                    data-current-year="{{ $year }}"
                ></div>
                <div class="flex items-center gap-3 ml-2 text-slate-500 dark:text-slate-300">
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>Masuk</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>Keluar</span>
                </div>
            </div>
        </div>
        <div class="relative w-full h-80">
            <canvas id="barChartUtama"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">

        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs">
            <h3 class="text-xs font-semibold text-slate-900 dark:text-slate-100 mb-0.5">Proporsi Stok per Kategori</h3>
            <p class="text-[11px] text-slate-500 dark:text-slate-400 mb-3">Sebaran jumlah stok fisik barang</p>
            <div class="relative w-full h-52 flex items-center justify-center">
                <canvas id="donutChartKategori"></canvas>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs">
            <h3 class="text-xs font-semibold text-slate-900 dark:text-slate-100 mb-0.5">Distribusi Transaksi per Kategori</h3>
            <p class="text-[11px] text-slate-500 dark:text-slate-400 mb-3">Volume barang masuk &amp; keluar per kategori</p>
            <div class="relative w-full h-52">
                @if(count($kategoriTransaksiLabels) > 0)
                    <canvas id="barChartKategoriTransaksi"></canvas>
                @else
                    <div class="flex flex-col items-center justify-center h-full text-center">
                        <div class="w-12 h-12 bg-slate-100 dark:bg-slate-800 rounded-2xl flex items-center justify-center text-slate-400 dark:text-slate-500 mb-3">
                            <i class="fas fa-chart-pie text-xl"></i>
                        </div>
                        <h4 class="text-xs font-bold text-slate-700 dark:text-slate-200">Data Belum Tersedia</h4>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5 px-6">Belum ada transaksi yang tercatat.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs">
            <h3 class="text-xs font-semibold text-slate-900 dark:text-slate-100 mb-0.5">Kesehatan Stok Inventaris</h3>
            <p class="text-[11px] text-slate-500 dark:text-slate-400 mb-3">Stok Aman vs Stok Kritis (≤5 item)</p>
            <div class="relative w-full h-52 flex items-center justify-center">
                <canvas id="pieChartKesehatanStok"></canvas>
            </div>
        </div>

    </div>

</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 space-y-6">
    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs">
        <h3 class="text-xs font-semibold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-4 flex items-center gap-2">
            <i class="fas fa-bolt text-amber-500"></i> AKSES CEPAT MENU
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('transaksi.index') }}" class="p-4 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 hover:bg-slate-100/80 dark:hover:bg-slate-800 transition flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-white dark:bg-slate-700 shadow-xs flex items-center justify-center text-slate-700 dark:text-slate-200"><i class="fas fa-arrow-right-arrow-left"></i></div>
                <div>
                    <h4 class="text-xs font-semibold text-slate-800 dark:text-slate-100">Catat Transaksi</h4>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Input barang masuk / keluar</p>
                </div>
            </a>
            <a href="{{ route('barang.index') }}" class="p-4 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 hover:bg-slate-100/80 dark:hover:bg-slate-800 transition flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-white dark:bg-slate-700 shadow-xs flex items-center justify-center text-slate-700 dark:text-slate-200"><i class="fas fa-box-archive"></i></div>
                <div>
                    <h4 class="text-xs font-semibold text-slate-800 dark:text-slate-100">Lihat Inventaris</h4>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Kelola stok dan kategori</p>
                </div>
            </a>
            @if(auth()->user()->role === 'superadmin')
                <a href="{{ route('users.index') }}" class="p-4 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 hover:bg-slate-100/80 dark:hover:bg-slate-800 transition flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white dark:bg-slate-700 shadow-xs flex items-center justify-center text-slate-700 dark:text-slate-200"><i class="fas fa-user-gear"></i></div>
                    <div>
                        <h4 class="text-xs font-semibold text-slate-800 dark:text-slate-100">Kelola Admin</h4>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">Atur pengguna sistem</p>
                    </div>
                </a>
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@vite(['resources/js/dashboard-anim.ts', 'resources/js/dashboard-filters-entry.tsx'])

<script>
    document.addEventListener('DOMContentLoaded', function () {

        // 1. Chart Utama (Bar Chart Asli)
        const chartUtama = new Chart(document.getElementById('barChartUtama').getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [
                    {
                        label: 'Masuk',
                        data: @json($monthlyIn),
                        backgroundColor: '#059669',
                        borderRadius: 4,
                        barPercentage: 0.6,
                        categoryPercentage: 0.5
                    },
                    {
                        label: 'Keluar',
                        data: @json($monthlyOut),
                        backgroundColor: '#d97706',
                        borderRadius: 4,
                        barPercentage: 0.6,
                        categoryPercentage: 0.5
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false } },
                    y: { grid: { color: '#f1f5f9' }, beginAtZero: true }
                }
            }
        });

        // Update grafik utama saat filter bulan / tahun diubah
        window.addEventListener('dashboard:filter', function (e) {
            const { labels, inData, outData } = e.detail;
            chartUtama.data.labels = labels;
            chartUtama.data.datasets[0].data = inData;
            chartUtama.data.datasets[1].data = outData;
            chartUtama.update();
        });

        // 2. Donut Chart (Tambahan)
        new Chart(document.getElementById('donutChartKategori').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: @json($kategoriLabels),
                datasets: [{
                    data: @json($kategoriData),
                    backgroundColor: ['#6366f1', '#10b981', '#f59e0b', '#ec4899', '#8b5cf6'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } } }
            }
        });

        // 3. Bar Chart Distribusi Transaksi per Kategori - Hanya dibuat jika data sudah tersedia
        const barChartKategoriTransaksi = document.getElementById('barChartKategoriTransaksi');
        if (barChartKategoriTransaksi) {
            const kategoriTransaksiColors = ['#6366f1', '#10b981', '#f59e0b', '#ec4899', '#8b5cf6', '#0ea5e9', '#f43f5e'];
            const kategoriTransaksiLabels = @json($kategoriTransaksiLabels);
            new Chart(barChartKategoriTransaksi.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: kategoriTransaksiLabels,
                    datasets: [{
                        label: 'Volume',
                        data: @json($kategoriTransaksiData),
                        backgroundColor: kategoriTransaksiLabels.map((_, i) => kategoriTransaksiColors[i % kategoriTransaksiColors.length]),
                        borderRadius: 6,
                        barThickness: 14
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { color: '#f1f5f9' }, beginAtZero: true },
                        y: { grid: { display: false } }
                    }
                }
            });
        }

        // 4. Pie Chart Kesehatan Stok (Tambahan)
        new Chart(document.getElementById('pieChartKesehatanStok').getContext('2d'), {
            type: 'pie',
            data: {
                labels: ['Stok Aman (>5)', 'Stok Kritis (≤5)'],
                datasets: [{
                    data: [{{ $stokAman }}, {{ $stokKritis }}],
                    backgroundColor: ['#10b981', '#f43f5e'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } } }
            }
        });

    });
</script>
@endsection
