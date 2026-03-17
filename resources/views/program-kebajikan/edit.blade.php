<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Program Kebajikan - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Edit Program Kebajikan</h1>
                        <p class="text-xs text-gray-600">Kemaskini maklumat program bantuan kebajikan</p>
                    </div>
                    <a href="{{ route('program-kebajikan.index') }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                        <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                        Kembali
                    </a>
                </div>

                <form method="POST" action="{{ route('program-kebajikan.update', $programKebajikan->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- Section 1: Maklumat Program -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Program</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Kod Program</label>
                                <input type="text" value="{{ $programKebajikan->kod_program }}" readonly class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs bg-gray-100">
                            </div>

                            <div>
                                <label for="nama_program" class="block text-xs font-medium text-gray-700 mb-2">Nama Program *</label>
                                <input type="text" id="nama_program" name="nama_program" value="{{ old('nama_program', $programKebajikan->nama_program) }}" required maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('nama_program')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="kategori_program" class="block text-xs font-medium text-gray-700 mb-2">Kategori Program *</label>
                                <select id="kategori_program" name="kategori_program" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="Pendidikan" {{ old('kategori_program', $programKebajikan->kategori_program) == 'Pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                                    <option value="Kesihatan" {{ old('kategori_program', $programKebajikan->kategori_program) == 'Kesihatan' ? 'selected' : '' }}>Kesihatan</option>
                                    <option value="Kecemasan" {{ old('kategori_program', $programKebajikan->kategori_program) == 'Kecemasan' ? 'selected' : '' }}>Kecemasan</option>
                                    <option value="Kebajikan Am" {{ old('kategori_program', $programKebajikan->kategori_program) == 'Kebajikan Am' ? 'selected' : '' }}>Kebajikan Am</option>
                                    <option value="Anak Yatim" {{ old('kategori_program', $programKebajikan->kategori_program) == 'Anak Yatim' ? 'selected' : '' }}>Anak Yatim</option>
                                    <option value="OKU" {{ old('kategori_program', $programKebajikan->kategori_program) == 'OKU' ? 'selected' : '' }}>OKU</option>
                                    <option value="Warga Emas" {{ old('kategori_program', $programKebajikan->kategori_program) == 'Warga Emas' ? 'selected' : '' }}>Warga Emas</option>
                                    <option value="Ibu Tunggal" {{ old('kategori_program', $programKebajikan->kategori_program) == 'Ibu Tunggal' ? 'selected' : '' }}>Ibu Tunggal</option>
                                    <option value="Lain-lain" {{ old('kategori_program', $programKebajikan->kategori_program) == 'Lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                                </select>
                                @error('kategori_program')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="jenis_bantuan" class="block text-xs font-medium text-gray-700 mb-2">Jenis Bantuan *</label>
                                <select id="jenis_bantuan" name="jenis_bantuan" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Jenis --</option>
                                    <option value="Tunai" {{ old('jenis_bantuan', $programKebajikan->jenis_bantuan) == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                                    <option value="Barangan" {{ old('jenis_bantuan', $programKebajikan->jenis_bantuan) == 'Barangan' ? 'selected' : '' }}>Barangan</option>
                                    <option value="Perkhidmatan" {{ old('jenis_bantuan', $programKebajikan->jenis_bantuan) == 'Perkhidmatan' ? 'selected' : '' }}>Perkhidmatan</option>
                                    <option value="Campuran" {{ old('jenis_bantuan', $programKebajikan->jenis_bantuan) == 'Campuran' ? 'selected' : '' }}>Campuran</option>
                                </select>
                                @error('jenis_bantuan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="tempoh_bantuan" class="block text-xs font-medium text-gray-700 mb-2">Tempoh Bantuan *</label>
                                <select id="tempoh_bantuan" name="tempoh_bantuan" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Tempoh --</option>
                                    @foreach($tempohBantuan as $tempoh)
                                        <option value="{{ $tempoh->nama_kategori }}" {{ old('tempoh_bantuan', $programKebajikan->tempoh_bantuan) == $tempoh->nama_kategori ? 'selected' : '' }}>
                                            {{ $tempoh->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tempoh_bantuan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Had Bantuan -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Had Bantuan</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="had_minimum" class="block text-xs font-medium text-gray-700 mb-2">Had Minimum (RM)</label>
                                <input type="number" id="had_minimum" name="had_minimum" value="{{ old('had_minimum', $programKebajikan->had_minimum) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('had_minimum')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="had_maksimum" class="block text-xs font-medium text-gray-700 mb-2">Had Maksimum (RM)</label>
                                <input type="number" id="had_maksimum" name="had_maksimum" value="{{ old('had_maksimum', $programKebajikan->had_maksimum) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('had_maksimum')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Tempoh Program -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Tempoh Program</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="tarikh_mula" class="block text-xs font-medium text-gray-700 mb-2">Tarikh Mula</label>
                                <input type="date" id="tarikh_mula" name="tarikh_mula" value="{{ old('tarikh_mula', $programKebajikan->tarikh_mula?->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('tarikh_mula')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="tarikh_tamat" class="block text-xs font-medium text-gray-700 mb-2">Tarikh Tamat</label>
                                <input type="date" id="tarikh_tamat" name="tarikh_tamat" value="{{ old('tarikh_tamat', $programKebajikan->tarikh_tamat?->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('tarikh_tamat')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="status_program" class="block text-xs font-medium text-gray-700 mb-2">Status Program *</label>
                                <select id="status_program" name="status_program" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="Aktif" {{ old('status_program', $programKebajikan->status_program) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Tidak Aktif" {{ old('status_program', $programKebajikan->status_program) == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                    <option value="Tamat" {{ old('status_program', $programKebajikan->status_program) == 'Tamat' ? 'selected' : '' }}>Tamat</option>
                                </select>
                                @error('status_program')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Syarat & Dokumen -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Syarat & Dokumen</h2>
                        
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="syarat_kelayakan" class="block text-xs font-medium text-gray-700 mb-2">Syarat Kelayakan</label>
                                <textarea id="syarat_kelayakan" name="syarat_kelayakan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">{{ old('syarat_kelayakan', $programKebajikan->syarat_kelayakan) }}</textarea>
                                <p class="text-[10px] text-gray-500 mt-1">Senaraikan syarat kelayakan untuk program ini</p>
                                @error('syarat_kelayakan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="dokumen_diperlukan" class="block text-xs font-medium text-gray-700 mb-2">Dokumen Diperlukan</label>
                                <textarea id="dokumen_diperlukan" name="dokumen_diperlukan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">{{ old('dokumen_diperlukan', $programKebajikan->dokumen_diperlukan) }}</textarea>
                                <p class="text-[10px] text-gray-500 mt-1">Senaraikan dokumen yang diperlukan untuk permohonan</p>
                                @error('dokumen_diperlukan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="catatan" class="block text-xs font-medium text-gray-700 mb-2">Catatan</label>
                                <textarea id="catatan" name="catatan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">{{ old('catatan', $programKebajikan->catatan) }}</textarea>
                                @error('catatan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('program-kebajikan.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                            Kemaskini Program
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
