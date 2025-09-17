@props([
    'label' => '',
    'name' => '',
    'value' => '',
    'placeholder' => 'Pilih tarikh',
    'required' => false,
    'readonly' => false,
    'disabled' => false,
    'error' => null,
    'help' => null,
    'minDate' => null,
    'maxDate' => null
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
        <input
            type="date"
            id="{{ $name }}"
            name="{{ $name }}"
            value="{{ old($name, $value ? (is_string($value) ? $value : $value->format('Y-m-d')) : '') }}"
            placeholder="{{ $placeholder }}"
            class="form-input w-full px-3 py-2 pr-10 border border-gray-300 rounded-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-gray-400 text-gray-900 bg-white {{ $readonly ? 'bg-gray-100 cursor-not-allowed' : '' }} {{ $error ? 'border-red-500' : '' }}"
            {{ $required ? 'required' : '' }}
            {{ $readonly ? 'readonly' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $minDate ? "min={$minDate}" : '' }}
            {{ $maxDate ? "max={$maxDate}" : '' }}
        />
        
        <!-- Calendar Icon -->
        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
            <span class="material-icons text-gray-500 text-sm">calendar_today</span>
        </div>
    </div>
    
    @if($error)
        <p class="text-red-500 text-2xs mt-1">{{ $error }}</p>
    @endif
    
    @if($help)
        <p class="text-gray-500 text-2xs mt-1">{{ $help }}</p>
    @endif
</div>

<style>
/* Custom white date picker styling */
input[type="date"] {
    background-color: #ffffff !important;
    color: #111827 !important;
}

input[type="date"]::-webkit-calendar-picker-indicator {
    opacity: 0;
    position: absolute;
    right: 0;
    width: 40px;
    height: 100%;
    cursor: pointer;
    background-color: transparent;
}

input[type="date"]::-webkit-inner-spin-button,
input[type="date"]::-webkit-clear-button {
    display: none;
}

input[type="date"]::-webkit-datetime-edit {
    background-color: #ffffff;
    color: #111827;
}

input[type="date"]::-webkit-datetime-edit-text {
    color: #6B7280;
    padding: 0 0.25rem;
    background-color: transparent;
}

input[type="date"]::-webkit-datetime-edit-month-field,
input[type="date"]::-webkit-datetime-edit-day-field,
input[type="date"]::-webkit-datetime-edit-year-field {
    color: #111827;
    background-color: #ffffff;
    border: none;
}

input[type="date"]:focus {
    background-color: #ffffff !important;
}

input[type="date"]:focus::-webkit-datetime-edit {
    background-color: #ffffff;
}

input[type="date"]:focus::-webkit-datetime-edit-text {
    color: #3B82F6;
    background-color: transparent;
}

input[type="date"]:focus::-webkit-datetime-edit-month-field,
input[type="date"]:focus::-webkit-datetime-edit-day-field,
input[type="date"]:focus::-webkit-datetime-edit-year-field {
    background-color: #ffffff;
    color: #111827;
}

/* Firefox styling */
input[type="date"] {
    -moz-appearance: textfield;
    background-color: #ffffff !important;
}

/* Placeholder styling when empty */
input[type="date"]:invalid {
    color: #9CA3AF;
    background-color: #ffffff !important;
}

input[type="date"]:focus:invalid {
    color: #111827;
    background-color: #ffffff !important;
}

/* Ensure white background in all states */
input[type="date"]:hover {
    background-color: #ffffff !important;
}

input[type="date"]:active {
    background-color: #ffffff !important;
}
</style>
