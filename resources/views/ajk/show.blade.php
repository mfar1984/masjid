<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Ahli Jawatankuasa - E-Masjid</title>
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
                <!-- Header Section -->
                <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Butiran Ahli Jawatankuasa</h1>
                        <p class="text-xs text-gray-600">Maklumat lengkap {{ $ajk->nama }}</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        <a href="{{ route('ajk.index') }}" class="inline-flex items-center justify-center h-[32px] px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs rounded transition-colors">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                            Kembali
                        </a>
                        @if(auth()->user()->hasPermission('ajk', 'update'))
                            <a href="{{ route('ajk.edit', $ajk) }}" class="inline-flex items-center justify-center h-[32px] px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded transition-colors">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">edit</span>
                                Edit
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Form-like Display -->
                <div class="space-y-6">
                    <!-- Gambar Profil -->
                    <div class="bg-gray-50 rounded-sm p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons text-purple-600 text-sm mr-2">photo_camera</span>
                            Gambar Profil (Carta Organisasi)
                        </h3>
                        <div class="flex items-center gap-4">
                            <div class="w-24 h-24 border-2 border-gray-300 rounded-full flex items-center justify-center bg-white overflow-hidden">
                                @if($ajk->gambar_path)
                                    <img src="{{ Storage::url($ajk->gambar_path) }}" class="w-24 h-24 rounded-full object-cover">
                                @else
                                    <span class="material-icons text-gray-400" style="font-size: 32px;">person</span>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm text-gray-900 font-medium">{{ $ajk->nama }}</p>
                                <p class="text-xs text-gray-500">{{ $ajk->jawatan_full }}</p>
                                @if(!$ajk->gambar_path)
                                    <p class="text-xs text-orange-600 mt-1">Tiada gambar dimuat naik</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="bg-gray-50 rounded-sm p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons text-blue-600 text-sm mr-2">person</span>
                            Maklumat Peribadi
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Nama Penuh -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Nama Penuh</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                        <span class="text-xs font-medium text-blue-600">{{ strtoupper(substr($ajk->nama, 0, 1)) }}</span>
                                    </div>
                                    <span class="text-sm text-gray-900">{{ $ajk->nama }}</span>
                                </div>
                            </div>

                            <!-- Nombor IC -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Nombor IC</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ $ajk->no_ic }}</span>
                                </div>
                            </div>

                            <!-- Nombor Telefon -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Nombor Telefon</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ $ajk->telefon }}</span>
                                </div>
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ $ajk->email ?? '--' }}</span>
                                </div>
                            </div>

                            <!-- Jantina -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Jantina</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ $ajk->jantina }}</span>
                                </div>
                            </div>

                            <!-- Umur -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Umur</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ $ajk->umur }}</span>
                                </div>
                            </div>

                            <!-- Alamat (Full Width) -->
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Alamat</label>
                                <div class="p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ $ajk->alamat ?? '--' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Jawatan Information -->
                    <div class="bg-gray-50 rounded-sm p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons text-purple-600 text-sm mr-2">work</span>
                            Maklumat Jawatan
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Jawatan -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Jawatan</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $ajk->jawatan_full }}
                                    </span>
                                </div>
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium 
                                        @if($ajk->status == 'Aktif') bg-green-100 text-green-800
                                        @elseif($ajk->status == 'Menunggu') bg-orange-100 text-orange-800
                                        @elseif($ajk->status == 'Ditolak') bg-red-100 text-red-800
                                        @elseif($ajk->status == 'Digantung') bg-purple-100 text-purple-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $ajk->status }}
                                    </span>
                                </div>
                            </div>

                            <!-- Tarikh Lantikan -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Lantikan</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ $ajk->tarikh_lantikan_formatted }}</span>
                                </div>
                            </div>

                            <!-- Tarikh Tamat -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Tamat</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ $ajk->tarikh_tamat_formatted }}</span>
                                </div>
                            </div>

                            <!-- Tempoh Jawatan -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tempoh Jawatan</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ $ajk->tempoh_jawatan ?? '--' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Documents -->
                    @if($ajk->ic_depan_path || $ajk->ic_belakang_path || $ajk->surat_lantikan_path)
                    <div class="bg-gray-50 rounded-sm p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons text-green-600 text-sm mr-2">description</span>
                            Dokumen Sokongan
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @if($ajk->ic_depan_path)
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">IC Depan</label>
                                <a href="{{ Storage::url($ajk->ic_depan_path) }}" target="_blank" 
                                    class="flex items-center justify-center p-4 bg-white border-2 border-dashed border-gray-300 rounded-sm hover:border-blue-500 transition-colors">
                                    <div class="text-center">
                                        <span class="material-icons text-blue-600 mb-1" style="font-size: 32px !important;">image</span>
                                        <p class="text-xs text-gray-600">Lihat Dokumen</p>
                                    </div>
                                </a>
                            </div>
                            @endif

                            @if($ajk->ic_belakang_path)
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">IC Belakang</label>
                                <a href="{{ Storage::url($ajk->ic_belakang_path) }}" target="_blank" 
                                    class="flex items-center justify-center p-4 bg-white border-2 border-dashed border-gray-300 rounded-sm hover:border-blue-500 transition-colors">
                                    <div class="text-center">
                                        <span class="material-icons text-blue-600 mb-1" style="font-size: 32px !important;">image</span>
                                        <p class="text-xs text-gray-600">Lihat Dokumen</p>
                                    </div>
                                </a>
                            </div>
                            @endif

                            @if($ajk->surat_lantikan_path)
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Surat Lantikan</label>
                                <a href="{{ Storage::url($ajk->surat_lantikan_path) }}" target="_blank" 
                                    class="flex items-center justify-center p-4 bg-white border-2 border-dashed border-gray-300 rounded-sm hover:border-blue-500 transition-colors">
                                    <div class="text-center">
                                        <span class="material-icons text-blue-600 mb-1" style="font-size: 32px !important;">description</span>
                                        <p class="text-xs text-gray-600">Lihat Dokumen</p>
                                    </div>
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Audit Information -->
                    <div class="bg-gray-50 rounded-sm p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons text-gray-600 text-sm mr-2">info</span>
                            Maklumat Audit
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Dicipta Pada</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ $ajk->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Dikemaskini Pada</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ $ajk->updated_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
