<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Penerima Bantuan - E-Masjid</title>
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
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Butiran Penerima Bantuan</h1>
                        <p class="text-xs text-gray-600">{{ $penerimaBantuan->no_pendaftaran }} - {{ $penerimaBantuan->nama_penuh }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('penerima-bantuan.index') }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                            Kembali
                        </a>
                        @if(auth()->user()->hasPermission('penerima_bantuan', 'update'))
                            <a href="{{ route('penerima-bantuan.edit', $penerimaBantuan->id) }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">edit</span>
                                Edit
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Section 1: Maklumat Peribadi -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Peribadi</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">No. Pendaftaran</label>
                            <p class="text-xs text-gray-900">{{ $penerimaBantuan->no_pendaftaran }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Nama Penuh</label>
                            <p class="text-xs text-gray-900 font-semibold">{{ $penerimaBantuan->nama_penuh }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">No. KP</label>
                            <p class="text-xs text-gray-900">{{ $penerimaBantuan->no_kp }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Jantina</label>
                            <p class="text-xs text-gray-900">{{ $penerimaBantuan->jantina }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tarikh Lahir</label>
                            <p class="text-xs text-gray-900">{{ $penerimaBantuan->tarikh_lahir ? \Carbon\Carbon::parse($penerimaBantuan->tarikh_lahir)->format('d/m/Y') : '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Umur</label>
                            <p class="text-xs text-gray-900">{{ $penerimaBantuan->umur ?? '-' }} tahun</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Bangsa</label>
                            <p class="text-xs text-gray-900">{{ $penerimaBantuan->bangsa ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Agama</label>
                            <p class="text-xs text-gray-900">{{ $penerimaBantuan->agama ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Status Perkahwinan</label>
                            <p class="text-xs text-gray-900">{{ $penerimaBantuan->status_perkahwinan }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Kewarganegaraan</label>
                            <p class="text-xs text-gray-900">{{ $penerimaBantuan->kewarganegaraan ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Maklumat Hubungan -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Hubungan</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">No. Telefon</label>
                            <p class="text-xs text-gray-900">{{ $penerimaBantuan->no_telefon }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">No. Telefon Kecemasan</label>
                            <p class="text-xs text-gray-900">{{ $penerimaBantuan->no_telefon_kecemasan ?? '-' }}</p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Emel</label>
                            <p class="text-xs text-gray-900">{{ $penerimaBantuan->emel ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Alamat -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Alamat Semasa</h2>
                    
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Alamat Lengkap</label>
                            <p class="text-xs text-gray-900">
                                {{ $penerimaBantuan->alamat_1 }}<br>
                                @if($penerimaBantuan->alamat_2){{ $penerimaBantuan->alamat_2 }}<br>@endif
                                {{ $penerimaBantuan->poskod }} {{ $penerimaBantuan->bandar }}<br>
                                {{ $penerimaBantuan->negeri }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Maklumat Keluarga -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Keluarga</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Bilangan Tanggungan</label>
                            <p class="text-xs text-gray-900">{{ $penerimaBantuan->bilangan_tanggungan ?? 0 }} orang</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Bilangan Anak</label>
                            <p class="text-xs text-gray-900">{{ $penerimaBantuan->bilangan_anak ?? 0 }} orang</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Bilangan Anak Sekolah</label>
                            <p class="text-xs text-gray-900">{{ $penerimaBantuan->bilangan_anak_sekolah ?? 0 }} orang</p>
                        </div>
                    </div>

                    @if($penerimaBantuan->nama_pasangan)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-200">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Nama Pasangan</label>
                            <p class="text-xs text-gray-900">{{ $penerimaBantuan->nama_pasangan }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">No. KP Pasangan</label>
                            <p class="text-xs text-gray-900">{{ $penerimaBantuan->no_kp_pasangan ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Pekerjaan Pasangan</label>
                            <p class="text-xs text-gray-900">{{ $penerimaBantuan->pekerjaan_pasangan ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Pendapatan Pasangan</label>
                            <p class="text-xs text-gray-900">{{ $penerimaBantuan->pendapatan_pasangan ? 'RM ' . number_format($penerimaBantuan->pendapatan_pasangan, 2) : '-' }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Section 5: Maklumat Pekerjaan & Kewangan -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Pekerjaan & Kewangan</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Status Pekerjaan</label>
                            <p class="text-xs text-gray-900">{{ $penerimaBantuan->status_pekerjaan }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Pekerjaan</label>
                            <p class="text-xs text-gray-900">{{ $penerimaBantuan->pekerjaan ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Majikan</label>
                            <p class="text-xs text-gray-900">{{ $penerimaBantuan->majikan ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Pendapatan Bulanan</label>
                            <p class="text-xs text-gray-900">{{ $penerimaBantuan->pendapatan_bulanan ? 'RM ' . number_format($penerimaBantuan->pendapatan_bulanan, 2) : '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Pendapatan Lain</label>
                            <p class="text-xs text-gray-900">{{ $penerimaBantuan->pendapatan_lain ? 'RM ' . number_format($penerimaBantuan->pendapatan_lain, 2) : '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Jumlah Pendapatan</label>
                            <p class="text-xs text-gray-900 font-semibold">{{ $penerimaBantuan->jumlah_pendapatan ? 'RM ' . number_format($penerimaBantuan->jumlah_pendapatan, 2) : '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Jenis Kediaman</label>
                            <p class="text-xs text-gray-900">{{ $penerimaBantuan->jenis_kediaman }}</p>
                        </div>

                        @if($penerimaBantuan->sewa_bulanan)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Sewa Bulanan</label>
                            <p class="text-xs text-gray-900">RM {{ number_format($penerimaBantuan->sewa_bulanan, 2) }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Section 6: Kategori Kebajikan -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Kategori Kebajikan</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Status OKU</label>
                            <p class="text-xs">
                                @if($penerimaBantuan->status_oku === 'Ya')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-purple-100 text-purple-800">Ya - OKU</span>
                                @else
                                    <span class="text-gray-900">Tidak</span>
                                @endif
                            </p>
                        </div>

                        @if($penerimaBantuan->status_oku === 'Ya')
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Jenis OKU</label>
                            <p class="text-xs text-gray-900">{{ $penerimaBantuan->jenis_oku ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">No. Kad OKU</label>
                            <p class="text-xs text-gray-900">{{ $penerimaBantuan->no_kad_oku ?? '-' }}</p>
                        </div>
                        @endif

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Status Yatim</label>
                            <p class="text-xs">
                                @if($penerimaBantuan->status_yatim === 'Ya')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-blue-100 text-blue-800">Ya - Yatim</span>
                                @else
                                    <span class="text-gray-900">Tidak</span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Status Ibu Tunggal</label>
                            <p class="text-xs">
                                @if($penerimaBantuan->status_ibu_tunggal === 'Ya')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-pink-100 text-pink-800">Ya - Ibu Tunggal</span>
                                @else
                                    <span class="text-gray-900">Tidak</span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Status Warga Emas</label>
                            <p class="text-xs">
                                @if($penerimaBantuan->status_warga_emas === 'Ya')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">Ya - Warga Emas</span>
                                @else
                                    <span class="text-gray-900">Tidak</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Section 7: Status & Catatan -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Status & Catatan</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Status Penerima</label>
                            <p class="text-xs">
                                @if($penerimaBantuan->status_penerima === 'Aktif')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                @elseif($penerimaBantuan->status_penerima === 'Tidak Aktif')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">Tidak Aktif</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Tamat</span>
                                @endif
                            </p>
                        </div>

                        @if($penerimaBantuan->catatan)
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Catatan</label>
                            <p class="text-xs text-gray-900">{{ $penerimaBantuan->catatan }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Section 8: Maklumat Sistem -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Sistem</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Masjid</label>
                            <p class="text-xs text-gray-900">{{ $penerimaBantuan->masjid->nama ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Dicipta Oleh</label>
                            <p class="text-xs text-gray-900">{{ $penerimaBantuan->creator->name ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tarikh Dicipta</label>
                            <p class="text-xs text-gray-900">{{ $penerimaBantuan->created_at ? $penerimaBantuan->created_at->format('d/m/Y H:i') : '-' }}</p>
                        </div>

                        @if($penerimaBantuan->updated_at && $penerimaBantuan->updated_at != $penerimaBantuan->created_at)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Dikemaskini Oleh</label>
                            <p class="text-xs text-gray-900">{{ $penerimaBantuan->updater->name ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tarikh Dikemaskini</label>
                            <p class="text-xs text-gray-900">{{ $penerimaBantuan->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Section 9: Sejarah Permohonan -->
                @if($penerimaBantuan->permohonanBantuan && $penerimaBantuan->permohonanBantuan->count() > 0)
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Sejarah Permohonan Bantuan</h2>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-xs">
                            <thead class="bg-blue-100">
                                <tr>
                                    <th class="px-3 py-2 text-left">No. Permohonan</th>
                                    <th class="px-3 py-2 text-left">Program</th>
                                    <th class="px-3 py-2 text-left">Tarikh</th>
                                    <th class="px-3 py-2 text-right">Jumlah</th>
                                    <th class="px-3 py-2 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($penerimaBantuan->permohonanBantuan->take(5) as $permohonan)
                                <tr class="hover:bg-white">
                                    <td class="px-3 py-2">{{ $permohonan->no_permohonan }}</td>
                                    <td class="px-3 py-2">{{ $permohonan->programKebajikan->nama_program ?? '-' }}</td>
                                    <td class="px-3 py-2">{{ $permohonan->tarikh_permohonan ? \Carbon\Carbon::parse($permohonan->tarikh_permohonan)->format('d/m/Y') : '-' }}</td>
                                    <td class="px-3 py-2 text-right">{{ $permohonan->jumlah_dipohon ? 'RM ' . number_format($permohonan->jumlah_dipohon, 2) : '-' }}</td>
                                    <td class="px-3 py-2 text-center">
                                        @if($permohonan->status_permohonan === 'Lulus')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Lulus</span>
                                        @elseif($permohonan->status_permohonan === 'Ditolak')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Ditolak</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-blue-100 text-blue-800">{{ $permohonan->status_permohonan }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($penerimaBantuan->permohonanBantuan->count() > 5)
                    <p class="text-[10px] text-gray-500 mt-2">Menunjukkan 5 permohonan terkini. Jumlah keseluruhan: {{ $penerimaBantuan->permohonanBantuan->count() }} permohonan.</p>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
