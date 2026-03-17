<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Jadual Penyusutan - E-Masjid</title>
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
                        <a href="{{ route('jadual-penyusutan.index') }}" class="text-blue-600 hover:text-blue-800">
                            <span class="material-icons" style="font-size: 20px !important;">arrow_back</span>
                        </a>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">Maklumat Jadual Penyusutan</h1>
                            <p class="text-xs text-gray-600">{{ $jadualPenyusutan->kategoriAset->nama_kategori ?? '-' }}</p>
                        </div>
                    </div>
                    @if(auth()->user()->hasPermission('jadual_penyusutan', 'update'))
                        <a href="{{ route('jadual-penyusutan.edit', $jadualPenyusutan) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                            <span class="material-icons mr-1" style="font-size: 16px !important;">edit</span>
                            Edit
                        </a>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-4 rounded border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Maklumat Penyusutan</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500">Kategori Aset</p>
                                <p class="text-sm text-gray-900">{{ $jadualPenyusutan->kategoriAset->nama_kategori ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Kadar Susut Tahunan</p>
                                <p class="text-sm text-gray-900">{{ number_format($jadualPenyusutan->kadar_susut_tahunan, 2) }}%</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Kaedah Susut</p>
                                <p class="text-sm text-gray-900">{{ $jadualPenyusutan->kaedah_susut }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Tempoh Guna</p>
                                <p class="text-sm text-gray-900">{{ $jadualPenyusutan->tempoh_guna_tahun }} tahun</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Status</p>
                                @if($jadualPenyusutan->status === 'Aktif')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-gray-100 text-gray-800">Tidak Aktif</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Maklumat Tambahan</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500">Catatan</p>
                                <p class="text-sm text-gray-900">{{ $jadualPenyusutan->catatan ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Dicipta Oleh</p>
                                <p class="text-sm text-gray-900">{{ $jadualPenyusutan->createdBy->name ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Tarikh Dicipta</p>
                                <p class="text-sm text-gray-900">{{ $jadualPenyusutan->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Dikemaskini Oleh</p>
                                <p class="text-sm text-gray-900">{{ $jadualPenyusutan->updatedBy->name ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Tarikh Dikemaskini</p>
                                <p class="text-sm text-gray-900">{{ $jadualPenyusutan->updated_at->format('d/m/Y H:i') }}</p>
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
