@extends(file_exists(resource_path('views/layouts/app.blade.php')) ? 'layouts.app' : 'dashboard')

@section('page_title', 'Transaksi In / Out')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

<!-- Main Container dengan State AlpineJS -->
<div x-data="{ 
    openModal: {{ $errors->any() ? 'true' : 'false' }},
    openDetailModal: false,
    openEditModal: false,
    openDeleteModal: false,
    deleteUrl: '',
    detailData: {},
    editData: {
        id: '',
        item_id: '',
        type: 'in',
        quantity: 1,
        transaction_date: '',
        notes: '',
        receipt_image: ''
    }
}">

    <div class="space-y-6">
        
        <!-- 1. HERO BRANDING CARD (SESUAI TEMA BARU) -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <!-- Logo Box -->
                <div class="w-12 h-12 bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-800 rounded-2xl flex items-center justify-center p-2 shrink-0">
                    <img src="/images/logo.png" alt="KREATINDO" class="w-full h-full object-contain">
                </div>
                <div>
                    <span class="text-[11px] uppercase tracking-wider text-slate-400 dark:text-slate-500 font-bold">PT. PUSAT KREATIF INDONESIA</span>
                    <h2 class="text-xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mt-0.5">
                        Riwayat Transaksi
                    </h2>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Pencatatan mutasi barang masuk (In) dan barang keluar (Out).</p>
                </div>
            </div>

            <!-- Action Button -->
            <button @click="openModal = true" 
                    type="button"
                    class="px-5 py-2.5 bg-slate-900 dark:bg-indigo-600 hover:bg-slate-800 dark:hover:bg-indigo-500 active:scale-95 text-white text-xs font-bold rounded-xl shadow-xs transition duration-200 flex items-center justify-center gap-2 shrink-0 cursor-pointer">
                <i class="fas fa-plus text-xs"></i> Tambah Transaksi
            </button>
        </div>

        <!-- TABEL DATA TRANSAKSI -->
