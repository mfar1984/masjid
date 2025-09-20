<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pengurusan Dokumen - E-Masjid</title>

    <!-- Favicon -->
    <x-favicon />

    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <style>
        /* Context Menu Styles */
        #contextMenu {
            animation: contextMenuFadeIn 0.15s ease-out;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        @keyframes contextMenuFadeIn {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(-5px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .context-menu-item:hover .context-submenu {
            display: block !important;
        }

        .context-submenu {
            animation: submenuSlideIn 0.15s ease-out;
        }

        /* Dynamic submenu positioning */
        .context-menu-item {
            position: relative;
        }

        .context-submenu {
            position: absolute;
            left: 100%;
            top: 0;
            margin-left: 4px;
        }

        /* Submenu positioning for right edge */
        .context-menu-item.submenu-left .context-submenu {
            left: auto;
            right: 100%;
            margin-left: 0;
            margin-right: 4px;
        }

        /* Submenu positioning for bottom edge */
        .context-menu-item.submenu-up .context-submenu {
            top: auto;
            bottom: 0;
        }

        @keyframes submenuSlideIn {
            from {
                opacity: 0;
                transform: translateX(-5px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Folder Color Picker Styles */
        #folderColorSection .grid {
            display: grid !important;
            grid-template-columns: repeat(8, 1fr) !important;
            gap: 8px !important;
        }

        #folderColorSection .w-6 {
            width: 24px !important;
            height: 24px !important;
            border-radius: 50% !important;
            cursor: pointer !important;
            transition: transform 0.2s ease !important;
            border: 2px solid transparent !important;
        }

        #folderColorSection .w-6:hover {
            transform: scale(1.1) !important;
            border-color: rgba(0, 0, 0, 0.2) !important;
        }

        #folderColorSection .w-6:active {
            transform: scale(0.95) !important;
        }

        /* Rename Modal Styles */
        #renameModal {
            backdrop-filter: blur(4px);
        }

        #renameModalContent {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #renameInput {
            transition: border-color 0.15s ease-in-out;
        }

        #renameInput:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Three-dot menu button styles */
        .three-dot-menu {
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.9);
        }

        /* Grid item hover effects */
        .folder-item:hover .three-dot-menu,
        .file-item:hover .three-dot-menu {
            opacity: 1 !important;
        }

        /* Prevent text selection on context menu */
        #contextMenu {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans min-h-screen flex flex-col" data-theme="corporate">


    <x-double-navbar :user="auth()->user()" />

    <div class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <!-- Main Dashboard Container -->
            <div class="bg-white shadow-lg border-x border-gray-200">
                <!-- Header Section - Google Drive Style -->
                <div class="px-6 py-4 border-b border-gray-200 bg-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-3">
                                <span class="material-icons text-2xl text-blue-600">folder</span>
                                <h1 class="text-xl font-semibold text-gray-900">Dokumen Saya</h1>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex items-center space-x-3">
                            @if(auth()->user()->hasPermission('documents', 'create'))
                                <button onclick="openNewFolderModal()" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm">
                                    <span class="material-icons text-lg mr-2">create_new_folder</span>
                                    Folder Baru
                                </button>
                                <a href="{{ route('documents.create', ['folder' => $currentFolder?->id]) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 transition-all duration-200 shadow-sm">
                                    <span class="material-icons text-lg mr-2">cloud_upload</span>
                                    Muat Naik
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Google Drive Style Layout -->
                <div class="flex min-h-[calc(100vh-200px)]">
                    <!-- Sidebar - Google Drive Style -->
                    <div class="w-64 bg-white border-r border-gray-200 flex-shrink-0">
                        <div class="p-4">
                            <!-- Quick Access Navigation -->
                            <div class="mb-8">
                                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4 px-3">Navigasi</h3>
                                <nav class="space-y-1">
                                    <a href="{{ route('documents.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ !request('type') ? 'bg-blue-50 text-blue-700 border-r-3 border-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <span class="material-icons text-lg mr-3 {{ !request('type') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}">folder</span>
                                        <span class="font-medium">Dokumen Saya</span>
                                    </a>
                                    <a href="{{ route('documents.index', ['type' => 'recent']) }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request('type') === 'recent' ? 'bg-blue-50 text-blue-700 border-r-3 border-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <span class="material-icons text-lg mr-3 {{ request('type') === 'recent' ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}">schedule</span>
                                        <span class="font-medium">Terkini</span>
                                    </a>
                                    <a href="{{ route('documents.index', ['type' => 'starred']) }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request('type') === 'starred' ? 'bg-blue-50 text-blue-700 border-r-3 border-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <span class="material-icons text-lg mr-3 {{ request('type') === 'starred' ? 'text-yellow-500' : 'text-gray-400 group-hover:text-yellow-500' }}">star</span>
                                        <span class="font-medium">Kegemaran</span>
                                    </a>
                                    <a href="{{ route('documents.index', ['type' => 'shared']) }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request('type') === 'shared' ? 'bg-blue-50 text-blue-700 border-r-3 border-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <span class="material-icons text-lg mr-3 {{ request('type') === 'shared' ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}">people</span>
                                        <span class="font-medium">Dikongsi</span>
                                    </a>
                                    
                                    <!-- Separator Line -->
                                    <div class="mx-3 my-3 border-t border-gray-200"></div>
                                    
                                    <a href="{{ route('documents.index', ['type' => 'spam']) }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request('type') === 'spam' ? 'bg-orange-50 text-orange-700 border-r-3 border-orange-600' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <span class="material-icons text-lg mr-3 {{ request('type') === 'spam' ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-600' }}">report</span>
                                        <span class="font-medium">Spam</span>
                                    </a>
                                    <a href="{{ route('documents.index', ['type' => 'trash']) }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request('type') === 'trash' ? 'bg-red-50 text-red-700 border-r-3 border-red-600' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <span class="material-icons text-lg mr-3 {{ request('type') === 'trash' ? 'text-red-600' : 'text-gray-400 group-hover:text-red-600' }}">delete</span>
                                        <span class="font-medium">Sampah</span>
                                    </a>
                                </nav>
                            </div>

                            <!-- File Types Filter -->
                            <div class="mb-8">
                                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4 px-3">Jenis Fail</h3>
                                <nav class="space-y-1">
                                    <a href="{{ route('documents.index', ['extension' => 'pdf']) }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50 transition-all duration-200">
                                        <span class="material-icons text-lg mr-3 text-red-500 group-hover:text-red-600">picture_as_pdf</span>
                                        <span class="font-medium">PDF</span>
                                    </a>
                                    <a href="{{ route('documents.index', ['extension' => 'docx']) }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50 transition-all duration-200">
                                        <span class="material-icons text-lg mr-3 text-blue-500 group-hover:text-blue-600">description</span>
                                        <span class="font-medium">Word</span>
                                    </a>
                                    <a href="{{ route('documents.index', ['extension' => 'xlsx']) }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50 transition-all duration-200">
                                        <span class="material-icons text-lg mr-3 text-green-500 group-hover:text-green-600">table_chart</span>
                                        <span class="font-medium">Excel</span>
                                    </a>
                                    <a href="{{ route('documents.index', ['extension' => 'jpg']) }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50 transition-all duration-200">
                                        <span class="material-icons text-lg mr-3 text-purple-500 group-hover:text-purple-600">image</span>
                                        <span class="font-medium">Gambar</span>
                                    </a>
                                </nav>
                            </div>

                            <!-- Storage Usage -->
                            <div class="px-3">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-sm font-medium text-gray-700">Storan</span>
                                        <span class="text-xs text-gray-500">{{ number_format($stats['total_size'] / 1024 / 1024, 1) }}MB / 1GB</span>
                                    </div>
                                    <div class="bg-gray-200 rounded-full h-2 mb-2">
                                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ min(($stats['total_size'] / (1024*1024*1024)) * 100, 100) }}%"></div>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        {{ round(($stats['total_size'] / (1024*1024*1024)) * 100, 1) }}% digunakan
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content Area -->
                    <div class="flex-1 flex flex-col bg-gray-50">
                        <!-- Breadcrumbs & Toolbar -->
                        <div class="bg-white border-b border-gray-200">
                            <div class="px-6 py-4">
                                <!-- Breadcrumbs -->
                                @if(count($breadcrumbs) > 1)
                                <nav class="flex mb-4" aria-label="Breadcrumb">
                                    <ol class="inline-flex items-center space-x-1">
                                        @foreach($breadcrumbs as $index => $breadcrumb)
                                            <li class="inline-flex items-center">
                                                @if($index > 0)
                                                    <span class="material-icons text-gray-300 text-sm mx-2">chevron_right</span>
                                                @endif
                                                @if($loop->last)
                                                    <span class="text-sm font-medium text-gray-900 px-2 py-1 bg-gray-100 rounded-md">{{ $breadcrumb['name'] }}</span>
                                                @else
                                                    <a href="{{ $breadcrumb['url'] }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:bg-blue-50 px-2 py-1 rounded-md transition-all duration-200">
                                                        {{ $breadcrumb['name'] }}
                                                    </a>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ol>
                                </nav>
                                @endif

                                <!-- Toolbar -->
                                <div class="flex items-center justify-between">
                                    <!-- Left side - Title & Stats -->
                                    <div class="flex items-center space-x-4">
                                        <div>
                                            <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                                                @if($currentFolder)
                                                    <span class="material-icons text-xl mr-2" style="color: {{ $currentFolder->color ?? '#3B82F6' }}">folder</span>
                                                    {{ $currentFolder->name }}
                                                @else
                                                    <span class="material-icons text-xl mr-2 text-blue-600">folder</span>
                                                    Dokumen Saya
                                                @endif
                                            </h2>
                                            <p class="text-sm text-gray-500 mt-1">
                                                {{ $stats['total_documents'] }} item • {{ number_format($stats['total_size'] / 1024 / 1024, 1) }} MB
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Right side - View Options & Actions -->
                                    <div class="flex items-center space-x-3">
                                        <!-- Search -->
                                        <div class="relative" x-data="{ open: false }">
                                            <button @click="open = !open" class="flex items-center justify-center w-9 h-9 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full transition-all duration-200">
                                                <span class="material-icons" style="font-size: 16px !important; line-height: 1 !important;">search</span>
                                            </button>
                                            
                                            <!-- Search Dropdown -->
                                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-20">
                                                <form method="GET" class="p-4">
                                                    <div class="relative">
                                                        <span class="material-icons absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" style="font-size: 18px !important; line-height: 1 !important;">search</span>
                                                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari dokumen atau folder..." class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" style="padding-left: 44px !important;" autofocus>
                                                    </div>
                                                    <div class="flex items-center justify-end space-x-3 mt-3">
                                                        @if(request()->hasAny(['search', 'type', 'extension']))
                                                            <a href="{{ route('documents.index', ['folder' => $currentFolder?->id]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
                                                                <span class="material-icons mr-2" style="font-size: 16px !important; line-height: 1 !important;">clear</span>
                                                                Reset
                                                            </a>
                                                        @endif
                                                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-all duration-200">
                                                            <span class="material-icons mr-2" style="font-size: 16px !important; line-height: 1 !important;">search</span>
                                                            Cari
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <!-- View Toggle Pills -->
                                        <div class="flex items-center bg-gray-100 rounded-full p-0.5">
                                            <button id="listViewBtn" onclick="switchToListView()"
                                                    class="flex items-center justify-center px-3 py-1.5 text-gray-500 hover:text-gray-700 hover:bg-white rounded-full transition-all duration-200"
                                                    style="font-size: 14px !important;">
                                                <span class="material-icons" style="font-size: 16px !important; line-height: 1 !important;">view_list</span>
                                            </button>
                                            <button id="gridViewBtn" onclick="switchToGridView()"
                                                    class="flex items-center justify-center px-3 py-1.5 text-blue-600 bg-white rounded-full shadow-sm transition-all duration-200"
                                                    style="font-size: 14px !important;">
                                                <span class="material-icons" style="font-size: 16px !important; line-height: 1 !important;">grid_view</span>
                                            </button>
                                            <button id="sortBtn" onclick="toggleSortDropdown()"
                                                    class="flex items-center justify-center px-3 py-1.5 text-gray-500 hover:text-gray-700 hover:bg-white rounded-full transition-all duration-200"
                                                    style="font-size: 14px !important;">
                                                <span class="material-icons" style="font-size: 16px !important; line-height: 1 !important;">sort</span>
                                            </button>
                                        </div>

                                        <!-- Sort Dropdown (positioned relative to pills) -->
                                        <div class="relative">
                                            <div id="sortDropdown" class="absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 hidden" style="z-index: 9999 !important;">
                                                <div class="p-2">
                                                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 px-2" style="font-size: 10px;">SUSUN MENGIKUT</div>
                                                    <a href="{{ route('documents.index', array_merge(request()->query(), ['sort' => 'name'])) }}"
                                                       class="block px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-md transition-colors duration-200 {{ $sortBy == 'name' ? 'bg-blue-50 text-blue-600' : '' }}"
                                                       style="font-size: 12px;">
                                                        <div class="flex items-center">
                                                            <span class="material-icons mr-2" style="font-size: 14px;">sort_by_alpha</span>
                                                            Nama
                                                        </div>
                                                    </a>
                                                    <a href="{{ route('documents.index', array_merge(request()->query(), ['sort' => 'date'])) }}"
                                                       class="block px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-md transition-colors duration-200 {{ $sortBy == 'date' ? 'bg-blue-50 text-blue-600' : '' }}"
                                                       style="font-size: 12px;">
                                                        <div class="flex items-center">
                                                            <span class="material-icons mr-2" style="font-size: 14px;">schedule</span>
                                                            Tarikh
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Additional Filter (keep tune icon for other filters) -->
                                        <div class="relative" x-data="{ open: false }">
                                            <button @click="open = !open" class="flex items-center justify-center w-9 h-9 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full transition-all duration-200">
                                                <span class="material-icons" style="font-size: 16px !important; line-height: 1 !important;">tune</span>
                                            </button>

                                            <!-- Filter Dropdown -->
                                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200" style="z-index: 9999 !important;">
                                                <div class="p-2">
                                                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 px-2">Tapis mengikut</div>
                                                    <a href="{{ route('documents.index', array_merge(request()->query(), ['type' => 'starred'])) }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md">Berbintang</a>
                                                    <a href="{{ route('documents.index', array_merge(request()->query(), ['type' => 'shared'])) }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md">Dikongsi</a>
                                                    <a href="{{ route('documents.index', array_merge(request()->query(), ['type' => 'recent'])) }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md">Terkini</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Content Grid -->
                        <div class="flex-1 p-6 flex flex-col relative">



                            <div id="fileGrid" class="relative flex-1">
                            @if($folders->count() > 0 || $documents->count() > 0)
                                <!-- Folders Section -->
                                @if($folders->count() > 0)
                                    <div class="mb-8">
                                        <h3 class="text-sm font-semibold text-gray-600 mb-4 px-1">Folder</h3>
                                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-8 gap-4">
                                            @foreach($folders as $folder)
                                                <div class="folder-item group relative bg-white rounded-xl border border-gray-200 hover:border-blue-300 hover:shadow-lg transition-all duration-300 cursor-pointer overflow-hidden" onclick="openFolder({{ $folder->id }})" oncontextmenu="showContextMenu(event, 'folder', {{ $folder->id }}, '{{ $folder->name }}', {{ $folder->is_starred ? 'true' : 'false' }}, '{{ $folder->color ?? '#3B82F6' }}')"
                                                    <!-- Folder Icon Container -->
                                                    <div style="padding: 16px !important; display: flex !important; flex-direction: column !important; align-items: center !important; justify-content: center !important; text-align: center !important; width: 100% !important; height: 100% !important;">
                                                        <!-- Icon Container -->
                                                        <div style="display: flex !important; justify-content: center !important; align-items: center !important; margin-bottom: 8px !important; width: 100% !important;">
                                                            <div class="relative" style="display: inline-block !important; position: relative !important;">
                                                                <span class="material-icons transition-all duration-300 group-hover:scale-110" style="color: {{ $folder->color ?? '#3B82F6' }}; font-size: 40px !important; line-height: 1 !important; display: block !important; text-align: center !important; margin: 0 auto !important;">folder</span>
                                                                @if($folder->is_starred)
                                                                    <div class="absolute" style="top: -4px !important; right: -4px !important;">
                                                                        <span class="material-icons bg-white rounded-full" style="font-size: 12px !important; line-height: 1 !important; color: #fbbf24 !important; padding: 2px !important;">star</span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <!-- Folder Name -->
                                                        <h4 class="group-hover:text-blue-700 transition-colors duration-200" style="font-size: 13px !important; font-weight: 500 !important; color: #111827 !important; text-align: center !important; margin: 0 0 3px 0 !important; padding: 0 4px !important; width: 100% !important; overflow: hidden !important; text-overflow: ellipsis !important; white-space: nowrap !important;">{{ $folder->name }}</h4>
                                                        <!-- Item Count and Masjid Info -->
                                                        <p style="font-size: 11px !important; color: #6b7280 !important; text-align: center !important; margin: 0 !important; padding: 0 !important; width: 100% !important;">
                                                            {{ $folder->getTotalDocuments() }} item
                                                            @if(auth()->user()->isSuperAdmin() && $folder->masjid)
                                                                <br><span style="font-size: 10px !important; color: #9ca3af !important;">{{ $folder->masjid->name }}</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                    
                                                    <!-- Hover overlay -->
                                                    <div class="absolute inset-0 bg-blue-50 opacity-0 group-hover:opacity-30 transition-opacity duration-300"></div>
                                                    
                                                    <!-- Action Menu -->
                                                    <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-all duration-200 z-10">
                                                        <div class="relative" x-data="{ open: false }">
                                                            <button @click.stop="open = !open" class="p-1.5 rounded-full bg-white shadow-sm hover:bg-gray-50 border border-gray-200">
                                                                <span class="material-icons text-sm text-gray-600">more_vert</span>
                                                            </button>
                                                            <!-- Comprehensive Context Menu -->
                                                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-1 w-56 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50" style="z-index: 9999 !important;">

                                                                <!-- Open with submenu -->
                                                                <div class="relative" x-data="{ openWith: false }">
                                                                    <button @mouseenter="openWith = true" @mouseleave="openWith = false" class="flex items-center justify-between w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                                        <div class="flex items-center">
                                                                            <span class="material-icons text-sm mr-3">open_with</span>
                                                                            Open with
                                                                        </div>
                                                                        <span class="material-icons text-sm">chevron_right</span>
                                                                    </button>
                                                                    <div x-show="openWith" @mouseenter="openWith = true" @mouseleave="openWith = false" class="absolute left-full top-0 ml-1 w-48 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50">
                                                                        <button onclick="openFolder({{ $folder->id }})" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                                            <span class="material-icons text-sm mr-3">visibility</span>
                                                                            Pratonton
                                                                        </button>
                                                                        <button onclick="openFolderNewTab({{ $folder->id }})" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                                            <span class="material-icons text-sm mr-3">open_in_new</span>
                                                                            Buka dalam tab baru
                                                                        </button>
                                                                    </div>
                                                                </div>

                                                                <div class="border-t border-gray-100 my-1"></div>

                                                                <!-- Download -->
                                                                <button onclick="downloadFolder({{ $folder->id }})" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                                    <span class="material-icons text-sm mr-3">download</span>
                                                                    Muat turun
                                                                </button>

                                                                <!-- Rename -->
                                                                <button onclick="renameFolder({{ $folder->id }}, '{{ $folder->name }}')" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                                    <span class="material-icons text-sm mr-3">edit</span>
                                                                    Namakan semula
                                                                </button>

                                                                <!-- Make a copy -->
                                                                <button onclick="copyFolder({{ $folder->id }})" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                                    <span class="material-icons text-sm mr-3">content_copy</span>
                                                                    Buat salinan
                                                                </button>

                                                                <div class="border-t border-gray-100 my-1"></div>

                                                                <!-- Share submenu -->
                                                                <div class="relative" x-data="{ share: false }">
                                                                    <button @mouseenter="share = true" @mouseleave="share = false" class="flex items-center justify-between w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                                        <div class="flex items-center">
                                                                            <span class="material-icons text-sm mr-3">share</span>
                                                                            Kongsi
                                                                        </div>
                                                                        <span class="material-icons text-sm">chevron_right</span>
                                                                    </button>
                                                                    <div x-show="share" @mouseenter="share = true" @mouseleave="share = false" class="absolute left-full top-0 ml-1 w-48 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50">
                                                                        <button onclick="shareFolder({{ $folder->id }})" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                                            <span class="material-icons text-sm mr-3">person_add</span>
                                                                            Kongsi dengan orang lain
                                                                        </button>
                                                                        <button onclick="copyFolderLink({{ $folder->id }})" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                                            <span class="material-icons text-sm mr-3">link</span>
                                                                            Salin pautan
                                                                        </button>
                                                                    </div>
                                                                </div>

                                                                <!-- Organize submenu -->
                                                                <div class="relative" x-data="{ organize: false }">
                                                                    <button @mouseenter="organize = true" @mouseleave="organize = false" class="flex items-center justify-between w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                                        <div class="flex items-center">
                                                                            <span class="material-icons text-sm mr-3">drive_file_move</span>
                                                                            Atur
                                                                        </div>
                                                                        <span class="material-icons text-sm">chevron_right</span>
                                                                    </button>
                                                                    <div x-show="organize" @mouseenter="organize = true" @mouseleave="organize = false" class="absolute left-full top-0 ml-1 w-48 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50">
                                                                        <button onclick="moveFolder({{ $folder->id }})" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                                            <span class="material-icons text-sm mr-3">drive_file_move</span>
                                                                            Pindah
                                                                        </button>
                                                                        <button onclick="addFolderShortcut({{ $folder->id }})" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                                            <span class="material-icons text-sm mr-3">add_link</span>
                                                                            Tambah pintasan
                                                                        </button>
                                                                        <button onclick="toggleStar('folder', {{ $folder->id }})" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                                            <span class="material-icons text-sm mr-3">{{ $folder->is_starred ? 'star' : 'star_border' }}</span>
                                                                            {{ $folder->is_starred ? 'Buang dari bintang' : 'Tambah ke bintang' }}
                                                                        </button>
                                                                    </div>
                                                                </div>

                                                                <!-- File information submenu -->
                                                                <div class="relative" x-data="{ info: false }">
                                                                    <button @mouseenter="info = true" @mouseleave="info = false" class="flex items-center justify-between w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                                        <div class="flex items-center">
                                                                            <span class="material-icons text-sm mr-3">info</span>
                                                                            Maklumat folder
                                                                        </div>
                                                                        <span class="material-icons text-sm">chevron_right</span>
                                                                    </button>
                                                                    <div x-show="info" @mouseenter="info = true" @mouseleave="info = false" class="absolute left-full top-0 ml-1 w-48 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50">
                                                                        <button onclick="showFolderDetails({{ $folder->id }})" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                                            <span class="material-icons text-sm mr-3">info</span>
                                                                            Butiran
                                                                        </button>
                                                                        <button onclick="showFolderActivity({{ $folder->id }})" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                                            <span class="material-icons text-sm mr-3">history</span>
                                                                            Aktiviti
                                                                        </button>
                                                                    </div>
                                                                </div>

                                                                <div class="border-t border-gray-100 my-1"></div>

                                                                <!-- Remove/Delete -->
                                                                @if(auth()->user()->hasPermission('documents', 'delete'))
                                                                    <button onclick="deleteFolder({{ $folder->id }})" class="flex items-center w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                                        <span class="material-icons text-sm mr-3">delete</span>
                                                                        Buang
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Documents Section -->
                                @if($documents->count() > 0)
                                    <div class="mb-8">
                                        <h3 class="text-sm font-semibold text-gray-600 mb-4 px-1">Dokumen</h3>





                                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-8 gap-4" style="display: grid !important; visibility: visible !important; opacity: 1 !important;">
                                            @foreach($documents as $document)
                                                <div class="file-item group relative bg-white rounded-xl border border-gray-200 hover:border-blue-300 hover:shadow-lg transition-all duration-300 cursor-pointer overflow-hidden" onclick="window.location.href='{{ route('documents.show', $document) }}'" style="display: block !important; visibility: visible !important; opacity: 1 !important; position: relative !important;">
                                                    <!-- Document Preview/Icon Container -->
                                                    <div class="p-4 text-center">
                                                        <div class="flex justify-center mb-3">
                                                            <div class="relative">
                                                                @if($document->isImage() && $document->preview_url)
                                                                    <img src="{{ $document->preview_url }}" alt="{{ $document->name }}" class="w-12 h-12 object-cover rounded-lg border border-gray-200 group-hover:scale-105 transition-transform duration-300">
                                                                @else
                                                                    <div class="w-12 h-12 flex items-center justify-center bg-gray-50 rounded-lg group-hover:bg-gray-100 transition-colors duration-300">
                                                                        <span class="material-icons text-2xl transition-all duration-300 group-hover:scale-110
                                                                            @switch($document->file_extension)
                                                                                @case('pdf') text-red-500 @break
                                                                                @case('doc') text-blue-600 @break
                                                                                @case('docx') text-blue-600 @break
                                                                                @case('xls') text-green-600 @break
                                                                                @case('xlsx') text-green-600 @break
                                                                                @case('ppt') text-orange-500 @break
                                                                                @case('pptx') text-orange-500 @break
                                                                                @case('txt') text-gray-600 @break
                                                                                @case('rtf') text-gray-600 @break
                                                                                @case('csv') text-green-500 @break
                                                                                @case('zip') text-purple-500 @break
                                                                                @case('rar') text-purple-500 @break
                                                                                @case('7z') text-purple-500 @break
                                                                                @case('jpg') text-pink-500 @break
                                                                                @case('jpeg') text-pink-500 @break
                                                                                @case('png') text-pink-500 @break
                                                                                @case('gif') text-pink-500 @break
                                                                                @case('bmp') text-pink-500 @break
                                                                                @case('svg') text-pink-500 @break
                                                                                @case('webp') text-pink-500 @break
                                                                                @case('mp4') text-red-600 @break
                                                                                @case('avi') text-red-600 @break
                                                                                @case('mov') text-red-600 @break
                                                                                @case('wmv') text-red-600 @break
                                                                                @case('mp3') text-indigo-500 @break
                                                                                @case('wav') text-indigo-500 @break
                                                                                @case('flac') text-indigo-500 @break
                                                                                @case('html') text-orange-600 @break
                                                                                @case('css') text-blue-500 @break
                                                                                @case('js') text-yellow-500 @break
                                                                                @case('json') text-yellow-600 @break
                                                                                @case('xml') text-orange-400 @break
                                                                                @default text-gray-400
                                                                            @endswitch">
                                                                            @switch($document->file_extension)
                                                                                @case('pdf') picture_as_pdf @break
                                                                                @case('doc') description @break
                                                                                @case('docx') description @break
                                                                                @case('xls') table_chart @break
                                                                                @case('xlsx') table_chart @break
                                                                                @case('csv') table_chart @break
                                                                                @case('ppt') slideshow @break
                                                                                @case('pptx') slideshow @break
                                                                                @case('txt') text_snippet @break
                                                                                @case('rtf') text_snippet @break
                                                                                @case('zip') folder_zip @break
                                                                                @case('rar') folder_zip @break
                                                                                @case('7z') folder_zip @break
                                                                                @case('jpg') image @break
                                                                                @case('jpeg') image @break
                                                                                @case('png') image @break
                                                                                @case('gif') image @break
                                                                                @case('bmp') image @break
                                                                                @case('svg') image @break
                                                                                @case('webp') image @break
                                                                                @case('mp4') movie @break
                                                                                @case('avi') movie @break
                                                                                @case('mov') movie @break
                                                                                @case('wmv') movie @break
                                                                                @case('mp3') music_note @break
                                                                                @case('wav') music_note @break
                                                                                @case('flac') music_note @break
                                                                                @case('html') code @break
                                                                                @case('css') code @break
                                                                                @case('js') code @break
                                                                                @case('json') code @break
                                                                                @case('xml') code @break
                                                                                @default insert_drive_file
                                                                            @endswitch
                                                                        </span>
                                                                    </div>
                                                                @endif
                                                                
                                                                <!-- Star indicator -->
                                                                @if($document->is_starred)
                                                                    <div class="absolute -top-1 -right-1">
                                                                        <span class="material-icons text-sm text-yellow-500 bg-white rounded-full p-0.5">star</span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <h4 class="text-sm font-medium text-gray-900 truncate px-1 group-hover:text-blue-700 transition-colors duration-200">{{ $document->name }}</h4>
                                                        <p class="text-xs text-gray-500 mt-1">
                                                            {{ $document->file_size_human }} • {{ strtoupper($document->file_extension) }}
                                                            @if(auth()->user()->isSuperAdmin() && $document->masjid)
                                                                <br><span class="text-xs text-gray-400">{{ $document->masjid->name }}</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                    
                                                    <!-- Hover overlay -->
                                                    <div class="absolute inset-0 bg-blue-50 opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
                                                    
                                                    <!-- Action Menu -->
                                                    <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-all duration-200 z-10">
                                                        <div class="relative" x-data="{ open: false }">
                                                            <button @click.stop="open = !open" class="p-1.5 rounded-full bg-white shadow-sm hover:bg-gray-50 border border-gray-200">
                                                                <span class="material-icons text-sm text-gray-600">more_vert</span>
                                                            </button>
                                                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-20">
                                                                <a href="{{ $document->download_url }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                                    <span class="material-icons text-sm mr-3">download</span>
                                                                    Muat Turun
                                                                </a>
                                                                @if($document->isPreviewable() && $document->preview_url)
                                                                    <button onclick="previewDocument({{ $document->id }})" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                                        <span class="material-icons text-sm mr-3">visibility</span>
                                                                        Pratonton
                                                                    </button>
                                                                @endif
                                                                <button onclick="toggleStar('document', {{ $document->id }})" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                                    <span class="material-icons text-sm mr-3">{{ $document->is_starred ? 'star' : 'star_border' }}</span>
                                                                    {{ $document->is_starred ? 'Buang dari Kegemaran' : 'Tambah ke Kegemaran' }}
                                                                </button>
                                                                @if(auth()->user()->hasPermission('documents', 'update'))
                                                                    <a href="{{ route('documents.edit', $document) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                                        <span class="material-icons text-sm mr-3">edit</span>
                                                                        Edit Dokumen
                                                                    </a>
                                                                @endif
                                                                @if(auth()->user()->hasPermission('documents', 'share'))
                                                                    <button onclick="shareDocument({{ $document->id }})" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                                        <span class="material-icons text-sm mr-3">share</span>
                                                                        Kongsi Dokumen
                                                                    </button>
                                                                @endif
                                                                @if(auth()->user()->hasPermission('documents', 'delete'))
                                                                    <div class="border-t border-gray-100 my-1"></div>
                                                                    <button onclick="deleteDocument({{ $document->id }})" class="flex items-center w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                                        <span class="material-icons text-sm mr-3">delete</span>
                                                                        Padam Dokumen
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- Pagination -->
                                        @if($documents->hasPages())
                                            <div class="mt-8 flex justify-center">
                                                <div class="bg-white rounded-lg border border-gray-200 p-1">
                                                    {{ $documents->appends(request()->query())->links() }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @else
                                <!-- Empty State - Perfect Middle Center -->
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="text-center">


                                        @if(request('search'))
                                            <!-- Search Empty State -->
                                            <div class="mb-6">
                                                <span class="material-icons text-6xl text-gray-300 mb-4">search_off</span>
                                            </div>
                                            <h3 class="text-lg font-medium text-gray-900 mb-3">
                                                Tiada hasil carian
                                            </h3>
                                            <p class="text-gray-500 max-w-sm mx-auto">
                                                Cuba cari dengan kata kunci yang berbeza atau tapis menggunakan jenis fail yang lain.
                                            </p>
                                        @else
                                            <!-- Empty Folder State -->
                                            <div class="mb-6">
                                                <img src="{{ asset('images/document-empty.png') }}" alt="Fail Kosong" class="mx-auto mb-4 opacity-60" style="width: 80px !important; height: 80px !important;">
                                            </div>
                                            <h3 class="text-lg font-medium text-gray-900 mb-3">Fail Kosong</h3>
                                            <p class="text-gray-500 max-w-md mx-auto text-sm leading-relaxed">
                                                Mula muat naik dokumen atau cipta folder baru untuk menyusun fail anda.
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rename Modal -->
    <div id="renameModal" class="fixed inset-0 overflow-y-auto h-full w-full hidden z-50" style="background-color: rgba(0, 0, 0, 0.3);">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <!-- Icon -->
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                    <span class="material-icons text-blue-600 text-xl">edit</span>
                </div>

                <!-- Title -->
                <h3 class="text-lg font-medium text-gray-900 mt-4" style="font-family: 'Poppins', sans-serif;">Namakan Semula</h3>

                <!-- Input Field -->
                <div class="mt-4 px-7">
                    <form id="renameForm" onsubmit="submitRename(event)">
                        <input
                            type="text"
                            id="renameInput"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            style="font-family: 'Poppins', sans-serif; font-size: 12px; color: #000000 !important; background-color: #ffffff !important;"
                            placeholder="Masukkan nama baru"
                            required
                        >
                    </form>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-center gap-3 mt-6">
                    <button
                        type="button"
                        onclick="closeRenameModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300"
                        style="font-family: 'Poppins', sans-serif;"
                    >
                        Batal
                    </button>
                    <button
                        type="button"
                        onclick="submitRename(event)"
                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        style="font-family: 'Poppins', sans-serif;"
                    >
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>

    <x-footer />

    <!-- Context Menu -->
    <div id="contextMenu" class="fixed bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50 hidden min-w-[200px]">

        <!-- Document-specific options -->
        <div id="documentOptions">
            <!-- Open with submenu -->
            <div class="context-menu-item group relative">
                <div class="flex items-center justify-between px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer">
                    <div class="flex items-center">
                        <span class="material-icons text-sm mr-3">open_with</span>
                        <span>Open with</span>
                    </div>
                    <span class="material-icons text-sm text-gray-400">chevron_right</span>
                </div>
                <!-- Submenu -->
                <div class="context-submenu bg-white rounded-lg shadow-xl border border-gray-200 py-2 hidden group-hover:block min-w-[180px]">
                    <div class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer flex items-center" onclick="previewItem()">
                        <span class="material-icons text-sm mr-3">visibility</span>
                        <span>Pratonton</span>
                    </div>
                    <div class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer flex items-center" onclick="openInNewTab()">
                        <span class="material-icons text-sm mr-3">open_in_new</span>
                        <span>Buka dalam tab baru</span>
                    </div>
                </div>
            </div>

            <!-- Make a copy -->
            <div class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer flex items-center" onclick="copyItem()">
                <span class="material-icons text-sm mr-3">content_copy</span>
                <span>Buat salinan</span>
            </div>

            <div class="border-t border-gray-100 my-1"></div>
        </div>

        <!-- Common options for both documents and folders -->
        <div id="commonOptions">
            <!-- Download -->
            <div class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer flex items-center" onclick="downloadItem()">
                <span class="material-icons text-sm mr-3">download</span>
                <span>Muat turun</span>
            </div>

            <!-- Rename -->
            <div class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer flex items-center" onclick="renameItem()">
                <span class="material-icons text-sm mr-3">edit</span>
                <span>Namakan semula</span>
            </div>

            <div class="border-t border-gray-100 my-1"></div>

            <!-- Share submenu -->
            <div class="context-menu-item group relative">
                <div class="flex items-center justify-between px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer">
                    <div class="flex items-center">
                        <span class="material-icons text-sm mr-3">share</span>
                        <span>Kongsi</span>
                    </div>
                    <span class="material-icons text-sm text-gray-400">chevron_right</span>
                </div>
                <!-- Submenu -->
                <div class="context-submenu bg-white rounded-lg shadow-xl border border-gray-200 py-2 hidden group-hover:block min-w-[180px]">
                    <div class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer flex items-center" onclick="shareItem()">
                        <span class="material-icons text-sm mr-3">person_add</span>
                        <span>Kongsi dengan orang lain</span>
                    </div>
                    <div class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer flex items-center" onclick="copyLink()">
                        <span class="material-icons text-sm mr-3">link</span>
                        <span>Salin pautan</span>
                    </div>
                </div>
            </div>

            <!-- Organize submenu -->
            <div class="context-menu-item group relative">
                <div class="flex items-center justify-between px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer">
                    <div class="flex items-center">
                        <span class="material-icons text-sm mr-3">folder_open</span>
                        <span>Atur</span>
                    </div>
                    <span class="material-icons text-sm text-gray-400">chevron_right</span>
                </div>
                <!-- Submenu -->
                <div class="context-submenu bg-white rounded-lg shadow-xl border border-gray-200 py-2 hidden group-hover:block min-w-[220px] max-w-[220px]">
                    <div class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer flex items-center" onclick="moveItem()">
                        <span class="material-icons text-sm mr-3">drive_file_move</span>
                        <span>Pindah</span>
                    </div>
                    <div class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer flex items-center" onclick="addShortcut()">
                        <span class="material-icons text-sm mr-3">shortcut</span>
                        <span>Tambah pintasan</span>
                    </div>
                    <div class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer flex items-center" onclick="toggleStarFromMenu()">
                        <span class="material-icons text-sm mr-3" id="starIcon">star_border</span>
                        <span id="starText">Tambah ke bintang</span>
                    </div>

                    <!-- Folder Color Picker - Only for folders -->
                    <div id="folderColorSection" class="border-t border-gray-100 mt-2 pt-3 px-4 pb-3">
                        <div class="text-sm font-medium text-gray-700 mb-3" style="font-family: 'Poppins', sans-serif;">Folder color</div>
                        <div id="colorPickerGrid" class="grid grid-cols-8 gap-2">
                            <!-- Colors will be dynamically generated with proper tick marks -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-100 my-1"></div>

            <!-- Information submenu - different text for folders vs documents -->
            <div class="context-menu-item group relative">
                <div class="flex items-center justify-between px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer">
                    <div class="flex items-center">
                        <span class="material-icons text-sm mr-3">info</span>
                        <span id="informationText">File information</span>
                    </div>
                    <span class="material-icons text-sm text-gray-400">chevron_right</span>
                </div>
                <!-- Submenu -->
                <div class="context-submenu bg-white rounded-lg shadow-xl border border-gray-200 py-2 hidden group-hover:block min-w-[180px]">
                    <div class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer flex items-center" onclick="showDetails()">
                        <span class="material-icons text-sm mr-3">info</span>
                        <span>Butiran</span>
                    </div>
                    <div class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer flex items-center" onclick="showActivity()">
                        <span class="material-icons text-sm mr-3">history</span>
                        <span>Aktiviti</span>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-100 my-1"></div>

            <!-- Remove/Restore actions - dynamic based on current view -->
            <div id="removeActions">
                <!-- Default view - Move to trash -->
                <div id="moveToTrashAction" class="px-4 py-2 text-sm text-red-600 hover:bg-red-50 cursor-pointer flex items-center" onclick="removeItem()">
                    <span class="material-icons text-sm mr-3">delete</span>
                    <span>Pindah ke tong sampah</span>
                    <span class="ml-auto text-xs text-red-400">Padam</span>
                </div>

                <!-- Trash/Spam view - Restore and permanent delete options -->
                <div id="restoreAction" class="px-4 py-2 text-sm text-green-600 hover:bg-green-50 cursor-pointer flex items-center hidden" onclick="restoreItem()">
                    <span class="material-icons text-sm mr-3">restore</span>
                    <span>Pulihkan</span>
                </div>

                <div id="permanentDeleteAction" class="px-4 py-2 text-sm text-red-600 hover:bg-red-50 cursor-pointer flex items-center hidden" onclick="permanentDeleteItem()">
                    <span class="material-icons text-sm mr-3">delete_forever</span>
                    <span>Padam kekal</span>
                </div>

                <!-- Mark as spam option - only for documents -->
                <div id="markSpamAction" class="px-4 py-2 text-sm text-orange-600 hover:bg-orange-50 cursor-pointer flex items-center hidden" onclick="moveToSpam()">
                    <span class="material-icons text-sm mr-3">report</span>
                    <span>Tandakan sebagai spam</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals will be added here -->
    
    <script>
        // Helper function to format file size
        function formatFileSize(bytes) {
            const units = ['B', 'KB', 'MB', 'GB'];
            let size = bytes;
            let unitIndex = 0;

            while (size >= 1024 && unitIndex < units.length - 1) {
                size /= 1024;
                unitIndex++;
            }

            return Math.round(size * 100) / 100 + ' ' + units[unitIndex];
        }

        // Helper function to format date and time in Malaysia timezone
        function formatDateTime(dateString) {
            const date = new Date(dateString);

            // Format for Malaysia timezone (UTC+8)
            const options = {
                timeZone: 'Asia/Kuala_Lumpur',
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                hour12: false // Use 24-hour format
            };

            // Get formatted date and time
            const formatted = date.toLocaleString('en-GB', options);

            // Convert to dd/mm/yyyy hh:mm format
            return formatted.replace(',', '');
        }
        
        // New Folder Modal Functions
        function openNewFolderModal() {
            const modal = document.getElementById('newFolderModal');
            const folderNameInput = document.getElementById('folderName');

            modal.classList.remove('hidden');

            // Focus on input after modal is shown
            setTimeout(() => {
                folderNameInput.focus();
            }, 100);
        }

        function closeNewFolderModal() {
            const modal = document.getElementById('newFolderModal');
            const folderNameInput = document.getElementById('folderName');
            const folderColorInput = document.getElementById('folderColor');

            modal.classList.add('hidden');
            folderNameInput.value = '';
            folderColorInput.value = '#3B82F6';
        }

        function createNewFolder() {
            const folderName = document.getElementById('folderName').value.trim();
            const folderColor = document.getElementById('folderColor').value;

            if (!folderName) {
                alert('Sila masukkan nama folder');
                return;
            }

            // Create form data
            const formData = new FormData();
            formData.append('name', folderName);
            formData.append('color', folderColor);
            formData.append('parent_folder_id', '{{ $currentFolder?->id ?? "" }}');
            formData.append('_token', '{{ csrf_token() }}');

            // Send request
            fetch('{{ route("document-folders.store") }}', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeNewFolderModal();
                    location.reload(); // Refresh page to show new folder
                } else {
                    alert(data.message || 'Ralat berlaku semasa membuat folder');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ralat berlaku semasa membuat folder');
            });
        }
        
        function editFolder(folderId) {
            // TODO: Implement edit folder modal
            alert('Edit folder modal - to be implemented');
        }
        
        function deleteFolder(folderId) {
            // TODO: Implement delete confirmation
            if (confirm('Adakah anda pasti ingin memadam folder ini?')) {
                // TODO: Send delete request
                alert('Delete folder - to be implemented');
            }
        }
        
        function toggleStar(type, id) {
            const endpoint = type === 'document'
                ? `/documents/${id}/star`
                : `/folders/${id}/star`;

            fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showNotification(data.message, 'success');
                    // Reload page to update star status
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showNotification('Ralat berlaku semasa mengemaskini status bintang', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Ralat berlaku semasa mengemaskini status bintang', 'error');
            });
        }

        // Notification function
        function showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;

            // Set colors based on type
            if (type === 'success') {
                notification.className += ' bg-green-500 text-white';
            } else if (type === 'error') {
                notification.className += ' bg-red-500 text-white';
            } else {
                notification.className += ' bg-blue-500 text-white';
            }

            notification.innerHTML = `
                <div class="flex items-center">
                    <span class="material-icons text-sm mr-2">${type === 'success' ? 'check_circle' : type === 'error' ? 'error' : 'info'}</span>
                    <span style="font-family: 'Poppins', sans-serif; font-size: 12px;">${message}</span>
                </div>
            `;

            document.body.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            // Auto remove after 3 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }

        function previewDocument(documentId) {
            // TODO: Implement document preview modal
            alert('Document preview - to be implemented');
        }
        
        function shareDocument(documentId) {
            // TODO: Implement share modal
            alert('Share document modal - to be implemented');
        }
        
        function deleteDocument(documentId) {
            // TODO: Implement delete confirmation
            if (confirm('Adakah anda pasti ingin memadam dokumen ini?')) {
                // TODO: Send delete request
                alert('Delete document - to be implemented');
            }
        }

        // View Toggle Functions
        function switchToListView() {
            const listBtn = document.getElementById('listViewBtn');
            const gridBtn = document.getElementById('gridViewBtn');
            const fileGrid = document.getElementById('fileGrid');

            // Update button states
            listBtn.classList.remove('text-gray-500', 'hover:text-gray-700', 'hover:bg-white');
            listBtn.classList.add('text-blue-600', 'bg-white', 'shadow-sm');

            gridBtn.classList.remove('text-blue-600', 'bg-white', 'shadow-sm');
            gridBtn.classList.add('text-gray-500', 'hover:text-gray-700', 'hover:bg-white');

            // Get folders and documents data first
            const folders = @json($folders->toArray());
            const documents = @json($documents->items()); // FIX: Get items() from paginated collection

            // Check if we have any data
            const hasData = (Array.isArray(folders) && folders.length > 0) || (Array.isArray(documents) && documents.length > 0);

            if (hasData) {
                // Create table layout for list view
                fileGrid.innerHTML = `
                    <div class="bg-white rounded-xs border border-gray-200 overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saiz</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemilik</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarikh Dicipta</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody id="listTableBody" class="bg-white divide-y divide-gray-200">
                            </tbody>
                        </table>
                    </div>
                `;

                // Populate table with folders and documents
                populateListTable();
            } else {
                // Show empty state for list view
                fileGrid.innerHTML = `
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-center">
                            <div class="mb-6">
                                <img src="/images/document-empty.png" alt="Fail Kosong" class="mx-auto mb-4 opacity-60" style="width: 80px !important; height: 80px !important;">
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-3">Fail Kosong</h3>
                            <p class="text-gray-500 max-w-md mx-auto text-sm leading-relaxed">Mula muat naik dokumen atau cipta folder baru untuk menyusun fail anda.</p>
                        </div>
                    </div>
                `;
            }

            // Store preference
            localStorage.setItem('documentsViewMode', 'list');
        }

        function switchToGridView() {
            const listBtn = document.getElementById('listViewBtn');
            const gridBtn = document.getElementById('gridViewBtn');
            const fileGrid = document.getElementById('fileGrid');

            // Update button states
            gridBtn.classList.remove('text-gray-500', 'hover:text-gray-700', 'hover:bg-white');
            gridBtn.classList.add('text-blue-600', 'bg-white', 'shadow-sm');

            listBtn.classList.remove('text-blue-600', 'bg-white', 'shadow-sm');
            listBtn.classList.add('text-gray-500', 'hover:text-gray-700', 'hover:bg-white');

            // Restore original grid layout dynamically
            populateGridLayout();

            // Store preference
            localStorage.setItem('documentsViewMode', 'grid');
        }

        // Sort Dropdown Toggle Function
        function toggleSortDropdown() {
            const dropdown = document.getElementById('sortDropdown');
            const sortBtn = document.getElementById('sortBtn');

            if (dropdown && dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
                sortBtn.classList.add('text-blue-600', 'bg-white', 'shadow-sm');
                sortBtn.classList.remove('text-gray-500', 'hover:text-gray-700', 'hover:bg-white');
            } else if (dropdown) {
                dropdown.classList.add('hidden');
                sortBtn.classList.remove('text-blue-600', 'bg-white', 'shadow-sm');
                sortBtn.classList.add('text-gray-500', 'hover:text-gray-700', 'hover:bg-white');
            }
        }

        // Close sort dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('sortDropdown');
            const sortBtn = document.getElementById('sortBtn');

            if (!sortBtn.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
                sortBtn.classList.remove('text-blue-600', 'bg-white', 'shadow-sm');
                sortBtn.classList.add('text-gray-500', 'hover:text-gray-700', 'hover:bg-white');
            }
        });

        // Function to populate list table
        function populateListTable() {
            const tableBody = document.getElementById('listTableBody');
            if (!tableBody) return;

            // Get folders and documents data from current page (convert Collections to arrays)
            const folders = @json($folders->toArray());
            const documents = @json($documents->items()); // FIX: Get items() from paginated collection

            let tableRows = '';

            // Add folders (check if folders is array and has data)
            if (Array.isArray(folders) && folders.length > 0) {
                Object.values(folders).forEach(folder => {
                    // Format folder size using total_size computed property
                    const folderSize = folder.total_size ? formatFileSize(folder.total_size) : '—';

                    // Determine owner - Super Admin if masjid_id is null, otherwise show masjid nama
                    const owner = folder.masjid_id ? (folder.masjid ? folder.masjid.nama : 'Masjid') : 'Super Admin';

                    tableRows += `
                        <tr class="hover:bg-gray-50 cursor-pointer group" onclick="openFolder(${folder.id})" oncontextmenu="showContextMenu(event, 'folder', ${folder.id}, '${folder.name}', ${folder.is_starred || false}, '${folder.color || '#3B82F6'}')">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="material-icons text-xl mr-3" style="color: ${folder.color || '#3B82F6'}">folder</span>
                                    <div class="text-sm font-medium text-gray-900">${folder.name}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${folderSize}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${owner}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDateTime(folder.created_at)}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <button class="text-gray-400 hover:text-gray-600 p-1 rounded-full hover:bg-gray-100 transition-all duration-200" onclick="event.stopPropagation(); showContextMenu(event, 'folder', ${folder.id}, '${folder.name}', ${folder.is_starred || false}, '${folder.color || '#3B82F6'}')">
                                    <span class="material-icons text-sm">more_vert</span>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }

            // Add documents (check if documents is array and has data)
            if (Array.isArray(documents) && documents.length > 0) {
                Object.values(documents).forEach(document => {
                    const fileSize = document.file_size ? formatFileSize(document.file_size) : '—';

                    // Determine owner - Super Admin if masjid_id is null, otherwise show masjid nama
                    const owner = document.masjid_id ? (document.masjid ? document.masjid.nama : 'Masjid') : 'Super Admin';

                    tableRows += `
                        <tr class="hover:bg-gray-50 cursor-pointer group" onclick="openDocument(${document.id})" oncontextmenu="showContextMenu(event, 'document', ${document.id}, '${document.name}', ${document.is_starred || false})">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="material-icons text-xl mr-3 text-blue-600">description</span>
                                    <div class="text-sm font-medium text-gray-900">${document.name}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${fileSize}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${owner}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDateTime(document.created_at)}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <button class="text-gray-400 hover:text-gray-600 p-1 rounded-full hover:bg-gray-100 transition-all duration-200" onclick="event.stopPropagation(); showContextMenu(event, 'document', ${document.id}, '${document.name}', ${document.is_starred || false})">
                                    <span class="material-icons text-sm">more_vert</span>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }

            tableBody.innerHTML = tableRows;
        }

        // Function to populate grid layout
        function populateGridLayout() {
            const fileGrid = document.getElementById('fileGrid');
            if (!fileGrid) return;

            // Get folders and documents data (convert Collections to arrays)
            const folders = @json($folders->toArray());
            const documents = @json($documents->items()); // FIX: Get items() from paginated collection



            let gridContent = '';

            // Check if we have any data
            const hasFolders = Array.isArray(folders) && folders.length > 0;
            const hasDocuments = Array.isArray(documents) && documents.length > 0;


            // Add folders section if folders exist
            if (hasFolders) {
                gridContent += `
                    <div class="mb-8">
                        <h3 class="text-sm font-semibold text-gray-600 mb-4 px-1">Folder</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-8 gap-4">
                `;

                folders.forEach(folder => {
                    const itemCount = folder.total_documents || 0;
                    const itemText = itemCount === 1 ? 'item' : 'item';

                    gridContent += `
                        <div class="folder-item group relative bg-gray-50 rounded-lg border border-gray-200 hover:border-blue-300 hover:shadow-md transition-all duration-200 cursor-pointer overflow-hidden" onclick="openFolder(${folder.id})" oncontextmenu="showContextMenu(event, 'folder', ${folder.id}, '${folder.name}', ${folder.is_starred || false}, '${folder.color || '#3B82F6'}')">
                            <!-- Google Drive style folder header -->
                            <div class="flex items-center justify-between p-3 bg-white border-b border-gray-100">
                                <div class="flex items-center space-x-3 flex-1 min-w-0">
                                    <span class="material-icons text-lg flex-shrink-0" style="color: ${folder.color || '#3B82F6'}">folder</span>
                                    <h4 class="text-sm font-medium text-gray-900 truncate">${folder.name}</h4>
                                </div>
                                <div class="flex-shrink-0">
                                    <button class="three-dot-menu opacity-0 group-hover:opacity-100 p-1 rounded-full hover:bg-gray-100 transition-all duration-200" onclick="event.stopPropagation(); showContextMenu(event, 'folder', ${folder.id}, '${folder.name}', ${folder.is_starred || false}, '${folder.color || '#3B82F6'}')">
                                        <span class="material-icons text-gray-600 text-lg">more_vert</span>
                                    </button>
                                </div>
                            </div>
                            <!-- Folder content area -->
                            <div class="p-4 text-center bg-gray-50">
                                <div class="text-xs text-gray-500">${itemCount} ${itemText}</div>
                            </div>
                        </div>
                    `;
                });

                gridContent += `
                        </div>
                    </div>
                `;
            }

            // Add documents section if documents exist
            if (hasDocuments) {
                gridContent += `
                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 mb-4 px-1">Dokumen</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-8 gap-4">
                `;

                documents.forEach(document => {
                    const fileSize = document.file_size ? formatFileSize(document.file_size) : '—';
                    const fileExtension = document.file_extension || '';
                    const fileIcon = getFileIcon(fileExtension);
                    const fileIconColor = getFileIconColor(fileExtension);

                    gridContent += `
                        <div class="file-item group relative bg-white rounded-lg border border-gray-200 hover:border-blue-300 hover:shadow-md transition-all duration-200 cursor-pointer overflow-hidden" onclick="openDocument(${document.id})" oncontextmenu="showContextMenu(event, 'document', ${document.id}, '${document.name}', ${document.is_starred || false})">
                            <!-- Google Drive style file header -->
                            <div class="flex items-center justify-between p-3 border-b border-gray-100">
                                <div class="flex items-center space-x-3 flex-1 min-w-0">
                                    <span class="material-icons text-lg ${fileIconColor} flex-shrink-0">${fileIcon}</span>
                                    <h4 class="text-sm font-medium text-gray-900 truncate">${document.name}</h4>
                                </div>
                                <div class="flex-shrink-0">
                                    <button class="three-dot-menu opacity-0 group-hover:opacity-100 p-1 rounded-full hover:bg-gray-100 transition-all duration-200" onclick="event.stopPropagation(); showContextMenu(event, 'document', ${document.id}, '${document.name}', ${document.is_starred || false})">
                                        <span class="material-icons text-gray-600 text-lg">more_vert</span>
                                    </button>
                                </div>
                            </div>
                            <!-- File preview/content area -->
                            <div class="p-4 text-center bg-gray-50 min-h-[120px] flex items-center justify-center">
                                <div class="text-center">
                                    <span class="material-icons text-4xl ${fileIconColor} mb-2 block">${fileIcon}</span>
                                    <div class="text-xs text-gray-500">${fileSize}</div>
                                </div>
                            </div>
                        </div>
                    `;
                });

                gridContent += `
                        </div>
                    </div>
                `;
            }

            // If no content, show empty state
            if (!hasFolders && !hasDocuments) {
                gridContent = `
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-center">
                            <div class="mb-6">
                                <img src="/images/document-empty.png" alt="Fail Kosong" class="mx-auto mb-4 opacity-60" style="width: 80px !important; height: 80px !important;">
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-3">Fail Kosong</h3>
                            <p class="text-gray-500 max-w-md mx-auto text-sm leading-relaxed">Mula muat naik dokumen atau cipta folder baru untuk menyusun fail anda.</p>
                        </div>
                    </div>
                `;
            }

            fileGrid.innerHTML = gridContent;
        }

        // Initialize view mode on page load
        function initializeViewMode() {
            const savedMode = localStorage.getItem('documentsViewMode') || 'grid';
            if (savedMode === 'list') {
                switchToListView();
            } else {
                switchToGridView();
            }
        }

        // Initialize modal event listeners when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            const newFolderModal = document.getElementById('newFolderModal');

            if (newFolderModal) {
                // Close modal when clicking outside
                newFolderModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeNewFolderModal();
                    }
                });
            }

            // Initialize view mode
            initializeViewMode();
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeNewFolderModal();
                hideContextMenu();
            }
        });

        // Context Menu Variables
        let contextMenuData = {
            type: null, // 'folder' or 'document'
            id: null,
            name: null,
            is_starred: false,
            color: null // For folders
        };

        // Centralized Context Menu System
        // This system works for both Grid Layout and List Layout
        // No duplicate code needed - same functions work everywhere

        // Context Menu Functions
        function showContextMenu(event, type, id, name, isStarred = false, color = null) {
            event.preventDefault();
            event.stopPropagation();

            const contextMenu = document.getElementById('contextMenu');
            contextMenuData = { type, id, name, is_starred: isStarred, color: color };

            // Update star status in menu
            updateStarMenuStatus(type, id);

            // Show menu temporarily to get actual dimensions
            contextMenu.style.visibility = 'hidden';
            contextMenu.classList.remove('hidden');

            // Get actual menu dimensions
            const menuRect = contextMenu.getBoundingClientRect();
            const menuWidth = menuRect.width;
            const menuHeight = menuRect.height;

            // Hide menu again
            contextMenu.classList.add('hidden');
            contextMenu.style.visibility = 'visible';

            // Get cursor position and window dimensions
            const x = event.clientX;
            const y = event.clientY;
            const windowWidth = window.innerWidth;
            const windowHeight = window.innerHeight;

            // Define minimum padding from screen edges
            const padding = 15;

            // Calculate optimal position with padding
            let menuX = x;
            let menuY = y;

            // Enhanced edge detection with better logic

            // Right edge check - if menu would overflow right side
            if (x + menuWidth + padding > windowWidth) {
                // Try positioning to the left of cursor
                menuX = x - menuWidth - 5;

                // If still overflows left side, clamp to left edge with padding
                if (menuX < padding) {
                    menuX = padding;
                }
            }

            // Left edge check - ensure minimum padding from left
            if (menuX < padding) {
                menuX = padding;
            }

            // Bottom edge check - if menu would overflow bottom
            if (y + menuHeight + padding > windowHeight) {
                // Try positioning above cursor
                menuY = y - menuHeight - 5;

                // If still overflows top, clamp to top edge with padding
                if (menuY < padding) {
                    menuY = padding;
                }
            }

            // Top edge check - ensure minimum padding from top
            if (menuY < padding) {
                menuY = padding;
            }

            // Final safety checks to absolutely ensure menu stays within bounds
            const maxX = windowWidth - menuWidth - padding;
            const maxY = windowHeight - menuHeight - padding;

            menuX = Math.max(padding, Math.min(menuX, maxX));
            menuY = Math.max(padding, Math.min(menuY, maxY));

            // Apply position and show menu
            contextMenu.style.left = menuX + 'px';
            contextMenu.style.top = menuY + 'px';
            contextMenu.classList.remove('hidden');

            // Debug logging (remove in production)
            console.log('Context Menu Positioning:', {
                cursor: { x, y },
                menu: { width: menuWidth, height: menuHeight },
                final: { x: menuX, y: menuY },
                window: { width: windowWidth, height: windowHeight },
                padding: padding
            });

            // Setup dynamic submenu positioning
            setupSubmenuPositioning(contextMenu, menuX, menuY);

            // Update context menu actions based on current view
            updateContextMenuActions();

            // Add click outside listener
            setTimeout(() => {
                document.addEventListener('click', hideContextMenu);
            }, 10);
        }

        function updateContextMenuActions() {
            const currentType = new URLSearchParams(window.location.search).get('type');

            // Get menu sections
            const documentOptions = document.getElementById('documentOptions');
            const informationText = document.getElementById('informationText');
            const folderColorSection = document.getElementById('folderColorSection');

            // Get action elements
            const moveToTrashAction = document.getElementById('moveToTrashAction');
            const restoreAction = document.getElementById('restoreAction');
            const permanentDeleteAction = document.getElementById('permanentDeleteAction');
            const markSpamAction = document.getElementById('markSpamAction');

            // Update menu based on item type
            if (contextMenuData.type === 'folder') {
                // FOLDER MENU - Hide document-specific options
                documentOptions.classList.add('hidden');
                informationText.textContent = 'Folder information';

                // Show folder color picker with current color highlighted
                if (folderColorSection) {
                    folderColorSection.classList.remove('hidden');
                    updateColorPicker();
                }

                // Hide spam option for folders (folders don't go to spam)
                markSpamAction.classList.add('hidden');
            } else {
                // DOCUMENT MENU - Show all document options
                documentOptions.classList.remove('hidden');
                informationText.textContent = 'File information';

                // Hide folder color picker for documents
                if (folderColorSection) {
                    folderColorSection.classList.add('hidden');
                }
            }

            // Hide all remove actions first
            moveToTrashAction.classList.add('hidden');
            restoreAction.classList.add('hidden');
            permanentDeleteAction.classList.add('hidden');
            if (contextMenuData.type === 'document') {
                markSpamAction.classList.add('hidden');
            }

            // Show appropriate actions based on current view
            if (currentType === 'trash') {
                // In trash view - show restore and permanent delete
                restoreAction.classList.remove('hidden');
                permanentDeleteAction.classList.remove('hidden');
            } else if (currentType === 'spam') {
                // In spam view - show restore and permanent delete
                restoreAction.classList.remove('hidden');
                permanentDeleteAction.classList.remove('hidden');
            } else {
                // Normal view - show move to trash
                moveToTrashAction.classList.remove('hidden');

                // Show mark as spam only for documents
                if (contextMenuData.type === 'document') {
                    markSpamAction.classList.remove('hidden');
                }
            }
        }

        function setupSubmenuPositioning(contextMenu, menuX, menuY) {
            const submenuItems = contextMenu.querySelectorAll('.context-menu-item');
            const windowWidth = window.innerWidth;
            const windowHeight = window.innerHeight;
            const padding = 15;

            submenuItems.forEach(item => {
                const submenu = item.querySelector('.context-submenu');
                if (submenu) {
                    // Reset positioning classes
                    item.classList.remove('submenu-left', 'submenu-up');

                    // Remove any existing event listeners to prevent duplicates
                    const newItem = item.cloneNode(true);
                    item.parentNode.replaceChild(newItem, item);

                    // Add fresh hover listener for dynamic positioning
                    newItem.addEventListener('mouseenter', function() {
                        const newSubmenu = newItem.querySelector('.context-submenu');
                        if (!newSubmenu) return;

                        // Temporarily show submenu to measure dimensions
                        newSubmenu.style.visibility = 'hidden';
                        newSubmenu.style.display = 'block';

                        const submenuRect = newSubmenu.getBoundingClientRect();
                        const submenuWidth = submenuRect.width;
                        const submenuHeight = submenuRect.height;

                        // Hide submenu again
                        newSubmenu.style.display = '';
                        newSubmenu.style.visibility = 'visible';

                        // Get item position
                        const itemRect = newItem.getBoundingClientRect();

                        // Check horizontal positioning
                        const submenuRightEdge = itemRect.right + submenuWidth + padding;
                        const submenuLeftEdge = itemRect.left - submenuWidth - padding;

                        if (submenuRightEdge > windowWidth && submenuLeftEdge >= 0) {
                            // Position submenu to the left if there's space
                            newItem.classList.add('submenu-left');
                        } else {
                            // Position submenu to the right (default)
                            newItem.classList.remove('submenu-left');
                        }

                        // Check vertical positioning
                        const submenuBottomEdge = itemRect.top + submenuHeight + padding;

                        if (submenuBottomEdge > windowHeight) {
                            // Position submenu upward if it would overflow bottom
                            newItem.classList.add('submenu-up');
                        } else {
                            newItem.classList.remove('submenu-up');
                        }
                    });
                }
            });
        }

        function hideContextMenu() {
            const contextMenu = document.getElementById('contextMenu');
            contextMenu.classList.add('hidden');
            document.removeEventListener('click', hideContextMenu);
        }

        function updateStarMenuStatus(type, id) {
            // This would check if item is starred and update the menu accordingly
            const starIcon = document.getElementById('starIcon');
            const starText = document.getElementById('starText');

            // Check if item is currently starred
            const isStarred = contextMenuData.is_starred || false;

            if (isStarred) {
                starIcon.textContent = 'star';
                starText.textContent = 'Buang dari bintang';
            } else {
                starIcon.textContent = 'star_border';
                starText.textContent = 'Tambah ke bintang';
            }
        }

        // Context Menu Actions
        function previewItem() {
            console.log('Preview:', contextMenuData);
            if (contextMenuData.type === 'document') {
                window.open(`/documents/${contextMenuData.id}/preview`, '_blank');
            }
            hideContextMenu();
        }

        function openInNewTab() {
            console.log('Open in new tab:', contextMenuData);
            if (contextMenuData.type === 'document') {
                window.open(`/documents/${contextMenuData.id}`, '_blank');
            } else if (contextMenuData.type === 'folder') {
                window.open(`/documents?folder=${contextMenuData.id}`, '_blank');
            }
            hideContextMenu();
        }

        function downloadItem() {
            console.log('Download:', contextMenuData);
            if (contextMenuData.type === 'document') {
                window.location.href = `/documents/${contextMenuData.id}/download`;
            } else if (contextMenuData.type === 'folder') {
                // Implement folder download as zip
                window.location.href = `/folders/${contextMenuData.id}/download`;
            }
            hideContextMenu();
        }

        function renameItem() {
            console.log('Rename:', contextMenuData);
            hideContextMenu();
            showRenameModal();
        }

        function showRenameModal() {
            const modal = document.getElementById('renameModal');
            const input = document.getElementById('renameInput');

            // Set current name in input
            input.value = contextMenuData.name;

            // Force input styling
            input.style.color = '#000000';
            input.style.backgroundColor = '#ffffff';
            input.style.background = '#ffffff';
            input.style.webkitTextFillColor = '#000000';

            // Show modal
            modal.classList.remove('hidden');

            // Focus and select text in input
            setTimeout(() => {
                input.focus();
                input.select();
                // Force styling again after focus
                input.style.color = '#000000';
                input.style.webkitTextFillColor = '#000000';
            }, 100);
        }

        function closeRenameModal() {
            const modal = document.getElementById('renameModal');
            modal.classList.add('hidden');
        }

        function submitRename(event) {
            event.preventDefault();

            const input = document.getElementById('renameInput');
            const newName = input.value.trim();

            if (!newName) {
                input.focus();
                return;
            }

            if (newName === contextMenuData.name) {
                closeRenameModal();
                return;
            }

            // Send rename request
            const endpoint = contextMenuData.type === 'document'
                ? `/documents/${contextMenuData.id}/rename`
                : `/folders/${contextMenuData.id}/rename`;

            fetch(endpoint, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ name: newName })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error renaming item');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error renaming item');
            });

            closeRenameModal();
        }

        // Add event listeners for rename modal
        document.addEventListener('DOMContentLoaded', function() {
            const renameModal = document.getElementById('renameModal');
            const renameInput = document.getElementById('renameInput');

            // Close modal when clicking outside
            if (renameModal) {
                renameModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeRenameModal();
                    }
                });
            }

            // Force input styling on all events
            if (renameInput) {
                ['input', 'keyup', 'keydown', 'focus', 'blur', 'change'].forEach(event => {
                    renameInput.addEventListener(event, function() {
                        this.style.color = '#000000';
                        this.style.backgroundColor = '#ffffff';
                        this.style.background = '#ffffff';
                        this.style.webkitTextFillColor = '#000000';
                    });
                });
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const renameModal = document.getElementById('renameModal');
                if (renameModal && !renameModal.classList.contains('hidden')) {
                    closeRenameModal();
                }
            }
        });

        // File type icon and color helper functions
        function getFileIcon(extension) {
            const icons = {
                'pdf': 'picture_as_pdf',
                'doc': 'description',
                'docx': 'description',
                'xls': 'table_chart',
                'xlsx': 'table_chart',
                'csv': 'table_chart',
                'ppt': 'slideshow',
                'pptx': 'slideshow',
                'txt': 'text_snippet',
                'rtf': 'text_snippet',
                'zip': 'folder_zip',
                'rar': 'folder_zip',
                '7z': 'folder_zip',
                'jpg': 'image',
                'jpeg': 'image',
                'png': 'image',
                'gif': 'image',
                'bmp': 'image',
                'svg': 'image',
                'webp': 'image',
                'mp4': 'movie',
                'avi': 'movie',
                'mov': 'movie',
                'wmv': 'movie',
                'mp3': 'music_note',
                'wav': 'music_note',
                'flac': 'music_note',
                'html': 'code',
                'css': 'code',
                'js': 'code',
                'json': 'code',
                'xml': 'code'
            };
            return icons[extension] || 'insert_drive_file';
        }

        function getFileIconColor(extension) {
            const colors = {
                'pdf': 'text-red-500',
                'doc': 'text-blue-600',
                'docx': 'text-blue-600',
                'xls': 'text-green-600',
                'xlsx': 'text-green-600',
                'csv': 'text-green-500',
                'ppt': 'text-orange-500',
                'pptx': 'text-orange-500',
                'txt': 'text-gray-600',
                'rtf': 'text-gray-600',
                'zip': 'text-purple-500',
                'rar': 'text-purple-500',
                '7z': 'text-purple-500',
                'jpg': 'text-pink-500',
                'jpeg': 'text-pink-500',
                'png': 'text-pink-500',
                'gif': 'text-pink-500',
                'bmp': 'text-pink-500',
                'svg': 'text-pink-500',
                'webp': 'text-pink-500',
                'mp4': 'text-red-600',
                'avi': 'text-red-600',
                'mov': 'text-red-600',
                'wmv': 'text-red-600',
                'mp3': 'text-indigo-500',
                'wav': 'text-indigo-500',
                'flac': 'text-indigo-500',
                'html': 'text-orange-600',
                'css': 'text-blue-500',
                'js': 'text-yellow-500',
                'json': 'text-yellow-600',
                'xml': 'text-orange-400'
            };
            return colors[extension] || 'text-gray-400';
        }

        function copyItem() {
            console.log('Copy:', contextMenuData);
            const endpoint = contextMenuData.type === 'document'
                ? `/documents/${contextMenuData.id}/copy`
                : `/folders/${contextMenuData.id}/copy`;

            fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error copying item');
                }
            });
            hideContextMenu();
        }

        function shareItem() {
            console.log('Share:', contextMenuData);
            // Implement share functionality
            alert(`Share ${contextMenuData.type}: ${contextMenuData.name}`);
            hideContextMenu();
        }

        function copyLink() {
            console.log('Copy link:', contextMenuData);
            const link = contextMenuData.type === 'document'
                ? `${window.location.origin}/documents/${contextMenuData.id}`
                : `${window.location.origin}/documents?folder=${contextMenuData.id}`;

            navigator.clipboard.writeText(link).then(() => {
                alert('Link copied to clipboard');
            });
            hideContextMenu();
        }

        function moveItem() {
            console.log('Move:', contextMenuData);
            // Implement move functionality
            alert(`Move ${contextMenuData.type}: ${contextMenuData.name}`);
            hideContextMenu();
        }

        function addShortcut() {
            console.log('Add shortcut:', contextMenuData);
            // Implement shortcut functionality
            alert(`Add shortcut for ${contextMenuData.type}: ${contextMenuData.name}`);
            hideContextMenu();
        }

        function toggleStarFromMenu() {
            console.log('Toggle star:', contextMenuData);
            const endpoint = contextMenuData.type === 'document'
                ? `/documents/${contextMenuData.id}/star`
                : `/folders/${contextMenuData.id}/star`;

            fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error updating star status');
                }
            });
            hideContextMenu();
        }

        function showDetails() {
            console.log('Show details:', contextMenuData);
            // Implement details panel
            alert(`Details for ${contextMenuData.type}: ${contextMenuData.name}`);
            hideContextMenu();
        }

        function showActivity() {
            console.log('Show activity:', contextMenuData);
            // Implement activity panel
            alert(`Activity for ${contextMenuData.type}: ${contextMenuData.name}`);
            hideContextMenu();
        }

        function removeItem() {
            console.log('Remove:', contextMenuData);

            // Check current view type
            const currentType = new URLSearchParams(window.location.search).get('type');

            if (currentType === 'trash' || currentType === 'spam') {
                // In trash/spam view - show options for restore or permanent delete
                const action = confirm(`Pilih tindakan untuk ${contextMenuData.type}: ${contextMenuData.name}\n\nOK = Pulihkan\nBatal = Padam kekal`);

                if (action) {
                    // Restore item
                    restoreItem();
                } else {
                    // Permanent delete
                    permanentDeleteItem();
                }
            } else {
                // Normal view - move to trash
                if (confirm(`Adakah anda pasti ingin memindahkan ${contextMenuData.type}: ${contextMenuData.name} ke tong sampah?`)) {
                    moveToTrash();
                }
            }
            hideContextMenu();
        }

        function moveToTrash() {
            const endpoint = contextMenuData.type === 'document'
                ? `/documents/${contextMenuData.id}/trash`
                : `/folders/${contextMenuData.id}/trash`;

            fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Error moving item to trash');
                }
            });
        }

        function moveToSpam() {
            if (confirm(`Adakah anda pasti ingin menandakan ${contextMenuData.type}: ${contextMenuData.name} sebagai spam?`)) {
                const endpoint = contextMenuData.type === 'document'
                    ? `/documents/${contextMenuData.id}/spam`
                    : `/folders/${contextMenuData.id}/spam`;

                fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'Error marking item as spam');
                    }
                });
            }
        }

        function restoreItem() {
            const endpoint = contextMenuData.type === 'document'
                ? `/documents/${contextMenuData.id}/restore`
                : `/folders/${contextMenuData.id}/restore`;

            fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Error restoring item');
                }
            });
        }

        function permanentDeleteItem() {
            if (confirm(`Adakah anda pasti ingin MEMADAM KEKAL ${contextMenuData.type}: ${contextMenuData.name}? Tindakan ini tidak boleh dibatalkan!`)) {
                const endpoint = contextMenuData.type === 'document'
                    ? `/documents/${contextMenuData.id}/force-delete`
                    : `/folders/${contextMenuData.id}/force-delete`;

                fetch(endpoint, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'Error permanently deleting item');
                    }
                });
            }
        }

        // Function to update color picker with current folder color
        function updateColorPicker() {
            const colorPickerGrid = document.getElementById('colorPickerGrid');
            if (!colorPickerGrid) return;

            // Get current folder color from contextMenuData or fetch from server
            const currentColor = getCurrentFolderColor();

            // Define all available colors
            const colors = [
                // Row 1: Browns to Yellows
                { color: '#8D6E63', title: 'Brown' },
                { color: '#E57373', title: 'Light Red' },
                { color: '#F44336', title: 'Red' },
                { color: '#FF5722', title: 'Deep Orange' },
                { color: '#FF9800', title: 'Orange' },
                { color: '#FFC107', title: 'Amber' },
                { color: '#FFEB3B', title: 'Yellow' },
                { color: '#FFF176', title: 'Light Yellow' },

                // Row 2: Blues to Greens
                { color: '#2196F3', title: 'Blue' },
                { color: '#81D4FA', title: 'Light Blue' },
                { color: '#4DD0E1', title: 'Cyan' },
                { color: '#4DB6AC', title: 'Teal' },
                { color: '#4CAF50', title: 'Green' },
                { color: '#66BB6A', title: 'Medium Green' },
                { color: '#8BC34A', title: 'Light Green' },
                { color: '#AED581', title: 'Lime' },

                // Row 3: Default, Grays, Pinks, Purples
                { color: '#3B82F6', title: 'Default' },
                { color: '#BDBDBD', title: 'Light Gray' },
                { color: '#F8BBD9', title: 'Light Pink' },
                { color: '#F48FB1', title: 'Pink' },
                { color: '#CE93D8', title: 'Light Purple' },
                { color: '#9C27B0', title: 'Purple' },
                { color: '#7986CB', title: 'Indigo' },
                { color: '#B39DDB', title: 'Light Indigo' }
            ];

            // Generate color picker HTML
            let colorHTML = '';
            colors.forEach(colorObj => {
                const isSelected = currentColor === colorObj.color;
                const checkIcon = isSelected ? '<span class="material-icons text-white" style="font-size: 12px;">check</span>' : '';

                colorHTML += `
                    <div class="w-6 h-6 rounded-full cursor-pointer hover:scale-110 transition-transform flex items-center justify-center"
                         style="background-color: ${colorObj.color} !important;"
                         onclick="changeFolderColor('${colorObj.color}')"
                         title="${colorObj.title}">
                        ${checkIcon}
                    </div>
                `;
            });

            colorPickerGrid.innerHTML = colorHTML;
        }

        // Function to get current folder color
        function getCurrentFolderColor() {
            // Try to get color from current folder data
            if (contextMenuData && contextMenuData.id) {
                // First, try to get from contextMenuData if available
                if (contextMenuData.color) {
                    return contextMenuData.color;
                }

                // Look for folder element in DOM to get current color
                const folderElements = document.querySelectorAll('.material-icons[style*="color"]');
                for (let element of folderElements) {
                    const parentElement = element.closest('[onclick*="' + contextMenuData.id + '"]');
                    if (parentElement && element.textContent === 'folder') {
                        const style = element.style.color;

                        // Handle RGB format
                        const rgbMatch = style.match(/rgb\((\d+),\s*(\d+),\s*(\d+)\)/);
                        if (rgbMatch) {
                            const r = parseInt(rgbMatch[1]);
                            const g = parseInt(rgbMatch[2]);
                            const b = parseInt(rgbMatch[3]);
                            return rgbToHex(r, g, b);
                        }

                        // Handle hex format
                        const hexMatch = style.match(/#[0-9A-Fa-f]{6}/);
                        if (hexMatch) {
                            return hexMatch[0].toUpperCase();
                        }
                    }
                }
            }
            return '#3B82F6'; // Default color
        }

        // Helper function to convert RGB to HEX
        function rgbToHex(r, g, b) {
            return "#" + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1).toUpperCase();
        }

        function changeFolderColor(color) {
            console.log('Changing folder color to:', color, 'for folder:', contextMenuData);

            if (contextMenuData.type !== 'folder') {
                console.error('Color change only available for folders');
                return;
            }

            fetch(`/document-folders/${contextMenuData.id}/color`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    color: color
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update ALL folder icons with this ID in the page immediately
                    updateFolderColorInUI(contextMenuData.id, color);

                    // Update color picker to show new selection
                    updateColorPicker();

                    // Hide context menu
                    hideContextMenu();

                    // Show success notification
                    showNotification('Warna folder berjaya dikemaskini', 'success');
                } else {
                    showNotification(data.message || 'Ralat mengemaskini warna folder', 'error');
                }
            })
            .catch(error => {
                console.error('Error updating folder color:', error);
                showNotification('Ralat mengemaskini warna folder', 'error');
            });
        }

        // Function to update folder color in UI without refresh
        function updateFolderColorInUI(folderId, color) {
            // Update in grid view
            const gridFolderElements = document.querySelectorAll(`[onclick*="openFolder(${folderId})"] .material-icons`);
            gridFolderElements.forEach(icon => {
                if (icon.textContent === 'folder') {
                    icon.style.color = color;
                }
            });

            // Update in list view (table)
            const listFolderElements = document.querySelectorAll(`tr[onclick*="openFolder(${folderId})"] .material-icons`);
            listFolderElements.forEach(icon => {
                if (icon.textContent === 'folder') {
                    icon.style.color = color;
                }
            });

            // Update in breadcrumb if it's current folder
            const breadcrumbIcon = document.querySelector('h2 .material-icons[style*="color"]');
            if (breadcrumbIcon && breadcrumbIcon.textContent === 'folder') {
                // Check if this is the current folder by comparing with URL or other means
                const currentFolderId = new URLSearchParams(window.location.search).get('folder');
                if (currentFolderId == folderId) {
                    breadcrumbIcon.style.color = color;
                }
            }

            console.log(`Updated folder ${folderId} color to ${color} in UI`);
        }

        // Helper functions for both grid and list layouts
        function openFolder(folderId) {
            window.location.href = `/documents?folder=${folderId}`;
        }

        function openDocument(documentId) {
            window.location.href = `/documents/${documentId}`;
        }

        // Utility function to create consistent three-dot button HTML
        function createThreeDotButton(type, id, name, isStarred = false, color = null, classes = '') {
            return `
                <button class="text-gray-400 hover:text-gray-600 p-1 rounded-full hover:bg-gray-100 transition-all duration-200 ${classes}"
                        onclick="event.stopPropagation(); showContextMenu(event, '${type}', ${id}, '${name}', ${isStarred}, '${color || ''}')">
                    <span class="material-icons text-sm">more_vert</span>
                </button>
            `;
        }

        // Utility function to create consistent right-click handler
        function addRightClickHandler(element, type, id, name, isStarred = false, color = null) {
            element.addEventListener('contextmenu', function(event) {
                showContextMenu(event, type, id, name, isStarred, color);
            });
        }

        // Centralized item click handlers
        function handleItemClick(type, id) {
            if (type === 'folder') {
                openFolder(id);
            } else if (type === 'document') {
                openDocument(id);
            }
        }
    </script>

    <!-- New Folder Modal -->
    <div id="newFolderModal" class="fixed inset-0 overflow-y-auto h-full w-full hidden z-50" style="background-color: rgba(0, 0, 0, 0.3);">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <!-- Icon -->
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                    <span class="material-icons text-blue-600 text-xl">create_new_folder</span>
                </div>

                <!-- Title -->
                <h3 class="text-lg font-medium text-gray-900 mt-4">Folder Baru</h3>

                <!-- Form -->
                <div class="mt-4 px-7">
                    <!-- Folder Name -->
                    <div class="mb-4 text-left">
                        <label for="folderName" class="block text-sm font-medium text-gray-700 mb-2">Nama Folder</label>
                        <input type="text" id="folderName" placeholder="Masukkan nama folder..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm"
                               onkeypress="if(event.key==='Enter') createNewFolder()">
                    </div>

                    <!-- Folder Color -->
                    <div class="mb-4 text-left">
                        <label for="folderColor" class="block text-sm font-medium text-gray-700 mb-2">Warna Folder</label>
                        <div class="flex items-center justify-center space-x-3">
                            <input type="color" id="folderColor" value="#3B82F6"
                                   class="w-12 h-8 border border-gray-300 rounded-md cursor-pointer">
                            <div class="flex space-x-2">
                                <button type="button" onclick="document.getElementById('folderColor').value='#3B82F6'"
                                        class="w-6 h-6 bg-blue-500 rounded-full border-2 border-gray-300 hover:border-gray-400"></button>
                                <button type="button" onclick="document.getElementById('folderColor').value='#10B981'"
                                        class="w-6 h-6 bg-green-500 rounded-full border-2 border-gray-300 hover:border-gray-400"></button>
                                <button type="button" onclick="document.getElementById('folderColor').value='#F59E0B'"
                                        class="w-6 h-6 bg-yellow-500 rounded-full border-2 border-gray-300 hover:border-gray-400"></button>
                                <button type="button" onclick="document.getElementById('folderColor').value='#EF4444'"
                                        class="w-6 h-6 bg-red-500 rounded-full border-2 border-gray-300 hover:border-gray-400"></button>
                                <button type="button" onclick="document.getElementById('folderColor').value='#8B5CF6'"
                                        class="w-6 h-6 bg-purple-500 rounded-full border-2 border-gray-300 hover:border-gray-400"></button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-center gap-3 mt-4">
                    <button type="button"
                            onclick="closeNewFolderModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Batal
                    </button>
                    <button type="button"
                            onclick="createNewFolder()"
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Buat Folder
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
</body>
</html>
