<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jadual Penyelenggaraan - E-Masjid</title>
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
                <div class="mb-6">
                    <div class="flex items-center space-x-2 mb-2">
                        <a href="{{ route('jadual-penyelenggaraan.index') }}" class="text-blue-600 hover:text-blue-800">
                            <span class="material-icons" style="font-size: 20px !important;">arrow_back</span>
                        </a>
                        <h1 class="text-xl font-bold text-gray-900">Tambah Jadual Penyelenggaraan</h1>
                    </div>
                    <p class="text-xs text-gray-600">Sila isi maklumat jadual penyelenggaraan</p>
                </div>

                <form action="{{ route('jadual-penyelenggaraan.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Maklumat Asas -->
                        <div class="space-y-4">
                            <h3 class="text-sm font-semibold text-gray-900 border-b pb-2">Maklumat Asas</h3>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Nama Jadual *</label>
                                <input type="text" name="nama_jadual" value="{{ old('nama_jadual') }}" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('nama_jadual')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Jenis Item *</label>
                                <select name="jenis_item" id="jenisItem" required onchange="toggleItemDropdown()"
                                    class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="Aset" {{ old('jenis_item') == 'Aset' ? 'selected' : '' }}>Aset</option>
                                    <option value="Fasiliti" {{ old('jenis_item') == 'Fasiliti' ? 'selected' : '' }}>Fasiliti</option>
                                </select>
                            </div>

                            <div id="asetDropdown">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Pilih Aset *</label>
                                <select name="senarai_aset_id" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">-- Pilih Aset --</option>
                                    @foreach($senariAset as $aset)
                                        <option value="{{ $aset->id }}" {{ old('senarai_aset_id') == $aset->id ? 'selected' : '' }}>{{ $aset->nama_aset }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="fasilitiDropdown" style="display: none;">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Pilih Fasiliti *</label>
                                <select name="senarai_fasiliti_id" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">-- Pilih Fasiliti --</option>
                                    @foreach($senariFasiliti as $fasiliti)
                                        <option value="{{ $fasiliti->id }}" {{ old('senarai_fasiliti_id') == $fasiliti->id ? 'selected' : '' }}>{{ $fasiliti->nama_fasiliti }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Jenis Penyelenggaraan *</label>
                                <select name="jenis_penyelenggaraan" required class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="Berkala" {{ old('jenis_penyelenggaraan') == 'Berkala' ? 'selected' : '' }}>Berkala</option>
                                    <option value="Pembaikan" {{ old('jenis_penyelenggaraan') == 'Pembaikan' ? 'selected' : '' }}>Pembaikan</option>
                                    <option value="Pemeriksaan" {{ old('jenis_penyelenggaraan') == 'Pemeriksaan' ? 'selected' : '' }}>Pemeriksaan</option>
                                    <option value="Servis" {{ old('jenis_penyelenggaraan') == 'Servis' ? 'selected' : '' }}>Servis</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Kekerapan *</label>
                                <select name="kekerapan" required class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="Harian" {{ old('kekerapan') == 'Harian' ? 'selected' : '' }}>Harian</option>
                                    <option value="Mingguan" {{ old('kekerapan') == 'Mingguan' ? 'selected' : '' }}>Mingguan</option>
                                    <option value="Bulanan" {{ old('kekerapan', 'Bulanan') == 'Bulanan' ? 'selected' : '' }}>Bulanan</option>
                                    <option value="Suku Tahunan" {{ old('kekerapan') == 'Suku Tahunan' ? 'selected' : '' }}>Suku Tahunan</option>
                                    <option value="Tahunan" {{ old('kekerapan') == 'Tahunan' ? 'selected' : '' }}>Tahunan</option>
                                </select>
                            </div>
                        </div>

                        <!-- Maklumat Tambahan -->
                        <div class="space-y-4">
                            <h3 class="text-sm font-semibold text-gray-900 border-b pb-2">Maklumat Tambahan</h3>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Mula *</label>
                                    <input type="date" name="tarikh_mula" value="{{ old('tarikh_mula', date('Y-m-d')) }}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Akhir</label>
                                    <input type="date" name="tarikh_akhir" value="{{ old('tarikh_akhir') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Skop Kerja</label>
                                <textarea name="skop_kerja" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('skop_kerja') }}</textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Nama Vendor</label>
                                    <input type="text" name="vendor_nama" value="{{ old('vendor_nama') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">No. Telefon Vendor</label>
                                    <input type="text" name="vendor_telefon" value="{{ old('vendor_telefon') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Anggaran Kos (RM)</label>
                                <input type="number" name="anggaran_kos" value="{{ old('anggaran_kos') }}" step="0.01" min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Status *</label>
                                <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="Aktif" {{ old('status', 'Aktif') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Tidak Aktif" {{ old('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Catatan</label>
                                <textarea name="catatan" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('catatan') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="mt-6 flex justify-end space-x-2">
                        <a href="{{ route('jadual-penyelenggaraan.index') }}" class="px-4 py-2 text-xs text-gray-700 bg-gray-100 rounded hover:bg-gray-200">Batal</a>
                        <button type="submit" class="px-4 py-2 text-xs text-white bg-blue-600 rounded hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <x-footer />

    <script>
        function toggleItemDropdown() {
            const jenisItem = document.getElementById('jenisItem').value;
            document.getElementById('asetDropdown').style.display = jenisItem === 'Aset' ? 'block' : 'none';
            document.getElementById('fasilitiDropdown').style.display = jenisItem === 'Fasiliti' ? 'block' : 'none';
        }
        document.addEventListener('DOMContentLoaded', toggleItemDropdown);
    </script>
</body>
</html>
