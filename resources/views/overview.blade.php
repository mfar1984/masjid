<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - E-Masjid</title>
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
</head>
<body class="bg-gray-50 font-sans min-h-screen flex flex-col" data-theme="corporate">
    <x-double-navbar :user="$user" />
    
    <div class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <!-- Main Dashboard Container -->
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <!-- Header Section -->
                <div class="mb-6">
                    <h1 class="text-xl font-bold text-gray-900 mb-2">Dashboard</h1>
                    <p class="text-xs text-gray-600">Selamat datang ke sistem pengurusan masjid</p>
                </div>

            <!-- Stats Cards Row 1 -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Ahli Kariah -->
                <div class="bg-gray-50 rounded-xs border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <span class="material-icons text-2xl">group</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Ahli Kariah</p>
                            <p class="text-2xl font-bold text-gray-900">1,247</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-green-600 flex items-center">
                            <span class="material-icons text-sm mr-1">trending_up</span>
                            +12%
                        </span>
                        <span class="text-gray-500 ml-2">dari bulan lepas</span>
                    </div>
                </div>

                <!-- Total Sumbangan Bulanan -->
                <div class="bg-gray-50 rounded-xs border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <span class="material-icons text-2xl">account_balance_wallet</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Sumbangan Bulanan</p>
                            <p class="text-2xl font-bold text-gray-900">RM 45,680</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-green-600 flex items-center">
                            <span class="material-icons text-sm mr-1">trending_up</span>
                            +8%
                        </span>
                        <span class="text-gray-500 ml-2">dari bulan lepas</span>
                    </div>
                </div>

                <!-- Program Aktif -->
                <div class="bg-gray-50 rounded-xs border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                            <span class="material-icons text-2xl">event</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Program Aktif</p>
                            <p class="text-2xl font-bold text-gray-900">23</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-blue-600 flex items-center">
                            <span class="material-icons text-sm mr-1">schedule</span>
                            5 program minggu ini
                        </span>
                    </div>
                </div>

                <!-- Status Aset -->
                <div class="bg-gray-50 rounded-xs border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                            <span class="material-icons text-2xl">inventory</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Status Aset</p>
                            <p class="text-2xl font-bold text-gray-900">98%</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-green-600 flex items-center">
                            <span class="material-icons text-sm mr-1">check_circle</span>
                            Baik
                        </span>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Sumbangan Trend -->
                <div class="bg-gray-50 rounded-xs border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Trend Sumbangan Bulanan</h3>
                    <div class="relative h-64">
                        <canvas id="donationChart" class="w-full h-full"></canvas>
                    </div>
                </div>

                <!-- Ahli Kariah by Zon -->
                <div class="bg-gray-50 rounded-xs border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Ahli Kariah mengikut Zon</h3>
                    <div class="relative h-64">
                        <canvas id="zoneChart" class="w-full h-full"></canvas>
                    </div>
                </div>
            </div>

                <!-- Activities & Events Tab View -->
                <div class="bg-gray-50 rounded-xs border border-gray-200" x-data="{ activeTab: 'activities' }">
                <!-- Tab Headers -->
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-8 px-6" aria-label="Tabs">
                        <button @click="activeTab = 'activities'" 
                                :class="activeTab === 'activities' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                            <span class="flex items-center">
                                <span class="material-icons text-sm mr-2">history</span>
                                Aktiviti Terkini
                            </span>
                        </button>
                        <button @click="activeTab = 'events'" 
                                :class="activeTab === 'events' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                            <span class="flex items-center">
                                <span class="material-icons text-sm mr-2">event</span>
                                Program Akan Datang
                            </span>
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">
                    <!-- Activities Tab -->
                    <div x-show="activeTab === 'activities'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="material-icons text-blue-600 text-sm">person_add</span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">Ahli Kariah Baru</p>
                                    <p class="text-sm text-gray-500">Ahmad bin Ali telah mendaftar sebagai ahli kariah zon A</p>
                                    <p class="text-xs text-gray-400 mt-1">2 jam yang lalu</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                        <span class="material-icons text-green-600 text-sm">account_balance_wallet</span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">Sumbangan Diterima</p>
                                    <p class="text-sm text-gray-500">RM 500.00 sumbangan untuk program masjid</p>
                                    <p class="text-xs text-gray-400 mt-1">4 jam yang lalu</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                        <span class="material-icons text-purple-600 text-sm">event</span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">Program Baru</p>
                                    <p class="text-sm text-gray-500">Kuliah Maghrib esok jam 7:30 malam</p>
                                    <p class="text-xs text-gray-400 mt-1">6 jam yang lalu</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                                        <span class="material-icons text-orange-600 text-sm">build</span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">Penyelenggaraan</p>
                                    <p class="text-sm text-gray-500">Sistem penyaman udara perlu diperbaiki</p>
                                    <p class="text-xs text-gray-400 mt-1">1 hari yang lalu</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Events Tab -->
                    <div x-show="activeTab === 'events'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="bg-white border border-gray-200 rounded-xs p-4 hover:border-blue-300 transition-colors">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-blue-600">Kuliah Maghrib</span>
                                    <span class="text-xs text-gray-500">Esok</span>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">Kuliah agama oleh Ustaz Ahmad</p>
                                <div class="flex items-center text-xs text-gray-500">
                                    <span class="material-icons text-sm mr-1">schedule</span>
                                    7:30 PM - 9:00 PM
                                </div>
                            </div>

                            <div class="bg-white border border-gray-200 rounded-xs p-4 hover:border-green-300 transition-colors">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-green-600">Program Khairat</span>
                                    <span class="text-xs text-gray-500">Jumaat</span>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">Kutipan khairat kematian</p>
                                <div class="flex items-center text-xs text-gray-500">
                                    <span class="material-icons text-sm mr-1">schedule</span>
                                    2:00 PM - 4:00 PM
                                </div>
                            </div>

                            <div class="bg-white border border-gray-200 rounded-xs p-4 hover:border-purple-300 transition-colors">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-purple-600">Mesyuarat AJK</span>
                                    <span class="text-xs text-gray-500">Sabtu</span>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">Mesyuarat bulanan jawatankuasa</p>
                                <div class="text-xs text-gray-500">
                                    <span class="material-icons text-sm mr-1">schedule</span>
                                    10:00 AM - 12:00 PM
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-footer />

    <script>
        // Wait for DOM to be ready and Chart.js to load
        document.addEventListener('DOMContentLoaded', function() {
            // Set timeout to prevent hanging
            const chartTimeout = setTimeout(() => {
                console.warn('Chart.js loading timeout, showing fallback content');
                showChartFallbacks();
            }, 5000); // 5 second timeout

            // Check if Chart.js is loaded
            if (typeof Chart === 'undefined') {
                console.warn('Chart.js not loaded, showing fallback content');
                clearTimeout(chartTimeout);
                showChartFallbacks();
                return;
            }

            try {
                // Clear timeout since charts are loading successfully
                clearTimeout(chartTimeout);
                
                // Donation Trend Chart
                const donationCtx = document.getElementById('donationChart');
                if (donationCtx) {
                    const donationChart = new Chart(donationCtx.getContext('2d'), {
                        type: 'line',
                        data: {
                            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                            datasets: [{
                                label: 'Sumbangan (RM)',
                                data: [32000, 35000, 38000, 42000, 45000, 48000, 52000, 55000, 58000, 62000, 65000, 68000],
                                borderColor: 'rgb(59, 130, 246)',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                tension: 0.4,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                x: {
                                    grid: {
                                        display: false
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.1)'
                                    },
                                    ticks: {
                                        callback: function(value) {
                                            return 'RM ' + value.toLocaleString();
                                        }
                                    }
                                }
                            },
                            elements: {
                                point: {
                                    radius: 4,
                                    hoverRadius: 6
                                }
                            }
                        }
                    });
                }

                // Zone Chart
                const zoneCtx = document.getElementById('zoneChart');
                if (zoneCtx) {
                    const zoneChart = new Chart(zoneCtx.getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: ['Zon A', 'Zon B', 'Zon C', 'Zon D'],
                            datasets: [{
                                data: [320, 280, 250, 397],
                                backgroundColor: [
                                    'rgb(59, 130, 246)',
                                    'rgb(16, 185, 129)',
                                    'rgb(245, 158, 11)',
                                    'rgb(239, 68, 68)'
                                ]
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    });
                }
            } catch (error) {
                console.error('Error creating charts:', error);
                showChartFallbacks();
            }
        });

        // Fallback function if charts fail to load
        function showChartFallbacks() {
            const donationChart = document.getElementById('donationChart');
            const zoneChart = document.getElementById('zoneChart');
            
            if (donationChart) {
                donationChart.innerHTML = `
                    <div class="flex items-center justify-center h-full text-gray-500">
                        <div class="text-center">
                            <span class="material-icons text-4xl mb-2">bar_chart</span>
                            <p class="text-sm">Trend Sumbangan Bulanan</p>
                            <p class="text-xs text-gray-400">Jan: RM32K, Feb: RM35K, Mar: RM38K...</p>
                        </div>
                    </div>
                `;
            }
            
            if (zoneChart) {
                zoneChart.innerHTML = `
                    <div class="flex items-center justify-center h-full text-gray-500">
                        <div class="text-center">
                            <span class="material-icons text-4xl mb-2">pie_chart</span>
                            <p class="text-sm">Ahli Kariah mengikut Zon</p>
                            <p class="text-xs text-gray-400">Zon A: 320, Zon B: 280, Zon C: 250, Zon D: 397</p>
                        </div>
                    </div>
                `;
            }
        }
    </script>
</body>
</html> 