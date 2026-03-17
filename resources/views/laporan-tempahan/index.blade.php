<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Tempahan - E-Masjid</title>
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
                    <h1 class="text-xl font-bold text-gray-900 mb-1">Laporan Tempahan Fasiliti</h1>
                    <p class="text-xs text-gray-600">Statistik dan laporan tempahan fasiliti</p>
                </div>

                <!-- Filters -->
                <form method="GET" action="{{ route('laporan-tempahan.index') }}" class="mb-6">
                    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari no tempahan, nama penyewa..." class="flex-1 px-3 py-2 border border-gray-300 rounded text-xs">

                        <select name="senarai_fasiliti_id" class="px-3 py-2 border border-gray-300 rounded text-xs">
                            <option value="">Semua Fasiliti</option>
                            @foreach($fasilitiList as $fasiliti)
                                <option value="{{ $fasiliti->id }}" {{ request('senarai_fasiliti_id') == $fasiliti->id ? 'selected' : '' }}>
                                    {{ $fasiliti->nama_fasiliti }}
                                </option>
                            @endforeach
                        </select>

                        <select name="status" class="px-3 py-2 border border-gray-300 rounded text-xs">
                            <option value="">Semua Status</option>
                            <option value="Baharu" {{ request('status') == 'Baharu' ? 'selected' : '' }}>Baharu</option>
                            <option value="Dalam Semakan" {{ request('status') == 'Dalam Semakan' ? 'selected' : '' }}>Dalam Semakan</option>
                            <option value="Lulus" {{ request('status') == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                            <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                            <option value="Dibatalkan" {{ request('status') == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                            <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>

                        <input type="date" name="tarikh_dari" value="{{ request('tarikh_dari') }}" class="px-3 py-2 border border-gray-300 rounded text-xs" placeholder="Tarikh Dari">

                        <input type="date" name="tarikh_hingga" value="{{ request('tarikh_hingga') }}" class="px-3 py-2 border border-gray-300 rounded text-xs" placeholder="Tarikh Hingga">

                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 whitespace-nowrap">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">search</span>
                            Cari
                        </button>
                        
                        <a href="{{ route('laporan-tempahan.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white text-xs rounded hover:bg-red-700 whitespace-nowrap">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">refresh</span>
                            Reset
                        </a>

                        <button type="button" onclick="window.print()" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-xs rounded hover:bg-green-700 whitespace-nowrap">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">print</span>
                            Print PDF
                        </button>

                        <a href="{{ route('laporan-tempahan.excel', request()->all()) }}" class="inline-flex items-center justify-center px-4 py-2 bg-teal-600 text-white text-xs rounded hover:bg-teal-700 whitespace-nowrap">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">download</span>
                            Export Excel
                        </a>
                    </div>
                </form>

                <!-- Stats Cards Row 1 -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-blue-50 border border-blue-200 rounded p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-blue-600 mb-1">Total Fasiliti</p>
                                <p class="text-xl font-bold text-blue-900">{{ $stats['total_fasiliti'] }}</p>
                            </div>
                            <span class="material-icons text-blue-600" style="font-size: 32px !important;">meeting_room</span>
                        </div>
                    </div>
                    <div class="bg-purple-50 border border-purple-200 rounded p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-purple-600 mb-1">Total Tempahan</p>
                                <p class="text-xl font-bold text-purple-900">{{ $stats['total_tempahan'] }}</p>
                            </div>
                            <span class="material-icons text-purple-600" style="font-size: 32px !important;">event</span>
                        </div>
                    </div>
                    <div class="bg-orange-50 border border-orange-200 rounded p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-orange-600 mb-1">Total Pembayaran</p>
                                <p class="text-xl font-bold" style="color: #7c2d12;">{{ $stats['total_pembayaran'] }}</p>
                            </div>
                            <span class="material-icons text-orange-600" style="font-size: 32px !important;">payments</span>
                        </div>
                    </div>
                    <div class="bg-teal-50 border border-teal-200 rounded p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-teal-600 mb-1">Jumlah Pendapatan</p>
                                <p class="text-xl font-bold" style="color: #134e4a;">RM {{ number_format($stats['jumlah_pendapatan'], 2) }}</p>
                            </div>
                            <span class="material-icons text-teal-600" style="font-size: 32px !important;">account_balance_wallet</span>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards Row 2 -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-green-50 border border-green-200 rounded p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-green-600 mb-1">Tempahan Lulus</p>
                                <p class="text-xl font-bold text-green-900">{{ $stats['tempahan_lulus'] }}</p>
                            </div>
                            <span class="material-icons text-green-600" style="font-size: 32px !important;">check_circle</span>
                        </div>
                    </div>
                    <div class="bg-red-50 border border-red-200 rounded p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-red-600 mb-1">Tempahan Ditolak</p>
                                <p class="text-xl font-bold text-red-900">{{ $stats['tempahan_ditolak'] }}</p>
                            </div>
                            <span class="material-icons text-red-600" style="font-size: 32px !important;">cancel</span>
                        </div>
                    </div>
                    <div class="bg-indigo-50 border border-indigo-200 rounded p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-indigo-600 mb-1">Tempahan Selesai</p>
                                <p class="text-xl font-bold text-indigo-900">{{ $stats['tempahan_selesai'] }}</p>
                            </div>
                            <span class="material-icons text-indigo-600" style="font-size: 32px !important;">done_all</span>
                        </div>
                    </div>
                    <div class="bg-yellow-50 border border-yellow-200 rounded p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-yellow-600 mb-1">Kadar Kelulusan</p>
                                <p class="text-xl font-bold" style="color: #713f12;">{{ number_format($stats['kadar_kelulusan'], 1) }}%</p>
                            </div>
                            <span class="material-icons text-yellow-600" style="font-size: 32px !important;">trending_up</span>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Chart 1: Tempahan Mengikut Status (Pie) -->
                    <div class="bg-blue-50 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Tempahan Mengikut Status</h3>
                        <div style="height: 250px; position: relative;">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>

                    <!-- Chart 2: Pembayaran Mengikut Kaedah (Bar) -->
                    <div class="bg-blue-50 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Pembayaran Mengikut Kaedah</h3>
                        <div style="height: 250px; position: relative;">
                            <canvas id="kaedahChart"></canvas>
                        </div>
                    </div>

                    <!-- Chart 3: Top 10 Fasiliti (Bar) -->
                    <div class="bg-blue-50 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Top 10 Fasiliti Paling Popular</h3>
                        <div style="height: 250px; position: relative;">
                            <canvas id="fasilitiChart"></canvas>
                        </div>
                    </div>

                    <!-- Chart 4: Trend Tempahan Bulanan (Line) -->
                    <div class="bg-blue-50 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Trend Tempahan Bulanan (12 Bulan)</h3>
                        <div style="height: 250px; position: relative;">
                            <canvas id="trendChart"></canvas>
                        </div>
                    </div>

                    <!-- Chart 5: Pendapatan Bulanan (Line) -->
                    <div class="bg-blue-50 rounded-lg p-4 md:col-span-2">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Pendapatan Bulanan (12 Bulan)</h3>
                        <div style="height: 250px; position: relative;">
                            <canvas id="pendapatanChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Table Section -->
                <div class="bg-blue-50 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Senarai Tempahan</h3>
                    
                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700">No. Tempahan</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700">Fasiliti</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700">Penyewa</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700">Tarikh</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700">Jumlah</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700">Status</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700">Pembayaran</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($tempahanList as $tempahan)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 py-2 text-xs text-gray-900">
                                        <a href="{{ route('tempahan-fasiliti.show', $tempahan) }}" class="text-blue-600 hover:underline">
                                            {{ $tempahan->no_tempahan }}
                                        </a>
                                    </td>
                                    <td class="px-3 py-2 text-xs text-gray-900">{{ $tempahan->senariFasiliti->nama_fasiliti }}</td>
                                    <td class="px-3 py-2 text-xs text-gray-900">{{ $tempahan->nama_penyewa }}</td>
                                    <td class="px-3 py-2 text-xs text-gray-900">{{ $tempahan->tarikh_mula->format('d/m/Y') }}</td>
                                    <td class="px-3 py-2 text-xs text-gray-900 font-semibold">RM {{ number_format($tempahan->jumlah_bayaran, 2) }}</td>
                                    <td class="px-3 py-2 text-xs">
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-[10px] font-medium
                                            @if($tempahan->status_tempahan == 'Lulus') bg-green-100 text-green-800
                                            @elseif($tempahan->status_tempahan == 'Baharu') bg-blue-100 text-blue-800
                                            @elseif($tempahan->status_tempahan == 'Dalam Semakan') bg-yellow-100 text-yellow-800
                                            @elseif($tempahan->status_tempahan == 'Ditolak') bg-red-100 text-red-800
                                            @elseif($tempahan->status_tempahan == 'Dibatalkan') bg-gray-100 text-gray-800
                                            @else bg-purple-100 text-purple-800
                                            @endif">
                                            {{ $tempahan->status_tempahan }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 text-xs">
                                        @if($tempahan->pembayaranSewa)
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-[10px] font-medium
                                                @if($tempahan->pembayaranSewa->status_pembayaran == 'Sudah Bayar') bg-green-100 text-green-800
                                                @else bg-orange-100 text-orange-800
                                                @endif">
                                                {{ $tempahan->pembayaranSewa->status_pembayaran }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-3 py-4 text-center text-xs text-gray-500">Tiada rekod dijumpai</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="md:hidden space-y-3">
                        @forelse($tempahanList as $tempahan)
                        <div class="bg-white border border-gray-200 rounded-lg p-3">
                            <div class="flex justify-between items-start mb-2">
                                <a href="{{ route('tempahan-fasiliti.show', $tempahan) }}" class="text-xs font-semibold text-blue-600 hover:underline">
                                    {{ $tempahan->no_tempahan }}
                                </a>
                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-[10px] font-medium
                                    @if($tempahan->status_tempahan == 'Lulus') bg-green-100 text-green-800
                                    @elseif($tempahan->status_tempahan == 'Baharu') bg-blue-100 text-blue-800
                                    @elseif($tempahan->status_tempahan == 'Dalam Semakan') bg-yellow-100 text-yellow-800
                                    @elseif($tempahan->status_tempahan == 'Ditolak') bg-red-100 text-red-800
                                    @elseif($tempahan->status_tempahan == 'Dibatalkan') bg-gray-100 text-gray-800
                                    @else bg-purple-100 text-purple-800
                                    @endif">
                                    {{ $tempahan->status_tempahan }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-900 mb-1"><span class="font-medium">Fasiliti:</span> {{ $tempahan->senariFasiliti->nama_fasiliti }}</p>
                            <p class="text-xs text-gray-900 mb-1"><span class="font-medium">Penyewa:</span> {{ $tempahan->nama_penyewa }}</p>
                            <p class="text-xs text-gray-900 mb-1"><span class="font-medium">Tarikh:</span> {{ $tempahan->tarikh_mula->format('d/m/Y') }}</p>
                            <p class="text-xs text-gray-900 mb-1"><span class="font-medium">Jumlah:</span> RM {{ number_format($tempahan->jumlah_bayaran, 2) }}</p>
                            @if($tempahan->pembayaranSewa)
                            <p class="text-xs text-gray-900"><span class="font-medium">Pembayaran:</span> 
                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-[10px] font-medium
                                    @if($tempahan->pembayaranSewa->status_pembayaran == 'Sudah Bayar') bg-green-100 text-green-800
                                    @else bg-orange-100 text-orange-800
                                    @endif">
                                    {{ $tempahan->pembayaranSewa->status_pembayaran }}
                                </span>
                            </p>
                            @endif
                        </div>
                        @empty
                        <p class="text-center text-xs text-gray-500 py-4">Tiada rekod dijumpai</p>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $tempahanList->links() }}
                    </div>
                </div>
            </div>
        </div>
    </main>

    <x-footer />

    <script>
        // Chart 1: Status Pie Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'pie',
            data: {
                labels: {!! json_encode($chartData['status_labels']) !!},
                datasets: [{
                    data: {!! json_encode($chartData['status_values']) !!},
                    backgroundColor: ['#3B82F6', '#EAB308', '#10B981', '#EF4444', '#6B7280', '#8B5CF6']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { font: { size: 10 } } }
                }
            }
        });

        // Chart 2: Kaedah Bayaran Bar Chart
        const kaedahCtx = document.getElementById('kaedahChart').getContext('2d');
        new Chart(kaedahCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartData['kaedah_labels']) !!},
                datasets: [{
                    label: 'Jumlah Pembayaran',
                    data: {!! json_encode($chartData['kaedah_values']) !!},
                    backgroundColor: '#10B981'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Chart 3: Top Fasiliti Bar Chart
        const fasilitiCtx = document.getElementById('fasilitiChart').getContext('2d');
        new Chart(fasilitiCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartData['fasiliti_labels']) !!},
                datasets: [{
                    label: 'Jumlah Tempahan',
                    data: {!! json_encode($chartData['fasiliti_values']) !!},
                    backgroundColor: '#8B5CF6'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: { beginAtZero: true }
                }
            }
        });

        // Chart 4: Trend Tempahan Line Chart
        const trendCtx = document.getElementById('trendChart').getContext('2d');
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData['trend_labels']) !!},
                datasets: [{
                    label: 'Jumlah Tempahan',
                    data: {!! json_encode($chartData['trend_values']) !!},
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Chart 5: Pendapatan Line Chart
        const pendapatanCtx = document.getElementById('pendapatanChart').getContext('2d');
        new Chart(pendapatanCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData['pendapatan_labels']) !!},
                datasets: [{
                    label: 'Pendapatan (RM)',
                    data: {!! json_encode($chartData['pendapatan_values']) !!},
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
</body>
</html>
