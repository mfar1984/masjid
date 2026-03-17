<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kemaskini Jadual Imam & Bilal - E-Masjid</title>
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
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Kemaskini Jadual Imam & Bilal</h1>
                        <p class="text-xs text-gray-600">{{ $jadualImamBilal->tarikh->format('d/m/Y') }} - {{ $jadualImamBilal->waktu_solat }}</p>
                    </div>
                    @if($jadualImamBilal->jenis_jadual === 'Auto')
                        <span class="inline-flex items-center px-3 py-1 rounded-sm text-xs font-medium bg-purple-100 text-purple-800">
                            <span class="material-icons mr-1" style="font-size: 14px;">autorenew</span>
                            Auto-Generated
                        </span>
                    @endif
                </div>

                <form action="{{ route('jadual-imam-bilal.update', $jadualImamBilal) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Maklumat Jadual -->
                        <div class="bg-gray-50 p-4 rounded-sm border border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-blue-600" style="font-size: 18px;">event</span>
                                Maklumat Jadual
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="tarikh" class="block text-xs font-medium text-gray-700 mb-1">Tarikh <span class="text-red-500">*</span></label>
                                    <input type="date" id="tarikh" name="tarikh" value="{{ old('tarikh', $jadualImamBilal->tarikh->format('Y-m-d')) }}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label for="waktu_solat" class="block text-xs font-medium text-gray-700 mb-1">Waktu Solat <span class="text-red-500">*</span></label>
                                    <select id="waktu_solat" name="waktu_solat" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        @foreach(['Subuh', 'Zohor', 'Asar', 'Maghrib', 'Isyak', 'Jumaat', 'Tarawih', 'Hari Raya'] as $waktu)
                                            <option value="{{ $waktu }}" {{ old('waktu_solat', $jadualImamBilal->waktu_solat) == $waktu ? 'selected' : '' }}>{{ $waktu }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Maklumat Imam -->
                        <div class="bg-gray-50 p-4 rounded-sm border border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-green-600" style="font-size: 18px;">person</span>
                                Maklumat Imam
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="imam_ajk_id" class="block text-xs font-medium text-gray-700 mb-1">Pilih Imam</label>
                                    <select id="imam_ajk_id" name="imam_ajk_id"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">-- Pilih Imam --</option>
                                        @foreach($imamList as $imam)
                                            <option value="{{ $imam->id }}" {{ old('imam_ajk_id', $jadualImamBilal->imam_ajk_id) == $imam->id ? 'selected' : '' }}>
                                                Imam {{ $imam->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($imamList->isEmpty())
                                        <p class="mt-1 text-[10px] text-yellow-600">Tiada Imam aktif. Sila tambah AJK dengan jawatan Imam.</p>
                                    @endif
                                </div>
                                <div>
                                    <label for="nama_imam" class="block text-xs font-medium text-gray-700 mb-1">Atau Masukkan Nama</label>
                                    <input type="text" id="nama_imam" name="nama_imam" value="{{ old('nama_imam', $jadualImamBilal->nama_imam) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label for="status_imam" class="block text-xs font-medium text-gray-700 mb-1">Status Imam <span class="text-red-500">*</span></label>
                                    <select id="status_imam" name="status_imam" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        @foreach(['Dijadual', 'Selesai', 'Ganti', 'Batal'] as $status)
                                            <option value="{{ $status }}" {{ old('status_imam', $jadualImamBilal->status_imam) == $status ? 'selected' : '' }}>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="imam_ganti" class="block text-xs font-medium text-gray-700 mb-1">Nama Imam Ganti</label>
                                    <input type="text" id="imam_ganti" name="imam_ganti" value="{{ old('imam_ganti', $jadualImamBilal->imam_ganti) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Isi jika status adalah Ganti">
                                </div>
                            </div>
                        </div>

                        <!-- Maklumat Bilal -->
                        <div class="bg-gray-50 p-4 rounded-sm border border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-orange-600" style="font-size: 18px;">record_voice_over</span>
                                Maklumat Bilal
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="bilal_ajk_id" class="block text-xs font-medium text-gray-700 mb-1">Pilih Bilal</label>
                                    <select id="bilal_ajk_id" name="bilal_ajk_id"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">-- Pilih Bilal --</option>
                                        @foreach($bilalList as $bilal)
                                            <option value="{{ $bilal->id }}" {{ old('bilal_ajk_id', $jadualImamBilal->bilal_ajk_id) == $bilal->id ? 'selected' : '' }}>
                                                Bilal {{ $bilal->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($bilalList->isEmpty())
                                        <p class="mt-1 text-[10px] text-yellow-600">Tiada Bilal aktif. Sila tambah AJK dengan jawatan Bilal.</p>
                                    @endif
                                </div>
                                <div>
                                    <label for="nama_bilal" class="block text-xs font-medium text-gray-700 mb-1">Atau Masukkan Nama</label>
                                    <input type="text" id="nama_bilal" name="nama_bilal" value="{{ old('nama_bilal', $jadualImamBilal->nama_bilal) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label for="status_bilal" class="block text-xs font-medium text-gray-700 mb-1">Status Bilal <span class="text-red-500">*</span></label>
                                    <select id="status_bilal" name="status_bilal" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        @foreach(['Dijadual', 'Selesai', 'Ganti', 'Batal'] as $status)
                                            <option value="{{ $status }}" {{ old('status_bilal', $jadualImamBilal->status_bilal) == $status ? 'selected' : '' }}>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="bilal_ganti" class="block text-xs font-medium text-gray-700 mb-1">Nama Bilal Ganti</label>
                                    <input type="text" id="bilal_ganti" name="bilal_ganti" value="{{ old('bilal_ganti', $jadualImamBilal->bilal_ganti) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Isi jika status adalah Ganti">
                                </div>
                            </div>
                        </div>

                        <!-- Catatan -->
                        <div class="bg-gray-50 p-4 rounded-sm border border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-gray-600" style="font-size: 18px;">notes</span>
                                Catatan
                            </h3>
                            <div>
                                <textarea id="catatan" name="catatan" rows="4"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('catatan', $jadualImamBilal->catatan) }}</textarea>
                            </div>
                            @if($jadualImamBilal->jenis_jadual === 'Auto')
                                <p class="mt-2 text-xs text-orange-600">
                                    <span class="material-icons align-middle" style="font-size: 14px;">info</span>
                                    Jadual ini dijana secara automatik. Sebarang perubahan akan menukar jenis kepada Manual.
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 flex items-center justify-end space-x-3">
                        <a href="{{ route('jadual-imam-bilal.index') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-gray-500 text-white text-xs rounded hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
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
</body>
</html>
