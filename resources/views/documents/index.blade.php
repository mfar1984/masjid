<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengurusan Dokumen - E-Masjid</title>

    <!-- Favicon -->
    <x-favicon />

    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
                                                <div class="folder-item group relative bg-white rounded-xl border border-gray-200 hover:border-blue-300 hover:shadow-lg transition-all duration-300 cursor-pointer overflow-hidden" onclick="window.location.href='{{ route('documents.index', ['folder' => $folder->id]) }}'">
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
                                                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-20">
                                                                <button onclick="editFolder({{ $folder->id }})" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                                    <span class="material-icons text-sm mr-3">edit</span>
                                                                    Edit Folder
                                                                </button>
                                                                <button onclick="toggleStar('folder', {{ $folder->id }})" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                                    <span class="material-icons text-sm mr-3">{{ $folder->is_starred ? 'star' : 'star_border' }}</span>
                                                                    {{ $folder->is_starred ? 'Buang dari Kegemaran' : 'Tambah ke Kegemaran' }}
                                                                </button>
                                                                @if(auth()->user()->hasPermission('documents', 'delete'))
                                                                    <div class="border-t border-gray-100 my-1"></div>
                                                                    <button onclick="deleteFolder({{ $folder->id }})" class="flex items-center w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                                        <span class="material-icons text-sm mr-3">delete</span>
                                                                        Padam Folder
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
                                                                                @case('doc') text-blue-500 @break
                                                                                @case('docx') text-blue-500 @break
                                                                                @case('xls') text-green-500 @break
                                                                                @case('xlsx') text-green-500 @break
                                                                                @case('ppt') text-orange-500 @break
                                                                                @case('pptx') text-orange-500 @break
                                                                                @default text-gray-400
                                                                            @endswitch">
                                                                            @switch($document->file_extension)
                                                                                @case('pdf') picture_as_pdf @break
                                                                                @case('doc') description @break
                                                                                @case('docx') description @break
                                                                                @case('xls') table_chart @break
                                                                                @case('xlsx') table_chart @break
                                                                                @case('ppt') slideshow @break
                                                                                @case('pptx') slideshow @break
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

    <x-footer />

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
            // TODO: Implement star toggle
            alert('Toggle star - to be implemented');
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
                        <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location.href='/documents?folder=${folder.id}'">
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
                                <button class="text-gray-400 hover:text-gray-600">
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
                        <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location.href='/documents/${document.id}'">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="material-icons text-xl mr-3 text-gray-400">description</span>
                                    <div class="text-sm font-medium text-gray-900">${document.name}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${fileSize}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${owner}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDateTime(document.created_at)}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <button class="text-gray-400 hover:text-gray-600">
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
                        <div class="folder-item group relative bg-white rounded-xl border border-gray-200 hover:border-blue-300 hover:shadow-lg transition-all duration-300 cursor-pointer overflow-hidden" onclick="window.location.href='/documents?folder=${folder.id}'">
                            <div class="p-4 text-center">
                                <div class="flex justify-center mb-3">
                                    <span class="material-icons text-4xl" style="color: ${folder.color || '#3B82F6'}">folder</span>
                                </div>
                                <h4 class="text-sm font-medium text-gray-900 mb-1 truncate">${folder.name}</h4>
                                <p class="text-xs text-gray-500">${itemCount} ${itemText}</p>
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
                    gridContent += `
                        <div class="file-item group relative bg-white rounded-xl border border-gray-200 hover:border-blue-300 hover:shadow-lg transition-all duration-300 cursor-pointer overflow-hidden" onclick="window.location.href='/documents/${document.id}'">
                            <div class="p-4 text-center">
                                <div class="flex justify-center mb-3">
                                    <span class="material-icons text-4xl text-gray-400">description</span>
                                </div>
                                <h4 class="text-sm font-medium text-gray-900 mb-1 truncate">${document.name}</h4>
                                <p class="text-xs text-gray-500">${fileSize}</p>
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
            }
        });
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
