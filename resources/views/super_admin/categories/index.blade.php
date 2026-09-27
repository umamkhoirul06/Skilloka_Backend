@extends('layouts.super_admin')

@section('title', 'Manajemen Kategori Kursus')

@section('content')
<div class="space-y-6" x-data="{ addModal: false, editModal: false, editData: { id: '', name: '', icon: '', color: '#3b82f6', order: 0 } }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-outfit text-gray-900">Kategori Pelatihan</h1>
            <p class="text-xs text-gray-500 mt-1">Kelola bidang kejuruan dan kategori kursus di seluruh platform Skilloka.</p>
        </div>
        <button @click="addModal = true" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold shadow-md transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Tambah Kategori Baru</span>
        </button>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs">
            {{ $errors->first() }}
        </div>
    @endif

    <!-- Categories Table Card -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50/80 text-gray-700 font-semibold border-b border-gray-200 uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="py-3.5 px-6">Urutan</th>
                        <th class="py-3.5 px-6">Nama Kategori</th>
                        <th class="py-3.5 px-6">Slug</th>
                        <th class="py-3.5 px-6">Aksen Warna</th>
                        <th class="py-3.5 px-6">Total Kursus</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($categories as $category)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-3.5 px-6 font-mono font-medium text-gray-500">{{ $category->order }}</td>
                            <td class="py-3.5 px-6">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs shadow-sm" style="background-color: {{ $category->color ?? '#3b82f6' }}">
                                        <i class="fa-solid {{ $category->icon ?? 'fa-shapes' }}"></i>
                                    </span>
                                    <span class="font-bold text-gray-800 text-sm">{{ $category->name }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-6 font-mono text-gray-500">{{ $category->slug }}</td>
                            <td class="py-3.5 px-6">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-mono border" style="border-color: {{ $category->color ?? '#ccc' }}; color: {{ $category->color ?? '#333' }}">
                                    <span class="w-2 h-2 rounded-full" style="background-color: {{ $category->color ?? '#3b82f6' }}"></span>
                                    {{ $category->color ?? '#3b82f6' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-6">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700">
                                    {{ $category->courses_count }} Kursus
                                </span>
                            </td>
                            <td class="py-3.5 px-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="editData = { id: '{{ $category->id }}', name: '{{ addslashes($category->name) }}', icon: '{{ $category->icon }}', color: '{{ $category->color }}', order: {{ $category->order }} }; editModal = true" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-medium transition">
                                        Edit
                                    </button>
                                    <form action="{{ route('super.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-xs font-medium transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-400">Belum ada kategori pelatihan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL TAMBAH KATEGORI -->
    <div x-show="addModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" style="display: none;">
        <div @click.away="addModal = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <h3 class="font-outfit font-bold text-lg text-gray-900">Tambah Kategori Kursus Baru</h3>
            <form action="{{ route('super.categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Kategori</label>
                    <input type="text" name="name" required placeholder="Contoh: Tata Busana & Menjahit" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs focus:outline-none focus:border-emerald-500">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Icon FontAwesome</label>
                        <input type="text" name="icon" placeholder="fa-scissors" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs focus:outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Warna Aksen</label>
                        <input type="color" name="color" value="#059669" class="w-full h-10 p-1 rounded-xl border border-gray-200 cursor-pointer">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Urutan Tampil (Order)</label>
                    <input type="number" name="order" value="0" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs focus:outline-none focus:border-emerald-500">
                </div>
                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" @click="addModal = false" class="px-4 py-2 rounded-xl text-xs font-medium text-gray-600 hover:bg-gray-100">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 shadow">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT KATEGORI -->
    <div x-show="editModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" style="display: none;">
        <div @click.away="editModal = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <h3 class="font-outfit font-bold text-lg text-gray-900">Ubah Kategori Kursus</h3>
            <form :action="'{{ url('super-admin/categories') }}/' + editData.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Kategori</label>
                    <input type="text" name="name" x-model="editData.name" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs focus:outline-none focus:border-emerald-500">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Icon FontAwesome</label>
                        <input type="text" name="icon" x-model="editData.icon" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs focus:outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Warna Aksen</label>
                        <input type="color" name="color" x-model="editData.color" class="w-full h-10 p-1 rounded-xl border border-gray-200 cursor-pointer">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Urutan Tampil (Order)</label>
                    <input type="number" name="order" x-model="editData.order" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs focus:outline-none focus:border-emerald-500">
                </div>
                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" @click="editModal = false" class="px-4 py-2 rounded-xl text-xs font-medium text-gray-600 hover:bg-gray-100">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 shadow">Perbarui Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
