<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Urusan Jenazah - E-Masjid</title>
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
                    <a href="{{ route('urusan-jenazah.index') }}" class="mr-3 text-gray-500 hover:text-gray-700">
                        <span class="material-icons">arrow_back</span>
                    </a>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Tambah Urusan Jenazah</h1>
                        <p class="text-xs text-gray-600">Daftar rekod urusan jenazah baru</p>
                    </div>
                </div>
                <form action="{{ route('urusan-jenazah.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Maklumat Simati -->
                        <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Simati</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Nama Simati <span class="text-red-500">*</span></label>
                                    <input type="text" name="nama_simati" value="{{ old('nama_simati') }}" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                    @error('nama_simati')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">No. IC Simati</label>
                                    <input type="text" name="no_ic_simati" value="{{ old('no_ic_simati') }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Jantina <span class="text-red-500">*</span></label>
                                        <select name="jantina" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                            <option value="Lelaki" {{ old('jantina') === 'Lelaki' ? 'selected' : '' }}>Lelaki</option>
                                            <option value="Perempuan" {{ old('jantina') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Umur</label>
                                        <input type="number" name="umur" value="{{ old('umur') }}" min="0" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Alamat Simati</label>
                                    <textarea name="alamat_simati" rows="2" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">{{ old('alamat_simati') }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Meninggal <span class="text-red-500">*</span></label>
                                    <input type="date" name="tarikh_meninggal" value="{{ old('tarikh_meninggal') }}" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Masa Meninggal</label>
                                    <input type="time" name="masa_meninggal" value="{{ old('masa_meninggal') }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Tempat Meninggal</label>
                                    <input type="text" name="tempat_meninggal" value="{{ old('tempat_meninggal') }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Sebab Kematian</label>
                                    <input type="text" name="sebab_kematian" value="{{ old('sebab_kematian') }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                </div>
                            </div>
                        </div>
                        <!-- Maklumat Waris & Pengurusan -->
                        <div class="space-y-6">
                            <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                                <h3 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Waris</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Nama Waris <span class="text-red-500">*</span></label>
                                        <input type="text" name="nama_waris" value="{{ old('nama_waris') }}" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">No. Telefon Waris <span class="text-red-500">*</span></label>
                                        <input type="text" name="no_telefon_waris" value="{{ old('no_telefon_waris') }}" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Hubungan Waris</label>
                                        <input type="text" name="hubungan_waris" value="{{ old('hubungan_waris') }}" placeholder="cth: Anak, Isteri, Suami" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                                <h3 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Kos</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Kos Pengurusan (RM)</label>
                                        <input type="number" name="kos_pengurusan" value="{{ old('kos_pengurusan', 0) }}" step="0.01" min="0" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Status Bayaran</label>
                                        <select name="status_bayaran" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                            <option value="Belum Bayar" {{ old('status_bayaran') === 'Belum Bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                            <option value="Sudah Bayar" {{ old('status_bayaran') === 'Sudah Bayar' ? 'selected' : '' }}>Sudah Bayar</option>
                                            <option value="Percuma" {{ old('status_bayaran') === 'Percuma' ? 'selected' : '' }}>Percuma</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Catatan</label>
                        <textarea name="catatan" rows="2" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">{{ old('catatan') }}</textarea>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('urusan-jenazah.index') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-gray-500 text-white text-xs rounded hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
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
