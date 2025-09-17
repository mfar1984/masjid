<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Sistem - E-Masjid</title>
    
    <!-- Favicon -->
    <x-favicon />
    
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans min-h-screen flex flex-col" data-theme="corporate" style="font-family: 'Poppins', sans-serif;">
    <x-double-navbar :user="auth()->user()" />
    
    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <!-- Main Container -->
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <!-- Header Section -->
                <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Status Sistem</h1>
                        <p class="text-xs text-gray-600">Pemantauan kesihatan dan prestasi sistem E-Masjid</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        <form action="{{ route('bantuan.status-sistem.refresh') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center justify-center h-[32px] px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded transition-colors">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">refresh</span>
                                Kemaskini
                            </button>
                        </form>
                    </div>
                </div>

                @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded-sm text-sm">
                    {{ session('success') }}
                </div>
                @endif

                <!-- Overall Status Banner -->
                <div class="mb-8 rounded-sm p-6 text-white {{ $overallStatus === 'healthy' ? 'bg-gradient-to-r from-blue-500 to-purple-600' : 'bg-gradient-to-r from-blue-500 to-purple-600' }}">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="text-center sm:text-left mb-4 sm:mb-0">
                            <div class="flex items-center justify-center sm:justify-start mb-2">
                                <span class="material-icons text-2xl mr-2">
                                    {{ $overallStatus === 'healthy' ? 'check_circle' : 'error' }}
                                </span>
                                <h2 class="text-lg font-bold">Status Keseluruhan</h2>
                            </div>
                            <div class="text-2xl font-bold mb-1">
                                {{ $overallStatus === 'healthy' ? 'Sihat' : 'Bermasalah' }}
                            </div>
                            <p class="text-sm opacity-90">
                                {{ $overallStatus === 'healthy' ? 'Semua sistem berfungsi dengan normal' : 'Terdapat masalah yang memerlukan perhatian' }}
                            </p>
                        </div>
                        <div class="text-center sm:text-right">
                            <div class="text-sm opacity-90 mb-1">Kemaskini terakhir</div>
                            <div class="text-lg font-semibold">{{ now()->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Health Check Categories -->
                <div class="space-y-6">
                    <!-- Application Health -->
                    @if(!empty($groupedResults['application']))
                    <div class="bg-white border border-gray-200 rounded-sm overflow-hidden">
                        <div class="bg-blue-50 px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center">
                                <span class="material-icons text-blue-600 text-lg mr-2">web</span>
                                <h3 class="text-sm font-semibold text-gray-900">Kesihatan Aplikasi</h3>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($groupedResults['application'] as $result)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-sm">
                                    <div class="flex items-center">
                                        <span class="material-icons text-sm mr-2 {{ $result->status === 'ok' ? 'text-green-600' : ($result->status === 'warning' ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ $result->status === 'ok' ? 'check_circle' : ($result->status === 'warning' ? 'warning' : 'error') }}
                                        </span>
                                        <span class="text-xs text-gray-700">{{ $result->name ?? 'Unknown Check' }}</span>
                                    </div>
                                    <span class="px-2 py-1 text-xs rounded-sm {{ $result->status === 'ok' ? 'bg-green-100 text-green-800' : ($result->status === 'warning' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($result->status) }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Database Health -->
                    @if(!empty($groupedResults['database']))
                    <div class="bg-white border border-gray-200 rounded-sm overflow-hidden">
                        <div class="bg-purple-50 px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center">
                                <span class="material-icons text-purple-600 text-lg mr-2">storage</span>
                                <h3 class="text-sm font-semibold text-gray-900">Kesihatan Database</h3>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($groupedResults['database'] as $result)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-sm">
                                    <div class="flex items-center">
                                        <span class="material-icons text-sm mr-2 {{ $result->status === 'ok' ? 'text-green-600' : ($result->status === 'warning' ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ $result->status === 'ok' ? 'check_circle' : ($result->status === 'warning' ? 'warning' : 'error') }}
                                        </span>
                                        <span class="text-xs text-gray-700">{{ $result->name ?? class_basename($result->check_name ?? 'Unknown') }}</span>
                                    </div>
                                    <span class="px-2 py-1 text-xs rounded-sm {{ $result->status === 'ok' ? 'bg-green-100 text-green-800' : ($result->status === 'warning' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($result->status) }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Cache & Queue Health -->
                    @if(!empty($groupedResults['cache_queue']))
                    <div class="bg-white border border-gray-200 rounded-sm overflow-hidden">
                        <div class="bg-orange-50 px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center">
                                <span class="material-icons text-orange-600 text-lg mr-2">memory</span>
                                <h3 class="text-sm font-semibold text-gray-900">Cache & Queue</h3>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($groupedResults['cache_queue'] as $result)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-sm">
                                    <div class="flex items-center">
                                        <span class="material-icons text-sm mr-2 {{ $result->status === 'ok' ? 'text-green-600' : ($result->status === 'warning' ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ $result->status === 'ok' ? 'check_circle' : ($result->status === 'warning' ? 'warning' : 'error') }}
                                        </span>
                                        <span class="text-xs text-gray-700">{{ $result->name ?? class_basename($result->check_name ?? 'Unknown') }}</span>
                                    </div>
                                    <span class="px-2 py-1 text-xs rounded-sm {{ $result->status === 'ok' ? 'bg-green-100 text-green-800' : ($result->status === 'warning' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($result->status) }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- System Health -->
                    @if(!empty($groupedResults['system']))
                    <div class="bg-white border border-gray-200 rounded-sm overflow-hidden">
                        <div class="bg-green-50 px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center">
                                <span class="material-icons text-green-600 text-lg mr-2">computer</span>
                                <h3 class="text-sm font-semibold text-gray-900">Kesihatan Sistem</h3>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($groupedResults['system'] as $result)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-sm">
                                    <div class="flex items-center">
                                        <span class="material-icons text-sm mr-2 {{ $result->status === 'ok' ? 'text-green-600' : ($result->status === 'warning' ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ $result->status === 'ok' ? 'check_circle' : ($result->status === 'warning' ? 'warning' : 'error') }}
                                        </span>
                                        <span class="text-xs text-gray-700">{{ $result->name ?? class_basename($result->check_name ?? 'Unknown') }}</span>
                                    </div>
                                    <span class="px-2 py-1 text-xs rounded-sm {{ $result->status === 'ok' ? 'bg-green-100 text-green-800' : ($result->status === 'warning' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($result->status) }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
</body>
</html>
