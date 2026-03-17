<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kebajikan - E-Masjid</title>
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
                    <h1 class="text-xl font-bold text-gray-900 mb-1">Laporan Kebajikan</h1>
                    <p class="text-xs text-gray-600">Statistik dan laporan program kebajikan</p>
                </div>

                <!-- Filters -->
                <form method="GET" action="{{ route('laporan-kebajikan.index') }}" class="mb-6">
                    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari no permohonan, nama penerima..." class="flex-1 px-3 py-2 border border-gray-300 rounded text-xs">

                        <select name="program_kebajikan_id" class="px-3 py-2 border border-gray-300 rounded text-xs">
                            <option value="">Semua Program</option>
                            @foreach($programs as $program)
                                <option value="{{ $program->id }}" {{ request('program_kebajikan_id') == $program->id ? 'selected' : '' }}>
                                    {{ $program->nama_program }}
                                </option>
                            @endforeach
                        </select>

                        <select name="kategori_program" class="px-3 py-2 border border-gray-300 rounded text-xs">
                            <option value="">Semua Kategori</option>
                            <option value="Pendidikan" {{ request('kategori_program') == 'Pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                            <option value="Kesihatan" {{ request('kategori_program') == 'Kesihatan' ? 'selected' : '' }}>Kesihatan</option>
                            <option value="Kecemasan" {{ request('kategori_program') == 'Kecemasan' ? 'selected' : '' }}>Kecemasan</option>
                            <option value="Kebajikan Am" {{ request('kategori_program') == 'Kebajikan Am' ? 'selected' : '' }}>Kebajikan Am</option>
                            <option value="Anak Yatim" {{ request('kategori_program') == 'Anak Yatim' ? 'selected' : '' }}>Anak Yatim</option>
                            <option value="OKU" {{ request('kategori_program') == 'OKU' ? 'selected' : '' }}>OKU</option>
                            <option value="Warga Emas" {{ request('kategori_program') == 'Warga Emas' ? 'selected' : '' }}>Warga Emas</option>
                            <option value="Ibu Tunggal" {{ request('kategori_program') == 'Ibu Tunggal' ? 'selected' : '' }}>Ibu Tunggal</option>
                        </select>

                        <select name="status" class="px-3 py-2 border border-gray-300 rounded text-xs">
                            <option value="">Semua Status</option>
                            <option value="Baharu" {{ request('status') == 'Baharu' ? 'selected' : '' }}>Baharu</option>
                            <option value="Dalam Semakan" {{ request('status') == 'Dalam Semakan' ? 'selected' : '' }}>Dalam Semakan</option>
                            <option value="Lawatan Rumah" {{ request('status') == 'Lawatan Rumah' ? 'selected' : '' }}>Lawatan Rumah</option>
                            <option value="Lulus" {{ request('status') == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                            <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>

                        <input type="date" name="tarikh_dari" value="{{ request('tarikh_dari') }}" class="px-3 py-2 border border-gray-300 rounded text-xs" placeholder="Tarikh Dari">

                        <input type="date" name="tarikh_hingga" value="{{ request('tarikh_hingga') }}" class="px-3 py-2 border border-gray-300 rounded text-xs" placeholder="Tarikh Hingga">

                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 whitespace-nowrap">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">search</span>
                            Cari
                        </button>
                        
                        <a href="{{ route('laporan-kebajikan.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white text-xs rounded hover:bg-red-700 whitespace-nowrap">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">refresh</span>
                            Reset
                        </a>
                    </div>
                </form>

                <!-- Stats Cards Row 1 -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-blue-50 border border-blue-200 rounded p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-blue-600 mb-1">Total Program</p>
                                <p class="text-xl font-bold text-blue-900">{{ $stats['total_program'] }}</p>
                            </div>
                            <span class="material-icons text-blue-600" style="font-size: 32px !important;">category</span>
                        </div>
                    </div>
                    <div class="bg-green-50 border border-green-200 rounded p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-green-600 mb-1">Total Penerima</p>
                                <p class="text-xl font-bold text-green-900">{{ $stats['total_penerima'] }}</p>
                            </div>
                            <span class="material-icons text-green-600" style="font-size: 32px !important;">people</span>
                        </div>
                    </div>
                    <div class="bg-purple-50 border border-purple-200 rounded p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-purple-600 mb-1">Total Permohonan</p>
                                <p class="text-xl font-bold text-purple-900">{{ $stats['total_permohonan'] }}</p>
                            </div>
                            <span class="material-icons text-purple-600" style="font-size: 32px !important;">description</span>
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
                </div>

                <!-- Stats Cards Row 2 -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-green-50 border border-green-200 rounded p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-green-600 mb-1">Permohonan Lulus</p>
                                <p class="text-xl font-bold text-green-900">{{ $stats['permohonan_lulus'] }}</p>
                            </div>
                            <span class="material-icons text-green-600" style="font-size: 32px !important;">check_circle</span>
                        </div>
                    </div>
                    <div class="bg-red-50 border border-red-200 rounded p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-red-600 mb-1">Permohonan Ditolak</p>
                                <p class="text-xl font-bold text-red-900">{{ $stats['permohonan_ditolak'] }}</p>
                            </div>
                            <span class="material-icons text-red-600" style="font-size: 32px !important;">cancel</span>
                        </div>
                    </div>
                    <div class="bg-teal-50 border border-teal-200 rounded p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-teal-600 mb-1">Jumlah Dibayar</p>
                                <p class="text-xl font-bold" style="color: #134e4a;">RM {{ number_format($stats['jumlah_dibayar'], 2) }}</p>
                            </div>
                            <span class="material-icons text-teal-600" style="font-size: 32px !important;">account_balance_wallet</span>
                        </div>
                    </div>
                    <div class="bg-yellow-50 border border-yellow-200 rounded p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-yellow-600 mb-1">Jumlah Belum Bayar</p>
                                <p class="text-xl font-bold" style="color: #713f12;">RM {{ number_format($stats['jumlah_belum_bayar'], 2) }}</p>
                            </div>
                            <span class="material-icons text-yellow-600" style="font-size: 32px !important;">pending</span>
                        </div>
                    </div>
                </div>

                <!-- Charts -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Permohonan by Status -->
                    <div class="bg-white border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons mr-2 text-blue-600" style="font-size: 18px !important;">pie_chart</span>
                            Permohonan Mengikut Status
                        </h3>
                        <div class="flex items-center justify-center" style="height: 300px;">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>

                    <!-- Pembayaran by Kaedah -->
                    <div class="bg-white border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons mr-2 text-green-600" style="font-size: 18px !important;">bar_chart</span>
                            Pembayaran Mengikut Kaedah
                        </h3>
                        <div class="flex items-center justify-center" style="height: 300px;">
                            <canvas id="kaedahChart"></canvas>
                        </div>
                    </div>

                    <!-- Permohonan by Program -->
                    <div class="bg-white border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons mr-2 text-purple-600" style="font-size: 18px !important;">bar_chart</span>
                            Permohonan Mengikut Program (Top 10)
                        </h3>
                        <div class="flex items-center justify-center" style="height: 300px;">
                            <canvas id="programChart"></canvas>
                        </div>
                    </div>

                    <!-- Trend Bulanan -->
                    <div class="bg-white border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons mr-2 text-orange-600" style="font-size: 18px !important;">show_chart</span>
                            Trend Permohonan Bulanan
                        </h3>
                        <div class="flex items-center justify-center" style="height: 300px;">
                            <canvas id="trendChart"></canvas>
                        </div>
                    </div>

                    <!-- Penerima by Kategori -->
                    <div class="bg-white border border-gray-200 rounded p-4 md:col-span-2">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons mr-2 text-teal-600" style="font-size: 18px !important;">pie_chart</span>
                            Penerima Mengikut Kategori
                        </h3>
                        <div class="flex items-center justify-center" style="height: 300px;">
                            <canvas id="kategoriChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="bg-gray-50 rounded border border-gray-200 overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-blue-100 text-gray-600">
                            <tr>
                                <th class="px-4 py-2 table-header">No. Permohonan</th>
                                <th class="px-4 py-2 table-header">Tarikh</th>
                                <th class="px-4 py-2 table-header">Penerima</th>
                                <th class="px-4 py-2 table-header">Program</th>
                                <th class="px-4 py-2 table-header">Jumlah</th>
                                <th class="px-4 py-2 table-header">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($permohonan as $item)
                                <tr class="hover:bg-white">
                                    <td class="px-4 py-2 table-data text-gray-900">{{ $item->no_permohonan }}</td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $item->tarikh_permohonan->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2 table-data text-gray-900">{{ $item->penerimaBantuan->nama_penuh }}</td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $item->programKebajikan->nama_program }}</td>
                                    <td class="px-4 py-2 table-data text-gray-600">RM {{ number_format($item->jumlah_dipohon ?? 0, 2) }}</td>
                                    <td class="px-4 py-2 table-data">
                                        @if($item->status_permohonan == 'Lulus')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Lulus</span>
                                        @elseif($item->status_permohonan == 'Ditolak')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Ditolak</span>
                                        @elseif($item->status_permohonan == 'Baharu')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-blue-100 text-blue-800">Baharu</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-yellow-100 text-yellow-800">{{ $item->status_permohonan }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                        <span class="material-icons mb-2" style="font-size: 48px !important;">description</span>
                                        <p class="text-sm">Tiada data permohonan</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($permohonan->hasPages())
                <div class="mt-4">
                    {{ $permohonan->appends(request()->query())->links('pagination::simple-tailwind') }}
                </div>
                @endif
            </div>
        </div>
    </main>

    <x-footer />

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Permohonan by Status Chart
            const statusCtx = document.getElementById('statusChart');
            if (statusCtx) {
                const statusData = {
                    labels: {!! json_encode($permohonanByStatus->keys()->toArray()) !!},
                    datasets: [{
                        data: {!! json_encode($permohonanByStatus->values()->toArray()) !!},
                        backgroundColor: ['#3B82F6', '#FBBF24', '#A855F7', '#10B981', '#EF4444', '#6B7280'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                };

                new Chart(statusCtx, {
                    type: 'pie',
                    data: statusData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    padding: 10,
                                    font: { size: 10 },
                                    boxWidth: 12,
                                    boxHeight: 12
                                }
                            }
                        }
                    }
                });
            }

            // Pembayaran by Kaedah Chart
            const kaedahCtx = document.getElementById('kaedahChart');
            if (kaedahCtx) {
                const kaedahData = {
                    labels: {!! json_encode($pembayaranByKaedah->keys()->toArray()) !!},
                    datasets: [{
                        label: 'Jumlah',
                        data: {!! json_encode($pembayaranByKaedah->values()->toArray()) !!},
                        backgroundColor: '#3B82F6',
                        borderWidth: 0,
                        borderRadius: 4
                    }]
                };

                new Chart(kaedahCtx, {
                    type: 'bar',
                    data: kaedahData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    font: { size: 10 }
                                }
                            },
                            x: {
                                ticks: { font: { size: 10 } }
                            }
                        }
                    }
                });
            }

            // Permohonan by Program Chart
            const programCtx = document.getElementById('programChart');
            if (programCtx) {
                const programLabels = {!! json_encode($permohonanByProgram->pluck('programKebajikan.nama_program')->toArray()) !!};
                const programValues = {!! json_encode($permohonanByProgram->pluck('total')->toArray()) !!};

                new Chart(programCtx, {
                    type: 'bar',
                    data: {
                        labels: programLabels,
                        datasets: [{
                            label: 'Permohonan',
                            data: programValues,
                            backgroundColor: '#10B981',
                            borderWidth: 0,
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    font: { size: 10 }
                                }
                            },
                            x: {
                                ticks: { font: { size: 10 } }
                            }
                        }
                    }
                });
            }

            // Trend Bulanan Chart
            const trendCtx = document.getElementById('trendChart');
            if (trendCtx) {
                const trendLabels = {!! json_encode($trendBulanan->pluck('bulan')->toArray()) !!};
                const trendValues = {!! json_encode($trendBulanan->pluck('total')->toArray()) !!};

                new Chart(trendCtx, {
                    type: 'line',
                    data: {
                        labels: trendLabels,
                        datasets: [{
                            label: 'Permohonan',
                            data: trendValues,
                            borderColor: '#A855F7',
                            backgroundColor: 'rgba(168, 85, 247, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    font: { size: 10 }
                                }
                            },
                            x: {
                                ticks: { font: { size: 10 } }
                            }
                        }
                    }
                });
            }

            // Penerima by Kategori Chart
            const kategoriCtx = document.getElementById('kategoriChart');
            if (kategoriCtx) {
                const kategoriData = {
                    labels: {!! json_encode($penerimaByKategori->keys()->toArray()) !!},
                    datasets: [{
                        data: {!! json_encode($penerimaByKategori->values()->toArray()) !!},
                        backgroundColor: ['#3B82F6', '#10B981', '#EF4444', '#FBBF24', '#A855F7', '#EC4899', '#14B8A6', '#F97316', '#6B7280'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                };

                new Chart(kategoriCtx, {
                    type: 'pie',
                    data: kategoriData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    padding: 10,
                                    font: { size: 10 },
                                    boxWidth: 12,
                                    boxHeight: 12
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>
