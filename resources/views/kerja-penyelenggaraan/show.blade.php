<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Kerja Penyelenggaraan - E-Masjid</title>
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
                        <a href="{{ route('kerja-penyelenggaraan.index') }}" class="text-blue-600 hover:text-blue-800">
                            <span class="material-icons" style="font-size: 20px !important;">arrow_back</span>
                        </a>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">{{ $kerjaPenyelenggaraan->no_kerja }}</h1>
                            <p class="text-xs text-gray-600">{{ $kerjaPenyelenggaraan->item_nama }}</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        @if(auth()->user()->hasPermission('kerja_penyelenggaraan', 'update'))
                            <a href="{{ route('kerja-penyelenggaraan.edit', $kerjaPenyelenggaraan) }}" class="inline-flex items-center px-3 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">
                                <span class="material-icons mr-1" style="font-size: 14px !important;">edit</span>Edit
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Status Badge -->
                <div class="mb-6">
                    @if($kerjaPenyelenggaraan->status === 'Selesai')
                        <span class="inline-flex items-center px-3 py-1 rounded text-sm font-medium bg-green-100 text-green-800">
                            <span class="material-icons mr-1" style="font-size: 16px !important;">check_circle</span>Selesai
                        </span>
                    @elseif($kerjaPenyelenggaraan->status === 'Sedang Berjalan')
                        <span class="inline-flex items-center px-3 py-1 rounded text-sm font-medium bg-yellow-100 text-yellow-800">
                            <span class="material-icons mr-1" style="font-size: 16px !important;">engineering</span>Sedang Berjalan
                        </span>
                    @elseif($kerjaPenyelenggaraan->status === 'Dirancang')
                        <span class="inline-flex items-center px-3 py-1 rounded text-sm font-medium bg-blue-100 text-blue-800">
                            <span class="material-icons mr-1" style="font-size: 16px !important;">schedule</span>Dirancang
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded text-sm font-medium bg-gray-100 text-gray-800">{{ $kerjaPenyelenggaraan->status }}</span>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Maklumat Kerja -->
                    <div class="bg-gray-50 rounded p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons mr-2 text-blue-600" style="font-size: 18px !important;">build</span>
                            Maklumat Kerja
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">No. Kerja</span>
                                <span class="text-xs font-medium text-gray-900">{{ $kerjaPenyelenggaraan->no_kerja }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">Jenis Item</span>
                                <span class="text-xs font-medium text-gray-900">{{ $kerjaPenyelenggaraan->jenis_item }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">Item</span>
                                <span class="text-xs font-medium text-gray-900">{{ $kerjaPenyelenggaraan->item_nama }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">Jenis Kerja</span>
                                <span class="text-xs font-medium text-gray-900">{{ $kerjaPenyelenggaraan->jenis_kerja }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">Tarikh Kerja</span>
                                <span class="text-xs font-medium text-gray-900">{{ $kerjaPenyelenggaraan->tarikh_kerja->format('d/m/Y') }}</span>
                            </div>
                            @if($kerjaPenyelenggaraan->masa_mula)
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">Masa</span>
                                <span class="text-xs font-medium text-gray-900">{{ $kerjaPenyelenggaraan->masa_mula }} - {{ $kerjaPenyelenggaraan->masa_tamat ?? '-' }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Maklumat Vendor & Kos -->
                    <div class="bg-gray-50 rounded p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons mr-2 text-green-600" style="font-size: 18px !important;">business</span>
                            Maklumat Vendor & Kos
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">Nama Vendor</span>
                                <span class="text-xs font-medium text-gray-900">{{ $kerjaPenyelenggaraan->vendor_nama ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">No. Telefon</span>
                                <span class="text-xs font-medium text-gray-900">{{ $kerjaPenyelenggaraan->vendor_telefon ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">Alamat</span>
                                <span class="text-xs font-medium text-gray-900">{{ $kerjaPenyelenggaraan->vendor_alamat ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">Kos</span>
                                <span class="text-xs font-bold text-green-600">RM {{ number_format($kerjaPenyelenggaraan->kos, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Kondisi -->
                    <div class="bg-gray-50 rounded p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons mr-2 text-purple-600" style="font-size: 18px !important;">assessment</span>
                            Kondisi Item
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">Kondisi Sebelum</span>
                                <span class="text-xs font-medium text-gray-900">{{ $kerjaPenyelenggaraan->kondisi_sebelum ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">Kondisi Selepas</span>
                                <span class="text-xs font-medium text-gray-900">{{ $kerjaPenyelenggaraan->kondisi_selepas ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Penerangan & Catatan -->
                    <div class="bg-gray-50 rounded p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons mr-2 text-orange-600" style="font-size: 18px !important;">description</span>
                            Penerangan & Catatan
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-xs text-gray-500 block mb-1">Penerangan Kerja</span>
                                <p class="text-xs text-gray-900">{{ $kerjaPenyelenggaraan->penerangan_kerja }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 block mb-1">Catatan</span>
                                <p class="text-xs text-gray-900">{{ $kerjaPenyelenggaraan->catatan ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Jadual Berkaitan -->
                @if($kerjaPenyelenggaraan->jadualPenyelenggaraan)
                <div class="mt-6 bg-blue-50 rounded p-4">
                    <h3 class="text-sm font-semibold text-gray-900 mb-2">Jadual Penyelenggaraan Berkaitan</h3>
                    <p class="text-xs text-gray-600">{{ $kerjaPenyelenggaraan->jadualPenyelenggaraan->no_jadual }} - {{ $kerjaPenyelenggaraan->jadualPenyelenggaraan->nama_jadual }}</p>
                </div>
                @endif

                <!-- Audit Info -->
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <div class="flex flex-wrap gap-4 text-xs text-gray-500">
                        <span>Dicipta oleh: {{ $kerjaPenyelenggaraan->createdBy->name ?? '-' }} pada {{ $kerjaPenyelenggaraan->created_at->format('d/m/Y H:i') }}</span>
                        @if($kerjaPenyelenggaraan->updatedBy)
                            <span>Dikemaskini oleh: {{ $kerjaPenyelenggaraan->updatedBy->name }} pada {{ $kerjaPenyelenggaraan->updated_at->format('d/m/Y H:i') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
