@props([
    'id' => '',
    'title' => '',
    'message' => '',
    'icon' => 'info',
    'iconColor' => 'text-blue-600',
    'iconBg' => 'bg-blue-100',
    'confirmText' => 'Confirm',
    'confirmColor' => 'bg-blue-600 hover:bg-blue-700',
    'cancelText' => 'Batal',
    'showTextarea' => false,
    'textareaLabel' => '',
    'textareaPlaceholder' => ''
])

<!-- Confirmation Modal -->
<div id="{{ $id }}Modal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-content">
            <div class="modal-icon-container {{ $iconBg }}">
                <span class="material-icons {{ $iconColor }} text-xl">{{ $icon }}</span>
            </div>
            <div class="modal-body">
                <h3 class="modal-title">{{ $title }}</h3>
                <p class="modal-message">
                    {!! $message !!}
                </p>

                @if($showTextarea)
                <div class="mb-4">
                    <label for="{{ $id }}Reason" class="modal-textarea-label">{{ $textareaLabel }}:</label>
                    <textarea id="{{ $id }}Reason" name="reason" rows="3"
                              class="modal-textarea"
                              placeholder="{{ $textareaPlaceholder }}"></textarea>
                </div>
                @endif

                {{ $slot }}
            </div>
            <div class="modal-actions">
                <button onclick="hide{{ ucfirst($id) }}Modal()" class="modal-btn-cancel">
                    {{ $cancelText }}
                </button>
                <form id="{{ $id }}Form" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="modal-btn-confirm {{ $confirmColor }}">
                        {{ $confirmText }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
