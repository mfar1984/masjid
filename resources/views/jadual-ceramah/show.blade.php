<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maklumat Jadual Ceramah - E-Masjid</title>
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
                    <div class="flex items-center">
                        <a href="{{ route('jadual-ceramah.index') }}" class="mr-3 text-gray-500 hover:text-gray-700">
                            <span class="material-icons">arrow_back</span>
                        </a>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900 mb-1">{{ $jadualCeramah->tajuk_ceramah }}</h1>
                            <p class="text-xs text-gray-600">{{ $jadualCeramah->tarikh->format('d/m/Y') }} | {{ $jadualCeramah->masa_mula }} - {{ $jadualCeramah->masa_tamat }}</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        @if($jadualCeramah->status_bayaran === 'Belum Bayar')
                        <form action="{{ route('jadual-ceramah.bayar', $jadualCeramah) }}" method="POST" onsubmit="return confirm('Sahkan bayaran?')">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-xs font-medium rounded-md hover:bg-green-700">
                                <span class="material-icons text-sm mr-1">payments</span>Bayar
                            </button>
                        </form>
                        @endif
                        <a href="{{ route('jadual-ceramah.edit', $jadualCeramah) }}" class="inline-flex items-center px-3 py-2 bg-yellow-500 text-white text-xs font-medium rounded-md hover:bg-yellow-600">
                            <span class="material-icons text-sm mr-1">edit</span>Edit
                        </a>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Ceramah</h3>
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-xs text-gray-500">Penceramah</dt>
                                <dd class="text-xs font-medium text-gray-900">{{ $jadualCeramah->penceramah->nama ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-xs text-gray-500">Tajuk</dt>
                                <dd class="text-xs text-gray-900">{{ $jadualCeramah->tajuk_ceramah }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-xs text-gray-500">Jenis</dt>
                                <dd class="text-xs text-gray-900">{{ $jadualCeramah->jenis_ceramah }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-xs text-gray-500">Tarikh</dt>
                                <dd class="text-xs text-gray-900">{{ $jadualCeramah->tarikh->format('d/m/Y') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-xs text-gray-500">Masa</dt>
                                <dd class="text-xs text-gray-900">{{ $jadualCeramah->masa_mula }} - {{ $jadualCeramah->masa_tamat }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-xs text-gray-500">Lokasi</dt>
                                <dd class="text-xs text-gray-900">{{ $jadualCeramah->lokasi ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-xs text-gray-500">Status</dt>
                                <dd class="text-xs">
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $jadualCeramah->status === 'Selesai' ? 'bg-green-100 text-green-800' : ($jadualCeramah->status === 'Batal' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
                                        {{ $jadualCeramah->status }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Bayaran</h3>
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-xs text-gray-500">Jenis Bayaran</dt>
                                <dd class="text-xs text-gray-900">{{ $jadualCeramah->jenis_bayaran }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-xs text-gray-500">Kadar Bayaran</dt>
                                <dd class="text-xs font-medium text-gray-900">RM {{ number_format($jadualCeramah->kadar_bayaran, 2) }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-xs text-gray-500">Status Bayaran</dt>
                                <dd class="text-xs">
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $jadualCeramah->status_bayaran === 'Sudah Bayar' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                        {{ $jadualCeramah->status_bayaran }}
                                    </span>
                                </dd>
                            </div>
                            @if($jadualCeramah->tarikh_bayaran)
                            <div class="flex justify-between">
                                <dt class="text-xs text-gray-500">Tarikh Bayaran</dt>
                                <dd class="text-xs text-gray-900">{{ $jadualCeramah->tarikh_bayaran->format('d/m/Y') }}</dd>
                            </div>
                            @endif
                        </dl>
                        @if($jadualCeramah->kos_pengangkutan || $jadualCeramah->kos_penginapan || $jadualCeramah->kos_makan_minum || $jadualCeramah->kos_lain)
                        <div class="border-t mt-4 pt-4">
                            <h4 class="text-xs font-medium text-gray-700 mb-3">Kos Tambahan</h4>
                            <dl class="space-y-2">
                                @if($jadualCeramah->kos_pengangkutan)
                                <div class="flex justify-between">
                                    <dt class="text-xs text-gray-500">Pengangkutan</dt>
                                    <dd class="text-xs text-gray-900">RM {{ number_format($jadualCeramah->kos_pengangkutan, 2) }}</dd>
                                </div>
                                @endif
                                @if($jadualCeramah->kos_penginapan)
                                <div class="flex justify-between">
                                    <dt class="text-xs text-gray-500">Penginapan</dt>
                                    <dd class="text-xs text-gray-900">RM {{ number_format($jadualCeramah->kos_penginapan, 2) }}</dd>
                                </div>
                                @endif
                                @if($jadualCeramah->kos_makan_minum)
                                <div class="flex justify-between">
                                    <dt class="text-xs text-gray-500">Makan Minum</dt>
                                    <dd class="text-xs text-gray-900">RM {{ number_format($jadualCeramah->kos_makan_minum, 2) }}</dd>
                                </div>
                                @endif
                                @if($jadualCeramah->kos_lain)
                                <div class="flex justify-between">
                                    <dt class="text-xs text-gray-500">Kos Lain</dt>
                                    <dd class="text-xs text-gray-900">RM {{ number_format($jadualCeramah->kos_lain, 2) }}</dd>
                                </div>
                                @endif
                                <div class="flex justify-between border-t pt-2 mt-2">
                                    <dt class="text-xs font-medium text-gray-700">Jumlah Kos</dt>
                                    <dd class="text-xs font-bold text-gray-900">RM {{ number_format($jadualCeramah->jumlah_kos, 2) }}</dd>
                                </div>
                            </dl>
                        </div>
                        @endif
                    </div>
                </div>
                @if($jadualCeramah->catatan || $jadualCeramah->catatan_kos)
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($jadualCeramah->catatan)
                    <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-2">Catatan</h3>
                        <p class="text-xs text-gray-600">{{ $jadualCeramah->catatan }}</p>
                    </div>
                    @endif
                    @if($jadualCeramah->catatan_kos)
                    <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-2">Catatan Kos</h3>
                        <p class="text-xs text-gray-600">{{ $jadualCeramah->catatan_kos }}</p>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </main>
    <x-footer />
</body>
</html>
