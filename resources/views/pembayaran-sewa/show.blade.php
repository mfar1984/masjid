<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Butiran Pembayaran Sewa - E-Masjid</title>
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
                <div class="mb-6 flex justify-between items-start">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Butiran Pembayaran Sewa</h1>
                        <p class="text-xs text-gray-600">Maklumat lengkap pembayaran sewa fasiliti</p>
                    </div>
                    <div class="flex space-x-2">
                        @if(auth()->user()->hasPermission('pembayaran_sewa', 'update'))
                            <a href="{{ route('pembayaran-sewa.edit', $pembayaranSewa) }}" class="h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 inline-flex items-center">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">edit</span>
                                Kemaskini
                            </a>
                        @endif
                        <a href="{{ route('pembayaran-sewa.index') }}" class="h-[32px] px-4 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300 inline-flex items-center">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                            Kembali
                        </a>
                    </div>
                </div>

                <!-- Section 1: Maklumat Pembayaran -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Pembayaran</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">No. Pembayaran</label>
                            <p class="text-xs text-gray-900 font-semibold">{{ $pembayaranSewa->no_pembayaran }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Pembayaran</label>
                            <p class="text-xs text-gray-900">{{ $pembayaranSewa->tarikh_pembayaran->format('d/m/Y') }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Kaedah Bayaran</label>
                            <p class="text-xs text-gray-900">{{ $pembayaranSewa->kaedah_bayaran }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Status Pembayaran</label>
                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-[10px] font-medium
                                @if($pembayaranSewa->status_pembayaran == 'Sudah Bayar') bg-green-100 text-green-800
                                @elseif($pembayaranSewa->status_pembayaran == 'Belum Bayar') bg-orange-100 text-orange-800
                                @elseif($pembayaranSewa->status_pembayaran == 'Deposit Dikembalikan') bg-blue-100 text-blue-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ $pembayaranSewa->status_pembayaran }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah Sewa</label>
                            <p class="text-xs text-gray-900 font-semibold">RM {{ number_format($pembayaranSewa->jumlah_sewa, 2) }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah Deposit</label>
                            <p class="text-xs text-gray-900 font-semibold">RM {{ number_format($pembayaranSewa->jumlah_deposit, 2) }}</p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah Bayaran</label>
                            <p class="text-sm text-gray-900 font-bold">RM {{ number_format($pembayaranSewa->jumlah_bayaran, 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Maklumat Tempahan -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Tempahan</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">No. Tempahan</label>
                            <a href="{{ route('tempahan-fasiliti.show', $pembayaranSewa->tempahanFasiliti) }}" class="text-xs text-blue-600 hover:underline font-semibold">
                                {{ $pembayaranSewa->tempahanFasiliti->no_tempahan }}
                            </a>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Nama Penyewa</label>
                            <p class="text-xs text-gray-900">{{ $pembayaranSewa->tempahanFasiliti->nama_penyewa }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">No. Telefon</label>
                            <p class="text-xs text-gray-900">{{ $pembayaranSewa->tempahanFasiliti->no_telefon_penyewa }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Tempahan</label>
                            <p class="text-xs text-gray-900">{{ $pembayaranSewa->tempahanFasiliti->tarikh_mula->format('d/m/Y') }} - {{ $pembayaranSewa->tempahanFasiliti->tarikh_tamat->format('d/m/Y') }}</p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Tujuan Tempahan</label>
                            <p class="text-xs text-gray-900">{{ $pembayaranSewa->tempahanFasiliti->tujuan_tempahan }}</p>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Maklumat Fasiliti -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Fasiliti</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Nama Fasiliti</label>
                            <a href="{{ route('senarai-fasiliti.show', $pembayaranSewa->senariFasiliti) }}" class="text-xs text-blue-600 hover:underline font-semibold">
                                {{ $pembayaranSewa->senariFasiliti->nama_fasiliti }}
                            </a>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Jenis Fasiliti</label>
                            <p class="text-xs text-gray-900">{{ $pembayaranSewa->senariFasiliti->jenis_fasiliti }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Lokasi</label>
                            <p class="text-xs text-gray-900">{{ $pembayaranSewa->senariFasiliti->lokasi }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Kapasiti</label>
                            <p class="text-xs text-gray-900">{{ $pembayaranSewa->senariFasiliti->kapasiti }} orang</p>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Maklumat Bank/Cek (Conditional) -->
                @if(in_array($pembayaranSewa->kaedah_bayaran, ['Bank Transfer', 'Online Banking', 'Cek']))
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">
                        @if($pembayaranSewa->kaedah_bayaran == 'Cek')
                            Maklumat Cek
                        @else
                            Maklumat Bank
                        @endif
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @if($pembayaranSewa->kaedah_bayaran == 'Cek')
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">No. Cek</label>
                                <p class="text-xs text-gray-900">{{ $pembayaranSewa->no_cek ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Cek</label>
                                <p class="text-xs text-gray-900">{{ $pembayaranSewa->tarikh_cek ? $pembayaranSewa->tarikh_cek->format('d/m/Y') : '-' }}</p>
                            </div>
                        @else
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">No. Rujukan</label>
                                <p class="text-xs text-gray-900">{{ $pembayaranSewa->no_rujukan ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">No. Akaun</label>
                                <p class="text-xs text-gray-900">{{ $pembayaranSewa->no_akaun ?? '-' }}</p>
                            </div>
                        @endif
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Nama Bank</label>
                            <p class="text-xs text-gray-900">{{ $pembayaranSewa->nama_bank ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Section 5: Dokumen Pembayaran -->
                @if($pembayaranSewa->resit_pembayaran_path || $pembayaranSewa->bukti_transfer_path || $pembayaranSewa->salinan_cek_path)
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Dokumen Pembayaran</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @if($pembayaranSewa->resit_pembayaran_path)
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Resit Pembayaran</label>
                            <a href="{{ Storage::url($pembayaranSewa->resit_pembayaran_path) }}" target="_blank" class="inline-flex items-center text-xs text-blue-600 hover:underline">
                                <span class="material-icons mr-1" style="font-size: 16px !important;">description</span>
                                Lihat Dokumen
                            </a>
                        </div>
                        @endif

                        @if($pembayaranSewa->bukti_transfer_path)
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Bukti Transfer</label>
                            <a href="{{ Storage::url($pembayaranSewa->bukti_transfer_path) }}" target="_blank" class="inline-flex items-center text-xs text-blue-600 hover:underline">
                                <span class="material-icons mr-1" style="font-size: 16px !important;">description</span>
                                Lihat Dokumen
                            </a>
                        </div>
                        @endif

                        @if($pembayaranSewa->salinan_cek_path)
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Salinan Cek</label>
                            <a href="{{ Storage::url($pembayaranSewa->salinan_cek_path) }}" target="_blank" class="inline-flex items-center text-xs text-blue-600 hover:underline">
                                <span class="material-icons mr-1" style="font-size: 16px !important;">description</span>
                                Lihat Dokumen
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Section 6: Deposit Return (if applicable) -->
                @if($pembayaranSewa->deposit_dikembalikan > 0 || $pembayaranSewa->tarikh_kembalikan_deposit)
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Pulangan Deposit</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah Deposit Dikembalikan</label>
                            <p class="text-xs text-gray-900 font-semibold">RM {{ number_format($pembayaranSewa->deposit_dikembalikan ?? 0, 2) }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Kembalikan</label>
                            <p class="text-xs text-gray-900">{{ $pembayaranSewa->tarikh_kembalikan_deposit ? $pembayaranSewa->tarikh_kembalikan_deposit->format('d/m/Y') : '-' }}</p>
                        </div>

                        @if($pembayaranSewa->sebab_potongan_deposit)
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Sebab Potongan Deposit</label>
                            <p class="text-xs text-gray-900">{{ $pembayaranSewa->sebab_potongan_deposit }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Section 7: Status & Catatan -->
                @if($pembayaranSewa->catatan)
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Catatan</h2>
                    <p class="text-xs text-gray-900">{{ $pembayaranSewa->catatan }}</p>
                </div>
                @endif

                <!-- Section 8: Maklumat Audit -->
                <div class="bg-blue-50 rounded-lg p-4">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Audit</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Dicipta Oleh</label>
                            <p class="text-xs text-gray-900">{{ $pembayaranSewa->createdBy->name ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Dicipta</label>
                            <p class="text-xs text-gray-900">{{ $pembayaranSewa->created_at->format('d/m/Y H:i') }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Dikemaskini Oleh</label>
                            <p class="text-xs text-gray-900">{{ $pembayaranSewa->updatedBy->name ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Dikemaskini</label>
                            <p class="text-xs text-gray-900">{{ $pembayaranSewa->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
