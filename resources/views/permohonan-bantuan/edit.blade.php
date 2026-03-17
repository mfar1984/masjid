<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Permohonan {{ $permohonanBantuan->no_permohonan }} - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Edit Permohonan {{ $permohonanBantuan->no_permohonan }}</h1>
                        <p class="text-xs text-gray-600">Kemaskini maklumat permohonan bantuan</p>
                    </div>
                    <a href="{{ route('permohonan-bantuan.show', $permohonanBantuan) }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                        <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                        Kembali
                    </a>
                </div>

                <form method="POST" action="{{ route('permohonan-bantuan.update', $permohonanBantuan) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Section 1: Maklumat Permohonan -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Permohonan</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="penerima_bantuan_id" class="block text-xs font-medium text-gray-700 mb-2">Pilih Penerima Bantuan *</label>
                                <select id="penerima_bantuan_id" name="penerima_bantuan_id" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Penerima --</option>
                                    @foreach($penerima as $p)
                                        <option value="{{ $p->id }}" {{ (old('penerima_bantuan_id', $permohonanBantuan->penerima_bantuan_id) == $p->id) ? 'selected' : '' }}>
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
                                        <option value="{{ $program->id }}" {{ (old('program_kebajikan_id', $permohonanBantuan->program_kebajikan_id) == $program->id) ? 'selected' : '' }}>
                                            {{ $program->nama_program }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('program_kebajikan_id')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="tarikh_permohonan" class="block text-xs font-medium text-gray-700 mb-2">Tarikh Permohonan *</label>
                                <input type="date" id="tarikh_permohonan" name="tarikh_permohonan" value="{{ old('tarikh_permohonan', $permohonanBantuan->tarikh_permohonan->format('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('tarikh_permohonan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="jenis_bantuan" class="block text-xs font-medium text-gray-700 mb-2">Jenis Bantuan *</label>
                                <select id="jenis_bantuan" name="jenis_bantuan" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Jenis --</option>
                                    <option value="Tunai" {{ old('jenis_bantuan', $permohonanBantuan->jenis_bantuan) == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                                    <option value="Barangan" {{ old('jenis_bantuan', $permohonanBantuan->jenis_bantuan) == 'Barangan' ? 'selected' : '' }}>Barangan</option>
                                    <option value="Perkhidmatan" {{ old('jenis_bantuan', $permohonanBantuan->jenis_bantuan) == 'Perkhidmatan' ? 'selected' : '' }}>Perkhidmatan</option>
                                    <option value="Campuran" {{ old('jenis_bantuan', $permohonanBantuan->jenis_bantuan) == 'Campuran' ? 'selected' : '' }}>Campuran</option>
                                </select>
                                @error('jenis_bantuan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="jumlah_dipohon" class="block text-xs font-medium text-gray-700 mb-2">Jumlah Dipohon (RM)</label>
                                <input type="number" step="0.01" id="jumlah_dipohon" name="jumlah_dipohon" value="{{ old('jumlah_dipohon', $permohonanBantuan->jumlah_dipohon) }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('jumlah_dipohon')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="keutamaan" class="block text-xs font-medium text-gray-700 mb-2">Keutamaan *</label>
                                <select id="keutamaan" name="keutamaan" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Keutamaan --</option>
                                    <option value="Biasa" {{ old('keutamaan', $permohonanBantuan->keutamaan) == 'Biasa' ? 'selected' : '' }}>Biasa</option>
                                    <option value="Sederhana" {{ old('keutamaan', $permohonanBantuan->keutamaan) == 'Sederhana' ? 'selected' : '' }}>Sederhana</option>
                                    <option value="Tinggi" {{ old('keutamaan', $permohonanBantuan->keutamaan) == 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                                    <option value="Kecemasan" {{ old('keutamaan', $permohonanBantuan->keutamaan) == 'Kecemasan' ? 'selected' : '' }}>Kecemasan</option>
                                </select>
                                @error('keutamaan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="tujuan_permohonan" class="block text-xs font-medium text-gray-700 mb-2">Tujuan Permohonan *</label>
                                <textarea id="tujuan_permohonan" name="tujuan_permohonan" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">{{ old('tujuan_permohonan', $permohonanBantuan->tujuan_permohonan) }}</textarea>
                                @error('tujuan_permohonan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('permohonan-bantuan.show', $permohonanBantuan) }}" class="px-4 py-2 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">save</span>
                            Kemaskini Permohonan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <x-footer />
</body>
</html>
