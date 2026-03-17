<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Asnaf - E-Masjid</title>
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
                <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Butiran Permohonan Asnaf</h1>
                        <p class="text-xs text-gray-600">Maklumat lengkap {{ $asnaf->nama }}</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        <a href="{{ route('asnaf.index') }}" class="inline-flex items-center justify-center h-[32px] px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs rounded">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                            Kembali
                        </a>
                        @if(auth()->user()->hasPermission('asnaf', 'update'))
                            <a href="{{ route('asnaf.edit', $asnaf) }}" class="inline-flex items-center justify-center h-[32px] px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">edit</span>
                                Edit
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Status Badge -->
                <div class="mb-6">
                    @if($asnaf->status == 'Diluluskan')
                        <span class="inline-flex items-center px-3 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                            <span class="material-icons mr-1" style="font-size: 14px !important;">check_circle</span>
                            {{ $asnaf->status }}
                        </span>
                    @elseif($asnaf->status == 'Menunggu')
                        <span class="inline-flex items-center px-3 py-1 rounded text-xs font-medium bg-orange-100 text-orange-800">
                            <span class="material-icons mr-1" style="font-size: 14px !important;">pending</span>
                            {{ $asnaf->status }}
                        </span>
                    @elseif($asnaf->status == 'Ditolak')
                        <span class="inline-flex items-center px-3 py-1 rounded text-xs font-medium bg-red-100 text-red-800">
                            <span class="material-icons mr-1" style="font-size: 14px !important;">close</span>
                            {{ $asnaf->status }}
                        </span>
                    @elseif($asnaf->status == 'Digantung')
                        <span class="inline-flex items-center px-3 py-1 rounded text-xs font-medium bg-purple-100 text-purple-800">
                            <span class="material-icons mr-1" style="font-size: 14px !important;">pause_circle</span>
                            {{ $asnaf->status }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                            <span class="material-icons mr-1" style="font-size: 14px !important;">rate_review</span>
                            {{ $asnaf->status }}
                        </span>
                    @endif
                </div>

                <div class="space-y-6">

                    <!-- 1. Maklumat Peribadi -->
                    <div class="bg-gray-50 rounded p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">1. Maklumat Peribadi</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><label class="block text-xs font-medium text-gray-700 mb-1">Nama Penuh</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $asnaf->nama }}</div></div>
                            <div><label class="block text-xs font-medium text-gray-700 mb-1">No. IC</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $asnaf->no_ic }}</div></div>
                            <div><label class="block text-xs font-medium text-gray-700 mb-1">Jantina</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $asnaf->jantina }}</div></div>
                            <div><label class="block text-xs font-medium text-gray-700 mb-1">Bangsa</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $asnaf->bangsa }}</div></div>
                            <div><label class="block text-xs font-medium text-gray-700 mb-1">Agama</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $asnaf->agama }}</div></div>
                            <div><label class="block text-xs font-medium text-gray-700 mb-1">Status Perkahwinan</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $asnaf->status_perkahwinan }}</div></div>
                            <div><label class="block text-xs font-medium text-gray-700 mb-1">Telefon</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $asnaf->telefon }}</div></div>
                            <div><label class="block text-xs font-medium text-gray-700 mb-1">Email</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $asnaf->email ?: '-' }}</div></div>
                        </div>
                    </div>

                    <!-- 2. Alamat IC -->
                    <div class="bg-gray-50 rounded p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">2. Alamat Mengikut IC</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2"><label class="block text-xs font-medium text-gray-700 mb-1">Alamat</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $asnaf->alamat_ic }}</div></div>
                            <div><label class="block text-xs font-medium text-gray-700 mb-1">Poskod</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $asnaf->poskod_ic }}</div></div>
                            <div><label class="block text-xs font-medium text-gray-700 mb-1">Bandar</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $asnaf->bandar_ic }}</div></div>
                            <div class="md:col-span-2"><label class="block text-xs font-medium text-gray-700 mb-1">Negeri</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $asnaf->negeri_ic }}</div></div>
                        </div>
                    </div>

                    <!-- 3. Alamat Kediaman -->
                    <div class="bg-gray-50 rounded p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">3. Alamat Kediaman Semasa</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2"><label class="block text-xs font-medium text-gray-700 mb-1">Alamat</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $asnaf->alamat_kediaman }}</div></div>
                            <div><label class="block text-xs font-medium text-gray-700 mb-1">Poskod</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $asnaf->poskod_kediaman }}</div></div>
                            <div><label class="block text-xs font-medium text-gray-700 mb-1">Bandar</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $asnaf->bandar_kediaman }}</div></div>
                            <div><label class="block text-xs font-medium text-gray-700 mb-1">Negeri</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $asnaf->negeri_kediaman }}</div></div>
                            <div><label class="block text-xs font-medium text-gray-700 mb-1">Status Kediaman</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $asnaf->status_kediaman }}</div></div>
                        </div>
                    </div>

                    <!-- 4. Maklumat Waris -->
                    <div class="bg-gray-50 rounded p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">4. Maklumat Waris</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><label class="block text-xs font-medium text-gray-700 mb-1">Nama Waris</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $asnaf->nama_waris }}</div></div>
                            <div><label class="block text-xs font-medium text-gray-700 mb-1">Hubungan</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $asnaf->hubungan_waris }}</div></div>
                            <div><label class="block text-xs font-medium text-gray-700 mb-1">No. IC Waris</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $asnaf->no_ic_waris }}</div></div>
                            <div><label class="block text-xs font-medium text-gray-700 mb-1">Telefon Waris</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $asnaf->telefon_waris }}</div></div>
                        </div>
                    </div>

                    <!-- 5. Kategori Asnaf -->
                    <div class="bg-gray-50 rounded p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">5. Kategori Asnaf & Sebab Permohonan</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div><label class="block text-xs font-medium text-gray-700 mb-1">Kategori Asnaf</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900"><span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-purple-100 text-purple-800">{{ $asnaf->kategori_asnaf }}</span></div></div>
                            <div><label class="block text-xs font-medium text-gray-700 mb-1">Sebab Permohonan</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $asnaf->sebab_permohonan }}</div></div>
                        </div>
                    </div>

                    <!-- 6. Pekerjaan & Pendapatan -->
                    <div class="bg-gray-50 rounded p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">6. Pekerjaan & Pendapatan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><label class="block text-xs font-medium text-gray-700 mb-1">Status Pekerjaan</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $asnaf->status_pekerjaan }}</div></div>
                            <div><label class="block text-xs font-medium text-gray-700 mb-1">Nama Majikan</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $asnaf->nama_majikan ?: '-' }}</div></div>
                            <div><label class="block text-xs font-medium text-gray-700 mb-1">Pendapatan Bulanan</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">RM {{ number_format($asnaf->pendapatan_bulanan, 2) }}</div></div>
                            <div><label class="block text-xs font-medium text-gray-700 mb-1">Pendapatan Pasangan</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">RM {{ number_format($asnaf->pendapatan_pasangan, 2) }}</div></div>
                            <div><label class="block text-xs font-medium text-gray-700 mb-1">Jumlah Pendapatan</label><div class="p-2 bg-white border border-gray-300 rounded text-sm font-semibold text-gray-900">RM {{ number_format($asnaf->total_pendapatan, 2) }}</div></div>
                            <div><label class="block text-xs font-medium text-gray-700 mb-1">Pendapatan Per Kapita</label><div class="p-2 bg-white border border-gray-300 rounded text-sm font-semibold text-gray-900">RM {{ number_format($asnaf->pendapatan_per_kapita, 2) }}</div></div>
                        </div>
                    </div>

                    <!-- 7. Tanggungan -->
                    <div class="bg-gray-50 rounded p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">7. Tanggungan & Perbelanjaan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><label class="block text-xs font-medium text-gray-700 mb-1">Bilangan Tanggungan</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $asnaf->bilangan_tanggungan }} orang</div></div>
                            <div><label class="block text-xs font-medium text-gray-700 mb-1">Jumlah Perbelanjaan</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">RM {{ number_format($asnaf->jumlah_perbelanjaan, 2) }}</div></div>
                        </div>
                    </div>

                    <!-- 8. Hutang -->
                    @if($asnaf->ada_hutang)
                    <div class="bg-gray-50 rounded p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">8. Maklumat Hutang</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><label class="block text-xs font-medium text-gray-700 mb-1">Jumlah Hutang</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">RM {{ number_format($asnaf->jumlah_hutang, 2) }}</div></div>
                            <div><label class="block text-xs font-medium text-gray-700 mb-1">Bayaran Bulanan</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">RM {{ number_format($asnaf->bayaran_hutang_bulanan, 2) }}</div></div>
                            <div class="md:col-span-2"><label class="block text-xs font-medium text-gray-700 mb-1">Sebab Berhutang</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $asnaf->sebab_berhutang }}</div></div>
                        </div>
                    </div>
                    @endif

                    <!-- 9. Kesihatan -->
                    <div class="bg-gray-50 rounded p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">9. Maklumat Kesihatan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><label class="block text-xs font-medium text-gray-700 mb-1">Status Kesihatan</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $asnaf->status_kesihatan }}</div></div>
                            <div><label class="block text-xs font-medium text-gray-700 mb-1">Jenis Penyakit</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $asnaf->jenis_penyakit ?: '-' }}</div></div>
                            <div><label class="block text-xs font-medium text-gray-700 mb-1">Kos Perubatan Bulanan</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">RM {{ number_format($asnaf->kos_perubatan_bulanan, 2) }}</div></div>
                        </div>
                    </div>

                    <!-- 10. Aset -->
                    <div class="bg-gray-50 rounded p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">10. Maklumat Aset</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><label class="block text-xs font-medium text-gray-700 mb-1">Pemilikan Rumah</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $asnaf->pemilikan_rumah }}</div></div>
                            <div><label class="block text-xs font-medium text-gray-700 mb-1">Pemilikan Kenderaan</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $asnaf->pemilikan_kenderaan }}</div></div>
                            <div><label class="block text-xs font-medium text-gray-700 mb-1">Simpanan Bank</label><div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">RM {{ number_format($asnaf->simpanan_bank, 2) }}</div></div>
                        </div>
                    </div>

                    <!-- 11. Kelulusan -->
                    @if($asnaf->status == 'Diluluskan' && $asnaf->jumlah_diluluskan)
                    <div class="bg-green-50 rounded p-4 border border-green-200">
                        <h3 class="text-sm font-semibold text-green-900 mb-4">Maklumat Kelulusan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><label class="block text-xs font-medium text-green-700 mb-1">Jumlah Diluluskan</label><div class="p-2 bg-white border border-green-300 rounded text-sm font-bold text-green-900">RM {{ number_format($asnaf->jumlah_diluluskan, 2) }}</div></div>
                            <div><label class="block text-xs font-medium text-green-700 mb-1">Tarikh Diluluskan</label><div class="p-2 bg-white border border-green-300 rounded text-sm text-green-900">{{ $asnaf->tarikh_diluluskan ? $asnaf->tarikh_diluluskan->format('d/m/Y') : '-' }}</div></div>
                            @if($asnaf->catatan_kelulusan)
                            <div class="md:col-span-2"><label class="block text-xs font-medium text-green-700 mb-1">Catatan</label><div class="p-2 bg-white border border-green-300 rounded text-sm text-green-900">{{ $asnaf->catatan_kelulusan }}</div></div>
                            @endif
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
