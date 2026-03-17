<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Butiran Transaksi - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Butiran Transaksi</h1>
                        <p class="text-xs text-gray-600">{{ $transaksiKewangan->no_transaksi }}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('transaksi-kewangan.edit', $transaksiKewangan->id) }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">edit</span>
                            Kemaskini
                        </a>
                        <a href="{{ route('transaksi-kewangan.index') }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                            Kembali
                        </a>
                    </div>
                </div>

                <!-- Status Badge & Amount Card -->
                <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Status Badge -->
                    <div class="bg-gradient-to-r {{ $transaksiKewangan->jenis_transaksi == 'Pendapatan' ? 'from-green-50 to-green-100' : 'from-red-50 to-red-100' }} rounded-lg p-4 border {{ $transaksiKewangan->jenis_transaksi == 'Pendapatan' ? 'border-green-200' : 'border-red-200' }}">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Jenis Transaksi</p>
                                <div class="flex items-center">
                                    <span class="material-icons mr-2 {{ $transaksiKewangan->jenis_transaksi == 'Pendapatan' ? 'text-green-600' : 'text-red-600' }}" style="font-size: 24px !important;">
                                        {{ $transaksiKewangan->jenis_transaksi == 'Pendapatan' ? 'arrow_downward' : 'arrow_upward' }}
                                    </span>
                                    <span class="text-sm font-bold {{ $transaksiKewangan->jenis_transaksi == 'Pendapatan' ? 'text-green-800' : 'text-red-800' }}">
                                        {{ $transaksiKewangan->jenis_transaksi }}
                                    </span>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $transaksiKewangan->status == 'Selesai' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $transaksiKewangan->status }}
                            </span>
                        </div>
                    </div>

                    <!-- Amount Card -->
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
                        <p class="text-xs text-gray-600 mb-1">Jumlah Transaksi</p>
                        <p class="text-2xl font-bold {{ $transaksiKewangan->jenis_transaksi == 'Pendapatan' ? 'text-green-600' : 'text-red-600' }}">
                            RM {{ number_format($transaksiKewangan->jumlah, 2) }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">{{ $transaksiKewangan->kategoriKewangan->nama_kategori }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="flex flex-col space-y-6">
                        <!-- Maklumat Transaksi -->
                        <div class="bg-white rounded-lg p-4 border border-gray-200 flex-1">
                            <h2 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-blue-600" style="font-size: 18px !important;">receipt_long</span>
                                Maklumat Transaksi
                            </h2>
                            <div class="space-y-3">
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <p class="text-xs text-gray-600">No. Transaksi</p>
                                    <p class="text-xs font-medium text-gray-900">{{ $transaksiKewangan->no_transaksi }}</p>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <p class="text-xs text-gray-600">Tarikh Transaksi</p>
                                    <p class="text-xs font-medium text-gray-900">{{ $transaksiKewangan->tarikh_transaksi->format('d/m/Y') }}</p>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <p class="text-xs text-gray-600">Kategori</p>
                                    <p class="text-xs font-medium text-gray-900">{{ $transaksiKewangan->kategoriKewangan->nama_kategori }}</p>
                                </div>
                                <div class="flex justify-between py-2">
                                    <p class="text-xs text-gray-600">Kaedah Bayaran</p>
                                    <p class="text-xs font-medium text-gray-900">{{ $transaksiKewangan->kaedah_bayaran }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Maklumat Bank -->
                        <div class="bg-white rounded-lg p-4 border border-gray-200 flex-1">
                            <h2 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-blue-600" style="font-size: 18px !important;">account_balance</span>
                                Maklumat Bank
                            </h2>
                            <div class="space-y-3">
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <p class="text-xs text-gray-600">Nama Bank</p>
                                    <p class="text-xs font-medium text-gray-900">{{ $transaksiKewangan->akaunBank->nama_bank }}</p>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <p class="text-xs text-gray-600">No. Akaun</p>
                                    <p class="text-xs font-medium text-gray-900">{{ $transaksiKewangan->akaunBank->no_akaun }}</p>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <p class="text-xs text-gray-600">Baki Pada Masa Transaksi</p>
                                    <p class="text-xs font-bold text-purple-600">RM {{ number_format($bakiPadaMasaTransaksi, 2) }}</p>
                                </div>
                                <div class="flex justify-between py-2">
                                    <p class="text-xs text-gray-600">Baki Semasa (Terkini)</p>
                                    <p class="text-xs font-bold text-blue-600">RM {{ number_format($transaksiKewangan->akaunBank->baki_semasa, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="flex flex-col space-y-6">
                        <!-- Butiran Transaksi -->
                        <div class="bg-white rounded-lg p-4 border border-gray-200 flex-1">
                            <h2 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-blue-600" style="font-size: 18px !important;">description</span>
                                Butiran Transaksi
                            </h2>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-xs text-gray-600 mb-1">Keterangan</p>
                                    <p class="text-xs font-medium text-gray-900 bg-gray-50 p-2 rounded">{{ $transaksiKewangan->keterangan }}</p>
                                </div>
                                @if($transaksiKewangan->no_rujukan)
                                <div>
                                    <p class="text-xs text-gray-600 mb-1">No. Rujukan</p>
                                    <p class="text-xs font-medium text-gray-900 bg-gray-50 p-2 rounded">{{ $transaksiKewangan->no_rujukan }}</p>
                                </div>
                                @endif
                                @if($transaksiKewangan->catatan)
                                <div>
                                    <p class="text-xs text-gray-600 mb-1">Catatan</p>
                                    <p class="text-xs font-medium text-gray-900 bg-gray-50 p-2 rounded">{{ $transaksiKewangan->catatan }}</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Dokumen Sokongan -->
                        @if($transaksiKewangan->dokumen)
                        <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg p-4 border border-purple-200">
                            <h2 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                                <span class="material-icons mr-2 text-purple-600" style="font-size: 18px !important;">attach_file</span>
                                Dokumen Sokongan
                            </h2>
                            <a href="{{ Storage::url($transaksiKewangan->dokumen) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-xs rounded hover:bg-purple-700 transition-colors">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">visibility</span>
                                Lihat Dokumen
                            </a>
                        </div>
                        @endif

                        <!-- Maklumat Sistem -->
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <h2 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-gray-600" style="font-size: 18px !important;">info</span>
                                Maklumat Sistem
                            </h2>
                            <div class="space-y-3">
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <p class="text-xs text-gray-600">Dicipta Pada</p>
                                    <p class="text-xs font-medium text-gray-900">{{ $transaksiKewangan->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <p class="text-xs text-gray-600">Dikemaskini Pada</p>
                                    <p class="text-xs font-medium text-gray-900">{{ $transaksiKewangan->updated_at->format('d/m/Y H:i') }}</p>
                                </div>
                                @if($transaksiKewangan->createdBy)
                                <div class="flex justify-between py-2">
                                    <p class="text-xs text-gray-600">Dicipta Oleh</p>
                                    <p class="text-xs font-medium text-gray-900">{{ $transaksiKewangan->createdBy->name }}</p>
                                </div>
                                @endif
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
