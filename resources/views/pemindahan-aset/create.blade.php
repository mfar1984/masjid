<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pemindahan Aset - E-Masjid</title>
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
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Tambah Pemindahan Aset</h1>
                        <p class="text-xs text-gray-600">Rekod pemindahan aset ke lokasi baru</p>
                    </div>
                    <a href="{{ route('pemindahan-aset.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white text-xs rounded hover:bg-gray-700">
                        <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                        Kembali
                    </a>
                </div>

                <!-- Form -->
                <form action="{{ route('pemindahan-aset.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Aset -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Aset <span class="text-red-500">*</span></label>
                            <select name="senarai_aset_id" id="senarai_aset_id" required class="w-full px-3 py-2 border border-gray-300 rounded text-xs @error('senarai_aset_id') border-red-500 @enderror">
                                <option value="">-- Pilih Aset --</option>
                                @foreach($senariAset as $aset)
                                    <option value="{{ $aset->id }}" data-lokasi="{{ $aset->lokasi_semasa }}" {{ old('senarai_aset_id') == $aset->id ? 'selected' : '' }}>
                                        {{ $aset->nama_aset }} ({{ $aset->kategoriAset->nama_kategori ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('senarai_aset_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tarikh Pemindahan -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Pemindahan <span class="text-red-500">*</span></label>
                            <input type="date" name="tarikh_pergerakan" value="{{ old('tarikh_pergerakan', date('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-300 rounded text-xs @error('tarikh_pergerakan') border-red-500 @enderror">
                            @error('tarikh_pergerakan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jenis Pemindahan -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Jenis Pemindahan <span class="text-red-500">*</span></label>
                            <select name="jenis_pergerakan" required class="w-full px-3 py-2 border border-gray-300 rounded text-xs @error('jenis_pergerakan') border-red-500 @enderror">
                                <option value="Pemindahan Dalaman" {{ old('jenis_pergerakan') == 'Pemindahan Dalaman' ? 'selected' : '' }}>Dalaman (Dalam Kawasan Masjid)</option>
                                <option value="Pemindahan Luaran" {{ old('jenis_pergerakan') == 'Pemindahan Luaran' ? 'selected' : '' }}>Luaran (Luar Kawasan Masjid)</option>
                            </select>
                            @error('jenis_pergerakan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Lokasi Asal (Auto-filled) -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Lokasi Asal</label>
                            <input type="text" id="lokasi_asal_display" readonly class="w-full px-3 py-2 border border-gray-200 rounded text-xs bg-gray-100 text-gray-600" placeholder="Pilih aset untuk melihat lokasi asal">
                        </div>

                        <!-- Lokasi Destinasi -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Lokasi Baru <span class="text-red-500">*</span></label>
                            <input type="text" name="lokasi_destinasi" value="{{ old('lokasi_destinasi') }}" required list="lokasi_list" class="w-full px-3 py-2 border border-gray-300 rounded text-xs @error('lokasi_destinasi') border-red-500 @enderror" placeholder="Masukkan atau pilih lokasi baru">
                            <datalist id="lokasi_list">
                                @foreach($lokasiList as $lokasi)
                                    <option value="{{ $lokasi }}">
                                @endforeach
                            </datalist>
                            @error('lokasi_destinasi')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sebab Pemindahan -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Sebab Pemindahan <span class="text-red-500">*</span></label>
                            <textarea name="sebab_pergerakan" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded text-xs @error('sebab_pergerakan') border-red-500 @enderror" placeholder="Nyatakan sebab pemindahan aset">{{ old('sebab_pergerakan') }}</textarea>
                            @error('sebab_pergerakan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Catatan -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Catatan</label>
                            <textarea name="catatan" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded text-xs @error('catatan') border-red-500 @enderror" placeholder="Catatan tambahan (pilihan)">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                        <a href="{{ route('pemindahan-aset.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                            <span class="material-icons mr-1" style="font-size: 14px !important; vertical-align: middle;">save</span>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <x-footer />

    <script>
        document.getElementById('senarai_aset_id').addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const lokasi = selected.getAttribute('data-lokasi') || '-';
            document.getElementById('lokasi_asal_display').value = lokasi;
        });

        // Trigger on page load if value exists
        document.addEventListener('DOMContentLoaded', function() {
            const select = document.getElementById('senarai_aset_id');
            if (select.value) {
                select.dispatchEvent(new Event('change'));
            }
        });
    </script>
</body>
</html>
