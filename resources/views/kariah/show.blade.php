<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Ahli Kariah - E-Masjid</title>
    <x-favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans min-h-screen flex flex-col">
    <x-double-navbar :user="$user" />

    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <!-- Header -->
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-xl font-bold text-gray-900 mb-1">Maklumat Ahli Kariah</h1>
                            <p class="text-xs text-gray-600">Butiran lengkap ahli kariah</p>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('kariah.edit', $kariah) }}" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-xs rounded-xs hover:bg-blue-700">
                                <span class="material-icons text-xs mr-2">edit</span>
                                Edit
                            </a>
                            <a href="{{ route('kariah.index') }}" class="inline-flex items-center px-3 py-2 bg-gray-100 text-gray-700 text-xs rounded-xs hover:bg-gray-200">
                                <span class="material-icons text-xs mr-2">arrow_back</span>
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Member Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Maklumat Asas</h2>
                        
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Nama Penuh</label>
                            <p class="text-sm text-gray-900">{{ $kariah->nama }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Umur</label>
                            <p class="text-sm text-gray-900">{{ $kariah->umur }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Nombor IC</label>
                            <p class="text-sm text-gray-900">{{ $kariah->no_ic }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Nombor Telefon</label>
                            <p class="text-sm text-gray-900">{{ $kariah->telefon }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Bangsa</label>
                            <p class="text-sm text-gray-900">{{ $kariah->bangsa ?: 'Tiada maklumat bangsa' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Email</label>
                            <p class="text-sm text-gray-900">{{ $kariah->email ?: 'Tiada email' }}</p>
                        </div>
                    </div>

                    <!-- Membership Information -->
                    <div class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Maklumat Keahlian</h2>
                        
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-lg {{ $kariah->status == 'Aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $kariah->status }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Zon</label>
                            <p class="text-sm text-gray-900">{{ $kariah->zon ?: 'Tiada zon' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tarikh Keahlian</label>
                            <p class="text-sm text-gray-900">{{ $kariah->tarikh_keahlian_formatted }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tarikh Kemaskini</label>
                            <p class="text-sm text-gray-900">{{ $kariah->tarikh_kemaskini_formatted }}</p>
                        </div>
                    </div>
                </div>

                <!-- Address -->
                @if($kariah->alamat)
                <div class="mt-6">
                    <h2 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Alamat</h2>
                    <p class="text-sm text-gray-900 whitespace-pre-line">{{ $kariah->alamat }}</p>
                </div>
                @endif

                <!-- System Information -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Maklumat Sistem</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-gray-600">
                        <div>
                            <span class="font-medium">Dicipta oleh:</span> {{ $kariah->creator->name ?? 'Sistem' }}
                        </div>
                        <div>
                            <span class="font-medium">Dikemaskini oleh:</span> {{ $kariah->updater->name ?? 'Sistem' }}
                        </div>
                        <div>
                            <span class="font-medium">Tarikh dicipta:</span> {{ $kariah->created_at->format('d/m/Y H:i') }}
                        </div>
                        <div>
                            <span class="font-medium">Tarikh dikemaskini:</span> {{ $kariah->updated_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
