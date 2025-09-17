@props([
    'label' => '',
    'name' => '',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'readonly' => false,
    'disabled' => false,
    'error' => null,
    'help' => null,
    'maxlength' => null,
    'rows' => 3
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
    
    @if($type === 'textarea')
        <textarea 
            id="{{ $name }}"
            name="{{ $name }}"
            rows="{{ $rows }}"
            placeholder="{{ $placeholder }}"
            class="form-input w-full px-3 py-2 border border-gray-300 rounded-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-gray-400 text-gray-900 {{ $readonly ? 'bg-gray-100 cursor-not-allowed' : '' }} {{ $error ? 'border-red-500' : '' }}"
            {{ $required ? 'required' : '' }}
            {{ $readonly ? 'readonly' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $maxlength ? "maxlength={$maxlength}" : '' }}
        >{{ old($name, $value) }}</textarea>
    @elseif($type === 'select')
        <select 
            id="{{ $name }}"
            name="{{ $name }}"
            class="form-input w-full px-3 py-2 border border-gray-300 rounded-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-gray-400 text-gray-900 {{ $readonly ? 'bg-gray-100 cursor-not-allowed' : '' }} {{ $error ? 'border-red-500' : '' }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
        >
            {{ $slot }}
        </select>
    @else
        <input 
            type="{{ $type }}"
            id="{{ $name }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            class="form-input w-full px-3 py-2 border border-gray-300 rounded-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-gray-400 text-gray-900 {{ $readonly ? 'bg-gray-100 cursor-not-allowed' : '' }} {{ $error ? 'border-red-500' : '' }}"
            {{ $required ? 'required' : '' }}
            {{ $readonly ? 'readonly' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $maxlength ? "maxlength={$maxlength}" : '' }}
        />
    @endif
    
    @if($error)
        <p class="text-red-500 text-2xs mt-1">{{ $error }}</p>
    @endif
    
    @if($help)
        <p class="text-gray-500 text-2xs mt-1">{{ $help }}</p>
    @endif
</div>
