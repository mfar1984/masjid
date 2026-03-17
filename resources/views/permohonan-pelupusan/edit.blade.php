<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Permohonan Pelupusan - E-Masjid</title>
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
                <div class="mb-6 flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('permohonan-pelupusan.index') }}" class="text-blue-600 hover:text-blue-800">
                            <span class="material-icons" style="font-size: 20px !important;">arrow_back</span>
                        </a>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">Edit Permohonan Pelupusan</h1>
                            <p class="text-xs text-gray-600">{{ $permohonanPelupusan->no_rujukan }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('permohonan-pelupusan.update', $permohonanPelupusan) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Aset <span class="text-red-500">*</span></label>
                            <select name="senarai_aset_id" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">
                                @foreach($senariAset as $aset)
                                    <option value="{{ $aset->id }}" {{ old('senarai_aset_id', $permohonanPelupusan->senarai_aset_id) == $aset->id ? 'selected' : '' }}>
                                        {{ $aset->no_siri }} - {{ $aset->nama_aset }}
                                    </option>
                                @endforeach
                            </select>
                            @error('senarai_aset_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Permohonan <span class="text-red-500">*</span></label>
                            <input type="date" name="tarikh_permohonan" value="{{ old('tarikh_permohonan', $permohonanPelupusan->tarikh_permohonan->format('Y-m-d')) }}" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">
                            @error('tarikh_permohonan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Kaedah Pelupusan <span class="text-red-500">*</span></label>
                            <select name="kaedah_pelupusan" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">
                                <option value="Jualan" {{ old('kaedah_pelupusan', $permohonanPelupusan->kaedah_pelupusan) == 'Jualan' ? 'selected' : '' }}>Jualan</option>
                                <option value="Derma" {{ old('kaedah_pelupusan', $permohonanPelupusan->kaedah_pelupusan) == 'Derma' ? 'selected' : '' }}>Derma</option>
                                <option value="Buang" {{ old('kaedah_pelupusan', $permohonanPelupusan->kaedah_pelupusan) == 'Buang' ? 'selected' : '' }}>Buang</option>
                                <option value="Tukar Ganti" {{ old('kaedah_pelupusan', $permohonanPelupusan->kaedah_pelupusan) == 'Tukar Ganti' ? 'selected' : '' }}>Tukar Ganti</option>
                            </select>
                            @error('kaedah_pelupusan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Nilai Pelupusan (RM)</label>
                            <input type="number" name="nilai_pelupusan" step="0.01" min="0" value="{{ old('nilai_pelupusan', $permohonanPelupusan->nilai_pelupusan) }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">
                            @error('nilai_pelupusan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Sebab Pelupusan <span class="text-red-500">*</span></label>
                            <textarea name="sebab_pelupusan" rows="3" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">{{ old('sebab_pelupusan', $permohonanPelupusan->sebab_pelupusan) }}</textarea>
                            @error('sebab_pelupusan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Catatan</label>
                            <textarea name="catatan" rows="2" class="w-full px-3 py-2 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">{{ old('catatan', $permohonanPelupusan->catatan) }}</textarea>
                            @error('catatan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="{{ route('permohonan-pelupusan.index') }}" class="px-4 py-2 text-xs text-gray-700 bg-gray-200 rounded hover:bg-gray-300">Batal</a>
                        <button type="submit" class="px-4 py-2 text-xs text-white bg-blue-600 rounded hover:bg-blue-700">Kemaskini</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
