<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perbelanjaan Lain - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Perbelanjaan Lain-lain</h1>
                        <p class="text-xs text-gray-600">Rekod perbelanjaan lain-lain</p>
                    </div>
                    <a href="{{ route('transaksi-kewangan.index') }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                        <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                        Kembali
                    </a>
                </div>

                <form method="POST" action="{{ route('perbelanjaan.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="jenis_perbelanjaan" value="Perbelanjaan Lain">

                    <div class="bg-orange-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Perbelanjaan</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="kategori_perbelanjaan" class="block text-xs font-medium text-gray-700 mb-2">Kategori *</label>
                                <input type="text" id="kategori_perbelanjaan" name="kategori_perbelanjaan" value="{{ old('kategori_perbelanjaan') }}" required maxlength="100" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs" placeholder="Contoh: Alat Tulis, Peralatan, dll">
                                @error('kategori_perbelanjaan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="tarikh_perbelanjaan" class="block text-xs font-medium text-gray-700 mb-2">Tarikh Perbelanjaan *</label>
                                <input type="date" id="tarikh_perbelanjaan" name="tarikh_perbelanjaan" value="{{ old('tarikh_perbelanjaan', date('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('tarikh_perbelanjaan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="nama_pembekal" class="block text-xs font-medium text-gray-700 mb-2">Nama Pembekal</label>
                                <input type="text" id="nama_pembekal" name="nama_pembekal" value="{{ old('nama_pembekal') }}" maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('nama_pembekal')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="no_invois" class="block text-xs font-medium text-gray-700 mb-2">No. Invois/Resit</label>
                                <input type="text" id="no_invois" name="no_invois" value="{{ old('no_invois') }}" maxlength="100" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('no_invois')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-orange-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Bayaran</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="jumlah" class="block text-xs font-medium text-gray-700 mb-2">Jumlah (RM) *</label>
                                <input type="number" id="jumlah" name="jumlah" value="{{ old('jumlah') }}" step="0.01" min="0.01" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('jumlah')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="kaedah_bayaran" class="block text-xs font-medium text-gray-700 mb-2">Kaedah Bayaran *</label>
                                <select id="kaedah_bayaran" name="kaedah_bayaran" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Kaedah --</option>
                                    @foreach($kaedahBayaran as $kaedah)
                                        <option value="{{ $kaedah->nama_kategori }}" {{ old('kaedah_bayaran') == $kaedah->nama_kategori ? 'selected' : '' }}>
                                            {{ $kaedah->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kaedah_bayaran')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="akaun_bank_id" class="block text-xs font-medium text-gray-700 mb-2">Akaun Bank *</label>
                                <select id="akaun_bank_id" name="akaun_bank_id" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Akaun Bank --</option>
                                    @foreach($akaunBank as $akaun)
                                        <option value="{{ $akaun->id }}">{{ $akaun->nama_bank }} - {{ $akaun->no_akaun }}</option>
                                    @endforeach
                                </select>
                                @error('akaun_bank_id')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="no_rujukan" class="block text-xs font-medium text-gray-700 mb-2">No. Rujukan</label>
                                <input type="text" id="no_rujukan" name="no_rujukan" value="{{ old('no_rujukan') }}" maxlength="100" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('no_rujukan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-orange-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Keterangan & Dokumen</h2>
                        
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="keterangan" class="block text-xs font-medium text-gray-700 mb-2">Keterangan *</label>
                                <textarea id="keterangan" name="keterangan" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">{{ old('keterangan') }}</textarea>
                                @error('keterangan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="dokumen" class="block text-xs font-medium text-gray-700 mb-2">Dokumen Sokongan</label>
                                <input type="file" id="dokumen" name="dokumen" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                <p class="text-[10px] text-gray-500 mt-1">Format: PDF, JPG, PNG (Max: 2MB)</p>
                                @error('dokumen')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="catatan" class="block text-xs font-medium text-gray-700 mb-2">Catatan</label>
                                <textarea id="catatan" name="catatan" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">{{ old('catatan') }}</textarea>
                                @error('catatan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('transaksi-kewangan.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-orange-600 text-white text-xs rounded hover:bg-orange-700">Simpan Perbelanjaan</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
