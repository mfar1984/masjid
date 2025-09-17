@props([
    'name' => 'search',
    'value' => '',
    'placeholder' => 'Cari...',
    'icon' => 'search'
])

<div class="flex-1">
    <div class="relative">
        <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">{{ $icon }}</span>
        <input type="text"
               name="{{ $name }}"
               value="{{ $value }}"
               placeholder="{{ $placeholder }}"
               class="w-full h-[32px] pl-9 pr-3 py-1 border border-gray-300 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 text-gray-900" />
    </div>
</div>
