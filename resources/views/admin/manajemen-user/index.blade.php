@extends(file_exists(resource_path('views/layouts/app.blade.php')) ? 'layouts.app' : 'dashboard')

@section('page_title', 'Manajemen Akun Admin')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

<div x-data="{ 
    openModal: false, 
    openDeleteModal: false, 
    deleteUrl: '', 
    deleteUserName: '', 
    loaded: false 
}" x-init="setTimeout(() => loaded = true, 50)">

    <div class="space-y-6 transition-all duration-500 ease-out transform"
         :class="loaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'">

        <!-- HERO BRANDING CARD (Sesuai Desain Gambar Referensi) -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 bg-slate-50 dark:bg-slate-800 rounded-2xl flex items-center justify-center p-2 border border-slate-100 dark:border-slate-800 shrink-0 shadow-xs">
                    <img src="/images/logo.png" alt="KREATINDO" class="w-full h-full object-contain rounded-xl">
                </div>
                <div>
                    <span class="text-[11px] uppercase tracking-widest text-slate-400 dark:text-slate-500 font-bold">PT. PUSAT KREATIF INDONESIA</span>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-slate-100 tracking-tight mt-0.5">Manajemen Akun Admin</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Kelola daftar pengguna dan hak akses kredensial ke dalam sistem.</p>
                </div>
            </div>

            <!-- Tombol Tambah Akun -->
            <div class="shrink-0">
                <button @click="openModal = true" type="button" class="px-5 py-3 bg-slate-900 dark:bg-indigo-600 hover:bg-slate-800 dark:hover:bg-indigo-500 active:scale-95 text-white text-xs font-bold rounded-2xl shadow-md transition duration-200 flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-user-plus text-xs"></i> Tambah Akun Baru
                </button>
            </div>
        </div>

        <!-- 4. TABEL DAFTAR AKUN ADMIN -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div>
                    <h3 class="text-slate-800 dark:text-slate-100 font-bold text-sm">Daftar Pengguna Terdaftar</h3>
                    <p class="text-slate-400 dark:text-slate-500 text-[11px] mt-0.5">Daftar akun yang memiliki kewenangan akses sistem</p>
                </div>
                <span class="px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl border border-slate-200 dark:border-slate-700">
                    Total: {{ method_exists($users, 'total') ? $users->total() : count($users) }} User
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                    <thead class="bg-slate-50 dark:bg-slate-800/70 text-slate-400 dark:text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100 dark:border-slate-800">
                        <tr>
                            <th class="p-4.5 pl-6">No</th>
                            <th class="p-4.5">Pengguna</th>
                            <th class="p-4.5">Email</th>
                            <th class="p-4.5">Role / Hak Akses</th>
                            <th class="p-4.5">Tanggal Dibuat</th>
                            <th class="p-4.5 text-center pr-6">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($users as $index => $user)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="p-4.5 pl-6 font-bold text-slate-500 dark:text-slate-400">
                                    {{ method_exists($users, 'firstItem') && $users->firstItem() ? $users->firstItem() + $index : $index + 1 }}
                                </td>
                                <td class="p-4.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-slate-900 dark:bg-slate-700 text-white flex items-center justify-center text-[10px] uppercase font-extrabold shadow-xs shrink-0">
                                            {{ substr($user->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900 dark:text-slate-100">{{ $user->name }}</div>
                                            <div class="text-[10px] text-slate-400 dark:text-slate-500 font-mono">ID: #{{ $user->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4.5 font-medium text-slate-700 dark:text-slate-200">
                                    {{ $user->email }}
                                </td>
                                <td class="p-4.5">
                                    @if($user->isSuperadmin())
                                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase tracking-wider bg-purple-50 text-purple-700 border border-purple-200/60 inline-flex items-center gap-1.5">
                                            <i class="fa-solid fa-crown text-[9px]"></i> Superadmin
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200/60 inline-flex items-center gap-1.5">
                                            <i class="fa-solid fa-user-shield text-[9px]"></i> Admin
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4.5 text-slate-500 dark:text-slate-400 font-medium">
                                    {{ $user->created_at ? $user->created_at->format('d M Y, H:i') : '-' }}
                                </td>
                                <td class="p-4.5 text-center pr-6">
                                    {{-- Logika: Jika ID sama dengan User Login ATAU Role-nya Superadmin --}}
                                    @if(auth()->id() === $user->id)
                                        <span class="text-[11px] text-slate-400 dark:text-slate-500 font-medium italic">Akun Anda</span>
                                    @elseif($user->isSuperadmin())
                                        <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 text-[10px] font-bold rounded-lg border border-slate-200 dark:border-slate-700/60 inline-flex items-center gap-1 cursor-not-allowed">
                                            <i class="fa-solid fa-lock text-[9px]"></i> Protected
                                        </span>
                                    @else
                                        <button type="button" 
                                                @click="openDeleteModal = true; deleteUrl = '{{ route('users.destroy', $user->hash) }}'; deleteUserName = '{{ addslashes($user->name) }}'" 
                                                class="px-3.5 py-1.5 bg-rose-50 hover:bg-rose-500 hover:text-white text-rose-600 text-[11px] font-bold rounded-xl transition inline-flex items-center gap-1.5 cursor-pointer border border-rose-100">
                                            <i class="fas fa-trash-can text-[10px]"></i> Hapus
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-12 text-center text-slate-400 dark:text-slate-500">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center mx-auto mb-3">
                                        <i class="fa-solid fa-users-slash text-xl"></i>
                                    </div>
                                    <p class="text-xs font-bold text-slate-700 dark:text-slate-200">Belum Ada Data Akun</p>
                                    <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5">Belum ada akun admin yang terdaftar dalam sistem.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(method_exists($users, 'hasPages') && $users->hasPages())
                <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

    </div>

    <!-- 5. MODAL FORM TAMBAH ADMIN -->
    <div x-show="openModal" 
         x-cloak
         class="fixed inset-0 z-[9999] overflow-y-auto"
         role="dialog" aria-modal="true">
        
        <div x-show="openModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="openModal = false"
             class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm"></div>

        <div class="min-h-full flex items-center justify-center p-4">
            <div x-show="openModal"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                 class="relative overflow-hidden rounded-3xl bg-white dark:bg-slate-900 text-left shadow-2xl transition-all w-full max-w-lg border border-slate-100 dark:border-slate-800 my-8">
                
                <div class="px-6 py-5 bg-slate-900 text-white flex items-center justify-between border-b border-white/10">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-slate-800 rounded-xl flex items-center justify-center p-1.5 border border-slate-700">
                            <img src="/images/logo.png" alt="KREATINDO" class="w-full h-full object-contain rounded-md">
                        </div>
                        <div>
                            <h3 class="text-xs font-bold leading-tight text-white">Form Tambah Admin Baru</h3>
                            <p class="text-[10px] text-slate-400 dark:text-slate-500">PT. Pusat Kreatif Indonesia</p>
                        </div>
                    </div>
                    <button @click="openModal = false" type="button" class="text-slate-400 dark:text-slate-500 hover:text-white transition cursor-pointer">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>

                <form action="{{ route('users.store') }}" method="POST" class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1.5">
                            <i class="fa-solid fa-id-card text-indigo-600 mr-1.5"></i>Nama Lengkap
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-700 transition outline-none">
                        @error('name')
                            <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1.5">
                            <i class="fa-solid fa-envelope text-indigo-600 mr-1.5"></i>Alamat Email
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="admin@kreatindo.com" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-700 transition outline-none">
                        @error('email')
                            <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1.5">
                            <i class="fa-solid fa-lock text-indigo-600 mr-1.5"></i>Kata Sandi (Password)
                        </label>
                        <input type="password" name="password" required placeholder="Minimal 8 Karakter" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-700 transition outline-none">
                        @error('password')
                            <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1.5">
                            <i class="fa-solid fa-user-gear text-indigo-600 mr-1.5"></i>Hak Akses (Role)
                        </label>
                        <select name="role" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-700 transition outline-none cursor-pointer">
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin (Petugas)</option>
                            <option value="superadmin" {{ old('role') === 'superadmin' ? 'selected' : '' }}>Superadmin (Pengelola Utama)</option>
                        </select>
                        @error('role')
                            <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100 dark:border-slate-800 mt-6">
                        <button type="button" @click="openModal = false" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 text-xs font-semibold rounded-xl transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="px-6 py-2.5 bg-slate-900 dark:bg-indigo-600 hover:bg-slate-800 dark:hover:bg-indigo-500 text-white text-xs font-semibold rounded-xl transition shadow-md cursor-pointer">
                            Simpan Akun Admin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 6. MODAL KONFIRMASI HAPUS USER -->
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
                
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Hapus Akun Admin?</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 mb-6">
                    Apakah Anda yakin ingin menghapus pengguna <span class="font-bold text-slate-700 dark:text-slate-200" x-text="deleteUserName"></span>? Hak akses user ini akan dicabut.
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
