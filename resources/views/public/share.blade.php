<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $item->name }} - E-Masjid</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="bg-white rounded shadow-sm p-6 mb-6">
            <div class="flex items-center gap-4">
                @if($type === 'folder')
                    <span class="material-icons text-4xl text-yellow-500">folder</span>
                @else
                    <span class="material-icons text-4xl text-blue-500">description</span>
                @endif
                
                <div>
                    <h1 class="text-xl font-semibold text-gray-900">{{ $item->name }}</h1>
                    <p class="text-sm text-gray-600">
                        Dikongsi secara awam • 
                        Tahap akses: {{ ucfirst($permission_level) }} •
                        Dilihat {{ $share->access_count }} kali
                    </p>
                </div>
            </div>
        </div>

        @if($type === 'folder')
            <!-- Folder Contents -->
            <div class="bg-white rounded shadow-sm">
                <div class="p-6 border-b">
                    <h2 class="text-lg font-medium text-gray-900">Kandungan Folder</h2>
                </div>
                
                <div class="p-6">
                    @php
                        $documents = \App\Models\Document::where('folder_id', $item->id)
                            ->where('status', 'active')
                            ->orderBy('name')
                            ->get();
                        
                        $subfolders = \App\Models\DocumentFolder::where('parent_id', $item->id)
                            ->where('status', 'active')
                            ->orderBy('name')
                            ->get();
                    @endphp

                    @if($subfolders->count() > 0 || $documents->count() > 0)
                        <div class="space-y-2">
                            <!-- Subfolders -->
                            @foreach($subfolders as $subfolder)
                                <div class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded">
                                    <span class="material-icons text-yellow-500">folder</span>
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">{{ $subfolder->name }}</div>
                                        <div class="text-xs text-gray-500">Folder</div>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Documents -->
                            @foreach($documents as $document)
                                <div class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded">
                                    <span class="material-icons text-blue-500">description</span>
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">{{ $document->name }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ strtoupper($document->file_extension ?? 'Dokumen') }} •
                                            {{ $document->file_size ? number_format($document->file_size / 1024, 1) . ' KB' : 'Saiz tidak diketahui' }}
                                        </div>
                                    </div>
                                    @if($share->permission_level !== 'view' || $share->can_download)
                                        <a href="{{ route('public.share.download', [$share->share_token, $document->hash_token]) }}"
                                           class="text-blue-600 hover:text-blue-800">
                                            <span class="material-icons text-sm">download</span>
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <span class="material-icons text-6xl text-gray-300 mb-4">folder_open</span>
                            <p class="text-gray-500">Folder ini kosong</p>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <!-- Document Details -->
            <div class="bg-white rounded shadow-sm">
                <div class="p-6 border-b">
                    <h2 class="text-lg font-medium text-gray-900">Maklumat Dokumen</h2>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Fail</label>
                            <p class="text-sm text-gray-900">{{ $item->name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Fail</label>
                            <p class="text-sm text-gray-900">{{ strtoupper($item->file_extension ?? 'Tidak diketahui') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Saiz Fail</label>
                            <p class="text-sm text-gray-900">
                                {{ $item->file_size ? number_format($item->file_size / 1024, 1) . ' KB' : 'Tidak diketahui' }}
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tarikh Dimuat Naik</label>
                            <p class="text-sm text-gray-900">{{ $item->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    @if($item->description)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Penerangan</label>
                            <p class="text-sm text-gray-900">{{ $item->description }}</p>
                        </div>
                    @endif

                    @if($share->permission_level !== 'view' || $share->can_download)
                        <div class="mt-6 pt-6 border-t">
                            <a href="{{ route('public.share.download', [$share->share_token, $item->hash_token]) }}"
                               class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                                <span class="material-icons text-sm">download</span>
                                Muat Turun
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Footer -->
        <div class="text-center mt-8 text-xs text-gray-500">
            <p>Dikongsi melalui E-Masjid • Pautan ini boleh diakses oleh sesiapa yang mempunyai pautan</p>
        </div>
    </div>
</body>
</html>