<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
            <thead class="bg-slate-50 dark:bg-slate-800/50 text-slate-400 dark:text-slate-500 uppercase tracking-wider font-extrabold text-[10px] border-b border-slate-100 dark:border-slate-800">
                <tr>
                    <th class="py-4 px-4 text-center w-12">No</th>
                    <th class="py-4 px-6">Tanggal</th>
                    <th class="py-4 px-4">Penginput</th>
                    <th class="py-4 px-4">Barang</th>
                    <th class="py-4 px-4">Tipe Transaksi</th>
                    <th class="py-4 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 font-medium">
                @forelse($transactions as $index => $trx)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-colors">
                        
                        <!-- Nomor Urut (Mendukung Pagination) -->
                        <td class="py-4 px-4 text-center font-bold text-slate-400 dark:text-slate-500 text-[11px] whitespace-nowrap">
                            {{ $transactions->firstItem() + $index }}
                        </td>

                        <!-- Tanggal Realtime / Created At -->
                        <td class="py-4 px-6 whitespace-nowrap">
                            <div class="font-bold text-slate-900 dark:text-slate-100">
                                {{ \Carbon\Carbon::parse($trx->transaction_date)->setTimezone('Asia/Jakarta')->translatedFormat('d M Y') }}
                            </div>
                            <div class="text-[10px] text-slate-400 dark:text-slate-500 font-normal">
                                {{ \Carbon\Carbon::parse($trx->created_at)->setTimezone('Asia/Jakarta')->format('H:i') }} WIB
                            </div>
                        </td>

                        <!-- Penginput (Avatar Inisial Real Admin) -->
                        <td class="py-4 px-4 whitespace-nowrap">
                            @if($trx->user)
                                @php
                                    $words = explode(' ', trim($trx->user->name));
                                    $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : substr($words[0], 1, 1)));
                                @endphp
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-lg bg-slate-900 dark:bg-slate-700 text-white flex items-center justify-center text-[10px] font-extrabold tracking-wider shrink-0 shadow-xs">
                                        {{ $initials }}
                                    </div>
                                    <span class="text-slate-800 dark:text-slate-100 font-semibold text-xs">
                                        {{ $trx->user->name }}
                                    </span>
                                </div>
                            @else
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center text-xs font-bold shrink-0">?</div>
                                    <span class="text-slate-400 dark:text-slate-500 italic font-normal text-xs">User Terhapus</span>
                                </div>
                            @endif
                        </td>

                        <!-- Nama Barang -->
                        <td class="py-4 px-4 font-bold text-slate-900 dark:text-slate-100 whitespace-nowrap">
                            <span class="px-3 py-1 rounded-xl bg-slate-100 dark:bg-slate-800/70 text-slate-800 dark:text-slate-100 text-[11px] font-semibold">
                                {{ $trx->item->name ?? '-' }}
                            </span>
                        </td>

                        <!-- Tipe Transaksi -->
                        <td class="py-4 px-4 whitespace-nowrap">
                            @if($trx->type === 'in')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-600 font-bold text-[10px] uppercase">
                                    <i class="fas fa-arrow-down text-[9px]"></i> MASUK
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-rose-50 text-rose-600 font-bold text-[10px] uppercase">
                                    <i class="fas fa-arrow-up text-[9px]"></i> KELUAR
                                </span>
                            @endif
                        </td>

                        <!-- Tombol Detail -->
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            <button type="button" 
                                    @click="
                                        detailData = {
                                            id: '{{ $trx->id }}',
                                            item_id: '{{ $trx->item_id }}',
                                            date: '{{ \Carbon\Carbon::parse($trx->transaction_date)->setTimezone('Asia/Jakarta')->translatedFormat('d F Y') }}',
                                            time: '{{ \Carbon\Carbon::parse($trx->created_at)->setTimezone('Asia/Jakarta')->format('H:i') }} WIB',
                                            user_name: '{{ $trx->user->name ?? 'User Terhapus' }}',
                                            user_id: '{{ $trx->user->id ?? '-' }}',
                                            item_name: {{ json_encode($trx->item->name ?? '-') }},
                                            type: '{{ $trx->type }}',
                                            quantity: '{{ $trx->quantity }}',
                                            unit: '{{ $trx->item->unit ?? '' }}',
                                            notes: {{ json_encode($trx->notes ?? '-') }},
                                            receipt_image: '{{ $trx->receipt_image ? asset('storage/' . $trx->receipt_image) : '' }}',
                                            is_pdf: {{ strtolower(pathinfo($trx->receipt_image, PATHINFO_EXTENSION)) === 'pdf' ? 'true' : 'false' }},
                                            raw_date: '{{ \Carbon\Carbon::parse($trx->transaction_date)->setTimezone('Asia/Jakarta')->format('Y-m-d') }}',
                                            delete_url: '{{ route('transaksi.destroy', $trx->id) }}'
                                        };
                                        openDetailModal = true;
                                    "
                                    class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg font-semibold text-xs transition duration-150 flex items-center justify-center gap-1.5 mx-auto cursor-pointer">
                                <i class="fas fa-eye text-[11px]"></i>
                                <span>Detail</span>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400 dark:text-slate-500">
                            <p class="text-xs font-bold text-slate-700 dark:text-slate-200">Belum Ada Riwayat Transaksi</p>
                            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5">Catatan barang masuk dan keluar akan tampil di sini.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($transactions->hasPages())
        <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
            {{ $transactions->links() }}
        </div>
    @endif
