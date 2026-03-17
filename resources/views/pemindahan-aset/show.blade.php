<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Butiran Pemindahan Aset - E-Masjid</title>
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
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Butiran Pemindahan Aset</h1>
                        <p class="text-xs text-gray-600">{{ $pemindahanAset->no_pergerakan }}</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('pemindahan-aset.edit', $pemindahanAset) }}" class="inline-flex items-center justify-center px-4 py-2 bg-yellow-600 text-white text-xs rounded hover:bg-yellow-700">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">edit</span>
                            Edit
                        </a>
                        <a href="{{ route('pemindahan-aset.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white text-xs rounded hover:bg-gray-700">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                            Kembali
                        </a>
                    </div>
                </div>

                <!-- Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Maklumat Pemindahan -->
                    <div class="bg-gray-50 border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons mr-2 text-blue-600" style="font-size: 18px !important;">swap_horiz</span>
                            Maklumat Pemindahan
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-600">No. Rujukan:</span>
                                <span class="text-xs font-medium text-gray-900">{{ $pemindahanAset->no_pergerakan }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-600">Tarikh:</span>
                                <span class="text-xs text-gray-900">{{ $pemindahanAset->tarikh_pergerakan ? $pemindahanAset->tarikh_pergerakan->format('d/m/Y') : '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-600">Jenis:</span>
                                <span class="px-2 py-1 rounded text-xs {{ $pemindahanAset->jenis_pergerakan == 'Pemindahan Dalaman' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                    {{ $pemindahanAset->jenis_pergerakan }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Maklumat Aset -->
                    <div class="bg-gray-50 border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons mr-2 text-green-600" style="font-size: 18px !important;">inventory_2</span>
                            Maklumat Aset
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-600">Nama Aset:</span>
                                <span class="text-xs font-medium text-gray-900">{{ $pemindahanAset->senariAset->nama_aset ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-600">Kategori:</span>
                                <span class="text-xs text-gray-900">{{ $pemindahanAset->senariAset->kategoriAset->nama_kategori ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-600">No. Siri:</span>
                                <span class="text-xs text-gray-900">{{ $pemindahanAset->senariAset->no_siri ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Lokasi -->
                    <div class="bg-gray-50 border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons mr-2 text-purple-600" style="font-size: 18px !important;">location_on</span>
                            Maklumat Lokasi
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-600">Lokasi Asal:</span>
                                <span class="text-xs text-gray-900">{{ $pemindahanAset->lokasi_asal ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-600">Lokasi Baru:</span>
                                <span class="text-xs font-medium text-gray-900">{{ $pemindahanAset->lokasi_destinasi ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Rekod -->
                    <div class="bg-gray-50 border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons mr-2 text-gray-600" style="font-size: 18px !important;">history</span>
                            Rekod
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-600">Dicipta Oleh:</span>
                                <span class="text-xs text-gray-900">{{ $pemindahanAset->createdBy->name ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-600">Tarikh Cipta:</span>
                                <span class="text-xs text-gray-900">{{ $pemindahanAset->created_at ? $pemindahanAset->created_at->format('d/m/Y H:i') : '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-600">Kemaskini Terakhir:</span>
                                <span class="text-xs text-gray-900">{{ $pemindahanAset->updated_at ? $pemindahanAset->updated_at->format('d/m/Y H:i') : '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Sebab & Catatan -->
                    <div class="md:col-span-2 bg-gray-50 border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons mr-2 text-orange-600" style="font-size: 18px !important;">description</span>
                            Sebab & Catatan
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Sebab Pemindahan:</p>
                                <p class="text-xs text-gray-900 bg-white p-3 rounded border border-gray-200">{{ $pemindahanAset->sebab_pergerakan ?? '-' }}</p>
                            </div>
                            @if($pemindahanAset->catatan)
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Catatan:</p>
                                <p class="text-xs text-gray-900 bg-white p-3 rounded border border-gray-200">{{ $pemindahanAset->catatan }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
