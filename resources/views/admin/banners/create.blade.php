@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Tambah Banner Baru</h2>

        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-white p-6 rounded-xl shadow-md">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Judul Banner</label>
                <input type="text" name="title" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Promo Spesial Kemerdekaan">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Gambar Banner (16:9 disarankan)</label>
                <input type="file" name="image" accept="image/*" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none">
            </div>

            <div class="mb-6 flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" checked
                    class="w-5 h-5 text-blue-600 rounded border-gray-300">
                <label for="is_active" class="ml-2 text-gray-700 font-semibold">Tampilkan Banner (Aktif)</label>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('admin.banners.index') }}"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Batal</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan
                    Banner</button>
            </div>
        </form>
    </div>
@endsection