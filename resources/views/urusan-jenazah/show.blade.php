<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maklumat Urusan Jenazah - E-Masjid</title>
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
                        <a href="{{ route('urusan-jenazah.index') }}" class="mr-3 text-gray-500 hover:text-gray-700">
                            <span class="material-icons">arrow_back</span>
                        </a>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900 mb-1">{{ $urusanJenazah->nama_simati }}</h1>
                            <p class="text-xs text-gray-600">{{ $urusanJenazah->no_rujukan }}</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('urusan-jenazah.edit', $urusanJenazah) }}" class="inline-flex items-center px-3 py-2 bg-yellow-500 text-white text-xs font-medium rounded-md hover:bg-yellow-600">
                            <span class="material-icons text-sm mr-1">edit</span>Edit
                        </a>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Simati</h3>
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-xs text-gray-500">No. Rujukan</dt>
                                <dd class="text-xs font-medium text-gray-900">{{ $urusanJenazah->no_rujukan }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-xs text-gray-500">Nama Simati</dt>
                                <dd class="text-xs font-medium text-gray-900">{{ $urusanJenazah->nama_simati }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-xs text-gray-500">No. IC</dt>
                                <dd class="text-xs text-gray-900">{{ $urusanJenazah->no_ic_simati ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-xs text-gray-500">Jantina</dt>
                                <dd class="text-xs"><span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $urusanJenazah->jantina === 'Lelaki' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">{{ $urusanJenazah->jantina }}</span></dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-xs text-gray-500">Umur</dt>
                                <dd class="text-xs text-gray-900">{{ $urusanJenazah->umur ? $urusanJenazah->umur . ' tahun' : '-' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-xs text-gray-500">Alamat</dt>
                                <dd class="text-xs text-gray-900">{{ $urusanJenazah->alamat_simati ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-xs text-gray-500">Tarikh Meninggal</dt>
                                <dd class="text-xs text-gray-900">{{ $urusanJenazah->tarikh_meninggal->format('d/m/Y') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-xs text-gray-500">Masa Meninggal</dt>
                                <dd class="text-xs text-gray-900">{{ $urusanJenazah->masa_meninggal ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-xs text-gray-500">Tempat Meninggal</dt>
                                <dd class="text-xs text-gray-900">{{ $urusanJenazah->tempat_meninggal ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-xs text-gray-500">Sebab Kematian</dt>
                                <dd class="text-xs text-gray-900">{{ $urusanJenazah->sebab_kematian ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>
                    <div class="space-y-6">
                        <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Waris</h3>
                            <dl class="space-y-3">
                                <div class="flex justify-between">
                                    <dt class="text-xs text-gray-500">Nama Waris</dt>
                                    <dd class="text-xs font-medium text-gray-900">{{ $urusanJenazah->nama_waris }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-xs text-gray-500">No. Telefon</dt>
                                    <dd class="text-xs text-gray-900">{{ $urusanJenazah->no_telefon_waris }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-xs text-gray-500">Hubungan</dt>
                                    <dd class="text-xs text-gray-900">{{ $urusanJenazah->hubungan_waris ?? '-' }}</dd>
                                </div>
                            </dl>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Pengurusan</h3>
                            <dl class="space-y-3">
                                <div class="flex justify-between">
                                    <dt class="text-xs text-gray-500">Mandi & Kafan</dt>
                                    <dd class="text-xs text-gray-900">{{ $urusanJenazah->tarikh_mandi_kafan ? $urusanJenazah->tarikh_mandi_kafan->format('d/m/Y H:i') : '-' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-xs text-gray-500">Solat Jenazah</dt>
                                    <dd class="text-xs text-gray-900">{{ $urusanJenazah->tarikh_solat_jenazah ? $urusanJenazah->tarikh_solat_jenazah->format('d/m/Y H:i') : '-' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-xs text-gray-500">Imam Solat</dt>
                                    <dd class="text-xs text-gray-900">{{ $urusanJenazah->imam_solat ?? '-' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-xs text-gray-500">Tarikh Kebumi</dt>
                                    <dd class="text-xs text-gray-900">{{ $urusanJenazah->tarikh_kebumi ? $urusanJenazah->tarikh_kebumi->format('d/m/Y H:i') : '-' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-xs text-gray-500">Lokasi Kubur</dt>
                                    <dd class="text-xs text-gray-900">{{ $urusanJenazah->lokasi_kubur ?? '-' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-xs text-gray-500">No. Kubur</dt>
                                    <dd class="text-xs text-gray-900">{{ $urusanJenazah->no_kubur ?? '-' }}</dd>
                                </div>
                            </dl>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Kos & Status</h3>
                            <dl class="space-y-3">
                                <div class="flex justify-between">
                                    <dt class="text-xs text-gray-500">Kos Pengurusan</dt>
                                    <dd class="text-xs font-medium text-gray-900">RM {{ number_format($urusanJenazah->kos_pengurusan, 2) }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-xs text-gray-500">Status Bayaran</dt>
                                    <dd class="text-xs"><span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $urusanJenazah->status_bayaran === 'Sudah Bayar' ? 'bg-green-100 text-green-800' : ($urusanJenazah->status_bayaran === 'Percuma' ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800') }}">{{ $urusanJenazah->status_bayaran }}</span></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-xs text-gray-500">Status</dt>
                                    <dd class="text-xs"><span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $urusanJenazah->status === 'Selesai' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">{{ $urusanJenazah->status }}</span></dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
                @if($urusanJenazah->catatan)
                <div class="mt-6 bg-gray-50 p-4 rounded-md border border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-900 mb-2">Catatan</h3>
                    <p class="text-xs text-gray-600">{{ $urusanJenazah->catatan }}</p>
                </div>
                @endif
            </div>
        </div>
    </main>
    <x-footer />
</body>
</html>
