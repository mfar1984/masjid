<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Jadual Penyelenggaraan - E-Masjid</title>
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
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('jadual-penyelenggaraan.index') }}" class="text-blue-600 hover:text-blue-800">
                            <span class="material-icons" style="font-size: 20px !important;">arrow_back</span>
                        </a>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">{{ $jadualPenyelenggaraan->no_jadual }}</h1>
                            <p class="text-xs text-gray-600">{{ $jadualPenyelenggaraan->nama_jadual }}</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        @if(auth()->user()->hasPermission('jadual_penyelenggaraan', 'update'))
                            <a href="{{ route('jadual-penyelenggaraan.edit', $jadualPenyelenggaraan) }}" class="inline-flex items-center px-3 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">
                                <span class="material-icons mr-1" style="font-size: 14px !important;">edit</span>Edit
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Status Badge -->
                <div class="mb-6">
                    @if($jadualPenyelenggaraan->status === 'Aktif')
                        <span class="inline-flex items-center px-3 py-1 rounded text-sm font-medium bg-green-100 text-green-800">
                            <span class="material-icons mr-1" style="font-size: 16px !important;">check_circle</span>Aktif
                        </span>
                    @elseif($jadualPenyelenggaraan->status === 'Selesai')
                        <span class="inline-flex items-center px-3 py-1 rounded text-sm font-medium bg-purple-100 text-purple-800">
                            <span class="material-icons mr-1" style="font-size: 16px !important;">task_alt</span>Selesai
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded text-sm font-medium bg-gray-100 text-gray-800">
                            <span class="material-icons mr-1" style="font-size: 16px !important;">cancel</span>Tidak Aktif
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Maklumat Asas -->
                    <div class="bg-gray-50 rounded p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons mr-2 text-blue-600" style="font-size: 18px !important;">info</span>
                            Maklumat Asas
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">No. Jadual</span>
                                <span class="text-xs font-medium text-gray-900">{{ $jadualPenyelenggaraan->no_jadual }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">Jenis Item</span>
                                <span class="text-xs font-medium text-gray-900">{{ $jadualPenyelenggaraan->jenis_item }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">Item</span>
                                <span class="text-xs font-medium text-gray-900">{{ $jadualPenyelenggaraan->item_nama }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">Jenis Penyelenggaraan</span>
                                <span class="text-xs font-medium text-gray-900">{{ $jadualPenyelenggaraan->jenis_penyelenggaraan }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">Kekerapan</span>
                                <span class="text-xs font-medium text-gray-900">{{ $jadualPenyelenggaraan->kekerapan }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Maklumat Tarikh -->
                    <div class="bg-gray-50 rounded p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons mr-2 text-green-600" style="font-size: 18px !important;">calendar_month</span>
                            Maklumat Tarikh
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">Tarikh Mula</span>
                                <span class="text-xs font-medium text-gray-900">{{ $jadualPenyelenggaraan->tarikh_mula ? $jadualPenyelenggaraan->tarikh_mula->format('d/m/Y') : '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">Tarikh Akhir</span>
                                <span class="text-xs font-medium text-gray-900">{{ $jadualPenyelenggaraan->tarikh_akhir ? $jadualPenyelenggaraan->tarikh_akhir->format('d/m/Y') : '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">Penyelenggaraan Seterusnya</span>
                                <span class="text-xs font-medium text-blue-600">{{ $jadualPenyelenggaraan->tarikh_penyelenggaraan_seterusnya ? $jadualPenyelenggaraan->tarikh_penyelenggaraan_seterusnya->format('d/m/Y') : '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">Anggaran Kos</span>
                                <span class="text-xs font-medium text-gray-900">RM {{ number_format($jadualPenyelenggaraan->anggaran_kos ?? 0, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Maklumat Vendor -->
                    <div class="bg-gray-50 rounded p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons mr-2 text-purple-600" style="font-size: 18px !important;">business</span>
                            Maklumat Vendor
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">Nama Vendor</span>
                                <span class="text-xs font-medium text-gray-900">{{ $jadualPenyelenggaraan->vendor_nama ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">No. Telefon</span>
                                <span class="text-xs font-medium text-gray-900">{{ $jadualPenyelenggaraan->vendor_telefon ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Skop & Catatan -->
                    <div class="bg-gray-50 rounded p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons mr-2 text-orange-600" style="font-size: 18px !important;">description</span>
                            Skop & Catatan
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-xs text-gray-500 block mb-1">Skop Kerja</span>
                                <p class="text-xs text-gray-900">{{ $jadualPenyelenggaraan->skop_kerja ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 block mb-1">Catatan</span>
                                <p class="text-xs text-gray-900">{{ $jadualPenyelenggaraan->catatan ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Senarai Kerja Penyelenggaraan -->
                @if($jadualPenyelenggaraan->kerjaPenyelenggaraan->count() > 0)
                <div class="mt-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Senarai Kerja Penyelenggaraan</h3>
                    <div class="overflow-x-auto bg-gray-50 rounded border border-gray-200">
                        <table class="min-w-full text-left text-sm">
                            <thead class="bg-blue-100">
                                <tr>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-700">No. Kerja</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-700">Tarikh</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-700">Jenis</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-700">Kos</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-700">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($jadualPenyelenggaraan->kerjaPenyelenggaraan as $kerja)
                                <tr class="hover:bg-white">
                                    <td class="px-4 py-2 text-xs text-gray-900">{{ $kerja->no_kerja }}</td>
                                    <td class="px-4 py-2 text-xs text-gray-600">{{ $kerja->tarikh_kerja->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2 text-xs text-gray-600">{{ $kerja->jenis_kerja }}</td>
                                    <td class="px-4 py-2 text-xs text-gray-900">RM {{ number_format($kerja->kos, 2) }}</td>
                                    <td class="px-4 py-2 text-xs">
                                        @if($kerja->status === 'Selesai')
                                            <span class="px-2 py-1 rounded-sm text-xs bg-green-100 text-green-800">Selesai</span>
                                        @else
                                            <span class="px-2 py-1 rounded-sm text-xs bg-yellow-100 text-yellow-800">{{ $kerja->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <!-- Audit Info -->
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <div class="flex flex-wrap gap-4 text-xs text-gray-500">
                        <span>Dicipta oleh: {{ $jadualPenyelenggaraan->createdBy->name ?? '-' }} pada {{ $jadualPenyelenggaraan->created_at->format('d/m/Y H:i') }}</span>
                        @if($jadualPenyelenggaraan->updatedBy)
                            <span>Dikemaskini oleh: {{ $jadualPenyelenggaraan->updatedBy->name }} pada {{ $jadualPenyelenggaraan->updated_at->format('d/m/Y H:i') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
