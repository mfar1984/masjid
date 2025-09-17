@props([
    'type' => 'button',
    'icon' => '',
    'color' => 'blue',
    'size' => 'md',
    'onclick' => ''
])

@php
$colorClasses = [
    'blue' => 'bg-blue-600 text-white hover:bg-blue-700',
    'red' => 'bg-red-100 text-red-700 hover:bg-red-200',
    'green' => 'bg-green-100 text-gray-700 hover:bg-green-200',
];

$sizeClasses = [
    'sm' => 'h-[28px] px-3 py-1 text-xs',
    'md' => 'h-[32px] px-4 py-1 text-xs',
    'lg' => 'h-[36px] px-5 py-2 text-sm',
];
@endphp

<button type="{{ $type }}"
        @if($onclick) onclick="{{ $onclick }}" @endif
        class="{{ $colorClasses[$color] ?? $colorClasses['blue'] }} {{ $sizeClasses[$size] ?? $sizeClasses['md'] }} rounded flex items-center justify-center min-w-[80px]"
        {{ $attributes }}>
    @if($icon)
        <span class="material-icons text-sm mr-1">{{ $icon }}</span>
    @endif
    {{ $slot }}
</button>
