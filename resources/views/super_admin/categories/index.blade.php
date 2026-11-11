@extends('layouts.super_admin')

@section('title', 'Manajemen Kategori Kursus')

@section('content')
<div class="space-y-6" x-data="{ 
    addModal: false, 
    editModal: false, 
    searchQuery: '',
    editData: { id: '', name: '', icon: '', color: '#3b82f6', order: 0 } 
}">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-outfit text-2xl font-bold text-slate-900 tracking-tight">Kategori Program Pelatihan</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola bidang kejuruan, warna aksen, dan hierarki kategori kursus di platform Skilloka.</p>
        </div>
        <button @click="addModal = true" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold shadow-sm transition flex items-center gap-2 self-start sm:self-auto">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Tambah Kategori Baru</span>
        </button>
    </div>

    <!-- ═══════════════════════════════════════
         SUMMARY METRICS (3 CARDS)
    ═══════════════════════════════════════ -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- Metric 1: Total Kategori -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Bidang Kejuruan</span>
                <div class="font-outfit text-2xl font-extrabold text-slate-900 mt-1">
                    {{ $categories->count() }} Kategori
                </div>
                <div class="text-[11px] font-semibold text-emerald-600 mt-1 flex items-center gap-1">
                    <i class="fa-solid fa-circle-check text-[10px]"></i>
                    <span>Tersedia untuk pendaftaran</span>
                </div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-shapes"></i>
            </div>
        </div>

        <!-- Metric 2: Total Kursus -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Program Kursus</span>
                <div class="font-outfit text-2xl font-extrabold text-slate-900 mt-1">
                    {{ $categories->sum('courses_count') }} Program
                </div>
                <div class="text-[11px] font-semibold text-indigo-600 mt-1 flex items-center gap-1">
                    <i class="fa-solid fa-layer-group text-[10px]"></i>
                    <span>Terhubung ke kategori aktif</span>
                </div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
        </div>

        <!-- Metric 3: Kategori Terpopuler -->
        @php
            $popularCategory = $categories->sortByDesc('courses_count')->first();
        @endphp
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Kategori Teraktif</span>
                <div class="font-outfit text-lg font-bold text-slate-900 mt-1 truncate max-w-[180px]">
                    {{ $popularCategory->name ?? 'Belum ada' }}
                </div>
                <div class="text-[11px] text-amber-600 font-semibold mt-1 flex items-center gap-1">
                    <i class="fa-solid fa-fire text-[10px]"></i>
                    <span>{{ $popularCategory->courses_count ?? 0 }} kursus terdaftar</span>
                </div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-star"></i>
            </div>
        </div>
    </div>

    <!-- Alert Notifikasi -->
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation text-rose-600 text-sm"></i>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <!-- ═══════════════════════════════════════
         TABEL KATEGORI KURSUS
    ═══════════════════════════════════════ -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">
        
        <!-- Table Toolbar -->
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="font-outfit font-bold text-base text-slate-900">Master Data Kategori</h3>
                <p class="text-xs text-slate-500 mt-0.5">Urutan tampil menentukan urutan pada katalog kursus mobile dan web publik.</p>
            </div>

            <!-- Search input client-side via Alpine -->
            <div class="relative w-full sm:w-64">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" 
                       x-model="searchQuery" 
                       placeholder="Cari kategori atau slug..." 
                       class="w-full pl-9 pr-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:bg-white transition">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-slate-700 font-semibold border-b border-slate-200 uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="py-3.5 px-6 w-20">Urutan</th>
                        <th class="py-3.5 px-6">Nama Kategori & Slug</th>
                        <th class="py-3.5 px-6">Aksen Warna</th>
                        <th class="py-3.5 px-6">Jumlah Kursus</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($categories as $category)
                        <tr class="hover:bg-slate-50/60 transition-colors"
                            x-show="searchQuery === '' || '{{ strtolower(addslashes($category->name)) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower($category->slug) }}'.includes(searchQuery.toLowerCase())">
                            
                            <!-- Urutan -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                <span class="inline-flex items-center justify-center w-8 h-7 rounded-lg bg-slate-100 text-slate-700 font-mono text-xs font-bold border border-slate-200">
                                    #{{ $category->order }}
                                </span>
                            </td>

                            <!-- Nama & Slug -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white text-sm shadow-2xs shrink-0" 
                                         style="background-color: {{ $category->color ?? '#3b82f6' }}">
                                        <i class="fa-solid {{ $category->icon ?? 'fa-shapes' }}"></i>
                                    </div>
                                    <div class="space-y-0.5">
                                        <div class="font-outfit font-bold text-sm text-slate-900">
                                            {{ $category->name }}
                                        </div>
                                        <div class="flex items-center gap-1.5 font-mono text-[11px] text-slate-400">
                                            <span>slug:</span>
                                            <span class="text-slate-600 bg-slate-100 px-1.5 py-0.2 rounded">{{ $category->slug }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Aksen Warna -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-lg bg-slate-50 border border-slate-200 text-slate-700 text-xs font-mono">
                                    <span class="w-3.5 h-3.5 rounded-full shadow-2xs border border-white" style="background-color: {{ $category->color ?? '#3b82f6' }}"></span>
                                    <span>{{ strtoupper($category->color ?? '#3b82f6') }}</span>
                                </div>
                            </td>

                            <!-- Total Kursus -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                @if($category->courses_count > 0)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-graduation-cap text-[10px]"></i>
                                        <span>{{ $category->courses_count }} Program Kursus</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-500 border border-slate-200">
                                        <i class="fa-regular fa-folder text-[10px]"></i>
                                        <span>0 Kursus</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="py-4 px-6 whitespace-nowrap text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <button @click="editData = { id: '{{ $category->id }}', name: '{{ addslashes($category->name) }}', icon: '{{ $category->icon }}', color: '{{ $category->color }}', order: {{ $category->order }} }; editModal = true" 
                                            class="px-3 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 rounded-xl text-xs font-semibold transition flex items-center gap-1.5">
                                        <i class="fa-solid fa-pen-to-square text-[11px] text-slate-400"></i>
                                        <span>Edit</span>
                                    </button>

                                    <form action="{{ route('super.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori {{ addslashes($category->name) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-1.5 bg-slate-50 hover:bg-rose-50 text-slate-400 hover:text-rose-600 border border-slate-200 hover:border-rose-200 rounded-xl text-xs transition" 
                                                title="Hapus Kategori">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 text-lg">
                                        <i class="fa-solid fa-shapes"></i>
                                    </div>
                                    <p class="font-medium text-slate-700">Belum ada kategori pelatihan yang dibuat.</p>
                                    <p class="text-xs text-slate-400">Tambahkan kategori baru untuk mengelompokkan program kursus mitra LPK.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ═══════════════════════════════════════
         MODAL TAMBAH KATEGORI
    ═══════════════════════════════════════ -->
    <div x-show="addModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs" 
         style="display: none;">
        <div @click.away="addModal = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-5 border border-slate-100">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-plus"></i>
                    </div>
                    <h3 class="font-outfit font-bold text-base text-slate-900">Tambah Kategori Baru</h3>
                </div>
                <button type="button" @click="addModal = false" class="text-slate-400 hover:text-slate-600 text-sm">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('super.categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Kategori <span class="text-rose-500">*</span></label>
                    <input type="text" 
                           name="name" 
                           required 
                           placeholder="Contoh: Tata Busana & Menjahit" 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Icon FontAwesome</label>
                        <input type="text" 
                               name="icon" 
                               placeholder="fa-scissors" 
                               value="fa-shapes"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition font-mono">
                        <span class="text-[10px] text-slate-400 mt-1 block">Contoh: fa-laptop-code, fa-language</span>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Warna Aksen</label>
                        <div class="flex items-center gap-2">
                            <input type="color" 
                                   name="color" 
                                   value="#059669" 
                                   class="w-10 h-9 p-0.5 rounded-xl border border-slate-200 cursor-pointer shrink-0">
                            <span class="text-xs text-slate-500 font-mono">Pilih warna</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Urutan Tampil (Order)</label>
                    <input type="number" 
                           name="order" 
                           value="0" 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition font-mono">
                    <span class="text-[10px] text-slate-400 mt-1 block">Semakin kecil angka, semakin awal ditampilkan.</span>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="addModal = false" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm transition">
                        Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ═══════════════════════════════════════
         MODAL EDIT KATEGORI
    ═══════════════════════════════════════ -->
    <div x-show="editModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs" 
         style="display: none;">
        <div @click.away="editModal = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-5 border border-slate-100">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </div>
                    <h3 class="font-outfit font-bold text-base text-slate-900">Ubah Kategori Kursus</h3>
                </div>
                <button type="button" @click="editModal = false" class="text-slate-400 hover:text-slate-600 text-sm">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form :action="'{{ url('super-admin/categories') }}/' + editData.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Kategori <span class="text-rose-500">*</span></label>
                    <input type="text" 
                           name="name" 
                           x-model="editData.name" 
                           required 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Icon FontAwesome</label>
                        <input type="text" 
                               name="icon" 
                               x-model="editData.icon" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Warna Aksen</label>
                        <div class="flex items-center gap-2">
                            <input type="color" 
                                   name="color" 
                                   x-model="editData.color" 
                                   class="w-10 h-9 p-0.5 rounded-xl border border-slate-200 cursor-pointer shrink-0">
                            <span class="text-xs text-slate-500 font-mono" x-text="editData.color"></span>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Urutan Tampil (Order)</label>
                    <input type="number" 
                           name="order" 
                           x-model="editData.order" 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition font-mono">
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="editModal = false" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition">
                        Perbarui Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
