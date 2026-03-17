<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran {{ $pembayaranBantuan->no_pembayaran }} - E-Masjid</title>
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
                <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Pembayaran {{ $pembayaranBantuan->no_pembayaran }}</h1>
                        <p class="text-xs text-gray-600">Butiran pembayaran bantuan - {{ $pembayaranBantuan->penerimaBantuan->nama_penuh }}</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        <a href="{{ route('pembayaran-bantuan.index') }}" class="inline-flex items-center justify-center h-[32px] px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs rounded">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                            Kembali
                        </a>
                        @if($pembayaranBantuan->status_pembayaran !== 'Sudah Bayar' && auth()->user()->hasPermission('pembayaran_bantuan', 'update'))
                            <a href="{{ route('pembayaran-bantuan.edit', $pembayaranBantuan) }}" class="inline-flex items-center justify-center h-[32px] px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">edit</span>
                                Edit
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Status Badge -->
                <div class="mb-6">
                    @if($pembayaranBantuan->status_pembayaran === 'Sudah Bayar')
                        <span class="inline-flex items-center px-3 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                            <span class="material-icons mr-1" style="font-size: 14px !important;">check_circle</span>
                            {{ $pembayaranBantuan->status_pembayaran }}
                        </span>
                    @elseif($pembayaranBantuan->status_pembayaran === 'Belum Bayar')
                        <span class="inline-flex items-center px-3 py-1 rounded text-xs font-medium bg-orange-100 text-orange-800">
                            <span class="material-icons mr-1" style="font-size: 14px !important;">pending</span>
                            {{ $pembayaranBantuan->status_pembayaran }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded text-xs font-medium bg-red-100 text-red-800">
                            <span class="material-icons mr-1" style="font-size: 14px !important;">cancel</span>
                            {{ $pembayaranBantuan->status_pembayaran }}
                        </span>
                    @endif
                </div>

                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded text-xs">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="space-y-6">
                    <!-- 1. Maklumat Pembayaran -->
                    <div class="bg-gray-50 rounded p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">1. Maklumat Pembayaran</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">No Pembayaran</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900 font-semibold">{{ $pembayaranBantuan->no_pembayaran }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Pembayaran</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $pembayaranBantuan->tarikh_pembayaran->format('d/m/Y') }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah Bayaran</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm font-bold text-gray-900">RM {{ number_format($pembayaranBantuan->jumlah_bayaran, 2) }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Kaedah Bayaran</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $pembayaranBantuan->kaedah_bayaran }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Maklumat Permohonan -->
                    <div class="bg-gray-50 rounded p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">2. Maklumat Permohonan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">No Permohonan</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">
                                    <a href="{{ route('permohonan-bantuan.show', $pembayaranBantuan->permohonanBantuan) }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $pembayaranBantuan->permohonanBantuan->no_permohonan }}
                                    </a>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Program Kebajikan</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $pembayaranBantuan->programKebajikan->nama_program }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Maklumat Penerima -->
                    <div class="bg-gray-50 rounded p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">3. Maklumat Penerima</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Nama Penuh</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900 font-semibold">{{ $pembayaranBantuan->penerimaBantuan->nama_penuh }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">No. IC</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $pembayaranBantuan->penerimaBantuan->no_kp }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">No. Telefon</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $pembayaranBantuan->penerimaBantuan->no_telefon }}</div>
                            </div>
                            <div class="md:col-span-2">
                                <a href="{{ route('penerima-bantuan.show', $pembayaranBantuan->penerimaBantuan) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 text-xs">
                                    <span class="material-icons mr-1" style="font-size: 16px !important;">visibility</span>
                                    Lihat Profil Lengkap Penerima
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Maklumat Bank/Cek/Barangan -->
                    @if($pembayaranBantuan->kaedah_bayaran === 'Bank Transfer' || $pembayaranBantuan->kaedah_bayaran === 'Cek')
                    <div class="bg-gray-50 rounded p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">4. Maklumat {{ $pembayaranBantuan->kaedah_bayaran }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($pembayaranBantuan->nama_bank)
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Nama Bank</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $pembayaranBantuan->nama_bank }}</div>
                            </div>
                            @endif
                            @if($pembayaranBantuan->no_akaun)
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">No. Akaun</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $pembayaranBantuan->no_akaun }}</div>
                            </div>
                            @endif
                            @if($pembayaranBantuan->no_rujukan)
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">No. Rujukan</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $pembayaranBantuan->no_rujukan }}</div>
                            </div>
                            @endif
                            @if($pembayaranBantuan->no_cek)
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">No. Cek</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $pembayaranBantuan->no_cek }}</div>
                            </div>
                            @endif
                            @if($pembayaranBantuan->tarikh_cek)
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Cek</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $pembayaranBantuan->tarikh_cek->format('d/m/Y') }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($pembayaranBantuan->kaedah_bayaran === 'Barangan')
                    <div class="bg-gray-50 rounded p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">4. Maklumat Barangan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Senarai Barangan</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $pembayaranBantuan->senarai_barangan }}</div>
                            </div>
                            @if($pembayaranBantuan->nilai_barangan)
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Nilai Barangan</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm font-bold text-gray-900">RM {{ number_format($pembayaranBantuan->nilai_barangan, 2) }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- 5. Maklumat Status -->
                    @if($pembayaranBantuan->status_pembayaran === 'Sudah Bayar')
                    <div class="bg-green-50 rounded p-4 border border-green-200">
                        <h3 class="text-sm font-semibold text-green-900 mb-4">5. Maklumat Pembayaran</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-green-700 mb-1">Dibayar Oleh</label>
                                <div class="p-2 bg-white border border-green-300 rounded text-sm text-green-900">{{ $pembayaranBantuan->pembayar->name ?? '-' }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-green-700 mb-1">Tarikh Dibayar</label>
                                <div class="p-2 bg-white border border-green-300 rounded text-sm text-green-900">{{ $pembayaranBantuan->tarikh_dibayar->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
