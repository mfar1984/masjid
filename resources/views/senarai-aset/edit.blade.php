<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Aset - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Edit Aset</h1>
                        <p class="text-xs text-gray-600">{{ $senariAset->no_aset }} - {{ $senariAset->nama_aset }}</p>
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

                <form method="POST" action="{{ route('senarai-aset.update', $senariAset) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Section 1: Maklumat Asas -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">1. Maklumat Asas</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="kategori_aset_id" class="block text-xs font-medium text-gray-700 mb-2">Kategori Aset *</label>
                                <select id="kategori_aset_id" name="kategori_aset_id" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($kategoriAset as $kategori)
                                        <option value="{{ $kategori->id }}" {{ old('kategori_aset_id', $senariAset->kategori_aset_id) == $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->kod_kategori }} - {{ $kategori->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kategori_aset_id')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Kod Aset</label>
                                <input type="text" value="{{ $senariAset->kod_aset ?: $senariAset->no_aset }}" readonly class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs bg-blue-50 text-blue-700 font-medium">
                                <p class="text-[10px] text-gray-500 mt-1">Kod aset tidak boleh diubah</p>
                            </div>

                            <div>
                                <label for="nama_aset" class="block text-xs font-medium text-gray-700 mb-2">Nama Aset *</label>
                                <input type="text" id="nama_aset" name="nama_aset" value="{{ old('nama_aset', $senariAset->nama_aset) }}" required maxlength="255" placeholder="Contoh: Kerusi Plastik, Meja Lipat, Kipas Angin" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('nama_aset')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="jenis_aset" class="block text-xs font-medium text-gray-700 mb-2">Jenis Aset</label>
                                <input type="text" id="jenis_aset" name="jenis_aset" value="{{ old('jenis_aset', $senariAset->jenis_aset) }}" maxlength="255" placeholder="Contoh: Kerusi Lipat, Meja Bulat, Kipas Dinding" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Maklumat Perolehan -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">2. Maklumat Perolehan</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="tarikh_perolehan" class="block text-xs font-medium text-gray-700 mb-2">Tarikh Perolehan *</label>
                                <input type="date" id="tarikh_perolehan" name="tarikh_perolehan" value="{{ old('tarikh_perolehan', $senariAset->tarikh_perolehan ? $senariAset->tarikh_perolehan->format('Y-m-d') : '') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('tarikh_perolehan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="cara_perolehan" class="block text-xs font-medium text-gray-700 mb-2">Cara Perolehan *</label>
                                <select id="cara_perolehan" name="cara_perolehan" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Cara --</option>
                                    <option value="Pembelian" {{ old('cara_perolehan', $senariAset->cara_perolehan) == 'Pembelian' ? 'selected' : '' }}>Pembelian</option>
                                    <option value="Derma" {{ old('cara_perolehan', $senariAset->cara_perolehan) == 'Derma' ? 'selected' : '' }}>Derma</option>
                                    <option value="Hibah" {{ old('cara_perolehan', $senariAset->cara_perolehan) == 'Hibah' ? 'selected' : '' }}>Hibah</option>
                                    <option value="Wakaf" {{ old('cara_perolehan', $senariAset->cara_perolehan) == 'Wakaf' ? 'selected' : '' }}>Wakaf</option>
                                    <option value="Pinjaman" {{ old('cara_perolehan', $senariAset->cara_perolehan) == 'Pinjaman' ? 'selected' : '' }}>Pinjaman</option>
                                    <option value="Lain-lain" {{ old('cara_perolehan', $senariAset->cara_perolehan) == 'Lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                                </select>
                                @error('cara_perolehan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="pembekal" class="block text-xs font-medium text-gray-700 mb-2">Pembekal</label>
                                <input type="text" id="pembekal" name="pembekal" value="{{ old('pembekal', $senariAset->pembekal) }}" maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div>
                                <label for="no_invois" class="block text-xs font-medium text-gray-700 mb-2">No. Invois</label>
                                <input type="text" id="no_invois" name="no_invois" value="{{ old('no_invois', $senariAset->no_invois) }}" maxlength="100" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div>
                                <label for="harga_perolehan" class="block text-xs font-medium text-gray-700 mb-2">Harga Perolehan (RM) *</label>
                                <input type="number" id="harga_perolehan" name="harga_perolehan" value="{{ old('harga_perolehan', $senariAset->harga_perolehan) }}" required step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                <p class="text-[10px] text-gray-500 mt-1">Harga perolehan untuk aset ini</p>
                                @error('harga_perolehan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Spesifikasi Aset -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">3. Spesifikasi Aset</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="jenama" class="block text-xs font-medium text-gray-700 mb-2">Jenama</label>
                                <input type="text" id="jenama" name="jenama" value="{{ old('jenama', $senariAset->jenama) }}" maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div>
                                <label for="model" class="block text-xs font-medium text-gray-700 mb-2">Model</label>
                                <input type="text" id="model" name="model" value="{{ old('model', $senariAset->model) }}" maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div>
                                <label for="no_siri" class="block text-xs font-medium text-gray-700 mb-2">No. Siri</label>
                                <input type="text" id="no_siri" name="no_siri" value="{{ old('no_siri', $senariAset->no_siri) }}" maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div>
                                <label for="warna" class="block text-xs font-medium text-gray-700 mb-2">Warna</label>
                                <input type="text" id="warna" name="warna" value="{{ old('warna', $senariAset->warna) }}" maxlength="100" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div>
                                <label for="saiz" class="block text-xs font-medium text-gray-700 mb-2">Saiz</label>
                                <input type="text" id="saiz" name="saiz" value="{{ old('saiz', $senariAset->saiz) }}" maxlength="100" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div class="md:col-span-2">
                                <label for="spesifikasi" class="block text-xs font-medium text-gray-700 mb-2">Spesifikasi Terperinci</label>
                                <textarea id="spesifikasi" name="spesifikasi" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">{{ old('spesifikasi', $senariAset->spesifikasi) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Lokasi -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">4. Lokasi</h2>
                        
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="lokasi_semasa" class="block text-xs font-medium text-gray-700 mb-2">Lokasi Semasa *</label>
                                <input type="text" id="lokasi_semasa" name="lokasi_semasa" value="{{ old('lokasi_semasa', $senariAset->lokasi_semasa) }}" required maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                <p class="text-[10px] text-gray-500 mt-1">Contoh: Dewan Utama, Pejabat, Stor</p>
                                @error('lokasi_semasa')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="lokasi_terperinci" class="block text-xs font-medium text-gray-700 mb-2">Lokasi Terperinci</label>
                                <textarea id="lokasi_terperinci" name="lokasi_terperinci" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">{{ old('lokasi_terperinci', $senariAset->lokasi_terperinci) }}</textarea>
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
                                <input type="number" id="tempoh_jaminan" name="tempoh_jaminan" value="{{ old('tempoh_jaminan', $senariAset->tempoh_jaminan) }}" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div>
                                <label for="no_polisi_insurans" class="block text-xs font-medium text-gray-700 mb-2">No. Polisi Insurans</label>
                                <input type="text" id="no_polisi_insurans" name="no_polisi_insurans" value="{{ old('no_polisi_insurans', $senariAset->no_polisi_insurans) }}" maxlength="100" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div>
                                <label for="syarikat_insurans" class="block text-xs font-medium text-gray-700 mb-2">Syarikat Insurans</label>
                                <input type="text" id="syarikat_insurans" name="syarikat_insurans" value="{{ old('syarikat_insurans', $senariAset->syarikat_insurans) }}" maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div>
                                <label for="tarikh_tamat_insurans" class="block text-xs font-medium text-gray-700 mb-2">Tarikh Tamat Insurans</label>
                                <input type="date" id="tarikh_tamat_insurans" name="tarikh_tamat_insurans" value="{{ old('tarikh_tamat_insurans', $senariAset->tarikh_tamat_insurans ? $senariAset->tarikh_tamat_insurans->format('Y-m-d') : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
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
                                    <option value="Aktif" {{ old('status_aset', $senariAset->status_aset) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Dalam Penyelenggaraan" {{ old('status_aset', $senariAset->status_aset) == 'Dalam Penyelenggaraan' ? 'selected' : '' }}>Dalam Penyelenggaraan</option>
                                    <option value="Rosak" {{ old('status_aset', $senariAset->status_aset) == 'Rosak' ? 'selected' : '' }}>Rosak</option>
                                    <option value="Dilupuskan" {{ old('status_aset', $senariAset->status_aset) == 'Dilupuskan' ? 'selected' : '' }}>Dilupuskan</option>
                                    <option value="Hilang" {{ old('status_aset', $senariAset->status_aset) == 'Hilang' ? 'selected' : '' }}>Hilang</option>
                                    <option value="Dipinjam" {{ old('status_aset', $senariAset->status_aset) == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                    <option value="Disewa" {{ old('status_aset', $senariAset->status_aset) == 'Disewa' ? 'selected' : '' }}>Disewa</option>
                                </select>
                                @error('status_aset')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="kondisi_aset" class="block text-xs font-medium text-gray-700 mb-2">Kondisi Aset *</label>
                                <select id="kondisi_aset" name="kondisi_aset" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="Baru" {{ old('kondisi_aset', $senariAset->kondisi_aset) == 'Baru' ? 'selected' : '' }}>Baru</option>
                                    <option value="Baik" {{ old('kondisi_aset', $senariAset->kondisi_aset) == 'Baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="Sederhana" {{ old('kondisi_aset', $senariAset->kondisi_aset) == 'Sederhana' ? 'selected' : '' }}>Sederhana</option>
                                    <option value="Teruk" {{ old('kondisi_aset', $senariAset->kondisi_aset) == 'Teruk' ? 'selected' : '' }}>Teruk</option>
                                    <option value="Rosak" {{ old('kondisi_aset', $senariAset->kondisi_aset) == 'Rosak' ? 'selected' : '' }}>Rosak</option>
                                </select>
                                @error('kondisi_aset')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="catatan" class="block text-xs font-medium text-gray-700 mb-2">Catatan</label>
                                <textarea id="catatan" name="catatan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">{{ old('catatan', $senariAset->catatan) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Section 7: Gambar & Dokumen Sedia Ada -->
                    @if($senariAset->gambar_aset || $senariAset->invois_path || $senariAset->warranty_card_path || $senariAset->manual_path || $senariAset->insurans_path || $senariAset->dokumen_lain)
                    <div class="bg-green-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">7. Dokumen Sedia Ada</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($senariAset->gambar_aset)
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Gambar Aset</label>
                                <div class="flex flex-wrap gap-2">
                                    @foreach(json_decode($senariAset->gambar_aset, true) ?? [] as $gambar)
                                    <a href="{{ asset('storage/' . $gambar) }}" target="_blank" class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded hover:bg-blue-200">
                                        <span class="material-icons mr-1" style="font-size: 14px;">image</span>
                                        Lihat
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            @if($senariAset->invois_path)
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Invois/Resit</label>
                                <a href="{{ asset('storage/' . $senariAset->invois_path) }}" target="_blank" class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded hover:bg-blue-200">
                                    <span class="material-icons mr-1" style="font-size: 14px;">description</span>
                                    Lihat Invois
                                </a>
                            </div>
                            @endif

                            @if($senariAset->warranty_card_path)
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Kad Jaminan</label>
                                <a href="{{ asset('storage/' . $senariAset->warranty_card_path) }}" target="_blank" class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded hover:bg-blue-200">
                                    <span class="material-icons mr-1" style="font-size: 14px;">verified</span>
                                    Lihat Kad Jaminan
                                </a>
                            </div>
                            @endif

                            @if($senariAset->manual_path)
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Manual Pengguna</label>
                                <a href="{{ asset('storage/' . $senariAset->manual_path) }}" target="_blank" class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded hover:bg-blue-200">
                                    <span class="material-icons mr-1" style="font-size: 14px;">menu_book</span>
                                    Lihat Manual
                                </a>
                            </div>
                            @endif

                            @if($senariAset->insurans_path)
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Dokumen Insurans</label>
                                <a href="{{ asset('storage/' . $senariAset->insurans_path) }}" target="_blank" class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded hover:bg-blue-200">
                                    <span class="material-icons mr-1" style="font-size: 14px;">security</span>
                                    Lihat Insurans
                                </a>
                            </div>
                            @endif

                            @if($senariAset->dokumen_lain)
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Dokumen Lain</label>
                                <div class="flex flex-wrap gap-2">
                                    @foreach(json_decode($senariAset->dokumen_lain, true) ?? [] as $index => $dokumen)
                                    <a href="{{ asset('storage/' . $dokumen) }}" target="_blank" class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded hover:bg-blue-200">
                                        <span class="material-icons mr-1" style="font-size: 14px;">attach_file</span>
                                        Dokumen {{ $index + 1 }}
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Section 8: Muat Naik Dokumen Baru (Pilihan) -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">8. Muat Naik Dokumen Baru (Pilihan)</h2>
                        <p class="text-[10px] text-gray-600 mb-4">Muat naik dokumen baru akan menggantikan dokumen sedia ada. Max 5MB, format: JPG, PNG, PDF</p>
                        
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
                            Kemaskini Aset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
