<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Agihan {{ $agihanZakat->no_agihan }} - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Edit Agihan {{ $agihanZakat->no_agihan }}</h1>
                        <p class="text-xs text-gray-600">Kemaskini maklumat agihan zakat</p>
                    </div>
                    <a href="{{ route('agihan-zakat.show', $agihanZakat) }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                        <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                        Kembali
                    </a>
                </div>

                <form method="POST" action="{{ route('agihan-zakat.update', $agihanZakat) }}">
                    @csrf
                    @method('PUT')

                    <!-- Section 1: Maklumat Agihan -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Agihan</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="tarikh_agihan" class="block text-xs font-medium text-gray-700 mb-2">Tarikh Agihan *</label>
                                <input type="date" id="tarikh_agihan" name="tarikh_agihan" value="{{ old('tarikh_agihan', $agihanZakat->tarikh_agihan->format('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('tarikh_agihan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="jumlah_diagihkan" class="block text-xs font-medium text-gray-700 mb-2">Jumlah Diagihkan (RM) *</label>
                                <input type="number" step="0.01" id="jumlah_diagihkan" name="jumlah_diagihkan" value="{{ old('jumlah_diagihkan', $agihanZakat->jumlah_diagihkan) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('jumlah_diagihkan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="kaedah_bayaran" class="block text-xs font-medium text-gray-700 mb-2">Kaedah Bayaran *</label>
                                <select id="kaedah_bayaran" name="kaedah_bayaran" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs" onchange="toggleBayaranFields()">
                                    <option value="">-- Pilih Kaedah --</option>
                                    <option value="Tunai" {{ old('kaedah_bayaran', $agihanZakat->kaedah_bayaran) == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                                    <option value="Cek" {{ old('kaedah_bayaran', $agihanZakat->kaedah_bayaran) == 'Cek' ? 'selected' : '' }}>Cek</option>
                                    <option value="Bank Transfer" {{ old('kaedah_bayaran', $agihanZakat->kaedah_bayaran) == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="E-Wallet" {{ old('kaedah_bayaran', $agihanZakat->kaedah_bayaran) == 'E-Wallet' ? 'selected' : '' }}>E-Wallet</option>
                                </select>
                                @error('kaedah_bayaran')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Maklumat Bayaran (Conditional) -->
                    <div id="bayaran_fields" class="bg-blue-50 rounded-lg p-4 mb-6" style="display: none;">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Bayaran</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="no_rujukan" class="block text-xs font-medium text-gray-700 mb-2">No Rujukan *</label>
                                <input type="text" id="no_rujukan" name="no_rujukan" value="{{ old('no_rujukan', $agihanZakat->no_rujukan) }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('no_rujukan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="nama_bank" class="block text-xs font-medium text-gray-700 mb-2">Nama Bank</label>
                                <input type="text" id="nama_bank" name="nama_bank" value="{{ old('nama_bank', $agihanZakat->nama_bank) }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('nama_bank')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="no_akaun" class="block text-xs font-medium text-gray-700 mb-2">No Akaun</label>
                                <input type="text" id="no_akaun" name="no_akaun" value="{{ old('no_akaun', $agihanZakat->no_akaun) }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('no_akaun')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Catatan -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Catatan</h2>
                        
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="catatan" class="block text-xs font-medium text-gray-700 mb-2">Catatan</label>
                                <textarea id="catatan" name="catatan" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">{{ old('catatan', $agihanZakat->catatan) }}</textarea>
                                @error('catatan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('agihan-zakat.show', $agihanZakat) }}" class="px-4 py-2 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">save</span>
                            Kemaskini Agihan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <x-footer />

    <script>
        function toggleBayaranFields() {
            const kaedah = document.getElementById('kaedah_bayaran').value;
            const bayaranFields = document.getElementById('bayaran_fields');
            const noRujukan = document.getElementById('no_rujukan');
            
            if (kaedah && kaedah !== 'Tunai') {
                bayaranFields.style.display = 'block';
                noRujukan.required = true;
            } else {
                bayaranFields.style.display = 'none';
                noRujukan.required = false;
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleBayaranFields();
        });
    </script>
</body>
</html>
