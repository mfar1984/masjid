<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muat Naik Dokumen - E-Masjid</title>

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
                                <span class="material-icons text-2xl text-blue-600">cloud_upload</span>
                                <h1 class="text-xl font-semibold text-gray-900">Muat Naik Dokumen</h1>
                            </div>
                        </div>
                        
                        <!-- Action Button -->
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('documents.index', ['folder' => $currentFolder?->id]) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm">
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
                                    <a href="{{ route('documents.index', array_merge(request()->query(), ['extension' => 'pdf'])) }}" class="group flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                                        <span class="material-icons text-lg mr-3 text-red-500 group-hover:text-red-600">picture_as_pdf</span>
                                        <span>PDF</span>
                                    </a>
                                    <a href="{{ route('documents.index', array_merge(request()->query(), ['extension' => 'doc,docx'])) }}" class="group flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                                        <span class="material-icons text-lg mr-3 text-blue-500 group-hover:text-blue-600">description</span>
                                        <span>Word</span>
                                    </a>
                                    <a href="{{ route('documents.index', array_merge(request()->query(), ['extension' => 'xls,xlsx'])) }}" class="group flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                                        <span class="material-icons text-lg mr-3 text-green-500 group-hover:text-green-600">table_chart</span>
                                        <span>Excel</span>
                                    </a>
                                    <a href="{{ route('documents.index', array_merge(request()->query(), ['extension' => 'ppt,pptx'])) }}" class="group flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                                        <span class="material-icons text-lg mr-3 text-orange-500 group-hover:text-orange-600">slideshow</span>
                                        <span>PowerPoint</span>
                                    </a>
                                    <a href="{{ route('documents.index', array_merge(request()->query(), ['extension' => 'jpg,jpeg,png,gif'])) }}" class="group flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                                        <span class="material-icons text-lg mr-3 text-purple-500 group-hover:text-purple-600">image</span>
                                        <span>Gambar</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Quick Upload Info -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-xs font-semibold text-gray-700 mb-3">Maklumat Upload</h4>
                                <div class="space-y-2 text-xs text-gray-600">
                                    <div class="flex items-center space-x-2">
                                        <span class="material-icons text-xs text-blue-500">cloud_upload</span>
                                        <span>Saiz maksimum: 50MB</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="material-icons text-xs text-green-500">check_circle</span>
                                        <span>Format pelbagai disokong</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="material-icons text-xs text-purple-500">security</span>
                                        <span>Upload selamat & terjamin</span>
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
                                        @if($currentFolder)
                                            <span class="material-icons text-gray-300 text-sm mx-2">chevron_right</span>
                                            <li class="inline-flex items-center">
                                                <a href="{{ route('documents.index', ['folder' => $currentFolder->id]) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:bg-blue-50 px-2 py-1 rounded-md transition-all duration-200">
                                                    {{ $currentFolder->name }}
                                                </a>
                                            </li>
                                        @endif
                                        <span class="material-icons text-gray-300 text-sm mx-2">chevron_right</span>
                                        <li class="inline-flex items-center">
                                            <span class="text-sm font-medium text-gray-900 px-2 py-1 bg-gray-100 rounded-md">Muat Naik</span>
                                        </li>
                                    </ol>
                                </nav>

                                <!-- Page Info -->
                                <div>
                                    <p class="text-sm text-gray-500">
                                        @if($currentFolder)
                                            Muat naik dokumen ke folder: <span class="font-medium text-gray-700">{{ $currentFolder->name }}</span>
                                        @else
                                            Muat naik dokumen ke folder utama
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Upload Content -->
                        <div class="p-6">
                            <div class="max-w-4xl mx-auto">
                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                    <!-- Upload Form -->
                                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                            <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                                @csrf
                                @if($currentFolder)
                                    <input type="hidden" name="folder_id" value="{{ $currentFolder->id }}">
                                @endif

                                <div class="p-6">
                                    <!-- File Upload Area -->
                                    <div class="mb-8">
                                        <label class="block text-sm font-semibold text-gray-700 mb-4">
                                            Upload Dokumen <span class="text-red-500">*</span>
                                        </label>
                                        
                                        <!-- Enhanced Drag & Drop Area -->
                                        <div id="dropZone" class="relative border-2 border-dashed border-gray-300 rounded-xl text-center hover:border-blue-400 hover:bg-blue-50 transition-all duration-300 cursor-pointer group" style="padding: 64px 48px !important;">
                                            <div id="dropZoneContent">
                                                <div class="mx-auto w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center group-hover:bg-blue-200 transition-colors" style="margin-bottom: 12px !important;">
                                                    <span class="material-icons text-2xl text-blue-600">cloud_upload</span>
                                                </div>
                                                <h3 class="text-lg font-semibold text-gray-900" style="margin-bottom: 12px !important;">Seret fail ke sini</h3>
                                                <p class="text-sm text-gray-500" style="margin-bottom: 32px !important;">atau klik untuk memilih dari komputer anda</p>
                                                <button type="button" class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">
                                                    <span class="material-icons text-lg mr-2">attach_file</span>
                                                    Pilih Fail
                                                </button>
                                            </div>
                                    
                                            <!-- File Preview (hidden initially) -->
                                            <div id="filePreview" class="hidden">
                                                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                                                    <!-- File Info - Centered Layout -->
                                                    <div class="text-center" style="margin-bottom: 16px !important;">
                                                        <div class="mx-auto w-16 h-16 bg-blue-50 rounded-xl" style="display: flex !important; align-items: center !important; justify-content: center !important; margin-bottom: 16px !important;">
                                                            <span id="fileIcon" class="material-icons text-3xl text-blue-500" style="line-height: 1 !important;">insert_drive_file</span>
                                                        </div>
                                                        <h4 id="fileName" class="text-sm font-semibold text-gray-900 break-words" style="margin-bottom: 4px !important; text-align: center !important;"></h4>
                                                        <p id="fileSize" class="text-xs text-gray-500" style="text-align: center !important;"></p>
                                                    </div>
                                                    
                                                    <!-- Action Button -->
                                                    <div style="display: flex !important; justify-content: center !important; margin-bottom: 16px !important;">
                                                        <button type="button" onclick="clearFile()" class="inline-flex items-center px-4 py-2 text-xs font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                                            <span class="material-icons text-sm mr-1" style="line-height: 1 !important;">close</span>
                                                            Buang Fail
                                                        </button>
                                                    </div>
                                                    
                                                    <!-- Upload Progress -->
                                                    <div id="uploadProgress" class="hidden">
                                                        <div class="bg-gray-200 rounded-full h-2 mb-2">
                                                            <div id="progressBar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                                                        </div>
                                                        <p id="progressText" class="text-xs text-gray-500 text-center">Memuat naik...</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <input type="file" id="fileInput" name="file" class="hidden" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif" required>
                                        
                                        @error('file')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        
                                        <div class="mt-3 text-xs text-gray-500 bg-gray-50 p-3 rounded-lg" style="display: flex !important; align-items: center !important; justify-content: center !important;">
                                            <span class="material-icons text-xs mr-1" style="line-height: 1 !important;">info</span>
                                            <span>Jenis fail yang disokong: PDF, Word, Excel, PowerPoint, Teks, Gambar (JPG, PNG, GIF). Saiz maksimum: 50MB</span>
                                        </div>
                                    </div>

                                    <!-- Document Name -->
                                    <div class="mb-6">
                                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Nama Dokumen <span class="text-red-500">*</span>
                                        </label>
                                        <input 
                                            type="text" 
                                            id="name" 
                                            name="name" 
                                            value="{{ old('name') }}" 
                                            placeholder="Masukkan nama dokumen"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all duration-200"
                                            required
                                        >
                                        @error('name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Description -->
                                    <div class="mb-6">
                                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Keterangan
                                        </label>
                                        <textarea 
                                            id="description" 
                                            name="description" 
                                            rows="4" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all duration-200 resize-none"
                                            placeholder="Masukkan keterangan dokumen (pilihan)"
                                        >{{ old('description') }}</textarea>
                                        @error('description')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Folder Selection -->
                                    <div class="mb-6">
                                        <label for="folder_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Folder Destinasi
                                        </label>
                                        <select id="folder_id" name="folder_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all duration-200">
                                            <option value="">Folder Utama</option>
                                            @foreach($folders as $folder)
                                                <option value="{{ $folder->id }}" {{ ($currentFolder && $currentFolder->id == $folder->id) || old('folder_id') == $folder->id ? 'selected' : '' }}>
                                                    {{ $folder->path }}
                                                    @if(auth()->user()->isSuperAdmin() && $folder->masjid)
                                                        ({{ $folder->masjid->name }})
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('folder_id')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-xl flex justify-end space-x-3">
                                    <a href="{{ route('documents.index', ['folder' => $currentFolder?->id]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
                                        Batal
                                    </a>
                                    <button type="submit" id="submitBtn" class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200">
                                        <span class="material-icons text-lg mr-2">cloud_upload</span>
                                        Muat Naik
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Sidebar Info -->
                    <div class="lg:col-span-1">
                        <div class="space-y-6">
                            <!-- Upload Tips -->
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                                <div class="flex items-center mb-4">
                                    <span class="material-icons text-blue-500 mr-2">tips_and_updates</span>
                                    <h3 class="text-sm font-semibold text-gray-900">Tips Muat Naik</h3>
                                </div>
                                <div class="space-y-3 text-sm text-gray-600">
                                    <div class="flex items-start space-x-2">
                                        <span class="material-icons text-xs mt-0.5 text-green-500">check_circle</span>
                                        <span>Pastikan nama fail tidak mengandungi aksara khas</span>
                                    </div>
                                    <div class="flex items-start space-x-2">
                                        <span class="material-icons text-xs mt-0.5 text-green-500">check_circle</span>
                                        <span>Gunakan nama dokumen yang jelas dan mudah difahami</span>
                                    </div>
                                    <div class="flex items-start space-x-2">
                                        <span class="material-icons text-xs mt-0.5 text-green-500">check_circle</span>
                                        <span>Tambah keterangan untuk memudahkan pencarian</span>
                                    </div>
                                    <div class="flex items-start space-x-2">
                                        <span class="material-icons text-xs mt-0.5 text-green-500">check_circle</span>
                                        <span>Pilih folder yang sesuai untuk organisasi yang baik</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Supported Formats -->
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                                <div class="flex items-center mb-4">
                                    <span class="material-icons text-purple-500 mr-2">file_present</span>
                                    <h3 class="text-sm font-semibold text-gray-900">Format Disokong</h3>
                                </div>
                                <div class="space-y-2 text-sm">
                                    <div class="flex items-center space-x-2">
                                        <span class="material-icons text-red-500 text-sm">picture_as_pdf</span>
                                        <span class="text-gray-600">PDF Documents</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="material-icons text-blue-500 text-sm">description</span>
                                        <span class="text-gray-600">Word (DOC, DOCX)</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="material-icons text-green-500 text-sm">table_chart</span>
                                        <span class="text-gray-600">Excel (XLS, XLSX)</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="material-icons text-orange-500 text-sm">slideshow</span>
                                        <span class="text-gray-600">PowerPoint (PPT, PPTX)</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="material-icons text-purple-500 text-sm">image</span>
                                        <span class="text-gray-600">Gambar (JPG, PNG, GIF)</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="material-icons text-gray-500 text-sm">text_snippet</span>
                                        <span class="text-gray-600">Text Files (TXT)</span>
                                    </div>
                                </div>
                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    <div class="text-xs text-gray-500" style="display: flex !important; align-items: center !important;">
                                        <span class="material-icons text-xs mr-1" style="line-height: 1 !important;">info</span>
                                        <span>Saiz maksimum: 50MB per fail</span>
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

    <script>
        // File upload handling
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('fileInput');
        const dropZoneContent = document.getElementById('dropZoneContent');
        const filePreview = document.getElementById('filePreview');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');
        const fileIcon = document.getElementById('fileIcon');
        const nameInput = document.querySelector('input[name="name"]');

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
            
            // Auto-fill document name if empty
            if (!nameInput.value) {
                const nameWithoutExtension = file.name.replace(/\.[^/.]+$/, "");
                nameInput.value = nameWithoutExtension;
            }
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

        // Form submission with progress
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const uploadProgress = document.getElementById('uploadProgress');
            
            // Show progress
            uploadProgress.classList.remove('hidden');
            submitBtn.disabled = true;
            
            // Simulate progress (in real implementation, use XMLHttpRequest for actual progress)
            let progress = 0;
            const progressInterval = setInterval(() => {
                progress += Math.random() * 30;
                if (progress > 90) progress = 90;
                
                document.getElementById('progressBar').style.width = progress + '%';
                document.getElementById('progressText').textContent = `Memuat naik... ${Math.round(progress)}%`;
                
                if (progress >= 90) {
                    clearInterval(progressInterval);
                    document.getElementById('progressText').textContent = 'Menyelesaikan...';
                }
            }, 200);
        });
    </script>

    <x-footer />
</body>
</html>
