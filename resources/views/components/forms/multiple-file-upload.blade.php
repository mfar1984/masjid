@props([
    'label' => '',
    'name' => '',
    'accept' => '',
    'required' => false,
    'error' => null,
    'help' => null,
    'maxSize' => '5MB',
    'allowedTypes' => 'PDF, PNG, JPEG, JPG',
    'maxFiles' => 5
])

<div class="mb-4">
    @if($label)
        <label for="{{ $name }}" class="form-label text-gray-700 mb-2 block">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <div class="relative">
        <!-- Hidden file input for multiple files -->
        <input 
            type="file"
            id="{{ $name }}"
            name="{{ $name }}[]"
            accept=".pdf,.png,.jpeg,.jpg"
            multiple
            class="hidden"
            {{ $required ? 'required' : '' }}
            onchange="handleMultipleFileSelect(this, '{{ $name }}')"
        />
        
        <!-- Custom file upload area -->
        <div 
            id="{{ $name }}-upload-area"
            class="border-2 border-dashed border-gray-300 rounded-sm p-6 text-center cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition-colors duration-200 {{ $error ? 'border-red-500 bg-red-50' : '' }}"
            onclick="document.getElementById('{{ $name }}').click()"
        >
            <div class="flex flex-col items-center">
                <span class="material-icons text-gray-400 text-2xl mb-2">cloud_upload</span>
                <p class="text-sm text-gray-600 mb-1">
                    <span class="font-medium text-blue-600">Klik untuk pilih fail</span> atau seret dan lepas di sini
                </p>
                <p class="text-2xs text-gray-500 mb-1">
                    {{ $allowedTypes }} (Maksimum {{ $maxSize }} setiap fail)
                </p>
                <p class="text-2xs text-gray-400">
                    Maksimum {{ $maxFiles }} fail boleh dimuat naik
                </p>
            </div>
        </div>
        
        <!-- Files preview area -->
        <div id="{{ $name }}-previews" class="mt-3 space-y-2"></div>
        
        <!-- File counter -->
        <div id="{{ $name }}-counter" class="hidden mt-2 text-2xs text-gray-500 text-center">
            <span id="{{ $name }}-count">0</span> / {{ $maxFiles }} fail dipilih
        </div>
    </div>
    
    @if($error)
        <p class="text-red-500 text-2xs mt-1">{{ $error }}</p>
    @endif
    
    @if($help)
        <p class="text-gray-500 text-2xs mt-1">{{ $help }}</p>
    @endif
</div>

