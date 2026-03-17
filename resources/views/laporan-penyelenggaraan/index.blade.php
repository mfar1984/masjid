<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penyelenggaraan - E-Masjid</title>
    <x-favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col" style="font-family: 'Poppins', sans-serif;">
    <x-double-navbar :user="auth()->user()" />

    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <!-- Header -->
                <div class="mb-6">
                    <h1 class="text-xl font-bold text-gray-900 mb-1">Laporan Penyelenggaraan</h1>
                    <p class="text-xs text-gray-600">Statistik dan laporan penyelenggaraan aset & fasiliti</p>
                </div>

                <!-- Filters -->
                <form method="GET" action="{{ route('laporan-penyelenggaraan.index') }}" class="mb-6">
                    <div class="flex flex-wrap gap-3">
                        @if(auth()->user()->hasRole('Super Admin'))
                        <select name="masjid_id" class="flex-1 min-w-[200px] px-3 py-2 border border-gray-300 rounded text-xs">
                            <option value="">Pilih Masjid</option>
                            @foreach($masjids as $masjid)
                                <option value="{{ $masjid->id }}" {{ $masjidId == $masjid->id ? 'selected' : '' }}>{{ $masjid->nama }}</option>
                            @endforeach
                        </select>
                        @endif
                        <select name="tahun" class="px-3 py-2 border border-gray-300 rounded text-xs">
                            @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                            <span class="material-icons mr-1" style="font-size: 14px !important;">search</span>Cari
                        </button>
                    </div>
                </form>

                <!-- Summary Cards -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-blue-50 border border-blue-200 rounded p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-blue-600 mb-1">Jumlah Jadual</p>
                                <p class="text-xl font-bold text-blue-900">{{ number_format($totalJadual) }}</p>
                                <p class="text-[10px] text-blue-600">{{ $jadualAktif }} aktif</p>
                            </div>
                            <span class="material-icons text-blue-600" style="font-size: 32px !important;">calendar_month</span>
                        </div>
                    </div>
                    <div class="bg-green-50 border border-green-200 rounded p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-green-600 mb-1">Kerja Selesai</p>
                                <p class="text-xl font-bold text-green-900">{{ number_format($kerjaSelesai) }}</p>
                                <p class="text-[10px] text-green-600">dari {{ $totalKerja }} kerja</p>
                            </div>
                            <span class="material-icons text-green-600" style="font-size: 32px !important;">check_circle</span>
                        </div>
                    </div>
                    <div class="bg-orange-50 border border-orange-200 rounded p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-orange-600 mb-1">Kerja Dirancang</p>
                                <p class="text-xl font-bold text-orange-900">{{ number_format($kerjaDirancang) }}</p>
                            </div>
                            <span class="material-icons text-orange-600" style="font-size: 32px !important;">schedule</span>
                        </div>
                    </div>
                    <div class="bg-purple-50 border border-purple-200 rounded p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-purple-600 mb-1">Jumlah Kos</p>
                                <p class="text-xl font-bold text-purple-900">RM {{ number_format($jumlahKos, 2) }}</p>
                            </div>
                            <span class="material-icons text-purple-600" style="font-size: 32px !important;">payments</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Chart: Kerja by Jenis -->
                    <div class="bg-white border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Kerja Mengikut Jenis</h3>
                        <div style="height: 250px;">
                            <canvas id="jenisChart"></canvas>
                        </div>
                    </div>

                    <!-- Chart: Kos by Bulan -->
                    <div class="bg-white border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Kos Penyelenggaraan Bulanan ({{ $tahun }})</h3>
                        <div style="height: 250px;">
                            <canvas id="kosChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Item Paling Kerap Diselenggara -->
                    <div class="bg-white border border-gray-200 rounded overflow-hidden">
                        <div class="px-4 py-3 bg-gray-50 border-b">
                            <h3 class="text-sm font-semibold text-gray-900">Item Paling Kerap Diselenggara</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-sm">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Item</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Jenis</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700 text-center">Kali</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700 text-right">Kos (RM)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($itemKerap as $item)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 text-xs text-gray-900">
                                            {{ $item->jenis_item === 'Aset' ? ($item->senariAset->nama_aset ?? '-') : ($item->senariFasiliti->nama_fasiliti ?? '-') }}
                                        </td>
                                        <td class="px-4 py-2 text-xs text-gray-600">{{ $item->jenis_item }}</td>
                                        <td class="px-4 py-2 text-xs text-gray-900 text-center">{{ $item->total }}</td>
                                        <td class="px-4 py-2 text-xs text-gray-900 text-right">{{ number_format($item->jumlah_kos, 2) }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-4 text-center text-gray-500 text-xs">Tiada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Jadual Akan Datang -->
                    <div class="bg-white border border-gray-200 rounded overflow-hidden">
                        <div class="px-4 py-3 bg-gray-50 border-b">
                            <h3 class="text-sm font-semibold text-gray-900">Jadual Penyelenggaraan Akan Datang</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-sm">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Jadual</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Item</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Tarikh</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($jadualAkanDatang as $jadual)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 text-xs text-gray-900">{{ $jadual->nama_jadual }}</td>
                                        <td class="px-4 py-2 text-xs text-gray-600">{{ $jadual->item_nama }}</td>
                                        <td class="px-4 py-2 text-xs text-blue-600">{{ $jadual->tarikh_penyelenggaraan_seterusnya->format('d/m/Y') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-4 text-center text-gray-500 text-xs">Tiada jadual akan datang</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Kerja Terkini -->
                <div class="mt-6 bg-white border border-gray-200 rounded overflow-hidden">
                    <div class="px-4 py-3 bg-gray-50 border-b">
                        <h3 class="text-sm font-semibold text-gray-900">Kerja Penyelenggaraan Terkini</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-700">No. Kerja</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-700">Item</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-700">Tarikh</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-700">Jenis</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-700 text-right">Kos</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-700">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($kerjaTerkini as $kerja)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 text-xs text-gray-900">{{ $kerja->no_kerja }}</td>
                                    <td class="px-4 py-2 text-xs text-gray-600">{{ $kerja->item_nama }}</td>
                                    <td class="px-4 py-2 text-xs text-gray-600">{{ $kerja->tarikh_kerja->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2 text-xs text-gray-600">{{ $kerja->jenis_kerja }}</td>
                                    <td class="px-4 py-2 text-xs text-gray-900 text-right">RM {{ number_format($kerja->kos, 2) }}</td>
                                    <td class="px-4 py-2 text-xs">
                                        @if($kerja->status === 'Selesai')
                                            <span class="px-2 py-1 rounded-sm bg-green-100 text-green-800">Selesai</span>
                                        @else
                                            <span class="px-2 py-1 rounded-sm bg-yellow-100 text-yellow-800">{{ $kerja->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-4 text-center text-gray-500 text-xs">Tiada kerja terkini</td>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Jenis Chart
            const jenisData = @json($kerjaByJenis);
            new Chart(document.getElementById('jenisChart'), {
                type: 'doughnut',
                data: {
                    labels: jenisData.map(item => item.jenis_kerja),
                    datasets: [{
                        data: jenisData.map(item => item.total),
                        backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'right', labels: { font: { size: 10 } } } }
                }
            });

            // Kos Chart
            const kosData = @json($kosByBulan);
            const bulanLabels = ['Jan', 'Feb', 'Mac', 'Apr', 'Mei', 'Jun', 'Jul', 'Ogo', 'Sep', 'Okt', 'Nov', 'Dis'];
            const kosValues = Array(12).fill(0);
            kosData.forEach(item => { kosValues[item.bulan - 1] = item.jumlah_kos; });

            new Chart(document.getElementById('kosChart'), {
                type: 'bar',
                data: {
                    labels: bulanLabels,
                    datasets: [{
                        label: 'Kos (RM)',
                        data: kosValues,
                        backgroundColor: '#8B5CF6',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        });
    </script>
</body>
</html>
