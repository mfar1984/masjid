@props([
    'label' => '',
    'name' => '',
    'accept' => '',
    'required' => false,
    'error' => null,
    'help' => null,
    'maxSize' => '5MB',
    'allowedTypes' => 'PDF, PNG, JPEG, JPG'
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
        <!-- Hidden file input -->
        <input 
            type="file"
            id="{{ $name }}"
            name="{{ $name }}"
            accept=".pdf,.png,.jpeg,.jpg"
            class="hidden"
            {{ $required ? 'required' : '' }}
            onchange="handleFileSelect(this, '{{ $name }}')"
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
                <p class="text-2xs text-gray-500">
                    {{ $allowedTypes }} (Maksimum {{ $maxSize }})
                </p>
            </div>
        </div>
        
        <!-- File preview area -->
        <div id="{{ $name }}-preview" class="hidden mt-3 p-3 bg-gray-50 rounded-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <span class="material-icons text-green-600 mr-2">description</span>
                    <div>
                        <p id="{{ $name }}-filename" class="text-sm font-medium text-gray-900"></p>
                        <p id="{{ $name }}-filesize" class="text-2xs text-gray-500"></p>
                    </div>
                </div>
                <button 
                    type="button" 
                    onclick="removeFile('{{ $name }}')"
                    class="text-red-600 hover:text-red-800 p-1"
                >
                    <span class="material-icons text-sm">close</span>
                </button>
            </div>
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
    function handleFileSelect(input, fieldName) {
        const file = input.files[0];
        const uploadArea = document.getElementById(fieldName + '-upload-area');
        const preview = document.getElementById(fieldName + '-preview');
        const filename = document.getElementById(fieldName + '-filename');
        const filesize = document.getElementById(fieldName + '-filesize');
        
        if (file) {
            // Show preview
            preview.classList.remove('hidden');
            uploadArea.classList.add('border-green-400', 'bg-green-50');
            uploadArea.classList.remove('border-gray-300', 'border-red-500', 'bg-red-50');
            
            // Update file info
            filename.textContent = file.name;
            filesize.textContent = formatFileSize(file.size);
            
            // Update upload area text
            const uploadText = uploadArea.querySelector('p');
            uploadText.innerHTML = '<span class="font-medium text-green-600">Fail dipilih</span> - klik untuk tukar';
        }
    }
    
    function removeFile(fieldName) {
        const input = document.getElementById(fieldName);
        const uploadArea = document.getElementById(fieldName + '-upload-area');
        const preview = document.getElementById(fieldName + '-preview');
        
        // Clear input
        input.value = '';
        
        // Hide preview
        preview.classList.add('hidden');
        
        // Reset upload area
        uploadArea.classList.remove('border-green-400', 'bg-green-50', 'border-red-500', 'bg-red-50');
        uploadArea.classList.add('border-gray-300');
        
        // Reset upload area text
        const uploadText = uploadArea.querySelector('p');
        uploadText.innerHTML = '<span class="font-medium text-blue-600">Klik untuk pilih fail</span> atau seret dan lepas di sini';
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
        const uploadAreas = document.querySelectorAll('[id$="-upload-area"]');
        
        uploadAreas.forEach(area => {
            const fieldName = area.id.replace('-upload-area', '');
            const input = document.getElementById(fieldName);
            
            area.addEventListener('dragover', function(e) {
                e.preventDefault();
                area.classList.add('border-blue-400', 'bg-blue-50');
            });
            
            area.addEventListener('dragleave', function(e) {
                e.preventDefault();
                area.classList.remove('border-blue-400', 'bg-blue-50');
            });
            
            area.addEventListener('drop', function(e) {
                e.preventDefault();
                area.classList.remove('border-blue-400', 'bg-blue-50');
                
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    input.files = files;
                    handleFileSelect(input, fieldName);
                }
            });
        });
    });
</script>
