<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Fasiliti - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Butiran Fasiliti</h1>
                        <p class="text-xs text-gray-600">{{ $senariFasiliti->kod_fasiliti }} - {{ $senariFasiliti->nama_fasiliti }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('senarai-fasiliti.index') }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                            Kembali
                        </a>
                        @if(auth()->user()->hasPermission('senarai_fasiliti', 'update'))
                            <a href="{{ route('senarai-fasiliti.edit', ['senarai_fasiliti' => $senariFasiliti->id]) }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">edit</span>
                                Edit
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Section 1: Maklumat Fasiliti -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Fasiliti</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Kod Fasiliti</label>
                            <p class="text-xs text-gray-900 font-semibold">{{ $senariFasiliti->kod_fasiliti }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Nama Fasiliti</label>
                            <p class="text-xs text-gray-900 font-semibold">{{ $senariFasiliti->nama_fasiliti }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Jenis Fasiliti</label>
                            <p class="text-xs text-gray-900">{{ $senariFasiliti->jenis_fasiliti }}</p>
                        </div>

                        @if($senariFasiliti->kategori_fasiliti)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Kategori Fasiliti</label>
                            <p class="text-xs text-gray-900">{{ $senariFasiliti->kategori_fasiliti }}</p>
                        </div>
                        @endif

                        @if($senariFasiliti->jenis_fasiliti === 'Aset' && $senariFasiliti->senariAset)
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Aset Berkaitan</label>
                            <a href="{{ route('senarai-aset.show', $senariFasiliti->senariAset->id) }}" class="text-xs text-blue-600 hover:underline">
                                {{ $senariFasiliti->senariAset->no_aset }} - {{ $senariFasiliti->senariAset->nama_aset }}
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Section 2: Kapasiti & Spesifikasi -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Kapasiti & Spesifikasi</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($senariFasiliti->kapasiti_maksimum)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Kapasiti Maksimum</label>
                            <p class="text-xs text-gray-900">{{ $senariFasiliti->kapasiti_maksimum }} orang</p>
                        </div>
                        @endif

                        @if($senariFasiliti->luas_kawasan)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Luas Kawasan</label>
                            <p class="text-xs text-gray-900">{{ $senariFasiliti->luas_kawasan }}</p>
                        </div>
                        @endif

                        @if($senariFasiliti->kemudahan)
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Kemudahan</label>
                            <p class="text-xs text-gray-900">{{ $senariFasiliti->kemudahan }}</p>
                        </div>
                        @endif

                        @if($senariFasiliti->spesifikasi)
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Spesifikasi</label>
                            <p class="text-xs text-gray-900">{{ $senariFasiliti->spesifikasi }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Section 3: Harga Sewa -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Harga Sewa</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($senariFasiliti->harga_sewa_sejam)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Harga Sewa Sejam</label>
                            <p class="text-xs text-gray-900 font-semibold">RM {{ number_format($senariFasiliti->harga_sewa_sejam, 2) }}</p>
                        </div>
                        @endif

                        @if($senariFasiliti->harga_sewa_separuh_hari)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Harga Sewa Separuh Hari</label>
                            <p class="text-xs text-gray-900 font-semibold">RM {{ number_format($senariFasiliti->harga_sewa_separuh_hari, 2) }}</p>
                        </div>
                        @endif

                        @if($senariFasiliti->harga_sewa_sehari)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Harga Sewa Sehari</label>
                            <p class="text-xs text-gray-900 font-semibold">RM {{ number_format($senariFasiliti->harga_sewa_sehari, 2) }}</p>
                        </div>
                        @endif

                        @if($senariFasiliti->deposit_diperlukan)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Deposit Diperlukan</label>
                            <p class="text-xs text-gray-900 font-semibold">RM {{ number_format($senariFasiliti->deposit_diperlukan, 2) }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Section 4: Syarat & Peraturan -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Syarat & Peraturan</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($senariFasiliti->had_minimum_tempahan)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Had Minimum Tempahan</label>
                            <p class="text-xs text-gray-900">{{ $senariFasiliti->had_minimum_tempahan }} unit</p>
                        </div>
                        @endif

                        @if($senariFasiliti->had_maksimum_tempahan)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Had Maksimum Tempahan</label>
                            <p class="text-xs text-gray-900">{{ $senariFasiliti->had_maksimum_tempahan }} unit</p>
                        </div>
                        @endif

                        @if($senariFasiliti->syarat_tempahan)
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Syarat Tempahan</label>
                            <p class="text-xs text-gray-900 whitespace-pre-line">{{ $senariFasiliti->syarat_tempahan }}</p>
                        </div>
                        @endif

                        @if($senariFasiliti->peraturan_penggunaan)
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Peraturan Penggunaan</label>
                            <p class="text-xs text-gray-900 whitespace-pre-line">{{ $senariFasiliti->peraturan_penggunaan }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Section 5: Gambar & Dokumen -->
                @if($senariFasiliti->gambar_fasiliti || $senariFasiliti->dokumen_peraturan)
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Gambar & Dokumen</h2>
                    
                    <div class="grid grid-cols-1 gap-4">
                        @if($senariFasiliti->gambar_fasiliti)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-2">Gambar Fasiliti</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                @php
                                    $gambarArray = is_array($senariFasiliti->gambar_fasiliti) 
                                        ? $senariFasiliti->gambar_fasiliti 
                                        : json_decode($senariFasiliti->gambar_fasiliti, true) ?? [];
                                @endphp
                                @foreach($gambarArray as $gambar)
                                    <a href="{{ Storage::url($gambar) }}" target="_blank" class="block">
                                        <img src="{{ Storage::url($gambar) }}" alt="Gambar Fasiliti" class="w-full h-24 object-cover rounded-sm border border-gray-300">
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if($senariFasiliti->dokumen_peraturan)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-2">Dokumen Peraturan</label>
                            <a href="{{ Storage::url($senariFasiliti->dokumen_peraturan) }}" target="_blank" class="inline-flex items-center text-xs text-blue-600 hover:underline">
                                <span class="material-icons mr-1" style="font-size: 16px !important;">description</span>
                                Lihat Dokumen
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Section 6: Status & Catatan -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Status & Catatan</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Status Fasiliti</label>
                            <p class="text-xs">
                                @if($senariFasiliti->status_fasiliti === 'Tersedia')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Tersedia</span>
                                @elseif($senariFasiliti->status_fasiliti === 'Tidak Tersedia')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Tidak Tersedia</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">{{ $senariFasiliti->status_fasiliti }}</span>
                                @endif
                            </p>
                        </div>

                        @if($senariFasiliti->catatan)
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Catatan</label>
                            <p class="text-xs text-gray-900">{{ $senariFasiliti->catatan }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Section 7: Sejarah Tempahan -->
                @if($senariFasiliti->tempahanFasiliti && $senariFasiliti->tempahanFasiliti->count() > 0)
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Sejarah Tempahan</h2>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-xs">
                            <thead class="bg-blue-100">
                                <tr>
                                    <th class="px-3 py-2 text-left">No. Tempahan</th>
                                    <th class="px-3 py-2 text-left">Tarikh</th>
                                    <th class="px-3 py-2 text-left">Penyewa</th>
                                    <th class="px-3 py-2 text-left">Tarikh Mula - Tamat</th>
                                    <th class="px-3 py-2 text-right">Jumlah</th>
                                    <th class="px-3 py-2 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($senariFasiliti->tempahanFasiliti->take(5) as $tempahan)
                                <tr class="hover:bg-white">
                                    <td class="px-3 py-2">
                                        <a href="{{ route('tempahan-fasiliti.show', $tempahan->id) }}" class="text-blue-600 hover:underline">
                                            {{ $tempahan->no_tempahan }}
                                        </a>
                                    </td>
                                    <td class="px-3 py-2">{{ $tempahan->tarikh_tempahan ? \Carbon\Carbon::parse($tempahan->tarikh_tempahan)->format('d/m/Y') : '-' }}</td>
                                    <td class="px-3 py-2">{{ $tempahan->nama_penyewa }}</td>
                                    <td class="px-3 py-2">
                                        {{ $tempahan->tarikh_mula ? \Carbon\Carbon::parse($tempahan->tarikh_mula)->format('d/m/Y') : '-' }} - 
                                        {{ $tempahan->tarikh_tamat ? \Carbon\Carbon::parse($tempahan->tarikh_tamat)->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="px-3 py-2 text-right">RM {{ number_format($tempahan->jumlah_bayaran, 2) }}</td>
                                    <td class="px-3 py-2 text-center">
                                        @if($tempahan->status_tempahan === 'Lulus')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Lulus</span>
                                        @elseif($tempahan->status_tempahan === 'Baharu')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-blue-100 text-blue-800">Baharu</span>
                                        @elseif($tempahan->status_tempahan === 'Dalam Semakan')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-yellow-100 text-yellow-800">Dalam Semakan</span>
                                        @elseif($tempahan->status_tempahan === 'Ditolak')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Ditolak</span>
                                        @elseif($tempahan->status_tempahan === 'Selesai')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-gray-100 text-gray-800">Selesai</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">{{ $tempahan->status_tempahan }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($senariFasiliti->tempahanFasiliti->count() > 5)
                    <p class="text-[10px] text-gray-500 mt-2">Menunjukkan 5 tempahan terkini. Jumlah keseluruhan: {{ $senariFasiliti->tempahanFasiliti->count() }} tempahan.</p>
                    @endif
                </div>
                @endif

                <!-- Section 8: Maklumat Sistem -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Sistem</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Masjid</label>
                            <p class="text-xs text-gray-900">{{ $senariFasiliti->masjid->nama ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Dicipta Oleh</label>
                            <p class="text-xs text-gray-900">{{ $senariFasiliti->createdBy->name ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tarikh Dicipta</label>
                            <p class="text-xs text-gray-900">{{ $senariFasiliti->created_at ? $senariFasiliti->created_at->format('d/m/Y H:i') : '-' }}</p>
                        </div>

                        @if($senariFasiliti->updated_at && $senariFasiliti->updated_at != $senariFasiliti->created_at)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Dikemaskini Oleh</label>
                            <p class="text-xs text-gray-900">{{ $senariFasiliti->updatedBy->name ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tarikh Dikemaskini</label>
                            <p class="text-xs text-gray-900">{{ $senariFasiliti->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
