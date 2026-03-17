<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Sokongan - E-Masjid</title>

    <!-- Favicon -->
    <x-favicon />

    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <style>
        /* EXACT SAME STYLES AS HUBUNGI-SOKONGAN */
        body {
            font-family: 'Poppins', sans-serif !important;
        }

        /* Live Pulse Animation */
        @keyframes livePulse {
            0%, 100% { 
                opacity: 1 !important;
                transform: scale(1) !important;
            }
            50% { 
                opacity: 0.7 !important;
                transform: scale(1.1) !important;
            }
        }

        .live-pulse {
            animation: livePulse 2s infinite ease-in-out !important;
        }

        /* Mode Toggle Buttons */
        .mode-toggle-btn {
            display: inline-flex !important;
            align-items: center !important;
            padding: 8px 16px !important;
            border-radius: 8px !important;
            font-size: 14px !important;
            font-weight: 500 !important;
            transition: all 0.2s ease !important;
            cursor: pointer !important;
            border: 1px solid #e5e7eb !important;
            background: white !important;
            color: #6b7280 !important;
        }

        .mode-toggle-btn.active {
            background: #3b82f6 !important;
            color: white !important;
            border-color: #3b82f6 !important;
        }

        .mode-toggle-btn:hover:not(.active) {
            background: #f3f4f6 !important;
            border-color: #d1d5db !important;
        }

        .mode-toggle-btn .material-icons {
            font-size: 1.125rem !important;
            margin-right: 8px !important;
            width: 18px !important;
            height: 18px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            vertical-align: middle !important;
        }

        /* Priority Badges */
        .priority-badge {
            font-size: 10px !important;
            font-weight: 600 !important;
            padding: 4px 8px !important;
            border-radius: 12px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
        }

        /* Content Items */
        .content-item {
            padding: 16px !important;
            border-bottom: 1px solid #f3f4f6 !important;
            transition: all 0.2s ease !important;
            cursor: pointer !important;
        }

        .content-item:hover {
            background-color: #f8fafc !important;
            border-left: 4px solid #3b82f6 !important;
            padding-left: 20px !important;
        }

        .content-item:last-child {
            border-bottom: none !important;
        }

        /* Header action buttons specific styling */
        .header-action-btn {
            padding: 8px !important;
            border-radius: 8px !important;
            transition: all 0.2s ease-in-out !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 40px !important;
            height: 40px !important;
        }
        
        .header-action-btn .material-icons {
            font-size: 1.125rem !important;
            color: #9ca3af !important;
            transition: color 0.2s ease-in-out !important;
        }
        
        .header-action-btn:hover {
            background-color: #dbeafe !important;
        }
        
        .header-action-btn:hover .material-icons {
            color: #3b82f6 !important;
        }

        /* Search input and icon specific styling */
        #searchInput {
            font-size: 0.875rem !important;
            padding: 8px 40px 8px 16px !important;
            border: 1px solid #d1d5db !important;
            border-radius: 8px !important;
            width: 256px !important;
            height: 40px !important;
            line-height: 1.5 !important;
        }
        
        #searchInput::placeholder {
            font-size: 0.8125rem !important;
            color: #9ca3af !important;
            font-weight: 400 !important;
            opacity: 1 !important;
        }
        
        #searchInput:focus {
            outline: none !important;
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
        }

        /* Search icon specific positioning */
        .search-icon {
            position: absolute !important;
            right: 12px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            font-size: 1.125rem !important;
            color: #9ca3af !important;
            pointer-events: none !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 18px !important;
            height: 18px !important;
        }

        /* Navigation sidebar icons specific styling */
        .nav-sidebar .material-icons {
            font-size: 1.125rem !important;
            width: 18px !important;
            height: 18px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        
        /* Content area avatar icons */
        .content-avatar .material-icons {
            font-size: 1.125rem !important;
            width: 18px !important;
            height: 18px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        /* Header Main Icon */
        .header-main-icon {
            transition: all 0.3s ease !important;
        }

        .header-main-icon .material-icons {
            font-size: 1.25rem !important;
            color: white !important;
        }

        .header-main-icon:hover {
            transform: scale(1.05) !important;
        }

        /* Live pulse animation */
        @keyframes livePulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.1); }
        }
        
        .live-pulse {
            animation: livePulse 2s infinite ease-in-out !important;
        }

        /* Main Content Container */
        .main-content-container {
            flex: 1 !important;
            overflow-y: auto !important;
            padding: 0 !important;
            margin: 0 !important;
            min-height: 0 !important;
            max-height: calc(100vh - 260px) !important;
        }

        /* Content Avatar */
        .content-avatar {
            transition: all 0.2s ease !important;
        }

        .content-item:hover .content-avatar {
            transform: scale(1.05) !important;
        }

        /* ===== CHAT INTERFACE STYLING ===== */
        
        /* Inline Chat Interface - Normal Container */
        #inlineChatInterface {
            background: white !important;
            border-radius: 8px !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
            overflow: hidden !important;
        }

        #inlineChatInterface.hidden {
            display: none !important;
        }

        /* Chat Interface - Simple Styling */
        #inlineChatInterface {
            font-family: 'Poppins', sans-serif !important;
        }

        /* Priority Notification Badge */
        .priority-notification {
            position: absolute !important;
            top: -4px !important;
            right: -4px !important;
            width: 18px !important;
            height: 18px !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 10px !important;
            font-weight: bold !important;
            border: 2px solid white !important;
        }

        /* Navigation Hover Effects */
        .nav-item-hover:hover {
            background: #f3f4f6 !important;
        }

        /* Container styles - EXACT SAME */
        .nav-sidebar {
            position: relative !important;
        }

        .content-header {
            position: relative !important;
        }

        .content-header::after {
            content: '' !important;
            position: absolute !important;
            bottom: -1px !important;
            left: -1px !important;
            right: 0 !important;
            height: 1px !important;
            background: #e5e7eb !important;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans min-h-screen flex flex-col" data-theme="corporate">

    <x-double-navbar :user="auth()->user()" />

        <div class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <!-- Main Dashboard Container - Same as Documents -->
            <div class="bg-white shadow-lg border-x border-gray-200">
                <!-- Header Section - Same Style as Documents -->
                <div class="px-6 py-4 border-b border-gray-200 bg-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-3">
                                <span class="material-icons text-2xl text-purple-600">business</span>
                                <h1 class="text-xl font-semibold text-gray-900">Dashboard Sokongan</h1>
                            </div>
                        </div>

                        <!-- Mode Toggle Buttons -->
                        <div class="flex items-center space-x-3">
                            <button id="chatModeBtn" class="mode-toggle-btn active">
                                <span class="material-icons">chat</span>
                                Chat Langsung
                            </button>
                            <button id="ticketModeBtn" class="mode-toggle-btn">
                                <span class="material-icons">confirmation_number</span>
                                Sistem Tiket
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex" style="height: calc(100vh - 180px);">
                    <!-- Left Sidebar Navigation - EXACT SAME AS HUBUNGI-SOKONGAN -->
                    <div class="w-56 bg-white border-r border-gray-200 flex flex-col nav-sidebar" style="height: calc(100vh - 180px) !important;">
                        <!-- Navigation Header -->
                        <div class="p-5 flex-shrink-0 !important">
                            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">NAVIGASI</h3>
                        </div>

                        <!-- Scrollable Navigation Content -->
                        <div class="flex-1 overflow-y-auto" style="min-height: 0 !important;">
                            <!-- Chat Navigation (Default Active) -->
                            <div id="chatNavigation" class="p-4">
                            @if($isSuperAdmin)
                                <!-- Main Header -->
                                <div class="mb-4">
                                    <div class="flex items-center px-3 py-2 text-sm font-medium text-blue-700 bg-blue-50 rounded-lg cursor-pointer" style="font-family: 'Poppins', sans-serif !important;">
                                        <span class="material-icons text-lg mr-3">business</span>
                                        Dashboard Sokongan
                                    </div>
                                </div>

                                <!-- OPERASI AKTIF Group -->
                                <div class="mb-4">
                                    <div class="px-3 py-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Operasi Aktif
                                    </div>
                                    <div class="mt-2 space-y-1">
                                        <a href="#" class="flex items-center px-3 py-2 text-sm text-green-700 hover:bg-green-50 rounded-lg transition-colors group">
                                            <span class="material-icons text-lg mr-3">flash_on</span>
                                            Sesi Langsung
                                            <span class="ml-auto text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">3</span>
                                        </a>
                                        <a href="#" class="flex items-center px-3 py-2 text-sm text-orange-700 hover:bg-orange-50 rounded-lg transition-colors group">
                                            <span class="material-icons text-lg mr-3">schedule</span>
                                            Senarai Tunggu
                                            <span class="ml-auto text-xs bg-orange-100 text-orange-800 px-2 py-1 rounded-full">8</span>
                                        </a>
                                        <a href="#" class="flex items-center px-3 py-2 text-sm text-blue-700 hover:bg-blue-50 rounded-lg transition-colors group">
                                            <span class="material-icons text-lg mr-3">sync</span>
                                            Dalam Proses
                                            <span class="ml-auto text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">5</span>
                                        </a>
                                        <a href="#" class="flex items-center px-3 py-2 text-sm text-purple-700 hover:bg-purple-50 rounded-lg transition-colors group">
                                            <span class="material-icons text-lg mr-3">star</span>
                                            Pelanggan VIP
                                            <span class="ml-auto text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded-full">2</span>
                                        </a>
                                    </div>
                                </div>

                                <!-- Separator -->
                                <div class="border-t border-gray-200 my-4"></div>

                                <!-- ANALITIK Group -->
                                <div class="mb-4">
                                    <div class="px-3 py-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Analitik
                                    </div>
                                    <div class="mt-2 space-y-1">
                                        <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors group">
                                            <span class="material-icons text-lg mr-3">today</span>
                                            Ringkasan Hari Ini
                                        </a>
                                        <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors group">
                                            <span class="material-icons text-lg mr-3">analytics</span>
                                            Metrik Prestasi
                                        </a>
                                        <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors group">
                                            <span class="material-icons text-lg mr-3">history</span>
                                            Sejarah Sesi
                                        </a>
                                    </div>
                                </div>

                                <!-- Separator -->
                                <div class="border-t border-gray-200 my-4"></div>

                                <!-- PENGURUSAN Group -->
                                <div class="mb-4">
                                    <div class="px-3 py-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Pengurusan
                                    </div>
                                    <div class="mt-2 space-y-1">
                                        <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors group">
                                            <span class="material-icons text-lg mr-3">group</span>
                                            Pengguna Aktif
                                        </a>
                                        <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors group">
                                            <span class="material-icons text-lg mr-3">mosque</span>
                                            Senarai Masjid
                                        </a>
                                        <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors group">
                                            <span class="material-icons text-lg mr-3">settings</span>
                                            Tetapan Sistem
                                        </a>
                                    </div>
                                </div>
                            @else
                            <!-- Simplified Chat Navigation for Masjid Users -->
                            <div class="mb-4">
                                <div class="px-3 py-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Chat Sokongan
                                </div>
                                <div class="mt-2 space-y-1">
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-blue-700 bg-blue-50 rounded-lg transition-colors group">
                                        <span class="material-icons text-lg mr-3">chat</span>
                                        Chat Sokongan
                                    </a>
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                        <span class="material-icons text-lg mr-3">history</span>
                                        Sejarah Sesi
                                    </a>
                                </div>
                            </div>
                            @endif
                            </div>

                            <!-- Ticket Navigation (Hidden by default) -->
                            <div id="ticketNavigation" class="p-4 hidden">
                            @if($isSuperAdmin)
                            <!-- Full Ticket Navigation for Super Admin -->
                            <!-- TAHAP KEUTAMAAN Group -->
                                        <div class="mb-4">
                                <div class="px-3 py-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Tahap Keutamaan
                                </div>
                                <div class="mt-2 space-y-1">
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-red-700 hover:bg-red-50 rounded-lg transition-colors group">
                                        <span class="material-icons text-lg mr-3 text-red-600">priority_high</span>
                                        Kecemasan
                                        <span class="ml-auto text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full">2</span>
                                    </a>
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-orange-700 hover:bg-orange-50 rounded-lg transition-colors group">
                                        <span class="material-icons text-lg mr-3 text-orange-600">warning</span>
                                        Tinggi
                                        <span class="ml-auto text-xs bg-orange-100 text-orange-800 px-2 py-1 rounded-full">5</span>
                                    </a>
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-blue-700 hover:bg-blue-50 rounded-lg transition-colors group">
                                        <span class="material-icons text-lg mr-3 text-blue-600">info</span>
                                        Sederhana
                                        <span class="ml-auto text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">8</span>
                                    </a>
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors group">
                                        <span class="material-icons text-lg mr-3 text-gray-500">low_priority</span>
                                        Rendah
                                        <span class="ml-auto text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">15</span>
                                    </a>
                            </div>
                                        </div>

                            <!-- Separator -->
                            <div class="border-t border-gray-200 my-4"></div>

                            <!-- STATUS TIKET Group -->
                            <div class="mb-4">
                                <div class="px-3 py-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Status Tiket
                                </div>
                                <div class="mt-2 space-y-1">
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-green-700 hover:bg-green-50 rounded-lg transition-colors group">
                                        <span class="material-icons text-lg mr-3">fiber_new</span>
                                        Tiket Baru
                                        <span class="ml-auto text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">6</span>
                                    </a>
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-blue-700 hover:bg-blue-50 rounded-lg transition-colors group">
                                        <span class="material-icons text-lg mr-3">work</span>
                                        Dalam Kerja
                                        <span class="ml-auto text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">12</span>
                                    </a>
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-yellow-700 rounded-lg transition-colors group menunggu-maklumbalas-hover nav-item-hover">
                                        <span class="material-icons text-lg mr-3">pause_circle</span>
                                        Menunggu Maklumbalas
                                        <span class="ml-auto text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">4</span>
                                    </a>
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors group">
                                        <span class="material-icons text-lg mr-3">check_circle</span>
                                        Selesai
                                        <span class="ml-auto text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">25</span>
                                    </a>
                                        </div>
                                    </div>

                            <!-- Separator -->
                            <div class="border-t border-gray-200 my-4"></div>

                            <!-- KATEGORI MASALAH Group -->
                            <div class="mb-4">
                                <div class="px-3 py-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Kategori Masalah
                                </div>
                                <div class="mt-2 space-y-1">
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-blue-700 hover:bg-blue-50 rounded-lg transition-colors group">
                                        <span class="material-icons text-lg mr-3">bug_report</span>
                                        Teknikal
                                        <span class="ml-auto text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">18</span>
                                    </a>
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-green-700 hover:bg-green-50 rounded-lg transition-colors group">
                                        <span class="material-icons text-lg mr-3">account_circle</span>
                                        Akaun
                                        <span class="ml-auto text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">8</span>
                                    </a>
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-purple-700 rounded-lg transition-colors group permintaan-ciri-hover nav-item-hover">
                                        <span class="material-icons text-lg mr-3">star</span>
                                        Permintaan Ciri
                                        <span class="ml-auto text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded-full">4</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Separator -->
                            <div class="border-t border-gray-200 my-4"></div>

                            <!-- LAPORAN & ANALISIS Group -->
                            <div class="mb-4">
                                <div class="px-3 py-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Laporan & Analisis
                                </div>
                                <div class="mt-2 space-y-1">
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                        <span class="material-icons text-lg mr-3">dashboard</span>
                                        Ringkasan Tiket
                                    </a>
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                        <span class="material-icons text-lg mr-3">assessment</span>
                                        Laporan SLA
                                    </a>
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                        <span class="material-icons text-lg mr-3">trending_up</span>
                                        Analisis Trend
                                    </a>
                                </div>
                            </div>

                            @else
                            <!-- Simplified Ticket Navigation for Masjid Users -->
                            <!-- Basic Ticket Actions -->
                            <div class="mb-4">
                                <div class="px-3 py-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Tiket Sokongan
                                </div>
                                <div class="mt-2 space-y-1">
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-blue-700 bg-blue-50 rounded-lg transition-colors group">
                                        <span class="material-icons text-lg mr-3">add_circle</span>
                                        Buat Tiket Baru
                                    </a>
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                        <span class="material-icons text-lg mr-3">list_alt</span>
                                        Tiket Saya
                                    </a>
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                        <span class="material-icons text-lg mr-3">history</span>
                                        Sejarah Tiket
                                    </a>
                                </div>
                            </div>
                            @endif
                            </div>
                        </div>
                    </div>

                    <!-- Right Content Area -->
                    <div class="flex-1 bg-white flex flex-col" style="height: calc(100vh - 180px) !important;">
                        <!-- Content Header -->
                        <div class="content-header px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-white to-gray-50 flex-shrink-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center shadow-lg header-main-icon">
                                        <span class="material-icons">business</span>
                                    </div>
                                    <div>
                                        <h2 id="contentTitle" class="text-lg font-semibold text-gray-900" style="font-family: 'Poppins', sans-serif !important;">Pengguna Dalam Talian</h2>
                                        <p id="contentSubtitle" class="text-sm text-gray-600 flex items-center">
                                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                                            5 masjid menunggu sokongan • 3 kecemasan
                    </p>
                </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="relative">
                                        <input type="text" placeholder="Cari masjid..." id="searchInput">
                                        <span class="material-icons search-icon">search</span>
                                    </div>
                                    <button class="header-action-btn" title="Filter Priority">
                                        <span class="material-icons">filter_list</span>
                                    </button>
                                    <button class="header-action-btn" title="Refresh Dashboard">
                                        <span class="material-icons">refresh</span>
                                    </button>
                                    <button class="header-action-btn" title="Settings">
                                        <span class="material-icons">settings</span>
                                    </button>
                                </div>
                    </div>
                        </div>

                        <!-- Main Content List with Horizontal Separators -->
                        <div class="main-content-container" id="mainContentArea">
                            <!-- Chat Interface Content -->
                            <div id="chatInterface" class="space-y-0">
                                <!-- Super Admin User List - Full Width -->
                                <!-- User Item 1 - Kecemasan -->
                                <div class="flex items-center cursor-pointer content-item" onclick="openChat('Ahmad Razak', 'Kecemasan', 'MAYDAY! Sistem tidak boleh login sama sekali. Semua pengguna terjejas!')">
                                    <div class="relative mr-4">
                                        <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-700 rounded-full flex items-center justify-center shadow-md content-avatar">
                                            <span class="material-icons text-white">error</span>
                                        </div>
                                        <div class="priority-notification bg-red-600">
                                            <span class="text-white font-bold">!</span>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h3 class="text-sm font-medium text-gray-900" style="font-family: 'Poppins', sans-serif !important;">Ahmad Razak</h3>
                                                <span class="text-xs text-red-600 font-medium">Masjid Al-Falah • Kuala Lumpur</span>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <div class="w-2 h-2 bg-green-500 rounded-full live-pulse" style="animation: livePulse 2s infinite ease-in-out !important;"></div>
                                                <span class="text-xs text-green-600 font-medium">Online</span>
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-600 mt-1">MAYDAY! Sistem tidak boleh login sama sekali. Semua pengguna terjejas!</p>
                                        <div class="flex items-center justify-between mt-2">
                                            <span class="text-xs text-gray-500">15 minit yang lalu</span>
                                            <span class="bg-red-100 text-red-800 priority-badge">Kecemasan</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- User Item 2 - Normal -->
                                <div class="flex items-center cursor-pointer content-item" onclick="openChat('Siti Aminah', 'Sederhana', 'Bagaimana nak setup email notifications untuk sistem?')">
                                    <div class="relative mr-4">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center shadow-md content-avatar">
                                            <span class="material-icons text-white">person</span>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h3 class="text-sm font-medium text-gray-900" style="font-family: 'Poppins', sans-serif !important;">Siti Aminah</h3>
                                                <span class="text-xs text-blue-600 font-medium">Masjid Ar-Rahman • Shah Alam</span>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <div class="w-2 h-2 bg-green-500 rounded-full live-pulse" style="animation: livePulse 2s infinite ease-in-out !important;"></div>
                                                <span class="text-xs text-green-600 font-medium">Online</span>
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-600 mt-1">Bagaimana nak setup email notifications untuk sistem?</p>
                                        <div class="flex items-center justify-between mt-2">
                                            <span class="text-xs text-gray-500">2 jam yang lalu</span>
                                            <span class="bg-blue-100 text-blue-800 priority-badge">Sederhana</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- User Item 3 - VIP -->
                                <div class="flex items-center cursor-pointer content-item" onclick="openChat('Dato\' Mahmud', 'VIP', 'Perlu bantuan segera untuk laporan bulanan.')">
                                    <div class="relative mr-4">
                                        <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-full flex items-center justify-center shadow-md content-avatar">
                                            <span class="material-icons text-white">star</span>
                                        </div>
                                        <div class="priority-notification bg-yellow-500">
                                            <span class="text-white font-bold">VIP</span>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h3 class="text-sm font-medium text-gray-900" style="font-family: 'Poppins', sans-serif !important;">Dato' Mahmud</h3>
                                                <span class="text-xs text-yellow-600 font-medium">Masjid Negara • Kuala Lumpur</span>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <div class="w-2 h-2 bg-green-500 rounded-full live-pulse" style="animation: livePulse 2s infinite ease-in-out !important;"></div>
                                                <span class="text-xs text-green-600 font-medium">Online</span>
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-600 mt-1">Perlu bantuan segera untuk laporan bulanan.</p>
                                        <div class="flex items-center justify-between mt-2">
                                            <span class="text-xs text-gray-500">45 minit yang lalu</span>
                                            <span class="bg-yellow-100 text-yellow-800 priority-badge">VIP</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Ticket Interface Content (Hidden by default) -->
                            <div id="ticketInterface" class="space-y-0 hidden">
                                <!-- Dashboard Overview Content for Ticket Mode -->
                                <div class="p-6">
                                    <!-- Ticket Stats Cards -->
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                                        <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                                            <div class="flex items-center">
                                                <div class="p-2 bg-red-500 rounded-lg">
                                                    <span class="material-icons text-white">priority_high</span>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium text-red-700">Kecemasan</p>
                                                    <p class="text-2xl font-bold text-red-900">2</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                                            <div class="flex items-center">
                                                <div class="p-2 bg-green-500 rounded-lg">
                                                    <span class="material-icons text-white">fiber_new</span>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium text-green-700">Tiket Baru</p>
                                                    <p class="text-2xl font-bold text-green-900">6</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                            <div class="flex items-center">
                                                <div class="p-2 bg-blue-500 rounded-lg">
                                                    <span class="material-icons text-white">work</span>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium text-blue-700">Dalam Kerja</p>
                                                    <p class="text-2xl font-bold text-blue-900">12</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                            <div class="flex items-center">
                                                <div class="p-2 bg-gray-500 rounded-lg">
                                                    <span class="material-icons text-white">check_circle</span>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium text-gray-700">Selesai</p>
                                                    <p class="text-2xl font-bold text-gray-900">25</p>
                                                </div>
                        </div>
                            </div>
                        </div>

                                    <!-- Ticket Queue List -->
                                    <div class="bg-white rounded-lg border border-gray-200">
                                        <div class="px-6 py-4 border-b border-gray-200">
                                            <h3 class="font-semibold text-gray-900">Antrian Tiket</h3>
                        </div>
                                        <div class="divide-y divide-gray-100">
                            @forelse($ticketQueue as $ticket)
                            @php
                                                $priorityColors = [
                                    'urgent' => 'bg-red-100 text-red-800',
                                    'high' => 'bg-orange-100 text-orange-800',
                                    'medium' => 'bg-blue-100 text-blue-800',
                                    'low' => 'bg-green-100 text-green-800'
                                                ];
                            @endphp
                                            <div class="content-item">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-900">#{{ $ticket['id'] }}</span>
                                                    <span class="priority-badge {{ $priorityColors[$ticket['priority']] ?? 'bg-gray-100 text-gray-800' }}">
                                                        {{ strtoupper($ticket['priority']) }}
                                    </span>
                                </div>
                                <h4 class="text-sm font-medium text-gray-900 mb-1">{{ $ticket['title'] }}</h4>
                                <p class="text-xs text-gray-600 mb-2">{{ $ticket['masjid_name'] }}</p>
                                <span class="text-xs text-gray-500">{{ $ticket['time_ago'] }}</span>
                            </div>
                            @empty
                            <div class="p-8 text-center">
                                <span class="material-icons text-4xl text-gray-400 mb-2">confirmation_number</span>
                                <p class="text-gray-500">Tiada tiket dalam antrian</p>
                            </div>
                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Inline Chat Interface (Hidden by default) -->
                            <div id="inlineChatInterface" class="hidden flex flex-col" style="height: 500px !important;">
                                <!-- Chat Header -->
                                <div class="bg-white border-b border-gray-200 p-4 flex-shrink-0">
                                    <div class="flex items-center justify-between">
                                        <button onclick="closeChat()" class="flex items-center text-blue-600 hover:text-blue-800 transition-colors">
                                            <span class="material-icons mr-2">arrow_back</span>
                                            <span class="font-medium">Kembali ke Senarai</span>
                                        </button>
                                        <div class="flex items-center space-x-3">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 rounded-full flex items-center justify-center mr-3">
                                                    <span class="material-icons text-white">person</span>
                                                </div>
                                                <div>
                                                    <h3 id="chatUserName" class="font-semibold text-gray-900">Ahmad Razak</h3>
                                                    <div class="flex items-center text-xs text-gray-600 space-x-1">
                                                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                                        <span>Dalam Talian</span>
                                                        <span>•</span>
                                                        <span>Masjid Al-Falah</span>
                                                        <span id="chatPriorityBadge" class="ml-2 px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">Kecemasan</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Chat Messages Area -->
                                <div class="flex-1 overflow-y-auto bg-gray-50 p-4" id="chatMessages">
                                    <!-- Sample Messages -->
                                    <div class="space-y-4">
                                        <div class="flex justify-start">
                                            <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg bg-white border border-gray-200">
                                                <p class="text-sm text-gray-800">Assalamualaikum, saya ada masalah dengan sistem upload dokumen. Bila saya cuba upload fail PDF, ia menunjukkan error "File too large" walaupun saiz fail hanya 2MB sahaja.</p>
                                                <p class="text-xs text-gray-500 mt-1">2 minit yang lalu</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex justify-end">
                                            <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg bg-blue-600 text-white">
                                                <p class="text-sm">Waalaikumussalam Ahmad. Terima kasih kerana menghubungi sokongan teknikal. Saya akan bantu anda menyelesaikan masalah ini. Boleh beritahu saya jenis browser yang anda gunakan?</p>
                                                <p class="text-xs opacity-75 mt-1">1 minit yang lalu</p>
                                            </div>
                                        </div>

                                        <div class="flex justify-start">
                                            <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg bg-white border border-gray-200">
                                                <p class="text-sm text-gray-800">Saya guna Google Chrome versi terkini. Masalah ini baru mula berlaku hari ini.</p>
                                                <p class="text-xs text-gray-500 mt-1">30 saat yang lalu</p>
                                            </div>
                                        </div>

                                        <!-- Typing Indicator -->
                                        <div id="typingIndicator" class="flex justify-start hidden">
                                            <div class="px-4 py-2 rounded-lg bg-white border border-gray-200">
                                                <div class="flex space-x-1">
                                                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-pulse"></div>
                                                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-pulse" style="animation-delay: 0.1s;"></div>
                                                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-pulse" style="animation-delay: 0.2s;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Chat Input Area -->
                                <div class="bg-white border-t border-gray-200 p-3 flex-shrink-0">
                                    <div class="flex items-center space-x-3">
                                        <button class="p-2 text-gray-500 hover:text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                                            <span class="material-icons">attach_file</span>
                                        </button>
                                        <div class="flex-1">
                                            <textarea id="chatInput" rows="1" placeholder="Taip mesej anda..." class="w-full px-4 py-2 border border-gray-300 rounded-lg resize-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"></textarea>
                                        </div>
                                        <button id="chatSendBtn" onclick="sendMessage()" class="p-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                            <span class="material-icons">send</span>
                                        </button>
                                    </div>
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
        document.addEventListener('DOMContentLoaded', function() {
            const chatModeBtn = document.getElementById('chatModeBtn');
            const ticketModeBtn = document.getElementById('ticketModeBtn');
            const chatInterface = document.getElementById('chatInterface');
            const ticketInterface = document.getElementById('ticketInterface');
            const chatNavigation = document.getElementById('chatNavigation');
            const ticketNavigation = document.getElementById('ticketNavigation');
            const contentTitle = document.getElementById('contentTitle');
            const contentSubtitle = document.getElementById('contentSubtitle');

            // Chat Mode Button Click
            chatModeBtn.addEventListener('click', function() {
                // Update button states
                chatModeBtn.classList.remove('inactive');
                chatModeBtn.classList.add('active');
                
                ticketModeBtn.classList.remove('active');
                ticketModeBtn.classList.add('inactive');

                // Show/hide content interfaces
                chatInterface.classList.remove('hidden');
                ticketInterface.classList.add('hidden');
                
                // Show/hide navigation sections
                chatNavigation.classList.remove('hidden');
                ticketNavigation.classList.add('hidden');
                
                // Update content header
                contentTitle.textContent = 'Pengguna Dalam Talian';
                contentSubtitle.innerHTML = '<span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>5 masjid menunggu sokongan • 3 kecemasan';
            });

            // Ticket Mode Button Click
            ticketModeBtn.addEventListener('click', function() {
                // Update button states
                ticketModeBtn.classList.remove('inactive');
                ticketModeBtn.classList.add('active');
                
                chatModeBtn.classList.remove('active');
                chatModeBtn.classList.add('inactive');

                // Show/hide content interfaces
                ticketInterface.classList.remove('hidden');
                chatInterface.classList.add('hidden');
                
                // Show/hide navigation sections
                ticketNavigation.classList.remove('hidden');
                chatNavigation.classList.add('hidden');
                
                // Update content header
                contentTitle.textContent = 'Sistem Tiket';
                contentSubtitle.innerHTML = '<span class="w-2 h-2 bg-blue-500 rounded-full mr-2 animate-pulse"></span>Menguruskan tiket sokongan';
            });

            // Initialize default state - Chat mode active by default
            chatModeBtn.classList.add('active');
            ticketModeBtn.classList.add('inactive');
            chatInterface.classList.remove('hidden');
            ticketInterface.classList.add('hidden');
            chatNavigation.classList.remove('hidden');
            ticketNavigation.classList.add('hidden');
            contentTitle.textContent = 'Pengguna Dalam Talian';
            contentSubtitle.innerHTML = '<span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>5 masjid menunggu sokongan • 3 kecemasan';
        });

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            console.log('Searching for:', searchTerm);
            // TODO: Implement search
        });

        // Chat Functions
        function openChat(userName, priority, description, masjidId, ipAddress, location, userStatus) {
            const chatInterface = document.getElementById('chatInterface');
            const ticketInterface = document.getElementById('ticketInterface');
            const inlineChatInterface = document.getElementById('inlineChatInterface');
            const contentHeader = document.querySelector('.content-header');
            const chatUserName = document.getElementById('chatUserName');
            const chatPriorityBadge = document.getElementById('chatPriorityBadge');
            
            // Hide user list and ticket interface
            chatInterface.classList.add('hidden');
            ticketInterface.classList.add('hidden');
            
            // Show inline chat interface
            inlineChatInterface.classList.remove('hidden');
            
            // Hide content header when in chat mode
            if (contentHeader) {
                contentHeader.style.display = 'none';
            }
            
            // Update chat user info
            if (chatUserName) {
                chatUserName.textContent = userName;
            }
            if (chatPriorityBadge) {
                chatPriorityBadge.textContent = priority;
                // Update badge color based on priority
                if (priority === 'Kecemasan') {
                    chatPriorityBadge.className = 'ml-2 px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium';
                } else if (priority === 'VIP') {
                    chatPriorityBadge.className = 'ml-2 px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium';
                } else {
                    chatPriorityBadge.className = 'ml-2 px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium';
                }
            }
            
            // Auto-scroll to bottom of chat messages
            setTimeout(() => {
                const chatMessages = document.getElementById('chatMessages');
                if (chatMessages) {
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }
            }, 100);
        }

        function closeChat() {
            const chatInterface = document.getElementById('chatInterface');
            const ticketInterface = document.getElementById('ticketInterface');
            const inlineChatInterface = document.getElementById('inlineChatInterface');
            const contentHeader = document.querySelector('.content-header');
            const contentTitle = document.getElementById('contentTitle');
            const contentSubtitle = document.getElementById('contentSubtitle');
            const chatModeBtn = document.getElementById('chatModeBtn');
            const ticketModeBtn = document.getElementById('ticketModeBtn');
            
            // Hide inline chat interface
            inlineChatInterface.classList.add('hidden');
            
            // Show content header again
            if (contentHeader) {
                contentHeader.style.display = 'flex';
            }
            
            // Show appropriate interface based on active mode
            if (ticketModeBtn.classList.contains('active')) {
                // Ticket mode is active
                ticketInterface.classList.remove('hidden');
                contentTitle.textContent = 'Sistem Tiket';
                contentSubtitle.innerHTML = '<span class="w-2 h-2 bg-orange-500 rounded-full mr-2 animate-pulse"></span>12 tiket aktif • 3 menunggu tindakan';
            } else {
                // Chat mode is active
                chatInterface.classList.remove('hidden');
                contentTitle.textContent = 'Pengguna Dalam Talian';
                contentSubtitle.innerHTML = '<span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>5 masjid menunggu sokongan • 3 kecemasan';
            }
        }

        function toggleChatMenu() {
            // TODO: Implement chat menu dropdown
            console.log('Chat menu clicked');
        }

        function sendMessage() {
            const chatInput = document.getElementById('chatInput');
            const chatMessages = document.getElementById('chatMessages');
            const chatSendBtn = document.getElementById('chatSendBtn');
            const typingIndicator = document.getElementById('typingIndicator');
            
            if (!chatInput.value.trim()) return;
            
            const messageText = chatInput.value.trim();
            chatInput.value = '';
            chatSendBtn.disabled = true;
            
            // Find the messages container
            const messagesContainer = chatMessages.querySelector('.space-y-4');
            
            // Add user message
            const userMessage = document.createElement('div');
            userMessage.className = 'flex justify-end';
            userMessage.innerHTML = `
                <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg bg-blue-600 text-white">
                    <p class="text-sm">${messageText}</p>
                    <p class="text-xs opacity-75 mt-1">Baru sahaja</p>
                </div>
            `;
            messagesContainer.appendChild(userMessage);
            
            // Show typing indicator
            typingIndicator.classList.remove('hidden');
            
            // Scroll to bottom
            chatMessages.scrollTop = chatMessages.scrollHeight;
            
            // Simulate bot response after 2 seconds
            setTimeout(() => {
                typingIndicator.classList.add('hidden');
                
                const botMessage = document.createElement('div');
                botMessage.className = 'flex justify-start';
                botMessage.innerHTML = `
                    <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg bg-white border border-gray-200">
                        <p class="text-sm text-gray-800">Terima kasih atas mesej anda. Saya akan membantu anda dengan isu ini.</p>
                        <p class="text-xs text-gray-500 mt-1">Baru sahaja</p>
                    </div>
                `;
                messagesContainer.appendChild(botMessage);
                
                // Scroll to bottom
                chatMessages.scrollTop = chatMessages.scrollHeight;
                chatSendBtn.disabled = false;
            }, 2000);
        }

        // Enable/disable send button based on input
        document.getElementById('chatInput').addEventListener('input', function() {
            const chatSendBtn = document.getElementById('chatSendBtn');
            chatSendBtn.disabled = !this.value.trim();
        });

        // Send message on Enter key
        document.getElementById('chatInput').addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
    </script>
</body>
</html>