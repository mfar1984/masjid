<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Penceramah - E-Masjid</title>
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
                    <a href="{{ route('senarai-penceramah.index') }}" class="mr-3 text-gray-500 hover:text-gray-700">
                        <span class="material-icons">arrow_back</span>
                    </a>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Tambah Penceramah</h1>
                        <p class="text-xs text-gray-600">Daftar penceramah baru</p>
                    </div>
                </div>

                <form action="{{ route('senarai-penceramah.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Maklumat Peribadi -->
                        <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Peribadi</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Nama Penuh <span class="text-red-500">*</span></label>
                                    <input type="text" name="nama" value="{{ old('nama') }}" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                    @error('nama')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">No. IC</label>
                                    <input type="text" name="no_ic" value="{{ old('no_ic') }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">No. Telefon</label>
                                    <input type="text" name="no_telefon" value="{{ old('no_telefon') }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                                    <input type="email" name="email" value="{{ old('email') }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Alamat</label>
                                    <textarea name="alamat" rows="2" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">{{ old('alamat') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Maklumat Tauliah -->
                        <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Tauliah</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Negara <span class="text-red-500">*</span></label>
                                    <select name="negara" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                        <option value="Malaysia" {{ old('negara') === 'Malaysia' ? 'selected' : '' }}>Malaysia</option>
                                        <option value="Luar Negara" {{ old('negara') === 'Luar Negara' ? 'selected' : '' }}>Luar Negara</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Negeri</label>
                                    <input type="text" name="negeri" value="{{ old('negeri') }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">No. Sijil Tauliah</label>
                                    <input type="text" name="no_sijil_tauliah" value="{{ old('no_sijil_tauliah') }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Tamat Tauliah</label>
                                    <input type="date" name="tarikh_tamat_tauliah" value="{{ old('tarikh_tamat_tauliah') }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Pihak Pengeluar</label>
                                    <input type="text" name="pihak_pengeluar" value="{{ old('pihak_pengeluar') }}" placeholder="cth: JAIN, JAKIM" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Bidang Kepakaran</label>
                                    <input type="text" name="bidang_kepakaran" value="{{ old('bidang_kepakaran') }}" placeholder="cth: Fiqh, Akidah, Sirah" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                                    <select name="status" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                        <option value="Aktif" {{ old('status', 'Aktif') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="Tidak Aktif" {{ old('status') === 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Catatan</label>
                        <textarea name="catatan" rows="2" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">{{ old('catatan') }}</textarea>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('senarai-penceramah.index') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-gray-500 text-white text-xs rounded hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
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
