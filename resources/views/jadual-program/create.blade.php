<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jadual Program - E-Masjid</title>
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
                    <a href="{{ route('jadual-program.index') }}" class="mr-3 text-gray-500 hover:text-gray-700"><span class="material-icons">arrow_back</span></a>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Tambah Jadual Program</h1>
                        <p class="text-xs text-gray-600">Daftar jadual program baru</p>
                    </div>
                </div>
                <form action="{{ route('jadual-program.store') }}" method="POST">
                    @csrf
                    <div class="bg-gray-50 p-4 rounded-md border border-gray-200 max-w-2xl space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Program <span class="text-red-500">*</span></label>
                            <select name="program_id" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                                <option value="">-- Pilih Program --</option>
                                @foreach($programList as $program)
                                    <option value="{{ $program->id }}" {{ old('program_id') == $program->id ? 'selected' : '' }}>{{ $program->nama_program }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh <span class="text-red-500">*</span></label>
                            <input type="date" name="tarikh" value="{{ old('tarikh') }}" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Masa Mula <span class="text-red-500">*</span></label>
                                <input type="time" name="masa_mula" value="{{ old('masa_mula') }}" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Masa Tamat <span class="text-red-500">*</span></label>
                                <input type="time" name="masa_tamat" value="{{ old('masa_tamat') }}" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Lokasi</label>
                            <input type="text" name="lokasi" value="{{ old('lokasi') }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Penceramah</label>
                            <input type="text" name="penceramah" value="{{ old('penceramah') }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Catatan</label>
                            <textarea name="catatan" rows="2" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md">{{ old('catatan') }}</textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('jadual-program.index') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-gray-500 text-white text-xs rounded hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                            Kembali
                        </a>
                        <button type="submit" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">save</span>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <x-footer />
</body>
</html>
