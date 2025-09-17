@props([
    'modalId' => 'suspendModal',
    'title' => 'Gantung Masjid',
    'message' => 'Adakah anda pasti mahu menggantung masjid ini?',
    'confirmText' => 'Ya, Gantung',
    'cancelText' => 'Batal',
    'action' => 'suspend'
])

<!-- Suspend Modal -->
<div id="{{ $modalId }}" class="fixed inset-0 overflow-y-auto h-full w-full hidden z-50" style="background-color: rgba(0, 0, 0, 0.3);">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <!-- Icon -->
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-orange-100">
                <span class="material-icons text-orange-600">pause_circle</span>
            </div>
            
            <!-- Title -->
            <h3 class="text-lg font-medium text-gray-900 mt-4">{{ $title }}</h3>
            
            <!-- Message -->
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">{{ $message }}</p>
                <p class="text-sm font-medium text-gray-900 mt-2" id="suspendMasjidName"></p>
                <p class="text-xs text-orange-600 mt-2">
                    Masjid yang digantung tidak akan dapat diakses oleh pengguna.
                </p>
            </div>
            
            <!-- Buttons -->
            <div class="flex items-center justify-center gap-3 mt-4">
                <button type="button" 
                        onclick="closeSuspendModal()" 
                        class="px-4 py-2 bg-gray-300 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    {{ $cancelText }}
                </button>
                <form id="suspendForm" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        {{ $confirmText }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showSuspendModal(id, nama) {
    document.getElementById('suspendMasjidName').textContent = nama;
    document.getElementById('suspendForm').action = `/senarai-masjid/${id}/suspend`;
    document.getElementById('{{ $modalId }}').classList.remove('hidden');
}

function closeSuspendModal() {
    document.getElementById('{{ $modalId }}').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('{{ $modalId }}').addEventListener('click', function(e) {
    if (e.target === this) {
        closeSuspendModal();
    }
});
</script>
