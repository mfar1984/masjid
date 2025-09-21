<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $document->name }} - E-Masjid</title>
    <x-favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
                                <span class="material-icons text-2xl text-blue-600">visibility</span>
                                <h1 class="text-xl font-semibold text-gray-900">Lihat Dokumen</h1>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('documents.index', ['folder' => $document->folder?->id]) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm">
                                <span class="material-icons text-lg mr-2">arrow_back</span>
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Main Content Area - No Sidebar -->
                <div class="flex-1 bg-gray-50">
                    <!-- Breadcrumbs & Document Info -->
                    <div class="bg-white border-b border-gray-200">
                        <div class="px-6 py-4">
                            <!-- Breadcrumbs -->
                            <nav class="flex mb-6" aria-label="Breadcrumb">
                                <ol class="inline-flex items-center space-x-1">
                                    <li class="inline-flex items-center">
                                        <a href="{{ route('documents.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:bg-blue-50 px-2 py-1 rounded-md transition-all duration-200">
                                            Dokumen Saya
                                        </a>
                                    </li>
                                    @if($document->folder)
                                        <span class="material-icons text-gray-300 text-sm mx-2">chevron_right</span>
                                        <li class="inline-flex items-center">
                                            <a href="{{ route('documents.index', ['folder' => $document->folder->id]) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:bg-blue-50 px-2 py-1 rounded-md transition-all duration-200">
                                                {{ $document->folder->name }}
                                            </a>
                                        </li>
                                    @endif
                                    <span class="material-icons text-gray-300 text-sm mx-2">chevron_right</span>
                                    <li class="inline-flex items-center">
                                        <span class="text-sm font-medium text-gray-900 px-2 py-1 bg-gray-100 rounded-md">{{ $document->name }}</span>
                                    </li>
                                </ol>
                            </nav>

                            <!-- Minimalist Document Details -->
                            <div class="bg-white rounded-lg border border-gray-200 p-4">
                                <div class="flex items-center justify-between">
                                    <!-- Left: Icon + Document Info -->
                                    <div class="flex items-center space-x-4">
                                        <!-- Small File Icon -->
                                        <div class="flex-shrink-0">
                                            @if($document->isImage())
                                                <div class="w-12 h-12 rounded-lg overflow-hidden border border-gray-200">
                                                    <img src="{{ $document->preview_url }}" alt="{{ $document->name }}" class="w-full h-full object-cover">
                                                </div>
                                            @else
                                                <div class="w-12 h-12 bg-gray-50 rounded-lg flex items-center justify-center">
                                                    <span class="material-icons text-xl 
                                                        @switch($document->file_extension)
                                                            @case('pdf') text-red-500 @break
                                                            @case('doc') @case('docx') text-blue-600 @break
                                                            @case('xls') @case('xlsx') text-green-600 @break
                                                            @case('ppt') @case('pptx') text-orange-500 @break
                                                            @default text-gray-400
                                                        @endswitch
                                                    ">
                                                        @switch($document->file_extension)
                                                            @case('pdf') picture_as_pdf @break
                                                            @case('doc') @case('docx') description @break
                                                            @case('xls') @case('xlsx') table_chart @break
                                                            @case('ppt') @case('pptx') slideshow @break
                                                            @default insert_drive_file
                                                        @endswitch
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Document Info -->
                                        <div class="min-w-0 flex-1">
                                            <!-- Title with badges -->
                                            <div class="flex items-center space-x-2 mb-1">
                                                <h1 class="text-lg font-semibold text-gray-900 truncate">{{ $document->name }}</h1>
                                                @if($document->is_starred)
                                                    <span class="material-icons text-yellow-500" style="font-size: 18px !important;">star</span>
                                                @endif
                                            </div>
                                            
                                            <!-- Compact metadata -->
                                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                                <span>{{ $document->file_size_human }}</span>
                                                <span>•</span>
                                                <span>{{ strtoupper($document->file_extension) }}</span>
                                                <span>•</span>
                                                <span>Dicipta {{ $document->created_at->diffForHumans() }}</span>
                                                @if($document->updated_at != $document->created_at)
                                                    <span>•</span>
                                                    <span>Dikemaskini {{ $document->updated_at->diffForHumans() }}</span>
                                                @endif
                                            </div>
                                            
                                            <!-- Description (if exists) -->
                                            @if($document->description)
                                                <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ $document->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Right: Action Buttons -->
                                    <div class="flex items-center space-x-2 flex-shrink-0 ml-4">
                                        <!-- Star Button -->
                                        <button onclick="toggleStar()" class="p-2 text-gray-400 hover:text-yellow-500 hover:bg-gray-50 rounded-lg transition-colors" title="{{ $document->is_starred ? 'Buang dari Kegemaran' : 'Tambah ke Kegemaran' }}">
                                            <span class="material-icons" style="font-size: 18px !important;">{{ $document->is_starred ? 'star' : 'star_border' }}</span>
                                        </button>
                                        
                                        <!-- Download Button -->
                                        <a href="{{ route('documents.download', $document) }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                                            <span class="material-icons mr-1" style="font-size: 18px !important;">download</span>
                                            Muat Turun
                                        </a>
                                        
                                        @if(auth()->user()->hasPermission('documents', 'update'))
                                            <!-- Edit Button -->
                                            <a href="{{ route('documents.edit', $document) }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                                                <span class="material-icons mr-1" style="font-size: 18px !important;">edit</span>
                                                Edit
                                            </a>
                                        @endif
                                        
                                        <!-- More Actions -->
                                        <div class="relative" x-data="{ open: false }">
                                            <button @click="open = !open" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">
                                                <span class="material-icons" style="font-size: 18px !important;">more_vert</span>
                                            </button>
                                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-10">
                                                @if(auth()->user()->hasPermission('documents', 'share'))
                                                    <button onclick="openShareModal()" class="flex items-center w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                        <span class="material-icons mr-3" style="font-size: 18px !important; width: 18px !important; height: 18px !important; display: flex !important; align-items: center !important; justify-content: center !important;">share</span>
                                                        <span style="line-height: 18px !important;">Kongsi</span>
                                                    </button>
                                                @endif
                                                <button onclick="copyLink()" class="flex items-center w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                    <span class="material-icons mr-3" style="font-size: 18px !important; width: 18px !important; height: 18px !important; display: flex !important; align-items: center !important; justify-content: center !important;">link</span>
                                                    <span style="line-height: 18px !important;">Salin Pautan</span>
                                                </button>
                                                @if(auth()->user()->hasPermission('documents', 'delete'))
                                                    <div class="border-t border-gray-100 my-1"></div>
                                                    <button onclick="deleteDocument()" class="flex items-center w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50">
                                                        <span class="material-icons mr-3" style="font-size: 18px !important; width: 18px !important; height: 18px !important; display: flex !important; align-items: center !important; justify-content: center !important; color: #dc2626 !important;">delete</span>
                                                        <span style="line-height: 18px !important;">Padam</span>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Beautiful Preview Section -->
                    <div class="p-6">
                        <div class="max-w-6xl mx-auto">
                            @if($document->isPreviewable())
                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                                    <!-- Preview Header -->
                                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <span class="material-icons text-blue-600">visibility</span>
                                                <h3 class="text-lg font-semibold text-gray-900">Pratonton Dokumen</h3>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-sm text-gray-500">{{ $document->name }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Preview Content -->
                                    <div class="p-6">
                                        @if($document->isImage())
                                            <div class="flex justify-center bg-gray-50 rounded-lg p-4">
                                                <img src="{{ $document->token_preview_url }}" alt="{{ $document->name }}" class="max-w-full max-h-[70vh] object-contain rounded-lg shadow-md">
                                            </div>
                                        @elseif($document->file_extension === 'pdf')
                                            <div class="bg-gray-100 rounded-lg overflow-hidden" style="height: 70vh;">
                                                <iframe src="{{ $document->token_preview_url }}" class="w-full h-full border-0"></iframe>
                                            </div>
                                        @else
                                            <div class="flex items-center justify-center h-64 bg-gray-50 rounded-lg">
                                                <div class="text-center">
                                                    <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                                        <span class="material-icons text-2xl text-gray-400">visibility_off</span>
                                                    </div>
                                                    <h4 class="text-lg font-medium text-gray-900 mb-2">Pratonton Tidak Tersedia</h4>
                                                    <p class="text-gray-500 mb-4">Jenis fail ini tidak menyokong pratonton dalam pelayar</p>
                                                    <a href="{{ route('documents.download', $document) }}" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white hover:bg-blue-700 transition-all duration-200">
                                                        <span class="material-icons text-sm mr-2">download</span>
                                                        Muat Turun untuk Lihat
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                                    <!-- No Preview Header -->
                                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                                        <div class="flex items-center space-x-3">
                                            <span class="material-icons text-gray-400">visibility_off</span>
                                            <h3 class="text-lg font-semibold text-gray-900">Pratonton Tidak Tersedia</h3>
                                        </div>
                                    </div>
                                    
                                    <!-- No Preview Content -->
                                    <div class="p-12">
                                        <div class="text-center">
                                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                                <span class="material-icons text-4xl text-gray-400">insert_drive_file</span>
                                            </div>
                                            <h4 class="text-xl font-semibold text-gray-900 mb-3">{{ $document->name }}</h4>
                                            <p class="text-gray-500 mb-6 max-w-md mx-auto">Jenis fail ini tidak menyokong pratonton dalam pelayar. Muat turun fail untuk melihat kandungan.</p>
                                            <div class="flex justify-center space-x-4">
                                                <a href="{{ route('documents.download', $document) }}" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white hover:bg-blue-700 transition-all duration-200">
                                                    <span class="material-icons text-sm mr-2">download</span>
                                                    Muat Turun
                                                </a>
                                                @if(auth()->user()->hasPermission('documents', 'update'))
                                                    <a href="{{ route('documents.edit', $document) }}" class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all duration-200">
                                                        <span class="material-icons text-sm mr-2">edit</span>
                                                        Edit
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-footer />

    <!-- Modals will be added here -->
    
    <script>
        function toggleStar() {
            fetch(`{{ route('documents.toggle-star', $document) }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ralat berlaku semasa mengemas kini status kegemaran');
            });
        }

        function openShareModal() {
            openSharingModal('document', '{{ $document->getHashToken() }}', '{{ $document->name }}');
        }

        function openPreviewModal() {
            // TODO: Implement preview modal
            alert('Preview modal - to be implemented');
        }

        function copyLink() {
            const url = window.location.href;
            navigator.clipboard.writeText(url).then(() => {
                alert('Pautan telah disalin ke clipboard');
            }).catch(() => {
                alert('Gagal menyalin pautan');
            });
        }

        function deleteDocument() {
            if (confirm('Adakah anda pasti ingin memadam dokumen ini? Tindakan ini tidak boleh dibatalkan.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `{{ route('documents.destroy', $document) }}`;
                
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                
                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                form.appendChild(methodInput);
                form.appendChild(tokenInput);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>

    <!-- Include Sharing Modal -->
    @include('components.sharing-modal')

    <!-- Sharing Modal JavaScript -->
    <script src="{{ asset('js/sharing-modal.js') }}"></script>
</body>
</html>
