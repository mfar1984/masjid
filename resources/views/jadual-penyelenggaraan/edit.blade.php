<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Jadual Penyelenggaraan - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900">Edit Jadual Penyelenggaraan</h1>
                    </div>
                    <p class="text-xs text-gray-600">{{ $jadualPenyelenggaraan->no_jadual }}</p>
                </div>

                <form action="{{ route('jadual-penyelenggaraan.update', $jadualPenyelenggaraan) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Maklumat Asas -->
                        <div class="space-y-4">
                            <h3 class="text-sm font-semibold text-gray-900 border-b pb-2">Maklumat Asas</h3>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Nama Jadual *</label>
                                <input type="text" name="nama_jadual" value="{{ old('nama_jadual', $jadualPenyelenggaraan->nama_jadual) }}" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Jenis Item *</label>
                                <select name="jenis_item" id="jenisItem" required onchange="toggleItemDropdown()"
                                    class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="Aset" {{ old('jenis_item', $jadualPenyelenggaraan->jenis_item) == 'Aset' ? 'selected' : '' }}>Aset</option>
                                    <option value="Fasiliti" {{ old('jenis_item', $jadualPenyelenggaraan->jenis_item) == 'Fasiliti' ? 'selected' : '' }}>Fasiliti</option>
                                </select>
                            </div>

                            <div id="asetDropdown">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Pilih Aset</label>
                                <select name="senarai_aset_id" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">-- Pilih Aset --</option>
                                    @foreach($senariAset as $aset)
                                        <option value="{{ $aset->id }}" {{ old('senarai_aset_id', $jadualPenyelenggaraan->senarai_aset_id) == $aset->id ? 'selected' : '' }}>{{ $aset->nama_aset }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="fasilitiDropdown" style="display: none;">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Pilih Fasiliti</label>
                                <select name="senarai_fasiliti_id" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">-- Pilih Fasiliti --</option>
                                    @foreach($senariFasiliti as $fasiliti)
                                        <option value="{{ $fasiliti->id }}" {{ old('senarai_fasiliti_id', $jadualPenyelenggaraan->senarai_fasiliti_id) == $fasiliti->id ? 'selected' : '' }}>{{ $fasiliti->nama_fasiliti }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Jenis Penyelenggaraan *</label>
                                <select name="jenis_penyelenggaraan" required class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @foreach(['Berkala', 'Pembaikan', 'Pemeriksaan', 'Servis'] as $jenis)
                                        <option value="{{ $jenis }}" {{ old('jenis_penyelenggaraan', $jadualPenyelenggaraan->jenis_penyelenggaraan) == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Kekerapan *</label>
                                <select name="kekerapan" required class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @foreach(['Harian', 'Mingguan', 'Bulanan', 'Suku Tahunan', 'Tahunan'] as $kekerapan)
                                        <option value="{{ $kekerapan }}" {{ old('kekerapan', $jadualPenyelenggaraan->kekerapan) == $kekerapan ? 'selected' : '' }}>{{ $kekerapan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Maklumat Tambahan -->
                        <div class="space-y-4">
                            <h3 class="text-sm font-semibold text-gray-900 border-b pb-2">Maklumat Tambahan</h3>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Mula *</label>
                                    <input type="date" name="tarikh_mula" value="{{ old('tarikh_mula', $jadualPenyelenggaraan->tarikh_mula?->format('Y-m-d')) }}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Akhir</label>
                                    <input type="date" name="tarikh_akhir" value="{{ old('tarikh_akhir', $jadualPenyelenggaraan->tarikh_akhir?->format('Y-m-d')) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Penyelenggaraan Seterusnya</label>
                                <input type="date" name="tarikh_penyelenggaraan_seterusnya" value="{{ old('tarikh_penyelenggaraan_seterusnya', $jadualPenyelenggaraan->tarikh_penyelenggaraan_seterusnya?->format('Y-m-d')) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Skop Kerja</label>
                                <textarea name="skop_kerja" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('skop_kerja', $jadualPenyelenggaraan->skop_kerja) }}</textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Nama Vendor</label>
                                    <input type="text" name="vendor_nama" value="{{ old('vendor_nama', $jadualPenyelenggaraan->vendor_nama) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">No. Telefon Vendor</label>
                                    <input type="text" name="vendor_telefon" value="{{ old('vendor_telefon', $jadualPenyelenggaraan->vendor_telefon) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Anggaran Kos (RM)</label>
                                <input type="number" name="anggaran_kos" value="{{ old('anggaran_kos', $jadualPenyelenggaraan->anggaran_kos) }}" step="0.01" min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Status *</label>
                                <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @foreach(['Aktif', 'Tidak Aktif', 'Selesai'] as $status)
                                        <option value="{{ $status }}" {{ old('status', $jadualPenyelenggaraan->status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Catatan</label>
                                <textarea name="catatan" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('catatan', $jadualPenyelenggaraan->catatan) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="mt-6 flex justify-end space-x-2">
                        <a href="{{ route('jadual-penyelenggaraan.index') }}" class="px-4 py-2 text-xs text-gray-700 bg-gray-100 rounded hover:bg-gray-200">Batal</a>
                        <button type="submit" class="px-4 py-2 text-xs text-white bg-blue-600 rounded hover:bg-blue-700">Kemaskini</button>
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