<script>
    let selectedFiles_{{ str_replace('-', '_', $name) }} = [];
    const maxFiles_{{ str_replace('-', '_', $name) }} = {{ $maxFiles }};
    
    function handleMultipleFileSelect(input, fieldName) {
        const files = Array.from(input.files);
        const uploadArea = document.getElementById(fieldName + '-upload-area');
        const previewsContainer = document.getElementById(fieldName + '-previews');
        const counter = document.getElementById(fieldName + '-counter');
        const countSpan = document.getElementById(fieldName + '-count');
        
        // Clear previous selections
        selectedFiles_{{ str_replace('-', '_', $name) }} = [];
        previewsContainer.innerHTML = '';
        
        // Check file limit
        if (files.length > maxFiles_{{ str_replace('-', '_', $name) }}) {
            alert(`Maksimum ${maxFiles_{{ str_replace('-', '_', $name) }}} fail sahaja dibenarkan.`);
            input.value = '';
            return;
        }
        
        // Process each file
        files.forEach((file, index) => {
            // Validate file size (5MB = 5 * 1024 * 1024 bytes)
            if (file.size > 5 * 1024 * 1024) {
                alert(`Fail "${file.name}" terlalu besar. Maksimum 5MB.`);
                return;
            }
            
            // Validate file type
            const allowedTypes = ['pdf', 'png', 'jpeg', 'jpg'];
            const fileExtension = file.name.split('.').pop().toLowerCase();
            if (!allowedTypes.includes(fileExtension)) {
                alert(`Fail "${file.name}" tidak dibenarkan. Hanya ${allowedTypes.join(', ').toUpperCase()} sahaja.`);
                return;
            }
            
            // Add to selected files
            selectedFiles_{{ str_replace('-', '_', $name) }}.push(file);
            
            // Create preview
            createFilePreview(file, index, fieldName);
        });
        
        // Update UI
        if (selectedFiles_{{ str_replace('-', '_', $name) }}.length > 0) {
            uploadArea.classList.add('border-green-400', 'bg-green-50');
            uploadArea.classList.remove('border-gray-300', 'border-red-500', 'bg-red-50');
            
            const uploadText = uploadArea.querySelector('p');
            uploadText.innerHTML = `<span class="font-medium text-green-600">${selectedFiles_{{ str_replace('-', '_', $name) }}.length} fail dipilih</span> - klik untuk tambah lagi`;
            
            counter.classList.remove('hidden');
            countSpan.textContent = selectedFiles_{{ str_replace('-', '_', $name) }}.length;
        } else {
            resetUploadArea(fieldName);
        }
    }
    
    function createFilePreview(file, index, fieldName) {
        const previewsContainer = document.getElementById(fieldName + '-previews');
        
        const preview = document.createElement('div');
        preview.className = 'p-3 bg-gray-50 rounded-sm border border-gray-200';
        preview.id = `${fieldName}-preview-${index}`;
        
        preview.innerHTML = `
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <span class="material-icons text-${getFileTypeColor(file.name)} mr-2">${getFileTypeIcon(file.name)}</span>
                    <div>
                        <p class="text-sm font-medium text-gray-900">${file.name}</p>
                        <p class="text-2xs text-gray-500">${formatFileSize(file.size)}</p>
                    </div>
                </div>
                <button 
                    type="button" 
                    onclick="removeFileFromSelection(${index}, '${fieldName}')"
                    class="text-red-600 hover:text-red-800 p-1"
                >
                    <span class="material-icons text-sm">close</span>
                </button>
            </div>
        `;
        
        previewsContainer.appendChild(preview);
    }
    
    function removeFileFromSelection(index, fieldName) {
        // Remove from selected files array
        selectedFiles_{{ str_replace('-', '_', $name) }}.splice(index, 1);
        
        // Remove preview element
        const preview = document.getElementById(`${fieldName}-preview-${index}`);
        if (preview) {
            preview.remove();
        }
        
        // Update file input
        const input = document.getElementById(fieldName);
        const dt = new DataTransfer();
        selectedFiles_{{ str_replace('-', '_', $name) }}.forEach(file => dt.items.add(file));
        input.files = dt.files;
        
        // Update UI
        const counter = document.getElementById(fieldName + '-counter');
        const countSpan = document.getElementById(fieldName + '-count');
        
        if (selectedFiles_{{ str_replace('-', '_', $name) }}.length > 0) {
            countSpan.textContent = selectedFiles_{{ str_replace('-', '_', $name) }}.length;
            
            const uploadArea = document.getElementById(fieldName + '-upload-area');
            const uploadText = uploadArea.querySelector('p');
            uploadText.innerHTML = `<span class="font-medium text-green-600">${selectedFiles_{{ str_replace('-', '_', $name) }}.length} fail dipilih</span> - klik untuk tambah lagi`;
        } else {
            resetUploadArea(fieldName);
        }
        
        // Re-index remaining previews
        const previewsContainer = document.getElementById(fieldName + '-previews');
        const remainingPreviews = previewsContainer.children;
        Array.from(remainingPreviews).forEach((preview, newIndex) => {
            preview.id = `${fieldName}-preview-${newIndex}`;
            const removeButton = preview.querySelector('button');
            removeButton.setAttribute('onclick', `removeFileFromSelection(${newIndex}, '${fieldName}')`);
        });
    }
    
    function resetUploadArea(fieldName) {
        const uploadArea = document.getElementById(fieldName + '-upload-area');
        const counter = document.getElementById(fieldName + '-counter');
        
        uploadArea.classList.remove('border-green-400', 'bg-green-50', 'border-red-500', 'bg-red-50');
        uploadArea.classList.add('border-gray-300');
        
        const uploadText = uploadArea.querySelector('p');
        uploadText.innerHTML = '<span class="font-medium text-blue-600">Klik untuk pilih fail</span> atau seret dan lepas di sini';
        
        counter.classList.add('hidden');
    }
    
    function getFileTypeIcon(filename) {
        const extension = filename.split('.').pop().toLowerCase();
        switch (extension) {
            case 'pdf': return 'picture_as_pdf';
            case 'png':
            case 'jpeg':
            case 'jpg': return 'image';
            default: return 'description';
        }
    }
    
    function getFileTypeColor(filename) {
        const extension = filename.split('.').pop().toLowerCase();
        switch (extension) {
            case 'pdf': return 'red-600';
            case 'png':
            case 'jpeg':
            case 'jpg': return 'green-600';
            default: return 'gray-600';
        }
    }
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    // Drag and drop functionality
    document.addEventListener('DOMContentLoaded', function() {
        const uploadArea = document.getElementById('{{ $name }}-upload-area');
        const input = document.getElementById('{{ $name }}');
        
        if (uploadArea && input) {
            uploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                uploadArea.classList.add('border-blue-400', 'bg-blue-50');
            });
            
            uploadArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                uploadArea.classList.remove('border-blue-400', 'bg-blue-50');
            });
            
            uploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                uploadArea.classList.remove('border-blue-400', 'bg-blue-50');
                
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    input.files = files;
                    handleMultipleFileSelect(input, '{{ $name }}');
                }
            });
        }
    });
</script>
