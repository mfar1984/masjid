<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit {{ $document->name }} - E-Masjid</title>
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
                                <span class="material-icons text-2xl text-blue-600">edit</span>
                                <h1 class="text-xl font-semibold text-gray-900">Edit Dokumen</h1>
                            </div>
                        </div>
                        
                        <!-- Action Button -->
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('documents.show', ['token' => $document->hash_token]) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm">
                                <span class="material-icons text-lg mr-2">arrow_back</span>
                                Kembali
                            </a>
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
                                    <a href="{{ route('documents.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 text-gray-700 hover:bg-gray-50">
                                        <span class="material-icons text-lg mr-3 text-gray-400 group-hover:text-gray-600">folder</span>
                                        <span class="font-medium">Dokumen Saya</span>
                                    </a>
                                    <a href="{{ route('documents.index', ['type' => 'recent']) }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 text-gray-700 hover:bg-gray-50">
                                        <span class="material-icons text-lg mr-3 text-gray-400 group-hover:text-gray-600">schedule</span>
                                        <span class="font-medium">Terkini</span>
                                    </a>
                                    <a href="{{ route('documents.index', ['type' => 'starred']) }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 text-gray-700 hover:bg-gray-50">
                                        <span class="material-icons text-lg mr-3 text-gray-400 group-hover:text-yellow-500">star</span>
                                        <span class="font-medium">Kegemaran</span>
                                    </a>
                                    <a href="{{ route('documents.index', ['type' => 'shared']) }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 text-gray-700 hover:bg-gray-50">
                                        <span class="material-icons text-lg mr-3 text-gray-400 group-hover:text-gray-600">people</span>
                                        <span class="font-medium">Dikongsi</span>
                                    </a>
                                </nav>
                            </div>

                            <!-- File Type Filters -->
                            <div class="mb-8">
                                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4 px-3">Jenis Fail</h3>
                                <div class="space-y-1">
                                    <a href="{{ route('documents.index', ['extension' => 'pdf']) }}" class="group flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                                        <span class="material-icons text-lg mr-3 text-red-500 group-hover:text-red-600">picture_as_pdf</span>
                                        <span>PDF</span>
                                    </a>
                                    <a href="{{ route('documents.index', ['extension' => 'docx']) }}" class="group flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                                        <span class="material-icons text-lg mr-3 text-blue-500 group-hover:text-blue-600">description</span>
                                        <span>Word</span>
                                    </a>
                                    <a href="{{ route('documents.index', ['extension' => 'xlsx']) }}" class="group flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                                        <span class="material-icons text-lg mr-3 text-green-500 group-hover:text-green-600">table_chart</span>
                                        <span>Excel</span>
                                    </a>
                                    <a href="{{ route('documents.index', ['extension' => 'jpg']) }}" class="group flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                                        <span class="material-icons text-lg mr-3 text-purple-500 group-hover:text-purple-600">image</span>
                                        <span>Gambar</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Edit Info -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-xs font-semibold text-gray-700 mb-3">Maklumat Edit</h4>
                                <div class="space-y-2 text-xs text-gray-600">
                                    <div class="flex items-center space-x-2">
                                        <span class="material-icons text-xs text-blue-500">edit</span>
                                        <span>Kemaskini metadata</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="material-icons text-xs text-green-500">swap_horiz</span>
                                        <span>Ganti fail (pilihan)</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="material-icons text-xs text-purple-500">history</span>
                                        <span>Sejarah versi disimpan</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content Area -->
                    <div class="flex-1 bg-gray-50">
                        <!-- Breadcrumbs & Toolbar -->
        <div class="bg-white border-b border-gray-200">
            <div class="px-6 py-4">
                <!-- Breadcrumbs -->
                <nav class="flex mb-4" aria-label="Breadcrumb">
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
                                            <a href="{{ route('documents.show', $document) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:bg-blue-50 px-2 py-1 rounded-md transition-all duration-200">
                                {{ $document->name }}
                            </a>
                        </li>
                                        <span class="material-icons text-gray-300 text-sm mx-2">chevron_right</span>
                        <li class="inline-flex items-center">
                                            <span class="text-sm font-medium text-gray-900 px-2 py-1 bg-gray-100 rounded-md">Edit</span>
                        </li>
                    </ol>
                </nav>

                                <!-- Page Info -->
                    <div>
                                    <p class="text-sm text-gray-500">
                                        Kemaskini maklumat dokumen: <span class="font-medium text-gray-700">{{ $document->name }}</span>
                                    </p>
                </div>
            </div>
        </div>

                        <!-- Edit Content -->
                        <div class="p-6">
            <div class="max-w-4xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Edit Form -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                            <form action="{{ route('documents.update', $document) }}" method="POST" enctype="multipart/form-data" id="editForm">
                                @csrf
                                @method('PUT')

                                <div class="p-6">
                                    <!-- Document Name -->
                                    <div class="mb-6">
                                        <x-forms.input-field
                                            name="name"
                                            label="Nama Dokumen"
                                            type="text"
                                            :value="old('name', $document->name)"
                                            placeholder="Masukkan nama dokumen"
                                            required="true"
                                        />
                                    </div>

                                    <!-- Description -->
                                    <div class="mb-6">
                                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                            Keterangan
                                        </label>
                                        <textarea 
                                            id="description" 
                                            name="description" 
                                            rows="4" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm"
                                            placeholder="Masukkan keterangan dokumen (pilihan)"
                                        >{{ old('description', $document->description) }}</textarea>
                                        @error('description')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Folder Selection -->
                                    <div class="mb-6">
                                        <label for="folder_id" class="block text-sm font-medium text-gray-700 mb-2">
                                            Folder Destinasi
                                        </label>
                                        <select id="folder_id" name="folder_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                                            <option value="">Folder Utama</option>
                                            @foreach($folders as $folder)
                                                <option value="{{ $folder->id }}" {{ old('folder_id', $document->folder_id) == $folder->id ? 'selected' : '' }}>
                                                    {{ $folder->path }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('folder_id')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- File Replacement -->
                                    <div class="mb-6">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Ganti Fail (Pilihan)
                                        </label>
                                        
                                        <!-- Current File Info -->
                                        <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                            <div class="flex items-center space-x-3">
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
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ $document->original_filename }}</p>
                                                    <p class="text-xs text-gray-500">{{ $document->file_size_human }} • {{ strtoupper($document->file_extension) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- File Upload Area -->
                                        <div id="dropZone" class="relative border-2 border-dashed border-gray-300 rounded-xl text-center hover:border-blue-400 hover:bg-blue-50 transition-all duration-300 cursor-pointer group" style="padding: 64px 48px !important;">
                                            <div id="dropZoneContent">
                                                <div class="mx-auto w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center group-hover:bg-blue-200 transition-colors" style="margin-bottom: 12px !important;">
                                                    <span class="material-icons text-2xl text-blue-600">cloud_upload</span>
                                                </div>
                                                <h3 class="text-lg font-semibold text-gray-900" style="margin-bottom: 12px !important;">Seret fail pengganti ke sini</h3>
                                                <p class="text-sm text-gray-500" style="margin-bottom: 32px !important;">atau klik untuk memilih dari komputer anda</p>
                                                <button type="button" class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">
                                                    <span class="material-icons text-lg mr-2">attach_file</span>
                                                    Pilih Fail
                                                </button>
                                            </div>
                                            
                                            <!-- New File Preview (hidden initially) -->
                                            <div id="filePreview" class="hidden">
                                                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                                                    <!-- File Info - Centered Layout -->
                                                    <div class="text-center" style="margin-bottom: 16px !important;">
                                                        <div class="mx-auto w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center" style="margin-bottom: 12px !important;">
                                                            <span id="fileIcon" class="material-icons text-2xl text-blue-600">insert_drive_file</span>
                                                        </div>
                                                        <h4 id="fileName" class="text-sm font-semibold text-gray-900" style="margin-bottom: 4px !important;"></h4>
                                                        <p id="fileSize" class="text-xs text-gray-500"></p>
                                                    </div>
                                                    
                                                    <!-- Action Button -->
                                                    <div class="text-center">
                                                        <button type="button" onclick="clearFile()" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">
                                                            <span class="material-icons text-sm mr-2">close</span>
                                                            Buang Fail
                                                    </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <input type="file" id="fileInput" name="file" class="hidden" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif">
                                        
                                        @error('file')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        
                                        <p class="mt-2 text-xs text-gray-500">
                                            Jenis fail yang disokong: PDF, Word, Excel, PowerPoint, Teks, Gambar (JPG, PNG, GIF). Saiz maksimum: 50MB
                                        </p>
                                        
                                        <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                                            <div class="flex">
                                                <span class="material-icons text-yellow-400 text-sm mr-2">warning</span>
                                                <p class="text-xs text-yellow-800">
                                                    Mengganti fail akan mencipta versi baru dokumen. Fail lama akan disimpan dalam sejarah versi.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-lg flex justify-end space-x-3">
                                    <a href="{{ route('documents.show', $document) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                        Batal
                                    </a>
                                    <button type="submit" id="submitBtn" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span class="material-icons text-sm mr-2">save</span>
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Document Info Sidebar -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Maklumat Dokumen</h3>
                            
                            <!-- Document Preview -->
                            <div class="mb-4">
                                @if($document->isImage())
                                    <img src="{{ $document->preview_url }}" alt="{{ $document->name }}" class="w-full h-32 object-cover rounded-lg border border-gray-200">
                                @else
                                    <div class="w-full h-32 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <span class="material-icons text-4xl text-gray-400">
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
                                    <dd class="text-sm text-gray-900">{{ strtoupper($document->file_extension) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Versi Semasa</dt>
                                    <dd class="text-sm text-gray-900">{{ $document->version }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Dicipta</dt>
                                    <dd class="text-sm text-gray-900">{{ $document->created_at->format('d/m/Y H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Dikemaskini</dt>
                                    <dd class="text-sm text-gray-900">{{ $document->updated_at->format('d/m/Y H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Jumlah Muat Turun</dt>
                                    <dd class="text-sm text-gray-900">{{ $document->download_count }}</dd>
                                </div>
                            </dl>

                            <!-- Quick Actions -->
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Tindakan Pantas</h4>
                                <div class="space-y-2">
                                    <a href="{{ route('documents.show', $document) }}" class="block w-full text-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                        <span class="material-icons text-sm mr-2">visibility</span>
                                        Lihat Dokumen
                                    </a>
                                    <a href="{{ route('documents.download', $document) }}" class="block w-full text-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                        <span class="material-icons text-sm mr-2">download</span>
                                        Muat Turun
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                            </div>
                        </div>
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
        // File upload handling (similar to create.blade.php)
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('fileInput');
        const dropZoneContent = document.getElementById('dropZoneContent');
        const filePreview = document.getElementById('filePreview');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');
        const fileIcon = document.getElementById('fileIcon');

        // Click to select file
        dropZone.addEventListener('click', () => {
            fileInput.click();
        });

        // Drag and drop events
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-blue-400', 'bg-blue-50');
        });

        dropZone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-blue-400', 'bg-blue-50');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-blue-400', 'bg-blue-50');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                handleFileSelect(files[0]);
            }
        });

        // File input change
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleFileSelect(e.target.files[0]);
            }
        });

        function handleFileSelect(file) {
            // Show file preview
            dropZoneContent.classList.add('hidden');
            filePreview.classList.remove('hidden');
            
            // Set file info
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            
            // Set file icon based on type
            const extension = file.name.split('.').pop().toLowerCase();
            fileIcon.textContent = getFileIcon(extension);
        }

        function clearFile() {
            fileInput.value = '';
            dropZoneContent.classList.remove('hidden');
            filePreview.classList.add('hidden');
        }

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

        function getFileIcon(extension) {
            const icons = {
                'pdf': 'picture_as_pdf',
                'doc': 'description',
                'docx': 'description',
                'xls': 'table_chart',
                'xlsx': 'table_chart',
                'ppt': 'slideshow',
                'pptx': 'slideshow',
                'txt': 'text_snippet',
                'jpg': 'image',
                'jpeg': 'image',
                'png': 'image',
                'gif': 'image'
            };
            
            return icons[extension] || 'insert_drive_file';
        }

        // Form submission handling
        document.getElementById('editForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="material-icons text-sm mr-2 animate-spin">refresh</span>Menyimpan...';
        });
    </script>
</body>
</html>
