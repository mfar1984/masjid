<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Program Kebajikan - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Tambah Program Kebajikan</h1>
                        <p class="text-xs text-gray-600">Cipta program bantuan kebajikan baharu</p>
                    </div>
                    <a href="{{ route('program-kebajikan.index') }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                        <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                        Kembali
                    </a>
                </div>

                <form method="POST" action="{{ route('program-kebajikan.store') }}">
                    @csrf

                    <!-- Section 1: Maklumat Program -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Program</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="nama_program" class="block text-xs font-medium text-gray-700 mb-2">Nama Program *</label>
                                <input type="text" id="nama_program" name="nama_program" value="{{ old('nama_program') }}" required maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('nama_program')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="kategori_program" class="block text-xs font-medium text-gray-700 mb-2">Kategori Program *</label>
                                <select id="kategori_program" name="kategori_program" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($jenisProgram as $jenis)
                                        <option value="{{ $jenis->nama_kategori }}" {{ old('kategori_program') == $jenis->nama_kategori ? 'selected' : '' }}>
                                            {{ $jenis->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kategori_program')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="jenis_bantuan" class="block text-xs font-medium text-gray-700 mb-2">Jenis Bantuan *</label>
                                <select id="jenis_bantuan" name="jenis_bantuan" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Jenis --</option>
                                    <option value="Tunai" {{ old('jenis_bantuan') == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                                    <option value="Barangan" {{ old('jenis_bantuan') == 'Barangan' ? 'selected' : '' }}>Barangan</option>
                                    <option value="Perkhidmatan" {{ old('jenis_bantuan') == 'Perkhidmatan' ? 'selected' : '' }}>Perkhidmatan</option>
                                    <option value="Campuran" {{ old('jenis_bantuan') == 'Campuran' ? 'selected' : '' }}>Campuran</option>
                                </select>
                                @error('jenis_bantuan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="tempoh_bantuan" class="block text-xs font-medium text-gray-700 mb-2">Tempoh Bantuan *</label>
                                <select id="tempoh_bantuan" name="tempoh_bantuan" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Tempoh --</option>
                                    @foreach($tempohBantuan as $tempoh)
                                        <option value="{{ $tempoh->nama_kategori }}" {{ old('tempoh_bantuan') == $tempoh->nama_kategori ? 'selected' : '' }}>
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
                        <p class="text-[10px] text-gray-500 mb-4">Had bantuan akan divalidasi berdasarkan kategori program yang dipilih mengikut tetapan sistem.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="had_minimum" class="block text-xs font-medium text-gray-700 mb-2">Had Minimum (RM)</label>
                                <input type="number" id="had_minimum" name="had_minimum" value="{{ old('had_minimum') }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                <p class="text-[10px] text-gray-500 mt-1" id="had_min_hint">Pilih kategori program untuk melihat had yang dibenarkan</p>
                                @error('had_minimum')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="had_maksimum" class="block text-xs font-medium text-gray-700 mb-2">Had Maksimum (RM)</label>
                                <input type="number" id="had_maksimum" name="had_maksimum" value="{{ old('had_maksimum') }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                <p class="text-[10px] text-gray-500 mt-1" id="had_max_hint">Pilih kategori program untuk melihat had yang dibenarkan</p>
                                @error('had_maksimum')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <!-- Dynamic hints based on kategori -->
                        <div class="mt-4 bg-blue-100 border border-blue-200 rounded p-3 hidden" id="had_info">
                            <p class="text-xs text-blue-800">
                                <span class="material-icons text-sm align-middle mr-1">info</span>
                                <span id="had_info_text"></span>
                            </p>
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const kategoriSelect = document.getElementById('kategori_program');
                            const hadInfo = document.getElementById('had_info');
                            const hadInfoText = document.getElementById('had_info_text');
                            const hadMinHint = document.getElementById('had_min_hint');
                            const hadMaxHint = document.getElementById('had_max_hint');

                            const limits = {
                                'Pendidikan': { min: '{{ $settings["had_pendidikan_min"] ?? 0 }}', max: '{{ $settings["had_pendidikan_max"] ?? 0 }}' },
                                'Kesihatan': { min: '{{ $settings["had_kesihatan_min"] ?? 0 }}', max: '{{ $settings["had_kesihatan_max"] ?? 0 }}' },
                                'Kecemasan': { min: '{{ $settings["had_kecemasan_min"] ?? 0 }}', max: '{{ $settings["had_kecemasan_max"] ?? 0 }}' },
                                'Kebajikan Am': { min: '{{ $settings["had_kebajikan_min"] ?? 0 }}', max: '{{ $settings["had_kebajikan_max"] ?? 0 }}' }
                            };

                            kategoriSelect.addEventListener('change', function() {
                                const kategori = this.value;
                                
                                if (limits[kategori]) {
                                    const limit = limits[kategori];
                                    const minFormatted = parseFloat(limit.min).toFixed(2);
                                    const maxFormatted = parseFloat(limit.max).toFixed(2);
                                    
                                    hadInfoText.textContent = `Had untuk ${kategori}: Minimum RM ${minFormatted}, Maksimum RM ${maxFormatted}`;
                                    hadMinHint.textContent = `Minimum yang dibenarkan: RM ${minFormatted}`;
                                    hadMaxHint.textContent = `Maksimum yang dibenarkan: RM ${maxFormatted}`;
                                    hadInfo.classList.remove('hidden');
                                } else {
                                    hadInfo.classList.add('hidden');
                                    hadMinHint.textContent = 'Pilih kategori program untuk melihat had yang dibenarkan';
                                    hadMaxHint.textContent = 'Pilih kategori program untuk melihat had yang dibenarkan';
                                }
                            });

                            // Trigger on page load if kategori already selected
                            if (kategoriSelect.value) {
                                kategoriSelect.dispatchEvent(new Event('change'));
                            }
                        });
                    </script>

                    <!-- Section 3: Tempoh Program -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Tempoh Program</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="tarikh_mula" class="block text-xs font-medium text-gray-700 mb-2">Tarikh Mula</label>
                                <input type="date" id="tarikh_mula" name="tarikh_mula" value="{{ old('tarikh_mula') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('tarikh_mula')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="tarikh_tamat" class="block text-xs font-medium text-gray-700 mb-2">Tarikh Tamat</label>
                                <input type="date" id="tarikh_tamat" name="tarikh_tamat" value="{{ old('tarikh_tamat') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('tarikh_tamat')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="status_program" class="block text-xs font-medium text-gray-700 mb-2">Status Program *</label>
                                <select id="status_program" name="status_program" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="Aktif" {{ old('status_program', 'Aktif') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Tidak Aktif" {{ old('status_program') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                    <option value="Tamat" {{ old('status_program') == 'Tamat' ? 'selected' : '' }}>Tamat</option>
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
                                <textarea id="syarat_kelayakan" name="syarat_kelayakan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">{{ old('syarat_kelayakan') }}</textarea>
                                <p class="text-[10px] text-gray-500 mt-1">Senaraikan syarat kelayakan untuk program ini</p>
                                @error('syarat_kelayakan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="dokumen_diperlukan" class="block text-xs font-medium text-gray-700 mb-2">Dokumen Diperlukan</label>
                                <textarea id="dokumen_diperlukan" name="dokumen_diperlukan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">{{ old('dokumen_diperlukan') }}</textarea>
                                <p class="text-[10px] text-gray-500 mt-1">Senaraikan dokumen yang diperlukan untuk permohonan</p>
                                @error('dokumen_diperlukan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="catatan" class="block text-xs font-medium text-gray-700 mb-2">Catatan</label>
                                <textarea id="catatan" name="catatan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">{{ old('catatan') }}</textarea>
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
                            Simpan Program
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
