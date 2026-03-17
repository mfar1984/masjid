<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kemaskini Akaun Bank - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Kemaskini Akaun Bank</h1>
                        <p class="text-xs text-gray-600">Kemaskini maklumat akaun bank</p>
                    </div>
                    <a href="{{ route('akaun-bank.index') }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                        <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                        Kembali
                    </a>
                </div>

                <form method="POST" action="{{ route('akaun-bank.update', $akaunBank) }}">
                    @csrf
                    @method('PUT')

                    <!-- Section 1: Maklumat Bank -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Bank</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="nama_bank" class="block text-xs font-medium text-gray-700 mb-2">Nama Bank *</label>
                                <select id="nama_bank" name="nama_bank" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Bank --</option>
                                    @foreach($namaBank as $bank)
                                        <option value="{{ $bank->nama_kategori }}" {{ old('nama_bank', $akaunBank->nama_bank) == $bank->nama_kategori ? 'selected' : '' }}>
                                            {{ $bank->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('nama_bank')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="no_akaun" class="block text-xs font-medium text-gray-700 mb-2">No. Akaun *</label>
                                <input type="text" id="no_akaun" name="no_akaun" value="{{ old('no_akaun', $akaunBank->no_akaun) }}" required maxlength="50" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('no_akaun')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="jenis_akaun" class="block text-xs font-medium text-gray-700 mb-2">Jenis Akaun *</label>
                                <select id="jenis_akaun" name="jenis_akaun" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Jenis --</option>
                                    @foreach($jenisAkaun as $jenis)
                                        <option value="{{ $jenis->nama_kategori }}" {{ old('jenis_akaun', $akaunBank->jenis_akaun) == $jenis->nama_kategori ? 'selected' : '' }}>
                                            {{ $jenis->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jenis_akaun')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="nama_pemegang_akaun" class="block text-xs font-medium text-gray-700 mb-2">Nama Pemegang Akaun *</label>
                                <input type="text" id="nama_pemegang_akaun" name="nama_pemegang_akaun" value="{{ old('nama_pemegang_akaun', $akaunBank->nama_pemegang_akaun) }}" required maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('nama_pemegang_akaun')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="cawangan" class="block text-xs font-medium text-gray-700 mb-2">Cawangan</label>
                                <input type="text" id="cawangan" name="cawangan" value="{{ old('cawangan', $akaunBank->cawangan) }}" maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('cawangan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="baki_awal" class="block text-xs font-medium text-gray-700 mb-2">Baki Awal (RM) *</label>
                                <input type="number" id="baki_awal" name="baki_awal" value="{{ old('baki_awal', $akaunBank->baki_awal) }}" required step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                <p class="text-[10px] text-gray-500 mt-1">Baki semasa: RM {{ number_format($akaunBank->baki_semasa, 2) }}</p>
                                @error('baki_awal')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Status & Catatan -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Status & Catatan</h2>
                        
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="status" class="block text-xs font-medium text-gray-700 mb-2">Status *</label>
                                <select id="status" name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="Aktif" {{ old('status', $akaunBank->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Tidak Aktif" {{ old('status', $akaunBank->status) == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                                @error('status')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="catatan" class="block text-xs font-medium text-gray-700 mb-2">Catatan</label>
                                <textarea id="catatan" name="catatan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">{{ old('catatan', $akaunBank->catatan) }}</textarea>
                                @error('catatan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('akaun-bank.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                            Kemaskini
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
