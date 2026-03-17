<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Permohonan Zakat - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Tambah Permohonan Zakat</h1>
                        <p class="text-xs text-gray-600">Cipta permohonan bantuan zakat baharu</p>
                    </div>
                    <a href="{{ route('permohonan-zakat.index') }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                        <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                        Kembali
                    </a>
                </div>

                <form method="POST" action="{{ route('permohonan-zakat.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Workflow Info -->
                    @if($settings['require_mesyuarat_attachment'] || $settings['require_supporting_docs'])
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <span class="material-icons text-blue-400" style="font-size: 20px;">info</span>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-xs font-medium text-blue-800">Keperluan Permohonan</h3>
                                <div class="mt-2 text-xs text-blue-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        @if($settings['require_supporting_docs'])
                                        <li>Dokumen sokongan adalah <strong>WAJIB</strong> dilampirkan</li>
                                        @endif
                                        @if($settings['require_mesyuarat_attachment'])
                                        <li>Minit mesyuarat diperlukan untuk kelulusan</li>
                                        @endif
                                        <li>Saiz fail maksimum: <strong>{{ $settings['max_file_size_mb'] }}MB</strong></li>
                                        <li>Format fail diterima: <strong>{{ strtoupper(implode(', ', $settings['allowed_file_types'])) }}</strong></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Section 1: Maklumat Permohonan -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Permohonan</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="asnaf_id" class="block text-xs font-medium text-gray-700 mb-2">Pilih Asnaf *</label>
                                <select id="asnaf_id" name="asnaf_id" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Asnaf --</option>
                                    @foreach($asnafList as $asnaf)
                                        <option value="{{ $asnaf->id }}" {{ old('asnaf_id') == $asnaf->id ? 'selected' : '' }}>
                                            {{ $asnaf->nama }} ({{ $asnaf->no_ic }}) - {{ $asnaf->kategori_asnaf }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('asnaf_id')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="tarikh_permohonan" class="block text-xs font-medium text-gray-700 mb-2">Tarikh Permohonan *</label>
                                <input type="date" id="tarikh_permohonan" name="tarikh_permohonan" value="{{ old('tarikh_permohonan', date('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('tarikh_permohonan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="jenis_bantuan" class="block text-xs font-medium text-gray-700 mb-2">Jenis Bantuan *</label>
                                <select id="jenis_bantuan" name="jenis_bantuan" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Jenis --</option>
                                    <option value="Tunai" {{ old('jenis_bantuan') == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                                    <option value="Barangan" {{ old('jenis_bantuan') == 'Barangan' ? 'selected' : '' }}>Barangan</option>
                                    <option value="Pendidikan" {{ old('jenis_bantuan') == 'Pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                                    <option value="Perubatan" {{ old('jenis_bantuan') == 'Perubatan' ? 'selected' : '' }}>Perubatan</option>
                                    <option value="Kecemasan" {{ old('jenis_bantuan') == 'Kecemasan' ? 'selected' : '' }}>Kecemasan</option>
                                </select>
                                @error('jenis_bantuan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="kategori_bantuan" class="block text-xs font-medium text-gray-700 mb-2">Kategori Bantuan *</label>
                                <select id="kategori_bantuan" name="kategori_bantuan" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="Bulanan" {{ old('kategori_bantuan') == 'Bulanan' ? 'selected' : '' }}>Bulanan</option>
                                    <option value="Sekali" {{ old('kategori_bantuan') == 'Sekali' ? 'selected' : '' }}>Sekali</option>
                                    <option value="Khas" {{ old('kategori_bantuan') == 'Khas' ? 'selected' : '' }}>Khas</option>
                                </select>
                                @error('kategori_bantuan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="jumlah_dipohon" class="block text-xs font-medium text-gray-700 mb-2">Jumlah Dipohon (RM) *</label>
                                <input type="number" step="0.01" id="jumlah_dipohon" name="jumlah_dipohon" value="{{ old('jumlah_dipohon') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('jumlah_dipohon')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="dokumen_sokongan" class="block text-xs font-medium text-gray-700 mb-2">
                                    Dokumen Sokongan @if($settings['require_supporting_docs'])<span class="text-red-600">*</span>@endif
                                </label>
                                <input type="file" id="dokumen_sokongan" name="dokumen_sokongan" accept=".{{ implode(',.',$settings['allowed_file_types']) }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs" @if($settings['require_supporting_docs']) required @endif>
                                <p class="mt-1 text-xs text-gray-500">
                                    Format: {{ strtoupper(implode(', ', $settings['allowed_file_types'])) }} (Max: {{ $settings['max_file_size_mb'] }}MB)
                                    @if($settings['require_supporting_docs'])
                                        <span class="text-orange-600 font-medium">- WAJIB</span>
                                    @endif
                                </p>
                                @error('dokumen_sokongan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="sebab_permohonan" class="block text-xs font-medium text-gray-700 mb-2">Sebab Permohonan *</label>
                                <textarea id="sebab_permohonan" name="sebab_permohonan" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">{{ old('sebab_permohonan') }}</textarea>
                                @error('sebab_permohonan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('permohonan-zakat.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">save</span>
                            Simpan Permohonan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <x-footer />
</body>
</html>
