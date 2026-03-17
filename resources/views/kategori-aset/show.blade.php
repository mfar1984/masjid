<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Kategori Aset - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Butiran Kategori Aset</h1>
                        <p class="text-xs text-gray-600">{{ $kategoriAset->kod_kategori }} - {{ $kategoriAset->nama_kategori }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('kategori-aset.index') }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                            Kembali
                        </a>
                        @if(auth()->user()->hasPermission('kategori_aset', 'update'))
                            <a href="{{ route('kategori-aset.edit', $kategoriAset->id) }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">edit</span>
                                Edit
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Section 1: Maklumat Kategori -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Kategori</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Kod Kategori</label>
                            <p class="text-xs text-gray-900 font-semibold">{{ $kategoriAset->kod_kategori }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Nama Kategori</label>
                            <p class="text-xs text-gray-900 font-semibold">{{ $kategoriAset->nama_kategori }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Jenis Kategori</label>
                            <p class="text-xs text-gray-900">{{ $kategoriAset->jenis_kategori }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Urutan Paparan</label>
                            <p class="text-xs text-gray-900">{{ $kategoriAset->urutan ?? '-' }}</p>
                        </div>

                        @if($kategoriAset->keterangan)
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Keterangan</label>
                            <p class="text-xs text-gray-900">{{ $kategoriAset->keterangan }}</p>
                        </div>
                        @endif

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                            <p class="text-xs">
                                @if($kategoriAset->status === 'Aktif')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">Tidak Aktif</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Statistik Aset -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Statistik Aset</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-white rounded p-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-[10px] text-gray-500 mb-1">Total Aset</p>
                                    <p class="text-lg font-bold text-blue-600">{{ $kategoriAset->senariAset->count() }}</p>
                                </div>
                                <span class="material-icons text-blue-600" style="font-size: 32px;">inventory_2</span>
                            </div>
                        </div>

                        <div class="bg-white rounded p-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-[10px] text-gray-500 mb-1">Aset Aktif</p>
                                    <p class="text-lg font-bold text-green-600">{{ $kategoriAset->senariAset->where('status_aset', 'Aktif')->count() }}</p>
                                </div>
                                <span class="material-icons text-green-600" style="font-size: 32px;">check_circle</span>
                            </div>
                        </div>

                        <div class="bg-white rounded p-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-[10px] text-gray-500 mb-1">Aset Rosak</p>
                                    <p class="text-lg font-bold text-red-600">{{ $kategoriAset->senariAset->where('status_aset', 'Rosak')->count() }}</p>
                                </div>
                                <span class="material-icons text-red-600" style="font-size: 32px;">warning</span>
                            </div>
                        </div>

                        <div class="bg-white rounded p-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-[10px] text-gray-500 mb-1">Nilai Total</p>
                                    <p class="text-sm font-bold text-purple-600">RM {{ number_format($kategoriAset->senariAset->sum('harga_perolehan'), 2) }}</p>
                                </div>
                                <span class="material-icons text-purple-600" style="font-size: 32px;">payments</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Senarai Aset -->
                @if($kategoriAset->senariAset && $kategoriAset->senariAset->count() > 0)
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Senarai Aset</h2>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-xs">
                            <thead class="bg-blue-100">
                                <tr>
                                    <th class="px-3 py-2 text-left">No. Aset</th>
                                    <th class="px-3 py-2 text-left">Nama Aset</th>
                                    <th class="px-3 py-2 text-left">Lokasi</th>
                                    <th class="px-3 py-2 text-right">Harga</th>
                                    <th class="px-3 py-2 text-center">Status</th>
                                    <th class="px-3 py-2 text-center">Kondisi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($kategoriAset->senariAset->take(10) as $aset)
                                <tr class="hover:bg-white">
                                    <td class="px-3 py-2">
                                        <a href="{{ route('senarai-aset.show', $aset->id) }}" class="text-blue-600 hover:underline">
                                            {{ $aset->no_aset }}
                                        </a>
                                    </td>
                                    <td class="px-3 py-2">{{ $aset->nama_aset }}</td>
                                    <td class="px-3 py-2">{{ $aset->lokasi_semasa }}</td>
                                    <td class="px-3 py-2 text-right">RM {{ number_format($aset->harga_perolehan, 2) }}</td>
                                    <td class="px-3 py-2 text-center">
                                        @if($aset->status_aset === 'Aktif')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                        @elseif($aset->status_aset === 'Rosak')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Rosak</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">{{ $aset->status_aset }}</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        @if($aset->kondisi_aset === 'Baru' || $aset->kondisi_aset === 'Baik')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">{{ $aset->kondisi_aset }}</span>
                                        @elseif($aset->kondisi_aset === 'Sederhana')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-yellow-100 text-yellow-800">{{ $aset->kondisi_aset }}</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">{{ $aset->kondisi_aset }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($kategoriAset->senariAset->count() > 10)
                    <p class="text-[10px] text-gray-500 mt-2">Menunjukkan 10 aset terkini. Jumlah keseluruhan: {{ $kategoriAset->senariAset->count() }} aset.</p>
                    @endif
                </div>
                @endif

                <!-- Section 4: Maklumat Sistem -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Sistem</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Masjid</label>
                            <p class="text-xs text-gray-900">{{ $kategoriAset->masjid->nama ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Dicipta Oleh</label>
                            <p class="text-xs text-gray-900">{{ $kategoriAset->createdBy->name ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tarikh Dicipta</label>
                            <p class="text-xs text-gray-900">{{ $kategoriAset->created_at ? $kategoriAset->created_at->format('d/m/Y H:i') : '-' }}</p>
                        </div>

                        @if($kategoriAset->updated_at && $kategoriAset->updated_at != $kategoriAset->created_at)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Dikemaskini Oleh</label>
                            <p class="text-xs text-gray-900">{{ $kategoriAset->updatedBy->name ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tarikh Dikemaskini</label>
                            <p class="text-xs text-gray-900">{{ $kategoriAset->updated_at->format('d/m/Y H:i') }}</p>
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
