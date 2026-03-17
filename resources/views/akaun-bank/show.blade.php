<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Butiran Akaun Bank - E-Masjid</title>
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
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Butiran Akaun Bank</h1>
                        <p class="text-xs text-gray-600">Maklumat lengkap akaun bank</p>
                    </div>
                    <div class="flex space-x-2">
                        @if(auth()->user()->hasPermission('kewangan', 'update'))
                            <a href="{{ route('akaun-bank.edit', $akaunBank) }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">edit</span>
                                Kemaskini
                            </a>
                        @endif
                        <a href="{{ route('akaun-bank.index') }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                            Kembali
                        </a>
                    </div>
                </div>

                <!-- Maklumat Bank -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Bank</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Nama Bank</label>
                            <p class="text-sm text-gray-900">{{ $akaunBank->nama_bank }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">No. Akaun</label>
                            <p class="text-sm text-gray-900">{{ $akaunBank->no_akaun }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Jenis Akaun</label>
                            <p class="text-sm text-gray-900">{{ $akaunBank->jenis_akaun }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Nama Pemegang Akaun</label>
                            <p class="text-sm text-gray-900">{{ $akaunBank->nama_pemegang_akaun }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Cawangan</label>
                            <p class="text-sm text-gray-900">{{ $akaunBank->cawangan ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                            @if($akaunBank->status === 'Aktif')
                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">Tidak Aktif</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Maklumat Baki -->
                <div class="bg-green-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Baki</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Baki Awal</label>
                            <p class="text-lg font-bold text-gray-900">RM {{ number_format($akaunBank->baki_awal, 2) }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Baki Semasa</label>
                            <p class="text-lg font-bold text-green-600">RM {{ number_format($akaunBank->baki_semasa, 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Catatan -->
                @if($akaunBank->catatan)
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-2">Catatan</h2>
                    <p class="text-xs text-gray-700">{{ $akaunBank->catatan }}</p>
                </div>
                @endif

                <!-- Audit Trail -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Audit</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                        <div>
                            <label class="block text-gray-500 mb-1">Dicipta Oleh</label>
                            <p class="text-gray-900">{{ $akaunBank->createdBy->name ?? '-' }}</p>
                            <p class="text-gray-500">{{ $akaunBank->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        @if($akaunBank->updated_at != $akaunBank->created_at)
                        <div>
                            <label class="block text-gray-500 mb-1">Dikemaskini Oleh</label>
                            <p class="text-gray-900">{{ $akaunBank->updatedBy->name ?? '-' }}</p>
                            <p class="text-gray-500">{{ $akaunBank->updated_at->format('d/m/Y H:i') }}</p>
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
