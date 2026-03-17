<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Tugas - E-Masjid</title>
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
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Laporan Tugas</h1>
                        <p class="text-xs text-gray-600">Ringkasan jadual tugas penceramah, imam dan bilal</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        <button onclick="window.print()" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-gray-600 text-white text-xs rounded hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">print</span>
                            Cetak
                        </button>
                    </div>
                </div>
                <x-statistics-grid :stats="$stats" />
                <form method="GET" action="{{ route('laporan-tugas.index') }}" class="mb-4">
                    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
                        <div class="flex gap-2">
                            <input type="date" name="tarikh_mula" value="{{ request('tarikh_mula') }}" class="px-3 py-2 text-xs border border-gray-300 rounded-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <input type="date" name="tarikh_akhir" value="{{ request('tarikh_akhir') }}" class="px-3 py-2 text-xs border border-gray-300 rounded-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="flex gap-2">
                            <x-action-button type="submit" icon="search" color="blue">Cari</x-action-button>
                            <x-action-button type="button" icon="refresh" color="red" onclick="window.location.href='{{ route('laporan-tugas.index') }}'">Reset</x-action-button>
                        </div>
                    </div>
                </form>

                <!-- Ringkasan Ceramah -->
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Jadual Ceramah Terkini</h3>
                    <div class="overflow-x-auto bg-gray-50 rounded-xs border border-gray-200">
                        <table class="min-w-full text-left text-sm">
                            <thead class="bg-blue-100 text-gray-600">
                                <tr>
                                    <th class="px-4 py-2 table-header">Tarikh</th>
                                    <th class="px-4 py-2 table-header">Penceramah</th>
                                    <th class="px-4 py-2 table-header">Tajuk</th>
                                    <th class="px-4 py-2 table-header">Jenis</th>
                                    <th class="px-4 py-2 table-header text-right">Bayaran</th>
                                    <th class="px-4 py-2 table-header text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($ceramahList as $ceramah)
                                <tr class="hover:bg-white">
                                    <td class="px-4 py-2 table-data">{{ $ceramah->tarikh->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2 table-data text-gray-900">{{ $ceramah->penceramah->nama ?? '-' }}</td>
                                    <td class="px-4 py-2 table-data">{{ Str::limit($ceramah->tajuk_ceramah, 30) }}</td>
                                    <td class="px-4 py-2 table-data">{{ $ceramah->jenis_ceramah }}</td>
                                    <td class="px-4 py-2 table-data text-right">RM {{ number_format($ceramah->kadar_bayaran, 2) }}</td>
                                    <td class="px-4 py-2 table-data text-center">
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $ceramah->status === 'Selesai' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">{{ $ceramah->status }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="px-4 py-4 text-center text-gray-500 text-xs">Tiada data</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Ringkasan Imam & Bilal -->
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Jadual Imam & Bilal Terkini</h3>
                    <div class="overflow-x-auto bg-gray-50 rounded-xs border border-gray-200">
                        <table class="min-w-full text-left text-sm">
                            <thead class="bg-green-100 text-gray-600">
                                <tr>
                                    <th class="px-4 py-2 table-header">Tarikh</th>
                                    <th class="px-4 py-2 table-header">Waktu</th>
                                    <th class="px-4 py-2 table-header">Imam</th>
                                    <th class="px-4 py-2 table-header text-center">Status Imam</th>
                                    <th class="px-4 py-2 table-header">Bilal</th>
                                    <th class="px-4 py-2 table-header text-center">Status Bilal</th>
                                    <th class="px-4 py-2 table-header text-center">Jenis</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($imamBilalList as $jadual)
                                <tr class="hover:bg-white">
                                    <td class="px-4 py-2 table-data">
                                        <div class="table-data-important">{{ $jadual->tarikh->format('d/m/Y') }}</div>
                                        <div class="text-[10px] text-gray-500">{{ $jadual->tarikh->translatedFormat('l') }}</div>
                                    </td>
                                    <td class="px-4 py-2 table-data">
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-blue-100 text-blue-800">{{ $jadual->waktu_solat }}</span>
                                    </td>
                                    <td class="px-4 py-2 table-data text-gray-900">
                                        {{ $jadual->imam_display }}
                                        @if($jadual->imam_ganti)
                                            <div class="text-[10px] text-orange-600">Ganti: {{ $jadual->imam_ganti }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 table-data text-center">
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium 
                                            {{ $jadual->status_imam === 'Selesai' ? 'bg-green-100 text-green-800' : 
                                               ($jadual->status_imam === 'Batal' ? 'bg-red-100 text-red-800' : 
                                               ($jadual->status_imam === 'Ganti' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800')) }}">
                                            {{ $jadual->status_imam }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 table-data text-gray-900">
                                        {{ $jadual->bilal_display }}
                                        @if($jadual->bilal_ganti)
                                            <div class="text-[10px] text-orange-600">Ganti: {{ $jadual->bilal_ganti }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 table-data text-center">
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium 
                                            {{ $jadual->status_bilal === 'Selesai' ? 'bg-green-100 text-green-800' : 
                                               ($jadual->status_bilal === 'Batal' ? 'bg-red-100 text-red-800' : 
                                               ($jadual->status_bilal === 'Ganti' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800')) }}">
                                            {{ $jadual->status_bilal }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 table-data text-center">
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $jadual->jenis_jadual === 'Auto' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $jadual->jenis_jadual }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="7" class="px-4 py-4 text-center text-gray-500 text-xs">Tiada data</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <x-footer />
</body>
</html>
