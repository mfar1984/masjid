<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Jadual Ceramah - E-Masjid</title>
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
                <div class="flex items-center mb-6">
                    <a href="{{ route('jadual-ceramah.index') }}" class="mr-3 text-gray-500 hover:text-gray-700">
                        <span class="material-icons">arrow_back</span>
                    </a>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Edit Jadual Ceramah</h1>
                        <p class="text-xs text-gray-600">{{ $jadualCeramah->tajuk_ceramah }}</p>
                    </div>
                </div>
                <form action="{{ route('jadual-ceramah.update', $jadualCeramah) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Ceramah</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Penceramah <span class="text-red-500">*</span></label>
                                    <select name="penceramah_id" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                        <option value="">-- Pilih Penceramah --</option>
                                        @foreach($penceramahList as $penceramah)
                                            <option value="{{ $penceramah->id }}" {{ old('penceramah_id', $jadualCeramah->penceramah_id) == $penceramah->id ? 'selected' : '' }}>{{ $penceramah->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Tajuk Ceramah <span class="text-red-500">*</span></label>
                                    <input type="text" name="tajuk_ceramah" value="{{ old('tajuk_ceramah', $jadualCeramah->tajuk_ceramah) }}" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Jenis Ceramah <span class="text-red-500">*</span></label>
                                    <select name="jenis_ceramah" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                        @foreach(['Kuliah Subuh', 'Kuliah Maghrib', 'Kuliah Isyak', 'Ceramah Jumaat', 'Ceramah Khas', 'Tazkirah', 'Lain-lain'] as $jenis)
                                            <option value="{{ $jenis }}" {{ old('jenis_ceramah', $jadualCeramah->jenis_ceramah) === $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh <span class="text-red-500">*</span></label>
                                    <input type="date" name="tarikh" value="{{ old('tarikh', $jadualCeramah->tarikh->format('Y-m-d')) }}" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Masa Mula <span class="text-red-500">*</span></label>
                                        <input type="time" name="masa_mula" value="{{ old('masa_mula', $jadualCeramah->masa_mula) }}" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Masa Tamat <span class="text-red-500">*</span></label>
                                        <input type="time" name="masa_tamat" value="{{ old('masa_tamat', $jadualCeramah->masa_tamat) }}" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Lokasi</label>
                                    <input type="text" name="lokasi" value="{{ old('lokasi', $jadualCeramah->lokasi) }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                                    <select name="status" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                        @foreach(['Dijadual', 'Selesai', 'Batal'] as $status)
                                            <option value="{{ $status }}" {{ old('status', $jadualCeramah->status) === $status ? 'selected' : '' }}>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Bayaran</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Jenis Bayaran <span class="text-red-500">*</span></label>
                                    <select name="jenis_bayaran" id="jenis_bayaran" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                        @foreach(['Sekali', 'Mingguan', 'Bulanan', 'Percuma'] as $jenis)
                                            <option value="{{ $jenis }}" {{ old('jenis_bayaran', $jadualCeramah->jenis_bayaran) === $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="bayaran_fields">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Kadar Bayaran (RM)</label>
                                        <input type="number" name="kadar_bayaran" value="{{ old('kadar_bayaran', $jadualCeramah->kadar_bayaran) }}" step="0.01" min="0" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                    </div>
                                </div>
                                <div class="border-t pt-4 mt-4">
                                    <h4 class="text-xs font-medium text-gray-700 mb-3">Kos Tambahan (Optional)</h4>
                                    <div class="space-y-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Kos Pengangkutan (RM)</label>
                                            <input type="number" name="kos_pengangkutan" value="{{ old('kos_pengangkutan', $jadualCeramah->kos_pengangkutan) }}" step="0.01" min="0" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Kos Penginapan (RM)</label>
                                            <input type="number" name="kos_penginapan" value="{{ old('kos_penginapan', $jadualCeramah->kos_penginapan) }}" step="0.01" min="0" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Kos Makan Minum (RM)</label>
                                            <input type="number" name="kos_makan_minum" value="{{ old('kos_makan_minum', $jadualCeramah->kos_makan_minum) }}" step="0.01" min="0" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Kos Lain (RM)</label>
                                            <input type="number" name="kos_lain" value="{{ old('kos_lain', $jadualCeramah->kos_lain) }}" step="0.01" min="0" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Catatan Kos</label>
                                            <textarea name="catatan_kos" rows="2" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">{{ old('catatan_kos', $jadualCeramah->catatan_kos) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Catatan</label>
                        <textarea name="catatan" rows="2" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">{{ old('catatan', $jadualCeramah->catatan) }}</textarea>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('jadual-ceramah.index') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-gray-500 text-white text-xs rounded hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                            Kembali
                        </a>
                        <button type="submit" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">save</span>
                            Kemaskini
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <x-footer />
    <script>
        document.getElementById('jenis_bayaran').addEventListener('change', function() {
            const bayaranFields = document.getElementById('bayaran_fields');
            bayaranFields.style.display = this.value === 'Percuma' ? 'none' : 'block';
        });
    </script>
</body>
</html>
