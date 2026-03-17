<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utiliti & Bil - E-Masjid</title>
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
                <!-- Header -->
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Utiliti & Bil</h1>
                        <p class="text-xs text-gray-600">Rekod perbelanjaan utiliti dan bil</p>
                    </div>
                    <a href="{{ route('transaksi-kewangan.index') }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                        <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                        Kembali
                    </a>
                </div>

                <form method="POST" action="{{ route('perbelanjaan.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="jenis_perbelanjaan" value="Utiliti & Bil">

                    <!-- Section 1: Maklumat Bil -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Bil</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="jenis_bil_id" class="block text-xs font-medium text-gray-700 mb-2">Jenis Bil *</label>
                                <select id="jenis_bil_id" name="jenis_bil_id" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Jenis Bil --</option>
                                    @foreach($jenisBil as $jenis)
                                        <option value="{{ $jenis->id }}" {{ old('jenis_bil_id') == $jenis->id ? 'selected' : '' }}>
                                            {{ $jenis->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jenis_bil_id')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="no_bil" class="block text-xs font-medium text-gray-700 mb-2">No. Bil *</label>
                                <input type="text" id="no_bil" name="no_bil" value="{{ old('no_bil') }}" required maxlength="100" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('no_bil')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="tarikh_perbelanjaan" class="block text-xs font-medium text-gray-700 mb-2">Tarikh Bil *</label>
                                <input type="date" id="tarikh_perbelanjaan" name="tarikh_perbelanjaan" value="{{ old('tarikh_perbelanjaan', date('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('tarikh_perbelanjaan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="tempoh_bil" class="block text-xs font-medium text-gray-700 mb-2">Tempoh Bil</label>
                                <input type="text" id="tempoh_bil" name="tempoh_bil" value="{{ old('tempoh_bil') }}" maxlength="50" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs" placeholder="Contoh: Jan 2025">
                                @error('tempoh_bil')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="bacaan_meter_awal" class="block text-xs font-medium text-gray-700 mb-2">Bacaan Meter Awal</label>
                                <input type="number" id="bacaan_meter_awal" name="bacaan_meter_awal" value="{{ old('bacaan_meter_awal') }}" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('bacaan_meter_awal')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="bacaan_meter_akhir" class="block text-xs font-medium text-gray-700 mb-2">Bacaan Meter Akhir</label>
                                <input type="number" id="bacaan_meter_akhir" name="bacaan_meter_akhir" value="{{ old('bacaan_meter_akhir') }}" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('bacaan_meter_akhir')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Maklumat Bayaran -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
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
                                        <option value="{{ $akaun->id }}" {{ old('akaun_bank_id') == $akaun->id ? 'selected' : '' }}>
                                            {{ $akaun->nama_bank }} - {{ $akaun->no_akaun }}
                                        </option>
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

                    <!-- Section 3: Keterangan & Dokumen -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
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

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('transaksi-kewangan.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                            Simpan Perbelanjaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
