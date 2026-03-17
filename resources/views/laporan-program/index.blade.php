<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Program - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Laporan Program</h1>
                        <p class="text-xs text-gray-600">Ringkasan program dan pendidikan masjid</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-xs font-medium rounded-md hover:bg-gray-700">
                            <span class="material-icons text-sm mr-1">print</span>Cetak
                        </button>
                    </div>
                </div>
                <x-statistics-grid :stats="$stats" />
                <form method="GET" action="{{ route('laporan-program.index') }}" class="mb-4">
                    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
                        <div class="flex gap-2">
                            <input type="date" name="tarikh_mula" value="{{ request('tarikh_mula') }}" class="px-3 py-2 text-xs border border-gray-300 rounded-md">
                            <input type="date" name="tarikh_akhir" value="{{ request('tarikh_akhir') }}" class="px-3 py-2 text-xs border border-gray-300 rounded-md">
                        </div>
                        <div class="flex gap-2">
                            <x-filter-dropdown name="jenis_program" :options="['Kuliah' => 'Kuliah', 'Ceramah' => 'Ceramah', 'Kursus' => 'Kursus', 'Bengkel' => 'Bengkel', 'Seminar' => 'Seminar']" :selected="request('jenis_program')" placeholder="Semua Jenis" />
                        </div>
                        <div class="flex gap-2">
                            <x-action-button type="submit" icon="search" color="blue">Cari</x-action-button>
                            <x-action-button type="button" icon="refresh" color="red" onclick="window.location.href='{{ route('laporan-program.index') }}'">Reset</x-action-button>
                        </div>
                    </div>
                </form>
                <!-- Senarai Program -->
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Senarai Program</h3>
                    <div class="overflow-x-auto bg-gray-50 rounded-xs border border-gray-200">
                        <table class="min-w-full text-left text-sm">
                            <thead class="bg-blue-100 text-gray-600">
                                <tr>
                                    <th class="px-4 py-2 table-header">Nama Program</th>
                                    <th class="px-4 py-2 table-header">Jenis</th>
                                    <th class="px-4 py-2 table-header">Kategori</th>
                                    <th class="px-4 py-2 table-header text-center">Peserta</th>
                                    <th class="px-4 py-2 table-header text-right">Yuran (RM)</th>
                                    <th class="px-4 py-2 table-header text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($programList as $program)
                                <tr class="hover:bg-white">
                                    <td class="px-4 py-2 table-data text-gray-900">{{ $program->nama_program }}</td>
                                    <td class="px-4 py-2 table-data">{{ $program->jenis_program }}</td>
                                    <td class="px-4 py-2 table-data">{{ $program->kategori }}</td>
                                    <td class="px-4 py-2 table-data text-center">{{ $program->peserta_count ?? 0 }}</td>
                                    <td class="px-4 py-2 table-data text-right">{{ number_format($program->yuran, 2) }}</td>
                                    <td class="px-4 py-2 table-data text-center">
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $program->status === 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">{{ $program->status }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="px-4 py-4 text-center text-gray-500 text-xs">Tiada data</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Statistik -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Statistik Mengikut Jenis</h3>
                        <dl class="space-y-3">
                            @foreach($programList->groupBy('jenis_program') as $jenis => $programs)
                            <div class="flex justify-between">
                                <dt class="text-xs text-gray-500">{{ $jenis }}</dt>
                                <dd class="text-xs font-medium text-gray-900">{{ $programs->count() }} program</dd>
                            </div>
                            @endforeach
                        </dl>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Statistik Mengikut Kategori</h3>
                        <dl class="space-y-3">
                            @foreach($programList->groupBy('kategori') as $kategori => $programs)
                            <div class="flex justify-between">
                                <dt class="text-xs text-gray-500">{{ $kategori }}</dt>
                                <dd class="text-xs font-medium text-gray-900">{{ $programs->count() }} program</dd>
                            </div>
                            @endforeach
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <x-footer />
</body>
</html>
