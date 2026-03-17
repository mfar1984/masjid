<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kategori Aset - E-Masjid</title>
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
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Edit Kategori Aset</h1>
                        <p class="text-xs text-gray-600">{{ $kategoriAset->kod_kategori }} - {{ $kategoriAset->nama_kategori }}</p>
                    </div>
                    <a href="{{ route('kategori-aset.index') }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                        <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                        Kembali
                    </a>
                </div>

                <form method="POST" action="{{ route('kategori-aset.update', $kategoriAset) }}">
                    @csrf
                    @method('PUT')

                    <!-- Section 1: Maklumat Kategori -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">1. Maklumat Kategori</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="kod_kategori" class="block text-xs font-medium text-gray-700 mb-2">Kod Kategori *</label>
                                <input type="text" id="kod_kategori" name="kod_kategori" value="{{ old('kod_kategori', $kategoriAset->kod_kategori) }}" required maxlength="50" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs uppercase">
                                <p class="text-[10px] text-gray-500 mt-1">Contoh: TB01, KEN01, PER01</p>
                                @error('kod_kategori')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="nama_kategori" class="block text-xs font-medium text-gray-700 mb-2">Nama Kategori *</label>
                                <input type="text" id="nama_kategori" name="nama_kategori" value="{{ old('nama_kategori', $kategoriAset->nama_kategori) }}" required maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('nama_kategori')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="jenis_kategori" class="block text-xs font-medium text-gray-700 mb-2">Jenis Kategori *</label>
                                <select id="jenis_kategori" name="jenis_kategori" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Jenis --</option>
                                    <option value="Tanah & Bangunan" {{ old('jenis_kategori', $kategoriAset->jenis_kategori) == 'Tanah & Bangunan' ? 'selected' : '' }}>Tanah & Bangunan</option>
                                    <option value="Kenderaan" {{ old('jenis_kategori', $kategoriAset->jenis_kategori) == 'Kenderaan' ? 'selected' : '' }}>Kenderaan</option>
                                    <option value="Peralatan" {{ old('jenis_kategori', $kategoriAset->jenis_kategori) == 'Peralatan' ? 'selected' : '' }}>Peralatan</option>
                                    <option value="Perabot" {{ old('jenis_kategori', $kategoriAset->jenis_kategori) == 'Perabot' ? 'selected' : '' }}>Perabot</option>
                                    <option value="Elektronik" {{ old('jenis_kategori', $kategoriAset->jenis_kategori) == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                                    <option value="Lain-lain" {{ old('jenis_kategori', $kategoriAset->jenis_kategori) == 'Lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                                </select>
                                @error('jenis_kategori')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="urutan" class="block text-xs font-medium text-gray-700 mb-2">Urutan Paparan</label>
                                <input type="number" id="urutan" name="urutan" value="{{ old('urutan', $kategoriAset->urutan) }}" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                <p class="text-[10px] text-gray-500 mt-1">Nombor kecil akan dipaparkan dahulu</p>
                            </div>

                            <div class="md:col-span-2">
                                <label for="keterangan" class="block text-xs font-medium text-gray-700 mb-2">Keterangan</label>
                                <textarea id="keterangan" name="keterangan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">{{ old('keterangan', $kategoriAset->keterangan) }}</textarea>
                            </div>

                            <div>
                                <label for="status" class="block text-xs font-medium text-gray-700 mb-2">Status *</label>
                                <select id="status" name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="Aktif" {{ old('status', $kategoriAset->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Tidak Aktif" {{ old('status', $kategoriAset->status) == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                                @error('status')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('kategori-aset.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                            Kemaskini Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
