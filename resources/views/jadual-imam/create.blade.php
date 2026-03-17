<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jadual Imam - E-Masjid</title>
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
                    <a href="{{ route('jadual-imam.index') }}" class="mr-3 text-gray-500 hover:text-gray-700">
                        <span class="material-icons">arrow_back</span>
                    </a>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Tambah Jadual Imam</h1>
                        <p class="text-xs text-gray-600">Daftar jadual tugas imam baru</p>
                    </div>
                </div>
                <form action="{{ route('jadual-imam.store') }}" method="POST">
                    @csrf
                    <div class="bg-gray-50 p-4 rounded-md border border-gray-200 max-w-2xl">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Pilih dari AJK</label>
                                <select name="ajk_id" id="ajk_id" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                    <option value="">-- Pilih AJK (Optional) --</option>
                                    @foreach($ajkList as $ajk)
                                        <option value="{{ $ajk->id }}" data-nama="{{ $ajk->nama }}" {{ old('ajk_id') == $ajk->id ? 'selected' : '' }}>{{ $ajk->nama }} - {{ $ajk->jawatan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Nama Imam <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_imam" id="nama_imam" value="{{ old('nama_imam') }}" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                @error('nama_imam')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh <span class="text-red-500">*</span></label>
                                <input type="date" name="tarikh" value="{{ old('tarikh') }}" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Waktu Solat <span class="text-red-500">*</span></label>
                                <select name="waktu_solat" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                    @foreach(['Subuh', 'Zohor', 'Asar', 'Maghrib', 'Isyak', 'Jumaat', 'Tarawih', 'Hari Raya'] as $waktu)
                                        <option value="{{ $waktu }}" {{ old('waktu_solat') === $waktu ? 'selected' : '' }}>{{ $waktu }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Catatan</label>
                                <textarea name="catatan" rows="2" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">{{ old('catatan') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('jadual-imam.index') }}" class="px-4 py-2 text-xs font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Batal</a>
                        <button type="submit" class="px-4 py-2 text-xs font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <x-footer />
    <script>
        document.getElementById('ajk_id').addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            if (selected.value) {
                document.getElementById('nama_imam').value = selected.dataset.nama;
            }
        });
    </script>
</body>
</html>
