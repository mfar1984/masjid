<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $masjid->nama }} - E-Masjid</title>
    
    <!-- Favicon -->
    <x-favicon />
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans min-h-screen flex flex-col" data-theme="corporate">
    <x-double-navbar :user="auth()->user()" />
    
    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <!-- Main Container -->
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <!-- Header Section -->
                <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Butiran Masjid</h1>
                        <p class="text-xs text-gray-600">Maklumat lengkap {{ $masjid->nama }}</p>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        <a href="{{ route('senarai-masjid.index') }}" class="inline-flex items-center justify-center h-[32px] px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs rounded transition-colors">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                            Kembali
                        </a>
                        <a href="{{ route('senarai-masjid.edit', $masjid) }}" class="inline-flex items-center justify-center h-[32px] px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded transition-colors">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">edit</span>
                            Edit
                        </a>
                    </div>
                </div>

                <!-- Form-like Display -->
                <div class="space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-gray-50 rounded-sm p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons text-blue-600 text-sm mr-2">info</span>
                            Maklumat Asas
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Nama Masjid</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm mr-2">{{ $masjid->kategori_icon }}</span>
                                    <span class="text-sm text-gray-900">{{ $masjid->nama }}</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Kod Masjid</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ $masjid->kod_masjid }}</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Kategori</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ ucfirst($masjid->kategori) }}</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Negeri</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ $masjid->negeri }}</span>
                                </div>
                            </div>



                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Bandar</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ $masjid->bandar ?? '-' }}</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Ditubuhkan</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ $masjid->tarikh_ditubuhkan ? \Carbon\Carbon::parse($masjid->tarikh_ditubuhkan)->format('d/m/Y') : '-' }}</span>
                                </div>
                            </div>


                        </div>
                    </div>

                    <!-- Address & Location Information -->
                    <div class="bg-gray-50 rounded-sm p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons text-green-600 text-sm mr-2">location_on</span>
                            Alamat & Lokasi
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Alamat Penuh</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm min-h-[60px]">
                                    <span class="text-sm text-gray-900">{{ $masjid->alamat }}</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Poskod</label>
                                    <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                        <span class="text-sm text-gray-900">{{ $masjid->poskod ?? '-' }}</span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Bandar</label>
                                    <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                        <span class="text-sm text-gray-900">{{ $masjid->bandar ?? '-' }}</span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Negeri</label>
                                    <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                        <span class="text-sm text-gray-900">{{ $masjid->negeri }}</span>
                                    </div>
                                </div>
                            </div>

                            @if($masjid->latitude && $masjid->longitude)
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Koordinat</label>
                                <div class="flex items-center space-x-2">
                                    <div class="flex-1 flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                        <span class="text-sm text-gray-900">{{ $masjid->latitude }}, {{ $masjid->longitude }}</span>
                                    </div>
                                    <a href="https://www.google.com/maps?q={{ $masjid->latitude }},{{ $masjid->longitude }}"
                                       target="_blank"
                                       class="inline-flex items-center justify-center h-[32px] px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded transition-colors">
                                        <span class="material-icons mr-1" style="font-size: 16px !important;">map</span>
                                        Lihat di Maps
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="bg-gray-50 rounded-sm p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons text-blue-600 text-sm mr-2">phone</span>
                            Maklumat Hubungan
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Telefon</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ $masjid->telefon ?? '-' }}</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Faks</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ $masjid->faks ?? '-' }}</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ $masjid->email ?? '-' }}</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Laman Web</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    @if($masjid->laman_web)
                                        <a href="{{ $masjid->laman_web }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 hover:underline">
                                            {{ $masjid->laman_web }}
                                        </a>
                                    @else
                                        <span class="text-sm text-gray-900">-</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="bg-gray-50 rounded-sm p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons text-purple-600 text-sm mr-2">info</span>
                            Maklumat Tambahan
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Nombor Daftar</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ $masjid->nombor_daftar ?? '-' }}</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Nama Penuh</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ $masjid->nama_penuh ?? '-' }}</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Kapasiti Jemaah</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ $masjid->kapasiti_jemaah ? number_format($masjid->kapasiti_jemaah) . ' orang' : '-' }}</span>
                                </div>
                            </div>

                            @if($masjid->logo_path)
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Logo</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <a href="{{ Storage::url($masjid->logo_path) }}" target="_blank" class="inline-flex items-center px-2 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs rounded transition-colors">
                                        <span class="material-icons mr-1" style="font-size: 16px !important;">image</span>
                                        Lihat Logo
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Registrar Information -->
                    <div class="bg-gray-50 rounded-sm p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons text-green-600 text-sm mr-2">person</span>
                            Maklumat Pendaftar
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Nama Pendaftar</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ $masjid->pendaftar_nama ?? '-' }}</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Jawatan</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ $masjid->pendaftar_jawatan ?? '-' }}</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Telefon Pendaftar</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ $masjid->pendaftar_telefon ?? '-' }}</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Email Pendaftar</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ $masjid->pendaftar_email ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attachments -->
                    @if($masjid->attachments && $masjid->attachments->count() > 0)
                    <div class="bg-gray-50 rounded-sm p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons text-purple-600 text-sm mr-2">attach_file</span>
                            Lampiran
                        </h3>

                        <div class="space-y-2">
                            @foreach($masjid->attachments as $attachment)
                            <div class="flex items-center justify-between p-3 bg-white border border-gray-300 rounded-sm">
                                <div class="flex items-center">
                                    <span class="material-icons text-gray-500 text-sm mr-2">description</span>
                                    <span class="text-sm text-gray-900">{{ $attachment->original_name }}</span>
                                </div>
                                <a href="{{ Storage::url($attachment->file_path) }}"
                                   target="_blank"
                                   class="inline-flex items-center px-2 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs rounded transition-colors">
                                    <span class="material-icons mr-1" style="font-size: 16px !important;">visibility</span>
                                    Lihat
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- System Information -->
                    <div class="bg-gray-50 rounded-sm p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons text-orange-600 text-sm mr-2">schedule</span>
                            Maklumat Sistem
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Didaftar</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ $masjid->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Kemaskini Terakhir</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ $masjid->updated_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    {!! $masjid->status_badge !!}
                                </div>
                            </div>

                            @if($masjid->approved_at)
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Diluluskan</label>
                                <div class="flex items-center p-2 bg-white border border-gray-300 rounded-sm">
                                    <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($masjid->approved_at)->format('d/m/Y H:i') }}</span>
                                </div>
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
