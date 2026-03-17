<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Fasiliti - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Tambah Fasiliti</h1>
                        <p class="text-xs text-gray-600">Daftar fasiliti baharu</p>
                    </div>
                    <a href="{{ route('senarai-fasiliti.index') }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                        <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                        Kembali
                    </a>
                </div>

                <form method="POST" action="{{ route('senarai-fasiliti.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Section 1: Maklumat Fasiliti -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">1. Maklumat Fasiliti</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="nama_fasiliti" class="block text-xs font-medium text-gray-700 mb-2">Nama Fasiliti *</label>
                                <input type="text" id="nama_fasiliti" name="nama_fasiliti" value="{{ old('nama_fasiliti') }}" required maxlength="255" placeholder="Contoh: Dewan Utama, Bilik Mesyuarat" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('nama_fasiliti')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="jenis_fasiliti" class="block text-xs font-medium text-gray-700 mb-2">Jenis Fasiliti *</label>
                                <select id="jenis_fasiliti" name="jenis_fasiliti" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Jenis --</option>
                                    <option value="Dewan" {{ old('jenis_fasiliti') == 'Dewan' ? 'selected' : '' }}>Dewan</option>
                                    <option value="Bilik" {{ old('jenis_fasiliti') == 'Bilik' ? 'selected' : '' }}>Bilik</option>
                                    <option value="Padang" {{ old('jenis_fasiliti') == 'Padang' ? 'selected' : '' }}>Padang</option>
                                    <option value="Tempat Letak Kereta" {{ old('jenis_fasiliti') == 'Tempat Letak Kereta' ? 'selected' : '' }}>Tempat Letak Kereta</option>
                                    <option value="Aset" {{ old('jenis_fasiliti') == 'Aset' ? 'selected' : '' }}>Aset</option>
                                    <option value="Lain-lain" {{ old('jenis_fasiliti') == 'Lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                                </select>
                                @error('jenis_fasiliti')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="kategori_fasiliti" class="block text-xs font-medium text-gray-700 mb-2">Kategori Fasiliti</label>
                                <input type="text" id="kategori_fasiliti" name="kategori_fasiliti" value="{{ old('kategori_fasiliti') }}" maxlength="255" placeholder="Contoh: Dewan Besar, Bilik VIP" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div id="aset-field" style="display: none;">
                                <label for="senarai_aset_id" class="block text-xs font-medium text-gray-700 mb-2">Aset Berkaitan</label>
                                <select id="senarai_aset_id" name="senarai_aset_id" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Aset --</option>
                                    @foreach($senariAset as $aset)
                                        <option value="{{ $aset->id }}" {{ old('senarai_aset_id') == $aset->id ? 'selected' : '' }}>
                                            {{ $aset->no_aset }} - {{ $aset->nama_aset }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-[10px] text-gray-500 mt-1">Pilih aset jika jenis fasiliti adalah Aset</p>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Kapasiti & Spesifikasi -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">2. Kapasiti & Spesifikasi</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="kapasiti_maksimum" class="block text-xs font-medium text-gray-700 mb-2">Kapasiti Maksimum (orang)</label>
                                <input type="number" id="kapasiti_maksimum" name="kapasiti_maksimum" value="{{ old('kapasiti_maksimum') }}" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div>
                                <label for="luas_kawasan" class="block text-xs font-medium text-gray-700 mb-2">Luas Kawasan</label>
                                <input type="text" id="luas_kawasan" name="luas_kawasan" value="{{ old('luas_kawasan') }}" maxlength="100" placeholder="Contoh: 500 kaki persegi" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div class="md:col-span-2">
                                <label for="kemudahan" class="block text-xs font-medium text-gray-700 mb-2">Kemudahan</label>
                                <textarea id="kemudahan" name="kemudahan" rows="3" placeholder="Contoh: Penghawa dingin, Projector, Sound system" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">{{ old('kemudahan') }}</textarea>
                            </div>

                            <div class="md:col-span-2">
                                <label for="spesifikasi" class="block text-xs font-medium text-gray-700 mb-2">Spesifikasi Terperinci</label>
                                <textarea id="spesifikasi" name="spesifikasi" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">{{ old('spesifikasi') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Harga Sewa -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">3. Harga Sewa</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="harga_sewa_sejam" class="block text-xs font-medium text-gray-700 mb-2">Harga Sewa Sejam (RM)</label>
                                <input type="number" id="harga_sewa_sejam" name="harga_sewa_sejam" value="{{ old('harga_sewa_sejam') }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div>
                                <label for="harga_sewa_separuh_hari" class="block text-xs font-medium text-gray-700 mb-2">Harga Sewa Separuh Hari (RM)</label>
                                <input type="number" id="harga_sewa_separuh_hari" name="harga_sewa_separuh_hari" value="{{ old('harga_sewa_separuh_hari') }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div>
                                <label for="harga_sewa_sehari" class="block text-xs font-medium text-gray-700 mb-2">Harga Sewa Sehari (RM)</label>
                                <input type="number" id="harga_sewa_sehari" name="harga_sewa_sehari" value="{{ old('harga_sewa_sehari') }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div>
                                <label for="deposit_diperlukan" class="block text-xs font-medium text-gray-700 mb-2">Deposit Diperlukan (RM)</label>
                                <input type="number" id="deposit_diperlukan" name="deposit_diperlukan" value="{{ old('deposit_diperlukan') }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Syarat & Peraturan -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">4. Syarat & Peraturan</h2>
                        
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="syarat_tempahan" class="block text-xs font-medium text-gray-700 mb-2">Syarat Tempahan</label>
                                <textarea id="syarat_tempahan" name="syarat_tempahan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">{{ old('syarat_tempahan') }}</textarea>
                            </div>

                            <div>
                                <label for="peraturan_penggunaan" class="block text-xs font-medium text-gray-700 mb-2">Peraturan Penggunaan</label>
                                <textarea id="peraturan_penggunaan" name="peraturan_penggunaan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">{{ old('peraturan_penggunaan') }}</textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="had_minimum_tempahan" class="block text-xs font-medium text-gray-700 mb-2">Had Minimum Tempahan (jam)</label>
                                    <input type="number" id="had_minimum_tempahan" name="had_minimum_tempahan" value="{{ old('had_minimum_tempahan') }}" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                </div>

                                <div>
                                    <label for="had_maksimum_tempahan" class="block text-xs font-medium text-gray-700 mb-2">Had Maksimum Tempahan (jam)</label>
                                    <input type="number" id="had_maksimum_tempahan" name="had_maksimum_tempahan" value="{{ old('had_maksimum_tempahan') }}" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 5: Gambar & Dokumen (Optional) -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">5. Gambar & Dokumen (Pilihan)</h2>
                        <p class="text-[10px] text-gray-600 mb-4">Semua fail adalah pilihan. Max 5MB, format: JPG, PNG, PDF</p>
                        
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="gambar_fasiliti" class="block text-xs font-medium text-gray-700 mb-2">Gambar Fasiliti</label>
                                <input type="file" id="gambar_fasiliti" name="gambar_fasiliti[]" multiple accept="image/jpeg,image/png,image/jpg" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                <p class="text-[10px] text-gray-500 mt-1">Boleh pilih beberapa gambar (max 5 gambar)</p>
                            </div>

                            <div>
                                <label for="dokumen_peraturan" class="block text-xs font-medium text-gray-700 mb-2">Dokumen Peraturan</label>
                                <input type="file" id="dokumen_peraturan" name="dokumen_peraturan" accept="application/pdf" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                <p class="text-[10px] text-gray-500 mt-1">Format: PDF</p>
                            </div>
                        </div>
                    </div>

                    <!-- Section 6: Status & Catatan -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">6. Status & Catatan</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="status_fasiliti" class="block text-xs font-medium text-gray-700 mb-2">Status Fasiliti *</label>
                                <select id="status_fasiliti" name="status_fasiliti" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="Tersedia" {{ old('status_fasiliti', 'Tersedia') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                                    <option value="Tidak Tersedia" {{ old('status_fasiliti') == 'Tidak Tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                                    <option value="Dalam Penyelenggaraan" {{ old('status_fasiliti') == 'Dalam Penyelenggaraan' ? 'selected' : '' }}>Dalam Penyelenggaraan</option>
                                </select>
                                @error('status_fasiliti')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="catatan" class="block text-xs font-medium text-gray-700 mb-2">Catatan</label>
                                <textarea id="catatan" name="catatan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">{{ old('catatan') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('senarai-fasiliti.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                            Simpan Fasiliti
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <x-footer />

    <script>
        // Show/hide aset field based on jenis_fasiliti
        document.getElementById('jenis_fasiliti').addEventListener('change', function() {
            const asetField = document.getElementById('aset-field');
            if (this.value === 'Aset') {
                asetField.style.display = 'block';
            } else {
                asetField.style.display = 'none';
                document.getElementById('senarai_aset_id').value = '';
            }
        });

        // Trigger on page load
        if (document.getElementById('jenis_fasiliti').value === 'Aset') {
            document.getElementById('aset-field').style.display = 'block';
        }
    </script>
</body>
</html>
