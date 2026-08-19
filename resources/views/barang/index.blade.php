@extends('layouts.app')

@section('page_title', 'Data Kategori Barang')

@section('content')
<div x-data="{ 
    openModal: false, 
    openDeleteModal: false, 
    deleteUrl: '', 
    deleteItemName: '' 
}" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    {{-- 1. Banner Header Kategori --}}
    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center p-2 border border-slate-200 dark:border-slate-700 shrink-0">
                <img src="/images/logo.png" alt="KREATINDO" class="w-full h-full object-contain rounded-lg">
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">PT. PUSAT KREATIF INDONESIA</p>
                <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mt-0.5 flex items-center gap-2">
                    Kategori: 
                    <span class="text-indigo-600">
                        {{ $activeCategory->name ?? 'Semua Barang' }}
                    </span>
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Ringkasan performa dan kelola ketersediaan stok barang harian.</p>
            </div>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            @if(Route::has('barang.export'))
                <a href="{{ route('barang.export', array_filter(['category' => request('category'), 'search' => request('search')])) }}" class="px-4 py-2 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-xl border border-slate-200 dark:border-slate-700 transition flex items-center gap-2 shadow-2xs">
                    <i class="fas fa-file-excel text-emerald-600 text-sm"></i> Ekspor Excel
                </a>
            @else
                <button type="button" onclick="alert('Fitur Ekspor Excel belum terhubung ke Controller.')" class="px-4 py-2 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-xl border border-slate-200 dark:border-slate-700 transition flex items-center gap-2 shadow-2xs">
                    <i class="fas fa-file-excel text-emerald-600 text-sm"></i> Ekspor Excel
                </button>
            @endif

            <button @click="openModal = true" type="button" class="px-4 py-2 bg-slate-900 dark:bg-indigo-600 hover:bg-slate-800 dark:hover:bg-indigo-500 text-white text-xs font-semibold rounded-xl shadow-xs transition flex items-center gap-2 cursor-pointer">
                <i class="fas fa-plus text-slate-300 text-xs"></i> Tambah Barang
            </button>
        </div>
    </div>

    {{-- 2. Form Filter & Pencarian --}}
    <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs">
        <form action="{{ route('barang.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau deskripsi barang..." class="w-full pl-9 pr-4 py-2 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition">
            </div>

            <div class="flex items-center gap-2">
                <select name="category" class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none cursor-pointer">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->hash }}" {{ request('category') == $cat->hash ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>

                <button type="submit" class="px-5 py-2 bg-slate-900 dark:bg-indigo-600 hover:bg-slate-800 dark:hover:bg-indigo-500 text-white text-xs font-medium rounded-xl transition cursor-pointer">
                    Cari
                </button>

                @if(request('search') || request('category'))
                    <a href="{{ route('barang.index') }}" class="px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 text-xs font-medium rounded-xl transition flex items-center gap-1.5 shrink-0" title="Reset Filter">
                        <i class="fas fa-rotate-left text-xs"></i> Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- 3. Tabel Data Barang --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-100 dark:border-slate-800 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-4 text-center w-12">#</th>
                        <th class="py-3.5 px-4">Nama Barang</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4 text-center">Stok</th>
                        <th class="py-3.5 px-4">Harga Satuan</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs text-slate-700 dark:text-slate-200">
                    @forelse($barangs as $index => $item)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                            <td class="py-3.5 px-4 text-center text-slate-400 dark:text-slate-500 font-medium">{{ $barangs->firstItem() + $index }}</td>
                            <td class="py-3.5 px-4 font-semibold text-slate-900 dark:text-slate-100">{{ $item->name }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-lg text-[11px] font-medium border border-slate-200 dark:border-slate-700">
                                    {{ ucfirst($item->category->name ?? 'Uncategorized') }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold {{ $item->stock <= 5 ? 'text-red-500' : 'text-slate-800 dark:text-slate-100' }}">
                                {{ $item->stock }} {{ $item->unit }}
                            </td>
                            <td class="py-3.5 px-4 font-medium">Rp {{ number_format($item->price ?? 0, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button type="button"
                                            @click="openDeleteModal = true; deleteUrl = '{{ route('barang.destroy', $item->hash) }}'; deleteItemName = '{{ addslashes($item->name) }}'"
                                            class="p-1.5 hover:bg-red-50 text-red-600 rounded-lg transition cursor-pointer" title="Hapus Barang">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 bg-slate-100 dark:bg-slate-800 rounded-2xl flex items-center justify-center text-slate-400 dark:text-slate-500 mb-3">
                                        <i class="fas fa-box-open text-xl"></i>
                                    </div>
                                    <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100">Belum Ada Data Barang</h4>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Tidak ada barang yang terdaftar.</p>
                                </div>
                            </td>
                        </tr>
                    @endempty
                </tbody>
            </table>
        </div>
        @if($barangs->hasPages())
            <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                {{ $barangs->links() }}
            </div>
        @endif
    </div>

    {{-- 4. MODAL TAMBAH BARANG --}}
    <div x-show="openModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="openModal" class="fixed inset-0 bg-slate-900/60 transition-opacity" @click="openModal = false"></div>

        <div class="min-h-full flex items-center justify-center p-4 sm:p-6">
            <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-900 text-left shadow-2xl transition-all w-full max-w-lg border border-slate-200 dark:border-slate-700">
                
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-white dark:bg-slate-900">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg flex items-center justify-center p-1.5">
                            <img src="/images/logo.png" alt="KREATINDO" class="w-full h-full object-contain opacity-90">
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-800 dark:text-slate-100 tracking-tight">Tambah Barang Baru</h3>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">Input informasi detail inventaris koperasi</p>
                        </div>
                    </div>
                    <button @click="openModal = false" type="button" class="w-8 h-8 rounded-lg text-slate-400 dark:text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-600 dark:hover:text-slate-300 transition-all flex items-center justify-center">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>

                <form action="{{ route('barang.store') }}" method="POST" class="p-6">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-1.5">Nama Barang</label>
                            <input type="text" name="name" required placeholder="Masukkan nama barang..." 
                                   class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl text-xs placeholder:text-slate-400 dark:text-slate-500 text-slate-700 dark:text-slate-200 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-1.5">Kategori</label>
                                <div class="relative">
                                    <select name="category_id" required 
                                            class="w-full appearance-none px-3.5 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl text-xs text-slate-700 dark:text-slate-200 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none cursor-pointer">
                                        <option value="">Pilih Kategori</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400 dark:text-slate-500">
                                        <i class="fas fa-chevron-down text-[10px]"></i>
                                    </div>
                                </div>
                            </div>

                            {{-- PERUBAHAN: Input Satuan menjadi Dropdown --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-1.5">Satuan</label>
                                <div class="relative">
                                    <select name="unit" required 
                                            class="w-full appearance-none px-3.5 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl text-xs text-slate-700 dark:text-slate-200 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none cursor-pointer">
                                        <option value="">Pilih Satuan</option>
                                        <option value="Pcs">Pcs</option>
                                        <option value="Kg">Kg</option>
                                        <option value="Dus">Dus</option>
                                        <option value="Pack">Pack</option>
                                        <option value="Liter">Liter</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400 dark:text-slate-500">
                                        <i class="fas fa-chevron-down text-[10px]"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-1.5">Stok</label>
                                <input type="number" min="0" name="stock" required placeholder="0" 
                                       class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl text-xs text-slate-700 dark:text-slate-200 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-1.5">Harga Satuan</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 dark:text-slate-500 text-xs font-semibold">Rp</span>
                                    <input type="number" min="0" name="price" required placeholder="15000" 
                                           class="w-full pl-10 pr-3.5 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl text-xs text-slate-700 dark:text-slate-200 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end space-x-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" @click="openModal = false" 
                                class="px-4 py-2 text-xs font-bold text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-5 py-2 bg-slate-900 dark:bg-indigo-600 hover:bg-slate-800 dark:hover:bg-indigo-500 text-white text-xs font-bold rounded-xl shadow-xs transition-all flex items-center gap-2 cursor-pointer">
                            <i class="fas fa-plus text-[10px]"></i>
                            Simpan Barang
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- 6. MODAL KONFIRMASI HAPUS BARANG --}}
    <div x-show="openDeleteModal"
         x-cloak
         class="fixed inset-0 z-[9999] overflow-y-auto"
         role="dialog"
         aria-modal="true">

        <div x-show="openDeleteModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="openDeleteModal = false"
             class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm"></div>

        <div class="min-h-full flex items-center justify-center p-4 text-center">
            <div x-show="openDeleteModal"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                 class="relative overflow-hidden rounded-3xl bg-white dark:bg-slate-900 text-center p-6 shadow-2xl w-full max-w-sm border border-slate-100 dark:border-slate-800">

                <div class="w-12 h-12 bg-rose-500/20 text-rose-500 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-rose-500/30">
                    <i class="fas fa-trash-can text-sm"></i>
                </div>

                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Hapus Barang?</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 mb-6">
                    Apakah Anda yakin ingin menghapus barang <span class="font-bold text-slate-700 dark:text-slate-200" x-text="deleteItemName"></span>? Data stok dan transaksi terkait akan ikut terhapus.
                </p>

                <form :action="deleteUrl" method="POST" class="flex items-center justify-center gap-3">
                    @csrf
                    @method('DELETE')
                    <button @click="openDeleteModal = false" type="button" class="w-full py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-semibold text-xs rounded-xl transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="w-full py-2.5 bg-rose-600 hover:bg-rose-700 active:scale-95 text-white font-semibold text-xs rounded-xl shadow-md transition cursor-pointer">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection