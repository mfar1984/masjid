<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Rekod Pelupusan - E-Masjid</title>
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
                        <a href="{{ route('rekod-pelupusan.index') }}" class="text-blue-600 hover:text-blue-800">
                            <span class="material-icons" style="font-size: 20px !important;">arrow_back</span>
                        </a>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">Rekod Pelupusan</h1>
                            <p class="text-xs text-gray-600">{{ $permohonanPelupusan->no_rujukan }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-4 rounded border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Maklumat Pelupusan</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500">No. Rujukan</p>
                                <p class="text-sm text-gray-900 font-semibold">{{ $permohonanPelupusan->no_rujukan }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Tarikh Permohonan</p>
                                <p class="text-sm text-gray-900">{{ $permohonanPelupusan->tarikh_permohonan->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Tarikh Pelupusan</p>
                                <p class="text-sm text-gray-900">{{ $permohonanPelupusan->tarikh_pelupusan ? $permohonanPelupusan->tarikh_pelupusan->format('d/m/Y') : '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Kaedah Pelupusan</p>
                                <p class="text-sm text-gray-900">{{ $permohonanPelupusan->kaedah_pelupusan }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Nilai Pelupusan</p>
                                <p class="text-sm text-gray-900">RM {{ number_format($permohonanPelupusan->nilai_pelupusan, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Maklumat Aset</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500">No. Siri</p>
                                <p class="text-sm text-gray-900">{{ $permohonanPelupusan->senariAset->no_siri ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Nama Aset</p>
                                <p class="text-sm text-gray-900">{{ $permohonanPelupusan->senariAset->nama_aset ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Kategori</p>
                                <p class="text-sm text-gray-900">{{ $permohonanPelupusan->senariAset->kategoriAset->nama_kategori ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Sebab Pelupusan</p>
                                <p class="text-sm text-gray-900">{{ $permohonanPelupusan->sebab_pelupusan }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded border border-gray-200 md:col-span-2">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Maklumat Kelulusan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <p class="text-xs text-gray-500">Diluluskan Oleh</p>
                                <p class="text-sm text-gray-900">{{ $permohonanPelupusan->diluluskanOleh->name ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Tarikh Kelulusan</p>
                                <p class="text-sm text-gray-900">{{ $permohonanPelupusan->tarikh_kelulusan ? $permohonanPelupusan->tarikh_kelulusan->format('d/m/Y') : '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Pemohon</p>
                                <p class="text-sm text-gray-900">{{ $permohonanPelupusan->createdBy->name ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Catatan Kelulusan</p>
                                <p class="text-sm text-gray-900">{{ $permohonanPelupusan->catatan_kelulusan ?? '-' }}</p>
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
