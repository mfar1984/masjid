<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trend Penyusutan - E-Masjid</title>
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
                <div class="mb-6">
                    <h1 class="text-xl font-bold text-gray-900 mb-1">Trend Penyusutan</h1>
                    <p class="text-xs text-gray-600">Laporan trend penyusutan nilai aset mengikut tahun dan kategori</p>
                </div>

                <x-statistics-grid :stats="$stats" />

                <!-- Trend by Year -->
                <div class="mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-3">Trend Penyusutan Mengikut Tahun</h2>
                    <div class="overflow-x-auto bg-gray-50 rounded-xs border border-gray-200">
                        <table class="min-w-full text-left text-sm">
                            <thead class="bg-blue-100 text-gray-600">
                                <tr>
                                    <th class="px-4 py-2 table-header">Tahun</th>
                                    <th class="px-4 py-2 table-header text-right">Nilai Asal (RM)</th>
                                    <th class="px-4 py-2 table-header text-right">Nilai Semasa (RM)</th>
                                    <th class="px-4 py-2 table-header text-right">Susut Nilai (RM)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($trendData as $data)
                                    <tr class="hover:bg-white">
                                        <td class="px-4 py-2 table-data font-semibold text-gray-900">{{ $data['year'] }}</td>
                                        <td class="px-4 py-2 table-data text-right text-gray-600">{{ number_format($data['nilai_asal'], 2) }}</td>
                                        <td class="px-4 py-2 table-data text-right text-green-600 font-semibold">{{ number_format($data['nilai_semasa'], 2) }}</td>
                                        <td class="px-4 py-2 table-data text-right text-red-600">{{ number_format($data['susut_nilai'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Trend by Kategori -->
                <div>
                    <h2 class="text-sm font-semibold text-gray-900 mb-3">Penyusutan Mengikut Kategori</h2>
                    <div class="overflow-x-auto bg-gray-50 rounded-xs border border-gray-200">
                        <table class="min-w-full text-left text-sm">
                            <thead class="bg-purple-100 text-gray-600">
                                <tr>
                                    <th class="px-4 py-2 table-header">Kategori</th>
                                    <th class="px-4 py-2 table-header text-center">Jumlah Aset</th>
                                    <th class="px-4 py-2 table-header text-right">Nilai Asal (RM)</th>
                                    <th class="px-4 py-2 table-header text-right">Nilai Semasa (RM)</th>
                                    <th class="px-4 py-2 table-header text-right">Susut Nilai (RM)</th>
                                    <th class="px-4 py-2 table-header text-center">Kadar (%)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($trendByKategori as $data)
                                    <tr class="hover:bg-white">
                                        <td class="px-4 py-2 table-data font-semibold text-gray-900">{{ $data['kategori'] }}</td>
                                        <td class="px-4 py-2 table-data text-center text-gray-600">{{ $data['jumlah_aset'] }}</td>
                                        <td class="px-4 py-2 table-data text-right text-gray-600">{{ number_format($data['nilai_asal'], 2) }}</td>
                                        <td class="px-4 py-2 table-data text-right text-green-600 font-semibold">{{ number_format($data['nilai_semasa'], 2) }}</td>
                                        <td class="px-4 py-2 table-data text-right text-red-600">{{ number_format($data['susut_nilai'], 2) }}</td>
                                        <td class="px-4 py-2 table-data text-center">
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-purple-100 text-purple-800">
                                                {{ $data['kadar_susut'] }}%
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                            <span class="material-icons mb-2" style="font-size: 48px !important;">trending_down</span>
                                            <p class="text-sm">Tiada data penyusutan dijumpai</p>
                                        </td>
                                    </tr>
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
