<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kerja Penyelenggaraan - E-Masjid</title>
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
                <div class="mb-6">
                    <div class="flex items-center space-x-2 mb-2">
                        <a href="{{ route('kerja-penyelenggaraan.index') }}" class="text-blue-600 hover:text-blue-800">
                            <span class="material-icons" style="font-size: 20px !important;">arrow_back</span>
                        </a>
                        <h1 class="text-xl font-bold text-gray-900">Tambah Kerja Penyelenggaraan</h1>
                    </div>
                    <p class="text-xs text-gray-600">Sila isi maklumat kerja penyelenggaraan</p>
                </div>

                <form action="{{ route('kerja-penyelenggaraan.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Maklumat Asas -->
                        <div class="space-y-4">
                            <h3 class="text-sm font-semibold text-gray-900 border-b pb-2">Maklumat Asas</h3>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Jadual Penyelenggaraan (Pilihan)</label>
                                <select name="jadual_penyelenggaraan_id" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">-- Tiada Jadual --</option>
                                    @foreach($jadualPenyelenggaraan as $jadual)
                                        <option value="{{ $jadual->id }}" {{ old('jadual_penyelenggaraan_id') == $jadual->id ? 'selected' : '' }}>{{ $jadual->nama_jadual }}</option>
                                    @endforeach
                                </select>
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
                                <label class="block text-xs font-medium text-gray-700 mb-1">Jenis Kerja *</label>
                                <select name="jenis_kerja" required class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @foreach(['Penyelenggaraan Berkala', 'Pembaikan', 'Pemeriksaan', 'Servis', 'Kecemasan'] as $jenis)
                                        <option value="{{ $jenis }}" {{ old('jenis_kerja') == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Kerja *</label>
                                <input type="date" name="tarikh_kerja" value="{{ old('tarikh_kerja', date('Y-m-d')) }}" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Masa Mula</label>
                                    <input type="time" name="masa_mula" value="{{ old('masa_mula') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Masa Tamat</label>
                                    <input type="time" name="masa_tamat" value="{{ old('masa_tamat') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Penerangan Kerja *</label>
                                <textarea name="penerangan_kerja" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('penerangan_kerja') }}</textarea>
                            </div>
                        </div>

                        <!-- Maklumat Tambahan -->
                        <div class="space-y-4">
                            <h3 class="text-sm font-semibold text-gray-900 border-b pb-2">Maklumat Vendor & Kos</h3>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Nama Vendor</label>
                                <input type="text" name="vendor_nama" value="{{ old('vendor_nama') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">No. Telefon</label>
                                    <input type="text" name="vendor_telefon" value="{{ old('vendor_telefon') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Kos (RM)</label>
                                    <input type="number" name="kos" value="{{ old('kos', 0) }}" step="0.01" min="0"
                                        class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Alamat Vendor</label>
                                <input type="text" name="vendor_alamat" value="{{ old('vendor_alamat') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <h3 class="text-sm font-semibold text-gray-900 border-b pb-2 pt-4">Kondisi Item</h3>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Kondisi Sebelum</label>
                                    <select name="kondisi_sebelum" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">-- Pilih --</option>
                                        @foreach(['Baik', 'Sederhana', 'Teruk', 'Rosak'] as $kondisi)
                                            <option value="{{ $kondisi }}" {{ old('kondisi_sebelum') == $kondisi ? 'selected' : '' }}>{{ $kondisi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Kondisi Selepas</label>
                                    <select name="kondisi_selepas" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">-- Pilih --</option>
                                        @foreach(['Baik', 'Sederhana', 'Teruk', 'Rosak'] as $kondisi)
                                            <option value="{{ $kondisi }}" {{ old('kondisi_selepas') == $kondisi ? 'selected' : '' }}>{{ $kondisi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Status *</label>
                                <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @foreach(['Dirancang', 'Sedang Berjalan', 'Selesai', 'Dibatalkan', 'Tertangguh'] as $status)
                                        <option value="{{ $status }}" {{ old('status', 'Dirancang') == $status ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Catatan</label>
                                <textarea name="catatan" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('catatan') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-2">
                        <a href="{{ route('kerja-penyelenggaraan.index') }}" class="px-4 py-2 text-xs text-gray-700 bg-gray-100 rounded hover:bg-gray-200">Batal</a>
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
