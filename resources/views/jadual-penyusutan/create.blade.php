<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jadual Penyusutan - E-Masjid</title>
    <x-favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col" style="font-family: 'Poppins', sans-serif;">
    <x-double-navbar :user="auth()->user()" />

    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <div class="mb-6 flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('jadual-penyusutan.index') }}" class="text-blue-600 hover:text-blue-800">
                            <span class="material-icons" style="font-size: 20px !important;">arrow_back</span>
                        </a>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">Tambah Jadual Penyusutan</h1>
                            <p class="text-xs text-gray-600">Tetapkan kadar susut nilai untuk kategori aset</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('jadual-penyusutan.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Kategori Aset <span class="text-red-500">*</span></label>
                            <select name="kategori_aset_id" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoriAset as $kategori)
                                    @if(!in_array($kategori->id, $existingKategori))
                                        <option value="{{ $kategori->id }}" {{ old('kategori_aset_id') == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama_kategori }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('kategori_aset_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Kadar Susut Tahunan (%) <span class="text-red-500">*</span></label>
                            <input type="number" name="kadar_susut_tahunan" step="0.01" min="0" max="100" value="{{ old('kadar_susut_tahunan', 10) }}" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">
                            @error('kadar_susut_tahunan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Kaedah Susut <span class="text-red-500">*</span></label>
                            <select name="kaedah_susut" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">
                                <option value="Garis Lurus" {{ old('kaedah_susut') == 'Garis Lurus' ? 'selected' : '' }}>Garis Lurus</option>
                                <option value="Baki Berkurangan" {{ old('kaedah_susut') == 'Baki Berkurangan' ? 'selected' : '' }}>Baki Berkurangan</option>
                            </select>
                            @error('kaedah_susut')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Tempoh Guna (Tahun) <span class="text-red-500">*</span></label>
                            <input type="number" name="tempoh_guna_tahun" min="1" max="50" value="{{ old('tempoh_guna_tahun', 5) }}" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">
                            @error('tempoh_guna_tahun')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                            <select name="status" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">
                                <option value="Aktif" {{ old('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Tidak Aktif" {{ old('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Catatan</label>
                            <textarea name="catatan" rows="3" class="w-full px-3 py-2 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">{{ old('catatan') }}</textarea>
                            @error('catatan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="{{ route('jadual-penyusutan.index') }}" class="px-4 py-2 text-xs text-gray-700 bg-gray-200 rounded hover:bg-gray-300">Batal</a>
                        <button type="submit" class="px-4 py-2 text-xs text-white bg-blue-600 rounded hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