</div>

        <!-- MODAL DETAIL TRANSAKSI -->
        <div x-show="openDetailModal" 
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-xs p-4 overflow-y-auto"
             style="display: none;">
            
            <div @click.away="openDetailModal = false" 
                 class="bg-white dark:bg-slate-900 rounded-2xl max-w-lg w-full shadow-xl overflow-hidden border border-slate-100 dark:border-slate-800 transform transition-all my-8">
                
                <!-- Modal Header -->
                <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50 dark:bg-slate-800/50">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100">Detail Transaksi</h3>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500">Informasi lengkap riwayat transaksi</p>
                    </div>
                    <button @click="openDetailModal = false" class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 transition cursor-pointer">
                        <i class="fas fa-xmark text-sm"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-4 text-xs">
                    <!-- Tipe Badge -->
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-800">
                        <span class="font-bold text-slate-600 dark:text-slate-300">Tipe Mutasi:</span>
                        <template x-if="detailData.type === 'in'">
                            <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-600 font-bold text-[10px] uppercase">
                                Barang Masuk (In)
                            </span>
                        </template>
                        <template x-if="detailData.type === 'out'">
                            <span class="px-2.5 py-1 rounded-lg bg-rose-50 text-rose-600 font-bold text-[10px] uppercase">
                                Barang Keluar (Out)
                            </span>
                        </template>
                    </div>

                    <!-- Data Grid -->
                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3 bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-100 dark:border-slate-800">
                            <span class="text-slate-400 dark:text-slate-500 text-[10px] font-bold uppercase block mb-0.5">Tanggal & Waktu</span>
                            <!-- Menampilkan tanggal, misal: 05 Agustus 2026 -->
    <p class="font-bold text-slate-900 dark:text-slate-100" x-text="detailData.date"></p>
    <!-- Menampilkan waktu realtime, misal: 09:26 WIB -->
    <p class="text-[10px] text-slate-400 dark:text-slate-500 font-semibold mt-0.5" x-text="detailData.time"></p>
                        </div>
                        <div class="p-3 bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-100 dark:border-slate-800">
                            <span class="text-slate-400 dark:text-slate-500 text-[10px] font-bold uppercase block mb-0.5">Petugas Penginput</span>
                            <p class="font-bold text-slate-900 dark:text-slate-100" x-text="detailData.user_name"></p>
                        </div>
                        <div class="p-3 bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-100 dark:border-slate-800">
                            <span class="text-slate-400 dark:text-slate-500 text-[10px] font-bold uppercase block mb-0.5">Nama Barang</span>
                            <p class="font-bold text-slate-900 dark:text-slate-100" x-text="detailData.item_name"></p>
                        </div>
                        <div class="p-3 bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-100 dark:border-slate-800">
                            <span class="text-slate-400 dark:text-slate-500 text-[10px] font-bold uppercase block mb-0.5">Jumlah Mutasi</span>
                            <p class="font-extrabold text-slate-900 dark:text-slate-100 text-sm" x-text="detailData.quantity + ' ' + detailData.unit"></p>
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div>
                        <span class="text-slate-400 dark:text-slate-500 text-[10px] font-bold uppercase block mb-1">Catatan / Keterangan</span>
                        <p class="p-3 bg-slate-50 dark:bg-slate-800 rounded-xl text-slate-700 dark:text-slate-200 font-medium border border-slate-100 dark:border-slate-800" x-text="detailData.notes || 'Tidak ada catatan'"></p>
                    </div>

                    <!-- Lampiran Bukti -->
                    <div>
                        <span class="text-slate-400 dark:text-slate-500 text-[10px] font-bold uppercase block mb-1">Bukti Resi / Lampiran</span>
                        <template x-if="detailData.receipt_image">
                            <a :href="detailData.receipt_image" target="_blank" 
                               class="w-full py-2.5 px-3 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 rounded-xl font-semibold flex items-center justify-between transition text-xs">
                                <span class="flex items-center gap-2">
                                    <i :class="detailData.is_pdf ? 'fas fa-file-pdf text-rose-500' : 'fas fa-image text-blue-500'"></i>
                                    <span x-text="detailData.is_pdf ? 'Lihat Dokumen PDF' : 'Lihat Foto Bukti'"></span>
                                </span>
                                <i class="fas fa-external-link-alt text-[10px]"></i>
                            </a>
                        </template>
                        <template x-if="!detailData.receipt_image">
                            <p class="text-slate-400 dark:text-slate-500 italic text-[11px]">Tidak ada lampiran bukti</p>
                        </template>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 flex items-center justify-between gap-3">
                    <button @click="openDetailModal = false" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-500 text-slate-700 dark:text-slate-200 font-bold rounded-xl text-xs transition cursor-pointer">
                        Tutup
                    </button>
                    <div class="flex items-center gap-2">
                        <button type="button"
                                @click="
                                    deleteUrl = detailData.delete_url;
                                    openDetailModal = false;
                                    openDeleteModal = true;
                                "
                                class="px-3.5 py-2 bg-rose-500 hover:bg-rose-600 text-white rounded-xl font-bold text-xs transition flex items-center gap-1.5 cursor-pointer">
                            <i class="fas fa-trash-can text-[11px]"></i>
                            <span>Hapus</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. MODAL TAMBAH TRANSAKSI -->
        <div x-show="openModal" 
             x-cloak
             class="fixed inset-0 z-[9999] overflow-y-auto"
             role="dialog" aria-modal="true">
            
            <div x-show="openModal"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="openModal = false"
                 class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity"></div>

            <div class="min-h-full flex items-center justify-center p-4 text-center">
                <div x-show="openModal"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-900 text-left shadow-xl transition-all w-full max-w-lg border border-slate-100 dark:border-slate-800 my-8">
                    
                    <!-- Modal Header -->
                    <div class="bg-slate-900 p-5 text-white flex items-center justify-between border-b border-white/10">
                        <div>
                            <h3 class="text-sm font-extrabold">Tambah Transaksi Baru</h3>
                            <p class="text-[10px] text-slate-300 dark:text-slate-400">Catat arus barang masuk atau keluar</p>
                        </div>
                        <button @click="openModal = false" type="button" class="text-slate-400 dark:text-slate-500 hover:text-white transition cursor-pointer">
                            <i class="fas fa-xmark text-sm"></i>
                        </button>
                    </div>

                    <!-- Form Input -->
                    <form action="{{ route('transaksi.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4 max-h-[75vh] overflow-y-auto text-xs">
                        @csrf

                        <!-- Pilih Barang -->
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-1.5 text-[10px]">Barang</label>
                            <select name="item_id" required
                                    class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 outline-none focus:ring-2 focus:ring-slate-900 focus:bg-white dark:focus:bg-slate-700 transition @error('item_id') border-rose-500 @enderror">
                                <option value="" disabled {{ old('item_id') ? '' : 'selected' }}>-- Pilih Barang --</option>
                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }} (Stok: {{ $item->stock }} {{ $item->unit }})
                                    </option>
                                @endforeach
                            </select>
                            @error('item_id')
                                <p class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jenis Transaksi -->
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-2 text-[10px]">Jenis Transaksi</label>
                            <div class="grid grid-cols-2 gap-3" x-data="{ selectedType: '{{ old('type', 'in') }}' }">
                                <label class="flex items-center justify-center gap-2 p-3 rounded-xl border cursor-pointer transition-all"
                                       :class="selectedType === 'in' ? 'bg-emerald-50 border-emerald-500 text-emerald-700 font-bold' : 'bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300'">
                                    <input type="radio" name="type" value="in" x-model="selectedType" class="sr-only">
                                    <i class="fas fa-arrow-down text-emerald-600"></i>
                                    <span class="uppercase">Barang Masuk (IN)</span>
                                </label>

                                <label class="flex items-center justify-center gap-2 p-3 rounded-xl border cursor-pointer transition-all"
                                       :class="selectedType === 'out' ? 'bg-rose-50 border-rose-500 text-rose-700 font-bold' : 'bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300'">
                                    <input type="radio" name="type" value="out" x-model="selectedType" class="sr-only">
                                    <i class="fas fa-arrow-up text-rose-600"></i>
                                    <span class="uppercase">Barang Keluar (OUT)</span>
                                </label>
                            </div>
                            @error('type')
                                <p class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jumlah -->
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-1.5 text-[10px]">Jumlah</label>
                            <input type="number" name="quantity" min="1" value="{{ old('quantity', 1) }}" required
                                   class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 outline-none focus:ring-2 focus:ring-slate-900 focus:bg-white dark:focus:bg-slate-700 transition @error('quantity') border-rose-500 @enderror">
                            @error('quantity')
                                <p class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Transaksi -->
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-1.5 text-[10px]">Tanggal Transaksi</label>
                            <input type="date" name="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}" required 
                                   class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 outline-none focus:ring-2 focus:ring-slate-900 focus:bg-white dark:focus:bg-slate-700 transition @error('transaction_date') border-rose-500 @enderror">
                            @error('transaction_date')
                                <p class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Catatan -->
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-1.5 text-[10px]">Catatan / Keterangan</label>
                            <textarea name="notes" rows="3" placeholder="Rincian barang, supplier, dll..."
                                      class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 outline-none focus:ring-2 focus:ring-slate-900 focus:bg-white dark:focus:bg-slate-700 transition @error('notes') border-rose-500 @enderror">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Upload Bukti Resi -->
                        <div>
                            <label for="receipt_image" class="block font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-1.5 text-[10px]">
                                Bukti Resi / Nota <span class="text-rose-500">*</span>
                            </label>
                            <input type="file" id="receipt_image" name="receipt_image" accept="image/jpeg,image/png,image/jpg,application/pdf" required 
                                   class="w-full text-xs text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 dark:file:bg-slate-800 file:text-slate-700 dark:file:text-slate-200 hover:file:bg-slate-200 dark:hover:file:bg-slate-600 transition cursor-pointer @error('receipt_image') border border-rose-500 rounded-xl p-2 @enderror">
                            
                            <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">* Format: JPG, PNG, atau PDF (Maks. 5MB)</p>

                            @error('receipt_image')
                                <p class="text-rose-500 text-[10px] font-bold mt-1 flex items-center gap-1">
                                    <i class="fas fa-circle-exclamation"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button type="submit" 
                                    class="w-full py-3 bg-slate-900 dark:bg-indigo-600 hover:bg-slate-800 dark:hover:bg-indigo-500 text-white font-bold rounded-xl text-xs shadow-xs transition cursor-pointer">
                                Simpan Transaksi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- 6. MODAL KONFIRMASI HAPUS (TEMA BARU) -->
        <div x-show="openDeleteModal" 
             x-cloak 
             class="fixed inset-0 z-[9999] overflow-y-auto" 
             role="dialog" 
             aria-modal="true">
            
            <!-- Backdrop -->
            <div x-show="openDeleteModal" 
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="openDeleteModal = false" 
                 class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity"></div>

            <div class="min-h-full flex items-center justify-center p-4 text-center">
                <div x-show="openDeleteModal" 
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-900 text-center p-6 shadow-xl w-full max-w-sm border border-slate-100 dark:border-slate-800 my-8">
                    
                    <!-- Icon Warning -->
                    <div class="w-12 h-12 bg-rose-50 text-rose-500 rounded-xl flex items-center justify-center mx-auto mb-3.5 text-lg">
                        <i class="fas fa-trash-can"></i>
                    </div>

                    <!-- Text Info -->
                    <h3 class="text-sm font-extrabold text-slate-900 dark:text-slate-100">Hapus Transaksi?</h3>
                    <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1 mb-5 leading-relaxed">
                        Tindakan ini tidak dapat dibatalkan dan stok barang akan otomatis menyesuaikan kembali.
                    </p>

                    <!-- Form Delete -->
                    <form :action="deleteUrl" method="POST" class="flex items-center justify-center gap-2.5">
                        @csrf
                        @method('DELETE')
                        
                        <button @click="openDeleteModal = false" 
                                type="button" 
                                class="w-full py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-bold text-xs rounded-xl transition cursor-pointer">
                            Batal
                        </button>
                        
                        <button type="submit" 
                                class="w-full py-2.5 bg-rose-600 hover:bg-rose-700 active:scale-95 text-white font-bold text-xs rounded-xl shadow-xs transition cursor-pointer">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection