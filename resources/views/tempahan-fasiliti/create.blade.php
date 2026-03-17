<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Tempahan Fasiliti - E-Masjid</title>
    <x-favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Make calendar icon visible */
        input[type="date"]::-webkit-calendar-picker-indicator,
        input[type="datetime-local"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
            filter: invert(0.5);
            opacity: 1;
        }
        input[type="date"]::-webkit-calendar-picker-indicator:hover,
        input[type="datetime-local"]::-webkit-calendar-picker-indicator:hover {
            filter: invert(0.3);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col" style="font-family: 'Poppins', sans-serif;">
    <x-double-navbar :user="auth()->user()" />

    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Tambah Tempahan Fasiliti</h1>
                        <p class="text-xs text-gray-600">Isi maklumat tempahan fasiliti</p>
                    </div>
                    <a href="{{ route('tempahan-fasiliti.index') }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                        <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                        Kembali
                    </a>
                </div>

                <form action="{{ route('tempahan-fasiliti.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Section 1: Maklumat Penyewa -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">1. Maklumat Penyewa</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="nama_penyewa" class="block text-xs font-medium text-gray-700 mb-2">Nama Penyewa *</label>
                                <input type="text" id="nama_penyewa" name="nama_penyewa" value="{{ old('nama_penyewa') }}" required maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('nama_penyewa')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="no_ic_penyewa" class="block text-xs font-medium text-gray-700 mb-2">No. IC *</label>
                                <input type="text" id="no_ic_penyewa" name="no_ic_penyewa" value="{{ old('no_ic_penyewa') }}" required maxlength="12" placeholder="000000000000" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('no_ic_penyewa')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="no_telefon_penyewa" class="block text-xs font-medium text-gray-700 mb-2">No. Telefon *</label>
                                <input type="text" id="no_telefon_penyewa" name="no_telefon_penyewa" value="{{ old('no_telefon_penyewa') }}" required maxlength="20" placeholder="01X-XXXXXXX" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('no_telefon_penyewa')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="emel_penyewa" class="block text-xs font-medium text-gray-700 mb-2">Emel</label>
                                <input type="email" id="emel_penyewa" name="emel_penyewa" value="{{ old('emel_penyewa') }}" maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div>
                                <label for="alamat_penyewa_1" class="block text-xs font-medium text-gray-700 mb-2">Alamat 1 *</label>
                                <input type="text" id="alamat_penyewa_1" name="alamat_penyewa_1" value="{{ old('alamat_penyewa_1') }}" required maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('alamat_penyewa_1')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="alamat_penyewa_2" class="block text-xs font-medium text-gray-700 mb-2">Alamat 2</label>
                                <input type="text" id="alamat_penyewa_2" name="alamat_penyewa_2" value="{{ old('alamat_penyewa_2') }}" maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div>
                                <label for="poskod_penyewa" class="block text-xs font-medium text-gray-700 mb-2">Poskod *</label>
                                <input type="text" id="poskod_penyewa" name="poskod_penyewa" value="{{ old('poskod_penyewa') }}" required maxlength="10" placeholder="00000" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('poskod_penyewa')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="bandar_penyewa" class="block text-xs font-medium text-gray-700 mb-2">Bandar *</label>
                                <input type="text" id="bandar_penyewa" name="bandar_penyewa" value="{{ old('bandar_penyewa') }}" required maxlength="100" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('bandar_penyewa')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="negeri_penyewa" class="block text-xs font-medium text-gray-700 mb-2">Negeri *</label>
                                <select id="negeri_penyewa" name="negeri_penyewa" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">Pilih Negeri</option>
                                    <option value="Johor" {{ old('negeri_penyewa') == 'Johor' ? 'selected' : '' }}>Johor</option>
                                    <option value="Kedah" {{ old('negeri_penyewa') == 'Kedah' ? 'selected' : '' }}>Kedah</option>
                                    <option value="Kelantan" {{ old('negeri_penyewa') == 'Kelantan' ? 'selected' : '' }}>Kelantan</option>
                                    <option value="Melaka" {{ old('negeri_penyewa') == 'Melaka' ? 'selected' : '' }}>Melaka</option>
                                    <option value="Negeri Sembilan" {{ old('negeri_penyewa') == 'Negeri Sembilan' ? 'selected' : '' }}>Negeri Sembilan</option>
                                    <option value="Pahang" {{ old('negeri_penyewa') == 'Pahang' ? 'selected' : '' }}>Pahang</option>
                                    <option value="Pulau Pinang" {{ old('negeri_penyewa') == 'Pulau Pinang' ? 'selected' : '' }}>Pulau Pinang</option>
                                    <option value="Perak" {{ old('negeri_penyewa') == 'Perak' ? 'selected' : '' }}>Perak</option>
                                    <option value="Perlis" {{ old('negeri_penyewa') == 'Perlis' ? 'selected' : '' }}>Perlis</option>
                                    <option value="Sabah" {{ old('negeri_penyewa') == 'Sabah' ? 'selected' : '' }}>Sabah</option>
                                    <option value="Sarawak" {{ old('negeri_penyewa') == 'Sarawak' ? 'selected' : '' }}>Sarawak</option>
                                    <option value="Selangor" {{ old('negeri_penyewa') == 'Selangor' ? 'selected' : '' }}>Selangor</option>
                                    <option value="Terengganu" {{ old('negeri_penyewa') == 'Terengganu' ? 'selected' : '' }}>Terengganu</option>
                                    <option value="WP Kuala Lumpur" {{ old('negeri_penyewa') == 'WP Kuala Lumpur' ? 'selected' : '' }}>WP Kuala Lumpur</option>
                                    <option value="WP Labuan" {{ old('negeri_penyewa') == 'WP Labuan' ? 'selected' : '' }}>WP Labuan</option>
                                    <option value="WP Putrajaya" {{ old('negeri_penyewa') == 'WP Putrajaya' ? 'selected' : '' }}>WP Putrajaya</option>
                                </select>
                                @error('negeri_penyewa')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="organisasi_penyewa" class="block text-xs font-medium text-gray-700 mb-2">Organisasi</label>
                                <input type="text" id="organisasi_penyewa" name="organisasi_penyewa" value="{{ old('organisasi_penyewa') }}" maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Maklumat Tempahan -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">2. Maklumat Tempahan</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="tarikh_tempahan" class="block text-xs font-medium text-gray-700 mb-2">Tarikh Tempahan *</label>
                                <input type="date" id="tarikh_tempahan" name="tarikh_tempahan" value="{{ old('tarikh_tempahan', date('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('tarikh_tempahan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="unit_tempoh" class="block text-xs font-medium text-gray-700 mb-2">Unit Tempoh *</label>
                                <select name="unit_tempoh" id="unit_tempoh" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">Pilih Unit</option>
                                    <option value="Jam" {{ old('unit_tempoh') == 'Jam' ? 'selected' : '' }}>Jam</option>
                                    <option value="Separuh Hari" {{ old('unit_tempoh') == 'Separuh Hari' ? 'selected' : '' }}>Separuh Hari</option>
                                    <option value="Hari" {{ old('unit_tempoh') == 'Hari' ? 'selected' : '' }}>Hari</option>
                                </select>
                                @error('unit_tempoh')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="tarikh_mula" class="block text-xs font-medium text-gray-700 mb-2">Tarikh & Masa Mula *</label>
                                <input type="datetime-local" name="tarikh_mula" id="tarikh_mula" value="{{ old('tarikh_mula') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('tarikh_mula')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="tarikh_tamat" class="block text-xs font-medium text-gray-700 mb-2">Tarikh & Masa Tamat *</label>
                                <input type="datetime-local" name="tarikh_tamat" id="tarikh_tamat" value="{{ old('tarikh_tamat') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('tarikh_tamat')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="tempoh_sewa" class="block text-xs font-medium text-gray-700 mb-2">Tempoh Sewa</label>
                                <input type="number" name="tempoh_sewa" id="tempoh_sewa" value="{{ old('tempoh_sewa') }}" readonly class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs bg-gray-100">
                                <p class="text-[10px] text-gray-500 mt-1">Auto-calculated dari tarikh mula & tamat</p>
                            </div>
                        </div>

                        <!-- Multiple Items Selection -->
                        <div class="border-t border-gray-300 pt-4 mt-4">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-xs font-semibold text-gray-900">Pilih Fasiliti & Aset *</h3>
                                <button type="button" id="add-item-btn" class="inline-flex items-center px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                                    <span class="material-icons mr-1" style="font-size: 14px !important;">add</span>
                                    Tambah Item
                                </button>
                            </div>
                            <p class="text-[10px] text-gray-600 mb-2">Anda boleh pilih beberapa fasiliti/aset sekaligus untuk tempahan ini</p>

                            <!-- Header Row -->
                            <div class="hidden md:flex items-center gap-2 mb-2 px-2 py-2 bg-gray-100 rounded text-xs font-semibold text-gray-700">
                                <div style="flex: 1;">Pilih Fasiliti</div>
                                <div style="width: 96px;" class="text-center">Jumlah</div>
                                <div style="width: 112px;" class="text-right">Unit</div>
                                <div style="width: 112px;" class="text-right">Total</div>
                                <div style="width: 40px;" class="text-center">X</div>
                            </div>

                            <div id="items-container">
                                <!-- Item rows will be added here dynamically -->
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Tujuan & Acara -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">3. Tujuan & Acara</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label for="tujuan_tempahan" class="block text-xs font-medium text-gray-700 mb-2">Tujuan Tempahan *</label>
                                <textarea id="tujuan_tempahan" name="tujuan_tempahan" required rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">{{ old('tujuan_tempahan') }}</textarea>
                                @error('tujuan_tempahan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="jenis_acara" class="block text-xs font-medium text-gray-700 mb-2">Jenis Acara</label>
                                <input type="text" id="jenis_acara" name="jenis_acara" value="{{ old('jenis_acara') }}" maxlength="255" placeholder="Cth: Majlis Perkahwinan, Mesyuarat" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>

                            <div>
                                <label for="bilangan_jangka_peserta" class="block text-xs font-medium text-gray-700 mb-2">Bilangan Jangka Peserta</label>
                                <input type="number" id="bilangan_jangka_peserta" name="bilangan_jangka_peserta" value="{{ old('bilangan_jangka_peserta') }}" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Lokasi Destinasi -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">4. Lokasi Destinasi</h2>
                        <p class="text-[10px] text-gray-600 mb-4">Lokasi di mana fasiliti/aset akan digunakan</p>
                        
                        <div class="mb-4">
                            <label class="block text-xs font-medium text-gray-700 mb-2">Jenis Lokasi *</label>
                            <div class="flex items-center space-x-6">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="is_lokasi_luaran" value="0" {{ old('is_lokasi_luaran', '0') == '0' ? 'checked' : '' }} class="form-radio text-blue-600" onchange="toggleLokasiFields()">
                                    <span class="ml-2 text-xs text-gray-700">Dalaman (Dalam Masjid)</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="is_lokasi_luaran" value="1" {{ old('is_lokasi_luaran') == '1' ? 'checked' : '' }} class="form-radio text-blue-600" onchange="toggleLokasiFields()">
                                    <span class="ml-2 text-xs text-gray-700">Luaran (Luar Masjid)</span>
                                </label>
                            </div>
                        </div>

                        <!-- Lokasi Dalaman -->
                        <div id="lokasi-dalaman-fields" class="{{ old('is_lokasi_luaran') == '1' ? 'hidden' : '' }}">
                            <div>
                                <label for="lokasi_destinasi" class="block text-xs font-medium text-gray-700 mb-2">Lokasi Dalam Masjid</label>
                                <select id="lokasi_destinasi" name="lokasi_destinasi" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">Pilih Lokasi</option>
                                    <option value="Dewan Utama" {{ old('lokasi_destinasi') == 'Dewan Utama' ? 'selected' : '' }}>Dewan Utama</option>
                                    <option value="Dewan Serbaguna" {{ old('lokasi_destinasi') == 'Dewan Serbaguna' ? 'selected' : '' }}>Dewan Serbaguna</option>
                                    <option value="Bilik Mesyuarat" {{ old('lokasi_destinasi') == 'Bilik Mesyuarat' ? 'selected' : '' }}>Bilik Mesyuarat</option>
                                    <option value="Ruang Solat" {{ old('lokasi_destinasi') == 'Ruang Solat' ? 'selected' : '' }}>Ruang Solat</option>
                                    <option value="Kawasan Luar" {{ old('lokasi_destinasi') == 'Kawasan Luar' ? 'selected' : '' }}>Kawasan Luar</option>
                                    <option value="Lain-lain" {{ old('lokasi_destinasi') == 'Lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                                </select>
                            </div>
                        </div>

                        <!-- Lokasi Luaran -->
                        <div id="lokasi-luaran-fields" class="{{ old('is_lokasi_luaran') != '1' ? 'hidden' : '' }}">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label for="nama_tempat_luaran" class="block text-xs font-medium text-gray-700 mb-2">Nama Tempat *</label>
                                    <input type="text" id="nama_tempat_luaran" name="nama_tempat_luaran" value="{{ old('nama_tempat_luaran') }}" maxlength="255" placeholder="Cth: Dewan Komuniti Taman Melati" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                </div>

                                <div>
                                    <label for="alamat_luaran_1" class="block text-xs font-medium text-gray-700 mb-2">Alamat 1 *</label>
                                    <input type="text" id="alamat_luaran_1" name="alamat_luaran_1" value="{{ old('alamat_luaran_1') }}" maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                </div>

                                <div>
                                    <label for="alamat_luaran_2" class="block text-xs font-medium text-gray-700 mb-2">Alamat 2</label>
                                    <input type="text" id="alamat_luaran_2" name="alamat_luaran_2" value="{{ old('alamat_luaran_2') }}" maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                </div>

                                <div>
                                    <label for="poskod_luaran" class="block text-xs font-medium text-gray-700 mb-2">Poskod *</label>
                                    <input type="text" id="poskod_luaran" name="poskod_luaran" value="{{ old('poskod_luaran') }}" maxlength="10" placeholder="00000" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                </div>

                                <div>
                                    <label for="bandar_luaran" class="block text-xs font-medium text-gray-700 mb-2">Bandar *</label>
                                    <input type="text" id="bandar_luaran" name="bandar_luaran" value="{{ old('bandar_luaran') }}" maxlength="100" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                </div>

                                <div>
                                    <label for="negeri_luaran" class="block text-xs font-medium text-gray-700 mb-2">Negeri *</label>
                                    <select id="negeri_luaran" name="negeri_luaran" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                        <option value="">Pilih Negeri</option>
                                        <option value="Johor" {{ old('negeri_luaran') == 'Johor' ? 'selected' : '' }}>Johor</option>
                                        <option value="Kedah" {{ old('negeri_luaran') == 'Kedah' ? 'selected' : '' }}>Kedah</option>
                                        <option value="Kelantan" {{ old('negeri_luaran') == 'Kelantan' ? 'selected' : '' }}>Kelantan</option>
                                        <option value="Melaka" {{ old('negeri_luaran') == 'Melaka' ? 'selected' : '' }}>Melaka</option>
                                        <option value="Negeri Sembilan" {{ old('negeri_luaran') == 'Negeri Sembilan' ? 'selected' : '' }}>Negeri Sembilan</option>
                                        <option value="Pahang" {{ old('negeri_luaran') == 'Pahang' ? 'selected' : '' }}>Pahang</option>
                                        <option value="Pulau Pinang" {{ old('negeri_luaran') == 'Pulau Pinang' ? 'selected' : '' }}>Pulau Pinang</option>
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
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 5: Harga & Bayaran -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">5. Harga & Bayaran</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="harga_sewa" class="block text-xs font-medium text-gray-700 mb-2">Jumlah Harga Sewa (RM)</label>
                                <input type="number" name="harga_sewa" id="harga_sewa" value="{{ old('harga_sewa', 0) }}" step="0.01" readonly class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs bg-gray-100 font-semibold">
                            </div>

                            <div>
                                <label for="deposit" class="block text-xs font-medium text-gray-700 mb-2">Jumlah Deposit (RM)</label>
                                <input type="number" name="deposit" id="deposit" value="{{ old('deposit', 0) }}" step="0.01" readonly class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs bg-gray-100 font-semibold">
                            </div>

                            <div>
                                <label for="jumlah_bayaran" class="block text-xs font-medium text-gray-700 mb-2">Jumlah Keseluruhan (RM)</label>
                                <input type="number" name="jumlah_bayaran" id="jumlah_bayaran" value="{{ old('jumlah_bayaran', 0) }}" step="0.01" readonly class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs bg-blue-100 font-bold text-blue-900">
                            </div>
                        </div>
                        <p class="text-[10px] text-gray-500 mt-2">Harga akan dikira secara automatik berdasarkan semua item yang dipilih</p>
                    </div>

                    <!-- Section 6: Dokumen (Pilihan) -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">6. Dokumen (Pilihan)</h2>
                        <p class="text-[10px] text-gray-600 mb-4">Semua fail adalah pilihan. Max 5MB, format: JPG, PNG, PDF</p>
                        
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="surat_permohonan" class="block text-xs font-medium text-gray-700 mb-2">Surat Permohonan</label>
                                <input type="file" id="surat_permohonan" name="surat_permohonan" accept="application/pdf" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                <p class="text-[10px] text-gray-500 mt-1">Format: PDF</p>
                            </div>

                            <div>
                                <label for="salinan_ic" class="block text-xs font-medium text-gray-700 mb-2">Salinan IC</label>
                                <input type="file" id="salinan_ic" name="salinan_ic" accept="application/pdf,image/jpeg,image/png,image/jpg" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                <p class="text-[10px] text-gray-500 mt-1">Format: PDF, JPG, PNG</p>
                            </div>

                            <div>
                                <label for="surat_sokongan" class="block text-xs font-medium text-gray-700 mb-2">Surat Sokongan</label>
                                <input type="file" id="surat_sokongan" name="surat_sokongan" accept="application/pdf" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                <p class="text-[10px] text-gray-500 mt-1">Format: PDF</p>
                            </div>

                            <div>
                                <label for="dokumen_lain" class="block text-xs font-medium text-gray-700 mb-2">Dokumen Lain</label>
                                <input type="file" id="dokumen_lain" name="dokumen_lain[]" accept="application/pdf,image/jpeg,image/png,image/jpg" multiple class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                <p class="text-[10px] text-gray-500 mt-1">Boleh pilih beberapa fail (max 3 fail)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Section 7: Catatan -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">7. Catatan</h2>
                        
                        <div>
                            <label for="catatan" class="block text-xs font-medium text-gray-700 mb-2">Catatan Tambahan</label>
                            <textarea id="catatan" name="catatan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">{{ old('catatan') }}</textarea>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('tempahan-fasiliti.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                            Simpan Tempahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <x-footer />

    <script>
        // Fasiliti data from backend
        const fasilitiData = @json($fasilitiList);
        let itemCounter = 0;

        // Event listeners
        document.getElementById('tarikh_mula').addEventListener('change', function() {
            calculateTempoh();
            refreshAllAvailability();
        });
        document.getElementById('tarikh_tamat').addEventListener('change', function() {
            calculateTempoh();
            refreshAllAvailability();
        });
        document.getElementById('unit_tempoh').addEventListener('change', function() {
            calculateTempoh();
            recalculateAllPrices();
        });
        document.getElementById('add-item-btn').addEventListener('click', addItemRow);

        // Calculate tempoh sewa
        function calculateTempoh() {
            const tarikhMula = new Date(document.getElementById('tarikh_mula').value);
            const tarikhTamat = new Date(document.getElementById('tarikh_tamat').value);
            const unitTempoh = document.getElementById('unit_tempoh').value;
            
            if (tarikhMula && tarikhTamat && tarikhTamat > tarikhMula && unitTempoh) {
                const diffMs = tarikhTamat - tarikhMula;
                let tempoh = 0;
                
                if (unitTempoh === 'Jam') {
                    tempoh = Math.ceil(diffMs / (1000 * 60 * 60));
                } else if (unitTempoh === 'Separuh Hari') {
                    tempoh = Math.ceil(diffMs / (1000 * 60 * 60 * 12));
                } else if (unitTempoh === 'Hari') {
                    tempoh = Math.ceil(diffMs / (1000 * 60 * 60 * 24));
                }
                
                document.getElementById('tempoh_sewa').value = tempoh;
                recalculateAllPrices();
            }
        }

        // Add new item row
        function addItemRow() {
            const tarikhMula = document.getElementById('tarikh_mula').value;
            const tarikhTamat = document.getElementById('tarikh_tamat').value;
            
            if (!tarikhMula || !tarikhTamat) {
                alert('Sila pilih tarikh mula dan tamat terlebih dahulu');
                return;
            }

            const container = document.getElementById('items-container');
            const row = document.createElement('div');
            row.className = 'item-row bg-white border border-gray-300 rounded p-3 mb-3';
            row.dataset.index = itemCounter;
            
            row.innerHTML = `
                <div class="flex items-center gap-2">
                    <div class="flex-1">
                        <select name="items[${itemCounter}][fasiliti_id]" class="fasiliti-select w-full px-2 py-1.5 border border-gray-300 rounded text-xs" required onchange="onFasilitiChange(this)">
                            <option value="">-- Pilih Fasiliti/Aset --</option>
                        </select>
                        <div class="availability-info text-[10px] mt-0.5"></div>
                    </div>
                    <div class="w-24">
                        <input type="number" name="items[${itemCounter}][quantity]" class="quantity-input w-full px-2 py-1.5 border border-gray-300 rounded text-xs text-center" value="1" min="1" required onchange="validateQuantity(this); calculateItemPrice(this)" oninput="validateQuantity(this)" placeholder="Qty">
                    </div>
                    <div class="w-28">
                        <input type="text" class="item-price w-full px-2 py-1.5 border border-gray-300 rounded text-xs bg-gray-50 text-right" readonly value="RM 0.00">
                    </div>
                    <div class="w-28">
                        <input type="text" class="item-subtotal w-full px-2 py-1.5 border border-gray-300 rounded text-xs bg-gray-100 font-semibold text-right" readonly value="RM 0.00">
                    </div>
                    <div class="w-10">
                        <button type="button" onclick="removeItemRow(this)" class="w-full h-[30px] bg-red-600 text-white rounded hover:bg-red-700 flex items-center justify-center">
                            <span class="material-icons" style="font-size: 16px !important;">delete</span>
                        </button>
                    </div>
                </div>
            `;
            
            container.appendChild(row);
            populateFasilitiOptions(row.querySelector('.fasiliti-select'));
            itemCounter++;
        }

        // Populate fasiliti options
        function populateFasilitiOptions(selectElement) {
            fasilitiData.forEach(fasiliti => {
                const option = document.createElement('option');
                option.value = fasiliti.id;
                option.textContent = `${fasiliti.nama_fasiliti} (${fasiliti.jenis_fasiliti})`;
                option.dataset.nama = fasiliti.nama_fasiliti;
                option.dataset.jenis = fasiliti.jenis_fasiliti;
                option.dataset.hargaSejam = fasiliti.harga_sewa_sejam || 0;
                option.dataset.hargaSeparuh = fasiliti.harga_sewa_separuh_hari || 0;
                option.dataset.hargaSehari = fasiliti.harga_sewa_sehari || 0;
                option.dataset.deposit = fasiliti.deposit_diperlukan || 0;
                option.dataset.kuantitiTotal = fasiliti.kuantiti_total || 1;
                option.dataset.isCountable = fasiliti.is_countable ? '1' : '0';
                selectElement.appendChild(option);
            });
        }

        // When fasiliti is selected
        function onFasilitiChange(selectElement) {
            const row = selectElement.closest('.item-row');
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const quantityInput = row.querySelector('.quantity-input');
            const availabilityInfo = row.querySelector('.availability-info');
            
            if (!selectedOption.value) {
                quantityInput.value = 1;
                quantityInput.max = 1;
                quantityInput.disabled = true;
                availabilityInfo.innerHTML = '';
                return;
            }

            const fasilitiId = selectedOption.value;
            checkAvailability(fasilitiId, row);
        }

        // Check availability via AJAX
        function checkAvailability(fasilitiId, row) {
            const tarikhMula = document.getElementById('tarikh_mula').value;
            const tarikhTamat = document.getElementById('tarikh_tamat').value;
            const availabilityInfo = row.querySelector('.availability-info');
            const quantityInput = row.querySelector('.quantity-input');
            const selectElement = row.querySelector('.fasiliti-select');
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            
            availabilityInfo.innerHTML = '<span class="text-gray-500">Menyemak ketersediaan...</span>';
            
            fetch(`/tempahan-fasiliti/check-availability?fasiliti_id=${fasilitiId}&tarikh_mula=${tarikhMula}&tarikh_tamat=${tarikhTamat}`)
                .then(response => response.json())
                .then(data => {
                    // Store availability data in row for validation
                    row.dataset.available = data.available;
                    row.dataset.total = data.total;
                    row.dataset.isCountable = data.is_countable ? '1' : '0';
                    
                    // Check if fasiliti is under maintenance
                    if (data.is_under_maintenance) {
                        const info = data.maintenance_info;
                        availabilityInfo.innerHTML = `
                            <div class="bg-orange-100 border border-orange-300 rounded p-2 mt-1">
                                <div class="flex items-center text-orange-700">
                                    <span class="material-icons mr-1" style="font-size: 14px !important;">engineering</span>
                                    <span class="font-semibold">Dalam Penyelenggaraan</span>
                                </div>
                                <div class="text-orange-600 mt-1">
                                    <p>No. Kerja: ${info.no_kerja}</p>
                                    <p>Jenis: ${info.jenis_kerja}</p>
                                    <p>Tarikh: ${info.tarikh_kerja}</p>
                                    <p>Status: ${info.status}</p>
                                </div>
                            </div>
                        `;
                        quantityInput.value = 0;
                        quantityInput.disabled = true;
                        quantityInput.placeholder = 'N/A';
                        calculateItemPrice(quantityInput);
                        return;
                    }
                    
                    const isCountable = data.is_countable;
                    const available = data.available;
                    
                    if (isCountable) {
                        // Countable item - set placeholder to show max available
                        quantityInput.placeholder = `Max: ${available}`;
                        
                        if (available > 0) {
                            availabilityInfo.innerHTML = `<span class="text-green-600">✓ ${available} / ${data.total} tersedia</span>`;
                            quantityInput.max = available;
                            quantityInput.disabled = false;
                            selectedOption.disabled = false;
                            
                            // Adjust quantity if exceeds available
                            if (parseInt(quantityInput.value) > available) {
                                quantityInput.value = available;
                                showQuantityWarning(row, available);
                            }
                        } else {
                            availabilityInfo.innerHTML = `<span class="text-red-600">✗ Tidak tersedia (${data.booked} / ${data.total} telah ditempah)</span>`;
                            quantityInput.value = 0;
                            quantityInput.disabled = true;
                            quantityInput.placeholder = 'Habis';
                            selectedOption.disabled = true;
                        }
                    } else {
                        // Unique item
                        if (available > 0) {
                            availabilityInfo.innerHTML = `<span class="text-green-600">✓ Tersedia</span>`;
                            quantityInput.value = 1;
                            quantityInput.max = 1;
                            quantityInput.disabled = true;
                            quantityInput.placeholder = '1';
                            selectedOption.disabled = false;
                        } else {
                            availabilityInfo.innerHTML = `<span class="text-red-600">✗ Tidak tersedia (sudah ditempah)</span>`;
                            quantityInput.value = 0;
                            quantityInput.disabled = true;
                            quantityInput.placeholder = 'Habis';
                            selectedOption.disabled = true;
                        }
                    }
                    
                    calculateItemPrice(quantityInput);
                })
                .catch(error => {
                    console.error('Error checking availability:', error);
                    availabilityInfo.innerHTML = '<span class="text-red-600">Ralat menyemak ketersediaan</span>';
                });
        }
        
        // Show warning when quantity exceeds available
        function showQuantityWarning(row, maxAvailable) {
            const availabilityInfo = row.querySelector('.availability-info');
            const currentHtml = availabilityInfo.innerHTML;
            availabilityInfo.innerHTML = currentHtml + `
                <div class="bg-yellow-100 border border-yellow-300 rounded p-1 mt-1 text-yellow-700">
                    <span class="material-icons align-middle" style="font-size: 12px !important;">warning</span>
                    Kuantiti dikurangkan ke ${maxAvailable} (maksimum tersedia)
                </div>
            `;
        }
        
        // Validate quantity on input change
        function validateQuantity(input) {
            const row = input.closest('.item-row');
            const maxAvailable = parseInt(row.dataset.available) || 0;
            const currentValue = parseInt(input.value) || 0;
            
            if (currentValue > maxAvailable) {
                input.value = maxAvailable;
                showQuantityWarning(row, maxAvailable);
                return false;
            }
            return true;
        }

        // Calculate price for single item
        function calculateItemPrice(element) {
            const row = element.closest('.item-row');
            const selectElement = row.querySelector('.fasiliti-select');
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const quantityInput = row.querySelector('.quantity-input');
            const itemPriceInput = row.querySelector('.item-price');
            const itemSubtotalInput = row.querySelector('.item-subtotal');
            
            if (!selectedOption.value) {
                itemPriceInput.value = 'RM 0.00';
                itemSubtotalInput.value = 'RM 0.00';
                calculateGrandTotal();
                return;
            }
            
            const unitTempoh = document.getElementById('unit_tempoh').value;
            const tempohSewa = parseFloat(document.getElementById('tempoh_sewa').value) || 0;
            const quantity = parseInt(quantityInput.value) || 0;
            
            let hargaPerUnit = 0;
            if (unitTempoh === 'Jam') {
                hargaPerUnit = parseFloat(selectedOption.dataset.hargaSejam) || 0;
            } else if (unitTempoh === 'Separuh Hari') {
                hargaPerUnit = parseFloat(selectedOption.dataset.hargaSeparuh) || 0;
            } else if (unitTempoh === 'Hari') {
                hargaPerUnit = parseFloat(selectedOption.dataset.hargaSehari) || 0;
            }
            
            const itemPrice = hargaPerUnit * tempohSewa;
            const subtotal = itemPrice * quantity;
            
            itemPriceInput.value = 'RM ' + itemPrice.toFixed(2);
            itemSubtotalInput.value = 'RM ' + subtotal.toFixed(2);
            
            calculateGrandTotal();
        }

        // Recalculate all item prices
        function recalculateAllPrices() {
            document.querySelectorAll('.item-row').forEach(row => {
                const quantityInput = row.querySelector('.quantity-input');
                calculateItemPrice(quantityInput);
            });
        }

        // Calculate grand total
        function calculateGrandTotal() {
            let totalHargaSewa = 0;
            let totalDeposit = 0;
            
            document.querySelectorAll('.item-row').forEach(row => {
                const selectElement = row.querySelector('.fasiliti-select');
                const selectedOption = selectElement.options[selectElement.selectedIndex];
                const subtotalText = row.querySelector('.item-subtotal').value.replace('RM ', '').replace(',', '');
                const subtotal = parseFloat(subtotalText) || 0;
                
                totalHargaSewa += subtotal;
                
                if (selectedOption.value) {
                    const deposit = parseFloat(selectedOption.dataset.deposit) || 0;
                    totalDeposit += deposit;
                }
            });
            
            const jumlahBayaran = totalHargaSewa + totalDeposit;
            
            document.getElementById('harga_sewa').value = totalHargaSewa.toFixed(2);
            document.getElementById('deposit').value = totalDeposit.toFixed(2);
            document.getElementById('jumlah_bayaran').value = jumlahBayaran.toFixed(2);
        }

        // Remove item row
        function removeItemRow(button) {
            const row = button.closest('.item-row');
            row.remove();
            calculateGrandTotal();
        }

        // Refresh all availability
        function refreshAllAvailability() {
            document.querySelectorAll('.item-row').forEach(row => {
                const selectElement = row.querySelector('.fasiliti-select');
                if (selectElement.value) {
                    checkAvailability(selectElement.value, row);
                }
            });
        }

        // Don't auto-add item on page load - let user click "Tambah Item" button

        // Toggle lokasi fields based on selection
        function toggleLokasiFields() {
            const isLuaran = document.querySelector('input[name="is_lokasi_luaran"]:checked').value === '1';
            const dalamFields = document.getElementById('lokasi-dalaman-fields');
            const luarFields = document.getElementById('lokasi-luaran-fields');
            
            if (isLuaran) {
                dalamFields.classList.add('hidden');
                luarFields.classList.remove('hidden');
            } else {
                dalamFields.classList.remove('hidden');
                luarFields.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
