<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Aset - E-Masjid</title>
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
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Butiran Aset</h1>
                        <p class="text-xs text-blue-600 font-medium">{{ $senariAset->kod_aset ?: $senariAset->no_aset }} - {{ $senariAset->nama_aset }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('senarai-aset.index') }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                            Kembali
                        </a>
                        @if(auth()->user()->hasPermission('senarai_aset', 'update'))
                            <a href="{{ route('senarai-aset.edit', $senariAset->id) }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">edit</span>
                                Edit
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Section 1: Maklumat Asas -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Asas</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Kod Aset</label>
                            <p class="text-xs text-blue-600 font-semibold">{{ $senariAset->kod_aset ?: $senariAset->no_aset }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Nama Aset</label>
                            <p class="text-xs text-gray-900 font-semibold">{{ $senariAset->nama_aset }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Kategori</label>
                            <p class="text-xs text-gray-900">{{ $senariAset->kategoriAset->nama_kategori ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Jenis Kategori</label>
                            <p class="text-xs text-gray-900">{{ $senariAset->kategoriAset->jenis_kategori ?? '-' }}</p>
                        </div>

                        @if($senariAset->jenis_aset)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Jenis Aset</label>
                            <p class="text-xs text-gray-900">{{ $senariAset->jenis_aset }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Section 2: Maklumat Perolehan -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Perolehan</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tarikh Perolehan</label>
                            <p class="text-xs text-gray-900">{{ $senariAset->tarikh_perolehan ? \Carbon\Carbon::parse($senariAset->tarikh_perolehan)->format('d/m/Y') : '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Cara Perolehan</label>
                            <p class="text-xs text-gray-900">{{ $senariAset->cara_perolehan }}</p>
                        </div>

                        @if($senariAset->pembekal)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Pembekal</label>
                            <p class="text-xs text-gray-900">{{ $senariAset->pembekal }}</p>
                        </div>
                        @endif

                        @if($senariAset->no_invois)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">No. Invois</label>
                            <p class="text-xs text-gray-900">{{ $senariAset->no_invois }}</p>
                        </div>
                        @endif

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Harga Perolehan</label>
                            <p class="text-xs text-gray-900 font-semibold">RM {{ number_format($senariAset->harga_perolehan, 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Spesifikasi Aset -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Spesifikasi Aset</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($senariAset->jenama)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Jenama</label>
                            <p class="text-xs text-gray-900">{{ $senariAset->jenama }}</p>
                        </div>
                        @endif

                        @if($senariAset->model)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Model</label>
                            <p class="text-xs text-gray-900">{{ $senariAset->model }}</p>
                        </div>
                        @endif

                        @if($senariAset->no_siri)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">No. Siri</label>
                            <p class="text-xs text-gray-900">{{ $senariAset->no_siri }}</p>
                        </div>
                        @endif

                        @if($senariAset->warna)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Warna</label>
                            <p class="text-xs text-gray-900">{{ $senariAset->warna }}</p>
                        </div>
                        @endif

                        @if($senariAset->saiz)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Saiz</label>
                            <p class="text-xs text-gray-900">{{ $senariAset->saiz }}</p>
                        </div>
                        @endif

                        @if($senariAset->spesifikasi)
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Spesifikasi Terperinci</label>
                            <p class="text-xs text-gray-900">{{ $senariAset->spesifikasi }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Section 4: Lokasi -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Lokasi</h2>
                    
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Lokasi Semasa</label>
                            <p class="text-xs text-gray-900 font-semibold">{{ $senariAset->lokasi_semasa }}</p>
                        </div>

                        @if($senariAset->lokasi_terperinci)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Lokasi Terperinci</label>
                            <p class="text-xs text-gray-900">{{ $senariAset->lokasi_terperinci }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Section 5: Jaminan & Insurans -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Jaminan & Insurans</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($senariAset->tempoh_jaminan)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tempoh Jaminan</label>
                            <p class="text-xs text-gray-900">{{ $senariAset->tempoh_jaminan }} bulan</p>
                        </div>
                        @endif

                        @if($senariAset->tarikh_tamat_jaminan)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tarikh Tamat Jaminan</label>
                            <p class="text-xs text-gray-900">{{ \Carbon\Carbon::parse($senariAset->tarikh_tamat_jaminan)->format('d/m/Y') }}</p>
                        </div>
                        @endif

                        @if($senariAset->no_polisi_insurans)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">No. Polisi Insurans</label>
                            <p class="text-xs text-gray-900">{{ $senariAset->no_polisi_insurans }}</p>
                        </div>
                        @endif

                        @if($senariAset->syarikat_insurans)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Syarikat Insurans</label>
                            <p class="text-xs text-gray-900">{{ $senariAset->syarikat_insurans }}</p>
                        </div>
                        @endif

                        @if($senariAset->tarikh_tamat_insurans)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tarikh Tamat Insurans</label>
                            <p class="text-xs text-gray-900">{{ \Carbon\Carbon::parse($senariAset->tarikh_tamat_insurans)->format('d/m/Y') }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Section 6: Status & Kondisi -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Status & Kondisi</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Status Aset</label>
                            <p class="text-xs">
                                @if($senariAset->status_aset === 'Aktif')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                @elseif($senariAset->status_aset === 'Rosak')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Rosak</span>
                                @elseif($senariAset->status_aset === 'Hilang')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Hilang</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">{{ $senariAset->status_aset }}</span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Kondisi Aset</label>
                            <p class="text-xs">
                                @if($senariAset->kondisi_aset === 'Baru' || $senariAset->kondisi_aset === 'Baik')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">{{ $senariAset->kondisi_aset }}</span>
                                @elseif($senariAset->kondisi_aset === 'Sederhana')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-yellow-100 text-yellow-800">{{ $senariAset->kondisi_aset }}</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">{{ $senariAset->kondisi_aset }}</span>
                                @endif
                            </p>
                        </div>

                        @if($senariAset->catatan)
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Catatan</label>
                            <p class="text-xs text-gray-900">{{ $senariAset->catatan }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Section 7: Sejarah Pergerakan -->
                @if($senariAset->pergerakanAset && $senariAset->pergerakanAset->count() > 0)
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Sejarah Pergerakan Aset</h2>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-xs">
                            <thead class="bg-blue-100">
                                <tr>
                                    <th class="px-3 py-2 text-left">No. Pergerakan</th>
                                    <th class="px-3 py-2 text-left">Tarikh</th>
                                    <th class="px-3 py-2 text-left">Jenis</th>
                                    <th class="px-3 py-2 text-left">Destinasi</th>
                                    <th class="px-3 py-2 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($senariAset->pergerakanAset->take(5) as $pergerakan)
                                <tr class="hover:bg-white">
                                    <td class="px-3 py-2">
                                        <a href="{{ route('pergerakan-aset.show', $pergerakan->id) }}" class="text-blue-600 hover:underline">
                                            {{ $pergerakan->no_pergerakan }}
                                        </a>
                                    </td>
                                    <td class="px-3 py-2">{{ $pergerakan->tarikh_pergerakan ? \Carbon\Carbon::parse($pergerakan->tarikh_pergerakan)->format('d/m/Y') : '-' }}</td>
                                    <td class="px-3 py-2">{{ $pergerakan->jenis_pergerakan }}</td>
                                    <td class="px-3 py-2">{{ $pergerakan->is_lokasi_luaran ? $pergerakan->nama_tempat_luaran : $pergerakan->lokasi_destinasi }}</td>
                                    <td class="px-3 py-2 text-center">
                                        @if($pergerakan->status_pulangan === 'Sudah Pulang')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Sudah Pulang</span>
                                        @elseif($pergerakan->status_pulangan === 'Lewat')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Lewat</span>
                                        @elseif($pergerakan->status_pulangan === 'Hilang')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Hilang</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">{{ $pergerakan->status_pulangan }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($senariAset->pergerakanAset->count() > 5)
                    <p class="text-[10px] text-gray-500 mt-2">Menunjukkan 5 pergerakan terkini. Jumlah keseluruhan: {{ $senariAset->pergerakanAset->count() }} pergerakan.</p>
                    @endif
                </div>
                @endif

                <!-- Section 8: Maklumat Sistem -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Sistem</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Masjid</label>
                            <p class="text-xs text-gray-900">{{ $senariAset->masjid->nama ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Dicipta Oleh</label>
                            <p class="text-xs text-gray-900">{{ $senariAset->createdBy->name ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tarikh Dicipta</label>
                            <p class="text-xs text-gray-900">{{ $senariAset->created_at ? $senariAset->created_at->format('d/m/Y H:i') : '-' }}</p>
                        </div>

                        @if($senariAset->updated_at && $senariAset->updated_at != $senariAset->created_at)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Dikemaskini Oleh</label>
                            <p class="text-xs text-gray-900">{{ $senariAset->updatedBy->name ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tarikh Dikemaskini</label>
                            <p class="text-xs text-gray-900">{{ $senariAset->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
