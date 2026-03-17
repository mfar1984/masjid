<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaji & Elaun - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Gaji & Elaun</h1>
                        <p class="text-xs text-gray-600">Rekod pembayaran gaji dan elaun</p>
                    </div>
                    <a href="{{ route('transaksi-kewangan.index') }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                        <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                        Kembali
                    </a>
                </div>

                <form method="POST" action="{{ route('perbelanjaan.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="jenis_perbelanjaan" value="Gaji & Elaun">

                    <div class="bg-purple-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Kakitangan</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="nama_kakitangan" class="block text-xs font-medium text-gray-700 mb-2">Nama Kakitangan *</label>
                                <input type="text" id="nama_kakitangan" name="nama_kakitangan" value="{{ old('nama_kakitangan') }}" required maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('nama_kakitangan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="jawatan" class="block text-xs font-medium text-gray-700 mb-2">Jawatan *</label>
                                <input type="text" id="jawatan" name="jawatan" value="{{ old('jawatan') }}" required maxlength="100" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('jawatan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="bulan_gaji" class="block text-xs font-medium text-gray-700 mb-2">Bulan Gaji *</label>
                                <input type="month" id="bulan_gaji" name="bulan_gaji" value="{{ old('bulan_gaji', date('Y-m')) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('bulan_gaji')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="tarikh_perbelanjaan" class="block text-xs font-medium text-gray-700 mb-2">Tarikh Bayaran *</label>
                                <input type="date" id="tarikh_perbelanjaan" name="tarikh_perbelanjaan" value="{{ old('tarikh_perbelanjaan', date('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('tarikh_perbelanjaan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-purple-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Butiran Gaji</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="gaji_pokok" class="block text-xs font-medium text-gray-700 mb-2">Gaji Pokok (RM) *</label>
                                <input type="number" id="gaji_pokok" name="gaji_pokok" value="{{ old('gaji_pokok') }}" step="0.01" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('gaji_pokok')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="elaun" class="block text-xs font-medium text-gray-700 mb-2">Elaun (RM)</label>
                                <input type="number" id="elaun" name="elaun" value="{{ old('elaun', 0) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('elaun')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="bonus" class="block text-xs font-medium text-gray-700 mb-2">Bonus (RM)</label>
                                <input type="number" id="bonus" name="bonus" value="{{ old('bonus', 0) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('bonus')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="potongan" class="block text-xs font-medium text-gray-700 mb-2">Potongan (RM)</label>
                                <input type="number" id="potongan" name="potongan" value="{{ old('potongan', 0) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('potongan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="jumlah" class="block text-xs font-medium text-gray-700 mb-2">Jumlah Bersih (RM) *</label>
                                <input type="number" id="jumlah" name="jumlah" value="{{ old('jumlah') }}" step="0.01" min="0.01" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('jumlah')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-purple-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Bayaran</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                                        <option value="{{ $akaun->id }}">{{ $akaun->nama_bank }} - {{ $akaun->no_akaun }}</option>
                                    @endforeach
                                </select>
                                @error('akaun_bank_id')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="no_rujukan" class="block text-xs font-medium text-gray-700 mb-2">No. Rujukan</label>
                                <input type="text" id="no_rujukan" name="no_rujukan" value="{{ old('no_rujukan') }}" maxlength="100" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('no_rujukan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="dokumen" class="block text-xs font-medium text-gray-700 mb-2">Dokumen Sokongan</label>
                                <input type="file" id="dokumen" name="dokumen" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                <p class="text-[10px] text-gray-500 mt-1">Format: PDF, JPG, PNG (Max: 2MB)</p>
                                @error('dokumen')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="keterangan" class="block text-xs font-medium text-gray-700 mb-2">Keterangan *</label>
                                <textarea id="keterangan" name="keterangan" rows="2" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">{{ old('keterangan') }}</textarea>
                                @error('keterangan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="catatan" class="block text-xs font-medium text-gray-700 mb-2">Catatan</label>
                                <textarea id="catatan" name="catatan" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">{{ old('catatan') }}</textarea>
                                @error('catatan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('transaksi-kewangan.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-purple-600 text-white text-xs rounded hover:bg-purple-700">Simpan Perbelanjaan</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
