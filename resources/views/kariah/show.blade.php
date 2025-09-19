<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Ahli Kariah - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Butiran Ahli Kariah</h1>
                        <p class="text-xs text-gray-600">Maklumat lengkap {{ $kariah->nama }}</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        <a href="{{ route('kariah.index') }}" class="inline-flex items-center justify-center h-[32px] px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs rounded transition-colors">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                            Kembali
                        </a>
                        @if(auth()->user()->hasPermission('kariah', 'update'))
                            <a href="{{ route('kariah.edit', $kariah) }}" class="inline-flex items-center justify-center h-[32px] px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded transition-colors">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">edit</span>
                                Edit
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Form-like Display (Same order as Create/Edit) -->
                <div class="space-y-6">
                    <!-- Personal Information (Following Create/Edit Order) -->
                    <div class="bg-gray-50 rounded-sm p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons text-blue-600 text-sm mr-2">person</span>
                            Maklumat Peribadi
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- 1. Nama Penuh -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Nama Penuh</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                        <span class="text-xs font-medium text-blue-600">{{ strtoupper(substr($kariah->nama, 0, 1)) }}</span>
                                    </div>
                                    <span class="text-sm text-gray-900">{{ $kariah->nama }}</span>
                                </div>
                            </div>

                            <!-- 2. Nombor IC -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Nombor IC</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ $kariah->no_ic }}</span>
                                </div>
                            </div>

                            <!-- 3. Nombor Telefon -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Nombor Telefon</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ $kariah->telefon }}</span>
                                </div>
                            </div>

                            <!-- 4. Jantina -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Jantina</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ $kariah->jantina ?: 'Tidak dinyatakan' }}</span>
                                </div>
                            </div>

                            <!-- 5. Bangsa -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Bangsa</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ $kariah->bangsa ?: 'Tiada maklumat' }}</span>
                                </div>
                            </div>

                            <!-- 6. Tarikh Keahlian -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Keahlian</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ $kariah->tarikh_keahlian_formatted }}</span>
                                </div>
                            </div>

                            <!-- 7. Status -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    @php
                                        $statusColors = [
                                            'Aktif' => 'bg-green-100 text-green-800',
                                            'Tidak Aktif' => 'bg-red-100 text-red-800',
                                            'Menunggu' => 'bg-yellow-100 text-yellow-800',
                                            'Ditolak' => 'bg-red-100 text-red-800',
                                            'Digantung' => 'bg-orange-100 text-orange-800'
                                        ];
                                        $statusClass = $statusColors[$kariah->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $statusClass }}">
                                        {{ $kariah->status }}
                                    </span>
                                </div>
                            </div>

                            <!-- 8. Email -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    @if($kariah->email)
                                        <span class="material-icons text-gray-400 text-sm mr-2">email</span>
                                        <span class="text-sm text-gray-900">{{ $kariah->email }}</span>
                                    @else
                                        <span class="text-sm text-gray-500 italic">Tiada email</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 9. Alamat (Moved up) -->
                    <div class="bg-gray-50 rounded-sm p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons text-purple-600 text-sm mr-2">location_on</span>
                            Alamat
                        </h3>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Alamat Penuh</label>
                            <div class="flex items-start p-2 bg-white border border-gray-300 rounded-sm min-h-[60px]">
                                @if($kariah->alamat)
                                    <span class="text-sm text-gray-900 whitespace-pre-line">{{ $kariah->alamat }}</span>
                                @else
                                    <span class="text-sm text-gray-500 italic">Tiada alamat</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- 10. Lampiran Dokumen (Moved to bottom) -->
                    <div class="bg-gray-50 rounded-sm p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons text-orange-600 text-sm mr-2">attachment</span>
                            Lampiran Dokumen
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- IC Depan -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Kad Pengenalan / Passport (Depan)</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm min-h-[40px]">
                                    @if($kariah->ic_depan_path)
                                        <span class="material-icons text-gray-400 text-sm mr-2">description</span>
                                        <a href="{{ Storage::url($kariah->ic_depan_path) }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 underline">
                                            Lihat Dokumen
                                        </a>
                                    @else
                                        <span class="text-sm text-gray-500 italic">Tiada dokumen</span>
                                    @endif
                                </div>
                            </div>

                            <!-- IC Belakang -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Kad Pengenalan / Passport (Belakang)</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm min-h-[40px]">
                                    @if($kariah->ic_belakang_path)
                                        <span class="material-icons text-gray-400 text-sm mr-2">description</span>
                                        <a href="{{ Storage::url($kariah->ic_belakang_path) }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 underline">
                                            Lihat Dokumen
                                        </a>
                                    @else
                                        <span class="text-sm text-gray-500 italic">Tiada dokumen</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- System Information -->
                    <div class="bg-gray-50 rounded-sm p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons text-gray-600 text-sm mr-2">info</span>
                            Maklumat Sistem
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Dicipta Oleh</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ $kariah->creator->name ?? 'Sistem' }}</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Dicipta</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ $kariah->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Dikemaskini Oleh</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ $kariah->updater->name ?? 'Sistem' }}</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Kemaskini</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ $kariah->updated_at->format('d/m/Y H:i') }}</span>
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
