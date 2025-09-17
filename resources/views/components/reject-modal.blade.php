@props([
    'modalId' => 'rejectModal',
    'title' => 'Tolak Permohonan Masjid',
    'message' => 'Adakah anda pasti mahu menolak permohonan masjid ini?',
    'confirmText' => 'Ya, Tolak',
    'cancelText' => 'Batal'
])

<!-- Reject Modal -->
<div id="{{ $modalId }}" class="fixed inset-0 overflow-y-auto h-full w-full hidden z-50" style="background-color: rgba(0, 0, 0, 0.3);">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <!-- Icon -->
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <span class="material-icons text-red-600 text-xl">cancel</span>
            </div>
            
            <!-- Title -->
            <h3 class="text-lg font-medium text-gray-900 mt-4">{{ $title }}</h3>
            
            <!-- Message -->
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">{{ $message }}</p>
                <p class="text-sm font-medium text-gray-900 mt-2" id="rejectMasjidName"></p>
                <p class="text-xs text-red-600 mt-2">
                    Masjid ini akan ditukar status kepada "Ditolak" dan tidak akan diluluskan.
                </p>
            </div>
            
            <!-- Reason Textarea -->
            <div class="mt-4 px-7">
                <label for="rejectReason" class="block text-sm font-medium text-gray-700 text-left mb-2">
                    Sebab Penolakan:
                </label>
                <textarea id="rejectReason"
                          name="reason"
                          rows="3"
                          class="modal-textarea"
                          placeholder="Sila nyatakan sebab penolakan..."
                          style="color: #000000 !important; background-color: #ffffff !important; background: #ffffff !important; -webkit-text-fill-color: #000000 !important; font-family: 'Poppins', sans-serif !important; font-size: 12px !important; border: 1px solid #d1d5db !important; padding: 8px 12px !important; width: 100% !important; border-radius: 6px !important;"></textarea>
            </div>
            
            <!-- Buttons -->
            <div class="flex items-center justify-center gap-3 mt-4">
                <button type="button" 
                        onclick="closeRejectModal()" 
                        class="px-4 py-2 bg-gray-300 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    {{ $cancelText }}
                </button>
                <form id="rejectForm" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        {{ $confirmText }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showRejectModal(id, nama) {
    document.getElementById('rejectMasjidName').textContent = nama;
    document.getElementById('rejectForm').action = `/senarai-masjid/${id}/reject`;

    // Clear previous reason and force text visibility
    const textarea = document.getElementById('rejectReason');
    textarea.value = '';

    // Force text visibility with JavaScript
    textarea.style.color = '#000000';
    textarea.style.backgroundColor = '#ffffff';
    textarea.style.background = '#ffffff';
    textarea.style.webkitTextFillColor = '#000000';
    textarea.style.fontFamily = 'Poppins, sans-serif';
    textarea.style.fontSize = '12px';
    textarea.style.border = '1px solid #d1d5db';
    textarea.style.padding = '8px 12px';
    textarea.style.width = '100%';
    textarea.style.borderRadius = '6px';

    document.getElementById('{{ $modalId }}').classList.remove('hidden');

    // Focus textarea after modal opens
    setTimeout(() => {
        textarea.focus();
        // Force text visibility again after focus
        textarea.style.color = '#000000';
        textarea.style.webkitTextFillColor = '#000000';
    }, 100);
}

function closeRejectModal() {
    document.getElementById('{{ $modalId }}').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('{{ $modalId }}').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});

// Force text visibility on textarea events
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('rejectReason');
    if (textarea) {
        // Force visibility on all events
        ['input', 'keyup', 'keydown', 'focus', 'blur', 'change'].forEach(event => {
            textarea.addEventListener(event, function() {
                this.style.color = '#000000';
                this.style.backgroundColor = '#ffffff';
                this.style.background = '#ffffff';
                this.style.webkitTextFillColor = '#000000';
            });
        });

        // Initial force
        textarea.style.color = '#000000';
        textarea.style.backgroundColor = '#ffffff';
        textarea.style.background = '#ffffff';
        textarea.style.webkitTextFillColor = '#000000';
    }
});
</script>
