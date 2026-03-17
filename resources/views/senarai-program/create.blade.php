<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Program - E-Masjid</title>
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
                <div class="flex items-center mb-6">
                    <a href="{{ route('senarai-program.index') }}" class="mr-3 text-gray-500 hover:text-gray-700">
                        <span class="material-icons">arrow_back</span>
                    </a>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Tambah Program</h1>
                        <p class="text-xs text-gray-600">Daftar program baru</p>
                    </div>
                </div>
                <form action="{{ route('senarai-program.store') }}" method="POST">
                    @csrf
                    <div class="bg-gray-50 p-4 rounded-md border border-gray-200 max-w-2xl">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Nama Program <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_program" value="{{ old('nama_program') }}" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                @error('nama_program')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Kod Program</label>
                                <input type="text" name="kod_program" value="{{ old('kod_program') }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Jenis Program <span class="text-red-500">*</span></label>
                                    <select name="jenis_program" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                        @foreach(['Kuliah', 'Ceramah', 'Kursus', 'Bengkel', 'Seminar', 'Kem', 'Lain-lain'] as $jenis)
                                            <option value="{{ $jenis }}" {{ old('jenis_program') === $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                                    <select name="kategori" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                        @foreach(['Dewasa', 'Remaja', 'Kanak-kanak', 'Wanita', 'Umum'] as $kat)
                                            <option value="{{ $kat }}" {{ old('kategori', 'Umum') === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Penerangan</label>
                                <textarea name="penerangan" rows="3" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">{{ old('penerangan') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Lokasi</label>
                                <input type="text" name="lokasi" value="{{ old('lokasi') }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Kapasiti</label>
                                    <input type="number" name="kapasiti" value="{{ old('kapasiti') }}" min="0" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Yuran (RM)</label>
                                    <input type="number" name="yuran" value="{{ old('yuran', 0) }}" step="0.01" min="0" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                                <select name="status" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                    <option value="Aktif" {{ old('status', 'Aktif') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Tidak Aktif" {{ old('status') === 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('senarai-program.index') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-gray-500 text-white text-xs rounded hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                            Kembali
                        </a>
                        <button type="submit" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">save</span>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <x-footer />
</body>
</html>
