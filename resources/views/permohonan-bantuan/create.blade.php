<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Permohonan Bantuan - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Tambah Permohonan Bantuan</h1>
                        <p class="text-xs text-gray-600">Cipta permohonan bantuan kebajikan baharu</p>
                    </div>
                    <a href="{{ route('permohonan-bantuan.index') }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                        <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                        Kembali
                    </a>
                </div>

                <form method="POST" action="{{ route('permohonan-bantuan.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Info Box: Workflow Settings -->
                    @if((isset($settings['permohonan_cooldown_days']) && $settings['permohonan_cooldown_days'] > 0) || (isset($settings['permohonan_max_per_year']) && $settings['permohonan_max_per_year'] > 0))
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <h3 class="text-xs font-semibold text-blue-900 mb-2">
                            <span class="material-icons text-sm align-middle mr-1">info</span>
                            Maklumat Penting
                        </h3>
                        <ul class="text-[10px] text-blue-800 space-y-1 ml-5 list-disc">
                            @if(isset($settings['permohonan_cooldown_days']) && $settings['permohonan_cooldown_days'] > 0)
                            <li>Penerima perlu menunggu {{ $settings['permohonan_cooldown_days'] }} hari selepas permohonan terakhir sebelum membuat permohonan baharu</li>
                            @endif
                            @if(isset($settings['permohonan_max_per_year']) && $settings['permohonan_max_per_year'] > 0)
                            <li>Had maksimum permohonan: {{ $settings['permohonan_max_per_year'] }} permohonan setahun bagi setiap penerima</li>
                            @endif
                            @if(isset($settings['auto_approve_amount']) && $settings['auto_approve_amount'] > 0)
                            <li>Permohonan dengan jumlah ≤ RM {{ number_format($settings['auto_approve_amount'], 2) }} akan diluluskan secara automatik</li>
                            @endif
                        </ul>
                    </div>
                    @endif

                    <!-- Section 1: Maklumat Permohonan -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Permohonan</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="penerima_bantuan_id" class="block text-xs font-medium text-gray-700 mb-2">Pilih Penerima Bantuan *</label>
                                <select id="penerima_bantuan_id" name="penerima_bantuan_id" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Penerima --</option>
                                    @foreach($penerima as $p)
                                        <option value="{{ $p->id }}" {{ old('penerima_bantuan_id') == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama_penuh }} ({{ $p->no_kp }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('penerima_bantuan_id')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="program_kebajikan_id" class="block text-xs font-medium text-gray-700 mb-2">Pilih Program *</label>
                                <select id="program_kebajikan_id" name="program_kebajikan_id" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Program --</option>
                                    @foreach($programs as $program)
                                        <option value="{{ $program->id }}" {{ old('program_kebajikan_id') == $program->id ? 'selected' : '' }}>
                                            {{ $program->nama_program }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('program_kebajikan_id')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
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
                                    @foreach($jenisBantuan as $jenis)
                                        <option value="{{ $jenis->nama_kategori }}" {{ old('jenis_bantuan') == $jenis->nama_kategori ? 'selected' : '' }}>
                                            {{ $jenis->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jenis_bantuan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="jumlah_dipohon" class="block text-xs font-medium text-gray-700 mb-2">Jumlah Dipohon (RM)</label>
                                <input type="number" step="0.01" id="jumlah_dipohon" name="jumlah_dipohon" value="{{ old('jumlah_dipohon') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @if(isset($settings['auto_approve_amount']) && $settings['auto_approve_amount'] > 0)
                                <p class="text-[10px] text-green-600 mt-1">
                                    <span class="material-icons text-xs align-middle">check_circle</span>
                                    Permohonan ≤ RM {{ number_format($settings['auto_approve_amount'], 2) }} akan diluluskan secara automatik
                                </p>
                                @endif
                                @error('jumlah_dipohon')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="keutamaan" class="block text-xs font-medium text-gray-700 mb-2">Keutamaan *</label>
                                <select id="keutamaan" name="keutamaan" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Keutamaan --</option>
                                    @foreach($keutamaan as $item)
                                        <option value="{{ $item->nama_kategori }}" {{ old('keutamaan') == $item->nama_kategori ? 'selected' : '' }}>
                                            {{ $item->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('keutamaan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="tujuan_permohonan" class="block text-xs font-medium text-gray-700 mb-2">Tujuan Permohonan *</label>
                                <textarea id="tujuan_permohonan" name="tujuan_permohonan" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">{{ old('tujuan_permohonan') }}</textarea>
                                @error('tujuan_permohonan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('permohonan-bantuan.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
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
