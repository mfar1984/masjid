<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maklumat Jadual Imam & Bilal - E-Masjid</title>
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
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Maklumat Jadual Imam & Bilal</h1>
                        <p class="text-xs text-gray-600">{{ $jadualImamBilal->tarikh->format('d/m/Y') }} - {{ $jadualImamBilal->waktu_solat }}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-sm text-xs font-medium {{ $jadualImamBilal->jenis_jadual === 'Auto' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $jadualImamBilal->jenis_jadual }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Maklumat Jadual -->
                    <div class="bg-gray-50 p-4 rounded-sm border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <span class="material-icons mr-2 text-blue-600" style="font-size: 18px;">event</span>
                            Maklumat Jadual
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">Tarikh</span>
                                <span class="text-xs font-medium text-gray-900">{{ $jadualImamBilal->tarikh->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">Hari</span>
                                <span class="text-xs font-medium text-gray-900">{{ $jadualImamBilal->tarikh->translatedFormat('l') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">Waktu Solat</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $jadualImamBilal->waktu_solat }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Maklumat Imam -->
                    <div class="bg-gray-50 p-4 rounded-sm border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <span class="material-icons mr-2 text-green-600" style="font-size: 18px;">person</span>
                            Maklumat Imam
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">Nama Imam</span>
                                <span class="text-xs font-medium text-gray-900">{{ $jadualImamBilal->imam_display }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">Status</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium 
                                    {{ $jadualImamBilal->status_imam === 'Selesai' ? 'bg-green-100 text-green-800' : 
                                       ($jadualImamBilal->status_imam === 'Batal' ? 'bg-red-100 text-red-800' : 
                                       ($jadualImamBilal->status_imam === 'Ganti' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ $jadualImamBilal->status_imam }}
                                </span>
                            </div>
                            @if($jadualImamBilal->imam_ganti)
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">Imam Ganti</span>
                                <span class="text-xs font-medium text-orange-600">{{ $jadualImamBilal->imam_ganti }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Maklumat Bilal -->
                    <div class="bg-gray-50 p-4 rounded-sm border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <span class="material-icons mr-2 text-orange-600" style="font-size: 18px;">record_voice_over</span>
                            Maklumat Bilal
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">Nama Bilal</span>
                                <span class="text-xs font-medium text-gray-900">{{ $jadualImamBilal->bilal_display }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">Status</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium 
                                    {{ $jadualImamBilal->status_bilal === 'Selesai' ? 'bg-green-100 text-green-800' : 
                                       ($jadualImamBilal->status_bilal === 'Batal' ? 'bg-red-100 text-red-800' : 
                                       ($jadualImamBilal->status_bilal === 'Ganti' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ $jadualImamBilal->status_bilal }}
                                </span>
                            </div>
                            @if($jadualImamBilal->bilal_ganti)
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">Bilal Ganti</span>
                                <span class="text-xs font-medium text-orange-600">{{ $jadualImamBilal->bilal_ganti }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div class="bg-gray-50 p-4 rounded-sm border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <span class="material-icons mr-2 text-gray-600" style="font-size: 18px;">notes</span>
                            Catatan & Maklumat Lain
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-xs text-gray-500">Catatan</span>
                                <p class="text-xs text-gray-900 mt-1">{{ $jadualImamBilal->catatan ?? '-' }}</p>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">Dicipta Oleh</span>
                                <span class="text-xs text-gray-900">{{ $jadualImamBilal->creator->name ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">Tarikh Dicipta</span>
                                <span class="text-xs text-gray-900">{{ $jadualImamBilal->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex items-center justify-end space-x-3">
                    <a href="{{ route('jadual-imam-bilal.index') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-gray-500 text-white text-xs rounded hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                        Kembali
                    </a>
                    @if(auth()->user()->hasPermission('jadual_imam_bilal', 'update'))
                        <a href="{{ route('jadual-imam-bilal.edit', $jadualImamBilal) }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">edit</span>
                            Kemaskini
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
