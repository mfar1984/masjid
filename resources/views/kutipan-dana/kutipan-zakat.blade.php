<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kutipan Zakat - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Kutipan Zakat</h1>
                        <p class="text-xs text-gray-600">Rekod kutipan zakat fitrah dan harta</p>
                    </div>
                    <a href="{{ route('transaksi-kewangan.index') }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                        <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                        Kembali
                    </a>
                </div>

                <form method="POST" action="{{ route('kutipan-dana.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="jenis_kutipan" value="Kutipan Zakat">

                    <!-- Section 1: Maklumat Pembayar -->
                    <div class="bg-purple-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Pembayar</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="nama_pembayar" class="block text-xs font-medium text-gray-700 mb-2">Nama Pembayar *</label>
                                <input type="text" id="nama_pembayar" name="nama_pembayar" value="{{ old('nama_pembayar') }}" required maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('nama_pembayar')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="no_telefon" class="block text-xs font-medium text-gray-700 mb-2">No. Telefon</label>
                                <input type="text" id="no_telefon" name="no_telefon" value="{{ old('no_telefon') }}" maxlength="20" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('no_telefon')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="jenis_zakat" class="block text-xs font-medium text-gray-700 mb-2">Jenis Zakat *</label>
                                <select id="jenis_zakat" name="jenis_zakat" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Jenis --</option>
                                    <option value="Zakat Fitrah" {{ old('jenis_zakat') == 'Zakat Fitrah' ? 'selected' : '' }}>Zakat Fitrah</option>
                                    <option value="Zakat Harta" {{ old('jenis_zakat') == 'Zakat Harta' ? 'selected' : '' }}>Zakat Harta</option>
                                    <option value="Fidyah" {{ old('jenis_zakat') == 'Fidyah' ? 'selected' : '' }}>Fidyah</option>
                                </select>
                                @error('jenis_zakat')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="bilangan_jiwa" class="block text-xs font-medium text-gray-700 mb-2">Bilangan Jiwa</label>
                                <input type="number" id="bilangan_jiwa" name="bilangan_jiwa" value="{{ old('bilangan_jiwa', 1) }}" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                <p class="text-[10px] text-gray-500 mt-1">Untuk Zakat Fitrah sahaja</p>
                                @error('bilangan_jiwa')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="tarikh_kutipan" class="block text-xs font-medium text-gray-700 mb-2">Tarikh Bayaran *</label>
                                <input type="date" id="tarikh_kutipan" name="tarikh_kutipan" value="{{ old('tarikh_kutipan', date('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('tarikh_kutipan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Maklumat Bayaran -->
                    <div class="bg-purple-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Bayaran</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="kategori_kewangan_id" class="block text-xs font-medium text-gray-700 mb-2">Kategori Pendapatan *</label>
                                <select id="kategori_kewangan_id" name="kategori_kewangan_id" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($kategori as $kat)
                                        <option value="{{ $kat->id }}" {{ old('kategori_kewangan_id') == $kat->id ? 'selected' : '' }}>
                                            {{ $kat->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kategori_kewangan_id')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

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

                    <!-- Section 3: Catatan & Dokumen -->
                    <div class="bg-purple-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Catatan & Dokumen</h2>
                        
                        <div class="grid grid-cols-1 gap-4">
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
                        <button type="submit" class="px-4 py-2 bg-purple-600 text-white text-xs rounded hover:bg-purple-700">
                            Simpan Kutipan Zakat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
