<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Aset - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Tambah Aset</h1>
                        <p class="text-xs text-gray-600">Daftar aset baharu</p>
                    </div>
                    <a href="{{ route('senarai-aset.index') }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                        <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                        Kembali
                    </a>
                </div>

                @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-xs">
                    {{ session('error') }}
                </div>
                @endif

                @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-xs">
                    <p class="font-semibold mb-2">Sila betulkan ralat berikut:</p>
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('senarai-aset.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Section 1: Maklumat Asas -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">1. Maklumat Asas</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="kategori_aset_id" class="block text-xs font-medium text-gray-700 mb-2">Kategori Aset *</label>
                                <select id="kategori_aset_id" name="kategori_aset_id" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($kategoriAset as $kategori)
                                        <option value="{{ $kategori->id }}" {{ old('kategori_aset_id') == $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->kod_kategori }} - {{ $kategori->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kategori_aset_id')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="kuantiti" class="block text-xs font-medium text-gray-700 mb-2">Kuantiti *</label>
                                <input type="number" id="kuantiti" name="kuantiti" value="{{ old('kuantiti', 1) }}" required min="1" max="1000" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                <p class="text-[10px] text-gray-500 mt-1">Jumlah aset yang sama untuk ditambah sekaligus (max 1000)</p>
                                @error('kuantiti')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="nama_aset" class="block text-xs font-medium text-gray-700 mb-2">Nama Aset *</label>
                                <input type="text" id="nama_aset" name="nama_aset" value="{{ old('nama_aset') }}" required maxlength="255" placeholder="Contoh: Kerusi Plastik, Meja Lipat, Kipas Angin" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                <p class="text-[10px] text-gray-500 mt-1">Jika kuantiti > 1, nombor akan ditambah automatik (contoh: Kerusi #1, Kerusi #2)</p>
                                @error('nama_aset')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="kod_aset_prefix" class="block text-xs font-medium text-gray-700 mb-2">Kod Aset (Prefix) *</label>
                                <div class="flex">
                                    <input type="text" id="kod_aset_prefix" name="kod_aset_prefix" value="{{ old('kod_aset_prefix') }}" required maxlength="50" placeholder="Contoh: PUTRA-KERUSI-2025-" class="flex-1 px-3 py-2 border border-gray-300 rounded-l-sm text-xs uppercase">
                                    <span class="px-3 py-2 bg-green-100 border border-l-0 border-gray-300 rounded-r-sm text-xs text-green-700 font-medium">0001</span>
                                </div>
                                <p class="text-[10px] text-gray-500 mt-1">Masukkan prefix kod aset. Nombor di belakang akan dijana automatik oleh sistem.</p>
                                @error('kod_aset_prefix')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="jenis_aset" class="block text-xs font-medium text-gray-700 mb-2">Jenis Aset</label>
                                <input type="text" id="jenis_aset" name="jenis_aset" value="{{ old('jenis_aset') }}" maxlength="255" placeholder="Contoh: Kerusi Lipat, Meja Bulat, Kipas Dinding" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Maklumat Perolehan -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">2. Maklumat Perolehan</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="tarikh_perolehan" class="block text-xs font-medium text-gray-700 mb-2">Tarikh Perolehan *</label>
                                <input type="date" id="tarikh_perolehan" name="tarikh_perolehan" value="{{ old('tarikh_perolehan') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('tarikh_perolehan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="cara_perolehan" class="block text-xs font-medium text-gray-700 mb-2">Cara Perolehan *</label>
                                <select id="cara_perolehan" name="cara_perolehan" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Cara --</option>
                                    <option value="Pembelian" {{ old('cara_perolehan') == 'Pembelian' ? 'selected' : '' }}>Pembelian</option>
                                    <option value="Derma" {{ old('cara_perolehan') == 'Derma' ? 'selected' : '' }}>Derma</option>
                                    <option value="Hibah" {{ old('cara_perolehan') == 'Hibah' ? 'selected' : '' }}>Hibah</option>
                                    <option value="Wakaf" {{ old('cara_perolehan') == 'Wakaf' ? 'selected' : '' }}>Wakaf</option>
                                    <option value="Pinjaman" {{ old('cara_perolehan') == 'Pinjaman' ? 'selected' : '' }}>Pinjaman</option>
                                    <option value="Lain-lain" {{ old('cara_perolehan') == 'Lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                                </select>
                                @error('cara_perolehan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="pembekal" class="block text-xs font-medium text-gray-700 mb-2">Pembekal</label>
                                <input type="text" id="pembekal" name="pembekal" value="{{ old('pembekal') }}" maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div>
                                <label for="no_invois" class="block text-xs font-medium text-gray-700 mb-2">No. Invois</label>
                                <input type="text" id="no_invois" name="no_invois" value="{{ old('no_invois') }}" maxlength="100" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div>
                                <label for="harga_per_unit" class="block text-xs font-medium text-gray-700 mb-2">Harga Per Unit (RM) *</label>
                                <input type="number" id="harga_per_unit" name="harga_per_unit" value="{{ old('harga_per_unit') }}" required step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs" onchange="calculateTotalHarga()">
                                <p class="text-[10px] text-gray-500 mt-1">Harga untuk satu unit aset</p>
                                @error('harga_per_unit')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="harga_perolehan" class="block text-xs font-medium text-gray-700 mb-2">Jumlah Harga Perolehan (RM)</label>
                                <input type="number" id="harga_perolehan" name="harga_perolehan" value="{{ old('harga_perolehan') }}" readonly step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs bg-gray-100 font-semibold">
                                <p class="text-[10px] text-gray-500 mt-1">Auto-kira: Harga Per Unit × Kuantiti</p>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Spesifikasi Aset -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">3. Spesifikasi Aset</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="jenama" class="block text-xs font-medium text-gray-700 mb-2">Jenama</label>
                                <input type="text" id="jenama" name="jenama" value="{{ old('jenama') }}" maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div>
                                <label for="model" class="block text-xs font-medium text-gray-700 mb-2">Model</label>
                                <input type="text" id="model" name="model" value="{{ old('model') }}" maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div>
                                <label for="no_siri" class="block text-xs font-medium text-gray-700 mb-2">No. Siri</label>
                                <input type="text" id="no_siri" name="no_siri" value="{{ old('no_siri') }}" maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div>
                                <label for="warna" class="block text-xs font-medium text-gray-700 mb-2">Warna</label>
                                <input type="text" id="warna" name="warna" value="{{ old('warna') }}" maxlength="100" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div>
                                <label for="saiz" class="block text-xs font-medium text-gray-700 mb-2">Saiz</label>
                                <input type="text" id="saiz" name="saiz" value="{{ old('saiz') }}" maxlength="100" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div class="md:col-span-2">
                                <label for="spesifikasi" class="block text-xs font-medium text-gray-700 mb-2">Spesifikasi Terperinci</label>
                                <textarea id="spesifikasi" name="spesifikasi" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">{{ old('spesifikasi') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Lokasi -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">4. Lokasi</h2>
                        
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="lokasi_semasa" class="block text-xs font-medium text-gray-700 mb-2">Lokasi Semasa *</label>
                                <input type="text" id="lokasi_semasa" name="lokasi_semasa" value="{{ old('lokasi_semasa') }}" required maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                <p class="text-[10px] text-gray-500 mt-1">Contoh: Dewan Utama, Pejabat, Stor</p>
                                @error('lokasi_semasa')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="lokasi_terperinci" class="block text-xs font-medium text-gray-700 mb-2">Lokasi Terperinci</label>
                                <textarea id="lokasi_terperinci" name="lokasi_terperinci" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">{{ old('lokasi_terperinci') }}</textarea>
                                <p class="text-[10px] text-gray-500 mt-1">Maklumat tambahan lokasi (contoh: Rak 3, Tingkat 2)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Section 5: Jaminan & Insurans -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">5. Jaminan & Insurans</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="tempoh_jaminan" class="block text-xs font-medium text-gray-700 mb-2">Tempoh Jaminan (Bulan)</label>
                                <input type="number" id="tempoh_jaminan" name="tempoh_jaminan" value="{{ old('tempoh_jaminan') }}" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div>
                                <label for="no_polisi_insurans" class="block text-xs font-medium text-gray-700 mb-2">No. Polisi Insurans</label>
                                <input type="text" id="no_polisi_insurans" name="no_polisi_insurans" value="{{ old('no_polisi_insurans') }}" maxlength="100" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div>
                                <label for="syarikat_insurans" class="block text-xs font-medium text-gray-700 mb-2">Syarikat Insurans</label>
                                <input type="text" id="syarikat_insurans" name="syarikat_insurans" value="{{ old('syarikat_insurans') }}" maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div>
                                <label for="tarikh_tamat_insurans" class="block text-xs font-medium text-gray-700 mb-2">Tarikh Tamat Insurans</label>
                                <input type="date" id="tarikh_tamat_insurans" name="tarikh_tamat_insurans" value="{{ old('tarikh_tamat_insurans') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>
                        </div>
                    </div>

                    <!-- Section 6: Status & Kondisi -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">6. Status & Kondisi</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="status_aset" class="block text-xs font-medium text-gray-700 mb-2">Status Aset *</label>
                                <select id="status_aset" name="status_aset" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="Aktif" {{ old('status_aset', 'Aktif') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Dalam Penyelenggaraan" {{ old('status_aset') == 'Dalam Penyelenggaraan' ? 'selected' : '' }}>Dalam Penyelenggaraan</option>
                                    <option value="Rosak" {{ old('status_aset') == 'Rosak' ? 'selected' : '' }}>Rosak</option>
                                    <option value="Dilupuskan" {{ old('status_aset') == 'Dilupuskan' ? 'selected' : '' }}>Dilupuskan</option>
                                    <option value="Hilang" {{ old('status_aset') == 'Hilang' ? 'selected' : '' }}>Hilang</option>
                                    <option value="Dipinjam" {{ old('status_aset') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                    <option value="Disewa" {{ old('status_aset') == 'Disewa' ? 'selected' : '' }}>Disewa</option>
                                </select>
                                @error('status_aset')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="kondisi_aset" class="block text-xs font-medium text-gray-700 mb-2">Kondisi Aset *</label>
                                <select id="kondisi_aset" name="kondisi_aset" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="Baru" {{ old('kondisi_aset', 'Baru') == 'Baru' ? 'selected' : '' }}>Baru</option>
                                    <option value="Baik" {{ old('kondisi_aset') == 'Baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="Sederhana" {{ old('kondisi_aset') == 'Sederhana' ? 'selected' : '' }}>Sederhana</option>
                                    <option value="Teruk" {{ old('kondisi_aset') == 'Teruk' ? 'selected' : '' }}>Teruk</option>
                                    <option value="Rosak" {{ old('kondisi_aset') == 'Rosak' ? 'selected' : '' }}>Rosak</option>
                                </select>
                                @error('kondisi_aset')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="catatan" class="block text-xs font-medium text-gray-700 mb-2">Catatan</label>
                                <textarea id="catatan" name="catatan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">{{ old('catatan') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Section 7: Gambar & Dokumen (Optional) -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">7. Gambar & Dokumen (Pilihan)</h2>
                        <p class="text-[10px] text-gray-600 mb-4">Semua fail adalah pilihan. Max 5MB, format: JPG, PNG, PDF</p>
                        
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="gambar_aset" class="block text-xs font-medium text-gray-700 mb-2">Gambar Aset</label>
                                <input type="file" id="gambar_aset" name="gambar_aset[]" multiple accept="image/jpeg,image/png,image/jpg" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                <p class="text-[10px] text-gray-500 mt-1">Boleh pilih beberapa gambar (max 5 gambar)</p>
                                @error('gambar_aset')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                                @error('gambar_aset.*')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="invois_path" class="block text-xs font-medium text-gray-700 mb-2">Invois/Resit</label>
                                <input type="file" id="invois_path" name="invois_path" accept="application/pdf,image/jpeg,image/png,image/jpg" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                <p class="text-[10px] text-gray-500 mt-1">Format: PDF, JPG, PNG</p>
                                @error('invois_path')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="warranty_card_path" class="block text-xs font-medium text-gray-700 mb-2">Kad Jaminan</label>
                                <input type="file" id="warranty_card_path" name="warranty_card_path" accept="application/pdf,image/jpeg,image/png,image/jpg" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                <p class="text-[10px] text-gray-500 mt-1">Format: PDF, JPG, PNG</p>
                                @error('warranty_card_path')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="manual_path" class="block text-xs font-medium text-gray-700 mb-2">Manual Pengguna</label>
                                <input type="file" id="manual_path" name="manual_path" accept="application/pdf" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                <p class="text-[10px] text-gray-500 mt-1">Format: PDF</p>
                                @error('manual_path')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="insurans_path" class="block text-xs font-medium text-gray-700 mb-2">Dokumen Insurans</label>
                                <input type="file" id="insurans_path" name="insurans_path" accept="application/pdf,image/jpeg,image/png,image/jpg" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                <p class="text-[10px] text-gray-500 mt-1">Format: PDF, JPG, PNG</p>
                                @error('insurans_path')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="dokumen_lain" class="block text-xs font-medium text-gray-700 mb-2">Dokumen Lain</label>
                                <input type="file" id="dokumen_lain" name="dokumen_lain[]" multiple accept="application/pdf,image/jpeg,image/png,image/jpg" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                <p class="text-[10px] text-gray-500 mt-1">Boleh pilih beberapa fail (max 5 fail)</p>
                                @error('dokumen_lain')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                                @error('dokumen_lain.*')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('senarai-aset.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                            Simpan Aset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <x-footer />

    <script>
        // Calculate total harga based on kuantiti and harga per unit
        function calculateTotalHarga() {
            const kuantiti = parseInt(document.getElementById('kuantiti').value) || 1;
            const hargaPerUnit = parseFloat(document.getElementById('harga_per_unit').value) || 0;
            const totalHarga = kuantiti * hargaPerUnit;
            document.getElementById('harga_perolehan').value = totalHarga.toFixed(2);
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Add event listener for kuantiti change
            document.getElementById('kuantiti').addEventListener('change', calculateTotalHarga);
            document.getElementById('kuantiti').addEventListener('input', calculateTotalHarga);
            
            // Initial calculation
            calculateTotalHarga();
        });
    </script>
</body>
</html>
