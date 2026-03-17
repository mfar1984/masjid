<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pergerakan Aset - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Tambah Pergerakan Aset</h1>
                        <p class="text-xs text-gray-600">Rekod pergerakan aset baharu</p>
                    </div>
                    <a href="{{ route('pergerakan-aset.index') }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                        <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                        Kembali
                    </a>
                </div>

                <form method="POST" action="{{ route('pergerakan-aset.store') }}" x-data="{ isLokasiLuaran: false }">
                    @csrf

                    <!-- Section 1: Maklumat Aset -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">1. Maklumat Aset</h2>
                        
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="senarai_aset_id" class="block text-xs font-medium text-gray-700 mb-2">Pilih Aset *</label>
                                <select id="senarai_aset_id" name="senarai_aset_id" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Aset --</option>
                                    @foreach($senariAset as $aset)
                                        <option value="{{ $aset->id }}" {{ old('senarai_aset_id') == $aset->id ? 'selected' : '' }}>
                                            {{ $aset->no_aset }} - {{ $aset->nama_aset }} ({{ $aset->kategoriAset->nama_kategori ?? '-' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('senarai_aset_id')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Maklumat Pergerakan -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">2. Maklumat Pergerakan</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="tarikh_pergerakan" class="block text-xs font-medium text-gray-700 mb-2">Tarikh Pergerakan *</label>
                                <input type="date" id="tarikh_pergerakan" name="tarikh_pergerakan" value="{{ old('tarikh_pergerakan', date('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('tarikh_pergerakan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="jenis_pergerakan" class="block text-xs font-medium text-gray-700 mb-2">Jenis Pergerakan *</label>
                                <select id="jenis_pergerakan" name="jenis_pergerakan" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Jenis --</option>
                                    <option value="Pemindahan Dalaman" {{ old('jenis_pergerakan') == 'Pemindahan Dalaman' ? 'selected' : '' }}>Pemindahan Dalaman</option>
                                    <option value="Pemindahan Luaran" {{ old('jenis_pergerakan') == 'Pemindahan Luaran' ? 'selected' : '' }}>Pemindahan Luaran</option>
                                    <option value="Pinjaman" {{ old('jenis_pergerakan') == 'Pinjaman' ? 'selected' : '' }}>Pinjaman</option>
                                    <option value="Sewa" {{ old('jenis_pergerakan') == 'Sewa' ? 'selected' : '' }}>Sewa</option>
                                    <option value="Penyelenggaraan" {{ old('jenis_pergerakan') == 'Penyelenggaraan' ? 'selected' : '' }}>Penyelenggaraan</option>
                                    <option value="Pulangan" {{ old('jenis_pergerakan') == 'Pulangan' ? 'selected' : '' }}>Pulangan</option>
                                </select>
                                @error('jenis_pergerakan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="kondisi_sebelum" class="block text-xs font-medium text-gray-700 mb-2">Kondisi Sebelum *</label>
                                <select id="kondisi_sebelum" name="kondisi_sebelum" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Kondisi --</option>
                                    <option value="Baru" {{ old('kondisi_sebelum') == 'Baru' ? 'selected' : '' }}>Baru</option>
                                    <option value="Baik" {{ old('kondisi_sebelum', 'Baik') == 'Baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="Sederhana" {{ old('kondisi_sebelum') == 'Sederhana' ? 'selected' : '' }}>Sederhana</option>
                                    <option value="Teruk" {{ old('kondisi_sebelum') == 'Teruk' ? 'selected' : '' }}>Teruk</option>
                                    <option value="Rosak" {{ old('kondisi_sebelum') == 'Rosak' ? 'selected' : '' }}>Rosak</option>
                                </select>
                                @error('kondisi_sebelum')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="sebab_pergerakan" class="block text-xs font-medium text-gray-700 mb-2">Sebab Pergerakan *</label>
                                <textarea id="sebab_pergerakan" name="sebab_pergerakan" rows="2" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">{{ old('sebab_pergerakan') }}</textarea>
                                @error('sebab_pergerakan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Lokasi Destinasi -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">3. Lokasi Destinasi</h2>
                        
                        <div class="mb-4">
                            <label class="block text-xs font-medium text-gray-700 mb-2">Jenis Lokasi *</label>
                            <div class="flex gap-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="is_lokasi_luaran" value="0" {{ old('is_lokasi_luaran', '0') == '0' ? 'checked' : '' }} required class="mr-2" x-model="isLokasiLuaran" @change="isLokasiLuaran = false">
                                    <span class="text-xs">Lokasi Dalaman Masjid</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="is_lokasi_luaran" value="1" {{ old('is_lokasi_luaran') == '1' ? 'checked' : '' }} required class="mr-2" x-model="isLokasiLuaran" @change="isLokasiLuaran = true">
                                    <span class="text-xs">Lokasi Luaran (Perlu Kelulusan)</span>
                                </label>
                            </div>
                            @error('is_lokasi_luaran')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                        </div>

                        <!-- Lokasi Dalaman -->
                        <div x-show="!isLokasiLuaran" class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="lokasi_destinasi" class="block text-xs font-medium text-gray-700 mb-2">Lokasi Destinasi *</label>
                                <input type="text" id="lokasi_destinasi" name="lokasi_destinasi" value="{{ old('lokasi_destinasi') }}" maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs" :required="!isLokasiLuaran">
                                <p class="text-[10px] text-gray-500 mt-1">Contoh: Dewan Utama, Pejabat, Stor</p>
                                @error('lokasi_destinasi')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <!-- Lokasi Luaran -->
                        <div x-show="isLokasiLuaran" class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="nama_tempat_luaran" class="block text-xs font-medium text-gray-700 mb-2">Nama Tempat Luaran *</label>
                                <input type="text" id="nama_tempat_luaran" name="nama_tempat_luaran" value="{{ old('nama_tempat_luaran') }}" maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs" :required="isLokasiLuaran">
                                @error('nama_tempat_luaran')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="alamat_luaran_1" class="block text-xs font-medium text-gray-700 mb-2">Alamat 1 *</label>
                                <input type="text" id="alamat_luaran_1" name="alamat_luaran_1" value="{{ old('alamat_luaran_1') }}" maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs" :required="isLokasiLuaran">
                                @error('alamat_luaran_1')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="alamat_luaran_2" class="block text-xs font-medium text-gray-700 mb-2">Alamat 2</label>
                                <input type="text" id="alamat_luaran_2" name="alamat_luaran_2" value="{{ old('alamat_luaran_2') }}" maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="poskod_luaran" class="block text-xs font-medium text-gray-700 mb-2">Poskod *</label>
                                    <input type="text" id="poskod_luaran" name="poskod_luaran" value="{{ old('poskod_luaran') }}" maxlength="5" pattern="[0-9]{5}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs" :required="isLokasiLuaran">
                                    @error('poskod_luaran')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                                </div>

                                <div>
                                    <label for="bandar_luaran" class="block text-xs font-medium text-gray-700 mb-2">Bandar *</label>
                                    <input type="text" id="bandar_luaran" name="bandar_luaran" value="{{ old('bandar_luaran') }}" maxlength="100" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs" :required="isLokasiLuaran">
                                    @error('bandar_luaran')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                                </div>

                                <div>
                                    <label for="negeri_luaran" class="block text-xs font-medium text-gray-700 mb-2">Negeri *</label>
                                    <select id="negeri_luaran" name="negeri_luaran" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs" :required="isLokasiLuaran">
                                        <option value="">-- Pilih Negeri --</option>
                                        <option value="Johor" {{ old('negeri_luaran') == 'Johor' ? 'selected' : '' }}>Johor</option>
                                        <option value="Kedah" {{ old('negeri_luaran') == 'Kedah' ? 'selected' : '' }}>Kedah</option>
                                        <option value="Kelantan" {{ old('negeri_luaran') == 'Kelantan' ? 'selected' : '' }}>Kelantan</option>
                                        <option value="Melaka" {{ old('negeri_luaran') == 'Melaka' ? 'selected' : '' }}>Melaka</option>
                                        <option value="Negeri Sembilan" {{ old('negeri_luaran') == 'Negeri Sembilan' ? 'selected' : '' }}>Negeri Sembilan</option>
                                        <option value="Pahang" {{ old('negeri_luaran') == 'Pahang' ? 'selected' : '' }}>Pahang</option>
                                        <option value="Penang" {{ old('negeri_luaran') == 'Penang' ? 'selected' : '' }}>Penang</option>
                                        <option value="Perak" {{ old('negeri_luaran') == 'Perak' ? 'selected' : '' }}>Perak</option>
                                        <option value="Perlis" {{ old('negeri_luaran') == 'Perlis' ? 'selected' : '' }}>Perlis</option>
                                        <option value="Sabah" {{ old('negeri_luaran') == 'Sabah' ? 'selected' : '' }}>Sabah</option>
                                        <option value="Sarawak" {{ old('negeri_luaran') == 'Sarawak' ? 'selected' : '' }}>Sarawak</option>
                                        <option value="Selangor" {{ old('negeri_luaran') == 'Selangor' ? 'selected' : '' }}>Selangor</option>
                                        <option value="Terengganu" {{ old('negeri_luaran') == 'Terengganu' ? 'selected' : '' }}>Terengganu</option>
                                        <option value="WP Kuala Lumpur" {{ old('negeri_luaran') == 'WP Kuala Lumpur' ? 'selected' : '' }}>WP Kuala Lumpur</option>
                                        <option value="WP Labuan" {{ old('negeri_luaran') == 'WP Labuan' ? 'selected' : '' }}>WP Labuan</option>
                                        <option value="WP Putrajaya" {{ old('negeri_luaran') == 'WP Putrajaya' ? 'selected' : '' }}>WP Putrajaya</option>
                                    </select>
                                    @error('negeri_luaran')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Maklumat Peminjam (Optional) -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">4. Maklumat Peminjam (Jika Berkaitan)</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="nama_peminjam" class="block text-xs font-medium text-gray-700 mb-2">Nama Peminjam</label>
                                <input type="text" id="nama_peminjam" name="nama_peminjam" value="{{ old('nama_peminjam') }}" maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div>
                                <label for="no_ic_peminjam" class="block text-xs font-medium text-gray-700 mb-2">No. IC Peminjam</label>
                                <input type="text" id="no_ic_peminjam" name="no_ic_peminjam" value="{{ old('no_ic_peminjam') }}" maxlength="12" pattern="[0-9]{12}" placeholder="000000000000" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div>
                                <label for="no_telefon_peminjam" class="block text-xs font-medium text-gray-700 mb-2">No. Telefon Peminjam</label>
                                <input type="text" id="no_telefon_peminjam" name="no_telefon_peminjam" value="{{ old('no_telefon_peminjam') }}" maxlength="20" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div>
                                <label for="organisasi_peminjam" class="block text-xs font-medium text-gray-700 mb-2">Organisasi Peminjam</label>
                                <input type="text" id="organisasi_peminjam" name="organisasi_peminjam" value="{{ old('organisasi_peminjam') }}" maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div>
                                <label for="tarikh_jangka_pulangan" class="block text-xs font-medium text-gray-700 mb-2">Tarikh Jangka Pulangan</label>
                                <input type="date" id="tarikh_jangka_pulangan" name="tarikh_jangka_pulangan" value="{{ old('tarikh_jangka_pulangan') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>
                        </div>
                    </div>

                    <!-- Section 5: Catatan -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">5. Catatan</h2>
                        
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="catatan" class="block text-xs font-medium text-gray-700 mb-2">Catatan Tambahan</label>
                                <textarea id="catatan" name="catatan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">{{ old('catatan') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('pergerakan-aset.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                            Simpan Pergerakan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
