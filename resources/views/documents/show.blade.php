<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $document->name }} - E-Masjid</title>
    <x-favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col" style="font-family: 'Poppins', sans-serif;">
    <x-double-navbar :user="auth()->user()" />
    
    <div class="flex-1 flex flex-col">
        <!-- Header Section -->
        <div class="bg-white border-b border-gray-200">
            <div class="px-6 py-4">
                <!-- Breadcrumbs -->
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('documents.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                Dokumen Saya
                            </a>
                        </li>
                        @if($document->folder)
                            <span class="material-icons text-gray-400 text-sm mx-1">chevron_right</span>
                            <li class="inline-flex items-center">
                                <a href="{{ route('documents.index', ['folder' => $document->folder->id]) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                    {{ $document->folder->name }}
                                </a>
                            </li>
                        @endif
                        <span class="material-icons text-gray-400 text-sm mx-1">chevron_right</span>
                        <li class="inline-flex items-center">
                            <span class="text-sm font-medium text-gray-500">{{ $document->name }}</span>
                        </li>
                    </ol>
                </nav>

                <!-- Document Header -->
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-4">
                        <!-- File Icon -->
                        <div class="flex-shrink-0">
                            @if($document->isImage())
                                <img src="{{ $document->preview_url }}" alt="{{ $document->name }}" class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                            @else
                                <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <span class="material-icons text-2xl text-gray-400">
                                        @switch($document->file_extension)
                                            @case('pdf')
                                                picture_as_pdf
                                                @break
                                            @case('doc')
                                            @case('docx')
                                                description
                                                @break
                                            @case('xls')
                                            @case('xlsx')
                                                table_chart
                                                @break
                                            @case('ppt')
                                            @case('pptx')
                                                slideshow
                                                @break
                                            @default
                                                insert_drive_file
                                        @endswitch
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Document Info -->
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                <h1 class="text-2xl font-semibold text-gray-900">{{ $document->name }}</h1>
                                @if($document->is_starred)
                                    <span class="material-icons text-yellow-500">star</span>
                                @endif
                                @if($document->is_shared)
                                    <span class="material-icons text-blue-500">people</span>
                                @endif
                            </div>
                            
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
                            
                            @if($document->description)
                                <p class="mt-2 text-gray-700">{{ $document->description }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex items-center space-x-3">
                        <!-- Star Button -->
                        <button onclick="toggleStar()" class="p-2 rounded-full hover:bg-gray-100" title="{{ $document->is_starred ? 'Buang dari Kegemaran' : 'Tambah ke Kegemaran' }}">
                            <span class="material-icons text-gray-500">{{ $document->is_starred ? 'star' : 'star_border' }}</span>
                        </button>
                        
                        <!-- Download Button -->
                        <a href="{{ route('documents.download', $document) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <span class="material-icons text-sm mr-2">download</span>
                            Muat Turun
                        </a>
                        
                        @if(auth()->user()->hasPermission('documents', 'share'))
                            <!-- Share Button -->
                            <button onclick="openShareModal()" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                <span class="material-icons text-sm mr-2">share</span>
                                Kongsi
                            </button>
                        @endif
                        
                        @if(auth()->user()->hasPermission('documents', 'update'))
                            <!-- Edit Button -->
                            <a href="{{ route('documents.edit', $document) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                <span class="material-icons text-sm mr-2">edit</span>
                                Edit
                            </a>
                        @endif
                        
                        <!-- More Actions -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="p-2 rounded-full hover:bg-gray-100">
                                <span class="material-icons text-gray-500">more_vert</span>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-1 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                                @if($document->isPreviewable())
                                    <button onclick="openPreviewModal()" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <span class="material-icons text-sm mr-2">visibility</span>
                                        Pratonton
                                    </button>
                                @endif
                                <button onclick="copyLink()" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <span class="material-icons text-sm mr-2">link</span>
                                    Salin Pautan
                                </button>
                                @if(auth()->user()->hasPermission('documents', 'delete'))
                                    <button onclick="deleteDocument()" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <span class="material-icons text-sm mr-2">delete</span>
                                        Padam
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex">
            <!-- Preview Area -->
            <div class="flex-1 p-6">
                @if($document->isPreviewable())
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 h-full">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Pratonton</h3>
                        </div>
                        <div class="p-4 h-full">
                            @if($document->isImage())
                                <div class="flex justify-center">
                                    <img src="{{ $document->preview_url }}" alt="{{ $document->name }}" class="max-w-full max-h-full object-contain">
                                </div>
                            @elseif($document->file_extension === 'pdf')
                                <iframe src="{{ $document->preview_url }}" class="w-full h-full border-0 rounded"></iframe>
                            @else
                                <div class="flex items-center justify-center h-64">
                                    <div class="text-center">
                                        <span class="material-icons text-6xl text-gray-300 mb-4">visibility_off</span>
                                        <p class="text-gray-500">Pratonton tidak tersedia untuk jenis fail ini</p>
                                        <a href="{{ route('documents.download', $document) }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                            <span class="material-icons text-sm mr-2">download</span>
                                            Muat Turun untuk Lihat
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 h-full flex items-center justify-center">
                        <div class="text-center">
                            <span class="material-icons text-6xl text-gray-300 mb-4">visibility_off</span>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Pratonton Tidak Tersedia</h3>
                            <p class="text-gray-500 mb-6">Jenis fail ini tidak menyokong pratonton dalam pelayar</p>
                            <a href="{{ route('documents.download', $document) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                <span class="material-icons text-sm mr-2">download</span>
                                Muat Turun untuk Lihat
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="w-80 bg-white border-l border-gray-200 flex-shrink-0">
                <div class="p-6">
                    <!-- Document Details -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Butiran Dokumen</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nama Fail Asal</dt>
                                <dd class="text-sm text-gray-900">{{ $document->original_filename }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Saiz</dt>
                                <dd class="text-sm text-gray-900">{{ $document->file_size_human }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Jenis</dt>
                                <dd class="text-sm text-gray-900">{{ strtoupper($document->file_extension) }} ({{ $document->mime_type }})</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Folder</dt>
                                <dd class="text-sm text-gray-900">
                                    @if($document->folder)
                                        <a href="{{ route('documents.index', ['folder' => $document->folder->id]) }}" class="text-blue-600 hover:text-blue-800">
                                            {{ $document->folder->name }}
                                        </a>
                                    @else
                                        Folder Utama
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Dicipta Oleh</dt>
                                <dd class="text-sm text-gray-900">{{ $document->creator->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tarikh Dicipta</dt>
                                <dd class="text-sm text-gray-900">{{ $document->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            @if($document->updated_at != $document->created_at)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Dikemaskini Oleh</dt>
                                    <dd class="text-sm text-gray-900">{{ $document->updater?->name ?? 'Tidak diketahui' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tarikh Dikemaskini</dt>
                                    <dd class="text-sm text-gray-900">{{ $document->updated_at->format('d/m/Y H:i') }}</dd>
                                </div>
                            @endif
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Versi</dt>
                                <dd class="text-sm text-gray-900">{{ $document->version }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Jumlah Muat Turun</dt>
                                <dd class="text-sm text-gray-900">{{ $document->download_count }}</dd>
                            </div>
                            @if($document->last_accessed_at)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Akses Terakhir</dt>
                                    <dd class="text-sm text-gray-900">{{ $document->last_accessed_at->diffForHumans() }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    <!-- Sharing Info -->
                    @if($document->shares->count() > 0)
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Dikongsi Dengan</h3>
                            <div class="space-y-3">
                                @foreach($document->shares as $share)
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $share->sharedWithMasjid->name }}</p>
                                            <p class="text-xs text-gray-500">{{ ucfirst($share->permission_level) }}</p>
                                        </div>
                                        <span class="text-xs text-gray-400">{{ $share->created_at->diffForHumans() }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Version History -->
                    @if($document->versions->count() > 0)
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Sejarah Versi</h3>
                            <div class="space-y-3">
                                @foreach($document->versions as $version)
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Versi {{ $version->version }}</p>
                                            <p class="text-xs text-gray-500">{{ $version->updated_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                        <a href="{{ route('documents.download', $version) }}" class="text-xs text-blue-600 hover:text-blue-800">
                                            Muat Turun
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
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
            // TODO: Implement share modal
            alert('Share modal - to be implemented');
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
</body>
</html>
